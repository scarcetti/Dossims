<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\TransactionItem;
use App\Models\BranchProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    function getBranch($column)
    // function getBranch($column=null)
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
        $branches = $this->branches();
        $top_products = $this->top_products($request);
        $storeFilter = $this->storeFilter($request);

        // $filter_branch = $request->filter_branch;
        // $filter_branch = Branch::where('name', $request->filter_branch)->first('id');

        $branches_ = $this->branches();
        foreach($branches_ as $item) {
            $list_branch_options[] = $item->name;
        }

        return view('voyager::analytics.index', compact('branches','top_products','list_branch_options','storeFilter'));
    }

    function branches()
    {
        $role_id = Auth::user()->role->id;
        $current_branch = $this->getBranch('id');

        return Branch::select('id','name')
                ->orderBy('name', 'asc')
                ->get();
    }
    function storeFilter($request)
    {
        $filter_branch= Branch::where('name', $request->filter_branch)->first('id');

        $branches_ = $this->branches();
        foreach($branches_ as $item) {
            if(is_null($filter_branch)) {
                $branch_id = $this->getBranch('id');
            }
            elseif(!is_null($filter_branch) && $filter_branch->id == $item->id) {
                $branch_id= $item->id;
            }
        }
        return $branch_id;
    }


    function top_products($request)
    {
        $filter_by = (isset($request->filter_value) || !is_null($request->filter_value)) ? $request->filter_value : 'Weekly';
        // $filter_branch = Branch::where('name', $request->filter_branch)->first('id');
        // $filter_branch = (isset($request->branch) || !is_null($request->branch)) ? $request->branch : 3;
        // 'Weekly', 'Monthly', 'Yearly', 'All-time'
        $branch_id = $this->storeFilter($request);
        if( isset($request->order_by) ) {
            switch ($request->order_by) {
                case 'Most selling':
                    $order = 'desc';
                    $qty = true;
                    break;
                case 'Least selling':
                    $order = 'asc';
                    $qty = true;
                    break;
                case 'Most profitable':
                    $order = 'desc';
                    $qty = false;
                    break;
                case 'Least profitable':
                    $order = 'asc';
                    $qty = false;
                    break;
            }
        }
        else {
            $order = 'desc';
            $qty = true;
        }


        $top_items = TransactionItem::when($qty, function($q) use($order) {
                        $q->selectRaw('branch_product_id, count(id) as count_')
                            ->orderBy('count_', $order);
                        })
                        ->when(!$qty, function($q) use($order) {
                            $q->selectRaw('branch_product_id, sum(price_at_purchase) as count_')
                                ->orderBy('count_', $order);
                        })
                        ->groupBy('branch_product_id')
                        ->with('branchProduct.product')
                        ->when($filter_by == 'Weekly', function($q) {
                            $now = Carbon::now();
                            $start = $now->startOfWeek()->format('m-d-Y');
                            $end = $now->endOfWeek()->format('m-d-Y');

                            $q->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($filter_by == 'Monthly', function($q) {
                            $q->whereHas('transaction', function($w) {
                                $w->whereMonth('created_at', Carbon::now()->format('m'));
                            });
                        })
                        ->when($filter_by == 'Yearly', function($q) {
                            $q->whereHas('transaction', function($w) {
                                $w->whereYear('created_at', Carbon::now()->format('Y'));
                            });
                        })
                        ->whereHas('branchProduct', function($q) use($branch_id) {
                            $q->where('branch_id', $branch_id);
                        })
                        // ->when($filter_by != 'All-time', function($q) {
                        //     $q->take(10);
                        // })
                        ->take(20)
                        ->get();

                        return $top_items;
    }

    public function chart($branch_id, $branch_product_id)
    {
        $bp = BranchProduct::with('product')->find($branch_product_id);

        $months = $this->monthly_sales($branch_product_id, $branch_id);
        $qty = $this->format_for_chart($months, $bp->product->name);
        return $qty;
    }

    function monthly_sales($branch_product_id, $branch_id)
    {
        $offset_month = 12;
        $tx = TransactionItem::whereHas('transaction', function($q) use($offset_month) {
                    $this_month = Carbon::now()->format('M-Y');

                    $q->whereDate('created_at', '>', Carbon::parse($this_month)->subMonths($offset_month)->endOfMonth());
                })
                ->whereHas('transaction.branch', function($q) use($branch_id) {
                    $q->where('id', $branch_id);
                })
                ->where('branch_product_id', $branch_product_id)
                ->with('transaction.branch')
                // ->take(10)
                ->get();

        if( count($tx) < 1 ) return null;

        foreach($tx as $key => $value) {
            $month = Carbon::parse($value->transaction->created_at)->format('M Y');
            $qty = $value->quantity;

            if(isset($months[$month])) {
                array_push($months[$month], $qty);
            }
            else {
                $months[$month] = [$qty];
            }
        }

        foreach($months as $key => $value) {
            $months[$key] = array_sum($value);
        }

        return $months;
    }

    function format_for_chart($sales, $title='Item')
    {
        /* FORMAT PARAMETERS AS

            - $sales :
                { "12-22": 123123123, "1-23": 321321312, "2-23": 43434343 }
        */

        if(is_null($sales)) return null;

        $dates = array_keys((array) $sales);
        $figures = array_values((array) $sales);


        foreach($figures as $key => $value) {
            if($key > 1) {
                $predictions[] = array_sum([
                    $figures[$key - 2],
                    $figures[$key - 1],
                    $figures[$key],
                ]) / 3;
            }
        }

        # getting predicted next 2 months begin

        $x = array_reverse($predictions);
        $y = array_reverse($figures);

        $predict_2nd = array_sum([
            $y[0],
            $y[1],
            $x[0],
        ]) / 3;

        $predict_3rd = array_sum([
            $y[0],
            $x[0],
            $predict_2nd,
        ]) / 3;

        array_push($predictions, $predict_2nd, $predict_3rd);

        # getting predicted next 2 months end

        // PREDICTION APPENDS
        $predict_first_month = Carbon::parse(end($dates))->addMonths(1)->format('M Y');
        $predict_second_month = Carbon::parse(end($dates))->addMonths(2)->format('M Y');
        $predict_third_month = Carbon::parse(end($dates))->addMonths(3)->format('M Y');

        array_push($dates, $predict_first_month, $predict_second_month, $predict_third_month);

        $last_three = array_slice($figures, -3);
        $predicted_figure = array_sum($last_three) / 3;
        array_push($figures, $predicted_figure);

        $dates = array_slice($dates, -12);
        $figures = array_slice($figures, -9);


        array_unshift($dates, 'Month');
        array_unshift($figures, 'Real Figures');
        array_unshift($predictions, 'Prediction');

        $first_ = floor(array_reverse($predictions)[2]);
        $second_ = floor(array_reverse($predictions)[1]);
        $third_ = floor(array_reverse($predictions)[0]);

        $vueChartOption = (object) array (
            'title' => (object) [
                'text' => $title,
                'subtext' => "Stocks must meet or exceed the following thresholds: ≥ $first_ for 1st month, ≥ $second_ for 2nd month, and ≥ $third_ for 3rd month.",
            ],
            'legend' =>
            array (
            ),
            'toolbox' => (object) [

                'feature' => (object) [
                    'saveAsImage' => (object)[]
                ]
            ],
            'tooltip' =>
            array (
                'trigger' => 'axis',
                'showContent' => true,
            ),
            'dataset' =>
            array (
                'source' =>
                array (
                0 => $dates,
                1 => $figures,
                2 => $predictions,
                ),
            ),
            'xAxis' =>
            array (
                'type' => 'category',
            ),
            'yAxis' =>
            array (
                'gridIndex' => 0,
            ),
            'series' =>
            array (
                0 =>
                array (
                'type' => 'line',
                'smooth' => true,
                'seriesLayoutBy' => 'row',
                'emphasis' =>
                array (
                    'focus' => 'series',
                ),
                ),
                1 =>
                array (
                'type' => 'line',
                'smooth' => true,
                'seriesLayoutBy' => 'row',
                'emphasis' =>
                array (
                    'focus' => 'series',
                ),
                )
            ),
            );

        return json_encode($vueChartOption);
    }

    function test()
    {
        $now = Carbon::now();
        // $start = $now->startOfWeek()->format('M d Y');
        // $end = $now->endOfWeek()->format('M d Y');

        // $start = $now->startOfMonth()->format('M d Y');
        // $end = $now->endOfMonth()->format('M d Y');

        $start = $now->startOfYear()->format('M d Y');
        $end = $now->endOfYear()->format('M d Y');

        return [$start, $end];
    }
}
