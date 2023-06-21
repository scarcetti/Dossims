<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'           => 'exclude_unless:business_customer_id,null|required',
            'business_customer_id'  => 'exclude_unless:customer_id,null|required',
            'employee_id'           => 'required',
            'cart.*.quantity'       => 'required|numeric|gt:0|lte:cart.*.selection.quantity',
            'cart.*.linear_meters'  => 'required|numeric|gt:0',
            'cart.*.job_order_note'  => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'  => 'Customer is required.',
            'business_customer_id.required' => 'Business customer is required.',
            'employee_id.required'  => 'Employee is required.',
            'cart.*.quantity.required' => 'Quantity field is required.',
            'cart.*.quantity.numeric' => 'Quantity field must be number or decimal value.',
            'cart.*.quantity.gt' => 'Quantity field value must be greater than zero.',
            'cart.*.quantity.lte' => 'Quantity cannot be greater than remaining stock.',

            'cart.*.linear_meters.required' => 'Linear meters field is required.',
            'cart.*.linear_meters.numeric' => 'Linear meters field must be number or decimal value.',
            'cart.*.linear_meters.gt' => 'Linear meters field value must be greater than zero.',
        ];
    }
}
