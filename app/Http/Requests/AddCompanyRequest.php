<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddCompanyRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ];
    }

     /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'email.required' => 'Company Email is required.',
            'email.email' => 'Please enter a valid Company email address.',
            'email.unique' => 'This email have another company registered.',
        ];
    }
}
