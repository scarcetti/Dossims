<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;


class OtherBranchProducts extends AbstractWidget
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

        /*return view('widgets.other_branch_products', [
            'config' => $this->config,
        ]);*/

        $count = \App\Models\BranchProduct::count();
        $string = 'Branches';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-shop',
            'title'  => "View other branch stocks",
            'text'   => "Check products on other branches",
            'button' => [
                'text' => 'View',
                'link' => env('APP_URL') . '/admin/inventory',
                // 'link' => route('voyager.posts.index'),
            ],
            'image' => '/widget-bg/hardware.jpg',
        ]));
    }

    public function shouldBeDisplayed()
    {
        return Auth::user()->role->name == 'inventory_manager';
        // return true;
    }
}
