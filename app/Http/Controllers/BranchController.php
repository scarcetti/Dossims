<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function addSuperadminBranch(){
        return view('superadmin.branches.add.add');
    }
    public function viewSuperadminBranch(){
        return view('superadmin.branches.view.view');
    }
}
