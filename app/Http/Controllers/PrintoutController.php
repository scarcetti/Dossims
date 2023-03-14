<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintoutController extends Controller
{
    public function chargeInvoice()
    {

    }

    public function cuttingList()
    {

    }

    public function deliveryReceipt()
    {

    }

    public function jobOrder()
    {

    }

    public function officialReceipt($txid)
    {

        $data = [
            'title' => 'Doming\'s Steel Trading',
            'date' => date('m/d/Y'),
        ];

        $transaction = Transaction::with('transactionItems.branchProduct.product', 'customer', 'businessCustomer')->find($txid);
           // return $transaction;

        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);
        // return view('printout.official-receipt', compact('data', 'transaction'));
        
        $pdf->loadView('printout.official-receipt', compact('data', 'transaction'));
        return $pdf->stream();
        // return $pdf->download('tutsmake.pdf');
    }

}
