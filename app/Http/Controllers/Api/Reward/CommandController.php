<?php

namespace App\Http\Controllers\Api\Reward;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Rewards\Store\StoreRequest;
use App\Http\Requests\Api\Rewards\Update\UpdateRequest;
use App\Repositories\RewardsRepository;
use App\Services\MainService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class CommandController extends ApiController
{
    public function __construct(
        private readonly RewardsRepository $repository,
        private readonly MainService       $mainService
    )
    {
    }

    #[OA\Post(
        path: '/rewards',
        operationId: "storeReward",
        summary: "store reward",
        tags: ["Rewards"],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/RewardStoreDTO"
        )
    )]
    #[OA\Response(
        response: 409,
        description: 'Reward already exists'
    )]
    #[OA\Response(
        response: 201,
        description: 'OK'
    )]
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $this->repository->store($request->validated());
        } catch (\InvalidArgumentException $exception) {
            return $this->errors([], $exception->getMessage(), $exception->getCode());
        }
        return $this->created();
    }

    #[OA\Put(
        path: '/rewards',
        operationId: "updateReward",
        summary: "update reward",
        tags: ["Rewards"],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/RewardUpdateDTO"
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Reward not found'
    )]
    #[OA\Response(
        response: 409,
        description: 'Reward already exists'
    )]
    #[OA\Response(
        response: 200,
        description: 'OK'
    )]
    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        try {
            $this->repository->update($id, $request->validated());
        } catch (\InvalidArgumentException $exception) {
            return $this->errors([], $exception->getMessage(), $exception->getCode());
        } catch (ModelNotFoundException $exception) {
            return $this->errors([], $exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->success();
    }

    #[OA\Patch(
        path: '/rewards/{rewardId}/{userId}',
        operationId: "attachReward",
        summary: "attach reward",
        tags: ["Rewards"],
    )]
    #[OA\Parameter(
        name: "rewardId",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Parameter(
        name: "userId",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'user or reward not found'
    )]
    #[OA\Response(
        response: 409,
        description: 'reward already attached to this user'
    )]
    #[OA\Response(
        response: 200,
        description: 'OK'
    )]
    public function attachUser(int $rewardId, int $userId): JsonResponse
    {
        try {
            $this->repository->getById($rewardId); // for validation
            if (!$this->mainService->checkUserExists($userId)) {
                return $this->errors([], 'user not found', Response::HTTP_NOT_FOUND);
            }
            $this->repository->attachUser($rewardId, $userId);

            // can be running by queue
            $this->mainService->attachReward($userId, $rewardId);
        } catch (\InvalidArgumentException $exception) {
            return $this->errors([], $exception->getMessage(), $exception->getCode());
        } catch (ModelNotFoundException $exception) {
            return $this->errors([], $exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->success();
    }

    #[OA\Delete(
        path: '/rewards/{rewardId}',
        operationId: "deleteReward",
        summary: "delete reward",
        tags: ["Rewards"],
    )]
    #[OA\Parameter(
        name: "rewardId",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'reward not found'
    )]
    #[OA\Response(
        response: 204,
        description: 'OK'
    )]
    public function delete(int $rewardId): Response
    {
        try {
            $this->repository->delete($rewardId);

            // can be running by queue
            $this->mainService->detachReward($rewardId);
            return $this->destroyResponse();
        } catch (\InvalidArgumentException $exception) {
            return $this->errors([], $exception->getMessage(), $exception->getCode());
        }
    }
}
