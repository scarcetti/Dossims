<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\job_order;

class JobOrderController extends Controller
{
    public function viewFrontDeskJobOrder(){
        return view('frontdesk.joborder.view.view');
    }
}
