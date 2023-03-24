<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\TransactionPayment;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function index()
    {
        $sales = $this->monthly_sales();
        $chart_option = $this->format_for_chart($sales);
        $asd = [];
        return view('voyager::predictions.index', compact('asd', 'chart_option'));
    }

        function monthly_sales($branch_id=4)
        {
            $offset_month = 5;
            $tx = TransactionPayment::whereHas('transaction', function($q) use($offset_month) {
                        $this_month = Carbon::now()->format('M-Y');

                        $q->whereDate('transaction_placement', '>', Carbon::parse($this_month)->subMonths($offset_month)->endOfMonth());
                    })
                    ->whereHas('transaction.branch', function($q) use($branch_id) {
                        $q->where('id', $branch_id);
                    })
                    ->with('transaction.branch')
                    // ->take(10)
                    ->get();

            if( count($tx) < 1 ) return null;

            foreach($tx as $key => $value) {
                $month = Carbon::parse($value->transaction->transaction_placement)->format('M Y');
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

        public function format_for_chart($sales)
        {
            /* FORMAT PARAMETERS AS 

                - $sales :
                    { "12-22": 123123123, "1-23": 321321312, "2-23": 43434343 }
            */

            if(is_null($sales)) return null;

            $dates = array_keys((array) $sales);
            $figures = array_values((array) $sales);

            // PREDICTION APPENDS
            $next_month = Carbon::parse(end($dates))->addMonths(1)->format('M Y');
            $last_unpredicted_month = end($dates);
            array_push($dates, $next_month);

            $last_three = array_slice($figures, -3);
            $predicted_figure = array_sum($last_three) / 3;
            array_push($figures, $predicted_figure);

            $vueChartOption = (object) [
                'title' => (object) [
                    'text' => 'Sales',
                    'subtext' => 'Toril Main Branch',
                ],
                'tooltip' => (object) [
                    'trgger' => 'axis',
                    'axisPointer' => (object) [ 'type' => 'cross' ],
                ],
                'toolbox' => (object) [
                    'show' => true,
                    'feature' => (object) [ 'saveAsImage' => (object) [] ],  
                ],
                'xAxis' => (object) [
                    'type' => 'category',
                    'boundaryGap' => false,
                    'data' => $dates,
                ],
                'yAxis' => (object) [
                    'type' => 'value',
                    'axisLabel' => (object) [ 'formatter' => '₱ {value}' ],
                    'axisPointer' => (object) [ 'snap' => true ],
                ],
                'visualMap' => (object) [
                    'show' => false,
                    'dimension' => 0,
                    'pieces' => [ 
                        0 => (object) [ 'lte' => 3, 'color' => 'green'],
                        1 => (object) [ 'lte' => 4, 'color' => 'red'],
                    ],  
                ],
                'series' => [
                    0 => (object) [
                        'name' => 'Sales',
                        'type' => 'line',
                        'smooth' => true,
                        'data' => $figures,
                        'markArea' => (object) [
                            'itemStyle' => (object) [
                                'color' => 'rgba(255, 173, 177, 0.4)',
                            ],
                            'data' => [
                                0 => [
                                    0 => (object) [
                                            'name' => 'Predicted',
                                            'xAxis' => $last_unpredicted_month,
                                        ],
                                    1 => (object) [
                                            'xAxis' => $next_month,
                                        ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            return json_encode($vueChartOption);
        }
}
