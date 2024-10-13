<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_expenses' => 'required|date_format:Y-m-d H:i:s|before_or_equal:' . date('Y-m-d H:i:s'),
            'value' => 'required|numeric|min:1',
            'description' => 'required|max:191'
        ];
    }
}
