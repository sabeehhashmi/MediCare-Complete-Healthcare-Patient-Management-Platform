<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorPatientAppointment;
use App\Models\Doctor;
use App\Models\Members; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;
use App\Models\WithdrawalRequest;

class EarningsController extends Controller
{
    public function index(Request $request)
    {
        if (!get_user_permission('earnings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Commission Management";
        $module_heading = "Earnings & Commission";
        
        $doctors = Doctor::with('user')
            ->whereHas('user', function($q) {
                $q->where('active', 1);
            })
            ->orderBy('id', 'desc')
            ->get();
        
        $summary = $this->getSummaryData();
        
        $pendingCount = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->where(function($q) {
                $q->where('commission_status', 'pending')
                  ->orWhereNull('commission_status');
            })
            ->count();
        
        return view('admin.earnings.index', compact('page_heading', 'module_heading', 'doctors', 'summary', 'pendingCount'));
    }

    public function withdrawals(Request $request)
    {
        if (!get_user_permission('earnings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $page_heading = "Withdrawal Requests";
        $module_heading = "Doctor Withdrawals";
        
        $doctors = Doctor::with('user')->whereHas('user', function($q) {
            $q->where('active', 1);
        })->orderBy('id', 'desc')->get();
        
        $pendingTotal = WithdrawalRequest::where('status', 'pending')->sum('amount');
        $approvedTotal = WithdrawalRequest::where('status', 'approved')->sum('amount');
        $paidTotal = WithdrawalRequest::where('status', 'paid')->sum('amount');
        $rejectedTotal = WithdrawalRequest::where('status', 'rejected')->sum('amount');
        
        return view('admin.earnings.withdrawals', compact(
            'page_heading', 'module_heading', 'doctors',
            'pendingTotal', 'approvedTotal', 'paidTotal', 'rejectedTotal'
        ));
    }

    public function loadWithdrawals(Request $request)
    {
        $query = WithdrawalRequest::with(['doctor.user']);
        
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $query->whereDate('created_at', '>=', $from_date);
        }
        
        if ($request->to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $query->whereDate('created_at', '<=', $to_date);
        }
        
        return DataTables::eloquent($query)
            ->addColumn('sl_no', function($item) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('doctor_name', function($item) {
                return $item->doctor && $item->doctor->user ? 'Dr. ' . $item->doctor->user->name : 'N/A';
            })
            ->addColumn('amount_formatted', function($item) {
                return 'AED ' . number_format($item->amount, 2);
            })
            ->addColumn('payment_method', function($item) {
                return ucfirst($item->payment_method ?? 'Bank Transfer');
            })
            ->addColumn('request_date', function($item) {
                if ($item->created_at) {
                    return web_date_in_timezone($item->created_at, 'd M Y h:i A');
                }
                return 'N/A';
            })
            ->addColumn('status_badge', function($item) {
                $badgeClass = match($item->status) {
                    'approved' => 'info',
                    'paid' => 'success',
                    'rejected' => 'danger',
                    'cancelled' => 'secondary',
                    default => 'warning'
                };
                return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($item->status) . '</span>';
            })
            ->addColumn('action', function($item) {
                $actions = '<div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';
                
                if ($item->status == 'pending' && get_user_permission('earnings', 'u')) {
                    $actions .= '<a class="dropdown-item approve-withdrawal" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-check-circle"></i> Approve
                                </a>';
                    $actions .= '<a class="dropdown-item reject-withdrawal" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-x-circle"></i> Reject
                                </a>';
                }
                
                if ($item->status == 'approved' && get_user_permission('earnings', 'u')) {
                    $actions .= '<a class="dropdown-item mark-paid-withdrawal" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-money"></i> Mark as Paid
                                </a>';
                }
                
                $actions .= '<a class="dropdown-item view-withdrawal-details" href="javascript:void(0)" data-id="' . $item->id . '">
                                <i class="bx bx-show"></i> View Details
                            </a>';
                $actions .= '</div></div>';
                
                return $actions;
            })
            ->rawColumns(['status_badge', 'action'])
            ->toJson();
    }

    public function approveWithdrawal(Request $request)
    {
        try {
            $withdrawal = WithdrawalRequest::findOrFail($request->id);
            $withdrawal->status = 'approved';
            $withdrawal->approved_by = Auth::id();
            $withdrawal->approved_at = now();
            $withdrawal->save();
            
            return response()->json(['status' => 1, 'message' => 'Withdrawal approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function markWithdrawalPaid(Request $request)
    {
        try {
            $withdrawal = WithdrawalRequest::findOrFail($request->id);
            $withdrawal->status = 'paid';
            $withdrawal->paid_at = now();
            $withdrawal->transaction_id = $request->transaction_id;
            $withdrawal->admin_notes = $request->notes;
            $withdrawal->save();
            
            return response()->json(['status' => 1, 'message' => 'Withdrawal marked as paid']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function rejectWithdrawal(Request $request)
    {
        try {
            $withdrawal = WithdrawalRequest::findOrFail($request->id);
            $withdrawal->status = 'rejected';
            $withdrawal->admin_notes = $request->notes;
            $withdrawal->save();
            
            return response()->json(['status' => 1, 'message' => 'Withdrawal rejected']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function getWithdrawalDetails($id)
    {
        try {
            $withdrawal = WithdrawalRequest::with(['doctor.user'])->findOrFail($id);
            
            $data = [
                'id' => $withdrawal->id,
                'doctor_name' => $withdrawal->doctor && $withdrawal->doctor->user ? 'Dr. ' . $withdrawal->doctor->user->name : 'N/A',
                'doctor_email' => $withdrawal->doctor && $withdrawal->doctor->user ? $withdrawal->doctor->user->email : 'N/A',
                'doctor_phone' => $withdrawal->doctor && $withdrawal->doctor->user ? $withdrawal->doctor->user->mobile_number : 'N/A',
                'amount' => number_format($withdrawal->amount, 2),
                'payment_method' => ucfirst($withdrawal->payment_method ?? 'Bank Transfer'),
                'bank_name' => $withdrawal->doctor->bank_name ?? 'N/A',
                'account_number' => $withdrawal->doctor->account_number ?? 'N/A',
                'iban' => $withdrawal->doctor->iban ?? 'N/A',
                'account_holder_name' => $withdrawal->doctor->account_holder_name ?? 'N/A',
                'request_date' => $withdrawal->created_at ? web_date_in_timezone($withdrawal->created_at, 'd M Y h:i A') : 'N/A',
                'status' => ucfirst($withdrawal->status),
                'approved_by' => $withdrawal->approved_by ? \App\Models\User::find($withdrawal->approved_by)?->name : 'N/A',
                'approved_at' => $withdrawal->approved_at ? web_date_in_timezone($withdrawal->approved_at, 'd M Y h:i A') : 'N/A',
                'transaction_id' => $withdrawal->transaction_id ?? 'N/A',
                'paid_at' => $withdrawal->paid_at ? web_date_in_timezone($withdrawal->paid_at, 'd M Y h:i A') : 'N/A',
                'admin_notes' => $withdrawal->admin_notes ?? 'N/A'
            ];
            
            return response()->json(['status' => 1, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Withdrawal not found']);
        }
    }
    
    private function getSummaryData()
    {
        $paidAppointments = DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->get();
        
        $totalConsultationFee = $paidAppointments->sum('consultation_fee');
        $totalAdminCommission = $paidAppointments->sum('admin_commission');
        $totalDoctorEarning = $paidAppointments->sum('doctor_earning');
        
        $pendingCommission = $paidAppointments->where('commission_status', 'pending')->sum('admin_commission');
        $approvedCommission = $paidAppointments->where('commission_status', 'approved')->sum('admin_commission');
        $paidCommission = $paidAppointments->where('commission_status', 'paid')->sum('admin_commission');
        
        return [
            'total_consultation_fee' => $totalConsultationFee,
            'total_admin_commission' => $totalAdminCommission,
            'total_doctor_earning' => $totalDoctorEarning,
            'pending_commission' => $pendingCommission,
            'approved_commission' => $approvedCommission,
            'paid_commission' => $paidCommission,
        ];
    }
    
    public function loadData(Request $request)
    {
        $query = DoctorPatientAppointment::with(['doctor.user', 'user'])
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->orderBy('id', 'desc');
        
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->commission_status) {
            $query->where('commission_status', $request->commission_status);
        }
        
        if ($request->from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '>=', $from_date);
        }
        
        if ($request->to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '<=', $to_date);
        }
        
        return DataTables::eloquent($query)
            ->addColumn('sl_no', function($item) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('booking_id', function($item) {
                return '<a href="' . route('admin.appointments.view', $item->id) . '" class="text-primary fw-bold">' . e($item->booking_id) . '</a>';
            })
            ->addColumn('doctor_name', function($item) {
                if ($item->doctor && $item->doctor->user) {
                    return 'Dr. ' . e($item->doctor->user->first_name . ' ' . $item->doctor->user->last_name);
                }
                return 'N/A';
            })
            ->addColumn('consultation_fee', function($item) {
                return 'AED ' . number_format($item->consultation_fee, 2);
            })
            // ->addColumn('admin_commission', function($item) {
            //     return 'AED ' . number_format($item->admin_commission, 2) . ' (' . ($item->admin_commission_percentage ?? 10) . '%)';
            // })
              ->addColumn('admin_commission', function($item) {
                
                $commission = $item->consultation_fee - ($item->doctor_earning ?? 0);
                
                // Calculate percentage
                $percentage = ($item->consultation_fee > 0) ? ($commission / $item->consultation_fee) * 100 : 0;
                
                return 'AED ' . number_format($commission, 2) . ' (' . round($percentage, 2) . '%)';
            })
            ->addColumn('doctor_earning', function($item) {
                return 'AED ' . number_format($item->doctor_earning, 2);
            })
            ->addColumn('payment_date', function($item) {
                if ($item->payment_completed_at) {
                    return web_date_in_timezone($item->payment_completed_at, 'd M Y h:i A');
                }
                return 'N/A';
            })
            ->addColumn('commission_status_badge', function($item) {
                $status = $item->commission_status ?? 'pending';
                $badgeClass = match($status) {
                    'approved' => 'info',
                    'paid' => 'success',
                    'rejected' => 'danger',
                    default => 'warning'
                };
                return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function($item) {
                $commissionStatus = $item->commission_status ?? 'pending';
                $actions = '<div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';
                
                if ($commissionStatus == 'pending' && get_user_permission('earnings', 'u')) {
                    $actions .= '<a class="dropdown-item approve-commission" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-check-circle"></i> Approve Commission
                                </a>';
                }
                
                if ($commissionStatus == 'approved' && get_user_permission('earnings', 'u')) {
                    $actions .= '<a class="dropdown-item mark-paid" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-money"></i> Mark as Paid
                                </a>';
                }
                
                if ($commissionStatus == 'pending' && get_user_permission('earnings', 'd')) {
                    $actions .= '<a class="dropdown-item reject-commission" href="javascript:void(0)" data-id="' . $item->id . '">
                                    <i class="bx bx-x-circle"></i> Reject Commission
                                </a>';
                }
                
                $actions .= '<a class="dropdown-item view-details" href="javascript:void(0)" data-id="' . $item->id . '">
                                <i class="bx bx-show"></i> View Details
                            </a>';
                $actions .= '</div></div>';
                
                return $actions;
            })
            ->rawColumns(['booking_id', 'commission_status_badge', 'action'])
            ->toJson();
    }
    
    public function approve(Request $request)
    {
        try {
            $appointment = DoctorPatientAppointment::findOrFail($request->id);
            
            if ($appointment->payment_status != 'paid') {
                return response()->json(['status' => 0, 'message' => 'Cannot approve: Payment not completed']);
            }
            
            if ($appointment->booking_status != BOOKING_STATUS_COMPLETED) {
                return response()->json(['status' => 0, 'message' => 'Cannot approve: Appointment not completed']);
            }
            
            $appointment->commission_status = 'approved';
            $appointment->commission_approved_by = Auth::id();
            $appointment->commission_approved_at = now();
            $appointment->save();
            
            return response()->json(['status' => 1, 'message' => 'Commission approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    
    public function markPaid(Request $request)
    {
        try {
            $appointment = DoctorPatientAppointment::findOrFail($request->id);
            
            $appointment->commission_status = 'paid';
            $appointment->commission_payment_date = $request->payment_date ? Carbon::createFromFormat('d-m-Y', $request->payment_date) : now();
            $appointment->commission_transaction_id = $request->transaction_id;
            $appointment->commission_notes = $request->notes;
            $appointment->save();
            
            return response()->json(['status' => 1, 'message' => 'Commission marked as paid']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    
    public function reject(Request $request)
    {
        try {
            $appointment = DoctorPatientAppointment::findOrFail($request->id);
            $appointment->commission_status = 'rejected';
            $appointment->commission_notes = $request->notes;
            $appointment->save();
            
            return response()->json(['status' => 1, 'message' => 'Commission rejected']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    
    public function getDetails($id)
    {
        try {
            $appointment = DoctorPatientAppointment::with(['doctor.user', 'user'])->findOrFail($id);
            
            $data = [
                'id' => $appointment->id,
                'booking_id' => $appointment->booking_id,
                'doctor_name' => $appointment->doctor && $appointment->doctor->user ? 'Dr. ' . $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name : 'N/A',
                'patient_name' => $appointment->user ? $appointment->user->first_name . ' ' . $appointment->user->last_name : 'N/A',
                'booking_date' => $appointment->booking_date ? web_date_in_timezone($appointment->booking_date, 'd M Y') : 'N/A',
                'consultation_fee' => number_format($appointment->consultation_fee, 2),
                'admin_commission' => number_format($appointment->admin_commission, 2),
                'admin_commission_percentage' => $appointment->admin_commission_percentage ?? 10,
                'doctor_earning' => number_format($appointment->doctor_earning, 2),
                'commission_status' => ucfirst($appointment->commission_status ?? 'pending'),
                'payment_completed_at' => $appointment->payment_completed_at ? web_date_in_timezone($appointment->payment_completed_at, 'd M Y h:i A') : 'N/A',
                'commission_approved_at' => $appointment->commission_approved_at ? web_date_in_timezone($appointment->commission_approved_at, 'd M Y h:i A') : 'N/A',
                'commission_payment_date' => $appointment->commission_payment_date ? web_date_in_timezone($appointment->commission_payment_date, 'd M Y') : 'N/A',
                'commission_transaction_id' => $appointment->commission_transaction_id ?? 'N/A',
                'commission_notes' => $appointment->commission_notes ?? 'N/A'
            ];
            
            return response()->json(['status' => 1, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Appointment not found']);
        }
    }
    
    public function export(Request $request)
    {
        $query = DoctorPatientAppointment::with(['doctor.user', 'user'])
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid');

        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->commission_status) {
            $query->where('commission_status', $request->commission_status);
        }

        if ($request->from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '>=', $from_date);
        }

        if ($request->to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '<=', $to_date);
        }

        $appointments = $query->get();

        $filename = 'earnings_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($appointments) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'Booking ID',
                'Doctor Name',
                'Patient Name',
                'Booking Date',
                'Consultation Fee (AED)',
                'Admin Commission (AED)',
                'Doctor Earning (AED)',
                'Payment Date',
                'Commission Status'
            ]);

            foreach ($appointments as $appointment) {
                fputcsv($handle, [
                    $appointment->booking_id,
                    $appointment->doctor && $appointment->doctor->user
                        ? 'Dr. ' . $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name
                        : 'N/A',
                    $appointment->user
                        ? $appointment->user->first_name . ' ' . $appointment->user->last_name
                        : 'N/A',
                    $appointment->booking_date ? web_date_in_timezone($appointment->booking_date, 'd M Y') : 'N/A',
                    number_format($appointment->consultation_fee, 2),
                    number_format($appointment->admin_commission, 2),
                    number_format($appointment->doctor_earning, 2),
                    $appointment->payment_completed_at ? web_date_in_timezone($appointment->payment_completed_at, 'd M Y h:i A') : 'N/A',
                    ucfirst($appointment->commission_status ?? 'pending')
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    public function exportWithdrawals(Request $request)
    {
        $query = WithdrawalRequest::with(['doctor.user']);
        
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $query->whereDate('created_at', '>=', $from_date);
        }
        
        if ($request->to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $query->whereDate('created_at', '<=', $to_date);
        }
        
        $withdrawals = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'withdrawals_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        return response()->stream(function () use ($withdrawals) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'ID',
                'Doctor Name',
                'Doctor Email',
                'Amount (AED)',
                'Payment Method',
                'Bank Name',
                'Account Number',
                'IBAN',
                'Account Holder Name',
                'Request Date',
                'Status',
                'Approved By',
                'Approved Date',
                'Transaction ID',
                'Paid Date',
                'Admin Notes'
            ]);
            
            foreach ($withdrawals as $withdrawal) {
                $bankName = '';
                $accountNumber = '';
                $iban = '';
                $accountHolderName = '';
                
                if ($withdrawal->doctor) {
                    $bankName = $withdrawal->doctor->bank_name ?? '';
                    $accountNumber = $withdrawal->doctor->account_number ?? '';
                    $iban = $withdrawal->doctor->iban ?? '';
                    $accountHolderName = $withdrawal->doctor->account_holder_name ?? '';
                }
                
                fputcsv($handle, [
                    $withdrawal->id,
                    $withdrawal->doctor && $withdrawal->doctor->user ? 'Dr. ' . $withdrawal->doctor->user->name : 'N/A',
                    $withdrawal->doctor && $withdrawal->doctor->user ? $withdrawal->doctor->user->email : 'N/A',
                    number_format($withdrawal->amount, 2),
                    ucfirst($withdrawal->payment_method ?? 'bank_transfer'),
                    $bankName,
                    $accountNumber,
                    $iban,
                    $accountHolderName,
                    $withdrawal->created_at ? web_date_in_timezone($withdrawal->created_at, 'd M Y h:i A') : 'N/A',
                    ucfirst($withdrawal->status),
                    $withdrawal->approved_by ? \App\Models\User::find($withdrawal->approved_by)?->name ?? 'N/A' : 'N/A',
                    $withdrawal->approved_at ? web_date_in_timezone($withdrawal->approved_at, 'd M Y h:i A') : 'N/A',
                    $withdrawal->transaction_id ?? 'N/A',
                    $withdrawal->paid_at ? web_date_in_timezone($withdrawal->paid_at, 'd M Y h:i A') : 'N/A',
                    $withdrawal->admin_notes ?? 'N/A'
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}