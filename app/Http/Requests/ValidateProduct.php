<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProduct extends FormRequest
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
            'product_category_id' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Product Name is required.',
            'product_category_id.required' => 'Category is required.',
            'price.required' => 'Price is required.',
            'quantity.required' => 'Quantity is required.',
        ];
    }
}
