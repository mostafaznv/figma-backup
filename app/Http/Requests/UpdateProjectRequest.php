<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function rules(): array
    {
        $projectId = $this->segment(2);

        return [
            'figma_id' => [
                'required', 'string', 'max:85',
                Rule::unique(Project::class)->ignore($projectId)
            ],

            'name' => [
                'required', 'string', 'max:190'
            ],

            'is_active' => [
                'required', 'boolean'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
