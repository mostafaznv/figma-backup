<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class DownloadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => [
                'required', 'integer'
            ],

            'hash' => [
                'required', 'string'
            ]
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
