<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CountryOfOrigin;
class CountryController extends Controller
{
    public function index(){
        $datamain = CountryModel::orderBy('name')
        ->get();
        return view('admin.countries.index',compact('datamain'));
    }

    public function create()
    {
        if (!get_user_permission('countries', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view("admin.countries.create");
    }

    public function store(Request $request){



        $request->validate([
            'name' =>  [
                'required',
                Rule::unique('country', 'name')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),
            ],
            'name_ar' => 'required',
            'prefix' => 'required',
            'dial_code' => 'required',
            'active' => 'required'
        ]);
        $check      = CountryModel::whereRaw('Lower(name) = ?', [strtolower($request->name)])->whereNull('deleted_at')->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Country Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
            return redirect()->route('admin.countries.index')->with('error',  'Country Already Exist.');
        }
        $data = CountryModel::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'prefix' => $request->prefix,
            'dial_code' => $request->dial_code,
            'active' => $request->active,
        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Country added successfully",
                'id' => $data->id,
                'name' => $request->name
            ]);
        }

        return redirect()->route('admin.countries.index')->with('success',  'Record updated successfully.');

    }


    public function storeOrigin(Request $request){


        $request->validate([
            'name' =>  [
                'required',
                'min:3',
                Rule::unique('country_of_origins', 'name')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),
            ],
        ]);
        $check      = CountryOfOrigin::whereRaw('Lower(name) = ?', [strtolower($request->name)])->whereNull('deleted_at')->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Country Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
            return redirect()->route('admin.countries.index')->with('error',  'Country Already Exist.');
        }
        $data = CountryOfOrigin::create([
            'name' => $request->name,
            'status'=>1,
            'name_ar' => $request->name_ar,

        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Country added successfully",
                'id' => $data->id,
                'name' => $request->name
            ]);
        }

        return redirect()->route('admin.countries.index')->with('success',  'Record updated successfully.');

    }

    public function edit($id)
    {
        if (!get_user_permission('countries', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Country";
        $datamain = CountryModel::find($id);

        if ($datamain) {
            return view("admin.countries.edit", compact('page_heading', 'datamain', 'id'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request, CountryModel $country){

        $request->validate([
            'name' => ['required',Rule::unique('country', 'name')
            ->ignore($request->id)
            ->where(function ($query) {
                // Adjust the query to handle deleted_at correctly
                $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
            })],
            'name_ar' => 'required',
            'prefix' => 'required',
            'dial_code' => 'required',
            'active' => 'required'
        ]);

        $country->update([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'active' => $request->active,
            'prefix' => $request->prefix,
            'dial_code' => $request->dial_code,
        ]);

        return redirect()->route('admin.countries.index')->with('success',  'Record updated successfully.');

    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if ($country = CountryModel::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'country' => CountryModel::where('id', $request->id)->first()]);
    }

    public function destroy(CountryModel $country){
        $emirtes = Emirate::where('country_id', $country->id)->count();
        $status = "0";
        if ($emirtes) {
            $message = "Country cannot be delete, because this country may have association with Emirates.";
        } else {
            $country->delete();
            $message = "Country deleted successfully";
            $status = "1";
        }

        echo json_encode(['status' => $status, 'message' => $message]);
    }
}
