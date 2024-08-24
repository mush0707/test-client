<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Reward\CommandController as RewardCommandController;
use App\Http\Controllers\Api\Reward\QueryController as RewardQueryController;
// can be authorized by sanctum
Route::group([
    'prefix' => 'rewards',
], function () {
    Route::get('/{id}', [RewardQueryController::class, 'getById']);
    Route::get('/bySlug/{slug}', [RewardQueryController::class, 'getBySlug']);

    Route::post('/', [RewardCommandController::class, 'store']);
    Route::put('/{id}', [RewardCommandController::class, 'update']);
    Route::patch('/{reward_id}/{user_id}', [RewardCommandController::class, 'attachUser']);
    Route::delete('/{reward_id}', [RewardCommandController::class, 'delete']);
});
