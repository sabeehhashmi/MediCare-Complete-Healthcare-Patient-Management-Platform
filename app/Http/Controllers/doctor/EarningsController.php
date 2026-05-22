<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorPatientAppointment;
use App\Models\Doctor;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;

class EarningsController extends Controller
{
    public function index(Request $request)
    {
        $page_heading = "My Earnings";
        $module_heading = "Earnings & Withdrawals";
        
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found');
        }
        
        // Get or create wallet
        $wallet = DoctorWallet::firstOrCreate(
            ['doctor_id' => $doctor->id],
            [
                'total_earned' => 0,
                'total_withdrawn' => 0,
                'current_balance' => 0,
                'pending_balance' => 0,
                'last_updated_at' => now()
            ]
        );
        
        // Sync wallet with actual data
        $this->syncWalletWithAppointments($doctor->id, $wallet);
        
        // Get pending withdrawal requests
        $pendingWithdrawals = WithdrawalRequest::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->get();
        
        // Get withdrawal history
        $withdrawalHistory = WithdrawalRequest::where('doctor_id', $doctor->id)
            ->whereIn('status', ['approved', 'paid', 'rejected', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get commission transactions
        $commissionTransactions = DoctorPatientAppointment::where('doctor_id', $doctor->id)
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->where('commission_status', 'paid')
            ->orderBy('payment_completed_at', 'desc')
            ->limit(10)
            ->get();
        
        // Monthly earnings for chart
        $monthlyEarnings = $this->getMonthlyEarnings($doctor->id);
        
        return view('doctor.earnings.index', compact(
            'page_heading',
            'module_heading',
            'wallet',
            'pendingWithdrawals',
            'withdrawalHistory',
            'commissionTransactions',
            'monthlyEarnings'
        ));
    }
    
    private function syncWalletWithAppointments($doctorId, $wallet)
    {
        $actualEarned = DoctorPatientAppointment::where('doctor_id', $doctorId)
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->where('commission_status', 'paid')
            ->sum('doctor_earning');
    
        $actualWithdrawn = WithdrawalRequest::where('doctor_id', $doctorId)
            ->where('status', 'paid')
            ->sum('amount');
    
        $pendingWithdrawn = WithdrawalRequest::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
    
        $pendingCommission = DoctorPatientAppointment::where('doctor_id', $doctorId)
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid')
            ->where('commission_status', 'approved')
            ->sum('doctor_earning');
    
        $actualBalance = $actualEarned - ($actualWithdrawn + $pendingWithdrawn);
    
        $wallet->total_earned = $actualEarned;
        $wallet->total_withdrawn = $actualWithdrawn;
        $wallet->current_balance = $actualBalance;
        $wallet->pending_balance = $pendingCommission;
        $wallet->last_updated_at = now();
        $wallet->save();
    
        return $wallet;
    }
    
    private function getMonthlyEarnings($doctorId)
    {
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyTotal = DoctorPatientAppointment::where('doctor_id', $doctorId)
                ->where('booking_status', BOOKING_STATUS_COMPLETED)
                ->where('payment_status', 'paid')
                ->where('commission_status', 'paid')
                ->whereYear('payment_completed_at', $month->year)
                ->whereMonth('payment_completed_at', $month->month)
                ->sum('doctor_earning');
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'amount' => $monthlyTotal
            ];
        }
        return $monthlyData;
    }
    
    public function loadData(Request $request)
    {
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        $query = DoctorPatientAppointment::with(['user', 'member'])
            ->where('doctor_id', $doctor->id)
            ->where('booking_status', BOOKING_STATUS_COMPLETED)
            ->where('payment_status', 'paid');
        
        if ($request->from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '>=', $from_date);
        }
        
        if ($request->to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $query->whereDate('payment_completed_at', '<=', $to_date);
        }
        
        if ($request->commission_status) {
            $query->where('commission_status', $request->commission_status);
        }
        
        return DataTables::eloquent($query)
            ->addColumn('sl_no', function($item) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('patient_name', function($item) {
                if ($item->member_id) {
                    $member = \App\Models\Members::find($item->member_id);
                    return $member ? $member->full_name : 'N/A';
                }
                return $item->user ? ($item->user->first_name . ' ' . $item->user->last_name) : 'N/A';
            })
            ->addColumn('booking_date', function($item) {
                return $item->booking_date ? web_date_in_timezone($item->booking_date, 'd M Y') : 'N/A';
            })
            ->addColumn('doctor_earning', function($item) {
                return 'AED ' . number_format($item->doctor_earning, 2);
            })
            ->addColumn('payment_date', function($item) {
                return $item->payment_completed_at ? web_date_in_timezone($item->payment_completed_at, 'd M Y') : 'N/A';
            })
            ->addColumn('commission_status', function($item) {
                $status = $item->commission_status ?? 'pending';
                $badgeClass = match($status) {
                    'approved' => 'info',
                    'paid' => 'success',
                    'rejected' => 'danger',
                    default => 'warning'
                };
                return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->rawColumns(['commission_status'])
            ->toJson();
    }
    
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'payment_method' => 'required|string',
            'account_details' => 'required|string',
        ]);
        
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        if (!$doctor) {
            return response()->json(['status' => 0, 'message' => 'Doctor profile not found']);
        }
        
        $wallet = DoctorWallet::where('doctor_id', $doctor->id)->first();
        $availableBalance = $wallet ? $wallet->current_balance : 0;
        
        if ($request->amount > $availableBalance) {
            return response()->json([
                'status' => 0, 
                'message' => 'Insufficient balance. Available: AED ' . number_format($availableBalance, 2)
            ]);
        }
        
        $pendingRequest = WithdrawalRequest::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->exists();
        
        if ($pendingRequest) {
            return response()->json([
                'status' => 0, 
                'message' => 'You already have a pending withdrawal request. Please wait for it to be processed.'
            ]);
        }
        
        DB::beginTransaction();
        try {
            $withdrawal = WithdrawalRequest::create([
                'doctor_id' => $doctor->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'account_details' => $request->account_details,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);
            
            WalletTransaction::create([
                'doctor_id' => $doctor->id,
                'withdrawal_id' => $withdrawal->id,
                'type' => 'debit',
                'amount' => $request->amount,
                'status' => 'pending',
                'description' => 'Withdrawal request submitted',
                'transaction_date' => now(),
                'created_by' => $loginuserid
            ]);
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'Withdrawal request submitted successfully. It will be processed within 3-5 business days.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    
    public function withdrawalHistory(Request $request)
    {
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        $withdrawals = WithdrawalRequest::where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $page_heading = "Withdrawal History";
        $module_heading = "Withdrawal History";
        
        return view('doctor.earnings.withdrawal_history', compact('withdrawals', 'page_heading', 'module_heading'));
    }
    
    public function cancelWithdrawal(Request $request)
    {
        $request->validate([
            'withdrawal_id' => 'required|exists:withdrawal_requests,id'
        ]);
        
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        $withdrawal = WithdrawalRequest::where('id', $request->withdrawal_id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$withdrawal) {
            return response()->json(['status' => 0, 'message' => 'Withdrawal request not found or cannot be cancelled']);
        }
        
        DB::beginTransaction();
        try {
            $withdrawal->status = 'cancelled';
            $withdrawal->save();
            
            WalletTransaction::where('withdrawal_id', $withdrawal->id)
                ->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return response()->json(['status' => 1, 'message' => 'Withdrawal request cancelled successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }
    
    public function getSummary(Request $request)
    {
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id', $loginuserid)->first();
        
        $wallet = DoctorWallet::where('doctor_id', $doctor->id)->first();
        
        return response()->json([
            'status' => 1,
            'data' => [
                'total_earned' => $wallet ? $wallet->total_earned : 0,
                'total_withdrawn' => $wallet ? $wallet->total_withdrawn : 0,
                'current_balance' => $wallet ? $wallet->current_balance : 0,
                'pending_balance' => $wallet ? $wallet->pending_balance : 0
            ]
        ]);
    }
}