<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;

class BranchStocks extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('widgets.branch_stocks', [
            'config' => $this->config,
        ]);
    }

    public function shouldBeDisplayed()
    {
        return Auth::user()->role->name == 'inventory_manager';
    }
}
