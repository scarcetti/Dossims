<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Branch;

use Illuminate\Support\Facades\DB;
use App\Models\BranchEmployee;



class BranchController extends Controller
{

    public function createBranch(Request $branch)
    {
        $display = 'superadmin.branches.add.add';
        $createBranch = Branch::create([
            'name'=>$branch->name,
            'contact_no'=>$branch->contact_no,
            'address'=>$branch->address,
            'city'=>$branch->city,
            'province'=>$branch->province,
            'zipcode'=>$branch->zipcode,
            'type'=>$branch->type,
        ]);
        return view($display, compact('createBranch'));
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
        $branches = Branch::select(DB::raw(
                'branches.*,
                count(branch_employees.id) as employee_count'
            ))
            ->leftJoin('branch_employees', 'branch_employees.branch_id', '=', 'branches.id')
            ->groupBy(
                'branches.id',
                'branches.name',
                'branches.contact_no',
                'branches.city',
                'branches.type',
                'branches.province',
                'branches.address',
                'branches.zipcode',
                'branches.created_at',
                'branches.updated_at',
            )
            ->get();

        $display = 'superadmin.branches.view.view';

        return view($display, compact('branches'));
        // return $branches;
    }
    
    public function fetchBranchById($id)
    {
        $branches = Branch::select(DB::raw(
            'branches.*,
            count(branch_employees.id) as employee_count'
        ))
        ->leftJoin('branch_employees', 'branch_employees.branch_id', '=', 'branches.id')
        ->groupBy(
            'branches.id',
            'branches.name',
            'branches.contact_no',
            'branches.city',
            'branches.type',
            'branches.province',
            'branches.address',
            'branches.zipcode',
            'branches.created_at',
            'branches.updated_at',
        )
        ->where('branches.id',$id)->first();
        return $branches;
        // return Branch::where('id',$id)->first();
    }

    /* public function createBranchEmployee(Request $branchEmp)
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

    public function fetchBranchEmployeeById($branch_id)
    {
        return BranchEmployee::where('branch_id',$branch_id)->get();
    }
 */
    

    public function addSuperadminBranch(){
        return view('superadmin.branches.add.add');
    }
   /*  public function viewSuperadminBranch(){
        return view('superadmin.branches.view.view');
    } */
}
