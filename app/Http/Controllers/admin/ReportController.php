<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use Validator,DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function report_daily_tickets(REQUEST $request){
        $page_heading = "Daily Ticket Purchases";
        $list=[];
        return view('admin.report.daily_list',compact('page_heading','list'));
    }
    public function report_monthly_tickets(REQUEST $request){
        $page_heading = "Monthly Ticket Purchases";
        $list=[];
        return view('admin.report.monthly_list',compact('page_heading','list'));
    }
    public function report_customers(REQUEST $request){
        $page_heading = "Customers";
        $list=[];
        return view('admin.report.customer_list',compact('page_heading','list'));
    }
}
