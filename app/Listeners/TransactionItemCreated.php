<?php

namespace App\Listeners;

use App\Events\TransactionItemCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionItemCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionItemCreated  $event
     * @return void
     */
    public function handle(TransactionItemCreated $event)
    {
        $job_order = $event->transaction_items;
    }
}
