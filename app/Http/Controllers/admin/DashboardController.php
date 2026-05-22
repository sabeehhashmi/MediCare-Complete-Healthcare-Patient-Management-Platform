<?php

namespace App\Http\Controllers\Admin;

use App\Models\VendorModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorPatientAppointment;
use App\Models\OrderProductsModel;

use DB;

use App\Http\Controllers\Controller;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller
{
    private function getFirstLastDate($date){
        $datefrom = date("Y-m", strtotime($date))."-01";
        $dateto = date("Y-m-t", strtotime($date));

        return [$datefrom, $dateto];
    }

    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        if (!get_user_permission('dashboard', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Dashboard";
        $hospital = Hospital::where('type', TYPE_HOSPITAL)->whereHas('user',function($q){
            $q->where('deleted', 0);
        })->count();
        $clinic = Hospital::where('type', TYPE_CLINIC)->whereHas('user',function($q){
            $q->where('deleted', 0);
        })->count();
        $doctor = Doctor::query();
        $doctor->leftJoin('users', 'users.id', '=', 'doctors.user_id')
            ->leftJoin('country', 'country.id', '=', 'doctors.country_id')
            ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
            ->leftJoin('department_doctors', 'department_doctors.doctor_id', '=', 'doctors.id')
            ->leftJoin('doctor_specialities', 'doctor_specialities.doctor_id', '=', 'doctors.id')
            ->leftJoin('country_of_origins', 'country_of_origins.id', '=', 'doctors.country_id')
            ->leftJoin('doctor_intrests', 'doctor_intrests.doctor_id', '=', 'doctors.id');
        $doctor = $doctor->count();
        $doctor = Doctor::whereHas('user',function($q){
            $q->where('deleted', 0);
        })->count();
        $patient = User::where('role', USER_ROLE)->where('deleted', 0)->count();
        // dd($totaldoctors);
        // $totaldepartments = $hospital->departments->count();
        $appointments = DoctorPatientAppointment::orderBy('id', 'desc')->take(10)->get();
        $totalappointments = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))->count();

        $pendingappointments = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))->where('booking_status', BOOKING_STATUS_PENDING)->count();
        $confirmappointments = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))->where('booking_status',  BOOKING_STATUS_CONFIRMED)->count();
        $completedappointments = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))->where('booking_status',  BOOKING_STATUS_COMPLETED)->count();
        $cancelledappointments = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))->where('booking_status',  BOOKING_STATUS_CANCELLED)->count();
//        $totalappointments = DoctorPatientAppointment::count();

       $NewConsultation = DoctorPatientAppointment::where('booking_type', 'New Consultation')->count();
       $FollowupConsultation = DoctorPatientAppointment::where('booking_type', 'Follow-up Consultation')->count();
       $SecondOpinion = DoctorPatientAppointment::where('booking_type', 'Second Opinion')->count();
       $OnlineConsultation = DoctorPatientAppointment::where('booking_type', 'Online Consultation')->count();
       $EmergencyConsultation = DoctorPatientAppointment::where('booking_type', 'Emergency Consultation')->count();
//        $confirmappointments = DoctorPatientAppointment::where('booking_status',  BOOKING_STATUS_CONFIRMED)->count();
//        $completedappointments = DoctorPatientAppointment::where('booking_status',  BOOKING_STATUS_COMPLETED)->count();
//        $cancelledappointments = DoctorPatientAppointment::where('booking_status',  BOOKING_STATUS_CANCELLED)->count();
        $time_slot = TIME_SLOTS;

        return view('admin.dashboard', compact('page_heading',
        'time_slot',
        'hospital',
        'clinic',
        'doctor',
        'patient',
        'appointments',
        'totalappointments',
        'pendingappointments',
        'confirmappointments',
        'completedappointments',
        'cancelledappointments',
        'NewConsultation',
        'FollowupConsultation',
        'SecondOpinion',
        'OnlineConsultation',
        'EmergencyConsultation'
    ));
    }
    public function notifications(){
        $page_heading = "Notifications";
        return view('admin.notifications', compact('page_heading'));

    }

    function getLastNDays($days, $format = 'd/m')
    {
        $m = gmdate("m");
        $de = gmdate("d");
        $y = gmdate("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] =  gmdate($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}
