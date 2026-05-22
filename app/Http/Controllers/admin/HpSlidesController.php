<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\HpPartnerLogo;
use App\Models\HpSlide;

class HpSlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Slides";

        $datamain = HpSlide::orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.hp_slides.index', compact('datamain', 'page_heading'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Add Slide";

        return view('admin.hp_slides.create', compact('page_heading'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'image.*' => 'mimes:jpeg,png,jpg|max:2048',
        ]);
 
        // dd($request->all());
       
            $Banner = new HpSlide();

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.homepage_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Banner->image = $file_name;
            }

            $Banner->status = $request->status;

            $Banner->save();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Slide added successfully",
                'id' => $Banner->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.hp-slides.index')->with('success',  'Slide Saved Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Slide"; 
        $Banner = HpSlide::find($id);  

        if ($Banner) {
            return view("admin.hp_slides.edit", compact('page_heading', 'Banner', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Slide"; 
        $Banner = HpSlide::find($id);  

        if ($Banner) {
            return view("admin.hp_slides.edit", compact('page_heading', 'Banner', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'image.*' => 'mimes:jpeg,png,jpg|max:2048',
        ]); 
    
            // dd($request->all());
 
            $Banner = HpSlide::find($id);

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.homepage_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Banner->image = $file_name;
            } 

            if ($request->hasfile('image')) {
            $Banner->status = $request->status;
            }

            $Banner->update();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Logo Updated successfully",
                'id' => $Banner->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.hp-slides.index')->with('success',  'Logo Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $Banner = HpSlide::find($id);
        $Banner->delete();
        echo json_encode(['status' => 1, 'message' => "Record deleted successfully"]);
    }

     public function change_status(Request $request)
    {
        if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($banner = HpSlide::where('id', $request->id)->update(['status' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'datamain' => HpSlide::where('id', $request->id)->first()]);
    }
}
