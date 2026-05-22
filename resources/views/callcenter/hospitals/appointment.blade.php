@include('callcenter.layouts.header')
<div class="mb-5 position-relative">
<div class="card">
    <div class="card-body">
        <div class="position-relative">
            <div class="d-flex gap-2 justify-content-between mb-3">
            <div class="btn-group toggle-btn-g-tabs-cale" role="group" aria-label="Basic checkbox toggle button group">
                            <input type="radio" class="btn-check" name="scs" id="btncheck4" autocomplete="off" checked />
                            <label class="btn btn-outline-primary" for="btncheck4"><img class="icn" src="{{ asset('') }}hospital/assets/images/table-view.png" alt="" /></label>

                            <input type="radio" class="btn-check" name="scs" id="btncheck5" autocomplete="off" />
                            <label class="btn btn-outline-primary" for="btncheck5"><img class="icn" src="{{ asset('') }}hospital/assets/images/calender-icon.png" alt="" /></label>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointment-modal">Make an Appointment</button>
            </div>
            <form id="appointment-filter-form">
            <div class="row align-items-end">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="from_date">From</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input" id="from_date" name="from_date" placeholder="From" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="to_date">To</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input" id="to_date" name="to_date" placeholder="To" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="booking_status">Booking Status</label>
                    <div class="position-relative select-custom-icon">
                        <select name="booking_status" id="booking_status" class="select2-single" data-placeholder="Select Type">
                            <option></option>
                            <option value="pending" {{ old('booking_status', request('booking_status')) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Confirmed" {{ old('booking_status', request('booking_status')) == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="Cancelled" {{ old('booking_status', request('booking_status')) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="Rescheduled" {{ old('booking_status', request('booking_status')) == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            <option value="Completed" {{ old('booking_status', request('booking_status')) == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        <i class="bx bx-calendar-event"></i>
                    </div>
                </div>





        <div class="col-sm">
                    <div class="mt-3 mt-md-0 mb-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="button" class="btn btn-dark waves-effect waves-light" id="reset-filters" >Refresh</button>

                    </div>
                </div>

            </div>
         </form>


            <!-- end row -->
            <div class="table-wrap" id="tableDiv">

                <div class="table-responsive">
                <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Booking Id</th>
                <th>Patient Name</th>
                <th>Time Slot</th>
                <th>Doctor</th>
                <th>Agent</th>
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
            <!-- <div class="calender-wrap" id="calenderDiv">

            </div> -->

            <div class="calender-wrap" id="calenderDiv">
                            <div class="card-h-100 my-3">
                                <div>

                                    <h4  class="mb-3" >Today <span class="text-muted">date</span> </h4>


                                    <div class="single-doctor-appointment mb-4">

                                        <div class="section-area-grid-view d-flex flex-wrap">

                                            <div class="col-slot-item">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal" data-appointment-url="#" data-appointment-dob="12-11-23" data-appointment-gender="male" data-appointment-patientwhatsapp="1234565432" data-appointment-number="21212" data-appointment-bookingtime="12:30" data-appointment-date="11-10-23" data-appointment-booking-id="12" data-appointment-reason="'N/A'" data-user-name="name" data-user-email="email"  class="border-styles completed reset-modal">
                                                    <div>
                                                        <p class="time">Time</p>
                                                        <p class="name">Name</p>
                                                        <span class="status">status</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <hr>
                                <div>

                                    <h4 class="mb-3">Tomorrow <span class="text-muted">data and time</span> </h4>



                                    <div class="single-doctor-appointment mb-4">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="flex-shrink-0 me-3">
                                            <img style="width: 60px; height: 60px; border-radius: 5px;" src="image" alt="Generic placeholder image">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="font-size-20 mb-2"> name</h4>

                                                <h6 class="font-size-13 mb-0 text-primary">specialties</h6>
                                            </div>
                                        </div>
                                        <div class="section-area-grid-view d-flex flex-wrap">


                                            <div class="col-slot-item">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal" data-appointment-url="#" data-appointment-dob="12-11-23" data-appointment-gender="male" data-appointment-patientwhatsapp="1234565432" data-appointment-number="21212" data-appointment-bookingtime="12:30" data-appointment-date="11-10-23" data-appointment-booking-id="12" data-appointment-reason="'N/A'" data-user-name="name" data-user-email="email"  class="border-styles completed reset-modal">
                                                    <div>
                                                        <p class="time">Time</p>
                                                        <p class="name">Name</p>
                                                        <span class="status">status</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div>

                                     <h4 class="mb-3">AfterTomorrow <span class="text-muted">data and time</span> </h4>
                                    <div class="single-doctor-appointment mb-4">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="flex-shrink-0 me-3">
                                            <img style="width: 60px; height: 60px; border-radius: 5px;" src="url" alt="Generic placeholder image">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="font-size-20 mb-2">Name</h4>

                                                <h6 class="font-size-13 mb-0 text-primary">specialties</h6>
                                            </div>
                                        </div>
                                        <div class="section-area-grid-view d-flex flex-wrap">

                                            <div class="col-slot-item">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal" data-appointment-url="#" data-appointment-dob="12-11-23" data-appointment-gender="male" data-appointment-patientwhatsapp="1234565432" data-appointment-number="21212" data-appointment-bookingtime="12:30" data-appointment-date="11-10-23" data-appointment-booking-id="12" data-appointment-reason="'N/A'" data-user-name="name" data-user-email="email"  class="border-styles completed reset-modal">
                                                    <div>
                                                        <p class="time">Time</p>
                                                        <p class="name">Name</p>
                                                        <span class="status">status</span>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="card card-h-100">-->
                            <!--    <div class="card-body">-->
                            <!--        <div id="calendar" style="min-height: 500px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
        </div>
    </div>
</div>

</div>


  <!-- Appointment Modal -->
  <div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="book_appointment_form" action="{{route('callcenter.saveAppointment')}}" class="custom-form">
                    @csrf
                    <div class="row">





                            <input type="hidden" value="{{$hospital_id}}" name="prnt_hospital_id">



                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Department </label>
                                <div class="position-relative">
                                    <select name="department_id" id="DepartmentSelct" class="select2-single" data-placeholder="Select Department ">
                                        <option value=" "></option>
                                        @if(count($departments ?? []))
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Doctor </label>
                                <div class="position-relative">
                                    <select name="doctor_id" id="doctorSelct" class="select2-single" data-placeholder="Select Doctor">
                                        <option value=" "></option>
                                        @if(count($doctors ?? []))
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Patient </label>
                                <div class="position-relative">
                                    <select name="patient" id="PatientSelct" class="select2-single" data-placeholder="Select Patient">
                                        <option></option>
                                        @if(count($patients ?? []))
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" >{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>



                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="booking_date" class="form-control flatpicker-input" id="booking_date_appointment" placeholder="Select Date" />
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

                                    @php
                                    $time_slot = TIME_SLOTS;
                                    @endphp
                                    @for($i = 0; $i < count($time_slot); $i++)
                                        <span>

                                            <input type="radio" class="availiblity" id="det{{$i+17+$i}}" disabled name="booking_time_slot" class="idReschedule time-slot checkbx-style" value="{{$time_slot[$i]}}"/>
                                                <label for="det{{$i+17+$i}}">{{$time_slot[$i]}}</label>
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
                    <button type="button" id="book_appointment" class="btn btn-primary">Confirm</button>
                </div>
                </div>
            </div>
        </div>



@include('callcenter.layouts.footer')
<script>
    $(document).ready(function() {

        var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            // minDate: "today",
            // maxDate: "today",
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
            // maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });




        function loadMember(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-members') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            $('#memberSelct').empty();
                            $('#memberSelct').append('<option value="">Self</option>');
                            $.each(res, function (index, data) {
                                $('#memberSelct').append('<option value="' + data.id+'">' + data.full_name + '</option>');
                            });
                            // $('#memberSelct').val(selectedId).trigger('change');
                            $('#memberSelct').select2({ dropdownParent: "#appointment-modal" }) // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            } else {
                $('#memberSelct').empty();
                $('#memberSelct').append('<option value=""></option>');
            }
        }

        function loadDepartments(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-hospital-departments') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            $('#department').empty();
                            $('#department').append('<option value="">Select Departments</option>');
                            $.each(res, function (index, data) {
                                $('#department').append('<option value="' + data.id+'">' + data.title+ '</option>');
                            });
                            // $('#department').val(selectedId).trigger('change');
                            $('#department').select2({ dropdownParent: "#appointment-modal" }) // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            } else {
                $('#department').empty();
                $('#department').append('<option value=""></option>');
            }
        }

        function loadDoctorsHospital(hospitalId, departmentId){
            if (hospitalId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-hospital-doctors') }}/" + hospitalId,
                    success: function (res) {
                        if (res) {
                            $('#doctorSelct').empty();
                            $('#doctorSelct').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctorSelct').append('<option value="' + data.user_id+'" data-id="'+data.id+'">' + data.user?.name + '</option>');
                            });
                            // $('#doctorSelct').val(selectedId).trigger('change');
                            $('#doctorSelct').select2({ dropdownParent: "#appointment-modal" }) // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#doctorSelct').empty();
                $('#doctorSelct').append('<option value=""></option>');
            }
        }



        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#PatientSelct').on("change", function () {
            loadMember($(this).val());
        });

        // $('#hospital').on("change", function () {
        //     loadDepartments($(this).val())
        //     // loadDoctorsHospital($(this).val())
        // });

        $('#appointment-modal #booking_date_appointment, #appointment-modal #doctorSelct').on("change", function () {
            checkAvailibility2($('#appointment-modal #doctorSelct').val(), $('#appointment-modal #booking_date_appointment').val());
        });

        // checkAvailibility($('#doctor').val(), $('#booking_date').val());


    });

    $(document).ready(function() {
        $('#event-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userName = button.data('user-name');

            var userEmail = button.data('user-email');
            var appointmentDate = button.data('appointment-date');
            var boking_id = button.data('appointment-booking-id');
            var booking_time = button.data('appointment-bookingtime');
            var number = button.data('appointment-number');
            var patientwhatsapp = button.data('appointment-patientwhatsapp');
            var gender = button.data('appointment-gender');
            var dob = button.data('appointment-dob');
            var url = button.data('appointment-url');
            $('#details-link-appointment').attr('href', url);






            // Update the modal content
            $('#modal-user-name').text(userName);
            $('#modal-user-email').text(userEmail);
            $('#modal-appointment-date').text(appointmentDate);
            $('#modal_booking_id').text(boking_id);
            $('#modal-booking-time').text(booking_time);
            $('#modal-patient-whatsapp').text(patientwhatsapp);
            $('#modal-patient-gender').text(gender);
            $('#modal-patient-dob').text(dob);



        });
    });
</script>
<script>

    // $("#example2").DataTable({

    // });
    //  document.addEventListener('DOMContentLoaded', function() {
    function calenderLoad() {
        var calendarEl = document.getElementById("calendar");

        var calendar = new FullCalendar.Calendar(calendarEl, {
            customButtons: {
                myCustomButton: {
                    icon: "bell",
                    click: function () {
                        alert("clicked the custom button!");
                    },
                },
            },
            headerToolbar: {
                left: "prev,today,next",
                // left: 'prevYear,prev,today,next,nextYear myCustomButton',
                center: "title",
                right: "dayGridMonth,timeGridWeek,listMonth",
                // right: 'multiMonthYear,dayGridMonth,timeGridWeek,listMonth'
            },

            eventClick: function (events, jsEvent, view) {
                $("#event-modal").modal("show");
                $("#modal-title").html(events.title);
                $("#modal-start").html(events.start);
                $("#modal-end").html(events.end);
            },

            themeSystem: "bootstrap5",
            // buttonIcons: {
            //   close: 'x-octagon',
            //   prev: 'arrow-left-circle-fill',
            //   next: 'arrow-right-circle-fill',
            //   prevYear: 'arrow-return-left',
            //   nextYear: 'arrow-return-right'
            // },
            initialView: "timeGridWeek", // Set the default view to weekly
            initialDate: "2024-04-13",
            editable: false,
            // render: true,
            dayMaxEvents: true, // allow "more" link when too many events
            navLinks: true,
            eventLimit: true,
            eventTimeFormat: {
                // like '14:30:00'
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            },
            events: [

                {
                    title: "#MYDW1020, Joseph Consultation",
                    start: "2024-04-14T11:00:00",
                    end: "2024-04-14T11:30:00",
                },
                {
                    title: "#MYDW1025, Joseph Consultation",
                    start: "2024-04-15T16:00:00",
                    end: "2024-04-15T16:30:00",
                },



                {
                    title: "#MYDW1032, Ajay Consultation",
                    start: "2024-04-17T11:00:00",
                    end: "2024-04-17T11:30:00",
                },
                {
                    title: "#MYDW1023, Nasar Consultation",
                    start: "2024-04-18T16:00:00",
                    end: "2024-04-18T16:30:00",
                },


                {
                    title: "#MYDW1028, Fairouz Consultation",
                    start: "2024-04-16T09:00:00",
                    end: "2024-04-16T09:30:00",
                },
                {
                    title: "#MYDW1031, John Doe Consultation",
                    start: "2024-04-25T16:00:00",
                    end: "2024-04-25T16:30:00",
                },
                {
                    title: "#MYDW1034, Robert Consultation",
                    start: "2024-04-25T09:00:00",
                    end: "2024-04-25T09:30:00",
                },
                {
                    title: "#MYDW1037, Maria Consultation",
                    start: "2024-04-25T13:00:00",
                    end: "2024-04-25T13:30:00",
                },
                {
                    title: "#MYDW1036, Mark Consultation",
                    start: "2024-04-25T11:00:00",
                    end: "2024-04-25T11:30:00",
                },
            ],
        });

        calendar.render();
        // });
    }

    const btncheck4 = document.getElementById("btncheck4");
    const btncheck5 = document.getElementById("btncheck5");
    const div1 = document.getElementById("tableDiv");
    const div2 = document.getElementById("calenderDiv");
    div2.style.display = "none";

    btncheck4.addEventListener("change", function () {
        if (this.checked) {
            div1.style.display = "block";
            div2.style.display = "none";
        }
    });

    btncheck5.addEventListener("change", function () {
        if (this.checked) {
            div1.style.display = "none";
            // calenderLoad();
            div2.style.display = "block";
        }
    });
</script>

<script>

    function checkAvailibility(doctor_id, date, selectedTime = null){
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('callcenter/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                        'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        updateSlots(res.oData.list, selectedTime);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }

    function updateSlots(slots, selectedTime = null) {
        $('.availiblity').prop('disabled', true);
        $('.availiblity').prop('checked', false);
        if(slots){
            $('.availiblity').each(function() {
                var slot = $(this).val();
                // console.log("slot: ", slot);
                var isAvailable = slots.find(s => s.slot_text === slot)?.is_available === "1";
                $(this).prop('disabled', !isAvailable);
                if(selectedTime){
                    $(this).prop('checked', selectedTime == slot);
                }
            });
        }
    }

    function checkAvailibility2(doctor_id, date){
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('callcenter/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                        'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        updateSlots2(res.oData.list);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }

    function updateSlots2(slots) {
        $('#appointment-modal .availiblity').prop('disabled', true);
        $('#appointment-modal .availiblity').prop('checked', false);

        if (slots && Array.isArray(slots)) {
            $('#appointment-modal .availiblity').each(function() {
                var slot = $(this).val();
                var slotData = slots.find(s => s.slot_text === slot);
                var isAvailable = slotData && slotData.is_available === "1";
                $(this).prop('disabled', !isAvailable);
            });
        }
    }

    $('#reschedule_date, #doctorIdReschedule').on("change", function () {
        checkAvailibility($('#doctorIdReschedule').val(), $('#reschedule_date').val());
    });

    $('.followup-link').click(function() {

        var appointmentId = $(this).data('appointment-id');
        var booking_id = $(this).data('booking-id');
        $('#idCompleted').val(appointmentId);
        $('#completed-appointment .modal-title').text('Complete Booking - ' + booking_id);
    });
    $('.accept-link').click(function() {

        var appointmentId = $(this).data('appointment-id');
        var booking_id = $(this).data('booking-id');
        $('#idConfirmed').val(appointmentId);
        $('#confirm-appointment .modal-title').text('Confirm Booking - ' + booking_id);
    });
     $('.cancel-link').click(function() {

            var appointmentId = $(this).data('appointment-id');
    //console.log(5353534345);
            var booking_id = $(this).data('booking-id');
            $('#idCancel').val(appointmentId);
            $('#cancel-appointment .modal-title').text('Cancel Booking - ' + booking_id);
    });

    $('.reschdule-link').click(function() {

        var booking_data = $(this).data('booking-data');
        var doctor_id = $(this).data('appointment-doctor_id');
        var booking_id = $(this).data('booking-data');
        $('#reschedule-modal, #idReschedule').val(booking_data.id);
        let bookingDate = new Date(booking_data.booking_date);
        let day = String(bookingDate.getDate()).padStart(2, '0');
        let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
        let year = bookingDate.getFullYear();
        let formattedDate = `${day}-${month}-${year}`;
        $('#reschedule-modal, #reschedule_date').val(formattedDate);
        $('#reschedule-modal #doctorIdReschedule').val(doctor_id);
        $('#reschedule-modal .modal-title').text('Reschedule Booking - ' + booking_data.booking_id);
        checkAvailibility(doctor_id, formattedDate, booking_data.booking_time_slot);
    });
    $('#reschedule-modal #confirm_reschedule_appointment').on('click', function(e) {
        e.preventDefault();

        var $form = $('#reschedule_appointment_form');
        var formData = new FormData($form[0]);

        // Logging formData for debugging purposes
        // formData.forEach((value, key) => {
        //     console.log(key + ": " + value);
        // });

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
                    App.alert(res['message'] || 'Record saved successfully', 'Success!', 'success');
                    console.log(res, 'res');
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

    $('#appointment-modal #book_appointment').on('click', function(e) {
        e.preventDefault();

        var $form = $('#book_appointment_form');
        var formData = new FormData($form[0]);

        // Logging formData for debugging purposes
        // formData.forEach((value, key) => {
        //     console.log(key + ": " + value);
        // });

        let i = 0;
        App.setJQueryValidationRules('#book_appointment_form');

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
                    App.alert(res['message'] || 'Record saved successfully', 'Success!', 'success');
                    console.log(res, 'res');
                    setTimeout(function() {
                        window.location.href = res['oData']['redirect'];
                    }, 2500);
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


    $("#cancelAppointment").click(function(e){

        e.preventDefault();
        let form = $('#cancelAppointment_form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ url('callcenter/patient_appointment_cancel') }}",
            type: "POST",
            data : data,
            dataType:"JSON",
            processData : false,
            contentType:false,

        success: function(response) {
            location.reload();
        },
        error: function(xhr, status, error) {


        }

        });

    })


$("#confirmAppointment").click(function(e){

     e.preventDefault();
     let form = $('#confirmAppointment_form')[0];
     let dataconfirm = new FormData(form);

      $.ajax({
        url: "{{ route('callcenter.patient_appointment_confirm') }}",
        type: "POST",
        data : dataconfirm,
        dataType:"JSON",
        processData : false,
        contentType:false,

     success: function(response) {
        location.reload();

 },
    error: function(xhr, status, error) {


    }

      });

})

$("#buttoncompleted").click(function(e){

    e.preventDefault();
    let form = $('#completedappointmentform')[0];
    let dataconfirm = new FormData(form);

     $.ajax({
       url: "{{ route('callcenter.patient_appointment_completed') }}",
       type: "POST",
       data : dataconfirm,
       dataType:"JSON",
       processData : false,
       contentType:false,

    success: function(response) {
       location.reload();

},
   error: function(xhr, status, error) {


   }

     });

})
</script>

<script>
$(document).ready(function () {
     // var value = $("#agent_id").val();
    // alert(value);
   var table = $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        searching:true,
        ajax: {
            'type':'POST',
            'url' : '{{ route("callcenter.callcenterAppointmentLoadData") }}',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.booking_status = $('#booking_status').val();
                d.agent_id = $('#agent_id').val();
            }
        },
        columns: [
            {data:'sl_no',  orderable: false, searchable: false},
            {data: 'booking_id', name:'doctor_patient_appointments.booking_id'},

            {
            data: 'user.name',
             name:'user.name',
            render: function(data, type, full, meta) {
                return `<div class="flex-shrink-0 me-3">
                            <img class="rounded-circle avatar-sm" src="${full.user.user_img_url}" /> ${full.user.name}
                        </div>` ;
            }
        },

            {data:'booking_time_slot',name:'doctor_patient_appointments.booking_time_slot'},
            {data: 'doctor.user.name',  orderable: false, searchable: false},
            {data: 'agent.user.name',  orderable: false, searchable: false},
            {
                "data": "booking_status",
                name:'doctor_patient_appointments.booking_status',
                name:'doctor_patient_appointments.booking_status',
                "render": function(data, type, row) {
                    if ((data === 'Confirmed') || (data === 'confirmed')) {
                        return '<div class="status-badge confirmed-badge"><span></span> Confirmed</div>';
                    } else if ((data === 'Cancelled') || (data === 'cancelled') ) {
                        return '<div class="status-badge cancelled-badge"><span></span> Cancelled</div>';
                    } else if ((data === 'Completed') || (data === 'completed') ) {
                        return '<div class="status-badge completed-badge"><span></span> Completed</div>';
                     } else if ((data === 'Pending') || (data === 'pending') ) {
                        return '<div class="status-badge pending-badge"><span></span> Pending</div>';
                    } else if ((data === 'Rescheduled') || (data === 'Rescheduled') ) {
                        return '<div class="status-badge reschedule-badge"><span></span> Rescheduled</div>';
                    } else {
                        return data; // Return data as is for other statuses
                    }
                }
            },
            {data: 'booking_date',name:'doctor_patient_appointments.booking_date'},
            {data: 'action',  orderable: false, searchable: false}
        ],
        lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
        pageLength: 10,
        order: [],
        language: {
            loadingRecords: "No Data Available",
        },
    });

    // Implement search functionality
    $('.flatpicker-input1').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true,
    });

    // Handle form submission
    $('#appointment-filter-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        table.ajax.reload(); // Reload DataTable with new data based on form filters
    });

   // Optional: Reset filters
    $('#reset-filters').on('click', function() {

        $('#appointment-filter-form')[0].reset();

       $('#appointment-filter-form #booking_status ').each(function() {
            $(this).val('').trigger('change');
        });

        table.ajax.reload();
    });

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        $('#table_list').DataTable().search($(this).serialize()).draw();
    });
});
    </script>

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


document.getElementById("reschedule_date").value = param4;
document.getElementById("idReschedule").value = param2;
let bookingDate = new Date(param4);
        let day = String(bookingDate.getDate()).padStart(2, '0');
        let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
        let year = bookingDate.getFullYear();
        let formattedDate = `${day}-${month}-${year}`;
      checkAvailibility(param2, formattedDate, param3);
//checkAvailibility(doctor_id, formattedDate, booking_data.booking_time_slot);

document.getElementById("exampleModalLabel").innerText = "Reschedule Booking- " + param1;
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
        $("#PatientSelct, #doctorSelct, #ClinicSelect, #DepartmentSelct").select2({ dropdownParent: "#appointment-modal" });

    });

    </script>


<script>
    // $(document).ready(function() {

    //     function loadDepartments(hospital_id, selectedEmirateId = ''){
    //     if (hospital_id) {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ url('callcenter/get-hospital-departments') }}/" + hospital_id,
    //             success: function (res) {
    //                 if (res) {
    //                     $('#DepartmentSelct').empty();
    //                      $('#departments').append('<option value="">Select Departments</option>');
    //                     $.each(res, function (index, department) {
    //                         $('#DepartmentSelct').append('<option value="' + department.id+'">' + department.title + '</option>');
    //                     });
    //                     // $('#DepartmentSelect').val(selectedEmirateId).trigger('change');
    //                     // $('#DepartmentSelect').select2(); // Reinitialize select2
    //                 }
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Error fetching departments:', error);
    //             }
    //         });
    //     } else {
    //         $('#emirate_id').empty();
    //         $('#emirate_id').append('<option value="">Select Departments</option>');
    //     }
    // }

    // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

    // $('#ClinicSelect').on("change", function () {
    //     loadDepartments($(this).val())
    // });
    // $('#DepartmentSelct').on("change", function () {
    //         console.log(23423);
    //         loadDoctorsDepartment($(this).val())
    //     });
    $(document).ready(function() {
    function loadDepartments(hospital_id, selectedEmirateId = '') {
        if (hospital_id) {
            $.ajax({
                type: "GET",
                url: "{{ url('callcenter/get-hospital-departments') }}/" + hospital_id,
                success: function (res) {
                    if (res) {
                        $('#DepartmentSelct').empty();
                        $('#DepartmentSelct').append('<option value="">Select Departments</option>');
                        $.each(res, function (index, department) {
                            $('#DepartmentSelct').append('<option value="' + department.id + '">' + department.title + '</option>');
                        });
                        // $('#DepartmentSelct').val(selectedEmirateId).trigger('change');
                        // $('#DepartmentSelct').select2(); // Reinitialize select2

                        // Event binding should be inside success callback
                        // $('#DepartmentSelct').on("change", function () {
                        //     console.log(23423);
                        //     loadDoctorsDepartment($(this).val());
                        // });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching departments:', error);
                }
            });
        } else {
            $('#DepartmentSelct').empty();
            $('#DepartmentSelct').append('<option value="">Select Departments</option>');
        }
    }

    // $('#ClinicSelect').on("change", function () {
    //     loadDepartments($(this).val());
    // });

    // // Initial event binding for existing elements
    // $('#DepartmentSelct').on("change", function () {
    //     console.log(23423);
    //     loadDoctorsDepartment($(this).val());
    // });
    $('#ClinicSelect').on("change", function () {

        var type = $(this).find('option:selected').data('type');
        if(type == 20) {
                // Disable the anotherDropdown
                console.log(234);
                $('#DepartmentSelct').prop('disabled', true);
                loadDoctors($(this).val());
            } else {
                // Enable the anotherDropdown
                $('#DepartmentSelct').prop('disabled', false);
                loadDepartments($(this).val());
            }
    });

    // Initial event binding for existing elements
    $('#DepartmentSelct').on("change", function () {
        console.log(23423);
        loadDoctorsDepartment($(this).val());
    });
    function loadDoctors(departmentId){
            if(departmentId){
                $.ajax({
                    type: "GET",
                    url: "{{ url('agent/get-clinic-doctors') }}/" + departmentId,
                    success: function (res) {
                        if (res) {
                            $('#doctorSelct').empty();
                            $('#doctorSelct').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctorSelct').append('<option value="' + data.user_id+'" data-id="'+data.id+'">' + data.user?.name + '</option>');
                            });
                            // $('#doctorSelect').val(selectedId).trigger('change');
                            $('#doctorSelct').select2({ dropdownParent: "#appointment-modal" }); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#doctorSelct').empty();
                $('#doctorSelct').append('<option value=""></option>');
            }
        }

    function loadDoctorsDepartment(departmentId){
            if(departmentId){
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-department-doctors') }}/" + departmentId,
                    success: function (res) {
                        if (res) {
                            $('#doctorSelct').empty();
                            $('#doctorSelct').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctorSelct').append('<option value="' + data.user_id+'" data-id="'+data.id+'">' + data.user?.name + '</option>');
                            });
                            // $('#doctorSelct').val(selectedId).trigger('change');
                            $('#doctorSelct').select2({ dropdownParent: "#appointment-modal" }); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#doctorSelct').empty();
                $('#doctorSelct').append('<option value=""></option>');
            }
        }
});

</script>



<script>

    function checkAvailibility(doctor_id, date, selectedTime = null){
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('callcenter/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                        'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        updateSlots(res.oData.list, selectedTime);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }

    function updateSlots(slots, selectedTime = null) {
        $('.availiblity').prop('disabled', true);
        $('.availiblity').prop('checked', false);
        if(slots){
            $('.availiblity').each(function() {
                var slot = $(this).val();
                // console.log("slot: ", slot);
                var isAvailable = slots.find(s => s.slot_text === slot)?.is_available === "1";
                $(this).prop('disabled', !isAvailable);
                if(selectedTime){
                    $(this).prop('checked', selectedTime == slot);
                }
            });
        }
    }
</script>
