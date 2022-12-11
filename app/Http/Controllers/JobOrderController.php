<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;

class JobOrderController extends Controller
{
    public function createJobOrder(Request $jobOrder)
    {
        return JobOrder::create([
            'contract_start'=>$jobOrder->contract_start,
            'contract_end'=>$jobOrder->contract_end,
            'time_in'=>$jobOrder->time_in,
            'time_out'=>$jobOrder->time_out,
            'daily_rate'=>$jobOrder->daily_rate,
            'currency'=>$jobOrder->currency,

        ]);
    }
    public function getJobOrders()
    {
        return JobOrder::get();
    }
    public function viewFrontDeskJobOrder(){
        return view('frontdesk.joborder.view.view');
    }
}
