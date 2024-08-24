<?php

namespace Tests\Feature;

use App\Http\Requests\Api\Rewards\Store\RewardStoreDTO;
use App\Http\Requests\Api\Rewards\Update\RewardUpdateDTO;
use App\Http\Responses\Schemas\RewardSchema;
use Illuminate\Support\Str;
use Tests\TestCase;

class RewardsTest extends TestCase
{
    public function test_reward_store()
    {
        define('LARAVEL_START', microtime(true));
        $response = $this->reward_store(new RewardStoreDTO(
            'testing_reward',
            150
        ));
        $response->assertStatus(201);
        $response = $this->reward_store(new RewardStoreDTO(
            'testing_reward1',
            150
        ));
        $response->assertStatus(201);
        $response = $this->reward_store(new RewardStoreDTO(
            'testing_reward1',
            150
        ));
        $response->assertStatus(409);
    }

    public function test_reward_get_by_slug($slug = 'testing-reward'): RewardSchema
    {
        $response = $this->reward_get_by_slug('testing-reward988888');
        $response->assertStatus(404);
        $response = $this->reward_get_by_slug($slug);
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content['data']);
        $content = $content['data'];
        $this->assertIsNumeric($content['id']);
        $this->assertIsString($content['name']);
        $this->assertIsNumeric($content['points']);
        return new RewardSchema(
            $content['id'],
            $content['name'],
            $content['points'],
        );
    }

    public function test_reward_get_by_id(): RewardSchema
    {
        $response = $this->reward_get_by_id(999999999999);
        $response->assertStatus(404);

        $content = $this->test_reward_get_by_slug();
        $response = $this->reward_get_by_id($content->getId());
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content['data']);
        $content = $content['data'];
        $this->assertIsNumeric($content['id']);
        $this->assertIsString($content['name']);
        $this->assertIsNumeric($content['points']);
        return new RewardSchema(
            $content['id'],
            $content['name'],
            $content['points'],
        );
    }

    public function test_reward_update()
    {
        $content = $this->test_reward_get_by_slug();
        $response = $this->reward_update($content->getId(), new RewardUpdateDTO(
            'testing_reward',
            150
        ));
        $response->assertStatus(200);
        $response = $this->reward_update($content->getId(), new RewardUpdateDTO(
            'testing_reward1',
            150
        ));
        $response->assertStatus(409);
        $response = $this->reward_update(999999999, new RewardUpdateDTO(
            'testing_reward1',
            150
        ));
        $response->assertStatus(404);
    }

    public function test_reward_attach()
    {
        $content = $this->test_reward_get_by_slug();
        $response = $this->reward_attach($content->getId(), 1);
        $response->assertStatus(200);
        $response = $this->reward_attach($content->getId(), 1);
        $response->assertStatus(409);
        $response = $this->reward_attach(99999999, 1);
        $response->assertStatus(404);
        $response = $this->reward_attach($content->getId(), 9999999999);
        $response->assertStatus(404);
    }

    public function test_reward_delete()
    {
        $content = $this->test_reward_get_by_slug();
        $response = $this->reward_delete($content->getId());
        $response->assertStatus(204);
        $content = $this->test_reward_get_by_slug('testing-reward1');
        $response = $this->reward_delete($content->getId());
        $response->assertStatus(204);
        $response = $this->reward_delete($content->getId());
        $response->assertStatus(404);
    }

    public function reward_store(RewardStoreDTO $payload)
    {
        return $this->post('/api/rewards', [
            'name' => $payload->getName(),
            'points' => $payload->getPoints(),
        ]);
    }

    public function reward_get_by_slug(string $slug)
    {
        return $this->get('/api/rewards/bySlug/' . $slug);
    }

    public function reward_get_by_id(int $id)
    {
        return $this->get('/api/rewards/' . $id);
    }

    public function reward_update(int $rewardId, RewardUpdateDTO $payload)
    {
        return $this->put('/api/rewards/' . $rewardId, [
            'name' => $payload->getName(),
            'points' => $payload->getPoints(),
        ]);
    }

    public function reward_attach(int $rewardId, int $userId)
    {
        return $this->patch('/api/rewards/' . $rewardId .'/'.$userId);
    }

    public function reward_delete(int $rewardId)
    {
        return $this->delete('/api/rewards/' . $rewardId);
    }
}
