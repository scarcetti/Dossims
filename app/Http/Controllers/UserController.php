<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateUser;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function create(Request $user)
    {
      /*   $user_data = $user;
        $user_data['password'] =  bcrypt($user_data['password']);
 */
        return User::createUser([
            'employee_id'=>$user->employee_id,
            'email'=>$user->email,
            'password'=>bcrypt($user->password),
            'status'=>$user->status

        ]);
    }
    public function getUsers()
    {
        return User::get();
        
    }

    public function addSuperadminUser(){
        return view('superadmin.users.add.add');
    }
    public function viewSuperadminUser(){
        return view('superadmin.users.view.view');
    }
    public function addAdminUser(){
        return view('admin.users.add.add');
    }
    public function viewAdminUser(){
        return view('admin.users.view.view');
    }
}
