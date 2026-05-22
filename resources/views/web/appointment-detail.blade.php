@extends('web.template.layout')

@section('title', 'Home')
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop
@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content">
                    <div class="container-fluid">

                        <div class="position-relative mb-5">
                            <div class="d-lg-flex">
                                @include('web.profile-sidebar')
                                <!-- end chat-leftsidebar -->
                        
                                <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Booking Id: <span class="text-primary">{{$appointment->booking_id}}</span></h5>
                                            <div class="status-badge {{getAppointmentStatusClass($appointment->booking_status)}}">
                                                <span></span> {{strtoupper($appointment->booking_status)}}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card border-0 p-0 overflow-hidden shadow-none">
                                                            <div class="mail-list">
                                                                <a href="#!">
                                                                    <h4 class="font-size-16 mb-3">Date & Time</h4>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="icn-bx me-3">
                                                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                                                        </span>
                                                                        @php
                                                                            $formattedDate = \Carbon\Carbon::parse($appointment->booking_date)->format('d F Y');
                                                                        @endphp
                                                                        <div class="flex-grow-1">
                                                                            <h4 class="mb-2 font-size-18">{{$formattedDate}}</h4>
                                                                            <h5 class="font-size-14">{{$appointment->booking_time_slot}}</h5>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <hr />
                                                                <a href="#!">
                                                                    <h4 class="font-size-16 mb-3">Clinic/ Hospital</h4>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="icn-bx me-3">
                                                                            <i class="bx bx-map font-size-20 align-middle"></i>
                                                                        </span>
                                                                        <div class="flex-grow-1">
                                                                            <h4 class="mb-2 font-size-18">{{$appointment->doctor->hospital->name_en ?? 'N/A'}}</h4>
                                                                            <!-- <h5 class="font-size-14">{{$appointment->doctor->hospital->location[0]->location ?? 'N/A'}}</h5> -->
                                                                            <h5 class="font-size-14">{{$appointment->hospital->address ?? 'N/A'}}</h5>
                                                                        </div>
                                                                        <div class="flex-shrink-0"></div>
                                                                    </div>
                                                                </a>

                                                                <hr />

                                                                <a href="#!">
                                                                    <h4 class="font-size-16 mb-3">Doctor Details</h4>
                                                                    <div class="d-flex">
                                                                        <div class="me-3">
                                                                            <img style="width: 80px; height: 80px; border-radius: 5px;" src="{{$appointment->doctor->user->user_img_url ?? null}}" alt="Generic placeholder image" />
                                                                        </div>
                                                                        <div>
                                                                            <div class="flex-grow-1 mb-2">
                                                                                <h4 class="mb-2 font-size-18">DR {{$appointment->doctor->user->name ?? 'N/A'}}</h4>
                                                                                <h5 class="mb-0 font-size-14"><i class="fas fa-map-marker-alt me-2"></i> {{$appointment->doctor->country->name ?? 'N/A'}}</h5>
                                                                            </div>
                                                                            <!-- <h5 class="mb-2 font-size-14"><i class="bx bx-calendar me-2"></i> {{$appointment->doctor->user->dob ?? null}}</h5> -->
                                                                            <h5 class="mb-2 font-size-14"><i class="fas fa-transgender me-2"></i> {{GENDERS[($appointment->doctor->user->gender ?? null)] ?? 'N/A'}}</h5>
                                                                            <h5 class="mb-2 font-size-14"><i class="bx bx-envelope me-2"></i> {{$appointment->doctor->user->email ?? "N/A"}}</h5>
                                                                            <h5 class="mb-2 font-size-14"><i class="bx bxs-phone-call me-2"></i> {{$appointment->doctor->user->phone ? ('+'.$appointment->doctor->user->dial_code.' '.$appointment->doctor->user->phone) : 'N/A'}}</h5>
                                                                            <h5 class="mb-0 font-size-14"><i class="bx bxl-whatsapp-square me-2"></i> {{$appointment->doctor->user->whatsap_phone ? ('+'.$appointment->doctor->user->whatsap_dial_code.' '.$appointment->doctor->user->whatsap_phone) : 'N/A'}}</h5>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row"></div>

                                            <hr />

                                            <div class="appointment-status-btns">
                                            @if (($appointment->booking_status === BOOKING_STATUS_PENDING || strtolower($appointment->booking_status) === BOOKING_STATUS_CONFIRMED || $appointment->booking_status === BOOKING_STATUS_RESCHEDULED))
                                                <button type="button" class="btn btn-dark waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#cancel-appointment">Cancel</button>
                                            @endif
                                            @if ($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_CONFIRMED)
                                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#reschedule-modal">Reschedule</button>
                                            @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                    <!-- end user chat -->

                            </div>
                    <!-- container-fluid -->
                    </div>
            <!-- </div> -->
            <!-- end main content-->

            <!-- Modal -->
            <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- {{$appointment->booking_id}}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action=""  id="cancelAppointment_form"  class="custom-form">
                                @csrf
                            <input type="hidden" id="idCancel" value="{{$appointment->id}}" name="appointment_id">
                                <div class="row">
                                    <div class="col-12 mb-3 text-center">
                                        <img src="{{ URL::asset('web') }}/images/cancel-img.svg" class="img-fluid" alt="">
                                    </div>

                                    <div class="col-12 text-center mb-3">
                                        <h4>Are you sure?</h4>
                                        <p class="mb-0">You want to cancel the appointment.</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="">Reason of Cancellation</label>
                                        <div class="position-relative">
                                            <textarea class="form-control" id="" name="reason_cancel" rows="2"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                            <button type="button" class="btn btn-dark" style="width: 120px;" id="cancelAppointment">Cancel</button>
                        </div>
                    </div>
                    
                </div>
            </div>

        <!-- Modal -->
            <div class="modal fade" id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reschedule Booking</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{url('web/patient/rescheduleAppointment')}}" id="reschedule_appointment_form" class="custom-form">
                        @csrf 
                            <input type="hidden" id="idReschedule" name="id" value="{{$appointment->id}}">
                            <input type="hidden" id="doctorIdReschedule" name="doctor_id" value="{{$appointment->doctor->user_id}}">
                            <div class="row">
                            @php
                                $foramted_appointment_date = ''; 
                                if($appointment->booking_date){
                                    $foramted_appointment_date = $appointment->booking_date = \Carbon\Carbon::createFromFormat('Y-m-d', $appointment->booking_date)->format('d-m-Y');
                                }
                            @endphp
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Select Date</label>
                                    <div class="position-relative">
                                        <input type="text" name="reschedule_date" class="form-control flatpicker-input" id="reschedule_date" placeholder="Select Date" value="{{$foramted_appointment_date}}" />
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="">Reason of Reschedule</label>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="" name="reason" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <ul class="availability-indicator">
                                        <li>
                                            <span class="not-available-color"></span> Not Available
                                        </li>
                                        <li>
                                            <span class="available-color"></span> Available
                                        </li>
                                        
                                        
                                        <li>
                                            <span class="selected-color"></span> Selected
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                <label class="form-label" for="username">Select Slot</label>
                                    <div class="timeslot-selector timeslot-selector-modal">
                                    @for($i = 0; $i < count($time_slot); $i++)
                                        <span>
                                            <input type="radio" class="availiblity" id="sat{{$i+17+$i}}" disabled name="booking_time_slot" {{($row->booking_time_slot ?? null) == $time_slot[$i] ? 'checked' : ''}}  value="{{$time_slot[$i]}}" class="idReschedule time-slot checkbx-style">
                                                <label for="sat{{$i+17+$i}}">{{$time_slot[$i]}}</label>
                                        </span>
                                            </span>
                                        @endfor


                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="confirm_reschedule_appointment">Confirm Reschedule</button>
                    </div>
                    </div>
                </div>
            </div>
@endsection

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
@stop
@section('custom_js')
<script>

    function reformatDate(dateStr) {
        const [day, month, year] = dateStr.split('-');
        return `${year}-${month}-${day}`;
    }

    function checkAvailibility(doctor_id, date, selectedTime = null){
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('web/appointments/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                    'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        updateSlots(res.oData.list, date, selectedTime);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }
    
    function updateSlots(slots, selectedDate, selectedTime = null) {
        $('.availiblity').prop('disabled', true);
        $('.availiblity').prop('checked', false);

        if (slots) {
            const currentDate = new Date();
            const currentDateString = currentDate.toISOString().split('T')[0];
            const currentHours = currentDate.getHours();
            const currentMinutes = currentDate.getMinutes();

            const selectedDateFormatted = reformatDate(selectedDate);
            $('.availiblity').each(function() {
                var slot = $(this).val();
                var [slotHours, slotMinutes] = slot.split(':').map(Number);

                var isAvailable = slots.find(s => s.slot_text === slot)?.is_available === "1";
                var isSlotEnabled = isAvailable;

                if (selectedDateFormatted === currentDateString) {
                    // Disable slot if it is earlier than the current time
                    if (slotHours < currentHours || (slotHours === currentHours && slotMinutes < currentMinutes)) {
                        isSlotEnabled = false;
                    }
                }

                $(this).prop('disabled', !isSlotEnabled);
                $(this).prop('checked', selectedTime == slot);
            });
        }
    }

    $("#cancelAppointment").click(function(e){
        e.preventDefault();
        let form = $('#cancelAppointment_form')[0];
        let data = new FormData(form);
       
         $.ajax({
           url: "{{ url('web/patient/patient_appointment_cancel') }}",
           type: "POST",
           data : data,
           dataType:"JSON",
           processData : false,
           contentType:false,      
           success: function(response) {
                $('#cancel-appointment').modal('hide');
                App.alert(response.message || 'Appointment Canceled Successfully', 'Success!','success');
                setTimeout(function() {
                    window.location.href = "{{url('/website/patient-appointment')}}";
                }, 1500);
           
           },
           error: function(xhr, status, error) {
                App.alert(response.message || 'Cannot Cancel this Appointment', 'Fail!','error');
           }
    
        });
    
    });

    $('#reschedule_date, #doctorIdReschedule').on("change", function () {
        checkAvailibility($('#doctorIdReschedule').val(), $('#reschedule_date').val());
    });

    $('#reschedule-modal #confirm_reschedule_appointment').on('click', function(e) {
        e.preventDefault();
        
        var $form = $('#reschedule_appointment_form');
        var formData = new FormData($form[0]);
        let i = 0;
        App.setJQueryValidationRules('#reschedule_appointment_form');

        form_in_progress = 1;
        App.loading(true);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: $form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: 'html',
            success: function(res) {
                $('#reschedule-modal').modal('hide');
                res = JSON.parse(res);
                form_in_progress = 0;
                App.loading(false);

                if (res['status'] == 0) {
                    $form.find('[type="submit"]').prop("disabled", false).text("Save");
                    
                    if (typeof res['errors'] !== 'undefined') {
                        var error_def = $.Deferred();
                        var error_index = 0;

                        jQuery.each(res['errors'], function(e_field, e_message) {
                            if (e_message != '') {
                                $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                if (error_index == 0) {
                                    error_def.resolve();
                                }
                                error_index++;
                            }
                        });

                        error_def.done(function() {
                            var error = $form.find('.is-invalid').eq(0);
                            $('html, body').animate({
                                scrollTop: (error.offset().top - 100),
                            }, 500);
                        });
                    } else {
                        var m = res['message'] || 'Unable to save variation. Please try again later.';
                        App.alert(m, 'Oops!', 'error');
                    }
                } else {
                    App.alert(res['message'] || 'Appointment Rescheduled Successfully', 'Success!', 'success');
                    // console.log(res, 'res');
                    setTimeout(function() {
                        window.location.href = res['oData']['redirect'];
                    }, 1500);
                }
            },
            error: function(e) {
                form_in_progress = 0;
                App.loading(false);
                $form.find('[type="submit"]').prop("disabled", false).text("Save");
                console.log(e);
                App.alert("Network error please try again", 'Oops!', 'error');
            }
        });
    });
    
        $(document).ready(function() {
            var table = $('#table_list').DataTable({
                processing: true,
                serverSide: true,
                filter: true,
                searching: true,
                ajax: {
                    'type': 'POST',
                    'url': '{{ route("patients.MyAppointmentLoadData") }}',
                    'data': function(d) {
                        // Send additional parameters to the server
                        d._token = '{{ csrf_token() }}';
                        d.patient_id = '{{ Auth::User()->id }}';
                        d.search['filters'] = $('#search-form').serializeArray().reduce(function(obj, item) {
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
                    }
                },
                columns: [
                    { data: 'sl_no', orderable: false, searchable: false },
                    { data: 'booking_id', name: 'booking_id'},
                    { data: 'dr_name', name: 'doctorUsers.name'},
                    { data: 'booking_time_slot', name: 'booking_time_slot'},
                    { data: 'booking_status', name: 'booking_status'},
                    { data: 'booking_date', name: 'booking_date'},
                    { data: 'action', orderable: false, searchable: false }
                ],
                // dom: 'Bfrtip',
                // buttons: [
                        
                //         {
                //             extend: 'excelHtml5',
                //             text: '<i class="mdi mdi-file-excel"></i>',
                //             titleAttr: 'Excel'
                //         },
                //     ],
                    lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    pageLength: 10,
                    order: [],
                    language: {
                        loadingRecords: "No Data Available",
                    },
                    
            });

            // Implement search functionality
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Optional: Clear the search form
            $('#clear-search').on('click', function(e) {
                e.preventDefault();
                let form = $('#search-form')[0];    
                form.reset();  // Reset the search form
                $(form).find('select').prop('selectedIndex', 0);
            
                $(form).find('select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).val(null).trigger('change');
                    }
                });
                table.ajax.reload();
            });
            
        });

        // Handle signup form submission
        // $('#update-profile-form').on('submit', function(event) {
        //     event.preventDefault(); // Prevent default form submission
        //     var $form = $(this);
        //     var formData = new FormData(this);
        //     $form.find('button[type="submit"]').text('Processing..').attr('disabled', true);

        //     $.ajax({
        //         url: $(this).attr('action'),
        //         type: $(this).attr('method'),
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             $form.find('button[type="submit"]').text('Update').attr('disabled', false);
        //             // console.log(JSON.parse(response));
        //             console.log(response);
        //             if(response.success == "1"){
        //                 App.alert(response.message || 'Profile Updated successfully', 'Success!','success');
        //                 setTimeout(function () {
        //                     window.location.href = "{{url('/website/patient-profile')}}";
        //                 }, 1500);
        //             }else{
        //                 App.alert(response.message || 'Failed to Update Profile', 'Fail!','error');
        //                 if(response.errors){
        //                     jQuery.each(response.errors, function (e_field, e_message) {
        //                         if (e_message != '') {
        //                             $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
        //                             $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
        //                             $('<div class="invalid-feedback">' + e_message + '</div>')
        //                                 .insertAfter($('[name="' + e_field + '"]').eq(0));
        //                             $('<div class="invalid-feedback">' + e_message + '</div>')
        //                                 .insertAfter($('[name="' + e_field + '[]"]').eq(0));
        //                         }
        //                     });
        //                 }
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             $form.find('button[type="submit"]').text('Update').attr('disabled', false);
        //             App.alert('Something Went wrong', 'Fail!','error');
        //         }
        //     });
        // });

        $(document).ready(function() {
            checkAvailibility('{{$appointment->doctor->user_id}}', '{{$foramted_appointment_date}}', '{{$appointment->booking_time_slot}}');
        });
        
        // function lodSubIncurance(incuranceId){
        //     if (incuranceId) {
        //         $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
        //         $.ajax({
        //             type: "GET",
        //             url: "{{ url('get-sub-insurance') }}/" + incuranceId,
        //             success: function (res) {
        //                 if (res) {
        //                     $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
        //                     $.each(res, function (index, data) {
        //                         $('#sub-insurance-policy').append('<option value="' + data.id+'">' + data.title + '</option>');
        //                     });
        //                     // $('#sub-insurance-policy').val(selectedId).trigger('change');
        //                     $('#sub-insurance-policy').select2(); // Reinitialize select2
        //                 }
        //             },
        //             error: function (xhr, status, error) {
        //                 console.error('Error fetching Members:', error);
        //             }
        //         });
        //     }else {
        //         $('#sub-insurance-policy').empty();
        //         $('#sub-insurance-policy').append('<option value=""></option>');
        //     }
        // }

        // $('#insurance-policy').on('change', function(){
        //     lodSubIncurance($(this).val());
        // })
        
    </script>
@endsection