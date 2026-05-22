@extends('front.template.layout')

@section('title', 'Complete Payment - MedNero')

@section('content')
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Complete Your Payment</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                        <div class="appointment-details mb-4">
                            <h5>Appointment Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Booking ID</th>
                                    <td>{{ $appointment->booking_id }}</td>
                                </tr>
                                <tr>
                                    <th>Doctor</th>
                                    <td>Dr. {{ $appointment->doctor->user->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ date('d-m-Y', strtotime($appointment->booking_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{ $appointment->booking_time_slot }}</td>
                                </tr>
                                <tr>
                                    <th>Consultation Fee</th>
                                    <td><strong class="text-primary">{{ $appointment->formatted_consultation_fee }}</strong></td>
                                </tr>
                                @if($appointment->is_urgent)
                                <tr>
                                    <th>Priority</th>
                                    <td><span class="badge bg-danger">URGENT</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <form action="{{ route('front.appointment-payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Pay {{ $appointment->formatted_consultation_fee }}
                            </button>
                        </form>
                        
                        <p class="text-muted text-center mt-3 small">
                            Secure payment powered by Stripe
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection