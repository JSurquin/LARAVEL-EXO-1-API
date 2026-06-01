<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Règles de validation pour PUT/PATCH /api/tasks/{id}
        // "sometimes" = le champ n'est validé que s'il est présent dans la requête
        return [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status'      => ['nullable', Rule::in(['todo', 'in_progress', 'done'])],
            'due_date'    => 'nullable|date|after:today',
        ];
    }
}
