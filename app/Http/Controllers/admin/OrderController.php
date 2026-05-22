<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use Validator,DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //
    public function daily(REQUEST $request){
        $page_heading = "Daily Ticket Purchases";
        $list=Orders::where(['product_type'=>'daily'])->OrderBy('id','desc');
        if($request->search_key){
            $list= $list->where(['ticket_number'=>$request->search_key]);
        }
        if($request->drow_date){
            $list= $list->whereDate('drow_date',date('Y-m-d',strtotime($request->drow_date)));
        }
        $list=$list->paginate(10);
        return view('admin.orders.daily_list',compact('page_heading','list'));
    }
    public function monthly(REQUEST $request){
        $page_heading = "Monthly Ticket Purchases";
        $list=Orders::where(['product_type'=>'monthly'])->OrderBy('id','desc');
        if($request->search_key){
            $list= $list->where(['ticket_number'=>$request->search_key]);
        }
        if($request->drow_date){
            $list= $list->whereDate('drow_date',date('Y-m-d',strtotime($request->drow_date)));
        }
        $list=$list->paginate(10);
        return view('admin.orders.monthly_list',compact('page_heading','list'));
    }
}
