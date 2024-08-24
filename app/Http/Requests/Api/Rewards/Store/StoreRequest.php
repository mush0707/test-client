<?php

namespace App\Http\Requests\Api\Rewards\Store;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

class StoreRequest extends FormRequest
{
    private RewardStoreDTO $schema;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'points' => 'required|numeric|gt:0',
        ];
    }

    public function validated($key = null, $default = null): RewardStoreDTO
    {
        $validatedPayload = parent::validated($key, $default);
        return new RewardStoreDTO(
            $validatedPayload['name'],
            $validatedPayload['points'],
        );
    }
}
