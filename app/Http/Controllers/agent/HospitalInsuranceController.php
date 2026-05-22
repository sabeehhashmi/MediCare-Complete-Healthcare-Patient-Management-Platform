<?php

namespace App\Http\Controllers\agent;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use App\Models\DepartmentModel;
use App\Models\AgentUserDetail;
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
        $insurances = HospitalInsurancesModel::where('hospital_id',$hospitalId)->get();
        return view("agent.our_insurance",  compact('page_heading', 'module_heading', 'name','hospital','insurances'));
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
        $insurancepolices = InsurencePolicy::orderBy('id','desc')->get();
        $subinsurancepolices = SubInsurencePolicy::with('insurence_with_trashed')->orderBy('id','desc')->paginate(10);
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $name       = $hospital->name_en;
        $insurance_id = "";
        $subinsurance_id = [];
        $id = "";

        
        return view("agent.createhospital_insurance", compact('page_heading','module_heading', 'id','name','insurance_id','subinsurance_id','hospital','subinsurancepolices','insurancepolices','mode','id','department_manager','department_name','email','phone','dial_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;

        foreach ($input['insurance'] as $index => $insuranceId) {
            if (!empty($input['id'])) {
                $hospitalInsurance = HospitalInsurancesModel::find($input['id']);
                if ($hospitalInsurance) {
                    // Update existing record
                    $hospitalInsurance->hospital_id = $hospitalId;
                    $hospitalInsurance->insurance_id = $insuranceId;
                    $hospitalInsurance->sub_insurance_id = implode(',', $input['sub_insurance']) ?? null;
                    $hospitalInsurance->save();
                } else {
                    // Handle error or continue with other logic
                    continue; // Skip this iteration and proceed to the next
                }
            } else {
                // Handle case where id is empty (likely for new records)
                $hospitalInsurance = new HospitalInsurancesModel();
                $hospitalInsurance->hospital_id = $hospitalId;
                $hospitalInsurance->insurance_id = $insuranceId;
                $hospitalInsurance->sub_insurance_id = implode(',', $input['sub_insurance']) ?? null;
                $hospitalInsurance->save();
            }
        }
        
        return redirect()->route('agent.ourinsurance')->with('success', 'Insurance information saved successfully.');
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
            
            return view("agent.createhospital_insurance", compact('page_heading','module_heading','mode','id','hospital','insurancepolices','subinsurancepolices','hospitalId','name','insurance_id','subinsurance_id'));
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

      
        $category = HospitalInsurancesModel::find($id);
        if ($category) {
            $category->delete();
            $message = "Insurance removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

       // echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
       return redirect()->route('agent.ourinsurance')->with('success', 'Insurance removed  successfully.');

    }
}
?>