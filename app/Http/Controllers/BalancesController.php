<?php

namespace App\Http\Controllers;
use App\Http\Requests\FinalBillingValidation;
use App\Models\Balance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalancesController extends Controller
{
    function getBranch($column=null)
    {
        $user = Auth::user();
        $x = Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();

        if(is_null($column)) {
            return is_null($x) ? false : $x;
        }
        else {
            return is_null($x) ? false : $x->$column;
        }
    }

    function current_employee($employees_list)
    {
        // dd(Auth::user());
        $current = array_filter($employees_list->toArray(), function ($employee) {
            return $employee['employee_id'] == Auth::user()->employee_id;
        });

        return json_encode($current[key($current)]);
    }

    function createTxno($branch_id=null)
    {
        $tx = Transaction::where('branch_id', $branch_id ?? $this->getBranch('id'))
            ->whereNotNull('txno')
            ->latest('id')
            ->first('txno');

        if( is_null($tx) ) {
            return '000001';
        }

        $x = intval($tx->txno) + 1;
        while ( strlen(strval($x)) < 6 ) {
            $x = '0' . strval($x);
        }

        return $x;
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
        $logged_employee = $this->current_employee($branch_employees);

        return view('voyager::balances.settle', compact('balances', 'payment_methods', 'branch_employees', 'logged_employee'));
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

        $employee_id = Employee::findOrFail(Auth::user()->id)->id;

        $transaction = [
            'customer_id'               => $request->balances_['customer_id'] ?? null,
            'branch_id'                 => $this->getBranch('id'),
            'employee_id'               => $employee_id,
            'status'                    => 'completed',
            'cashier_id'                => $employee_id,
            'txno'                      => $this->createTxno(),
        ];

        $txn_payment = \App\Models\TransactionPayment::create([
            'amount_paid' => $request->grand_total,
            'payment_type_id' => 4,
            'payment_method_id' => $request->payment_method['id'],
        ])->transaction()->create($transaction);

        $deleted = Balance::where('id', $request->balances_['id'])->delete();

        return $deleted;
    }
}
