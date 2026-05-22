<?php
// app/Http/Controllers/front/AppointmentPaymentController.php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorAppointmentsStatus;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentPaymentController extends Controller
{
    public function showPaymentPage($token)
    {
        $appointment = DoctorPatientAppointment::where('payment_token', $token)
            ->with(['doctor.user', 'user'])
            ->firstOrFail();
        
        // Check if already paid
        if ($appointment->payment_status == 'paid') {
            return redirect()->route('front.appointment-payment.success', ['token' => $token])
                ->with('message', 'This appointment has already been paid for.');
        }
        
        $page_heading = "Complete Payment";
        return view('front.appointment-payment', compact('appointment', 'page_heading', 'token'));
    }
    
    public function processPayment(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:doctor_patient_appointments,payment_token'
        ]);
        
        $appointment = DoctorPatientAppointment::where('payment_token', $request->token)->firstOrFail();
        
        if ($appointment->payment_status == 'paid') {
            return redirect()->route('front.appointment-payment.success', ['token' => $request->token]);
        }
        
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $lineItems = [[
            'price_data' => [
                'currency' => 'aed',
                'product_data' => [
                    'name' => 'Doctor Consultation - ' . $appointment->booking_id,
                    'description' => 'Appointment with Dr. ' . ($appointment->doctor->user->name ?? 'Doctor') . ' on ' . date('d-m-Y', strtotime($appointment->booking_date)) . ' at ' . $appointment->booking_time_slot,
                ],
                'unit_amount' => round($appointment->consultation_fee * 100),
            ],
            'quantity' => 1,
        ]];
        
        try {
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('front.appointment-payment.success', ['token' => $appointment->payment_token]) . '?checkoutsession_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('front.appointment-payment.cancel', ['token' => $appointment->payment_token]),
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'booking_id' => $appointment->booking_id,
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->user_id
                ]
            ]);
            
            $appointment->stripe_session_id = $checkoutSession->id;
            $appointment->save();
            
            return redirect()->away($checkoutSession->url);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
    
    public function paymentSuccess(Request $request)
    {
        $appointment = DoctorPatientAppointment::where('payment_token', $request->token)->firstOrFail();
        
        Stripe::setApiKey(env('STRIPE_SECRET'));
       
        
        
        try {
            $session = StripeSession::retrieve($request->checkoutsession_id);
            
            
            if ($session->payment_status === 'paid') {
                // Update appointment
                $appointment->payment_status = 'paid';
                $appointment->payment_completed_at = now();
                $appointment->booking_status = BOOKING_STATUS_CONFIRMED;
                $appointment->save();
                
                // Add status history
                DoctorAppointmentsStatus::create([
                    'appointment_id' => $appointment->id,
                    'status' => 'Payment completed - Appointment Confirmed',
                    'changed_by' => $appointment->user_id,
                    'changed_at' => now()
                ]);
                
                // Send confirmation notification
                exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");
                
                return view('front.appointment-payment-success', compact('appointment'));
            }
            
            return redirect()->route('front.appointment-payment.cancel', ['token' => $request->token]);
            
        } catch (\Exception $e) {
            return redirect()->route('front.appointment-payment.cancel', ['token' => $request->token]);
        }
    }
    
    public function paymentCancel($token)
    {
        $appointment = DoctorPatientAppointment::where('payment_token', $token)->first();
        return view('front.appointment-payment-cancel', compact('appointment'));
    }
}