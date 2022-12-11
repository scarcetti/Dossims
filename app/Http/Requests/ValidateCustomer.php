<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateCustomer extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'zipcode' => 'required',
            'contact_no' => 'required',
            'email' => ['required', Rule::unique('customer','email')],
            'type'=> 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required'     => 'Email field is required.',
            'email.email'     => 'Must be a valid email.',
            'email.unique'     => 'Email already exists.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'province.required' => 'Province is required.',
            'zipcode.required' => 'Zipcode is required.',
            'contact_no.required' => 'Contact number is required.',
            'type.required' => 'Customer type is required.',
        ];
    }
}
