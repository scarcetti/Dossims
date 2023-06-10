<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'remarks',
        'interacting_employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
