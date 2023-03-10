<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

class Inventory extends AbstractWidget
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
        /*return view('widgets.cutting_list', [
            'config' => $this->config,
        ]);*/

        $branch_id = $this->getBranch('id');
        $count = \App\Models\BranchProduct::when($branch_id, function($q) use ($branch_id) {
                return $q->where('branch_id', $branch_id);
            })->count();
        $string = 'Stocks';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-window-list',
            'title'  => "{$count} {$string}",
            'text'   => __('voyager::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => 'View all ' . $string,
                'link' => env('APP_URL') . '/admin/branch-products',
                // 'link' => route('voyager.posts.index'),
            ],
            'image' => '/widget-bg/steel-cuts',
        ]));
    }

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


    public function shouldBeDisplayed()
    {
        return Auth::user()->role->name == 'inventory_manager';
    }
}
