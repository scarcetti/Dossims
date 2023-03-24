<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;

class Predictions extends AbstractWidget
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

        return view('widgets.predictions', [
            'config' => $this->config,
        ]);
    }

    public function shouldBeDisplayed()
    {
        return in_array(Auth::user()->role->name, ['admin', 'branch_manager', 'general_manager']);
    }
}
