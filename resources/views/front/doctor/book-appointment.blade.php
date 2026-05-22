@extends('front.template.layout')

@section('title', 'Book Appointment')

@section('content')
<style>
    .text-danger {
  font-size: 14px;
}
.cart-body.card-amount {
  border: 2px solid #e6e6e6;
  padding: 10px;
}
</style>
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            <div class="col-lg-7">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title">
                        <h4>Check Availability</h4>
                    </div>
                    <div class="checkout-form">
                        

                        
                        <form action="{{url('front/booking-overview')}}" 
                            class="booking-overview" 
                            method="POST" 
                            id="appointment-save-form" enctype="multipart/form-data">
        @csrf
                            @error('doctor_id')
                            <span class="text-danger ">{{$message}}</span>
                            @enderror
                        <input type="hidden" name="booking_id" value="{{ rand(100000000, 2147483647) }}">
                        <input type="hidden" name="doctor_id" value="{{ $doctor->user_id }}">
                        <input type="hidden" name="booking_date" id="selected_booking_date">
                        @error('booking_date')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                        <h5>Preferred Date</h5>
                        
                        <div class="mb-30"  id="booking_date_appointment"></div>

                        @error('booking_time_slot')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <h5>Preferred Time Range</h5>
                        <div class="time-slots time-range-slots mb-40">
                            <ul>
                                @for($i = 0; $i < count($time_slot); $i++)
                                <li class="">
                                    <label class="checkbox-container">
                                        <input 
    type="radio" 
    class="availability-slot"
    id="sat{{$i+17+$i}}"   
    name="booking_time_slot" 
    value="{{$time_slot[$i]}}"
    >
                                        <span class="checkmark"></span>
                                        <span class="label-text">
                                           
                                            <span class="small-label">{{$time_slot[$i]}}</span>
                                        </span>
                                    </label>
                                </li>
                                @endfor
                            </ul>

                            <ul class="color-indicaters-wrap">
                                <li>
                                    <span class="clr-indicate unavailable"></span>
                                    <span class="text-indicate">Unavailable</span>
                                </li>
                                <li>
                                    <span class="clr-indicate available"></span>
                                    <span class="text-indicate">Available</span>
                                </li>
                                <li>
                                    <span class="clr-indicate selected"></span>
                                    <span class="text-indicate">Selected</span>
                                </li>
                            </ul>
                            
                            <div class="form-inner2 mb-25 mt-30">
                                <div class="form-check">
                                    <input class="form-check-input" name="is_flexible" type="checkbox" id="contactCheck11" checked />
                                   
                                    <label class="form-check-label" for="contactCheck11">
                                        I'm flexible with time
                                    </label>
                                </div>
                            </div>

                        </div>

                       
                           <h5> Documents Upload</h5>
                            <div class="row">
                               
                                <div class="col-md-12">
                                     @if ($settings->consent_url ?? null)
                        <a id="previewLink" href="{{$settings->consent_url}}" target="_blank">Download Consent Document</a>
                        @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner">
                                        
                                        <label>Consent Document</label>
                                        <div class="custom-file">
                                            <input type="file" name="consent"  class="form-control">
                                            <label>
                                                <span class="btn">Choose File</span>
                                                <span class="file-name">No file selected</span>
                                            </label>
                                            
                                        </div>
                                         @error('consent')
                        <span class="text-danger">{{$message}}</span>
                    @enderror

                                        
                                    </div>
                                </div>
                            </div>
                                 <h5> Report Upload</h5>
                            <div class="row">
                                
                            
                            <div class="form-inner2 mb-25 mt-30">
                                <div class="form-check">
                                    <input class="form-check-input" name="no_reports" type="checkbox" id="contactCheck112" />
                                   
                                    <label class="form-check-label" for="contactCheck112">
                                        I don't have reports
                                    </label>
                                </div>
                            </div>
                               
    <div class="col-md-6 report-section">
        <div class="form-inner">
            <label>Lab Reports</label>
            <div class="custom-file">
                <input type="file" name="lab_report[]" multiple class="form-control">
                <label>
                    <span class="btn">Choose File</span>
                    <span class="file-name">No file selected</span>
                </label>
            </div>
             @error('lab_report')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
        </div>
    </div>

    <div class="col-md-6 report-section">
        <div class="form-inner">
            <label>X-Ray / Imaging Report</label>
            <div class="custom-file">
                <input type="file" name="xray[]" multiple class="form-control">
                <label>
                    <span class="btn">Choose File</span>
                    <span class="file-name">No file selected</span>
                </label>
            </div>
            @error('xray')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
        </div>
    </div>


                                

                                
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-inner two mb-25">
                                        
                                        <label>Booking Type</label>
                                    <select name="booking_type" id="booking_type" class="select2-single" data-placeholder="Select Type">
                                        <option></option>
                                        @foreach($bookingTypes as $type)
                                        <option value="{{ $type->name }}">
                                            {{ $type->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('booking_type')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                    </div>
                                </div>
                                
                                @if(Auth::check())
                                <div class="col-md-12 mt-3">
                                    <h5>Choose the Patient</h5>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-inner two mb-25">
                                        @error('patient_id')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                        <label>Select Patient*</label>
                                        <select id="patient_id" name="patient_id" class="form-control">
                                            <option value="">Loading...</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                               
                                <div class="col-md-12">
                                    <p style="font-size: 14px;" class="mt-30">We'll submit your request to the doctor. If they're available, 
                                        our team will confirm your appointment shortly.</p>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Phone Number*</label>
                                        <input type="text" placeholder="(212)+ 455 645 678">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Email Address <span>(Optional)</span></label>
                                        <input type="email" placeholder="info@gmail.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Your Location</label>
                                        <input type="text" placeholder="Type Location">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Street Address*</label>
                                        <input type="text" placeholder="Street address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Postal Code*</label>
                                        <input type="text" placeholder="Postal code">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-inner two mb-25">
                                        <label>Short Notes*</label>
                                        <textarea placeholder="Write Something..."></textarea>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title">
                        <h4>Doctor Detail</h4>
                    </div>
                    <div class="order-sum-area">
                        <form>
                            <div class="cart-menu">
                                <div class="cart-body">
                                    <ul>
                                        <li class="single-item">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    <div class="item-img">
                                                        <img src="{{$doctor->user->user_img_url ?? null}}"
                                                            alt="">
                                                    </div>
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                            <span>{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}</span>
                                                            <h6><a href="#">
                                                                Dr. {{$doctor->user->name}}</a></h6>
                                                            <span  class="mt-2 d-inline-block">{{$doctor->hospital->name_en ?? null}}</span>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li> 
                                        
                                        
                                    </ul>
                                </div>
                                <div class="cart-body card-amount">
                                     <ul>
                                      
                                        
                                        <li class="single-item">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                           <h6>Total Appointment Amount: AED <span class="total_val">{{ $doctor->user->consultation_fee }}</h6>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>
                                       @if(Auth::check())
                                        <li class="single-item">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                           <h6>Available Credits:<span class="available_points">{{Auth::user()->points}}</span></h6>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>
                                        @endif
<li class="single-item">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                        @php
                                $settings=getSettings();
                                @endphp
                                @if(Auth::check() && $settings->loyallty_points_enable==1 && Auth::user()->points >= $settings->loyallty_points_for_percentage)
                                <div class="form-inner2 mb-25 mt-30">
                                <div class="form-check">
                                    <input class="form-check-input" name="use_points" type="checkbox" id="contactCheck113" />
                                   
                                    <label class="form-check-label" for="contactCheck113">
                                        Use Loyalty Credits
                                    </label>
                                </div>
                            </div> 
                            @endif

                             </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>
                                         <li class="single-item eligible_saving" style="display:none ">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                           <h6> Eligible Discount: AED <span class="savings">0</span></h6>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>

                                        <li class="single-item eligible_saving" style="display:none ">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                           <h6> Final Payable Amount after discount:  AED <span class="remaings">{{ $doctor->user->consultation_fee }}</span></h6>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>
                                    </ul>
                                    </div>
                                <div class="cart-footer">
                                   
                               <p style="font-size: 14px;" class="mb-4">Tip: Book in advance for more flexible appointment options.</p>
<ul>
<li>
                                    <p style="font-size: 14px; " class="mt-0 mb-0">{{$doctor->year_of_experiance ?? 0}} years experience overall</p>
</li> <li>
                                    <p style="font-size: 14px; " class="mt-0 mb-0">{{ round($doctor->hospital->location[0]->distance ?? 0.0)}} km away</p>
</li>
</ul>
                                   

                                    <button type="button" class="primary-btn1" >
                                        <span>
                                            SUBMIT REQUEST
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                            </svg>
                                        </span>
                                        <span>
                                            SUBMIT REQUEST
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    new AirDatepicker('#booking_date_appointment', {
    inline: true,
    locale: localeEn,
    dateFormat: 'dd-MM-yyyy',

    minDate: new Date(),   // ⭐ restrict past dates

    onSelect({date, formattedDate}) {
        $('#selected_booking_date').val(formattedDate);

        checkAvailibility(
            '{{ $doctor->user_id }}',
            formattedDate
        );
    }
});
    $('.primary-btn1').on('click', function(){
        
    $('.booking-overview').submit();
});
    </script>
    <script>
        $(document).ready(function(){
            let bookingDate = new Date();
            let day = String(bookingDate.getDate()).padStart(2, '0');
            let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
            let year = bookingDate.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            checkAvailibility('{{$doctor->user_id}}', formattedDate);

        });
        function reformatDate(dateStr) {
            const [day, month, year] = dateStr.split('-');
            return `${year}-${month}-${day}`;
        }
        function loadMember(){

let currentUser = '{{ Auth::user()->id ?? null }}';

$.ajax({
    type: "GET",
    url: "{{ url('front/get-members') }}",
    success: function (res) {

        let $select = $('#patient_id');

        $select.empty();
        $select.append('<option value="">Select Patient</option>');

        $.each(res, function (index, data) {

            let fullName = data.full_name 
                ? data.full_name 
                : (data.name ?? '');

            let selected = (currentUser == data.id) ? 'selected' : '';

            $select.append(
                '<option value="'+data.id+'" '+selected+'>'+fullName+'</option>'
            );
        });

        // 🔥 THIS IS THE IMPORTANT LINE
        $select.niceSelect('update');

    }
});
}
       
        function checkAvailibility(doctor_id, date){
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ route('front.appointments.check_doctor_availability') }}",
                    data:{
                        'booking_date': date,
                            'doctor_user_id': doctor_id,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function (res) {
                        if (res.oData.list) {
                            updateSlots(res.oData.list, date);
                        }else{
                            disableAllSlots();
                          //  alert(res['message'] || 'Doctor is Not Available');
                        }
                    },
                    error: function (xhr, status, error) {
                     //   App.alert('Something went wrong', 'Fail!', 'error');
                        console.error('Error fetching Members:', error);
                    }
                });
            }
        }

        function updateSlots(slots, selectedDate) {

let $slots = $('.availability-slot');

// Reset all slots first
$slots.prop('disabled', true);
$slots.prop('checked', false);

var currentDate = new Date();
var currentHours = currentDate.getHours();
var currentMinutes = currentDate.getMinutes();
var currentDateString = currentDate.toISOString().split('T')[0];

const selectedDateFormatted = reformatDate(selectedDate);

if (slots && Array.isArray(slots)) {

    $slots.each(function() {

        var slotValue = $(this).val(); // e.g. 14:30

        var [slotHours, slotMinutes] = slotValue.split(':').map(Number);

        var slotData = slots.find(s => s.slot_text === slotValue);
        var isAvailable = slotData && slotData.is_available === "1";

        var isPast =
            selectedDateFormatted === currentDateString &&
            (slotHours < currentHours ||
            (slotHours === currentHours && slotMinutes < currentMinutes));

        if (isAvailable && !isPast) {
            $(this).prop('disabled', false);
            $(this).closest('li').removeClass('unavailable').addClass('available');
        } else {
            $(this).prop('disabled', true);
            $(this).closest('li').removeClass('available').addClass('unavailable');
        }

    });
}
}
        $('#booking_date_appointment').on("change", function () {
            checkAvailibility('{{$doctor->user_id}}', $('#booking_date_appointment').val());
        });

        $(document).ready(function() {
            loadMember();
            $("#GenderModal, #insurance-policy, #sub-insurance-policy").select2({ dropdownParent: "#event-modal" });
            // $('#booking_date_appointment').val()
            checkAvailibility('{{$doctor->user_id}}', $('#booking_date_appointment').val());
        });

        function lodSubIncurance(incuranceId){
            if (incuranceId) {
                $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                    success: function (res) {
                        if (res) {
                            $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
                            $.each(res, function (index, data) {
                                $('#sub-insurance-policy').append('<option value="' + data.id+'">' + data.title + '</option>');
                            });
                            // $('#sub-insurance-policy').val(selectedId).trigger('change');
                            $('#sub-insurance-policy').select2({ dropdownParent: "#event-modal" })
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#sub-insurance-policy').empty();
                $('#sub-insurance-policy').append('<option value=""></option>');
            }
        }

        function resetPatientForm(){
            var form = $('#save-patient-form')[0];
            form.reset();
            $(form).find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="tel"], input[type="url"]').val('');
            $("#GenderModal, #insurance-policy, #sub-insurance-policy").select2({ dropdownParent: "#event-modal" });
        }
        function savePatient(close = false){
            event.preventDefault();
            var $form = $('#save-patient-form');
            let formData = $('#save-patient-form').serialize();
            $('#add-mode').text('Processing..').attr('disabled', true);
            $('#finish-continue').attr('disabled', true);
            $.ajax({
                url: $('#save-patient-form').attr('action'),
                method: $('#save-patient-form').attr('method'),
                data: formData,
                success: function(response) {
                    $('#add-mode').text('Add More').attr('disabled', false);
                    $('#finish-continue').attr('disabled', false);
                    if (response.status == '1') {
                        loadMember();
                       // App.alert(response['message'] || 'Patients saved successfully', 'Success!', 'success');
                        if(close){
                            $('#event-modal').modal('hide');
                        }else{
                            resetPatientForm();
                        }
                    } else {
                        // Handle error response
                        if(response.errors){
                           // App.alert(response.message || 'Something went wrong', 'Fail!','error');
                            jQuery.each(response.errors, function (e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                }
                            });
                        } else {
                          //  App.alert(response.message || 'Failed to Verify OTP', 'Fail!','error');
                            $('#add-mode').text('Add More').attr('disabled', false);
                            $('#finish-continue').attr('disabled', false);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                  //  App.alert(res['message'] || 'Someting went wrong', 'Fail!', 'error');
                }
            });
        }
        $('#add-more').on('click', function(event) {
            savePatient();
        });
        
        $('#finish-continue').on('click', function(event) {
            savePatient(true);
        });


        $('#insurance-policy').on('change', function(){
            lodSubIncurance($(this).val());
        })

        function disableAllSlots() {

let $slots = $('.availability-slot');

$slots.each(function() {

    $(this).prop('disabled', true);
    $(this).prop('checked', false);

    $(this).closest('li')
        .removeClass('available')
        .addClass('unavailable');
});
}

        // loadMember('{{$patient->id ?? null}}')
    </script>

    <script>
$(document).ready(function() {

    function toggleReports() {
        if ($('#contactCheck112').is(':checked')) {
            $('.report-section').hide();
        } else {
            $('.report-section').show();
        }
    }

    // Run on load
    toggleReports();

    // Run on change
    $('#contactCheck112').on('change', function() {
        
        toggleReports();
    });

});


$(document).ready(function () {

    // ================================
    // SETTINGS VALUES
    // ================================
    let consultationFee = parseFloat("{{ $doctor->user->consultation_fee ?? 0 }}");

    // % discount from settings table
    let loyaltyPercentage = parseFloat("{{ getSettings()->loyallty_points_percentage ?? 0 }}");

    // ================================
    // INITIAL VALUES
    // ================================
    $('.total_val').text(consultationFee.toFixed(2));
    $('.remaings').text(consultationFee.toFixed(2));

    // ================================
    // CHECKBOX CHANGE
    // ================================
    $('input[name="use_points"]').on('change', function () {
        
        if ($(this).is(':checked')) {

            // calculate discount
            let discount = (consultationFee * loyaltyPercentage) / 100;

            // final amount
            let remaining = consultationFee - discount;
            $('.eligible_saving').show();
            // update UI
            $('.savings').text(discount.toFixed(2));
            $('.remaings').text(remaining.toFixed(2));

        } else {

            // reset values
            $('.eligible_saving').hide();
            $('.savings').text('0.00');
            $('.remaings').text(consultationFee.toFixed(2));
        }
    });

});

$('#appointment-save-form').on('submit', function (e) {

    // checkbox outside form
    let usePoints = $('#contactCheck113').is(':checked') ? 1 : 0;

    // append hidden input inside form
    if ($('#appointment-save-form input[name="use_points"]').length === 0) {

        $('#appointment-save-form').append(
            '<input type="hidden" name="use_points" value="' + usePoints + '">'
        );

    } else {

        $('#appointment-save-form input[name="use_points"]').val(usePoints);
    }
});
</script>

    
@endsection