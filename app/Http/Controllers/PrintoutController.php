<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use ZipArchive;

class PrintoutController extends Controller
{
    function getBranch($column=null)
    {
        $user = Auth::user();
        $x = \App\Models\Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();

        if(is_null($column)) {
            return is_null($x) ? false : $x;
        }
        else {
            return is_null($x) ? false : $x->$column;
        }
    }

    function branches()
    {
        $role_id = Auth::user()->role->id;
        $current_branch = $this->getBranch('id');

        return \App\Models\Branch::when( $role_id == 4 , function($q) use($current_branch) {
                    $q->where('id', $current_branch);
                })
                ->select('id','name')
                ->orderBy('name', 'asc')
                ->get();
    }

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

    public function reports()
    {
        $branches = $this->branches();
        return view('printout.salesreport', compact('branches'));
    }

    public function salesReport(Request $request)
    {
        $date = Carbon::createFromFormat('Y-m', $request->m_y);
        $month = $date->format('F');
        $year = $date->format('Y');
        $branch_name = \App\Models\Branch::select('name')->find($request->branch_id)->name;

        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);

        $pdf->loadView('printout.salesreport.index', compact('month', 'year', 'branch_name'));

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$m_y-sales-report.pdf");
    }

    public function test_dl()
    {
        $now = Carbon::now()->format('mdy:his');
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
