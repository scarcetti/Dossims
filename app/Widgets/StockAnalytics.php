<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;

class StockAnalytics extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    // protected $top5 = (new \App\Http\Controllers\PredictionController)->top_5_selling();

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('widgets.stock_analytics', [
            // 'top' => (new \App\Http\Controllers\PredictionController)->top_5_selling(),
        ]);
    }

    public function shouldBeDisplayed()
    {
        return in_array(Auth::user()->role->name, ['admin', 'branch_manager', 'general_manager']);
    }
}
