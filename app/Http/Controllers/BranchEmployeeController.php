<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BranchEmployee;

class BranchEmployeeController extends Controller
{
    public function createBranchEmployee(Request $branchEmp)
    {
        return BranchEmployee::create([
            'employee_id'=>$branchEmp->employee_id,
            'branch_id'=>$branchEmp->branch_id,
            'job_order_id'=>$branchEmp->job_order_id,
        ]);
    }

    public function updateBranchEmployee(Request $request)
    {
        $branchEmp_data = $request->all();
        $branchEmp = BranchEmployee::where('id',$request->id)->first();
        $branchEmp->update($branchEmp_data);
        return $branchEmp;
    }

    public function deleteBranchEmployee($id)
    {
        return BranchEmployee::where('id',$id)->delete();
    }

    public function fetchBranchEmployees()
    {
        return BranchEmployee::get();
    }

    public function fetchBranchEmployeeById($id)
    {
        return BranchEmployee::where('id',$id)->get();
    }
    
    public function fetchBranchEmployeeByBranchId($branch_id)
    {
        return BranchEmployee::where('branch_id',$branch_id)->get();
    }
}
