<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AgentUserDetail;
use App\Models\Area;
use App\Models\CallCenterUserDetail;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\Hospital;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function index() {
        if (!get_user_permission('areas', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $datamain = Area::with(['country' => function($query) {
                $query->withTrashed();
            }, 'emirate' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.areas.index', compact('datamain'));
    }


    public function create()
    {
        if (!get_user_permission('areas', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $countries = CountryModel::all();
        $emirates = Emirate::all();

        return view('admin.areas.create', compact('countries', 'emirates'));
    }

    public function store(Request $request){
        if (!get_user_permission('areas', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'name_en' => [
                'required',
                function ($attribute, $value, $fail) {
                    $cleanedValue = preg_replace('/\s+/', ' ', trim($value));
                    if (\DB::table('areas')->where('name_en', $cleanedValue)->exists()) {
                        $fail('The name has already been taken.');
                    }
                },
            ],
            'country_id' => 'required',
            'emirate_id' => 'required',
        ]);
/** check if already */
        $check      = Area::whereRaw('Lower(name_en) = ?', [strtolower($request->name_en)])->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Area Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
        }
       $data =  Area::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'country_id' => $request->country_id,
            'emirate_id' => $request->emirate_id,
            'active' => $request->active,
        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Area added successfully",
                'id' => $data->id,
                'name' => $request->name_en
            ]);
        }
        return redirect()->route('admin.areas.index')->with('success',  'Record created successfully.');

    }

    public function edit($id)
    {
        if (!get_user_permission('areas', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Area";
        $datamain = Area::with('country', 'emirate')->find($id);
        $countries = CountryModel::all();
        $emirates = Emirate::all();

        if ($datamain) {
            return view("admin.areas.edit", compact('page_heading', 'datamain', 'id', 'countries', 'emirates'));
        } else {
            abort(404);
        }
    }


    public function update(Request $request, Area $area){
        if (!get_user_permission('areas', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $request->validate([
            'name_en' => 'required',
            'country_id' => 'required',
            'emirate_id' => 'required',
            'active' => 'required'
        ]);

        $area->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'country_id' => $request->country_id,
            'emirate_id' => $request->emirate_id,
            'active' => $request->active,
        ]);

        return redirect()->route('admin.areas.index')->with('success',  'Record updated successfully.');

    }

    public function change_status(Request $request)
    {
        if (!get_user_permission('areas', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $status = "0";
        $message = "";
        if ($country = Area::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'country' => Area::where('id', $request->id)->first()]);
    }

    public function destroy(Area $area){
        if (!get_user_permission('areas', 'd')) {
            return redirect()->route('admin.restricted_page');
        }
        $agents = AgentUserDetail::where('area_id', $area->id)->count();
        $call_centers = CallCenterUserDetail::where('area_id', $area->id)->count();
        $hospitals = Hospital::where('area_id', $area->id)->count();
        $status = "0";
        if ($agents || $call_centers || $hospitals) {
            if ($agents) {
                $associated = 'agents';
            } else if ($call_centers) {
                $associated = 'call centers';
            } else {
                $associated = 'hospitals';
            }
            $message = "Area cannot be delete, because this area may have association with " . $associated;
        } else {
            $area->delete();
            $message = "Area deleted successfully";
            $status = "1";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function getAreas($emirateId) {
        $areas = Area::where('emirate_id', $emirateId)->where(['active'=>1])->orderBy('name_en','asc')->get();
        return response()->json($areas);
    }


}
