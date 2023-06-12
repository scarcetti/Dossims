<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
use ZipArchive;

class PrintoutController extends Controller
{
    public function chargeInvoice($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )
        ->where('txno', $txid)
        ->first();
        if( is_null($transaction ) ) abort(404);

        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);

        $pdf->loadView('printout.charge-invoice.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-charge-invoice.pdf");
    }

    public function cashInvoice($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )
        ->where('txno', $txid)
        ->first();
        if( is_null($transaction ) ) abort(404);
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);

        $pdf->loadView('printout.cash-invoice.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-cash-invoice.pdf");
    }

    public function cuttingList($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product.productCategory',
            'transactionItems.branchProduct.product.measurementUnit',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
            'payment.delivery_fees',
        )
        ->where('txno', $txid)
        ->first();
        if( is_null($transaction ) ) abort(404);
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);

        // return view('printout.cutting-list', compact('transaction'));

        $pdf->loadView('printout.cutting-list.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-cutting-list.pdf");
    }

    public function deliveryReceipt($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )
        ->where('txno', $txid)
        ->first();
        if( is_null($transaction ) ) abort(404);
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);

        $pdf->loadView('printout.delivery-fee.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-delivery-receipt.pdf");
    }

    public function jobOrder($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )
        ->where('txno', $txid)
        ->first();
        if( is_null($transaction ) ) abort(404);
        $pdf = PDF::setPaper('a4', 'portrait')->setWarnings(false);

        $pdf->loadView('printout.job-order.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-job-order.pdf");
    }

    public function officialReceipt($txid)
    {
        $transaction = Transaction::with(
            'transactionItems.branchProduct.product',
            'customer',
            'cashier',
            'businessCustomer',
            'payment.payment_method',
        )
        ->where('txno', $txid)
        ->first();

        if( is_null($transaction ) ) abort(404);

        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);

        $pdf->loadView('printout.official-receipt.index', compact('transaction'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$txid-official-receipt.pdf");
    }

    public function test_dl()
    {
        $now = \Carbon\Carbon::now()->format('mdy:his');
        $pdfFolder = storage_path("app/tmp/$now");

        File::ensureDirectoryExists($pdfFolder);

        // $bladeFiles = ['file1', 'file2', 'file3']; // Replace with your own Blade files
        $bladeFiles = [
            'charge-invoice.index',
            'cash-invoice.index',
            'delivery-fee.index',
            'official-receipt.index',
        ]; // Replace with your own Blade files

        foreach ($bladeFiles as $bladeFile) {
            $pdf = PDF::loadView("printout.$bladeFile");
            $pdf->save($pdfFolder . "/$bladeFile.pdf");
        }

        $pdfZipFileName = 'pdfs.zip';

        $zip = new ZipArchive();

        if ($zip->open($pdfZipFileName, ZipArchive::CREATE) === TRUE) {

            $pdfFiles = File::files($pdfFolder);

            foreach ($pdfFiles as $file) {
                $fileName = $file->getRelativePathname();
                $zip->addFile($file, $fileName);
            }

            $zip->close();
        }

        return response()->download($pdfZipFileName)->deleteFileAfterSend(true);
    }
}
