

@extends('front.template.layout')

@section('title', 'Payment Cancelled - MedNero')

@section('content')
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 500px;">
            
            <img src="{{ URL::asset('web/images/cancel-icon.svg') }}" style="width: 200px;" alt="" class="icn my-4">
            
            <div class="checkout-form-title mt-30">
                <h3>Payment Cancelled</h3>
            </div>
            
            <p>Your payment was cancelled. You can try again.</p>
            
            @if($appointment)
            <div class="mt-4">
                <a href="{{ route('front.appointment-payment', ['token' => $appointment->payment_token]) }}" 
                   class="primary-btn1">
                    Try Again
                </a>
            </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Back To Home</a>
            </div>
        </div>
    </div>
</div>
@endsection