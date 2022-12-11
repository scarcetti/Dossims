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
    public function getTransactions()
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
    public function getTransactionItems()
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
    public function getTransactionPayments()
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
