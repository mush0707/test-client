<?php

namespace App\Repositories;

use App\Http\Requests\Api\Rewards\Store\RewardStoreDTO;
use App\Http\Requests\Api\Rewards\Update\RewardUpdateDTO;
use App\Models\Reward;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RewardsRepository
{
    public function store(RewardStoreDTO $payload): void
    {
        if ($this->existCheck(Str::slug($payload->getName()))) {
            throw new \InvalidArgumentException('reward with this name is already exists', ResponseAlias::HTTP_CONFLICT);
        }
        $reward = new Reward();
        $reward->name = $payload->getName();
        $reward->slug = Str::slug($payload->getName());
        $reward->points = $payload->getPoints();
        $reward->save();
    }

    /**
     * @param int $id
     * @param RewardUpdateDTO $payload
     * @return void
     *
     * $id can be like ObjectValue
     */
    public function update(int $id, RewardUpdateDTO $payload): void
    {
        $reward = $this->getById($id);
        if ($this->existCheck(Str::slug($payload->getName()), $id)) {
            throw new \InvalidArgumentException('reward with this name is already exists', ResponseAlias::HTTP_CONFLICT);
        }
        $reward->name = $payload->getName();
        $reward->slug = Str::slug($payload->getName());
        $reward->points = $payload->getPoints();
        $reward->save();
    }

    public function getById(int $id): Reward|\Throwable
    {
        return Reward::query()
            ->select([
                'id',
                'name',
                'points'
            ])
            ->findOrFail($id);
    }

    public function getBySlug(string $slug): Reward|\Throwable
    {
        return Reward::query()
            ->select([
                'id',
                'name',
                'points'
            ])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    private function existCheck(string $slug, int $idForUpdate = null): bool
    {
        $queryBuilder = Reward::query()->where('slug', $slug);
        if ($idForUpdate) {
            $queryBuilder->where('id', '!=', $idForUpdate);
        }
        return $queryBuilder->exists();
    }

    public function attachUser(int $rewardId, int $userId): void
    {
        if (DB::table('reward_users')
            ->where('reward_id', $rewardId)
            ->where('user_id', $userId)->exists()) {
            throw new \InvalidArgumentException('reward already attached to this user', ResponseAlias::HTTP_CONFLICT);
        }
        DB::table('reward_users')
            ->insert([
                'reward_id' => $rewardId,
                'user_id' => $userId
            ]);
    }

    public function delete(int $rewardId): void
    {
        try {
            $reward = $this->getById($rewardId);
        } catch (ModelNotFoundException $exception) {
            throw new \InvalidArgumentException('reward not found', ResponseAlias::HTTP_NOT_FOUND);
        }
        DB::table('reward_users')
            ->where('reward_id', $rewardId)
            ->delete();
        $reward->delete();
    }
}
