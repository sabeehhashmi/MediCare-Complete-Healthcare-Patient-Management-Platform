<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!get_user_permission('settings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $datamain = Banner::orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.banner.index', compact('datamain'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!get_user_permission('settings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         if (!get_user_permission('settings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'title' => 'required|min:3',
            'image.*' => 'mimes:jpeg,png,jpg|max:2048',
        ]);
        /** check if already */
        $check      = Banner::whereRaw('Lower(title) = ?', [strtolower($request->title)])->get();

        if ($check->count() > 0) {
            
            if($request->json_action){
                return response()->json([
                    'success' => true, 
                    'message' => "Banner Title Already Exist.",
                ]);
            }
        }

        // dd($request->all());
       
            $Banner = new Banner();

            $Banner->title = $request->title;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Banner->image = $file_name;
            }

            $Banner->status = $request->status;

            $Banner->save();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Banner added successfully",
                'id' => $Banner->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.banners.index')->with('success',  'Banner Saved Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Banner"; 
        $Banner = Banner::find($id);  

        if ($Banner) {
            return view("admin.banner.edit", compact('page_heading', 'Banner', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Banner"; 
        $Banner = Banner::find($id);  

        if ($Banner) {
            return view("admin.banner.edit", compact('page_heading', 'Banner', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'title' => 'required',
            'image.*' => 'mimes:jpeg,png,jpg|max:2048',
        ]); 
    
            // dd($request->all());
 
            $Banner = Banner::find($id);

            $Banner->title = $request->title;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Banner->image = $file_name;
            } 

            if ($request->hasfile('image')) {
            $Banner->status = $request->status;
            }

            $Banner->update();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Banner Updated successfully",
                'id' => $Banner->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.banners.index')->with('success',  'Banner Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!get_user_permission('settings', 'd')) {
            return redirect()->route('admin.restricted_page');
        }
        $Banner = Banner::find($id);
        $Banner->delete();
        echo json_encode(['status' => 1, 'message' => "Record deleted successfully"]);
    }

     public function change_status(Request $request)
    {
        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($banner = Banner::where('id', $request->id)->update(['status' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'datamain' => banner::where('id', $request->id)->first()]);
    }
}
