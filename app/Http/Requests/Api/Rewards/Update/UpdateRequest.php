<?php

namespace App\Http\Requests\Api\Rewards\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

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

    public function validated($key = null, $default = null): RewardUpdateDTO
    {
        $validatedPayload = parent::validated($key, $default);
        return new RewardUpdateDTO(
            $validatedPayload['name'],
            $validatedPayload['points'],
        );
    }
}
