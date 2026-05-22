

@extends('front.template.layout')

@section('title', 'Payment Successful - MedNero')

@section('content')
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 500px;">
            
            <img src="{{ URL::asset('web/images/success-img-icon.svg') }}" style="width: 400px;" alt="" class="icn my-4">
            
            <div class="checkout-form-title mt-30">
                <h3>Payment Successful!</h3>
            </div>
            
            <p>Your appointment has been confirmed.</p>
            
            <div class="appointment-info bg-light p-4 rounded my-4">
                <h5>Booking Details</h5>
                <p><strong>Booking No:</strong> <span class="text-primary">{{ $appointment->booking_id }}</span></p>
                <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->user->name ?? '' }}</p>
                <p><strong>Date:</strong> {{ date('d-m-Y', strtotime($appointment->booking_date)) }}</p>
                <p><strong>Time:</strong> {{ $appointment->booking_time_slot }}</p>
                <p><strong>Amount Paid:</strong> <span class="text-primary">{{ $appointment->formatted_consultation_fee }}</span></p>
            </div>
            
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ url('/') }}" class="primary-btn1 btn-outline">Back To Home</a>
                <a href="{{ route('doctor_list') }}" class="primary-btn1">Find More Doctors</a>
            </div>
        </div>
    </div>
</div>
@endsection