@extends('front.template.layout')

@section('title', 'My Reports - MedNero')

@section('content')
<div class="checkout-page pt-100 mb-100">
        <div class="container">
            
            <div class="text-center mx-auto" style="max-width: 400px;">
                
                <img src="{{ URL::asset('web/images/success-img-icon.svg') }}" style="width: 400px;" alt="" class="icn my-4">
            <div class="checkout-form-title mt-30">
                <h3>Thank You!</h3>
            </div>
            <p>Your Booking Has Been Received.</p>

            <p>Mednero team will contact you soon</p>

            <h5>Booking No : <span class="text-primary">{{ $doctor->booking_id }}</span> </h5>
        
            <div style="display: flex; align-items:center; justify-content: center; gap: 20px">
                <a href="{{url('/')}}" class="w-50 primary-btn1 btn-outline justify-content-center">Back To Home</a>
             <a href="{{route('doctor_list')}}" class="primary-btn1 justify-content-center w-50">Search Doctor</a>
            </div>

            </div>

        </div>
    </div>
@endsection
