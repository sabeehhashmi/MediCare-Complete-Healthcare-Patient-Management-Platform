@include('agent.layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">

    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
  


<div class="position-relative mb-5">
    
    <div class="card">
    <div class="col-lg-12 mt-3 me-auto">
            <a  href=" {{route('admin.agents.index')}}"  class="btn btn-primary float-end">Back</a>
            </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Booking Id: <span class="text-primary">{{$users[0]['booking_id']}}</span></h5>
            <div class="status-badge pending-badge">
                                            <span></span> {{$users[0]['booking_status']}}
                                        </div>
        </div>
    <div class="card-body">
        <div class="">
            <div class="row">
               

                <div class="col-12">
                    <div class="card border-0 p-0 overflow-hidden shadow-none">
                        <div class="mail-list">
                        <a href="#!">
                                    <h4 class="font-size-16 mb-3">Rescheduled Date & Time</h4>
                                    <div class="d-flex align-items-center">
                                        <span class="icn-bx me-3">
                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                        </span>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-2 font-size-18">{{$users[0]['booking_date']}}</h4>
                                            <h5 class="font-size-14">{{$users[0]['booking_time_slot']}}</h5>
                                        </div>
                                    </div>
                                </a>
                                <hr />
                                <a href="#!">
                                    <h4 class="font-size-16 mb-3"> Date & Time</h4>
                                    <div class="d-flex align-items-center">
                                        <span class="icn-bx me-3">
                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                        </span> 
                                        <div class="flex-grow-1">
                                            <h4 class="mb-2 font-size-18">{{$users[0]['previous_booking_date']}}</h4>
                                            <h5 class="font-size-14">{{$users[0]['previous_booking_time_slot']}}</h5>
                                        </div>
                                    </div>
                                </a>
                                <hr />
                                <a href="#!">
                                    <h4 class="font-size-16 mb-3">Clinic</h4>
                                    <div class="d-flex align-items-center">
                                        <span class="icn-bx me-3">
                                            <i class="bx bx-map font-size-20 align-middle"></i>
                                        </span>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-2 font-size-18">{{$users[0]['name_en']}}</h4>
                                            <h5 class="font-size-14">{{$users[0]['address']}}</h5>
                                        </div>
                                        <div class="flex-shrink-0"></div>
                                    </div>
                                </a>

                                <hr />

                                <a href="#!">
                                    <h4 class="font-size-16 mb-3">Patient Details</h4>
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <img style="width: 80px; height: 80px; border-radius: 5px;" src="{{empty($users[0]['user_img_url']) ? asset('admin-assets/assets/images/doctor_placeholder.jpg'): get_uploaded_image_url($users[0]['user_img_url'],'user_image_upload_dir')}}" alt="Patient  placeholder image" />
                                        </div>
                                        <div>
                                            <div class="flex-grow-1 mb-2">
                                                <h4 class="mb-2 font-size-18">{{$users[0]['first_name']}} </h4>
                                                <h5 class="mb-0 font-size-14"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                            </div>
                                            <h5 class="mb-2 font-size-14"><i class="bx bx-calendar me-2"></i> {{$users[0]['dob']}}</h5>
                                            <h5 class="mb-2 font-size-14"><i class="fas fa-transgender me-2"></i>
                                            @if($users[0]['gender'] === 1)
                                            Male
                                            @elseif($users[0]['gender'] === 2)
                                            Female
                                            @else

                                            @endif
                                            {{$users[0]['gender']}}
                                            </h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bx-envelope me-2"></i> {{$users[0]['email']}}</h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bxs-phone-call me-2"></i> +{{$users[0]['dial_code']}} {{$users[0]['phone']}}</h5>
                                            <h5 class="mb-0 font-size-14"><i class="bx bxl-whatsapp-square me-2"></i>+{{$users[0]['whatsap_dial_code']}}{{$users[0]['whatsap_phone']}}</h5>
                                            @if($users[0]['booking_status']== 'Cancelled')
                                            <p>Reason Cancel: {{$users[0]['reason_cancel']}}</p>
                                            @elseif($users[0]['booking_status']== 'Rescheduled')
                                                <p>Reason Rescheduled: {{$users[0]['reason_reschedule']}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <hr>
                                <a href="#!">
                                        <h4 class="font-size-16 mb-3">Doctor Details</h4>
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <img style="width: 80px; height: 80px; border-radius: 5px;" src="assets/images/doctor.png" alt="Generic placeholder image" />
                                            </div>
                                            <div>
                                                <div class="flex-grow-1 mb-2">
                                                    <h4 class="font-size-18 mb-2">{{$doctor->name}}</h4>
                                                    <h5 class="mb-0 font-size-14"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                                </div>
                                                <h5 class="mb-2 font-size-14"><i class="fas fa-transgender me-2"></i>
                                            @if($users[0]['gender'] === 1)
                                            Male
                                            @elseif($users[0]['gender'] === 2)
                                            Female
                                            @else

                                            @endif
                                            {{$users[0]['gender']}}
                                            </h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bx-envelope me-2"></i> {{$users[0]['email']}}</h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bxs-phone-call me-2"></i> +{{$users[0]['dial_code']}} {{$users[0]['phone']}}</h5>
                                            <h5 class="mb-0 font-size-14"><i class="bx bxl-whatsapp-square me-2"></i>+{{$users[0]['whatsap_dial_code']}}{{$users[0]['whatsap_phone']}}</h5>
                                            </div>
                                        </div>
                                    </a>

                                    <hr />

                                <a href="#!">
                                    <h4 class="font-size-16 mb-3">Agent Details</h4>
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <img style="width: 80px; height: 80px; border-radius: 5px;" src="assets/images/agent.jpeg" alt="Generic placeholder image" />
                                        </div>
                                        <div>
                                            <div class="flex-grow-1 mb-2">
                                                <h4 class="font-size-18 mb-2">{{$agent->user->name}}</h4>
                                                <h5 class="mb-0 font-size-14"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                            </div>
                                            <h5 class="mb-2 font-size-14"><i class="fas fa-transgender me-2"></i>
                                            @if($agent->user->gender === "1")
                                            Male
                                            @elseif($agent->user->gender === 2)
                                            Female
                                            @else

                                            @endif
                                            
                                            </h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bx-envelope me-2"></i> {{$agent->user->email}}</h5>
                                            <h5 class="mb-2 font-size-14"><i class="bx bxs-phone-call me-2"></i> +{{$agent->user->dial_code}} {{$agent->user->phone}}</h5>
                                            <h5 class="mb-0 font-size-14"><i class="bx bxl-whatsapp-square me-2"></i>+{{$agent->user->whatsap_dial_code}}{{$agent->user->whatsap_phone}}</h5>
                                        </div>
                                    </div>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
        </div>

        

        <hr />

        <div class="appointment-status-btns">
            <button type="button" class="reset-modal btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#confirm-appointment">Confirm</button>
            <button type="button" class="reset-modal btn btn-dark waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#cancel-appointment">Cancel</button>
            <button type="button" class="reset-modal btn btn-outline-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#reschedule-modal">Reschedule</button>
            <!-- <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#followup-modal">Follow Up</button>
            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#completed-appointment">Completed</button> -->
        </div>
</div>


           
        </div>
    </div>
</div>
<!-- End Page-content -->

<!-- Modal -->
<div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="composemodalTitle">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="To" />
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Subject" />
                    </div>
                    <div class="mb-3">
                        <div id="email-editor"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send <i class="fab fa-telegram-plane ms-1"></i></button>
            </div>
        </div>
    </div>
</div>
      <!-- Modal -->
      <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- {{$users[0]['booking_id']}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="cancelAppointment_form" class="custom-form">
                        @csrf 
                            <div class="row">
                            <input type="hidden" id="idCancel" value="{{$users[0]['id']}}" name="appointment_id">    
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
                        <button type="button"  id="cancelViewAppointment" class="btn btn-dark" style="width: 120px;">Cancel</button>
                    </div>
                </div>
                
            </div>
        </div>

       


    

      
     <!-- Modal -->
<div class="modal fade" id="confirm-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel1">Confirm Appointment- {{$users[0]['booking_id']}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action=""  id="confirmAppointment_form" class="custom-form">
                        @csrf 
                            <div class="row">
                            <input type="hidden" id="idConfirmed" value="{{$users[0]['id']}}" name="appointment_id">    
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
                        <button type="button" id="confirmViewAppointment" class="btn btn-primary">Confirm Appointment</button>
                    </div>
                </div>
                
            </div>
        </div>
<div class="modal fade"  id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel3">Reschedule Booking {{$users[0]['booking_id']}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="confirmReschedule_form" class="custom-form">
                    @csrf 
                        <div class="row">
                        <input type="hidden" id="idReschedule" value="{{$users[0]['id']}}" name="appointment_id">    
                        <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="booking_date" value="{{$users[0]['booking_date']}}" class="form-control flatpicker-input" id="" placeholder="Select Date" />
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
                                               
                                            <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot"  value="{{$time_slot[$i]}}"class="time-slot checkbx-style" 
                                            @if(is_array($booking_time_slot) && in_array($time_slot[$i], $booking_time_slot))
                                      checked 
                                     @endif />
                                            
                                              
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
                    <button type="button" id="confirmViewReschedule" class="btn btn-primary">Confirm Reschedule</button>
                </div>
                </div>
            </div>
        </div>

        @include('agent.layouts.footer')


        <script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>


<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->
   <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

     <script>
   
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
   // $("#base-style").DataTable();
    function passDataToViewModel(param1, param2 ){
        console.log(param1, param2);
        document.getElementById("idCancel").value = param2;
   document.getElementById("exampleModalLabel").innerText = "Cancel Appointment- " + param1;
 
   document.getElementById("idReschedule").value = param2;
   document.getElementById("exampleModalLabel3").innerText = "Reschedule Booking- " + param1;
 
   
   document.getElementById("idConfirmed").value = param2;
   document.getElementById("exampleModalLabel1").innerText = "Confirm Appointment- " + param1;   
}
  
$("#cancelViewAppointment").click(function(e){
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
$("#confirmViewAppointment").click(function(e){
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
$("#confirmViewReschedule").click(function(e){
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
</script>
