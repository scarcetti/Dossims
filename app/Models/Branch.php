<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'contact_no',
        'city',
        'type',
        'province',
        'address',
        'zipcode',
    ];

    public function branchEmployees()
    {
        return $this->hasMany(BranchEmployee::class);
    }
}
