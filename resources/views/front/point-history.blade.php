@extends('front.template.layout')

@section('title', 'My Bookings')

@section('content')
<style>
    .booking-tabs {
    display: flex;
    gap: 10px;
}

.tab-btn {
    padding: 10px 20px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
    border-radius: 5px;
}

.tab-btn.active {
    background: #000;
    color: #fff;
}
</style>


<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">

 <div class="checkout-page user-account-page pt-100 mb-100">
        <div class="container">
            <div class="row g-lg-4 gy-5">
                
                <div class="col-lg-4">
                    @include('front.layouts.user-sidebar')
                </div>

                <div class="col-lg-8">
                    
                    <div class="checkout-form-wrapper">
                        <div class="checkout-form-title">
                            <h4>Loyalty & Rewards</h4>
                        </div>

                       <form method="GET" action="{{ url()->current() }}" id="search-form">
    <div class="row align-items-end mt-3 mb-3">
        
        <div class="col-lg-4 col-md-4 mb-2">
            <label class="form-label">From</label>
            <div class="position-relative input-custom-icon">
                <input type="text"
                       name="booking_from"
                       class="form-control flatpicker-input1"
                       id="from_date"
                       placeholder="From"
                       value="{{ request('booking_from') }}" />
                <span class="bx bx-calendar-event position-absolute top-50  translate-middle" style="right: 8px;"></span>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 mb-2">
            <label class="form-label">To</label>
            <div class="position-relative input-custom-icon">
                <input type="text"
                       name="booking_to"
                       class="form-control flatpicker-input1"
                       id="to_date"
                       placeholder="To"
                       value="{{ request('booking_to') }}" />
                <span class="bx bx-calendar-event position-absolute top-50  translate-middle" style="right: 8px;"></span>
            </div>
        </div>

         <div class="col-lg-4 col-md-4 mb-2">
            <label class="form-label">Booking ID</label>
            <div class="position-relative input-custom-icon">
                <input type="text"
                       name="booking_id"
                       class="form-control"
                       id="booking_id"
                       placeholder="Booking ID"
                       value="{{ request('booking_id') }}" />
                
            </div>
        </div>

        <div class="col-sm mb-2">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Search</button>

                <a href="{{ url()->current() }}" class="btn btn-dark">
                    Refresh
                </a>
               <button type="button" class="btn btn-primary" id="exportExcel">
    Export Excel
</button>

<button type="button" class="btn btn-primary" id="exportPdf">
    Export Pdf
</button>
            </div>
        </div>

    </div>
</form>
                        
                        
                 <div id="calendarViewfull" ><div class="mb-30" id="calenderViewDetail" ></div></div>       
    

                        
                        <!-- Faq Section Start-->
                        <div class="home1-faq-section mb-100" id="listView">
                            <div class="row ">
                            <div class="col-xl-4">
                                <h5>Total earned points :    {{ Auth::user()->points + Auth::user()->used_points }}</h5>
                              
                              </div> 
                              <div class="col-xl-4">
                                <h5> 
                                    Available Points :    {{ Auth::user()->points }}
                                    </h5>
                                </div>
                              <div class="col-xl-4">
                                <h5>
                                    Redeem Points :    {{ Auth::user()->used_points }}
                                    </h5>
                              </div>
                              </div>
                                <div class="row justify-content-center">
                            
                                    
                                    <div class="col-xl-12">  
                              <div class="faq-wrap">
                                            @if($points->count())
                                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                                    @foreach($points as $key=>$point)
                                                    @php $appointment = $point->appointment;// dd($appointment->hospital->name_en); 
                                                    @endphp
                                                    @if($appointment)
                                                        <div class="accordion-item wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
                                                            <h5 class="accordion-header" id="flush-heading{{ $key }}">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $key }}"
                                                                    aria-expanded="false" aria-controls="flush-collapse{{ $key }}">{{ $appointment->booking_id }} - {{ $point->points  }}  ( {{ $point->type  }} )
                                                                    <span class="badge text-bg-primary ms-2">{{ $appointment->booking_status }}</span>
                                                                    
                                                                    <div class="avil-type position-static ms-2">
                                                                        <span class="item-type inperson">
                                                                            <i class='bx bxs-buildings' ></i>
                                                                        </span>
                                                                    </div>
                                                                    
                                                                </button>
                                                            </h5>
                                                            <div id="flush-collapse{{ $key }}" class="accordion-collapse collapse"
                                                                aria-labelledby="flush-heading{{ $key }}" data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Booking No: <strong>{{ $appointment->booking_id }}</strong></span> 
                                                                        <span>Booked Slot: <strong>{{ $appointment->booking_date }} - {{ \Carbon\Carbon::parse($appointment->booking_time_slot)->format('h:i A') }}</strong></span> 
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Doctor Name: <strong>{{ $appointment->doctor->user->name ?? 'N/A' }}</strong></span> 
                                                                        <span>Paid Amount: <strong>AED {{ $appointment->consultation_fee }}</strong></span> 
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Patient Name: <strong>{{ $appointment->patient_member_name ?? $appointment->user->name }}</strong></span> 
                                                                        <span>Clinic: <strong>{{ $appointment->hospital->name_en }}</strong></span> 
                                                                    </div>

                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Booking Type: <strong>{{$appointment->booking_type ?? 'N/A'}}</strong></span> 
                                                                        <!-- <span>Clinic: <strong>Boston Medical Centre</strong></span>  -->
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">

                                                                    @if($appointment->booking_status == 'Pending')
                                                                    <!-- <button type="button" data-bs-toggle="modal" data-bs-target="#cancelModalModal"
                                                                        class="primary-btn1 btn-outline-secondery mt-30">
                                                                        <span>
                                                                            Cancel
                                                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                        <span>
                                                                            Cancel
                                                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                    </button>
                                                                    
                                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#rescheduleModal"
                                                                        class="primary-btn1 btn-outline mt-30">
                                                                        <span>
                                                                            Reschedule
                                                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                        <span>
                                                                            Reschedule
                                                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                    </button> -->
                                                                    @endif
                                                                </div>

                                                                <a href="{{ url('/useraccount-appointment-details/' . $appointment->id) }}" class="primary-btn1 btn-outline btn-outline-dark mt-3 w-100 py-3">
                                                                    <span>
                                                                        View Details
                                                                        <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                    <span>
                                                                        View Details
                                                                        <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <p>You do not have any appointments yet!</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($points->count())
                                    <div class="col-12 py-3">
                                        {{ $points->links() }}
                                    </div>
                                    @endif
                                </div>
                        </div>
                        <!-- Faq Section End-->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
   
    <!-- Enquiry Modal section Start-->
    <div class="modal enquiry-modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                    </svg>
                </button>
                <div class="modal-body">
                    <h4 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment!</h4>
                    <form class="enquiry-form-wrapper">
                        

                            <div class="mb-30" id="calenderView"></div>

                        <div class="time-slots mb-40">
                                <ul>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" checked="checked" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">09:00 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio" disabled>
                                            <span class="checkmark"></span>
                                            <span class="label-text">09:15 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">09:30 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">09:45 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio" disabled>
                                            <span class="checkmark"></span>
                                            <span class="label-text">10:00 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio" disabled>
                                            <span class="checkmark"></span>
                                            <span class="label-text">10:15 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">10:30 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">10:45 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">11:00 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">11:15 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">11:30 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">11:45 AM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">12:00 PM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">12:15 PM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">12:30 PM</span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">12:45 PM</span>
                                        </label>
                                    </li>
                                </ul>

                                <ul class="color-indicaters-wrap">
                                    <li>
                                        <span class="clr-indicate unavailable"></span>
                                        <span class="text-indicate">Unavailable Slots</span>
                                    </li>
                                    <li>
                                        <span class="clr-indicate available"></span>
                                        <span class="text-indicate">Available Slots</span>
                                    </li>
                                    <li>
                                        <span class="clr-indicate selected"></span>
                                        <span class="text-indicate">Selected</span>
                                    </li>
                                </ul>

                            </div>
                        <div class="form-inner">
                            <button type="submit" class="primary-btn1 black-bg">
                                <span>
                                    Confirm Reschedule
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                                <span>
                                    Confirm Reschedule
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Enquiry Modal section End-->


        <!-- Enquiry Modal section Start-->
    <div class="modal enquiry-modal fade" id="cancelModalModal" tabindex="-1" aria-labelledby="cancelModallLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                    </svg>
                </button>
                <div class="modal-body">
                    <h4 class="modal-title" id="cancelModalLabel">Cancel Appointment!</h4>
                    <form class="enquiry-form-wrapper">
                        
                        <h4>Are you sure?</h4>
                        <p>You want to cancel the appointment.</p>

                        <div class="form-inner">
                            <button type="submit" class="primary-btn1 black-bg">
                                <span>
                                    Yes, Procceed 
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                                <span>
                                    Yes, Procceed 
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <button type="submit" class="primary-btn1 btn-outline-secondery black-bg ms-3">
                                <span>
                                    No
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                                <span>
                                    No
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Enquiry Modal section End-->
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script>
    let appointments = @json($points->items());
    document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calenderViewDetail');

    let events = appointments.map(item => {
    return {
        title: item.booking_id + ' - ' + item.doctor_name,
        start: item.booking_date + 'T' + item.booking_time_slot,
        url: "{{ url('/useraccount-appointment-details') }}/" + item.id
    };
});

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        events: events,

        eventClick: function(info) {
            info.jsEvent.preventDefault(); // prevent default

            if (info.event.url) {
                window.location.href = info.event.url;
            }
        }
    });

    calendar.render();
});
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        // remove active class
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // hide all
        document.getElementById('listView').style.display = 'none';
        document.getElementById('calendarViewfull').style.display = 'none';

        // show selected
        document.getElementById(this.dataset.target).style.display = 'block';
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('calendarViewfull').style.display = 'none';
});
 $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today",
    });
    var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            minDate: "",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    toDate.set('minDate', selectedDates[0]);
                } else {
                    toDate.set('minDate', null);
                }
            }
        });

        var toDate = flatpickr("#to_date", {
            dateFormat: "d-m-Y",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });
document.getElementById('exportExcel').addEventListener('click', function () {

    let url = buildExportUrl("{{ route('front.points.export') }}");

    window.location.href = url;
});

document.getElementById('exportPdf').addEventListener('click', function () {

    let url = buildExportUrl("{{ route('front.points.export.pdf') }}");

    window.location.href = url;
});

function buildExportUrl(baseUrl) {

    let form = document.getElementById('search-form');

    let formData = new FormData(form);

    let params = new URLSearchParams();

    formData.forEach((value, key) => {

        if (value !== null && value !== '') {
            params.append(key, value);
        }

    });

    return baseUrl + '?' + params.toString();
}


</script>

@endsection