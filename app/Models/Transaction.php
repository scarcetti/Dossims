<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'employee_id',
        'cashier_id',
        'branch_id',
        'transaction_payment_id',
        'business_customer_id',
        'status',
        'transaction_placement',
        'created_at',
        'updated_at',
        'txno',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function businessCustomer()
    {
        return $this->belongsTo(BusinessCustomer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(Employee::class, 'cashier_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function payment()
    {
        return $this->belongsTo(TransactionPayment::class, 'transaction_payment_id');
    }
}
