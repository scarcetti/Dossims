<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinalBillingValidation extends FormRequest
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
            'cashier_id'       => 'required',
            // 'grand_total'      => 'nullable',
            'amount_tendered'  => 'required|numeric|gte:grand_total',
        ];
    }

    public function messages()
    {
        return [
            'cashier_id.required'       => 'Cashier field is required.',
            'amount_tendered.required'  => 'Amount tendered is required',
            'amount_tendered.gte'       => 'Amount tendered must be greater than or equal to the grand total.',
        ];
    }
}
