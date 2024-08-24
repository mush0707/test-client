<?php

namespace App\Http\Controllers\Api\Reward;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Responses\Schemas\RewardSchema;
use App\Repositories\RewardsRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class QueryController extends ApiController
{
    public function __construct(
        private RewardsRepository $repository
    )
    {
    }

    #[OA\Get(
        path: '/rewards/{rewardId}',
        operationId: "rewardById",
        summary: "Get reward by id",
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
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            ref: "#/components/schemas/RewardSchema"
        )
    )]
    public function getById(int $id): JsonResponse
    {
        try {
            $reward = $this->repository->getById($id);
        } catch (ModelNotFoundException $e) {
            return $this->errors([], $e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return $this->success((new RewardSchema(
            $reward->id,
            $reward->name,
            $reward->points,
        ))->toArray());
    }

    #[OA\Get(
        path: '/rewards/{slug}',
        operationId: "rewardBySlug",
        summary: "Get reward by slug",
        tags: ["Rewards"],
    )]
    #[OA\Parameter(
        name: "slug",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "string"
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'reward not found'
    )]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            ref: "#/components/schemas/RewardSchema"
        )
    )]
    public function getBySlug(string $slug): JsonResponse
    {
        try {
            $reward = $this->repository->getBySlug($slug);
        } catch (ModelNotFoundException $e) {
            return $this->errors([], $e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return $this->success((new RewardSchema(
            $reward->id,
            $reward->name,
            $reward->points,
        ))->toArray());
    }
}
