<?php

namespace App\Http\Requests\Api\Rewards\Update;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class RewardUpdateDTO
{
    public function __construct(
        #[OA\Property()]
        private string $name,
        #[OA\Property()]
        private float $points,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

}
