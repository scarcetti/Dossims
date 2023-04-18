<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'updated_at_payment_id',
        'outstanding_balance',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
