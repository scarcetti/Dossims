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

    public function updateRole(Request $request)
    {
        $role_data = $request->all();
        $role = Role::where('id',$request->id)->first();
        $role->update($role_data);
        return $role;
    }

    public function deleteRole($id)
    {
        return Role::where('id',$id)->delete();
    }

    public function fetchAllRoles()
    {
        return Role::get();
    }
}
