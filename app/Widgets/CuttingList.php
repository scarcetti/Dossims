<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

class CuttingList extends AbstractWidget
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

        $count = \App\Models\Transaction::where('status', 'procuring')->count();
        $string = 'Pending orders';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-window-list',
            'title'  => "{$count} {$string}",
            'text'   => __('voyager::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => 'View all ' . $string,
                'link' => env('APP_URL') . '/admin/cutting-list',
                // 'link' => route('voyager.posts.index'),
            ],
            'image' => '/widget-bg/steel-cuts',
        ]));
    }

    public function shouldBeDisplayed()
    {
        return Auth::user()->role->name == 'laborer';
    }
}
