<?php

namespace App\Http\Controllers;
use App\Models\Balance;
use App\Http\Requests\FinalBillingValidation;
use Illuminate\Http\Request;

class BalancesController extends Controller
{
    public function index()
    {
        $balances = Balance::with('customer')->get();
        return view('voyager::balances.browse', compact('balances'));
    }

    public function selected($id)
    {
        $balances = Balance::with('customer')->findOrFail($id);
        $payment_methods = (new \App\Http\Controllers\TransactionController)->fetchPaymentMethods();

        return view('voyager::balances.settle', compact('balances', 'payment_methods'));
    }

    public function settle(FinalBillingValidation $request)
    {
        return 1;
    }
}
