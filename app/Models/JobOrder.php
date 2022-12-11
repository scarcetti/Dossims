<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_start',
        'contract_end',
        'time_in',
        'time_out',
        'daily_rate',
        'currency',
    ];
}
