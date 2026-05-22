@extends('front.template.layout')

@section('title', 'My Reports - MedNero')

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            
            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title">
                        <h4>My Reports</h4>
                    </div>
                    <!-- Faq Section Start-->
                    <div class="home1-faq-section mb-100">
                            <div class="row justify-content-center">
                                <div class="col-xl-12">
                                    <div class="faq-wrap switch-tab-button-wrap">
                                        
                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <!-- <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Lab Reports</button> -->
                                                <a class="nav-link {{ request('type') != 'xray' ? 'active' : '' }}" href="{{ route('front.reports') . '?type=lab' }}">Lab Reports</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <!-- <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">X-Ray / Imaging Report</button> -->
                                                <a class="nav-link {{ request('type') == 'xray' ? 'active' : '' }}" href="{{ route('front.reports') . '?type=xray' }}">X-Ray / Imaging Report</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

                                                @if($allReports && $allReports->count())
                                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                                        @foreach($allReports as $appKey => $appointment)
                                                            @foreach($appointment->reports as $key => $doc)
                                                                <div class="accordion-item wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
                                                                    <h5 class="accordion-header" id="labreportHeading{{$doc->id}}">
                                                                        <button class="accordion-button d-flex justify-content-between" type="button"
                                                                            data-bs-toggle="collapse" data-bs-target="#labreportContent{{$doc->id}}"
                                                                            aria-expanded="false" aria-controls="labreportContent{{$doc->id}}">#MEDN{{ $doc->id }} &nbsp;&nbsp; <span class="badge text-bg-secondary">{{ $doc->created_at->format('d-M-Y h:i A') }}</span> &nbsp;&nbsp; <span class="badge text-bg-primary {{ $appointment->reports->count() == 1 ? 'd-none' : '' }}">{{ $key + 1 }}</span>                                                      
                                                                        </button>
                                                                    </h5>
                                                                    <div id="labreportContent{{$doc->id}}" class="accordion-collapse collapse show"
                                                                        aria-labelledby="labreportHeading{{$doc->id}}" data-bs-parent="#accordionFlushExample">
                                                                        <div class="accordion-body">
                                                                            <div class="d-flex justify-content-between">
                                                                                <span>Booking No: <strong>{{ $appointment->booking_id }}</strong></span> 
                                                                                <span>Booked Slot: <strong>{{ $appointment->booking_date }} - {{ \Carbon\Carbon::parse($appointment->booking_time_slot)->format('h:i A') }}</strong></span> 
                                                                            </div>
                                                                            <div class="d-flex justify-content-between">
                                                                                <span>Patient Name: <strong>{{ $appointment->user->name }}</strong></span> 
                                                                                <span>Clinic: <strong>{{ $appointment->hospital->name_en }}</strong></span> 
                                                                            </div>

                                                                            <div class="d-flex justify-content-between">
                                                                                <a href="{{ $doc->docment }}" target="_blank" class="primary-btn1 btn-outline mt-30">
                                                                                    <span>View Report <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>
                                                                                    <span>View Report <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>
                                                                                </a>
                                                                            </div>
                                                                                
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>

                                                    <div class="col-12 py-3">
                                                        {{ $allReports->links() }}
                                                    </div>

                                                @else
                                                    <p>You do not have any reports yet.</p>
                                                @endif


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- Faq Section End-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--Checkout Page End-->
@endsection

@section('modals')
<!-- Enquiry Modal section Start-->
<div class="modal enquiry-modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
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
                                <!-- ... other slots ... (trimmed for brevity but keeping structure) -->
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
                            <span>Confirm Reschedule <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal enquiry-modal fade" id="cancelModalModal" tabindex="-1" aria-labelledby="cancelModallLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                </svg>
            </button>
            <div class="modal-body">
                <h4 class="modal-title" id="cancelModalLabel">Cancel Appointment!</h4>
                <form class="enquiry-form-wrapper">
                    <h4>Are you sure?</h4>
                    <p>You want to cancel the appointment.</p>
                    <div class="form-inner">
                        <button type="submit" class="primary-btn1 black-bg">
                            <span>Yes, Proceed <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>
                        </button>
                        <button type="button" class="primary-btn1 btn-outline-secondery black-bg ms-3" data-bs-dismiss="modal">
                            <span>No <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>
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
<script>
    new AirDatepicker('#calenderView', {
        inline: true,
        locale: localeEn
    })
</script>
@endsection
