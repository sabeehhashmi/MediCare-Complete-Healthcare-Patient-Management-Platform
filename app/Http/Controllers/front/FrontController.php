<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\AppointmentDoc;
use App\Models\Appointments;
use App\Models\Article;
use App\Models\ClinicalSummary;
use App\Models\ContactUsEntry;
use App\Models\ContactUsSetting;
use App\Models\DoctorPatientAppointment;
use App\Models\InsurencePolicy;
use App\Models\Members;
use App\Models\Prescription;
use App\Models\Referral;
use App\Models\ReferralDetail;
use App\Models\SubInsurencePolicy;
use App\Models\MedicinCategory;
use App\Models\PointHistory;
use App\Models\Specialty;
use App\Models\CallRecording;
use App\Models\SpecialIntrests;
use App\Models\Languages;
use App\Models\CountryOfOrigin;
use App\Models\HospitalDoctorFeedback;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\Hospital;
use App\Models\DepartmentModel;
use App\Models\WebsiteService;
use App\Models\FaqModel;
use App\Models\Video;
use App\Models\WellnessTip;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\AppointmentsExport;
use App\Exports\PointHistoryExport;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $aboutPage = Article::where('status', 1)
            ->where('type', 2)
            ->where('title_en', 'About Us')
            ->first();

        $servicePage = Article::where('status', 1)
            ->where('type', 2)
            ->where('title_en', 'Services')
            ->first();

        $websiteServices = WebsiteService::where('status', 1)->get();
        $contactUs = ContactUsSetting::first();
        

        $categories = MedicinCategory::where('status', 1)->orderBy('title', 'asc')->get();

        $specialties = Specialty::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
    $insurencePolicies = InsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
    $subInsurencePolicies = [];
    if ($request->insurance_id ?? null) {
        $subInsurencePolicies = SubInsurencePolicy::where('status', 1)->where('insurence_id', $request->insurance_id)->orderBy('title')->get()->pluck('title', 'id');
    }

    $medicalConditions = SpecialIntrests::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
    $languages = Languages::orderBy('title')->get()->pluck('title', 'id');
    $countries = CountryOfOrigin::orderBy('name')->get()->pluck('name', 'id');
    $genders = [1 => 'Male', 2 => 'Female', 3 => 'Others'];
    $emirates = Emirate::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
    $areas = Area::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
    $hospitals = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
        ->where('users.active', 1)
        ->orderBy('hospitals.name_en', 'asc')
        ->select('hospitals.*')
        ->get();

        

    $departments = DepartmentModel::where(['status'=>1])->orderBy('title','asc')->get();
    $specialities = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
    $special_interestes = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
    $countries = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc')->get();
        return view('front.index',compact('categories', 'aboutPage', 'contactUs', 'servicePage', 'websiteServices',
    'specialties', 
        'insurencePolicies', 
        'subInsurencePolicies', 
        'medicalConditions', 
        'languages', 
        'countries', 
        'genders', 
        'emirates', 
        'areas', 
        'hospitals', 
        ));
    }
    
public function startCall(Request $request)
{
    $doctor_id = auth()->id();

    $channel = $request->channel;
    $guest = $request->guest;

    // MUST be numeric
    $recordingUid = (string) rand(100000, 999999);

    $appointment = DoctorPatientAppointment::where(
        'booking_id',
        '#' . $channel
    )->first();

    // =========================================
    // UPDATE APPOINTMENT
    // =========================================
    if ($guest != 'doctor') {

        DoctorPatientAppointment::where(
            'booking_id',
            '#' . $channel
        )->update([
            'is_call_live' => 1,
            'call_started_at' => now()
        ]);
    }

    try {

        $appId = env('AGORA_APP_ID');

        $customerId = env('AGORA_CUSTOMER_ID');

        $customerSecret = env('AGORA_CUSTOMER_SECRET');

        // =========================================
        // STEP 1: ACQUIRE
        // =========================================
        $acquire = Http::withBasicAuth(
            $customerId,
            $customerSecret
        )->post(
            "https://api.agora.io/v1/apps/{$appId}/cloud_recording/acquire",
            [
                "cname" => $channel,

                "uid" => (string) $recordingUid,

                "clientRequest" => [
                    "resourceExpiredHour" => 24,
                    "scene" => 0
                ]
            ]
        );

        $acquireData = $acquire->json();

        \Log::info("AGORA ACQUIRE", $acquireData);

        $resourceId = $acquireData['resourceId'] ?? null;

        // =========================================
        // STEP 2: START RECORDING
        // =========================================
        if ($resourceId) {
        $agoraToken = generateAgoraToken($channel, $recordingUid);
            $start = Http::withBasicAuth(
                $customerId,
                $customerSecret
            )->post(
                "https://api.agora.io/v1/apps/{$appId}/cloud_recording/resourceid/{$resourceId}/mode/individual/start",
                [

                    "cname" => $channel,

                    "uid" => (string) $recordingUid,
                    
                    "token" => $agoraToken,
                    "clientRequest" => [

                        "recordingConfig" => [

                            "maxIdleTime" => 300,

                            "streamTypes" => 2,

                            "channelType" => 1,

                            "subscribeUidGroup" => 0
                        ],

                        // MUST include hls + mp4
                        "recordingFileConfig" => [
                            "avFileType" => ["hls", "mp4"]
                        ],

                        // STORAGE
                        "storageConfig" => [

                            "vendor" => 1,

                            "region" => 21,

                            "bucket" => env('AWS_BUCKET'),

                            "accessKey" => env('AWS_ACCESS_KEY_ID'),

                            "secretKey" => env('AWS_SECRET_ACCESS_KEY'),

                            "fileNamePrefix" => [
                                "recordings",
                                $channel
                            ]
                        ]
                    ]
                ]
            );

            $startData = $start->json();

            \Log::info("AGORA START", $startData);

            // SUCCESS
            if (isset($startData['sid'])) {

                CallRecording::create([

                    'appointment_id' => $appointment->id,

                    'channel' => $channel,

                    'uid' => $recordingUid,

                    'resource_id' => $resourceId,

                    'sid' => $startData['sid'],

                    'recording_response' =>
                        json_encode($startData),

                    'status' => 'recording'
                ]);
            }
        }

    } catch (\Exception $e) {

        \Log::error(
            "Agora Start Error: " .
            $e->getMessage()
        );
    }

    // =========================================
    // YOUR EXISTING COMMANDS
    // =========================================
    exec(
        "php " .
        base_path() .
        "/artisan video:call $doctor_id $channel > /dev/null 2>&1 &"
    );

    exec(
        "php " .
        base_path() .
        "/artisan video:user_call $doctor_id $channel > /dev/null 2>&1 &"
    );

    return response()->json([
        'status' => true
    ]);
}

public function endCallStatus(Request $request)
{
    $channel = $request->channel;

    $appointment = DoctorPatientAppointment::where(
        'booking_id',
        '#' . $channel
    )->first();

    // =========================================
    // UPDATE STATUS
    // =========================================
    DoctorPatientAppointment::where(
        'booking_id',
        '#' . $channel
    )->update([
        'is_call_live' => 0
    ]);

    try {

        $recording = CallRecording::where(
            'appointment_id',
            $appointment->id
        )
        ->where('status', 'recording')
        ->latest()
        ->first();

        if ($recording) {

            $appId = env('AGORA_APP_ID');

            $customerId = env('AGORA_CUSTOMER_ID');

            $customerSecret = env('AGORA_CUSTOMER_SECRET');

            // =========================================
            // STOP RECORDING
            // =========================================
            $stop = Http::withBasicAuth(
                $customerId,
                $customerSecret
            )->post(
                "https://api.agora.io/v1/apps/{$appId}/cloud_recording/resourceid/{$recording->resource_id}/sid/{$recording->sid}/mode/individual/stop",
                [

                    "cname" => $channel,

                    "uid" => (string) $recording->uid,

                    "clientRequest" => new \stdClass()
                ]
            );

            $stopData = $stop->json();
            
            \Log::info("AGORA STOP", $stopData);

            // =========================================
            // WAIT FOR FILE FINALIZATION
            // =========================================
            sleep(15);

            // =========================================
            // QUERY RECORDING
            // =========================================
            $query = Http::withBasicAuth(
                $customerId,
                $customerSecret
            )->get(
                "https://api.agora.io/v1/apps/{$appId}/cloud_recording/resourceid/{$recording->resource_id}/sid/{$recording->sid}/mode/individual/query"
            );

            $queryData = $query->json();

            \Log::info("AGORA QUERY", $queryData);

            // =========================================
            // GET FILES
            // =========================================
            $fileList =
                $queryData['serverResponse']['fileList']
                ?? [];

            $mp4File = null;

            foreach ($fileList as $file) {

                if (
                    isset($file['fileName']) &&
                    str_contains(
                        $file['fileName'],
                        '.mp4'
                    )
                ) {

                    $mp4File = $file['fileName'];

                    break;
                }
            }

            // =========================================
            // SAVE DB
            // =========================================
            $recording->update([

                'status' => 'completed',

                'recording_file' => $mp4File,

                'recording_response' =>
                    json_encode($queryData)
            ]);
        }

    } catch (\Exception $e) {

        \Log::error(
            "Agora Stop Error: " .
            $e->getMessage()
        );
    }

    return response()->json([
        'status' => true
    ]);
}


    /**
     * Show report listing
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showReports(Request $request): \Illuminate\View\View
    {
        $user_id = auth()->id();
        $type = $request->input('type', 'lab');

        $relation = ($type === 'xray') ? 'xrayReports' : 'labReports';

        $allReports = DoctorPatientAppointment::whereHas($relation)
            ->with([$relation, 'user', 'hospital'])
            ->where('user_id', $user_id)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $allReports->getCollection()->transform(function ($appointment) use ($relation) {
            $appointment->setRelation('reports', $appointment->$relation);
            return $appointment;
        });

        return view('front.reports', compact('allReports'));
    }


    /**
     * Show appointment listing
     * @return \Illuminate\View\View
     */
    public function showBookings(Request $request): \Illuminate\View\View
{
    $user_id = auth()->id();

    $savedPatients = Members::where('user_id', $user_id)->pluck('id')->toArray();

    $query = DoctorPatientAppointment::query()
        ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
        ->join('users as doctor_user', 'doctor_user.id', '=', 'doctors.user_id')
        ->join('hospitals', 'hospitals.id', '=', 'doctor_patient_appointments.hospital_id')
        ->leftJoin('members', 'members.id', '=', 'doctor_patient_appointments.member_id')
        ->join('users as patient_user', 'patient_user.id', '=', 'doctor_patient_appointments.user_id')
        ->select(
            'doctor_patient_appointments.*', 
            'doctor_user.name as doctor_name',
            'doctor_user.email as doctor_email',
            'patient_user.name as patient_name',
            'hospitals.name_en as hospital_name',
            'hospitals.address as hospital_address',
            'members.full_name as patient_member_name'
        )
        ->where(function($q) use ($user_id, $savedPatients) {
            $q->where('doctor_patient_appointments.user_id', $user_id)
              ->orWhereIn('doctor_patient_appointments.member_id', $savedPatients);
        });

    // ✅ FROM DATE
    if (!empty($request->booking_from)) {
        try {
            $from = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_from)->format('Y-m-d');
            $query->whereDate('doctor_patient_appointments.booking_date', '>=', $from);
        } catch (\Exception $e) {}
    }

    // ✅ TO DATE
    if (!empty($request->booking_to)) {
        try {
            $to = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_to)->format('Y-m-d');
            $query->whereDate('doctor_patient_appointments.booking_date', '<=', $to);
        } catch (\Exception $e) {}
    }

    // ✅ BOOKING ID (optional)
   if (!empty($request->booking_id)) {

            $bookingId = strtolower(trim($request->booking_id));

            $query->whereRaw(
                'LOWER(doctor_patient_appointments.booking_id) LIKE ?',
                ['%' . $bookingId . '%']
            );
        }


    $appointments = $query
        ->orderBy('doctor_patient_appointments.booking_date', 'desc')
        ->paginate(5)
        ->withQueryString();

    return view('front.bookings', compact('appointments'));
}

    /**
     * Show appointment details
     * @return \Illuminate\View\View
     */
    public function showBookingDetails($id): \Illuminate\View\View
    {

        $user_id = auth()->id();

        $savedPatients = Members::where('user_id', $user_id)->pluck('id')->toArray();

        $appointment = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctor_user', 'doctor_user.id', '=', 'doctors.user_id')
            ->join('users as patient_user', 'patient_user.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', 'doctor_patient_appointments.member_id')
            ->leftJoin('hospitals', 'hospitals.id', '=', 'doctor_patient_appointments.hospital_id')
            ->leftJoin('clinical_assessment_and_documentation', 'clinical_assessment_and_documentation.appointment_id', '=', 'doctor_patient_appointments.id')
            ->select(
                'doctor_patient_appointments.*', 
                'doctor_user.name as doctor_name',
                'patient_user.name as patient_name',
                'hospitals.name_en as hospital_name',
                'members.full_name as patient_member_name',
                'clinical_assessment_and_documentation.symptoms as caad_symptoms',
                'clinical_assessment_and_documentation.present_illness as caad_present_illness',
                'clinical_assessment_and_documentation.past_history as caad_past_history'
            )

            ->where('doctor_patient_appointments.id', $id)
            
            ->where(function($query) use ($user_id, $savedPatients) {
                $query->where('doctor_patient_appointments.user_id', $user_id)
                    ->orWhereIn('doctor_patient_appointments.member_id', $savedPatients);
            })
            ->firstOrFail();

            

        $feeback = HospitalDoctorFeedback::where('appointment_id', $appointment->id)->first();
        $summaries = ClinicalSummary::where('appointment_id', $appointment->id)->get();
        $referral = ReferralDetail::with(['refferal_doctor', 'department'])->where('appointment_id', $appointment->id)->first();
        $prescription = Prescription::with(['details', 'details.medicine', 'details.direction', 'details.frequency', 'details.dosage', 'details.duration'])->where('appointment_id', $appointment->id)->first();
        $labReports = AppointmentDoc::where('appointment_id', $appointment->id)->where('type', 'lab_test')->get();
        $xrayReports = AppointmentDoc::where('appointment_id', $appointment->id)->where('type', 'xray')->get();
        $time_slot = TIME_SLOTS;
        return view('front.appointment-details', compact('appointment', 'summaries', 'referral', 'prescription', 'labReports', 'xrayReports','time_slot','feeback'));
    }


    /**
     * Show saved patients listing
     * @return \Illuminate\View\View
     */
    public function showSavedpatients(Request $request)
    {
        $insurence_list = InsurencePolicy::orderBy('title','asc')->get();
        $user_id = auth()->id();
        $patients = Members::withCount('appointments')->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(5);

        $sub_insurence_list = SubInsurencePolicy::get(['id', 'title', 'insurence_id']);


        return view('front.patients', compact('insurence_list', 'sub_insurence_list', 'patients'));
    }
    
    public function showSettings()
    {
        $user = auth()->user();
        return view('front.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $user->enable_reminder_notification = $request->has('enable_reminder_notification') ? 1 : 0;
        $user->enable_public_notification = $request->has('enable_public_notification') ? 1 : 0;
        $user->enable_lab_result_notification = $request->has('enable_lab_result_notification') ? 1 : 0;
        $user->enable_payment_notification = $request->has('enable_payment_notification') ? 1 : 0;
        $user->enable_prescription_notification = $request->has('enable_prescription_notification') ? 1 : 0;
        $user->save();

        return redirect()->back()->with('status', '1')->with('message', 'Settings updated successfully');
    }

    public function showNotifications(Request $request)
    {
        $user = auth()->user();
        return view('front.notifications', compact('user'));
    }

    /**
     * get sub listing
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchSubInsurence(Request $request): \Illuminate\Http\JsonResponse
    {
        $insurence_id = $request->insurence_id;
        $sub_insurence_list = SubInsurencePolicy::where('insurence_id', $insurence_id)->orderBy('title','asc')->get();

        return response()->json(['status' => true, 'data' => $sub_insurence_list]);

    }


    /**
     * get sub listing
     * @return \Illuminate\Http\JsonResponse
     */
    public function putPatients(Request $request): \Illuminate\Http\JsonResponse
    {

        $message = "Member Added Successfully";

        $rules = [
            'id' => 'nullable|integer',
            'full_name' => 'required|string|max:255',
            // 'full_name_ar' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:1,2,3',
            'insurence_id' => 'nullable|integer|exists:insurence_policies,id'
        ];

        $subInsuranceExists = DB::table('sub_insurence_policies')
        ->where('insurence_id', $request->insurence_id)
        ->exists();

        if($subInsuranceExists){
            $rules['sub_insurence_id'] = 'required|integer|exists:sub_insurence_policies,id';
        }

        try {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => '0', 'message' => $validator->errors()->first() ]);
            }

            $id = $request->id;

            // Find or create the patient
            if ($id) {
                $message = "Member Updated Successfully";
                $member = Members::find($id);

                if (!$member) {
                    return response()->json(['status' => '0', 'message' => 'Member not found']);
                }

            } else {
                $member = new Members();
            }

            DB::beginTransaction();

            $paitentid = auth()->id();

            // Update patient data
            $member->full_name = $request->full_name;
            $member->age = $request->age;
            $member->gender = $request->gender;
            $member->user_id = $paitentid;
            $member->insurence_id = $request->insurence_id;
            $member->sub_insurence_id = $request->sub_insurence_id ?? null;
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $member->user_image = $file_name;
            }

            $member->save();

            DB::commit();
            return response()->json(['status' => '1', 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => '0', 'message' => 'Failed to save member: ' . $e->getMessage()]);
        }


    }

    public function deleteMember(Request $request, $id)
    {
        $status = "0";
        $message = "";

        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $status,
                'message' => 'Invalid ID format'
            ]);
        }

        $row = Members::find($id);
        if ($row) {
            $row->delete();
            $message = "Member deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Member data";
        }

        return redirect()->back()->with($status, $message);
    }


    public function cancelBooking(Request $request, $id)
    {
        $status = "0";
        $message = "";

        $row = DoctorPatientAppointment::find($id);
        if ($row) {
            $row->booking_status = BOOKING_STATUS_CANCELLED;
            $row->save();
             exec("php " . base_path() . "/artisan app:send-notitications-patient " . $row->id . " > /dev/null 2>&1 & ");
            $message = "Booking cancelled successfully";
            $status = "1";
        } else {
            $message = "Invalid Member data";
        }

        return redirect()->back()->with($status, $message);
    }


    public function contactUsForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'subject' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|max:3000',
        ]);

        $status = 1;
        $message = "Form submitted successfully!";
        $errors = '';
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors]);
        }

        // Create the contact us entry
        $contactUsEntry = new ContactUsEntry();
        $contactUsEntry->name = $request->name;
        $contactUsEntry->email = $request->email;
        $contactUsEntry->subject = $request->subject;
        
        if($request->filled('phone')){
            $contactUsEntry->phone = $request->phone;
        }
        
        if ($request->hasfile('image')) {
        $file = $request->file('image');
        $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
        $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
        $contactUsEntry->file = $file_name;
        } 

        if($request->filled('message')){
            $contactUsEntry->message = $request->message;
        }

        $contactUsEntry->save();

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors]);

    }

    public function termsConditions(){

        $terms = Article::where('status', 1)
            ->where('type', 2)
            ->where('title_en', 'Terms and Conditions')
            ->first();

        return view('front.terms-conditions', compact('terms'));
    }

        public function privacyPolicy(){

        $policy = Article::where('status', 1)
            ->where('type', 2)
            ->where('title_en', 'Privacy & Internet Cookies Policy')
            ->first();

        return view('front.privacy-policy', compact('policy'));
    }

     /**
     * Show report listing
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showRatings(Request $request)
{
    $user_id = auth()->id();

    $feedbacks = HospitalDoctorFeedback::with(['appointment.doctor.user', 'appointment.hospital'])
        ->where('user_id', $user_id)
        ->latest()
        ->paginate(10);

    return view('front.feeback', compact('feedbacks'));
}

public function updateFeedback(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feedback_id' => 'required|exists:hospital_doctor_feedback,id',
        'rating' => 'required|integer|min:1|max:5',
        'feeback_message' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'message' => $validator->errors()->first()
        ]);
    }

    $feedback = HospitalDoctorFeedback::where('id', $request->feedback_id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$feedback) {
        return response()->json([
            'status' => "0",
            'message' => "Feedback not found"
        ]);
    }

    $feedback->update([
        'rating' => $request->rating,
        'feeback_message' => $request->feeback_message,
    ]);

    return response()->json([
        'status' => "1",
        'message' => "Feedback updated successfully"
    ]);
}

public function deleteFeedback(Request $request)
{
    $feedback = HospitalDoctorFeedback::where('id', $request->id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$feedback) {
        return response()->json([
            'status' => "0",
            'message' => "Feedback not found"
        ]);
    }

    $feedback->delete();

    return response()->json([
        'status' => "1",
        'message' => "Feedback deleted successfully"
    ]);
}

public function deleteDocs(Request $request,$id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        // $hospital = Hospital::where('user_id',$loginuserid)->first();
        // $hospital_id  = $hospital->id;
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
           // 'booking_id' => 'required|exists:doctor_patient_appointments,id',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $id ?? null;
            
            // Generate a random booking ID for new appointments
            // $FourDigitRandomNumber = rand(1231, 7879);

            if ($bookingId) {
                
                // Update existing appointment
                $appointment = AppointmentDoc::find($id);
                $bookingId =$appointment->appointment_id;
                
                if (!$appointment) {
                    return response()->json(['status' => '0', 'message' => 'Docment not found', 'errors' => ['id' => 'Appointment not found']]);
                }
               
                $appointment->delete();
                $message = "Document Delete Successfully";
            }
                
            
            
            
            
          //  $this->addAppointmentHistory($appointment->id, $appointment->booking_status, auth()->user()->id);
         //   exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = route('front.appointment-details',['id'=>$bookingId]);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

     public function faqsList()
    {

        $faqs = FaqModel::where('active',1)->get();
        return view('front.faqs',compact('faqs'));
    } 
    
    public function faqsDetail()
    {
        
        return view('front.faqs_detail');
    }

    public function videosList()
    {
        
       $videos=Video::where('status',1)->get();
        return view('front.videos',compact('videos'));
    } 
    
    public function videosDetail(Request $request)
    {
         $video=Video::find($request->id);
        
        return view('front.videos_detail',compact('video'));
    }
    public function wellnessList()
    {

         $tips=WellnessTip::where('status',1)->get();
       
        return view('front.wellness',compact('tips'));
    } 
    
    public function wellnessDetail(Request $request )
    {
        $tip=WellnessTip::find($request->id);
        
        return view('front.wellness_detail',compact('tip'));
    }

    public function export(Request $request)
    {


        $request->user_id=auth()->id(); 
        $filters = $request->all();
        // dd($filters);
        $exporter = new AppointmentsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }


public function exportPdf(Request $request)
{

    $request->user_id=auth()->id(); 
        $filters = $request->all();
    $filters = $request->all();

    $exporter = new AppointmentsExport();
    $data = $exporter->exportPdf($filters);

    $html = view('exports.appointments-pdf', compact('data'))->render();

    $mpdf = new Mpdf([
        'format' => 'A4-L', // LANDSCAPE for wide table
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    $fileName = 'appointments.pdf';

    return response(
        $mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN),
        200
    )->header('Content-Type', 'application/pdf')
     ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
}

public function chellangeDetail(Request $request)
{
    
    $status = "0";
    $message = "";
    $o_data = [];
    $errors = [];

    $validator = Validator::make($request->all(), [
        'chellange_id' => 'required',
    ], [
        'chellange_id.required' => 'Event ID required'
    ]);

    if ($validator->fails()) {
        $message = "Validation error occured";
        $errors = $validator->messages();
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => (object) $errors,
        ], 200);
    }

    $access_token = $request->access_token;
    $user = User::where('user_access_token', $access_token)->first();

    if (empty($user)) {
        return response()->json([
            'status' => (string) 0,
            'message' => 'User Session Expired',
            'errors' => (object) $errors,
        ], 401);
    }

    $userId = $user->id;

    // Load challenge with all necessary relationships
    $chellange = Challenge::withCount([
        'favoritedByUsers as is_favorited' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }
    ])
    ->with(['companies', 'companies.user.company', 'targetType', 'award','challenge_tag'])
    ->where('status', 1)
    ->where('id', $request->chellange_id)
    ->first();
  
    if (empty($chellange)) {
        return response()->json(['status' => '0', 'message' => 'Challenge not found'], 200);
    }

    // Preload related users only once
    if ($chellange->kind == 2) {
        $relatedUsers = UserChallenge::where('challenge_id', $chellange->id)
            ->get(['user_id', 'sender_id'])
            ->flatMap(function ($row) {
                return [$row->user_id, $row->sender_id];
            })
            ->unique()
            ->values();
    } elseif ($chellange->kind == 3) {
        $relatedUsers = User::where('company_id', $user->company_id)->pluck('id');
    } else {
        $relatedUsers = collect([$userId]);
    }
    

    // Preload all progress records in one query
    $allProgress = ProgressRecord::where('challenge_id', $chellange->id)
        ->whereIn('user_id', $relatedUsers)
        ->get()
        ->groupBy('user_id');
        

    // Preload all UserChallenge records in one query
    $allUserChallenges = UserChallenge::with('user')
        ->where('challenge_id', $chellange->id)
        ->get()
        ->keyBy('user_id');
        
       

    // User's own progress
    $step_record = collect($allProgress->get($userId))->sortByDesc('id')->first();
   
    $step_record_value = $step_record ? $step_record->total_value : 0;

    // Calculate completed value for the main user
    $chellange->completed_value = $this->calculateTotalValue($chellange, $user, $step_record_value);
    if ($chellange->target_type == 2 && $chellange->completed_value > 0) {
        $completed_value = $step_record_value ? $step_record_value / 1000 : 0;
       // dd($completed_value);
        if ($chellange->kind != 1) {
            $get_val = $this->calculateTotalValue($chellange, $user, $completed_value);
            $chellange->completed_value = $get_val ? $get_val / 1000 : '0';
        } else {
            $chellange->completed_value = $completed_value;
        }
    }
      
    // Determine last entry date
   if ($chellange->kind == 3) {

    $last_record = ProgressRecord::where('challenge_id', $chellange->id)
        ->whereIn('user_id', $relatedUsers)
        ->latest('created_at')
        ->first();

    $chellange->last_entry_date = $last_record
        ? $last_record->created_at
        : '';
} elseif ($chellange->kind == 2) {

    $last_record = ProgressRecord::select('created_at')
        ->where('challenge_id', $chellange->id)
        ->whereIn('user_id', $relatedUsers)
        ->latest('id')
        ->first();

    $chellange->last_entry_date = $last_record
        ? $last_record->created_at
        : '';
} else {
        $chellange->last_entry_date = $step_record ? $step_record->created_at : '';
    }
    

    // Get user's challenge status
    $userChallengeStatus = UserChallenge::where('user_id', $userId)
        ->where('challenge_id', $chellange->id)
        ->first();
        
       
        
    // Determine join status
    $getActiveChallenges = $this->getActiveChallenges($request);
    $join_status = 'Not Joined';
    if ($userChallengeStatus) {
        if ($userChallengeStatus->challenge_status == 'completed') {
            $join_status = 'Completed';
        } elseif ($userChallengeStatus->challenge_status == 'waiting_approval') {
            $join_status = 'Waiting For Approval';
        } else {
            $join_status = in_array($userChallengeStatus->challenge_id, $getActiveChallenges) ? 'Active' : 'Paused';
        }
    }
    $chellange->join_status = $join_status;
    $chellange->join_type = $userChallengeStatus ? $userChallengeStatus->challenge_status : "";
    $chellange->waiting_approval = ($userChallengeStatus && $userChallengeStatus->challenge_status == 'waiting_approval') ? "1" : "0";
    $chellange->is_joind = $userChallengeStatus ? "1" : "0";

    // Prepare join list
    // Get invited user IDs only once
$invitedUserIds = collect();



// Only apply invite filtering if challenge_kind == 2
$invitedUserIds = collect();

if ($chellange->kind == 2) {
    
    $invitedUserIds=User::where('company_id',$user->company_id)->pluck('id');
    
}

// Build query first (do NOT call get yet)
$query = UserChallenge::with('user')
    ->where('challenge_id', $chellange->id);

// If private challenge → filter in DB level
if ($chellange->kind == 2) {
    $query->whereIn('user_id', $invitedUserIds);
}

// Now execute query
$allCUserChallenges = $query->get()->keyBy('user_id');

$userinvitedChallengeS = $allCUserChallenges
    ->filter(function ($uc) use ($relatedUsers, $chellange, $invitedUserIds) {

        // Must exist in related users
        if (!$relatedUsers->contains($uc->user_id)) {
            return false;
        }

        // If private challenge → must be in invite list (including current user)
        if ($chellange->challenge_kind == 2) {
            return $invitedUserIds->contains($uc->user_id);
        }

        return true;
    })
    ->map(function ($uc) use ($chellange, $allProgress) {

        $step_record = collect($allProgress->get($uc->user_id))
            ->sortByDesc('id')
            ->first();

        $completed_value = $step_record ? $step_record->total_value : 0;

        if ($chellange->target_type == 2 && $completed_value != 0) {
            
            $completed_value = abs($completed_value) / 1000;
            
        }

        $uc->completed_value = $completed_value;

        return $uc;
    })
    ->values();
    
   if ($chellange->kind!=2){
    $chellange->join_list = $userinvitedChallengeS;
    }
    else{
        if($chellange->is_joind==1){
        $chellange->join_list = $userinvitedChallengeS;
        }else{
            $chellange->join_list = [];
        }
    }

    // Prepare invite list
    $invite_list = $allUserChallenges->filter(function ($uc) {
        return $uc->challenge_status != 'joined';
    })->values();
    
    

    // Prepare invite info
    $invite = ChallengeInvite::where('sender_id', $user->id)
        ->where('challenge_id', $chellange->id)
        ->with('user')
        ->get();
         $chellange->invite_list = $invite;
         $invite=$invite->first();
    $chellange->invite_email = $invite ? $invite->user->email : '';
    $chellange->invite_name = $invite ? $invite->user->name : '';
    $chellange->invite_image = $invite ? $invite->user->profile_full_image : '';

    $o_data['chellange'] = $chellange;

    // Awards / badges
    if ($chellange->kind != 3) {
        $completed_challenges = UserChallenge::where('challenge_status', 'completed')
            ->with('user')
            ->where('challenge_id', $request->chellange_id)
            ->whereNull('sender_id')
            ->get();
    } else {
        $completed_challenges = UserChallenge::where('challenge_status', 'completed')
            ->with('user')
            ->where('challenge_id', $request->chellange_id)
            ->whereNull('sender_id')
            ->limit(1)
            ->get();
    }
    $o_data['awards_badges'] = $completed_challenges;

    // Leaderboard
    $history = $this->challenege_stats_details($request->chellange_id);
    $o_data['leaderboard'] = $history['leaderboard'] ?? [];
        
    // Convert all to string
    $o_data = convert_all_elements_to_string($o_data);
    
    if($history['leaderboard']->isEmpty()){
       
         $o_data['leaderboard'] = [];
    }

    // Clean empty collections
    if (empty($completed_challenges->first())) $o_data['awards_badges'] = [];
    if (empty($userinvitedChallengeS->first())) $chellange->join_list = [];
    if (empty($invite_list->first())) $chellange->invite_list = [];

    $status = "1";

    return response()->json([
        'status' => $status,
        'message' => $message,
        'errors' => (object) $errors,
        'oData' => $o_data
    ], 200);
}

public function showPointHistory(Request $request)
{
    $user_id = auth()->id();

    $query = PointHistory::with([
        'appointment.doctor.user',
        'appointment.hospital',
        'appointment.member',
    ])->where('user_id', $user_id);

    // --------------------
    // DATE FILTERS
    // --------------------
    if (!empty($request->booking_from)) {
          $from = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_from)->format('Y-m-d');
            $query->whereDate('point_histories.created_at', '>=', $from);
       
    }

    if (!empty($request->booking_to)) {
        $to = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_to)->format('Y-m-d');
        $query->whereDate('point_histories.created_at', '<=', $to);
    }

    // --------------------
    // SEARCH BY BOOKING ID
    // --------------------
    if (!empty($request->booking_id)) {
        $bookingId = strtolower(trim($request->booking_id));

        $query->whereHas('appointment', function ($q) use ($bookingId) {
            $q->whereRaw('LOWER(booking_id) LIKE ?', ["%{$bookingId}%"]);
        });
    }

    $points = $query
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->withQueryString();

    return view('front.point-history', compact('points'));
}

public function exportPoints(Request $request)
{
    $request->user_id = auth()->id();

    $filters = $request->all();

    $exporter = new PointHistoryExport();

    $fileName = $exporter->export($filters);

    return response()->download($fileName)->deleteFileAfterSend(true);
}

public function exportPointsPdf(Request $request)
{
    $request->user_id = auth()->id();

    $filters = $request->all();

    $exporter = new PointHistoryExport();

    $data = $exporter->exportPdf($filters);

    $html = view('exports.points-pdf', compact('data'))->render();

    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4-L',
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    $fileName = 'points-history.pdf';

    return response(
        $mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN),
        200
    )->header('Content-Type', 'application/pdf')
     ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
}

public function change_status(Request $request)
{
    $user = DoctorPatientAppointment::find($request->id);

    if (!$user) {
        return response()->json([
            'status' => 0,
            'message' => 'Record Not Exist!'
        ]);
    }

    $updated = DoctorPatientAppointment::where('id', $request->id)
        ->update(['document_permission' => $request->status]);

    if ($updated) {

        $msg = $request->status ? "Request Approved" : "Request Rejected";

        return response()->json([
            'status' => 1,
            'message' => $msg
        ]);
    }

    return response()->json([
        'status' => 0,
        'message' => 'Something went wrong'
    ]);
}
}
