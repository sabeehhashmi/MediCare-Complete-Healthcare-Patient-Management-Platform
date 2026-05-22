@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <form action="{{url('web/booking-overview')}}" method="POST" id="appointment-save-form">
                @csrf
            <!-- <div class="main-content"> -->
                <div class="action-bottom-area py-2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 d-md-flex justify-content-between align-items-center">
                                <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" name="is_flexible" type="checkbox" id="formCheck2" checked />
                                        <label class="form-check-label ms-2" for="formCheck2">
                                            I am flexible to different time, subjected to the Doctor’s availability
                                        </label>
                                    </div>
                                </div>
                                <button id="confirm-booking" type="submit" class="btn btn-primary w-md-280 w-100">Confirm Booking</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-content">
                    @if ($errors->any())
                        <!-- <span class="text-danger">
                            <strong class="">Please fill the form properly</strong>
                        </span> -->
                    @endif
                    <div class="container-fluid" style="max-width: 100%;">
                        <div class="row">
                            <div class="col-lg-5 px-md-2 px-0">
                                <div class="product-box rounded p-md-3 p-2 doctor-card in-detail-page">
                                    <div class="row align-items-center">
                                        @error('doctor_id')
                                        <span class="text-danger ">{{$message}}</span>
                                        @enderror
                                        <div class="col-md-4 col-4 pe-0">
                                            <input type="hidden" name="doctor_id" value="{{$doctor->user_id}}">
                                            <div class="product-img bg-light">
                                                <img src="{{$doctor->user->user_img_url ?? null}}" alt="" class="img-fluid mx-auto d-block" />
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-8">
                                            <div class="product-content pt-0">
                                                <h5 class="mt-1 mb-2"><a href="#" class="text-body fw-bold font-size-18">Dr. {{$doctor->user->name}}</a></h5>
                                                <p class="text-body font-size-16 mb-1">{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}</p>
                                                <p class="text-muted font-size-13 mb-0">{{$doctor->year_of_experiance ?? 0}} years experience overall</p>
                                                <h5 class="font-size-15 mt-1 mb-2">{{$doctor->hospital->name_en ?? null}}</h5>
                                                <p class="text-muted font-size-14 mb-0">{{round($doctor->hospital->location[0]->distance ?? 0.0)}} km away</p>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12 d-flex justify-content-between mt-2 align-items-center">
                                                        <div class="">
                                                            <span class="font-size-10">Availability Status</span>
                                                            <p class="status fw-bold mb-0 available">Available</p>
                                                        </div>
                                                        <a href="bookin-appointment.php" class="btn btn-primary">Book an Appointment</a>
                                                    </div> -->
                                    </div>
                                </div>

                                <div class="mt-md-4 mt-2">
                                    <div class="card">
                                        <div class="card-body">
                                            @error('booking_date')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                            <div class="row location-card">
                                                <label class="form-label">Choose Date</label>
                                                <input type="text" class="form-control datepicker-inline @error('booking_date') is-invalid @enderror" value="{{ old('booking_date') }}" name="booking_date" style="opacity: 0; height: 0; padding: 0;" id="booking_date_appointment" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 px-md-2 px-0">
                                <div class="card">
                                    <div class="card-body">
                                        @error('booking_time_slot')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label mb-3" for="username">Select Slot</label>
                                                <div class="timeslot-selector timeslot-selector-modal">                        
                                                @for($i = 0; $i < count($time_slot); $i++)
                                                    <span>
                                                        <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot" disabled value="{{$time_slot[$i]}}" class="idReschedule time-slot checkbx-style availiblity @error('booking_time_slot') is-invalid @enderror"  {{ old('booking_time_slot') == $time_slot[$i] ? 'checked' : '' }} />  
                                                            <label for="sat{{$i+17+$i}}">{{$time_slot[$i]}}</label>
                                                </span>
                                                        </span>
                                                    @endfor
                                            </div>
                                            </div>
                                            <div class="col-12">
                                                <ul class="availability-indicator mt-3">
                                                    <li><span class="not-available-color"></span> Not Available</li>
                                                    <li><span class="available-color"></span> Available</li>
                                                    <li><span class="selected-color"></span> Selected</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                @if(Auth::check())
                                <div class="product-box Patient-choose-card rounded p-md-3 p-2 doctor-card in-detail-page mt-md-4 mt-2 mb-5">
                                    <div class="row">
                                        @error('patient_id')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <label class="form-label mb-0" for="username">Select Patient</label>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal" class="btn btn-outline-primary">Add Patient </a>
                                            </div>
                                        </div>
                                        <div class="col-md-12 pe-0">
                                            <hr class="my-3" />
                                            <div id="patients-data-list">
                                                <!-- <div class="form-check form-check-right mb-3 pb-2 d-flex justify-content-between border-bottom">
                                                    <label class="form-check-label" for="formRadiosRight1">
                                                        {{Auth::User()->first_name}} {{Auth::User()->last_name}}
                                                    </label>
                                                    <input class="form-check-input" type="radio" name="patient_id" id="formRadiosRight1" checked />
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            <!-- </div> -->
            </form>

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Add Patient</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="{{url('web/save-members')}}" method="POST" class="custom-form" id="save-patient-form">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="row">
                                    <input type="hidden" name="patient" value="{{Auth::User()->id ?? null}}">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Full Name</label>
                                        <div class="position-relative">
                                            <input type="text" name="full_name" class="form-control no-icon" id="" placeholder="Enter Full Name" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">Gender </label>
                                        <div class="position-relative">
                                            <select name="gender" id="GenderModal" class="select2-single no-icon" data-placeholder="Gender">
                                                <option></option>
                                                <option value="1">Female</option>
                                                <option value="2">Male</option>
                                                <option value="3">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Age</label>
                                        <div class="position-relative">
                                            <input type="text" name="age" class="form-control no-icon" id="" placeholder="Enter Age" maxlength="3" pattern="\d{1,3}" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Policy </label>
                                        <div class="position-relative">
                                            <select name="insurance_id" id="insurance-policy" class="select2-single no-icon" data-placeholder="My Insurance Policy">
                                            <option value="">My Insurance Policy</option>
                                            @foreach($insurencePolicies as $id => $value)
                                            <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Network </label>
                                        <div class="position-relative">
                                            <select name="sub_insurance_id" id="sub-insurance-policy" class="select2-single no-icon" data-placeholder="My Insurance Network">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">Cancel</button>
                                <button id="finish-continue" class="btn btn-primary">Finish Adding and Continue Booking</button>
                            </div>
                        </form>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
            <!-- </div> -->
            <!-- end main content-->
@endsection

@section('custom_js')
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
            let currentUser = '{{Auth::User()->id ?? null}}';
            $.ajax({
                type: "GET",
                url: "{{ url('web/get-members') }}",
                success: function (res) {
                    if (res) {
                        $('#patients-data-list').empty();
                        // $('#departments').append('<option value="">Select Departments</option>');
                        $.each(res, function (index, data) {
                            $('#patients-data-list').append(`<div class="form-check form-check-right mb-3 pb-2 d-flex justify-content-between border-bottom">
                                                    <label class="form-check-label" for="formRadiosRight${index}">
                                                    ${data.full_name ? data.full_name : (data.first_name+' '+data.last_name)}
                                                    </label>
                                                    <input class="form-check-input" type="radio" name="patient_id" id="formRadiosRight${index}" value="${data.id}" ${currentUser == data.id ? 'checked' : ''}/>
                                                </div>`);
                        });

                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }

        function checkAvailibility(doctor_id, date){
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ route('web.appointments.check_doctor_availability') }}",
                    data:{
                        'booking_date': date,
                            'doctor_user_id': doctor_id,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function (res) {
                        if (res.oData.list) {
                            updateSlots(res.oData.list, date);
                        }else{
                            App.alert(res['message'] || 'Doctor is Not Available', 'Fail!', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        App.alert('Something went wrong', 'Fail!', 'error');
                        console.error('Error fetching Members:', error);
                    }
                });
            }
        }

        function updateSlots(slots, selectedDate) {
            $('.availiblity').prop('disabled', true);
            $('.availiblity').prop('checked', false);

            // Get the current date and time
            var currentDate = new Date();
            var currentHours = currentDate.getHours();
            var currentMinutes = currentDate.getMinutes();
            var currentDateString = currentDate.toISOString().split('T')[0];
            const selectedDateFormatted = reformatDate(selectedDate);

            if (slots && Array.isArray(slots)) {
                $('.availiblity').each(function() {
                    var slot = $(this).val();
                    
                    // Parse the slot time to compare with the current time
                    var [slotHours, slotMinutes] = slot.split(':').map(Number);

                    // Check if the slot is available and not in the past
                    var slotData = slots.find(s => s.slot_text === slot);
                    var isAvailable = slotData && slotData.is_available === "1";
                    var isPast = selectedDateFormatted === currentDateString && (slotHours < currentHours || (slotHours === currentHours && slotMinutes < currentMinutes));

                    // Disable the slot if it's not available or in the past
                    $(this).prop('disabled', !isAvailable || isPast);
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
                        App.alert(response['message'] || 'Patients saved successfully', 'Success!', 'success');
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
                            App.alert(response.message || 'Failed to Verify OTP', 'Fail!','error');
                            $('#add-mode').text('Add More').attr('disabled', false);
                            $('#finish-continue').attr('disabled', false);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    App.alert(res['message'] || 'Someting went wrong', 'Fail!', 'error');
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
        // loadMember('{{$patient->id ?? null}}')
    </script>
@endsection