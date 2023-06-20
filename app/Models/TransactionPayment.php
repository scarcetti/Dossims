<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount_paid',
        'payment_type_id',
        'payment_method_id',
        'remarks',
        'downpayment_id',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function delivery_fees()
    {
        return $this->hasOne(DeliveryFees::class);
    }

    public function downpayment()
    {
        return $this->belongsTo(TransactionPayment::class, 'downpayment_id', 'id');
    }

    public function final_payment()
    {
        // return $this->hasOne(TransactionPayment::class, 'id', 'downpayment_id');
        return $this->belongsTo(TransactionPayment::class, 'id', 'downpayment_id');
    }
}
