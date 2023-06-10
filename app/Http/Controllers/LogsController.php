<?php

namespace App\Http\Controllers;
use App\Models\GeneralLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function fetchLogs()
    {

    }

    public function logAdded($remarks)
    {
        $employee_id = Auth::user()->id ?? null;

        $general_log = GeneralLog::create([
                'employee_id' => $employee_id,
                'remarks' => $remarks,
        ]);

        return $general_log;
    }
}
