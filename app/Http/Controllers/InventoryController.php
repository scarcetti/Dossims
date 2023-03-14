<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->branches();

        $request = $request->all();

        $branch_products = BranchProduct::leftJoin('products', 'products.id', '=', 'branch_products.product_id')
                            ->when(isset($request['search_input']), function($x) use($request) {
                                $x->whereHas('product', function ($q) use ($request) {
                                    $q->where('branch_products.name', 'ilike', '%'.$request['search_input'].'%');
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

        function branches() {
            return Branch::select('id', 'name')->get();
        }

    public function inboundAndTransfers()
    {
        return view('voyager::inventory.inbound-and-transfers.index');
    }
}
