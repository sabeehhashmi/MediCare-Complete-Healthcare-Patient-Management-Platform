<?php
// app/Http/Controllers/admin/ProductTagController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductTagController extends Controller
{
    public function index()
    {
        if (!get_user_permission('product_tags', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Product Tags";
        $list = ProductTag::orderBy('id', 'desc')->get();
        return view('admin.product_tags.list', compact('page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('product_tags', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = 'Product Tag';
        $tag = null;

        if ($id) {
            $id = decrypt($id);
            $tag = ProductTag::find($id);
        }
        
        return view('admin.product_tags.create', compact('page_heading', 'id', 'tag'));
    }

    public function submit(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $o_data['redirect'] = route('admin.product_tags.list');

        $rules = [
            'name_en' => [
                'required',
                'min:2',
                Rule::unique('product_tags', 'name_en')
                    ->ignore($request->id)
                    ->whereNull('deleted_at'),
            ],
            'name_ar' => 'required',
            'name_bn' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            DB::beginTransaction();
            try {
                $data = [
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_ar,
                    'name_bn' => $request->name_bn,
                    'slug' => Str::slug($request->name_en),
                    'description' => $request->description,
                    'color' => $request->color ?? '#1baeff',
                    'status' => $request->status ?? 0,
                    'last_updated_by' => Auth::user()->id ?? 0
                ];

                if ($request->id) {
                    $tag = ProductTag::find($request->id);
                    if (!$tag) {
                        throw new Exception("Tag not found");
                    }
                    $tag->update($data);
                    $message = "Tag updated successfully";
                } else {
                    $data['created_by'] = Auth::user()->id ?? 0;
                    ProductTag::create($data);
                    $message = "Tag added successfully";
                }

                DB::commit();
                $status = "1";
                
            } catch (Exception $e) {
                DB::rollback();
                $message = "Failed to save tag: " . $e->getMessage();
            }
        }

        return response()->json(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $id = $request->id;

        $tag = ProductTag::find($id);
        if ($tag) {
            $tag->status = $request->status;
            $tag->save();
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Failed to change status";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function delete($id)
    {
        $status = "0";
        $message = "";

        try {
            $id = decrypt($id);
            $tag = ProductTag::find($id);
            
            if ($tag) {
                $tag->delete();
                $message = "Tag deleted successfully";
                $status = "1";
            } else {
                $message = "Tag not found";
            }
        } catch (Exception $e) {
            $message = "Invalid ID format";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }
}