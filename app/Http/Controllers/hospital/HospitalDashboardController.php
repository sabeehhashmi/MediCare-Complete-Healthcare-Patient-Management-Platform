<?php

namespace App\Http\Controllers\hospital;

use App\Models\OrderModel;
use App\Models\AccountType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\DepartmentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\GymSubscription;
use App\Models\WholeSaleRequests;
use App\Models\OrderProductsModel;
use App\Models\ReservationBooking;
use App\Models\Appointments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class HospitalDashboardController extends Controller
{
    protected $order_detail_route;

    public function dashboard()
    {

        $page_heading = "Hospital Dashboard";
        $module_heading = "Dashboard";
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $doctorIds = Doctor::where('hospital_id', $hospitalId)->pluck('id');
        $totaldoctors = count($doctorIds);
        // dd($totaldoctors);
        $totaldepartments = $hospital->departments->count();
        $appointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->orderBy('id', 'desc')->take(5)->get();
        $totalappointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->count();
        
        $pendingappointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_status', BOOKING_STATUS_PENDING)->count();
        $confirmappointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_status',  BOOKING_STATUS_CONFIRMED)->count();
        $completedappointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_status',  BOOKING_STATUS_COMPLETED)->count();
        $cancelledappointments = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_status',  BOOKING_STATUS_CANCELLED)->count();
       // $appointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->orderBy('id', 'desc')->take(5)->with('patient')->get();         
       $NewConsultation = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_type', 'New Consultation')->count();
       $FollowupConsultation = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_type', 'Follow-up Consultation')->count();
       $SecondOpinion = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_type', 'Second Opinion')->count();
       $OnlineConsultation = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_type', 'Online Consultation')->count();
       $EmergencyConsultation = DoctorPatientAppointment::where('hospital_id', $hospitalId)->where('booking_type', 'Emergency Consultation')->count();
       
       return view('hospital.dashboard', compact('page_heading','module_heading','totaldepartments',
       'totaldoctors','totalappointments','pendingappointments','confirmappointments','completedappointments',
       'cancelledappointments','appointments',
    'NewConsultation',
        'FollowupConsultation',
        'SecondOpinion',
        'OnlineConsultation',
        'EmergencyConsultation'));
    }

    public function notifications(){
        $page_heading = "Notifications";
        $module_heading = "Notifications";
        return view('hospital.notifications', compact('page_heading','module_heading'));
    }

    public function getOrders()
    {
        $vendor_id = auth()->user()->id;
        $user_tye_id = auth()->user()->user_type_id;
        $activity_type_id = auth()->user()->activity_type_id;

        if ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $this->order_detail_route = url('vendor/gym/subscription_details/');

                $orders = GymSubscription::select('gym_subscriptions.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"))
                    ->leftjoin('users', 'users.id', 'gym_subscriptions.user_id')
                    ->where('store_id', $vendor_id)
                    ->with(['customer'])
                    ->orderBy('gym_subscriptions.id', 'DESC')
                    ->limit(10)->get();
            } else {
                $this->order_detail_route = url('vendor/reservation/order_details/');

                $orders = ReservationBooking::select('reservation_bookings.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, reservation_bookings.total_amount as grand_total"))
                    ->leftjoin('users', 'users.id', 'reservation_bookings.user_id')
                    ->where('vendor_id', $vendor_id)
                    ->with(['customer'])
                    ->orderBy('reservation_bookings.id', 'DESC')
                    ->limit(10)->get();
            }
        } elseif ($user_tye_id == AccountType::SERVICE_PROVIDERS) {
            $this->order_detail_route = url('vendor/service/order_details/');
            $orders = ServiceRequest::select('service_requests.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, service_requests.total_amount as grand_total"))
                ->leftjoin('users', 'users.id', 'service_requests.user_id')
                ->where('store_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('service_requests.id', 'DESC')
                ->limit(10)->get();
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $this->order_detail_route = url('vendor/wholesale/order_details/');
            $orders = WholeSaleRequests::select('whole_sale_requests.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, whole_sale_requests.grand_total as grand_total"))
                ->leftjoin('users', 'users.id', 'whole_sale_requests.user_id')
                ->where('store_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('whole_sale_requests.id', 'DESC')
                ->limit(10)->get();
        } else {
            //if ($user_tye_id == AccountType::COMMERCIAL_CENTER)
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT) {
                $this->order_detail_route = url('vendor/food/order_details/');
                $orders = OrderModel::foodProductsOnly()->select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, orders.order_id as id"))
                    ->leftjoin('users', 'users.id', 'orders.user_id')
                    ->where('store_id', $vendor_id)
                    ->with(['customer'])
                    ->orderBy('orders.order_id', 'DESC')
                    ->limit(10)->get();
            } else {
                $this->order_detail_route = url('vendor/order_details/');
                $orders = OrderModel::select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, orders.order_id as id"))
                    ->leftjoin('users', 'users.id', 'orders.user_id')
                    ->where('store_id', $vendor_id)
                    ->with(['customer'])
                    ->orderBy('orders.order_id', 'DESC')
                    ->limit(10)->get();
            }
        }

        return $orders;
    }

    public function getOrderStatusCount()
    {
        $vendor_id = auth()->user()->id;
        $user_tye_id = auth()->user()->user_type_id;
        $activity_type_id = auth()->user()->activity_type_id;

        if ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $st_count['pending'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_pending'))->count();
                // $st_count['cancelled'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_cancelled'))->count();
                $st_count['rejected'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_rejected'))->count();
                $st_count['completed'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_completed'))->count();
            } else {
                $st_count['pending'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_waiting_for_confirmation'))->count();
                $st_count['confirmed'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_booking_confirmed'))->count();
                $st_count['reserved'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_reserved'))->count();
                $st_count['completed'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_completed'))->count();
                $st_count['rejected'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_rejected'))->count();
                // $st_count['cancelled'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.reservation_status_cancelled'))->count();
            }
        } elseif ($user_tye_id == AccountType::SERVICE_PROVIDERS) {
            $st_count['pending'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_status_pending'))->count();
            $st_count['quote_added'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_added'))->count();
            $st_count['quote_accepted'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_accepted'))->count();
            $st_count['quote_rejected'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_rejected'))->count();
            $st_count['service_rejected'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_status_rejected'))->count();
            $st_count['on_the_way'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_on_the_way'))->count();
            $st_count['work_started'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_work_started'))->count();
            $st_count['work_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_work_completed'))->count();
            $st_count['payment_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_payment_completed'))->count();
            $st_count['service_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_service_completed'))->count();
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $st_count['pending'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_status_pending'))->count();
            $st_count['accepted'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_added'))->count();
            $st_count['rejected'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_status_rejected'))->count();
            $st_count['quote_accepted'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_accepted'))->count();
            $st_count['quote_rejected'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_rejected'))->count();
            $st_count['payment_completed'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_payment_completed'))->count();
            $st_count['on_the_way'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_on_the_way'))->count();
            $st_count['completed'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_completed'))->count();
        } else {
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT) {
                $st_count['pending'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_pending'))->count();
                $st_count['accepted'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_accepted'))->count();
                $st_count['preparing_order'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_ready_for_delivery'))->count();
                $st_count['dispatched'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_dispatched'))->count();
                $st_count['delivered'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_delivered'))->count();
                $st_count['rejected'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_rejected'))->count();
            } else {
                $st_count['pending'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_pending'))->count();
                $st_count['accepted'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_accepted'))->count();
                $st_count['ready_for_delivery'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_ready_for_delivery'))->count();
                $st_count['dispatched'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_dispatched'))->count();
                $st_count['delivered'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_delivered'))->count();
                $st_count['rejected'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_rejected'))->count();
            }
        }

        $labels = capitalizeAndRemoveAWordInArray(array_keys($st_count));

        return ['data' => $st_count, 'labels' => $labels];
    }

    public function getLastNDays($days, $format = 'd/m')
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
