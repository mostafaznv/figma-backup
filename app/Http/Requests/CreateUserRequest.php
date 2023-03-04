<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:190'
            ],

            'email' => [
                'required', 'email', 'max:255',
                Rule::unique(User::class)
            ],

            'password' => [
                'required', 'string',
                Password::min(16)->letters()->numbers()->mixedCase(),
            ]
        ];
    }
}
