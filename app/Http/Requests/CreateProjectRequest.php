<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'figma_id' => [
                'required', 'string', 'max:85',
                Rule::unique(Project::class)
            ],

            'name' => [
                'required', 'string', 'max:190'
            ],

            'slug' => [
                'required', 'alpha_dash', 'max:50'
            ],

            'is_active' => [
                'required', 'boolean'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug'      => Str::slug($this->slug),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
