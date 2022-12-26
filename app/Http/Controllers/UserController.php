<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function createUser(Request $user)
    {
      /*   $user_data = $user;
        $user_data['password'] =  bcrypt($user_data['password']);
 */
        return User::create([
            'employee_id'=>$user->employee_id,
            'email'=>$user->email,
            'password'=>bcrypt($user->password),
            'status'=>$user->status,
            'role_id'=>$user->role_id

        ]);
    }

    public function updateUser(Request $request)
    {
        $user_data = $request->all();
        $user = User::where('id',$request->id)->first();
        $user->update($user_data);
        return $user;
    }

    public function deleteUser($id)
    {
        return User::where('id',$id)->delete();
    }

    public function fetchAllUsers()
    {
        return User::get();
        
    }

    public function test()
    {
        return DB::select('select * from branches');
        
        
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
