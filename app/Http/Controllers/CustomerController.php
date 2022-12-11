<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function createCustomer(Request $customer)
    {
        return Customer::create([
            'first_name'=>$customer->first_name,
            'last_name'=>$customer->last_name,
            'birthdate'=>$customer->birthdate,
            'address'=>$customer->address,
            'city'=>$customer->city,
            'province'=>$customer->province,
            'zipcode'=>$customer->zipcode,
            'contact_no'=>$customer->contact_no,
            'email'=>$customer->email,
            'type'=>$customer->type,

        ]);
    }
    public function getCustomers()
    {
        return Customer::get();
    }

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
