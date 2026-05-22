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
    @if(get_user_permission('patients','c'))
    <div class="card-header">
    <div class="card-header"><a href="{{route('admin.hospitals.create_appointment', $hospital_id)}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Book an Appointment </a></div>
    @endif

    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Booking Id</th>
                <th>Doctor Name</th>
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
    var table = $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        searching:true,
        ajax: {
            'type':'POST', 
            'url' : '{{ route("admin.hospitals.appointmentLoadData") }}',
            'data':{
                '_token': '{{csrf_token()}}',
                'hospital_id': '{{$hospital_id ?? null}}'
            }
        },
        columns: [
            {data:'sl_no'},
            {data: 'booking_id'},
            {data: 'dr_name'},
            {data: 'patient_name'},
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

    $(document).on('click', '.delete-appointment', function() {
        var id = $(this).data('id');
        var msg = 'Are you sure you want to delete this appointment?';
        var href = '{{ url("admin/patients/delete-appointment") }}/' + id;

        App.confirm('Confirm Delete', msg, function() {
            var ajxReq = $.ajax({
                url: href,
                type: 'DELETE',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.status == 1) {
                        App.alert(res.message || 'Deleted successfully', 'Success!');
                        setTimeout(function() {
                            // Destroy and reload the datatable to reflect the changes
                            $('#table_list').DataTable().destroy();
                            location.reload();
                        }, 1500);
                    } else {
                        App.alert(res.message || 'Unable to delete the record.', 'Failed!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    App.alert('An error occurred while trying to delete the appointment.', 'Error');
                }
            });
        });
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