<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiClientResponse;

/**
 * @OA\Info(
 *    title="Rewards API",
 *    version="1.0.0"
 * ),
 * @OA\Server(
 *    url="http://192.168.49.2:30008/api",
 *    description="local"
 * )
 */
class ApiController extends Controller
{
    use ApiClientResponse;
}
