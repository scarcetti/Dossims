<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /* 'employee_id' => 'required', */
            'email' => 'required',
            'email' => ['required', Rule::unique('users','email')],
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'status' => 'required'
        ];
    }
    public function messages()
    {
        return [
           /*  'employee_id.required' => 'Employee ID is required.', */
            'email.required'     => 'Email field is required.',
            'email.email'     => 'Must be a valid email.',
            'email.unique'     => 'Email already exists.',
            'password.required'     => 'Password field is required.',
            'confirm_password.required'     => 'Confirm Password field is required.',
            'status.required' => 'Status is required.',
        ];
    }
}
