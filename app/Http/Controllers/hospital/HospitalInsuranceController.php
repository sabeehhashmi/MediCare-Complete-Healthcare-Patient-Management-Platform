<?php

namespace App\Http\Controllers\hospital;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use App\Models\DepartmentModel;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorHolidays;
use App\Models\DoctorInstantAppointment;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\SpecialIntrests;
use App\Models\Languages;
use App\Models\LicenceType;
use App\Models\Qualifications;
use App\Models\HospitalInsurancesModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\SubInsurencePolicy;
use App\Models\Emirate;
use App\Models\InsurencePolicy;
use App\Models\HospitalImage;
use App\Models\HospitalInsurancePolicy;
use Illuminate\Support\Facades\Hash;

use DataTables;

class HospitalInsuranceController extends Controller
{
    public function index()
    {   
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $name       = $hospital->name_en;
        $page_heading = "Our Insurance" ; 
        $module_heading = "Hospital Profile" ; 
        $insurances = HospitalInsurancePolicy::where('hospital_id', $hospitalId)
        ->get()
        ->groupBy('insurance_id')
        ->map(function ($group, $insuranceId) {
            return [
                'insurance_id' => $insuranceId, // Add insurance_id
                'sub_insurances' => $group->pluck('sub_insurance_id')->toArray(), // Collect sub_insurance_ids
            ];
        })
        ->values(); 
        // dd($insurances);
        return view("hospital.our_insurance",  compact('page_heading', 'module_heading', 'name','hospital','insurances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $page_heading = "Insurance";
        $module_heading = "Hospital Profile";
        $mode = "create";
        $id = "";
        $department_manager = "";
        $department_name = "";
        $email = "";
        $phone = "";
        $dial_code = "";
        $insurancepolices = InsurencePolicy::orderBy('title', 'asc')->get()
        ->unique('title')
        ->values();
    
        $subinsurancepolices = SubInsurencePolicy::with('insurence_with_trashed')
        ->orderBy('title', 'asc')
        ->get()
        ->unique('title')
        ->values();
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $name       = $hospital->name_en;
        $insurance_id = "";
        $subinsurance_id = [];
        $id = "";
        $insurances = HospitalInsurancePolicy::where('hospital_id', $hospitalId)
        ->get()
        ->groupBy('insurance_id')
        ->map(function ($group, $insuranceId) {
            return [
                'insurance_id' => $insuranceId, // Add insurance_id
                'sub_insurances' => $group->pluck('sub_insurance_id')->toArray(), // Collect sub_insurance_ids
            ];
        })
        ->values();

        
        return view("hospital.createhospital_insurance", compact('page_heading','module_heading', 'id','name','insurance_id','subinsurance_id','hospital','subinsurancepolices','insurancepolices','mode','id','department_manager','department_name','email','phone','dial_code','insurances'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $input = $request->all();
        
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id', $loginuserid)->first();
        $hospitalId  = $hospital->id;
        $check = HospitalInsurancePolicy::where('hospital_id', $hospital->id)
        ->where('insurance_id', $request->insurance)
        ->first();
        if ($check) {

            $check = HospitalInsurancePolicy::where('hospital_id', $hospital->id)
                ->where('insurance_id', $request->insurance)
                ->delete();
        //     $page_heading = "Insurance";
        //     $module_heading = "Hospital Profile";
        //     $mode = "create";
        //     $id = "";
        //     $department_manager = "";
        //     $department_name = "";
        //     $email = "";
        //     $phone = "";
        //     $dial_code = "";
        //     $insurancepolices = InsurencePolicy::orderBy('title','asc')->get();
        //     $subinsurancepolices = SubInsurencePolicy::with('insurence_with_trashed')->orderBy('title','asc')->get();
        //     $loginuserid = Auth::id();
        //     $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        //     $hospitalId  = $hospital->id;
        //     $name       = $hospital->name_en;
        //     $insurance_id = "";
        //     $subinsurance_id = [];
        //     $id = "";
        //     $requestParam = $request->all();
        //     $duplicate_insurance = $check->sub_insurance_id;
        //     // dd($requestParam);
        //     return view("hospital.createhospital_insurance", compact('page_heading','module_heading', 'id','name','insurance_id','subinsurance_id','hospital','subinsurancepolices','insurancepolices','mode','id','department_manager','department_name','email','phone','dial_code', 'requestParam', 'duplicate_insurance'));
        // }
        }

        if ($id) {
            DB::beginTransaction();
            try {
                if ($request->has('sub_insurance') && is_array($request->sub_insurance)) {
                    foreach ($request->sub_insurance as $sub_insurance_id) {
                        $insurancePolicy = new HospitalInsurancePolicy();
                        $insurancePolicy->hospital_id = $hospital->id;
                        $insurancePolicy->insurance_id = $request->insurance;
                        $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                        $insurancePolicy->save();
                    }
                }

                DB::commit();
                $status = "1";
                $message = "Insurance policy updated successfully";
            } catch (Exception $e) {
                DB::rollback();
                $message = "Failed to update insurance policy: " . $e->getMessage();
                return redirect()->route('hospital.ourinsurance')->with('error', $message);
            }
        } else {
            DB::beginTransaction();
            try {
                if ($request->has('sub_insurance') && is_array($request->sub_insurance)) {
                    foreach ($request->sub_insurance as $sub_insurance_id) {
                        $insurancePolicy = new HospitalInsurancePolicy();
                        $insurancePolicy->hospital_id = $hospital->id;
                        $insurancePolicy->insurance_id = $request->insurance;
                        $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                        $insurancePolicy->save();
                    }
                }

                DB::commit();
                $status = "1";
                $message = "Insurance policy added successfully";
            } catch (Exception $e) {
                DB::rollback();
                $message = "Failed to add insurance policy: " . $e->getMessage();
                return redirect()->route('hospital.ourinsurance')->with('error', $message);
            }
        }
        
        return redirect()->route('hospital.ourinsurance')->with('success', 'Insurance information saved successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_heading = "Edit Department";
        $module_heading = "Hospital Profile";
       
        $insurance = HospitalInsurancesModel::find($id);
        
        if ($insurance) {
             $page_heading = "Category ";
             $mode = "edit";
             $id = $insurance->id;
             $insurance_id = $insurance->insurance_id;
             $subinsurance_id = explode(',', $insurance->sub_insurance_id);
             $insurancepolices = InsurencePolicy::orderBy('id','desc')->paginate(10);
             $subinsurancepolices = SubInsurencePolicy::with('insurence_with_trashed')->orderBy('id','desc')->paginate(10);
             $loginuserid = Auth::id();
             $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
             $hospitalId  = $hospital->id;
             $name       = $hospital->name_en;
            
            return view("hospital.createhospital_insurance", compact('page_heading','module_heading','mode','id','hospital','insurancepolices','subinsurancepolices','hospitalId','name','insurance_id','subinsurance_id'));
         } else {
             abort(404);
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];

      
        $category = HospitalInsurancePolicy::where('insurance_id',$id);
        if ($category) {
            $category->delete();
            $message = "Insurance removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

       // echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
       return redirect()->route('hospital.ourinsurance')->with('success', 'Insurance removed  successfully.');

    }
}
?>