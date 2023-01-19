<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function createEmployee(Request $employee)
    {
        return Employee::create([
            'first_name'=>$employee->first_name,
            'middle_name'=>$employee->middle_name,
            'last_name'=>$employee->last_name,
            'gender'=>$employee->gender,
            'birthdate'=>$employee->birthdate,
            'address'=>$employee->address,
            'city'=>$employee->city,
            'province'=>$employee->province,
            'zipcode'=>$employee->zipcode,
            'contact_no'=>$employee->contact_no,

        ]);
    }

    public function updateEmployee(Request $request)
    {
        $employee_data = $request->all();
        $employee = Employee::where('id',$request->id)->first();
        $employee->update($employee_data);
        return $employee;
    }

    public function deleteEmployee($id)
    {
        return Employee::where('id',$id)->delete();
    }

    public function fetchAllEmployees()
    {
        $employees = Employee::get();
        $display = 'superadmin.employees.view.view';
        return view($display, compact('employees'));
        // return Employee::get();
    }

    public function fetchEmployeeById($id)
    {
        return Employee::where('id',$id)->get();
    }

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
