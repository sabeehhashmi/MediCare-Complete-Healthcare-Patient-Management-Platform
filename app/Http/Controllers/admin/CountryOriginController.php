<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\CountryOfOrigin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryOriginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page_heading = "Country Origins";
        $countries = CountryOfOrigin::orderBy('name')
            ->get();
        return view('admin.country-of-origins.index',compact('countries', 'page_heading'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_heading = "Create Country Origin";
        if (!get_user_permission('countries', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        return view("admin.country-of-origins.create", compact('page_heading'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>  [
                'required',
                Rule::unique('country_of_origins', 'name')
                    ->ignore($request->id)
                    ->where(function ($query) {
                        // Adjust the query to handle deleted_at correctly
                        $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                    }),
            ],
            'name_ar' => 'required',
            'active' => 'required'
        ]);
        $check      = CountryOfOrigin::whereRaw('Lower(name) = ?', [strtolower($request->name)])->whereNull('deleted_at')->get();

        if ($check->count() > 0) {

            if($request->json_action){
                return response()->json([
                    'success' => true,
                    'message' => "Country of Origin Already Exist.",
                    'id' => '',
                    'name' => ''
                ]);
            }
            return redirect()->route('admin.country-of-origin.index')->with('error',  'Country of Origin Already Exist.');
        }
        $data = CountryOfOrigin::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->active,
        ]);

        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Country of Origin added successfully",
                'id' => $data->id,
                'name' => $request->name
            ]);
        }

        return redirect()->route('admin.country-of-origin.index')->with('success',  'Record updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!get_user_permission('countries', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Country Origin";
        $datamain = CountryOfOrigin::find($id);

        if ($datamain) {
            return view("admin.country-of-origins.edit", compact('page_heading', 'datamain', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $country = CountryOfOrigin::findOrFail($id);
        $request->validate([
            'name' => ['required',Rule::unique('country_of_origins', 'name')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                })],
            'name_ar' => 'required',
            'active' => 'required'
        ]);

        $country->update([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->active,
        ]);

        return redirect()->route('admin.country-of-origin.index')->with('success',  'Record updated successfully.');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if ($country = CountryOfOrigin::where('id', $request->id)->update(['status' => $request->status])) {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = CountryOfOrigin::findOrFail($id);
        $country->delete();
        $message = "Country deleted successfully";
        $status = "1";

        echo json_encode(['status' => $status, 'message' => $message]);
    }
}
