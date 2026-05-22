@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    @stop


@section("content")

<div class="card mb-5">
    @if(get_user_permission('doctors','c'))
    <div class="card-header">

    <div class="col-lg-12 mb-5">
            <a  href=" {{route('admin.doctors.index')}}"  class="btn btn-primary float-end">Back</a>
            </div>
            @if(!empty($doctor_id))
    <a  data-bs-toggle="modal" data-bs-target="#appointment-modal" class="reset-modal btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i>Make an Appointment</a></div>
  @endif
    @endif


    <div class="row align-items-end mt-3 ms-4"">
        <div class="col-lg-3 col-md-6 mb-3">
            <label class="form-label" for="username">From</label>
            <div class="position-relative input-custom-icon">
                <input type="text" class="form-control flatpicker-input" id="" placeholder="From" />
                <span class="bx bx-calendar-event"></span>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <label class="form-label" for="username">To</label>
            <div class="position-relative input-custom-icon">
                <input type="text" class="form-control flatpicker-input" id="" placeholder="To" />
                <span class="bx bx-calendar-event"></span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <label class="form-label" for="username">Booking Status</label>
            <div class="position-relative select-custom-icon">
                <select name="" id="" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Type">
                    <option></option>
                    <option value="1">Pending</option>
                    <option value="2">Confirmed</option>
                    <option value="3">Cancelled</option>
                    <option value="4">Rescheduled</option>
                    <option value="5">Completed</option>
                </select>
                <i class="bx bx-calendar-event"></i>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-between">
                <div class="mt-3 mt-md-0 mb-3 me-3">
                    <button type="button" class="btn btn-success">Search</button>
                </div>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Booking Id</th>
                <th>Patient Name</th>
                <th>Time Slot</th>
                <th>Booking Status</th>
                <th>Booking Date</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        </div>
    </div>
</div>

<div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <form method="post" action="{{route('admin.doctors.patienttAppointmentSave')}}"  class="custom-form"> -->
                    <form method="post"  id="company_form" class="custom-form">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{$doctor_id}}" id="doctor_id" name="doctor_id">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Select Patient </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="patient_id" id="PatientSelct" class="select2-single jqv-input" data-jqv-required="true" data-placeholder="Select Patient">
                                    @foreach($patient as $patient)
                                    <option></option>
                                        <option value="{{$patient->id}}">{{$patient->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="username">Select Patient</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="booking_date" value="" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="">Reason of Reschedule</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <textarea class="form-control" id="" name="" rows="2"></textarea>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-12">
                                <ul class="availability-indicator">
                                    <li class="form-check ">
                                        <span class="not-available-color"></span> Not Available
                                        <!-- <input type="radio" class="form-check-input not-available-color" id="radio1" name="booking_availability" value="not_available" checked>Not Available
  <label class="form-check-label" for="radio1"></label> -->
                                    </li>
                                    <li class="form-check  ">
                                        <span class="available-color"></span> Available
                                        <!-- <input type="radio" class="form-check-input available-color" id="radio2" name="booking_availability" value="available">Available
  <label class="form-check-label" for="radio2"></label> -->
                                    </li>


                                    <li class="form-check ">
                                        <span class="selected-color"></span> Selected
                                        <!-- <input type="radio" class="form-check-input available-color" id="radio3" name="booking_availability" value="selected">Selected
  <label class="form-check-label"></label> -->
                                    </li>
                                    <!-- <div class="form-check">

</div>
<div class="form-check">

</div>
<div class="form-check">

</div> -->
                                </ul>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="username">Select Slot</label>
                                    <div class="timeslot-selector timeslot-selector-modal">
                                    @for($i = 0; $i < count($time_slot); $i++)
                                        <span>

                                            <input type="radio" id="sat{{$i*10+91+$i*10}}"  name="booking_time_slot"  value="{{$time_slot[$i]}}"class="time-slot checkbx-style" />

                                                <label for="sat{{$i*10+91+$i*10}}">{{$time_slot[$i]}}</label>
                                    </span>
                                            </span>
                                     @endfor

                                    <!-- <span>
                                        <input type="radio" id="sat91" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat91">08:00</label>
                                    </span> -->

                                    <!-- <span>
                                        <input type="radio" id="sat101" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat101">08:15</label>
                                    </span> -->


                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="submit" id="patientAppointment"  class="btn btn-success" data-bs-dismiss="modal">Confirm</button>
                </div>
                </form>
                </div>
            </div>
        </div>
       <!-- Modal -->
       <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="cancelAppointment_form" class="custom-form">
                        @csrf
                            <div class="row">
                            <input type="hidden" id="idCancel" value="" name="appointment_id">
                                <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/cancel-img.svg" class="img-fluid" alt="">
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
                        <button type="button"  id="cancelAppointment" class="btn btn-dark" style="width: 120px;">Cancel</button>
                    </div>
                </div>

            </div>
        </div>




        <div class="modal fade" id="confirm-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel1">Confirm Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action=""  id="confirmAppointment_form" class="custom-form">
                        @csrf
                            <div class="row">
                            <input type="hidden" id="idConfirmed" value="" name="appointment_id">
                                <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/success-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to confirm the appointment.</p>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" id="confirmAppointment" class="btn btn-primary">Confirm Appointment</button>
                    </div>
                </div>

            </div>
        </div>



        <div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel2">Complete Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action=""  id="completed_form" class="custom-form">
                        @csrf
                            <div class="row">
                            <input type="hidden" id="idCompleted" value="" name="appointment_id">
                            <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/success-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to complete the appointment.</p>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" id="completed" class="btn btn-primary">Completed</button>
                    </div>
                </div>

            </div>
        </div>
     <!-- Modal -->
     <div class="modal fade"  id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel3">Reschedule Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="confirmReschedule_form" class="custom-form">
                    @csrf
                        <div class="row">

                        <input type="hidden" id="idReschedule" value="" name="appointment_id">
                        <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="booking_date" id="reschedule_booking_date" value="" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Reason of Reschedule</label>
                                <div class="position-relative">
                                    <textarea class="form-control" id="" name="reason_reschedule"  value ="" rows="2"></textarea>
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

                                            <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot"  value="{{$time_slot[$i]}}"class="idReschedule time-slot checkbx-style"

                                            />

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
                    <button type="button" id="confirmReschedule" class="btn btn-primary">Confirm Reschedule</button>
                </div>
                </div>
            </div>
        </div>

@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>


<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->
   <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

     <script>
   function passDataToCancelModel(param1, param2 ){

    document.getElementById("idCancel").value = param2;
    document.getElementById("exampleModalLabel").innerText = "Cancel Appointment- " + param1;

    }
    function passDataToCompletedModel(param1, param2 ){


    document.getElementById("idCompleted").value = param2;
    document.getElementById("exampleModalLabel2").innerText = "Complete Appointment- " + param1;
    }
    function passDataToConfirmModel(param1, param2 ){
        console.log("sdfsd",param2);
    document.getElementById("idConfirmed").value = param2;
    document.getElementById("exampleModalLabel1").innerText = "Confirm Appointment- " + param1;
    }
    function passDataToRescheduleModel(param1, param2 ,param3,param4){

console.log("sdfsd",param3);

document.getElementById("reschedule_booking_date").value = param4;
    document.getElementById("idReschedule").value = param2;
    var radioButtons = document.getElementsByClassName("idReschedule");

    for (var i = 0; i < radioButtons.length; i++) {

        if (radioButtons[i].value === param3) {
            radioButtons[i].checked = true;
        }
    }
    document.getElementById("exampleModalLabel3").innerText = "Reschedule Booking- " + param1;
    }
    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today",
    });



    // $(".flatpicker-input-date-time").flatpickr({
    //     minDate: "today",
    //     enableTime: true,
    //     dateFormat: "d-m-Y H:i"
    // });

    $(".flatpicker-input-date-time").flatpickr({
        minDate: "today",
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        onReady: function(selectedDates, dateStr, instance) {
                // Create OK button
                var okButton = document.createElement("button");
                okButton.innerText = "OK";
                okButton.classList.add("btn", "btn-primary", "ms-2");

                // Add click event to OK button
                okButton.addEventListener("click", function() {
                    instance.close();
                });

                // Create Clear button
                var clearButton = document.createElement("button");
                clearButton.innerText = "Clear";
                clearButton.classList.add("btn", "btn-outline-secondary"
                , "waves-effect",  "waves-light");

                // Add click event to Clear button
                clearButton.addEventListener("click", function() {
                    instance.clear();
                    // instance.close();
                });

                // Append OK and Clear buttons to flatpickr calendar
                var buttonContainer = document.createElement("div");
                buttonContainer.classList.add("flatpickr-button-container", "d-flex", "justify-content-end", "px-3", "pb-2");
                buttonContainer.appendChild(clearButton);
                buttonContainer.appendChild(okButton);

                instance.calendarContainer.appendChild(buttonContainer);
        }
    });

    $(".flatpicker-input-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
    $(".flatpicker-input-multiple").flatpickr({
        dateFormat: "d-m-Y",
        mode: "multiple",
        minDate: "today"
    });
    $(document).ready(function() {
        $('.select2-single').select2({
            placeholder: $(this).data('placeholder'),

        });
        $("#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
    });
    </script>
<script>
$(document).ready(function () {
     var value = $("#doctor_id").val();
   //  alert(value);
    $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        searching:true,
        ajax: {
            'type':'POST',
            'url' : '{{ route("admin.doctors.appointmentLoadData") }}',
            'data':{
                '_token': '{{csrf_token()}}',
                'doctor_id': value
            }
        },
        columns: [
            {data:'sl_no'},
            {data: 'booking_id'},
            {data: 'name'},
            {data:'booking_time_slot'},
            {data: 'booking_status'},
            {data: 'booking_date'},
            {data: 'action',  orderable: false, searchable: false}
        ],
        order: [],
        language: {
            loadingRecords: "No Data Available",
        },
    });

    // Implement search functionality
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        $('#table_list').DataTable().search($(this).serialize()).draw();
    });
});
    </script>
    <script>
        App.initFormView();
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
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
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/admin_users');
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });

        $("#patientAppointment").click(function(e){
     e.preventDefault();
     let form = $('#company_form')[0];
     let data = new FormData(form);

      $.ajax({
        url: "{{ route('admin.doctors.patienttAppointmentSave') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();
    //     if (response.errors) {
    //         var errorMsg = '';
    //         $.each(response.errors, function(field, errors) {
    //             $.each(errors, function(index, error) {
    //                 errorMsg += error + '<br>';
    //             });
    //         });
    //         iziToast.error({
    //             message: errorMsg,
    //             position: 'topRight'
    //         });

    //     } else {
    //        iziToast.success({
    //        message: response.success,
    //        position: 'topRight'

    //              });
    //     }

    //
 },
    error: function(xhr, status, error) {

        // iziToast.error({
        //     message: 'An error occurred: ' + error,
        //     position: 'topRight'
        // });
    }

      });

})
$("#cancelAppointment").click(function(e){
     e.preventDefault();
     let form = $('#cancelAppointment_form')[0];
     let data = new FormData(form);

      $.ajax({
        url: "{{ route('admin.doctors.patienttAppointmentCancel') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();
    //     if (response.errors) {
    //         var errorMsg = '';
    //         $.each(response.errors, function(field, errors) {
    //             $.each(errors, function(index, error) {
    //                 errorMsg += error + '<br>';
    //             });
    //         });
    //         iziToast.error({
    //             message: errorMsg,
    //             position: 'topRight'
    //         });

    //     } else {
    //        iziToast.success({
    //        message: response.success,
    //        position: 'topRight'

    //              });
    //     }

    //
 },
    error: function(xhr, status, error) {

        // iziToast.error({
        //     message: 'An error occurred: ' + error,
        //     position: 'topRight'
        // });
    }

      });

})
$("#confirmAppointment").click(function(e){
     e.preventDefault();
     let form = $('#confirmAppointment_form')[0];
     let data = new FormData(form);

      $.ajax({
        url: "{{ route('admin.doctors.patienttAppointmentConfirmed') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();
    //     if (response.errors) {
    //         var errorMsg = '';
    //         $.each(response.errors, function(field, errors) {
    //             $.each(errors, function(index, error) {
    //                 errorMsg += error + '<br>';
    //             });
    //         });
    //         iziToast.error({
    //             message: errorMsg,
    //             position: 'topRight'
    //         });

    //     } else {
    //        iziToast.success({
    //        message: response.success,
    //        position: 'topRight'

    //              });
    //     }

    //
 },
    error: function(xhr, status, error) {

        // iziToast.error({
        //     message: 'An error occurred: ' + error,
        //     position: 'topRight'
        // });
    }

      });

})
$("#confirmReschedule").click(function(e){
     e.preventDefault();
     let form = $('#confirmReschedule_form')[0];
     let data = new FormData(form);

      $.ajax({
        url: "{{ route('admin.doctors.patienttAppointmentRescheduled') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();
    //     if (response.errors) {
    //         var errorMsg = '';
    //         $.each(response.errors, function(field, errors) {
    //             $.each(errors, function(index, error) {
    //                 errorMsg += error + '<br>';
    //             });
    //         });
    //         iziToast.error({
    //             message: errorMsg,
    //             position: 'topRight'
    //         });

    //     } else {
    //        iziToast.success({
    //        message: response.success,
    //        position: 'topRight'

    //              });
    //     }

    //
 },
    error: function(xhr, status, error) {

        // iziToast.error({
        //     message: 'An error occurred: ' + error,
        //     position: 'topRight'
        // });
    }

      });

})
$("#completed").click(function(e){
     e.preventDefault();
     let form = $('#completed_form')[0];
     let data = new FormData(form);

      $.ajax({
        url: "{{ route('admin.doctors.patienttAppointmentCompleted') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();
    //     if (response.errors) {
    //         var errorMsg = '';
    //         $.each(response.errors, function(field, errors) {
    //             $.each(errors, function(index, error) {
    //                 errorMsg += error + '<br>';
    //             });
    //         });
    //         iziToast.error({
    //             message: errorMsg,
    //             position: 'topRight'
    //         });

    //     } else {
    //        iziToast.success({
    //        message: response.success,
    //        position: 'topRight'

    //              });
    //     }

    //
 },
    error: function(xhr, status, error) {

        // iziToast.error({
        //     message: 'An error occurred: ' + error,
        //     position: 'topRight'
        // });
    }

      });

})
    </script>
@stop
