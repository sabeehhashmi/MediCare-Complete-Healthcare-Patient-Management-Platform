<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Emirate;
use App\Models\CountryModel;
use Illuminate\Http\Request;

class EmiratesController extends Controller
{
    public function index() {
        if (!get_user_permission('emirates', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $datamain = Emirate::with(['country' => function($query) {
            $query->withTrashed();
        }])
        ->where(['active'=>1])
        ->orderBy('id', 'desc')
        ->get();

        return view('admin.emirates.index', compact('datamain'));
    }
    public function create()
    {
        if (!get_user_permission('emirates', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $countries = CountryModel::all();
        return view('admin.emirates.create', compact('countries'));
    }

    public function store(Request $request){
        if (!get_user_permission('emirates', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'name_en' => [
                'required',
                'unique:emirates,name_en',
                function ($attribute, $value, $fail) {
                    $cleanedValue = preg_replace('/\s+/', ' ', trim($value));
                    if (\DB::table('emirates')->where('name_en', $cleanedValue)->exists()) {
                        $fail('The :attribute has already been taken.');
                    }
                },
            ],
            'country_id' => 'required|exists:country,id'
        ]);

        $check      = Emirate::whereRaw('Lower(name_en) = ?', [strtolower($request->name_en)])->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Emirate Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
            return redirect()->route('admin.emirates.index')->with('error',  'Record already Exist.');
        }

        $data = Emirate::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'active' => $request->active,
            'country_id' => $request->country_id,
        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Emirate added successfully",
                'id' => $data->id,
                'name' => $request->name_en
            ]);
        }

        return redirect()->route('admin.emirates.index')->with('success',  'Record created successfully.');

    }

    public function edit($id)
    {
        if (!get_user_permission('emirates', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Country";
        $datamain = Emirate::with('country')->find($id);
        $countries = CountryModel::all();

        if ($datamain) {
            return view("admin.emirates.edit", compact('page_heading', 'datamain', 'id', 'countries'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request, Emirate $emirate){
        if (!get_user_permission('emirates', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $request->validate([
            'name_en' => 'required',
            'active' => 'required',
            'country_id' => 'required|exists:country,id'
        ]);

        $emirate->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'active' => $request->active,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('admin.emirates.index')->with('success',  'Record updated successfully.');

    }

    public function change_status(Request $request)
    {
        if (!get_user_permission('emirates', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($country = Emirate::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'country' => Emirate::where('id', $request->id)->first()]);
    }

    public function destroy(Emirate $emirate){
        if (!get_user_permission('emirates', 'd')) {
            return redirect()->route('admin.restricted_page');
        }
        $areas = Area::where('emirate_id', $emirate->id)->count();
        $status = "0";
        if ($areas) {
            $message = "Emirate cannot be delete, because this emirate may have association with area.";
        } else {
            $emirate->delete();
            $message = "Emirate deleted successfully";
            $status = "1";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }


    public function getEmirates($countryId) {
        $emirates = Emirate::where('country_id', $countryId)->orderBy('name_en','asc')->get();
        return response()->json($emirates);
    }

}
