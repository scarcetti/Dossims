<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'outstanding_balance',
        'amount_paid',
        'remarks',
        'created_at',
    ];
}
