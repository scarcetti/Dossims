<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\InventoryTransfer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->branches();

        $request = $request->all();

        $branch_products = BranchProduct::leftJoin('products', 'products.id', '=', 'branch_products.product_id')
                            ->when(isset($request['search_input']), function($x) use($request) {
                                $x->whereHas('product', function ($q) use ($request) {
                                    $q->where('products.name', 'ilike', '%'.$request['search_input'].'%');
                                });
                            })
                            ->when(isset($request['branch_id']), function($q) use ($request) {
                                $q->where('branch_products.branch_id', $request['branch_id'])
                                    ->orderBy('products.name', 'ASC');

                            })/*
                            ->when(!isset($request['branch_id']), function($q) use ($request) {

                            })*/
                            ->with('product', 'branch')
                            ->get();
                            // ->paginate(15);

        return view('voyager::inventory.index', compact('branches','branch_products'));
    }

        function branches($no_current = false) {
            if($no_current) {
                $user = Auth::user();
                $x = Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                    $q->where('id', $user->id);
                })->first()->id;

                return Branch::select('id', 'name')->where('id', '!=', $x)->get();
            }

            // dd($x);
            return Branch::select('id', 'name')->get();
        }

        function branch_stocks() {
            $user = Auth::user();
            $branch_id = Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                $q->where('id', $user->id);
            })->first()->id;

            $branch_products = BranchProduct::leftJoin('products', 'products.id', '=', 'branch_products.product_id')
                            ->where('branch_products.branch_id', $branch_id)
                            ->orderBy('products.name', 'ASC')
                            ->with('product.measurementUnit', 'branch')
                            ->get();

            return $branch_products;
        }

    public function inboundAndTransfers()
    {
        if(is_null( \Illuminate\Support\Facades\Auth::user() )) {
            return redirect()->intended('/admin');
        }

        $branches = $this->branches(true);
        $branch_stocks = $this->branch_stocks();

        return view('voyager::inventory.transfers.index', compact('branches', 'branch_stocks'));
    }

    public function createInbound(Request $request)
    {
        $payload = $request->all();
        $payload['arrival_date'] = Carbon::parse($request->arrival_date);
        $inventory_transfer = InventoryTransfer::create($payload)->batch()->createMany($request->products);
        return response()->json(compact('inventory_transfer'));
    }

    public function createOutbound(Request $request)
    {
        $payload = $request->all();
        $inventory_transfer = InventoryTransfer::create($payload);
        return response()->json(compact('inventory_transfer'));
    }

    public function fetchInbound()
    {
        $inventory_transfer = InventoryTransfer::where('direction', 'inbound')->get();
        return $inventory_transfer;
    }

    public function fetchOutbound()
    {
        $inventory_transfer = InventoryTransfer::where('direction', 'outbound')->get();
        return $inventory_transfer;
    }
}
