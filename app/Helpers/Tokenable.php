<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

trait Tokenable
{
    public static function tokenCheck(): bool
    {
        if (!is_file(Storage::disk('local')->get('main_service/token.json'))) {
            return false;
        }
        return true;
    }

    public static function storeToken(string $content): void
    {
        if (!is_dir(storage_path('app/main_service'))) {
            mkdir(storage_path('app/main_service'), 0777, true);
        }
        // there can be validation with DTO
        Storage::disk('local')->put('main_service/token.json', $content);
    }

    public static function getToken(): string
    {
        $token = Storage::disk('local')->get('main_service/token.json');
        return json_decode($token, true)['access_token'];
    }
}
