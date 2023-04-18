<?php

namespace App\Http\Controllers;
use App\Http\Requests\FinalBillingValidation;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalancesController extends Controller
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

    public function index()
    {
        $balances = Balance::with('customer')->get();
        return view('voyager::balances.browse', compact('balances'));
    }

    public function selected($id)
    {
        $balances = Balance::with('customer')->findOrFail($id);
        $payment_methods = (new \App\Http\Controllers\TransactionController)->fetchPaymentMethods();
        $branch_employees = $this->fetchBranchEmployees();


        return view('voyager::balances.settle', compact('balances', 'payment_methods', 'branch_employees'));
    }

        function fetchBranchEmployees()
        {
            $branch_id = $this->getBranch('id');
            return \App\Models\BranchEmployee::when($branch_id, function($q) use($branch_id) {
                            $q->where('branch_id', $branch_id);
                        })
                        ->with('employee')
                        ->get();
        }

    public function settle(FinalBillingValidation $request)
    {
        #    Payment types value:
        #       1 Downpayment
        #       2 Full payment
        #       3 Periodic payment
        #       4 Final payment

        // \App\Models\Transaction::where('id', $request->txid)->update([
        //     'status' => 'procuring',
        //     'cashier_id' => $request->cashier_id,
        //     'transaction_payment_id' => $tx_payment_id,
        //     'txno' => $this->createTxno(),
        // ]);

        // $txn_payment = \App\Models\TransactionPayment::create([
        //     'amount_paid' => $request->grand_total,
        //     'payment_type_id' => 4,
        //     'payment_method_id' => $payment['payment_method_id'],
        // ]);
        // return 1;
    }
}
