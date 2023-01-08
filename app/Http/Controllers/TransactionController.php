<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

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

    public function fetchTransactionById($id)
    {
        return Transaction::where('id',$id)->first();
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
