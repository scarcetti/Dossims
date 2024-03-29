<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuttingListController extends Controller
{
    public function getOrders()
    {
        if(!isset(Auth::user()->role)) {
            return redirect('/');
        }

        $orders = Transaction::
                        where('status', 'procuring')
                        ->with('customer', 'businessCustomer', 'transactionItems.jobOrder', 'transactionItems.branchProduct.product.measurementUnit')
                        ->get();

        return view('voyager::cutting-list.browse', compact('orders'));
    }

    public function cuttingList($id)
    {
        if(!isset(Auth::user()->role)) {
            return redirect('/');
        }

        $tx_items = TransactionItem::where('transaction_id', $id)
                    ->with('jobOrder', 'branchProduct.product.measurementUnit')
                    ->whereHas('jobOrder')
                    ->get();

        return view('voyager::cutting-list.actions', compact('tx_items'));
    }

    public function updateStatus($transaction_id, $job_order_id)
    {
        $job_order = JobOrder::find($job_order_id);
        $status = $job_order->status;
        $job_order->update([
            'status' => $status == 'pending' ? 'in progress' : 'completed',
        ]);

        $this->other_orders($transaction_id);

        return $job_order;
    }

            function other_orders($transaction_id)
            {
                // $transaction_items = TransactionItem::where('transaction_id', $transaction_id)->with('jobOrder')->get();

                $incomplete_jos = JobOrder::whereHas('transaction_item', function($q) use($transaction_id) {
                        $q->where('transaction_id', $transaction_id)->where('status', '!=', 'completed');
                    })->pluck('status')->count();

                if($incomplete_jos == 0) {
                    if($this->to_pick_up($transaction_id)) {
                        $msg = 'waiting for pickup';
                    }
                    else {
                        $msg = 'preparing for delivery';
                    }

                    $transaction = Transaction::where('id', $transaction_id)->update(['status' => $msg]);
                }
            }

            function to_pick_up($transaction_id)
            {
                $transaction = Transaction::where('id', $transaction_id)
                    ->whereHas('payment.delivery_fees', function($q) {
                        $q->where('total', '>', 0);
                    })
                    ->first();

                return is_null($transaction);
            }
}
