<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!get_user_permission('settings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $datamain = Video::orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.videos.index', compact('datamain'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!get_user_permission('settings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view('admin.videos.create');
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
          //  'title' => 'required|min:3',
        ]);
        /** check if already */
        $check      = Video::whereRaw('Lower(title) = ?', [strtolower($request->title)])->get();

        if ($check->count() > 0) {
            
            if($request->json_action){
                return response()->json([
                    'success' => true, 
                    'message' => "Video Title Already Exist.",
                ]);
            }
        }

        // dd($request->all());
       
            $Video = new Video();

            $Video->title = $request->title;
            $Video->description = $request->description;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Video->file = $file_name;
            }

            $Video->status = $request->status;

            $Video->save();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Video added successfully",
                'id' => $Video->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.videos.index')->with('success',  'Video Saved Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Video"; 
        $Video = Video::find($id);  

        if ($Video) {
            return view("admin.videos.edit", compact('page_heading', 'Video', 'id'));
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

        $page_heading = "Edit Video"; 
        $video = Video::find($id);  

        if ($video) {
            return view("admin.videos.edit", compact('page_heading', 'video', 'id'));
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
          //  'title' => 'required',
        ]); 
    
            // dd($request->all());
 
            $Video = Video::find($id);

            $Video->title = $request->title;
            $Video->description = $request->description;

           if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $Video->file = $file_name;
            } 

          
            $Video->status = $request->status;
            

            $Video->update();



        if($request->json_action){
            return response()->json([
                'success' => true, 
                'message' => "Video Updated successfully",
                'id' => $Video->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.videos.index')->with('success',  'Video Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!get_user_permission('settings', 'd')) {
            return redirect()->route('admin.restricted_page');
        }
        $Video = Video::find($id);
        $Video->delete();
        echo json_encode(['status' => 1, 'message' => "Record deleted successfully"]);
    }

     public function change_status(Request $request)
    {
        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($wellness_tip = Video::where('id', $request->id)->update(['status' => $request->status])) {
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
