<?php

namespace App\Widgets;
use Illuminate\Support\Facades\Auth;
use Arrilot\Widgets\AbstractWidget;

class Balances extends AbstractWidget
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

        return view('widgets.balances', [
            'config' => $this->config,
        ]);
    }

    public function shouldBeDisplayed()
    {
        return in_array(Auth::user()->role->name, ['admin', 'branch_manager', 'general_manager', 'frontliner', 'cashier']);
    }
}
