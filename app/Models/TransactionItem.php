<?php

namespace App\Models;

use App\Events\TransactionItemCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'branch_product_id',
        'price_at_purchase',
        'quantity',
        'linear_meters',
        'tbd',
        'job_order_note',
    ];

    protected $dispatchesEvents = [
        'created' => TransactionItemCreated::class,
    ];

    public function branchProduct()
    {
        return $this->belongsTo(BranchProduct::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function jobOrder()
    {
        return $this->hasOne(JobOrder::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
