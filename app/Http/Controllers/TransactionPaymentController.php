<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionPayment;

class TransactionPaymentController extends Controller
{
    public function createTransactionPayment(Request $payment)
    {
        return TransactionPayment::create([
            'transaction_id'=>$payment->transaction_id,
            'outstanding_balance'=>$payment->outstanding_balance,
            'amount_paid'=>$payment->amount_paid,
            'remarks'=>$payment->remarks,
        ]);
    }

    public function updateTransactionPayment(Request $request)
    {
        $transactionPayment_data = $request->all();
        $transactionPayment = TransactionPayment::where('id',$request->id)->first();
        $transactionPayment->update($transactionPayment_data);
        return $transactionPayment;
    }

    public function deleteTransactionPayment($id)
    {
        return TransactionPayment::where('id',$id)->delete();
    }

    public function fetchAllTransactionPayments()
    {
        return TransactionPayment::get();
    }

    public function fetchTransactionPaymentById($id)
    {
        return TransactionPayment::where('id',$id)->first();
    }

}
