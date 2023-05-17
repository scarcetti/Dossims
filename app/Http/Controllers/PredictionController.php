<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\TransactionPayment;
use Illuminate\Http\Request;

class PredictionController extends Controller
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

    function branches()
    {
        $role_id = Auth::user()->role->id;
        $current_branch = $this->getBranch('id');

        return Branch::when( $role_id == 4 , function($q) use($current_branch) {
                    $q->where('id', $current_branch);
                })
                ->select('id','name')
                ->orderBy('name', 'asc')
                ->get();
    }

    public function index()
    {
        foreach($this->branches() as $item) {
            $sales = $this->monthly_sales($item->id);

            $branches[] = [
                'branch' => strtolower(str_replace(' ', '_', $item->name)),
                'set' => $this->format_for_chart($sales, $item->name),
            ];
        }

        return view('voyager::predictions.index', compact('branches'));
    }

        function monthly_sales($branch_id=4)
        {
            $offset_month = 6;
            $tx = TransactionPayment::whereHas('transaction', function($q) use($offset_month) {
                        $this_month = Carbon::now()->format('M-Y');

                        $q->whereDate('created_at', '>', Carbon::parse($this_month)->subMonths($offset_month)->endOfMonth());
                    })
                    ->whereHas('transaction.branch', function($q) use($branch_id) {
                        $q->where('id', $branch_id);
                    })
                    ->with('transaction.branch')
                    // ->take(10)
                    ->get();

            if( count($tx) < 1 ) return null;

            foreach($tx as $key => $value) {
                $month = Carbon::parse($value->transaction->created_at)->format('M Y');
                $paid = floatval($value->amount_paid);

                if(isset($months[$month])) {
                    array_push($months[$month], $paid);
                }
                else {
                    $months[$month] = [$paid];
                }
            }

            foreach($months as $key => $value) {
                $months[$key] = array_sum($value);
            }

            return $months;
        }

        public function format_for_chart($sales, $title='Item')
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

            // PREDICTION APPENDS
            $next_month = Carbon::parse(end($dates))->addMonths(1)->format('M Y');
            $last_unpredicted_month = end($dates);
            array_push($dates, $next_month);

            // $last_three = array_slice($figures, -3);
            // $predicted_figure = array_sum($last_three) / 3;
            // array_push($figures, $predicted_figure);

            $dates = array_slice($dates, -4);
            $figures = array_slice($figures, -3);

            array_unshift($dates, 'Month');
            array_unshift($figures, 'Real Figures');
            array_unshift($predictions, 'Prediction');

            $vueChartOption = (object) array (
                'title' => (object) [
                    'text' => $title,
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

            // return json_encode($vueChartOption);
            return $vueChartOption;
        }

    public function top_5_selling()
    {
        return 'adasdasd';
    }
}
