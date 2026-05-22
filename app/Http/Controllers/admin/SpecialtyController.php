<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSpecialities;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SpecialtyController extends Controller
{
    public function index(){
        if (!get_user_permission('specialties', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Specialties";
        $datamain = Specialty::orderBy('id','desc')
        ->get();
        return view('admin.specialties.index',compact('datamain', 'page_heading'));
    }

    public function create()
    {
        if (!get_user_permission('specialties', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view('admin.specialties.create');
    }

    public function store(Request $request){

        if (!get_user_permission('specialties', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'name_en' => [
                'required',
                'min:3',
                Rule::unique('specialties', 'name_en')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),
            ],
            'active' => 'required'
        ]);

        $check      = Specialty::whereRaw('Lower(name_en) = ?', [strtolower($request->name_en)])->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Speciality Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
        }

        $data = Specialty::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'active' => $request->active,
        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Speciality added successfully",
                'id' => $data->id,
                'name' => $request->name_en
            ]);
        }

        return redirect()->route('admin.specialties.index')->with('success',  'Record created successfully.');

    }

    public function edit($id)
    {
        if (!get_user_permission('specialties', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Country";
        $datamain = Specialty::find($id);

        if ($datamain) {
            return view("admin.specialties.edit", compact('page_heading', 'datamain', 'id'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request, Specialty $specialty){
        if (!get_user_permission('specialties', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'active' => 'required'
        ]);

        $specialty->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'active' => $request->active,
        ]);

        return redirect()->route('admin.specialties.index')->with('success',  'Record updated successfully.');

    }

    public function change_status(Request $request)
    {
        if (!get_user_permission('specialties', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($country = Specialty::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'country' => Specialty::where('id', $request->id)->first()]);
    }

    public function destroy(Specialty $specialty){
        if (!get_user_permission('specialties', 'd')) {
            return redirect()->route('admin.restricted_page');
        }

        $specilities = DoctorSpecialities::where('speciality_id', $specialty->id)->count();
        if ($specilities) {
            $message = "Speciality cannot be delete, because this speciality may have association with doctors.";
        } else {
            $specialty->delete();
            $message = "Speciality deleted successfully";
            $status = "1";
        }

        echo json_encode(['status' => 1, 'message' => $message]);
    }
}
