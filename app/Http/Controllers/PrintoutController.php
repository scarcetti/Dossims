<?php

namespace App\Http\Controllers;
use PDF;
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

    public function officialReceipt()
    {

        $data = [
            'title' => 'Welcome to Tutsmake.com',
            'date' => date('m/d/Y')
        ];
           
        $pdf = PDF::setPaper('a4', 'landscape')->setWarnings(false);

        $pdf->loadView('printout.official-receipt', compact('data'));
        // return view('printout.official-receipt', compact('data'));
        return $pdf->stream();
        // return $pdf->download('tutsmake.pdf');
    }

}
