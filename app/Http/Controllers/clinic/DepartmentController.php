<?php

namespace App\Http\Controllers\clinic;
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
    public function index()
    {
      
        
        $page_heading = "Departments";
        
        $departments = DepartmentModel::where(['departments.deleted' => 0])->orderBy('id', 'desc')->get();
        
        
        return view('clinic.departmentlist', compact('page_heading', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $page_heading = "Department";
        $mode = "create";
        $id = "";
        $department_manager = "";
        $department_name = "";
        $email = "";
        $phone = "";
        $dial_code = "";
        
        return view("clinic.createdepartment", compact('page_heading','mode','id','department_manager','department_name','email','phone','dial_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'department_name' => 'required',
            'department_manager' => 'required',
            'phone' => 'required',
            'dial_code' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $id = $request->id;
            $department_name = $request->department_name;
            $email = $request->email;
            //$check_exist = DB::table('departments')->where('deleted', 0)->where('department_name', $request->department_name)->where('id', '!=', $request->id)->get()->toArray();
            $check_exist = DepartmentModel::where(function($query) use ($department_name, $email, $id) {
                $query->where('department_name', $department_name)
                      ->orWhere('email', $email);
            })
            ->where('deleted', 0)
            ->where('id', '!=', $id)
            ->get()
            ->toArray();
           
            if (empty($check_exist)) {
                $ins = [
                    'department_name' => $request->department_name,
                    'department_manager' => $request->department_manager,
                    'dial_code' => $request->dial_code,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'updated_uid' => session("user_id"),
                    'active' => 1,//$request->active,
                    'deleted' =>0,
                ];

               
                if ($request->id != "") {
                    $category = DepartmentModel::find($request->id);
                    $category->update($ins);
                    $department=$category;
                    $name = $department->department_name;
                    activity_log('department_updated', "$name Updated");
                    $status = "1";
                    $message = "Department updated succesfully";
                } else {
                    $ins['created_uid'] = session("user_id");
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    
                    $department=DepartmentModel::create($ins);
                    $name = $department->department_name;
                    activity_log('department_created', "$name Created");
                    $status = "1";
                    $message = "Department added successfully";
                }
            } else {
                $status = "0";
                $message = "Validation error";
                
                foreach ($check_exist as $exist) {
                    if ($exist['department_name'] === $department_name) {
                        $errors['department_name'] = $department_name . " already added";
                    }
                    if ($exist['email'] === $email) {
                        $errors['email'] = $email . " already added";
                    }
                }
            }

        }

        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
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
       
        $department = DepartmentModel::find($id);
        
        if ($department) {
             $page_heading = "Category ";
             $mode = "edit";
             $id = $department->id;
             $department_manager = $department->department_manager;
             $department_name = $department->department_name;
             $email = $department->email;
             $phone = $department->phone;
             $dial_code = $department->dial_code;
            
            return view("clinic.createdepartment", compact('page_heading','mode','id','department_manager','department_name','email','phone','dial_code'));
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

      
        $category = DepartmentModel::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->status = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Department removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
}
?>