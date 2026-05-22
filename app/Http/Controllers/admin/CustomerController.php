<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator,DB;
use Illuminate\Support\Facades\Auth;
class CustomerController extends Controller
{
    //
    public function index(REQUEST $request){
        $page_heading = "Customers";
        $list = User::where('role',3)->orderBy('id','desc');
        if($request->status){
            $list = $list->where('active',$request->status);
        }
        if($request->from_date){
            $list = $list->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
        }
        if($request->to_date){
            $list = $list->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
        }
        if($request->search_key){
            $list = $list->whereRaw("name ilike '%".strtolower($request->search_key)."%'");
        }
        $list = $list->paginate(10);
        return view('admin.users.list',compact('page_heading','list'));
    }
    public function delete(REQUEST $request,$id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $user = User::find($id);
        if ($user) {
            $user->email = $user->email."__deleted_account_app".$user->id;
            $user->phone = $user->phone."__deleted_account_app".$user->id;
            $user->deleted = 1;
            $user->active = 0;
            $user->save();
            $status = "1";
            $message = "Customer removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function view_user_referls(REQUEST $request,$id=0){
        $page_heading = "View Referral Customers";
        $list = User::where('role',3)->where(['refered_by'=>$id])->orderBy('id','desc');
        if($request->status){
            $list = $list->where('active',$request->status);
        }
        if($request->from_date){
            $list = $list->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
        }
        if($request->to_date){
            $list = $list->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
        }
        if($request->search_key){
            $list = $list->whereRaw("name ilike '%".strtolower($request->search_key)."%'");
        }
        $list = $list->paginate(10);

        $selected_customer = User::where(['id'=>$id])->get()->first();
        return view('admin.users.referel_list',compact('page_heading','list','id','selected_customer'));
    }
}
