<?php

namespace App\Http\Controllers\callcenter;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use App\Models\DepartmentModel;
use App\Models\DepartmentHospital;
use App\Models\HospitalDepartmentModel;
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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\HospitalImage;
use Illuminate\Support\Facades\Hash;

use DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {  
        $page_heading = "Departments";
        $module_heading = "Departments";
        $user_hospital_id = Hospital::where('user_id', auth()->user()->id)->first();

        $hospital_id = $user_hospital_id->id;
        $hospital = Hospital::find($hospital_id);
        
        $department_list = DepartmentHospital::with(['department', 'hospital'])->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('callcenter.departmentlist',compact('page_heading', 'module_heading', 'hospital_id', 'hospital', 'department_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = '')
    {
        $user_hospital_id = Hospital::where('user_id', auth()->user()->id)->first();
        $departments = DepartmentModel::where('status', 1)->get();
        $row = null;
        $hospital_id = $user_hospital_id->id;
        $page_heading = $id ? 'Edit Department' : 'Create Department';
        $module_heading = "Departments";
        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = DepartmentHospital::find($id);
        }
        return view('callcenter.createDepartment', compact('page_heading', 'module_heading', 'id', 'hospital_id', 'row', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        
        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'department' => 'required|exists:departments,id',
            ];

        $validator = Validator::make($request->all(), $rules);
        
        $o_data['redirect'] = route('callcenter.departments');

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            $check = DepartmentHospital::where('hospital_id', $request->hospital_id)
                ->where('department_id', $request->department)
                ->where('id', '!=', $id)
                ->first();

            if ($check) {
                $status = "0";
                $message = "This department is already associated with the hospital.";
                $errors['department'] = 'This department is already associated with the hospital.';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $departmentHospital = DepartmentHospital::find($id);
                        $departmentHospital->hospital_id = $request->hospital_id;
                        $departmentHospital->department_id = $request->department;
                        $departmentHospital->manager_name = $request->manager_name;
                        $departmentHospital->phone = $request->phone;
                        $departmentHospital->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $departmentHospital->email = $request->email;
                        $departmentHospital->save();
                        $name = $departmentHospital->department->title;
                        activity_log('department_updated', "$name Updated");
                        DB::commit();
                        $status = "1";
                        $message = "Department updated successfully";

                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to update Department: " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $departmentHospital = new DepartmentHospital();
                        $departmentHospital->hospital_id = $request->hospital_id;
                        $departmentHospital->department_id = $request->department;
                        $departmentHospital->manager_name = $request->manager_name;
                        $departmentHospital->phone = $request->phone;
                        $departmentHospital->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $departmentHospital->email = $request->email;
                        $departmentHospital->save();

                        $name = $departmentHospital->department->title;
                        activity_log('department_created', "$name Created");
                        DB::commit();
                        $status = "1";
                        $message = "Department added successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to add Department: " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
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
        $module_heading = "Departments";
        $department = HospitalDepartmentModel::find($id);
        
        if ($department) {
             $page_heading = "Category ";
             $mode = "edit";
             $id = $department->id;
             $department_manager = $department->department_manager;
             $department_name = $department->department_name;
             $email = $department->email;
             $phone = $department->phone;
             $dial_code = $department->dial_code;
             $selected_department_id = $department->department_id;
             $departments = DepartmentModel::orderBy('id', 'desc')->get();
            
            return view("callcenter.createdepartment", compact('page_heading','module_heading', 'departments','selected_department_id','mode','id','department_manager','department_name','email','phone','dial_code'));
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

        $id = decrypt($id);
        $row = DepartmentHospital::find($id);
        if ($row) {
            $row->delete(); 
            $status = "1";
            $message = "Department removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function getHospitalDepartments($hospitalId) {
        $departments = [];
        $hospital = Hospital::where('id', $hospitalId)->first();
        if($hospital){
            $departments = $hospital->departments->toArray();
        }
        return response()->json($departments);
    }
}
?>