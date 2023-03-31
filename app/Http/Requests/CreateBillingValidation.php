<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBillingValidation extends FormRequest
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
        #    Payment types value:
        #       1 Downpayment
        #       2 Full payment
        #       3 Periodic payment
        #       4 Final payment

        $base_fields = [
            'cashier_id' => 'required',

            'cart.*.discount_value'  => 'numeric|gt:0',

            'delivery_fee.distance'  => 'required_if:delivery_fee.outside,true|numeric|gt:0',

            'payment.payment_type_id'  => 'required',
            'payment.payment_method_id'  => 'required',

            'payment.downpayment_amount'  => 'numeric|gte:payment.grand_total',
            'payment.amount_tendered'  => 'required|numeric|gte:payment.grand_total',
        ];

        /*if( $this->payment['payment_type_id'] == 1 ) {
            $base_fields = array_merge($base_fields, [
                'payment.amount_tendered'  => 'required|numeric|gte:payment.grand_total',
            ]);
        }
        if( $this->payment['payment_type_id'] == 2 ) {
            $base_fields = array_merge($base_fields, [
                'payment.amount_tendered'  => 'required|numeric|gte:payment.grand_total',
            ]);
        }*/

        return $base_fields;
    }

    public function messages()
    {
        return [
            'cashier_id.required' => 'Cashier field is required.',

            'cart.*.discount_value.required_if' => 'Discount value is required.',
            'cart.*.discount_value.numeric' => 'Incorrect input for Discount value.',
            'cart.*.discount_value.gt' => 'Incorrect input for Discount value.',

            'payment.payment_type_id.required'  => 'Payment type is required',
            'payment.payment_method_id.required'  => 'Payment method is required',

            'payment.downpayment_amount.gte'  => 'Downpayment amount must be greater than or equal to the grand total.',

            'payment.amount_tendered.required'  => 'Amount tendered is required',
            'payment.amount_tendered.gte'  => 'Amount tendered must be greater than or equal to the grand total.',
        ];
    }
}
