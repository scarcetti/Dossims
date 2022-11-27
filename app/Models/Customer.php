<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected  $fillable = [
        'id',
        'first_name',
        'last_name',
        'birthdate',
        'address',
        'city',
        'province',
        'zipcode',
        'contact_no',
        'email',
        'type'
    ];
}
