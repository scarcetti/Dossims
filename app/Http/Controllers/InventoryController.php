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

        $branch_products = BranchProduct::when(isset($request['search_input']), function($x) use($request) {
                                $x->whereHas('product', function ($q) use ($request) {
                                    $q->where('name', 'ilike', '%'.$request['search_input'].'%');
                                });
                            })
                            ->where('branch_id', $request['branch_id'])
                            ->with('product')
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
