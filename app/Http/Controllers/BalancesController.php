<?php

namespace App\Http\Controllers;
use App\Models\Balance;
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
        return view('voyager::balances.settle', compact('balances'));
    }

    public function settle(Request $request)
    {
        # code...
    }
}
