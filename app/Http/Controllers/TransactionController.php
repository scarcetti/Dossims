<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function completedSuperadminTransaction(){
        return view('superadmin.transactions.completed.completed');
    }
    public function pendingSuperadminTransaction(){
        return view('superadmin.transactions.pending.pending');
    }
    public function completedAdminTransaction(){
        return view('admin.transactions.completed.completed');
    }
    public function pendingAdminTransaction(){
        return view('admin.transactions.pending.pending');
    }
    public function completedFrontdeskTransaction(){
        return view('frontdesk.transactions.completed.completed');
    }
    public function pendingFrontdeskTransaction(){
        return view('frontdesk.transactions.pending.pending');
    }
}
