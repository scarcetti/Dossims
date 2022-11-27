<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
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
