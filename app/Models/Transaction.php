<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'business_customer_id',
        'employee_id',
        'transaction_id',
        'status',
        'transaction_placement',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
