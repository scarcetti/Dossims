<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryFees extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_payment_id',
        'outside_brgy',
        'long',
        'distance',
        'total',
    ];
}
