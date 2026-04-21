<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFieldUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_stage' => ['required', 'string', 'in:' . implode(',', \App\Models\Field::STAGES)],
            'note' => ['nullable', 'string', 'max:1000'],
            'observed_at' => ['required', 'date', 'before_or_equal:now'],
        ];
    }
}
