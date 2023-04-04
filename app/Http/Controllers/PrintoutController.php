<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintoutController extends Controller
{
    public function chargeInvoice()
    {
        $transaction = 1;
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);
        // return view('printout.charge-invoice.index', compact('transaction'));

        $pdf->loadView('printout.charge-invoice.index', compact('transaction'));
        return $pdf->stream();
    }

    public function cashInvoice()
    {
        $transaction = 1;
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);
        // return view('printout.charge-invoice.index', compact('transaction'));

        $pdf->loadView('printout.cash-invoice.index', compact('transaction'));
        return $pdf->stream();
    }

    public function cuttingList()
    {

    }

    public function deliveryReceipt()
    {
        $transaction = 1;
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);
        // return view('printout.charge-invoice.index', compact('transaction'));

        $pdf->loadView('printout.delivery-fee.index', compact('transaction'));
        return $pdf->stream();
    }

    public function jobOrder()
    {

    }

    public function officialReceipt($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )->find($txid);

        if( is_null($transaction ) ) abort(404);

        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);
        // return view('printout.official-receipt.index', compact('data', 'transaction'));

        $pdf->loadView('printout.official-receipt.index', compact('transaction'));
        return $pdf->stream();
        // return $pdf->download('tutsmake.pdf');
    }

}
