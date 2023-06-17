<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransferBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'price',
        'inventory_transfer_id',
        'pcs',
        'meters',
    ];

    public function batches()
    {
        return $this->belongsTo(InventoryTransfer::class);
    }
}
