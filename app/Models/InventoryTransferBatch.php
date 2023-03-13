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
    ];

    public function batches()
    {
        return $this->hasMany(InventoryTransfer::class);
    }
}
