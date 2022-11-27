<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function addSuperadminEmployee(){
        return view('superadmin.employees.add.add');
    }
    public function viewSuperadminEmployee(){
        return view('superadmin.employees.view.view');
    }
    public function addAdminEmployee(){
        return view('admin.employees.add.add');
    }
    public function viewAdminEmployee(){
        return view('admin.employees.view.view');
    }
}
