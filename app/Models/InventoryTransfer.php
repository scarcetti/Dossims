<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'direction',
        'arrival_date',
        'referrer',
        'referrer_contact',
        'distributor_id',
        'receiver_branch_id',
        'sender_branch_id',
        'employee_id',
    ];

    public function batch()
    {
        return $this->hasMany(InventoryTransferBatch::class);
    }

    public function sender()
    {
        return $this->belongsTo(Branch::class, 'sender_branch_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
