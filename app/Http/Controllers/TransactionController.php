<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionPayment;

class TransactionController extends Controller
{

    public function createTransaction(Request $transaction)
    {
        return Transaction::create([
            'customer_id'=>$transaction->customer_id,
            'employee_id'=>$transaction->employee_id,
            'status'=>$transaction->status,
        ]);
    }

    public function updateTransaction(Request $request)
    {
        $transaction_data = $request->all();
        $transaction = Transaction::where('id',$request->id)->first();
        $transaction->update($transaction_data);
        return $transaction;
    }

    public function deleteTransaction($id)
    {
        return Transaction::where('id',$id)->delete();
    }

    public function fetchAllTransactions()
    {
        return Transaction::get();
    }

    public function createTransactionItem(Request $item)
    {
        return TransactionItem::create([
            'transaction_id'=>$item->transaction_id,
            'product_id'=>$item->product_id,
            'price_at_purchase'=>$item->price_at_purchase,
            'quantity'=>$item->quantity,
        ]);
    }

    public function updateTransactionItem(Request $request)
    {
        $transactionItem_data = $request->all();
        $transactionItem = TransactionItem::where('id',$request->id)->first();
        $transactionItem->update($transactionItem_data);
        return $transactionItem;
    }

    public function deleteTransactionItem($id)
    {
        return TransactionItem::where('id',$id)->delete();
    }

    public function fetchAllTransactionItems()
    {
        return TransactionItem::get();
    }
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

    public function completedSuperadminTransaction(){
        return view('superadmin.transactions.completed.completed');
    }
    public function pendingSuperadminTransaction(){
        return view('superadmin.transactions.pending.pending');
    }
    public function completedAdminTransaction(){
        return view('admin.transactions.completed.completed');
    }
    public function pendingAdminTransaction(){
        return view('admin.transactions.pending.pending');
    }
    public function completedFrontdeskTransaction(){
        return view('frontdesk.transactions.completed.completed');
    }
    public function pendingFrontdeskTransaction(){
        return view('frontdesk.transactions.pending.pending');
    }
}
