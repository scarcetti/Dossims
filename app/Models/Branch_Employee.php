<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'branch_id',
        'job_order_id',
    ];
}
