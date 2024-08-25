<?php

namespace App\Services;

use App\Enums\Http\Method;
use App\Helpers\Tokenable;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as Response;

abstract class InternalService
{
    use Tokenable;
    private string $service;
    public function __construct()
    {
        $this->service = 'main';
    }

    protected function auth(): void
    {
        $response = Http::post(config('services.'.$this->service.'.url') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.'.$this->service.'.client_id'),
            'scope' => '*',
            'client_secret' => config('services.'.$this->service.'.client_secret'),
        ]);

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new \InvalidArgumentException($response->getBody(), $response->getStatusCode());
        }

        self::storeToken($response->getBody()->getContents());
    }

    protected function request(Method $method, string $url, array $data = [], bool $refreshToken = false): array
    {
        if(!self::tokenCheck()) {
            $this->auth();
        }
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . self::getToken(),
        ])->{$method->value}(config('services.'.$this->service.'.url') . $url, $data);

        if($response->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            if(!$refreshToken) {
                $this->auth();
                $this->request($method, $url, $data, true);
            }
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new \InvalidArgumentException($response->getBody(), $response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    public function setService(string $service): void
    {
        $this->service = $service;
    }

}
