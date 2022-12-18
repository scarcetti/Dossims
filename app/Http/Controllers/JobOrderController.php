<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;
use Illuminate\Queue\Jobs\JobName;

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

    public function updateJobOrder(Request $request)
    {
        $jobOrder_data = $request->all();
        $jobOrder = JobOrder::where('id',$request->id)->first();
        $jobOrder->update($jobOrder_data);
        return $jobOrder;
    }

    public function deleteJobOrder($id)
    {
        return JobOrder::where('id',$id)->delete();
    }

    public function fetchAllJobOrders()
    {
        return JobOrder::get();
    }
    public function viewFrontDeskJobOrder(){
        return view('frontdesk.joborder.view.view');
    }
}
