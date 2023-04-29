<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\JobOrder;

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
        $txns = Transaction::where('id', $id)->with('transactionItems.jobOrder', 'transactionItems.branchProduct.product.measurementUnit')->first();
        return view('voyager::cutting-list.actions', compact('txns'));
    }

    public function updateStatus($id)
    {
        // return $id;
        $job_order = JobOrder::find($id);

        $status = $job_order->status;

        return $job_order->update([
            'status' => $status == 'pending' ? 'in progress' : 'completed',
        ]);
    }

            function FunctionName(Type $var = null)
            {
                # code...
            }
}
