<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Validator,DB;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    //
    public function index(REQUEST $request){
        $page_heading = "Tickets";
        $list = Product::orderBy('id','desc');
        if($request->status){
            $list = $list->where('product_status',$request->status);
        }
        if($request->from_date){
            $list = $list->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
        }
        if($request->to_date){
            $list = $list->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
        }
        if($request->search_key){
            $list = $list->whereRaw("product_name ilike '%".strtolower($request->search_key)."%'");
        }
        $list = $list->paginate(10);
        return view('admin.product.list',compact('page_heading','list'));
    }
    public function create(REQUEST $request,$id=FALSE){
        $page_heading = "Create Ticket";
        $product_name = '';
        $description  = '';
        $price        = '';
        $product_type = 'daily';
        $description  = '';
        $file_name    = '';
        $drow_date    = '';
        $drow_time    = '';
        $product_status='';
        if($id){
            $page_heading = "Edit Ticket";
            $product      = Product::find($id);
            $product_name = $product->product_name;
            $description  = $product->description;
            $price        = $product->price;
            $product_type = $product->product_type;
            $description  = $product->description;
            $file_name    = $product->file_url;
            $drow_date    = $product->drow_date;
            $drow_time    = $product->drow_time;
            $product_status= $product->product_status;
        }
        return view('admin.product.create',compact('page_heading','id','product_name','description','price','product_type','description','file_name','drow_date','product_status','drow_time'));
    }

    public function save(REQUEST $request){
        
        $status   = "0";
        $message  = "";
        $o_data   = ['redirect'=>route('admin.list_product')];
        $errors   = [];
        $rules= [
            'product_name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'drow_time'   =>'required',
            'product_image'=>'mimes:jpg,jpeg,png,gif'
        ];
        if($request->product_type=="monthly"){
            $rules['drow_date'] = 'required';
        }
        if($request->id == ""){
            $rules['product_image']='required|mimes:jpg,jpeg,png,gif';
        }
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        }else{
            
            if($request->id){
                $product = Product::find($request->id);
            }else{
                $product = new Product();
                $product->product_code=config('global.ticket_prefix')."-".uniqid();
                $product->created_at = gmdate('Y-m-d H:i:s');
                $product->created_by = Auth::user()->id;
            }
                $product->updated_by = Auth::user()->id;
                $product->updated_at = gmdate('Y-m-d H:i:s');
                $product->product_name = $request->product_name;
                $product->product_type = $request->product_type;
                $product->price        = $request->price;
                $product->description  = $request->description;
                $product->drow_date = $request->drow_date??1;
                //echo date('H:i',strtotime($request->drow_time)); exit;
                $product->drow_time    = date('H:i',strtotime($request->drow_time));
                $product->product_status = $request->product_status;
                if($file = $request->file("product_image")){
                    $dir = config('global.upload_path')."/".config('global.product_image_upload_dir');
                    $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                    $file->storeAs(config('global.product_image_upload_dir'),$file_name,config('global.upload_bucket'));
                    $product->file_name = $file_name;
                }
                
                $product->save();
                
                if($request->id){
                    $message = "Ticket updated successfully";
                    $status = "1";
                }else{
                    $message = "Ticket created successfully";
                    $status = "1";
                }
        }
        return response()->json(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }
    public function delete(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";

        

        $category_data = Product::where(['id' => $id])->first();

        if ($category_data) {
            Product::where(['id' => $id])->delete();
            $message = "Product deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Product data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
