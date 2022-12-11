<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateBranch extends FormRequest
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
            'name' => 'required',
            'contact_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'zipcode' => 'required',
            'type' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Branch name is required',
            'contact_no.required' => 'Contact number is required',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'province.required' => 'Province is required',
            'zipcode.required' => 'Zipcode is required',
            'type.required' => 'Type is required',
        ];
    }
}
