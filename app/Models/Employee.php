<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    // public $additional_attributes = ['full_name'];
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birthdate',
        'contact_no',
        'address',
        'city',
        'province',
        'zipcode',
        'full_name',
    ];

    // public function getFullNameAttribute()
    // {
    //     return "{$this->first_name} {$this->last_name}";
    // }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
