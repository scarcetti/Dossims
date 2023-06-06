<?php

namespace App\Events;

use App\Models\JobOrder;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionItemCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransactionItem $transaction_item)
    {
        // function checks if readymade
        $ready_made = Product::whereHas('branch_product.transaction_item', function($q) use($transaction_item) {
            // $q->where('id', $transaction_item->id)->where('ready_made', false);
            $q->where('id', $transaction_item->id);
        })->first('ready_made')->ready_made;

        if( !$ready_made ) {
            JobOrder::create([
                'transaction_item_id' => $transaction_item->id,
                'status' => 'in progress'
            ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
