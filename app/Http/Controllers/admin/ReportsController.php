<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorPatientAppointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Members;
use App\Models\HospitalDoctorFeedback;
use App\Models\BookingType;
use App\Models\DepartmentModel;
use App\Models\Specialty;
use App\Models\Emirate;
use App\Models\Prescription;
use App\Models\AppointmentDoc;
use App\Models\DoctorAppointmentsStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportsController extends Controller
{
    /**
     * Reports Dashboard with Tabs
     */
    public function index()
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Reports Dashboard";
        
        // Get summary statistics
        $totalPatients = User::where('role', USER_ROLE)->where('deleted', 0)->count();
        $totalDoctors = Doctor::whereHas('user', function($q) {
            $q->where('active', 1)->where('deleted', 0);
        })->count();
        $totalHospitals = Hospital::where('type', TYPE_HOSPITAL)->count();
        $totalClinics = Hospital::where('type', TYPE_CLINIC)->count();
        
        // Appointment statistics
        $totalAppointments = DoctorPatientAppointment::count();
        $pendingAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_PENDING)->count();
        $confirmedAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_CONFIRMED)->count();
        $completedAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)->count();
        $cancelledAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_CANCELLED)->count();
        $rescheduledAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_RESCHEDULED)->count();
        
        // Monthly appointments for chart - PostgreSQL compatible
        $monthlyAppointments = DoctorPatientAppointment::select(
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
            DB::raw('count(*) as total')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        
        // Recent activities
        $recentAppointments = DoctorPatientAppointment::with(['doctor.user', 'user', 'hospital'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.index', compact(
            'page_heading',
            'totalPatients',
            'totalDoctors',
            'totalHospitals',
            'totalClinics',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'rescheduledAppointments',
            'monthlyAppointments',
            'recentAppointments'
        ));
    }
    
    /**
     * Patients Report with Filters
     */
    public function patients(Request $request)
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Patients Report";
        
        $query = User::where('role', USER_ROLE)->where('deleted', 0);
        
        // Apply filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('status')) {
            $query->where('active', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('patient_id', 'LIKE', "%{$search}%");
            });
        }
        
        $patients = $query->orderBy('id', 'desc')->paginate(20);
        
        // Get appointment counts for each patient
        foreach ($patients as $patient) {
            $patient->appointment_count = DoctorPatientAppointment::where('user_id', $patient->id)->count();
            $patient->member_count = Members::where('user_id', $patient->id)->count();
        }
        
        return view('admin.reports.patients', compact('page_heading', 'patients'));
    }
    
    /**
     * Export Patients Report with all details
     */
    public function exportPatients(Request $request)
    {
        $query = User::where('role', USER_ROLE)->where('deleted', 0);
        
        // Apply same filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('status')) {
            $query->where('active', $request->status);
        }
        
        $patients = $query->orderBy('id', 'desc')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Patient ID',
            'C1' => 'Full Name',
            'D1' => 'Email',
            'E1' => 'Phone Number',
            'F1' => 'WhatsApp Number',
            'G1' => 'Gender',
            'H1' => 'Date of Birth',
            'I1' => 'Age',
            'J1' => 'Address',
            'K1' => 'Identification Type',
            'L1' => 'Identification Number',
            'M1' => 'Insurance Provider',
            'N1' => 'Sub Insurance',
            'O1' => 'Total Appointments',
            'P1' => 'Completed Appointments',
            'Q1' => 'Cancelled Appointments',
            'R1' => 'Total Members',
            'S1' => 'Member Names',
            'T1' => 'Registration Date',
            'U1' => 'Status'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:U1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($patients as $key => $patient) {
            // Get appointment counts
            $appointments = DoctorPatientAppointment::where('user_id', $patient->id)->get();
            $totalAppointments = $appointments->count();
            $completedAppointments = $appointments->where('booking_status', BOOKING_STATUS_COMPLETED)->count();
            $cancelledAppointments = $appointments->where('booking_status', BOOKING_STATUS_CANCELLED)->count();
            
            // Get members
            $members = Members::where('user_id', $patient->id)->get();
            $memberNames = $members->pluck('full_name')->implode(', ');
            
            // Calculate age
            $age = '';
            if ($patient->dob) {
                $age = Carbon::parse($patient->dob)->age;
            }
            
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $patient->patient_id ?? 'N/A');
            $sheet->setCellValue('C' . $row, $patient->first_name . ' ' . $patient->last_name);
            $sheet->setCellValue('D' . $row, $patient->email);
            $sheet->setCellValue('E' . $row, ($patient->dial_code ? '+' . $patient->dial_code : '') . $patient->phone);
            $sheet->setCellValue('F' . $row, ($patient->whatsap_dial_code ? '+' . $patient->whatsap_dial_code : '') . $patient->whatsap_phone);
            $sheet->setCellValue('G' . $row, $patient->gender == 1 ? 'Male' : ($patient->gender == 2 ? 'Female' : 'Other'));
            $sheet->setCellValue('H' . $row, $patient->dob ? Carbon::parse($patient->dob)->format('d-m-Y') : '');
            $sheet->setCellValue('I' . $row, $age);
            $sheet->setCellValue('J' . $row, $patient->address ?? '');
            $sheet->setCellValue('K' . $row, $patient->identification_type ?? '');
            $sheet->setCellValue('L' . $row, $patient->identification_number ?? '');
            $sheet->setCellValue('M' . $row, $patient->insurance->title ?? '');
            $sheet->setCellValue('N' . $row, $patient->subInsurance->title ?? '');
            $sheet->setCellValue('O' . $row, $totalAppointments);
            $sheet->setCellValue('P' . $row, $completedAppointments);
            $sheet->setCellValue('Q' . $row, $cancelledAppointments);
            $sheet->setCellValue('R' . $row, $members->count());
            $sheet->setCellValue('S' . $row, $memberNames);
            $sheet->setCellValue('T' . $row, Carbon::parse($patient->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue('U' . $row, $patient->active == 1 ? 'Active' : 'Inactive');
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'patients_report_' . date('Y-m-d_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Appointments Report with Filters
     */
    public function appointments(Request $request)
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Appointments Report";
        
        $query = DoctorPatientAppointment::with(['doctor.user', 'user', 'hospital', 'member', 'status_history.changedBy']);
        
        // Apply filters
        if ($request->filled('from_date')) {
            $query->whereDate('booking_date', '>=', Carbon::parse($request->from_date));
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('booking_date', '<=', Carbon::parse($request->to_date));
        }
        
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }
        
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }
        
        $appointments = $query->orderBy('id', 'desc')->paginate(20);
        
        $hospitals = Hospital::orderBy('name_en', 'asc')->get();
        $doctors = Doctor::with('user')->get();
        $bookingTypes = BookingType::where('status', 1)->get();
        
        return view('admin.reports.appointments', compact(
            'page_heading',
            'appointments',
            'hospitals',
            'doctors',
            'bookingTypes'
        ));
    }
    
    /**
     * Export Appointments Report with all details
     */
    public function exportAppointments(Request $request)
    {
        $query = DoctorPatientAppointment::with(['doctor.user', 'user', 'hospital', 'member', 'followups', 'docs', 'status_history.changedBy']);
        
        // Apply filters
        if ($request->filled('from_date')) {
            $query->whereDate('booking_date', '>=', Carbon::parse($request->from_date));
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('booking_date', '<=', Carbon::parse($request->to_date));
        }
        
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }
        
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        $appointments = $query->orderBy('id', 'desc')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Booking ID',
            'C1' => 'Hospital Name',
            'D1' => 'Doctor Name',
            'E1' => 'Doctor Email',
            'F1' => 'Doctor Phone',
            'G1' => 'Patient Name',
            'H1' => 'Patient Email',
            'I1' => 'Patient Phone',
            'J1' => 'Member Name',
            'K1' => 'Booking Type',
            'L1' => 'Booking Date',
            'M1' => 'Time Slot',
            'N1' => 'Previous Booking Date',
            'O1' => 'Previous Time Slot',
            'P1' => 'Booking Status',
            'Q1' => 'Reason for Cancellation',
            'R1' => 'Reason for Reschedule',
            'S1' => 'Follow-up Details',
            'T1' => 'Documents Uploaded',
            'U1' => 'Created By',
            'V1' => 'Created Date',
            'W1' => 'Status History',
            'X1' => 'Department'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:X1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($appointments as $key => $appointment) {
            // Get follow-up details
            $followupDetails = '';
            foreach ($appointment->followups as $followup) {
                $followupDetails .= Carbon::parse($followup->followup_date)->format('d-m-Y') . ': ' . $followup->notes . "\n";
            }
            
            // Get documents
            $documents = $appointment->docs->pluck('docment')->implode(', ');
            
            // Get status history
            $statusHistory = '';
            foreach ($appointment->status_history as $history) {
                $statusHistory .= $history->status . ' by ' . ($history->changedBy->name ?? 'System') . ' on ' . Carbon::parse($history->changed_at)->format('d-m-Y H:i') . "\n";
            }
            
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $appointment->booking_id);
            $sheet->setCellValue('C' . $row, $appointment->hospital->name_en ?? '');
            $sheet->setCellValue('D' . $row, $appointment->doctor->user->name ?? '');
            $sheet->setCellValue('E' . $row, $appointment->doctor->user->email ?? '');
            $sheet->setCellValue('F' . $row, ($appointment->doctor->user->dial_code ?? '') . ($appointment->doctor->user->phone ?? ''));
            $sheet->setCellValue('G' . $row, $appointment->user->first_name . ' ' . $appointment->user->last_name);
            $sheet->setCellValue('H' . $row, $appointment->user->email);
            $sheet->setCellValue('I' . $row, ($appointment->user->dial_code ?? '') . ($appointment->user->phone ?? ''));
            $sheet->setCellValue('J' . $row, $appointment->member->full_name ?? '');
            $sheet->setCellValue('K' . $row, $appointment->booking_type);
            $sheet->setCellValue('L' . $row, Carbon::parse($appointment->booking_date)->format('d-m-Y'));
            $sheet->setCellValue('M' . $row, $appointment->booking_time_slot);
            $sheet->setCellValue('N' . $row, $appointment->previous_booking_date ? Carbon::parse($appointment->previous_booking_date)->format('d-m-Y') : '');
            $sheet->setCellValue('O' . $row, $appointment->previous_booking_time_slot ?? '');
            $sheet->setCellValue('P' . $row, ucfirst($appointment->booking_status));
            $sheet->setCellValue('Q' . $row, $appointment->reason_cancel ?? '');
            $sheet->setCellValue('R' . $row, $appointment->reason_reschedule ?? '');
            $sheet->setCellValue('S' . $row, $followupDetails);
            $sheet->setCellValue('T' . $row, $documents);
            $sheet->setCellValue('U' . $row, $appointment->created_by_user->name ?? '');
            $sheet->setCellValue('V' . $row, Carbon::parse($appointment->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue('W' . $row, $statusHistory);
            $sheet->setCellValue('X' . $row, $appointment->department->title ?? '');
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'X') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'appointments_report_' . date('Y-m-d_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Doctors Report with Filters
     */
    public function doctors(Request $request)
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Doctors Report";
        
        $query = Doctor::with(['user', 'hospital', 'departments', 'specialities', 'qualifications', 'languages', 'allFeedbacks']);
        
        // Apply filters
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        
        if ($request->filled('department_id')) {
            $query->whereHas('departments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        
        if ($request->filled('speciality_id')) {
            $query->whereHas('specialities', function($q) use ($request) {
                $q->where('speciality_id', $request->speciality_id);
            });
        }
        
        if ($request->filled('status')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('active', $request->status);
            });
        }
        
        // NEW: Rating filter
        if ($request->filled('rating') && $request->rating != '') {
            $rating = (float) $request->rating;
            
            // Get doctor IDs that have average rating >= selected rating
            $doctorIdsWithRating = HospitalDoctorFeedback::select('doctor_id')
                ->where('status', 1)
                ->whereNotNull('doctor_id')
                ->where('rating', '>=', 1)
                ->groupBy('doctor_id')
                ->havingRaw('AVG(rating) >= ?', [$rating])
                ->pluck('doctor_id')
                ->toArray();
            
            if (!empty($doctorIdsWithRating)) {
                $query->whereIn('doctors.id', $doctorIdsWithRating);
            } else {
                // No doctors match - return empty result
                $query->whereRaw('1 = 0');
            }
        }

        
        
        $doctors = $query->orderBy('id', 'desc')->paginate(20);
        
        $hospitals = Hospital::orderBy('name_en', 'asc')->get();
        $departments = DepartmentModel::where('status', 1)->get();
        $specialities = Specialty::where('active', 1)->get();
        
        // Get appointment statistics and review statistics for each doctor
        foreach ($doctors as $doctor) {
            $doctor->total_appointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->count();
            $doctor->completed_appointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_COMPLETED)->count();
            $doctor->cancelled_appointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_CANCELLED)->count();
            $doctor->pending_appointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_PENDING)->count();
            
            // Review statistics
            $doctor->average_rating = $doctor->average_rating;
            $doctor->total_reviews = $doctor->total_reviews;
            $doctor->positive_reviews = $doctor->allFeedbacks()
                ->where('status', 1)
                ->where('rating', '>=', 4)
                ->count();
        }
        
        return view('admin.reports.doctors', compact(
            'page_heading',
            'doctors',
            'hospitals',
            'departments',
            'specialities'
        ));
    }
    
    
    /**
     * Export Doctors Report with all details
     */
    public function exportDoctors(Request $request)
    {
        $query = Doctor::with(['user', 'hospital', 'departments', 'specialities', 'qualifications', 'languages', 'intrests', 'allFeedbacks']);
        
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        
        if ($request->filled('department_id')) {
            $query->whereHas('departments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
         if ($request->filled('rating') && $request->rating != '') {
            $rating = (float) $request->rating;
            
            $doctorIdsWithRating = HospitalDoctorFeedback::select('doctor_id')
                ->where('status', 1)
                ->whereNotNull('doctor_id')
                ->groupBy('doctor_id')
                ->havingRaw('AVG(rating) >= ?', [$rating])
                ->pluck('doctor_id')
                ->toArray();
            
            if (!empty($doctorIdsWithRating)) {
                $query->whereIn('doctors.id', $doctorIdsWithRating);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        
        $doctors = $query->orderBy('id', 'desc')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers - Added review columns
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Doctor Name',
            'C1' => 'Email',
            'D1' => 'Phone Number',
            'E1' => 'Hospital/Clinic',
            'F1' => 'Departments',
            'G1' => 'Specialities',
            'H1' => 'Qualifications',
            'I1' => 'Languages Spoken',
            'J1' => 'Special Interests',
            'K1' => 'Year of Experience',
            'L1' => 'License Numbers',
            'M1' => 'Profile Description',
            'N1' => 'Gender',
            'O1' => 'Country of Origin',
            'P1' => 'Consultation Fee',
            'Q1' => 'Total Appointments',
            'R1' => 'Completed Appointments',
            'S1' => 'Pending Appointments',
            'T1' => 'Cancelled Appointments',
            'U1' => 'Average Rating',
            'V1' => 'Total Reviews',
            'W1' => 'Positive Reviews (4-5 Stars)',
            'X1' => 'Registration Date',
            'Y1' => 'Status'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:Y1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($doctors as $key => $doctor) {
            // Get appointment counts
            $totalAppointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->count();
            $completedAppointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_COMPLETED)->count();
            $pendingAppointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_PENDING)->count();
            $cancelledAppointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                ->where('booking_status', BOOKING_STATUS_CANCELLED)->count();
            
            // Get review statistics
            $averageRating = $doctor->average_rating;
            $totalReviews = $doctor->total_reviews;
            $positiveReviews = $doctor->allFeedbacks()
                ->where('status', 1)
                ->where('rating', '>=', 4)
                ->count();
            
            // Collect license numbers
            $licenseNumbers = [];
            if ($doctor->license_no) $licenseNumbers[] = "DHA: " . $doctor->license_no;
            if ($doctor->license_no_moh) $licenseNumbers[] = "MOH: " . $doctor->license_no_moh;
            if ($doctor->license_no_doh) $licenseNumbers[] = "DOH: " . $doctor->license_no_doh;
            if ($doctor->license_no_dhcc) $licenseNumbers[] = "DHCC: " . $doctor->license_no_dhcc;
            
            // Consultation fee
            $consultationFee = $doctor->user->consultation_fee ?? 0;
            $consultationFee = is_numeric($consultationFee) ? floatval($consultationFee) : 0;
            
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $doctor->user->name ?? '');
            $sheet->setCellValue('C' . $row, $doctor->user->email ?? '');
            $sheet->setCellValue('D' . $row, ($doctor->user->dial_code ?? '') . ($doctor->user->phone ?? ''));
            $sheet->setCellValue('E' . $row, $doctor->hospital->name_en ?? '');
            $sheet->setCellValue('F' . $row, $doctor->departments->pluck('title')->implode(', '));
            $sheet->setCellValue('G' . $row, $doctor->specialities->pluck('name_en')->implode(', '));
            $sheet->setCellValue('H' . $row, $doctor->qualifications->pluck('title')->implode(', '));
            $sheet->setCellValue('I' . $row, $doctor->languages->pluck('title')->implode(', '));
            $sheet->setCellValue('J' . $row, $doctor->intrests->pluck('title')->implode(', '));
            $sheet->setCellValue('K' . $row, $doctor->year_of_experiance ?? '');
            $sheet->setCellValue('L' . $row, implode(', ', $licenseNumbers));
            $sheet->setCellValue('M' . $row, strip_tags($doctor->profile_desciription ?? ''));
            $sheet->setCellValue('N' . $row, $doctor->gender == 1 ? 'Male' : ($doctor->gender == 2 ? 'Female' : 'Other'));
            $sheet->setCellValue('O' . $row, $doctor->country->name ?? '');
            $sheet->setCellValue('P' . $row, $consultationFee);
            $sheet->setCellValue('Q' . $row, $totalAppointments);
            $sheet->setCellValue('R' . $row, $completedAppointments);
            $sheet->setCellValue('S' . $row, $pendingAppointments);
            $sheet->setCellValue('T' . $row, $cancelledAppointments);
            $sheet->setCellValue('U' . $row, $averageRating);
            $sheet->setCellValue('V' . $row, $totalReviews);
            $sheet->setCellValue('W' . $row, $positiveReviews);
            $sheet->setCellValue('X' . $row, Carbon::parse($doctor->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue('Y' . $row, $doctor->user->active == 1 ? 'Active' : 'Inactive');
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'Y') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'doctors_report_' . date('Y-m-d_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Hospitals/Clinics Report with Filters
     */
    public function hospitals(Request $request)
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Hospitals / Clinics Report";
        
        $query = Hospital::with(['user', 'country', 'emirate', 'area', 'departments', 'insurences']);
        
        // Apply filters
        if ($request->filled('type')) {
            // If type filter is applied, filter by that type
            if ($request->type == 'hospital') {
                $query->where('type', TYPE_HOSPITAL);
                $page_heading = "Hospitals Report";
            } elseif ($request->type == 'clinic') {
                $query->where('type', TYPE_CLINIC);
                $page_heading = "Clinics Report";
            }
        }
        // REMOVED the else block that was forcing only hospitals by default
        // Now when no type is selected, it shows BOTH hospitals AND clinics
        
        if ($request->filled('emirate_id')) {
            $query->where('emirate_id', $request->emirate_id);
        }
        
        if ($request->filled('status')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('active', $request->status);
            });
        }
        
        $hospitals = $query->orderBy('id', 'desc')->paginate(20);
        
        $emirates = Emirate::where('active', 1)->get();
        
        // Get statistics for each hospital/clinic
        foreach ($hospitals as $hospital) {
            $hospital->doctor_count = Doctor::where('hospital_id', $hospital->id)->count();
            $hospital->appointment_count = DoctorPatientAppointment::where('hospital_id', $hospital->id)->count();
            $hospital->completed_appointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)
                ->where('booking_status', BOOKING_STATUS_COMPLETED)->count();
            $hospital->pending_appointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)
                ->where('booking_status', BOOKING_STATUS_PENDING)->count();
        }
        
        return view('admin.reports.hospitals', compact(
            'page_heading',
            'hospitals',
            'emirates'
        ));
    }
    
    /**
     * Export Hospitals Report with all details
     */
    public function exportHospitals(Request $request)
    {
        $query = Hospital::with(['user', 'country', 'emirate', 'area', 'departments', 'insurences.subInsurance', 'locations']);
        
        if ($request->filled('type')) {
            if ($request->type == 'hospital') {
                $query->where('type', TYPE_HOSPITAL);
            } elseif ($request->type == 'clinic') {
                $query->where('type', TYPE_CLINIC);
            }
        }
        
        if ($request->filled('emirate_id')) {
            $query->where('emirate_id', $request->emirate_id);
        }
        
        $hospitals = $query->orderBy('id', 'desc')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Name (English)',
            'C1' => 'Name (Arabic)',
            'D1' => 'Type',
            'E1' => 'Email',
            'F1' => 'Phone Number',
            'G1' => 'Direct Appointment Number',
            'H1' => 'Country',
            'I1' => 'Emirate/City',
            'J1' => 'Area',
            'K1' => 'Address',
            'L1' => 'Location URL',
            'M1' => 'Website',
            'N1' => 'Profile Description (EN)',
            'O1' => 'Profile Description (AR)',
            'P1' => 'Insurance Providers',
            'Q1' => 'Number of Doctors',
            'R1' => 'Total Appointments',
            'S1' => 'Completed Appointments',
            'T1' => 'Pending Appointments',
            'U1' => 'Contract Signed',
            'V1' => 'Registration Date',
            'W1' => 'Status'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:W1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($hospitals as $key => $hospital) {
            // Get insurance providers
            $insurances = $hospital->insurences->map(function($ins) {
                return $ins->insurance->title ?? '' . ($ins->subInsurance ? ' - ' . $ins->subInsurance->title : '');
            })->implode(', ');
            
            // Get doctor count
            $doctorCount = Doctor::where('hospital_id', $hospital->id)->count();
            
            // Get appointment counts
            $totalAppointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)->count();
            $completedAppointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)
                ->where('booking_status', BOOKING_STATUS_COMPLETED)->count();
            $pendingAppointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)
                ->where('booking_status', BOOKING_STATUS_PENDING)->count();
            
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $hospital->name_en);
            $sheet->setCellValue('C' . $row, $hospital->name_ar ?? '');
            $sheet->setCellValue('D' . $row, $hospital->type == TYPE_HOSPITAL ? 'Hospital' : 'Clinic');
            $sheet->setCellValue('E' . $row, $hospital->user->email ?? '');
            $sheet->setCellValue('F' . $row, ($hospital->user->dial_code ?? '') . ($hospital->user->phone ?? ''));
            $sheet->setCellValue('G' . $row, ($hospital->appointment_dial_code ?? '') . ($hospital->appointment_phone ?? ''));
            $sheet->setCellValue('H' . $row, $hospital->country->name ?? '');
            $sheet->setCellValue('I' . $row, $hospital->emirate->name_en ?? '');
            $sheet->setCellValue('J' . $row, $hospital->area->name_en ?? '');
            $sheet->setCellValue('K' . $row, $hospital->address);
            $sheet->setCellValue('L' . $row, $hospital->locations->first()->location ?? '');
            $sheet->setCellValue('M' . $row, $hospital->website);
            $sheet->setCellValue('N' . $row, strip_tags($hospital->profile_description ?? ''));
            $sheet->setCellValue('O' . $row, strip_tags($hospital->profile_description_ar ?? ''));
            $sheet->setCellValue('P' . $row, $insurances);
            $sheet->setCellValue('Q' . $row, $doctorCount);
            $sheet->setCellValue('R' . $row, $totalAppointments);
            $sheet->setCellValue('S' . $row, $completedAppointments);
            $sheet->setCellValue('T' . $row, $pendingAppointments);
            $sheet->setCellValue('U' . $row, $hospital->is_contract_signed ? 'Yes' : 'No');
            $sheet->setCellValue('V' . $row, Carbon::parse($hospital->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue('W' . $row, $hospital->user->active == 1 ? 'Active' : 'Inactive');
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'W') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'hospitals_report_' . date('Y-m-d_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Financial Report
     */
    public function financial(Request $request)
    {
        if (!get_user_permission('reports', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Financial Report";
        
        // Get date range
        $fromDate = $request->filled('from_date') ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date) : Carbon::now()->endOfDay();
        
        // Get appointment revenue
        $completedAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();
        
        // Fix: Cast consultation_fee to numeric for PostgreSQL
        $totalConsultationFees = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
            ->whereNotNull('users.consultation_fee')
            ->where('users.consultation_fee', '!=', '')
            ->where(DB::raw("NULLIF(users.consultation_fee, '') IS NOT NULL"))
            ->sum(DB::raw("CAST(users.consultation_fee AS NUMERIC)"));
        
        $totalDoctors = Doctor::count();
        
        // Alternative: Get all fees and sum in PHP if casting doesn't work
        // $consultationFees = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
        //     ->whereNotNull('users.consultation_fee')
        //     ->where('users.consultation_fee', '!=', '')
        //     ->pluck('users.consultation_fee')
        //     ->toArray();
        // $totalConsultationFees = array_sum(array_map('floatval', $consultationFees));
        
        // Get monthly breakdown - PostgreSQL compatible
        $monthlyRevenue = DoctorPatientAppointment::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('count(*) as total_appointments')
            )
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        
        // Get appointment status distribution
        $statusDistribution = [
            'pending' => DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_PENDING)->count(),
            'confirmed' => DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_CONFIRMED)->count(),
            'completed' => DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)->count(),
            'cancelled' => DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_CANCELLED)->count(),
            'rescheduled' => DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_RESCHEDULED)->count(),
        ];
        
        return view('admin.reports.financial', compact(
            'page_heading',
            'fromDate',
            'toDate',
            'completedAppointments',
            'totalConsultationFees',
            'totalDoctors',
            'monthlyRevenue',
            'statusDistribution'
        ));
    }
    
    /**
     * Export Financial Report
     */
    public function exportFinancial(Request $request)
    {
        $fromDate = $request->filled('from_date') ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date) : Carbon::now()->endOfDay();
        
        $appointments = DoctorPatientAppointment::with(['doctor.user', 'user', 'hospital'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('id', 'desc')
            ->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Booking ID',
            'C1' => 'Hospital',
            'D1' => 'Doctor',
            'E1' => 'Patient',
            'F1' => 'Booking Type',
            'G1' => 'Booking Date',
            'H1' => 'Booking Status',
            'I1' => 'Consultation Fee',
            'J1' => 'Created Date'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($appointments as $key => $appointment) {
            // Get consultation fee as float (handles string values)
            $consultationFee = $appointment->doctor->user->consultation_fee ?? 0;
            $consultationFee = is_numeric($consultationFee) ? floatval($consultationFee) : 0;
            
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $appointment->booking_id);
            $sheet->setCellValue('C' . $row, $appointment->hospital->name_en ?? '');
            $sheet->setCellValue('D' . $row, $appointment->doctor->user->name ?? '');
            $sheet->setCellValue('E' . $row, $appointment->user->first_name . ' ' . $appointment->user->last_name);
            $sheet->setCellValue('F' . $row, $appointment->booking_type);
            $sheet->setCellValue('G' . $row, Carbon::parse($appointment->booking_date)->format('d-m-Y'));
            $sheet->setCellValue('H' . $row, ucfirst($appointment->booking_status));
            $sheet->setCellValue('I' . $row, $consultationFee);
            $sheet->setCellValue('J' . $row, Carbon::parse($appointment->created_at)->format('d-m-Y H:i'));
            $row++;
        }
        
        // Add summary row
        $totalConsultationFees = $appointments->sum(function($app) {
            $fee = $app->doctor->user->consultation_fee ?? 0;
            return is_numeric($fee) ? floatval($fee) : 0;
        });
        
        $sheet->setCellValue('A' . $row, 'Total');
        $sheet->setCellValue('I' . $row, $totalConsultationFees);
        $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'financial_report_' . date('Y-m-d_His') . '.xlsx';
        $writer->save($fileName);
        
        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    /**
     * Get doctor reviews for AJAX request
     */
    public function getDoctorReviews($doctor_id)
    {
        try {
            $reviews = HospitalDoctorFeedback::with(['user'])
                ->where('doctor_id', $doctor_id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get()
                ->map(function($review) {
                    return [
                        'id' => $review->id,
                        'patient_name' => $review->user ? $review->user->first_name . ' ' . $review->user->last_name : 'N/A',
                        'rating' => $review->rating,
                        'feeback_message' => $review->feeback_message,
                        'created_at' => Carbon::parse($review->created_at)->format('d-m-Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get patient members for AJAX request
     */
    public function getPatientMembers($patient_id)
    {
        try {
            $members = Members::where('user_id', $patient_id)->get()->map(function($member) {
                return [
                    'id' => $member->id,
                    'full_name' => $member->full_name,
                    'full_name_ar' => $member->full_name_ar,
                    'age' => $member->age,
                    'gender' => $member->gender == 1 ? 'Male' : ($member->gender == 2 ? 'Female' : 'Other'),
                    'gender_value' => $member->gender,
                    'insurance' => $member->insurence ? $member->insurence->title : 'N/A',
                    'sub_insurance' => $member->subInsurance ? $member->subInsurance->title : 'N/A',
                    'image' => $member->user_image ? get_uploaded_image_url($member->user_image, 'user_image_upload_dir') : null,
                    'created_at' => Carbon::parse($member->created_at)->format('d-m-Y')
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $members,
                'total' => $members->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Get patient appointments for AJAX request
     */
    public function getPatientAppointments(Request $request)
    {
        try {
            $patientId = $request->patient_id;
            
            $appointments = DoctorPatientAppointment::with(['doctor.user', 'hospital', 'member'])
                ->where('user_id', $patientId)
                ->orderBy('id', 'desc')
                ->get()
                ->map(function($appointment) {
                    // Get doctor name
                    $doctorName = 'N/A';
                    if ($appointment->doctor && $appointment->doctor->user) {
                        $doctorName = $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name;
                    }
                    
                    // Get hospital name
                    $hospitalName = $appointment->hospital->name_en ?? 'N/A';
                    
                    // Get patient name (for member case)
                    $patientName = $appointment->user->first_name . ' ' . $appointment->user->last_name;
                    if ($appointment->member) {
                        $patientName = $appointment->member->full_name . ' (Member)';
                    }
                    
                    // Get status class
                    $statusClass = 'pending';
                    switch ($appointment->booking_status) {
                        case BOOKING_STATUS_CONFIRMED:
                            $statusClass = 'confirmed';
                            break;
                        case BOOKING_STATUS_COMPLETED:
                            $statusClass = 'completed';
                            break;
                        case BOOKING_STATUS_CANCELLED:
                            $statusClass = 'cancelled';
                            break;
                        case BOOKING_STATUS_RESCHEDULED:
                            $statusClass = 'rescheduled';
                            break;
                        default:
                            $statusClass = 'pending';
                    }
                    
                    return [
                        'id' => $appointment->id,
                        'booking_id' => $appointment->booking_id,
                        'doctor_name' => $doctorName,
                        'hospital_name' => $hospitalName,
                        'patient_name' => $patientName,
                        'booking_date' => Carbon::parse($appointment->booking_date)->format('d-m-Y'),
                        'booking_time_slot' => $appointment->booking_time_slot,
                        'booking_status' => ucfirst($appointment->booking_status),
                        'status_class' => $statusClass,
                        'booking_type' => $appointment->booking_type,
                        'view_url' => route('admin.appointments.view', ['id' => $appointment->id])
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $appointments,
                'recordsTotal' => $appointments->count(),
                'recordsFiltered' => $appointments->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}