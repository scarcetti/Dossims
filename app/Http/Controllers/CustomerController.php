<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function viewAdminCustomer(){
        return view('admin.customer.view.view');
    }
}
