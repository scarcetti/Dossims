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

    public function updateBranch(Request $request)
    {
        $branch_data = $request->all();
        $branch = Branch::where('id',$request->id)->first();
        $branch->update($branch_data);
        return $branch;
    }

    public function deleteBranch($id)
    {
        return Branch::where('id',$id)->delete();
    }

    public function fetchAllBranches()
    {
        return Branch::get();
    }
    
    public function fetchBranchById($id)
    {
        return Branch::where('id',$id)->first();
    }


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

    public function deleteBranchEmp($id)
    {
        return BranchEmployee::where('id',$id)->delete();
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
