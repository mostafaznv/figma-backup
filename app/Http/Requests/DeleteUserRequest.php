<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    public function rules(): array
    {
        $this->errorBag = 'userDeletion';

        $userId = $this->segment(2);
        $user = User::query()->where('id', $userId)->firstOrFail();

        return [
            'email' => [
                "in:$user->email"
            ],
        ];
    }
}
