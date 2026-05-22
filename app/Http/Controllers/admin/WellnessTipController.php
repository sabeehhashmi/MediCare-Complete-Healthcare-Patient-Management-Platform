<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WellnessTip;

class WellnessTipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!get_user_permission('settings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $datamain = WellnessTip::orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.wellness_tips.index', compact('datamain'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!get_user_permission('settings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view('admin.wellness_tips.create');
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
        ]);
        /** check if already */
        $check      = WellnessTip::whereRaw('Lower(title) = ?', [strtolower($request->title)])->get();

        if ($check->count() > 0) {
            
            if($request->json_action){
                return response()->json([
                    'success' => true, 
                    'message' => "WellnessTip Title Already Exist.",
                ]);
            }
        }

        // dd($request->all());
       
            $WellnessTip = new WellnessTip();

            $WellnessTip->title = $request->title;
            $WellnessTip->description = $request->description;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $WellnessTip->file = $file_name;
            }

            $WellnessTip->status = $request->status;

            $WellnessTip->save();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "WellnessTip added successfully",
                'id' => $WellnessTip->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.wellness_tips.index')->with('success',  'WellnessTip Saved Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit WellnessTip"; 
        $WellnessTip = WellnessTip::find($id);  

        if ($WellnessTip) {
            return view("admin.wellness_tips.edit", compact('page_heading', 'WellnessTip', 'id'));
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

        $page_heading = "Edit WellnessTip"; 
        $WellnessTip = WellnessTip::find($id);  

        if ($WellnessTip) {
            return view("admin.wellness_tips.edit", compact('page_heading', 'WellnessTip', 'id'));
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
        ]); 
    
            // dd($request->all());
 
            $WellnessTip = WellnessTip::find($id);

            $WellnessTip->title = $request->title;
            $WellnessTip->description = $request->description;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $WellnessTip->file = $file_name;
            } 

          
            $WellnessTip->status = $request->status;
            

            $WellnessTip->update();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "WellnessTip Updated successfully",
                'id' => $WellnessTip->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.wellness_tips.index')->with('success',  'WellnessTip Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!get_user_permission('settings', 'd')) {
            return redirect()->route('admin.restricted_page');
        }
        $WellnessTip = WellnessTip::find($id);
        $WellnessTip->delete();
        echo json_encode(['status' => 1, 'message' => "Record deleted successfully"]);
    }

     public function change_status(Request $request)
    {
        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($wellness_tip = WellnessTip::where('id', $request->id)->update(['status' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'datamain' => wellness_tip::where('id', $request->id)->first()]);
    }
}
