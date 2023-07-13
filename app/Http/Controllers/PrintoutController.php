<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
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
        // return $request;
        $request = request();

        $start = $request->m_y_start1;
        $end = $request->m_y_end1;

        $startDate = Carbon::parse($start.'-01')->format('Y-m-d');
        $endDate = Carbon::parse($end)->endOfMonth()->format('Y-m-d');

        $branch_name = \App\Models\Branch::select('name')->find($request->branch_id)->name;


        // return \App\Models\TransactionPayment::with('transaction')->get();

        $transaction_payments = \App\Models\TransactionPayment::where('payment_type_id', '!=', 1)
            ->whereHas('transaction.branch', function($q) use($request) {
                $q->where('id', $request->branch_id);
            })
            ->whereHas('transaction', function($q) use($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('downpayment')
            ->get();

        $sum = 0;
        foreach($transaction_payments as $item) {
            $sum += floatval($item->amount_paid);
            !is_null($item->downpayment) && $sum += floatval($item->downpayment->amount_paid);
        }

        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);
        $pdf->loadView('printout.salesreport.index', compact('start', 'end', 'branch_name', 'sum'));

        $filename = $start == $end ? $start : "$start-$end";

        return env('APP_DEBUG', false) ?
                    $pdf->stream() :
                    $pdf->download("$filename-sales-report.pdf");
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
