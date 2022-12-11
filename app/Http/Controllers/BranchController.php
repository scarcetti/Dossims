<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchEmployee;

class BranchController extends Controller
{

    public function createBranch(Request $branch)
    {
        return Branch::create([
            'name'=>$branch->name,
            'contact_no'=>$branch->contact_no,
            'address'=>$branch->address,
            'city'=>$branch->city,
            'province'=>$branch->province,
            'zipcode'=>$branch->zipcode,
            'type'=>$branch->type,
        ]);
    }
    public function getBranches()
    {
        return Branch::get();
    }

    public function createBranchEmployee(Request $branchEmp)
    {
        return BranchEmployee::create([
            'employee_id'=>$branchEmp->employee_id,
            'branch_id'=>$branchEmp->branch_id,
            'job_order_id'=>$branchEmp->job_order_id,
        ]);
    }
    public function getBranchEmployees()
    {
        return BranchEmployee::get();
    }

    public function addSuperadminBranch(){
        return view('superadmin.branches.add.add');
    }
    public function viewSuperadminBranch(){
        return view('superadmin.branches.view.view');
    }
}
