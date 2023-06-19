<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    use HasFactory;
    // public $additional_attributes = ['full_name'];
    protected  $fillable = [
        'id',
        'first_name',
        'last_name',
        'contact_no',
        'branch_id',
        'full_name',
        'address',
    ];

    // public function getFullNameAttribute()
    // {
    //     return "{$this->first_name} {$this->last_name}";
    // }

    public function balance()
    {
        return $this->hasOne(Balance::class);
    }
}
