<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BranchEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'branch_id',
    ];

    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', $this->getBranch('id'));
    }

    function getBranch($column=null)
    {
        $user = Auth::user();
        $x = \App\Models\Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();

        if(is_null($column)) {
            return is_null($x) ? false : $x;
        }
        else {
            return is_null($x) ? false : $x->$column;
        }
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
