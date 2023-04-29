<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_item_id',
        'note',
        'status',
    ];

    public function transaction_item()
    {
        return $this->belongsTo(TransactionItem::class);
    }
}
