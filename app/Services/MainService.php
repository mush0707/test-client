<?php

namespace App\Services;

use App\Enums\Http\Method;
use App\Helpers\Tokenable;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as Response;

// can be implemented interface
class MainService extends InternalService
{
    public function checkUserExists(int $id): bool
    {
        $response = $this->request(Method::GET, '/api/user/' . $id . '/check');
        return $response['data'];
    }

    public function attachReward(int $userId, int $rewardId): void
    {
        $this->request(Method::POST, '/api/user/rewards/' . $userId . '/' . $rewardId);
    }

    public function detachReward(int $rewardId): void
    {
        $this->request(Method::DELETE, '/api/user/rewards/' . $rewardId);
    }
}
