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
}
