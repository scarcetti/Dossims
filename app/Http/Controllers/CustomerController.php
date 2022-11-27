<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function viewSuperadminCustomer(){
        return view('superadmin.customers.view.view');
    }
    public function addSuperadminCustomer(){
        return view('superadmin.customers.add.add');
    }
    public function viewAdminCustomer(){
        return view('admin.customers.view.view');
    }
    public function addAdminCustomer(){
        return view('admin.customers.add.add');
    }
   /*  public function addFrontdeskCustomer(){
        return view('frontdesk.customers.add.add');
    } */
    public function viewFrontdeskCustomer(){
        return view('frontdesk.customers.view.view');
    }
}
