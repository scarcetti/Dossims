<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;


class RoleController extends Controller
{
    public function createRole(Request $roles)
    {
        return Role::create([
            'name'=>$roles->name,
        ]);
    }
    public function getRoles()
    {
        return Role::get();
    }
}
