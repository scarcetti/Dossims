<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\InventoryTransfer;
use App\Models\Employee;
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

        function branches($no_current = false)
        {
            if($no_current) {
                return Branch::select('id', 'name')->where('id', '!=', $this->current_branch())->get();
            }
            return Branch::select('id', 'name')->get();
        }

        function branch_stocks()
        {
            $branch_products = BranchProduct::leftJoin('products', 'products.id', '=', 'branch_products.product_id')
                            ->where('branch_products.branch_id', $this->current_branch())
                            ->where('branch_products.quantity', '>', 0)
                            ->orderBy('products.name', 'ASC')
                            ->with('product.measurementUnit', 'branch')
                            ->get();

            return $branch_products;
        }

        function current_branch()
        {
            $user = Auth::user();
            $branch_id = Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                $q->where('id', $user->id);
            })->first()->id;

            return $branch_id;
        }

    public function inboundAndTransfers(Request $request)
    {
        if(is_null( \Illuminate\Support\Facades\Auth::user() )) {
            return redirect()->intended('/admin');
        }

        $inbounds = $this->fetchInbound($request);
        $outbounds = $this->fetchOutbound($request);
        $branches = $this->branches(true);
        $branch_stocks = $this->branch_stocks();

        return view('voyager::inventory.transfers.index', compact('branches', 'branch_stocks', 'inbounds', 'outbounds'));
    }

        function fetchInbound($request)
        {
            $inventory_transfer = InventoryTransfer::where('receiver_branch_id', $this->current_branch())
                    ->with('sender', 'batch', 'employee')
                    ->paginate($perPage = 15, $columns = ['*'], $pageName = 'inbounds');
            return $inventory_transfer;
        }

        function fetchOutbound($request)
        {
            $inventory_transfer = InventoryTransfer::where('sender_branch_id', $this->current_branch())
                    ->with('receiver', 'batch', 'employee')
                    ->paginate($perPage = 15, $columns = ['*'], $pageName = 'outbounds');
            return $inventory_transfer;
        }

    public function createInbound(Request $request)
    {
        $payload = $request->all();
        !is_null($payload['arrival_date']) && $this->addStocks($request->products);
        $inventory_transfer = InventoryTransfer::create($payload)->batch()->createMany($request->products);
        return response()->json(compact('inventory_transfer'));
    }

    public function createOutbound(Request $request)
    {
        $payload = $request->all();
        $this->subtractStocks($request->products);
        $inventory_transfer = InventoryTransfer::create($payload)->batch()->createMany($request->products);
        return response()->json(compact('inventory_transfer'));
    }

    public function stockArrivalConfirm(Request $request)
    {
        $this->addStocks($request->batch);
        $inventory_transfer = InventoryTransfer::where('id', $request->id)->update([
            'arrival_date' => Carbon::now(),
            'employee_id' => Auth::user()->employee_id,
        ]);
        return $inventory_transfer;
    }

    function addStocks($stocks)
    {
        foreach($stocks as $stock) {
            $branch_product = BranchProduct::where('branch_id', $this->current_branch())
                ->where('product_id', $stock['product_id'])
                ->first();

            if(is_null($branch_product)) {
                $branch_product = $this->createBranchProduct($stock['product_id']);
            }

            if(is_null($stock['meters'])) {
                $branch_product->quantity += floatval($stock['pcs']);
                $branch_product->save();
            }
            else {
                $branch_product->quantity += (floatval($stock['pcs']) * floatval($stock['meters']));
                $branch_product->save();
            }
        }
    }
    function subtractStocks($stocks)
    {
        foreach($stocks as $stock) {
            $branch_product = BranchProduct::where('branch_id', $this->current_branch())
                ->where('product_id', $stock['product_id'])
                ->first();

            if(is_null($stock['meters'])) {
                $branch_product->quantity -= floatval($stock['pcs']);
                $branch_product->save();
            }
            else {
                $branch_product->quantity -= (floatval($stock['pcs']) * floatval($stock['meters']));
                $branch_product->save();
            }
        }
    }

    function createBranchProduct($product_id)
    {
        return BranchProduct::create([
            'quantity' => 0,
            'product_id' => $product_id,
            'branch_id' => $this->current_branch(),
            'price' => 0,
        ]);
    }

}
