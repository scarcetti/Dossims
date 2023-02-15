<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_item_id',
        'value',
        'per_item',
        'fixed_amount',
        'percentage',
    ];
}
