<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class CuttingListController extends Controller
{
    public function getOrders()
    {
        $orders = Transaction::
                        where('status', 'procuring')
                        ->with('customer', 'businessCustomer', 'transactionItems.jobOrder', 'transactionItems.branchProduct.product.measurementUnit')
                        ->get();

        return view('voyager::cutting-list.browse', compact('orders'));
    }

    public function cuttingList($id)
    {
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
                    $transaction = Transaction::where('id', $transaction_id)->update(['status' => 'preparing for delivery']);
                }
            }
}
