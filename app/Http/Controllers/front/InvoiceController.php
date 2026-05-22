<?php
// app/Http/Controllers/front/InvoiceController.php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\DoctorPatientAppointment;
use App\Models\Members;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices (appointments only)
     */
    public function index(Request $request)
    {
        $page_heading = "My Invoices";
        
        $user_id = auth()->id();
        
        // Get saved patients for permission check
        $savedPatients = Members::where('user_id', $user_id)->pluck('id')->toArray();
        
        // Build query for appointment invoices
        $query = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctor_user', 'doctor_user.id', '=', 'doctors.user_id')
            ->join('hospitals', 'hospitals.id', '=', 'doctor_patient_appointments.hospital_id')
            ->leftJoin('members', 'members.id', '=', 'doctor_patient_appointments.member_id')
            ->join('users as patient_user', 'patient_user.id', '=', 'doctor_patient_appointments.user_id')
            ->select(
                'doctor_patient_appointments.id',
                'doctor_patient_appointments.booking_id',
                'doctor_patient_appointments.booking_date',
                'doctor_patient_appointments.booking_time_slot',
                'doctor_patient_appointments.booking_status',
                'doctor_patient_appointments.consultation_fee',
                'doctor_patient_appointments.payment_status',
                'doctor_patient_appointments.payment_completed_at',
                'doctor_user.name as doctor_name',
                'doctor_user.email as doctor_email',
                'patient_user.name as patient_name',
                'patient_user.email as patient_email',
                'patient_user.phone as patient_phone',
                'hospitals.name_en as hospital_name',
                'hospitals.address as hospital_address',
                
                'members.full_name as patient_member_name'
            )
            ->where(function($q) use ($user_id, $savedPatients) {
                $q->where('doctor_patient_appointments.user_id', $user_id);
                if (!empty($savedPatients)) {
                    $q->orWhereIn('doctor_patient_appointments.member_id', $savedPatients);
                }
            })
            ->whereIn('doctor_patient_appointments.booking_status', ['Completed', 'Confirmed'])
            ->where('doctor_patient_appointments.payment_status', 'paid')
            ->whereNotNull('doctor_patient_appointments.consultation_fee')
            ->whereNull('doctor_patient_appointments.deleted_at');
        
        // Apply filters
        if ($request->filled('from_date')) {
            try {
                $fromDate = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
                $query->whereDate('doctor_patient_appointments.booking_date', '>=', $fromDate);
            } catch (\Exception $e) {}
        }
        
        if ($request->filled('to_date')) {
            try {
                $toDate = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
                $query->whereDate('doctor_patient_appointments.booking_date', '<=', $toDate);
            } catch (\Exception $e) {}
        }
        
        if ($request->filled('invoice_number')) {
            $invoiceNumber = $request->invoice_number;
            $query->where('doctor_patient_appointments.booking_id', 'LIKE', "%{$invoiceNumber}%");
        }
        
        // Get paginated results
        $invoices = $query->orderBy('doctor_patient_appointments.booking_date', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return view('front.invoices.index', compact('page_heading', 'invoices'));
    }
    
    /**
     * Show invoice details
     */
    public function show($id)
    {
        $user_id = auth()->id();
        $savedPatients = Members::where('user_id', $user_id)->pluck('id')->toArray();
        
        $invoice = DoctorPatientAppointment::with(['doctor.user', 'doctor.doctorSpecialities.speciality', 'hospital', 'member', 'user'])
            ->where('doctor_patient_appointments.id', $id)
            ->where(function($q) use ($user_id, $savedPatients) {
                $q->where('doctor_patient_appointments.user_id', $user_id);
                if (!empty($savedPatients)) {
                    $q->orWhereIn('doctor_patient_appointments.member_id', $savedPatients);
                }
            })
            ->whereIn('doctor_patient_appointments.booking_status', ['Completed', 'Confirmed'])
            ->firstOrFail();
        
        // Get specialty name
        $specialtyName = 'General';
        if ($invoice->doctor->doctorSpecialities->isNotEmpty()) {
            $specialtyName = $invoice->doctor->doctorSpecialities->first()->speciality->name_en ?? 'General';
        }
        
        // Prepare invoice data
        $invoiceData = (object)[
            'id' => $invoice->id,
            'invoice_number' => $invoice->booking_id,
            'invoice_date' => $invoice->booking_date,
            'booking_date' => $invoice->booking_date,
            'booking_time_slot' => $invoice->booking_time_slot,
            'booking_status' => $invoice->booking_status,
            'booking_type' => $invoice->booking_type ?? 'In-Clinic',
            'consultation_fee' => $invoice->consultation_fee,
            'subtotal' => $invoice->consultation_fee,
            'tax' => 0,
            'total' => $invoice->consultation_fee,
            'payment_status' => $invoice->payment_status,
            'payment_method' => 'Stripe',
            'payment_completed_at' => $invoice->payment_completed_at,
            'doctor_name' => $invoice->doctor->user->name ?? 'N/A',
            'doctor_email' => $invoice->doctor->user->email ?? 'N/A',
            'doctor_specialty' => $specialtyName,
            'hospital_name' => $invoice->hospital->name_en ?? 'N/A',
            'hospital_address' => $invoice->hospital->address ?? 'N/A',
            'hospital_phone' => $invoice->hospital->phone ?? 'N/A',
            'patient_name' => $invoice->member->full_name ?? $invoice->user->name ?? 'N/A',
            'patient_email' => $invoice->user->email ?? 'N/A',
            'patient_phone' => $invoice->user->phone ?? 'N/A',
            'patient_member_name' => $invoice->member->full_name ?? null,
        ];
        
        return view('front.invoices.show', compact('invoiceData'));
    }
    
    /**
     * Download invoice as PDF
     */
    public function download($id)
    {
        $user_id = auth()->id();
        $savedPatients = Members::where('user_id', $user_id)->pluck('id')->toArray();
        
        $invoice = DoctorPatientAppointment::with(['doctor.user', 'doctor.doctorSpecialities.speciality', 'hospital', 'member', 'user'])
            ->where('doctor_patient_appointments.id', $id)
            ->where(function($q) use ($user_id, $savedPatients) {
                $q->where('doctor_patient_appointments.user_id', $user_id);
                if (!empty($savedPatients)) {
                    $q->orWhereIn('doctor_patient_appointments.member_id', $savedPatients);
                }
            })
            ->whereIn('doctor_patient_appointments.booking_status', ['Completed', 'Confirmed'])
            ->firstOrFail();
        
        // Get specialty name
        $specialtyName = 'General';
        if ($invoice->doctor->doctorSpecialities->isNotEmpty()) {
            $specialtyName = $invoice->doctor->doctorSpecialities->first()->speciality->name_en ?? 'General';
        }
        
        // Prepare invoice data
        $invoiceData = (object)[
            'id' => $invoice->id,
            'invoice_number' => $invoice->booking_id,
            'invoice_date' => $invoice->booking_date,
            'booking_date' => $invoice->booking_date,
            'booking_time_slot' => $invoice->booking_time_slot,
            'booking_status' => $invoice->booking_status,
            'booking_type' => $invoice->booking_type ?? 'In-Clinic',
            'consultation_fee' => $invoice->consultation_fee,
            'subtotal' => $invoice->consultation_fee,
            'tax' => 0,
            'total' => $invoice->consultation_fee,
            'payment_status' => $invoice->payment_status,
            'payment_method' => 'Stripe',
            'payment_completed_at' => $invoice->payment_completed_at,
            'doctor_name' => $invoice->doctor->user->name ?? 'N/A',
            'doctor_email' => $invoice->doctor->user->email ?? 'N/A',
            'doctor_specialty' => $specialtyName,
            'hospital_name' => $invoice->hospital->name_en ?? 'N/A',
            'hospital_address' => $invoice->hospital->address ?? 'N/A',
            'hospital_phone' => $invoice->hospital->phone ?? 'N/A',
            'patient_name' => $invoice->member->full_name ?? $invoice->user->name ?? 'N/A',
            'patient_email' => $invoice->user->email ?? 'N/A',
            'patient_phone' => $invoice->user->phone ?? 'N/A',
            'patient_member_name' => $invoice->member->full_name ?? null,
        ];
        
        $pdf = Pdf::loadView('front.invoices.pdf', compact('invoiceData'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('invoice-' . $invoice->booking_id . '.pdf');
    }
}