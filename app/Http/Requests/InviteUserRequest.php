<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\{companyUser,User};

class InviteUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:Admin,Member'],
        ];
    }

     public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            $user = User::select('id','role','email')->where('email', $this->email)->first();
            if (!$user) {
                return false;
            }

            $exists = CompanyUser::where('company_id', $this->route('companyId'))
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'email',
                    'This user is already part of this company.'
                );
            }
        });
    }

     /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Invitee name is required.',
            'email.required' => 'Invitee Email is required.',
            'email.email' => 'Please enter a valid Invitee email address.',
            'email.unique' => 'This email is already registered in this company.',
        ];
    }
}
