<?php

namespace App\Http\Controllers;

use App\Models\BranchProduct;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CriticalStocksController extends Controller
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

    public function index(Request $request)
    {
        $low_stocks = $this->low_stocks($request);

        return view('voyager::critical-stocks.browse', compact('low_stocks'));
    }

        function low_stocks($request)
        {
            // if( isset($request->order_by) ) {
            //     switch ($request->order_by) {
            //         case 'Most selling':
            //             $order = 'desc';
            //             $qty = true;
            //             break;
            //         case 'Least selling':
            //             $order = 'asc';
            //             $qty = true;
            //             break;
            //         case 'Most profitable':
            //             $order = 'desc';
            //             $qty = false;
            //             break;
            //         case 'Least profitable':
            //             $order = 'asc';
            //             $qty = false;
            //             break;
            //     }
            // }
            // else {
            //     $order = 'desc';
            //     $qty = true;
            // }

            // $branch_id = $this->getBranch('id');

            // $top_items = TransactionItem::when($qty, function($q) use($order) {
            //                 $q->selectRaw('branch_product_id, count(id) as count_')
            //                     ->orderBy('count_', $order);
            //                 })
            //                 ->when(!$qty, function($q) use($order) {
            //                     $q->selectRaw('branch_product_id, sum(price_at_purchase) as count_')
            //                         ->orderBy('count_', $order);
            //                 })
            //                 ->groupBy('branch_product_id')
            //                 ->with('branchProduct.product')
            //                 ->whereHas('branchProduct', function($q) use($branch_id) {
            //                     $q->where('branch_id', $branch_id);
            //                 })
            //                 ->take(20)
            //                 ->get();

            //                 return $top_items;

            $currentMonth = Carbon::now()->startOfMonth();
            $previousTwoMonths = Carbon::now()->subMonths(2)->startOfMonth();

            $branch_products = BranchProduct::select('branch_products.*')
                ->leftJoin('transaction_items', 'branch_products.id', '=', 'transaction_items.branch_product_id')
                ->where('branch_products.branch_id', $this->getBranch('id'))
                ->where('branch_products.quantity', '<', function ($query) use ($currentMonth, $previousTwoMonths) {
                    $query->select(DB::raw('SUM(transaction_items.quantity)'))
                    ->from('transaction_items')
                    ->whereBetween('transaction_items.created_at', [$previousTwoMonths, $currentMonth]);
                })
                ->orWhere('branch_products.quantity', '<', 10)
                ->with('product')
                ->groupBy('branch_products.id')
                ->orderBy('branch_products.quantity', 'desc')
                ->paginate(20);

            return $branch_products;
        }
}
