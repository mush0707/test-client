<?php

namespace App\Http\Responses\Schemas;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class RewardSchema
{
    public function __construct(
        #[OA\Property()]
        private int    $id,
        #[OA\Property()]
        private string $name,
        #[OA\Property()]
        private float  $points,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'points' => $this->points,
        ];
    }
}
