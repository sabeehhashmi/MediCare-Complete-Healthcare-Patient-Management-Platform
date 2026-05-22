@include('clinic.layouts.header')
<div class="mb-5 position-relative">
<div class="card">
    <div class="card-body">
        <div class="position-relative">
            <div class="d-flex gap-2 justify-content-between mb-3">
                <div class="btn-group toggle-btn-g-tabs-cale" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="radio" class="btn-check" name="scs" id="btncheck4" autocomplete="off" checked />
                    <label class="btn btn-outline-primary" for="btncheck4"><img class="icn" src="{{ asset('') }}clinic/assets/images/table-view.png" alt="" /></label>

                    <input type="radio" class="btn-check" name="scs" id="btncheck5" autocomplete="off" />
                    <label class="btn btn-outline-primary" for="btncheck5"><img class="icn" src="{{ asset('') }}clinic/assets/images/calender-icon.png" alt="" /></label>
                </div>
                <button type="button" class="btn btn-primary reset-modal" data-bs-toggle="modal" data-bs-target="#appointment-modal">Make an Appointment</button>
            </div>
            <form action="{{ route('clinic.totalappointments') }}" method="GET" id="search-form">
                <div class="row align-items-end">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="from_date">Patient ID</label>
                        <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control" name="patient_id" value="{{old('patient_id', request('patient_id'))}}" placeholder="E.g MED0001" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="from_date">From</label>
                        <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input1" id="from_date" name="from_date" value="{{old('from_date', request('from_date'))}}" placeholder="From" />
                        <span class="bx bx-calendar-event"></span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="to_date">To</label>
                        <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input1" id="to_date" name="to_date" value="{{old('to_date', request('to_date'))}}" placeholder="To" />
                        <span class="bx bx-calendar-event"></span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="doctor_id">Select Doctor</label>
                        <div class="position-relative select-custom-icon">
                            <select name="doctor_id" id="doctor_id" class="select2-single" data-placeholder="Select Doctor">
                                <option></option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', request('doctor_id')) == $doctor->id ? 'selected' : '' }} >{{ $doctor->user->name }}</option>
                                @endforeach
                            </select>
                            <i class="fi fi-rr-user-md icon d-flex"></i>
                        </div>
                    </div>

                    <!-- Other filters -->

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="booking_status">Booking Status</label>
                        <div class="position-relative select-custom-icon">
                            <select name="booking_status" id="booking_status" class="select2-single" data-placeholder="Select Type">
                                <option></option>
                                <option value="{{BOOKING_STATUS_PENDING}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_PENDING ? 'selected' : '' }}>Pending</option>
                                <option value="{{BOOKING_STATUS_CONFIRMED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_CONFIRMED ? 'selected' : '' }}>Confirmed</option>
                                <option value="{{BOOKING_STATUS_CANCELLED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_CANCELLED ? 'selected' : '' }}>Cancelled</option>
                                <option value="{{BOOKING_STATUS_RESCHEDULED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_RESCHEDULED ? 'selected' : '' }}>Rescheduled</option>
                                <option value="{{BOOKING_STATUS_COMPLETED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_COMPLETED ? 'selected' : '' }}>Completed</option>
                            </select>
                            <i class='bx bx-sync'></i>
                        </div>
                    </div>
                    

                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="booking_type">Booking Type</label>
                    <div class="position-relative select-custom-icon">
                        <select name="booking_type" id="booking_type" class="select2-single" data-placeholder="Select Type">
                            <option></option>
                            @foreach($bookingTypes as $type)
                                    <option value="{{ $type->name }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                        </select>
                        <i class='bx bx-sync'></i>
                    </div>
                </div>

                    <div class="col-sm">
                        <div class="d-flex mt-3 mb-3">
                            <button type="submit" id="" class="btn btn-primary">Search</button>
                            <a href="{{route('clinic.totalappointments')}}" type="button"  class=" btn btn-info waves-effect waves-light">Refresh</a>
                            <a href="{{ route('clinic.appointments.export') }}" id="export" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export</a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- end row -->
            <div class="table-wrap" id="tableDiv">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle" id="example2">
                    <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Id</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Time Slot</th>
                                <th>Doctor & Specialties</th>
                                <th>Processed By</th>
                                <!-- <th>Department</th> -->
                                <th>Booking Status</th>
                                <th>Booking Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($appointments->count() == 0)
                            <tr>
                                <td colspan="8">No Data Available</td>
                            </tr>
                        @endif
                        @foreach ($appointments as $key => $appointment)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $appointment->booking_id }}<br> 
                                    @php
                                    $html='';
                                        $booking_types = [
                                    'New Consultation'        => 'text-bg-success',
                                    'Follow-up Consultation'  => 'text-bg-primary',
                                    'Second Opinion'          => 'text-bg-warning',
                                    'Online Consultation'     => 'text-bg-info',
                                    'Emergency Consultation'  => 'text-bg-danger',
                                ];
                
                                            if (isset($booking_types[$appointment->booking_type])) {
                                        
                                        $badgeClass = $booking_types[$appointment->booking_type];
                                
                                        $html = ' 
                                        <span class="badge rounded-pill '.$badgeClass.' p-1 px-2 font-size-10 fw-normal me-2">
                                                '.$appointment->booking_type.'
                                            </span>';
                                    }
                                    echo $html;
                                    @endphp
                                </td>
                                <td>
                                <a href="#!" class="patient-link">{{$appointment->member ? $appointment->member->patient_id : ( ($appointment->user->patient_id ?? ''))}}</a>
                                </td>
                                <td>
                                <a href="#!" class="patient-link"><img src="{{ $appointment->member ? ($appointment->member->user_img_url ?? null) : ($appointment->user->user_img_url ?? null) }}" width="32" height="32" class="me-2" alt="" />{{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}</a>
                                </td>
                                <td>{{ $appointment->booking_time_slot }}</td>
                                <td>DR {{$appointment->doctor->user->name ?? null}}<br>Specialist- {{($appointment->doctor->specialities ?? null) ? $appointment->doctor->specialities->pluck('name_en')->unique()->implode(', ') : null}}</td>
                                <td>
                                    @php
                                        if ($appointment->status_history->count()) {
                                            $history = $appointment->status_history->first();
                                            echo  !empty($history['changedBy']) ? ($history['changedBy']['name'] != '' ? $history['changedBy']['name'] : 'N/A') : 'N/A';
                                        } else {
                                            echo $appointment->created_by_user->name;
                                        }
                                    @endphp
                                   </td>
                                <!-- <td>{{$appointment->department->title ?? ""}}</td> -->
                                <td><div class="status-badge @if($appointment->booking_status == BOOKING_STATUS_PENDING) pending-badge
                                                                  @elseif($appointment->booking_status == BOOKING_STATUS_COMPLETED) completed-badge
                                                                  @elseif($appointment->booking_status == BOOKING_STATUS_CANCELLED) cancelled-badge
                                                                  @elseif($appointment->booking_status == BOOKING_STATUS_CONFIRMED) confirmed-badge
                                                                  @elseif($appointment->booking_status == BOOKING_STATUS_RESCHEDULED) reschedule-badge
                                                                  @endif">
                                        <span></span> {{strtoupper($appointment->booking_status)}}
                                    </div></td>
                                <td>{{ get_date_in_timezone($appointment->booking_date,'d F Y') }}</td>
                                <td>
                                    <div class="dropdown mt-4 mt-sm-0">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                                <a class="dropdown-item cancel-link" href="{{route('clinic.appointmentdetail',['id'=>$appointment->id])}}">View Appointment</a>
                                                @if (($appointment->booking_status === BOOKING_STATUS_PENDING || strtolower($appointment->booking_status) === BOOKING_STATUS_CONFIRMED || strtolower($appointment->booking_status) === 'rescheduled'))
                                                <a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" data-bs-target="#cancel-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Cancel Appointment</a>
                                                @endif
                                                @if (($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_RESCHEDULED))
                                                <a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" data-bs-target="#confirm-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Confirm Appointment</a>
                                                @endif
                                                @if ($appointment->booking_status === BOOKING_STATUS_CONFIRMED )
                                                <a class="dropdown-item followup-link" href="#!" data-bs-toggle="modal" data-bs-target="#completed-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Complete Appointment</a>
                                                @endif
                                                @if ($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_CONFIRMED)
                                                <a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" data-bs-target="#reschedule-modal" data-booking-data="{{json_encode($appointment)}}" data-appointment-doctor_id="{{$appointment->doctor->user_id ?? null}}">Reschedule Appointment</a>
                                                @endif
                                                @if ($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_CONFIRMED)
                                                <a class="dropdown-item upload-link" href="#!" data-bs-toggle="modal" data-bs-target="#upload-docs" data-booking-id="{{$appointment->id}}" data-booking-data="{{json_encode($appointment)}}" data-appointment-doctor_id="{{$appointment->doctor->user_id ?? null}}">Upload Documents</a>
                                                @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="mt-4">
                        <div class="col-sm-12 col-md-12 pull-right">
                            <span>
                                Showing
                                {{
                                    (($appointments->currentPage() - 1) * $appointments->perPage()) + 1
                                }}
                                to
                                {{
                                    min($appointments->currentPage() * $appointments->perPage(), $appointments->total())
                                }}
                                of {{$appointments->total()}} entries
                            </span>
                            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                {!! $appointments->appends(request()->input())->links('admin.template.pagination') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="d-flex justify-content-right">
                    {{ $appointments->links() }}
                </div> -->
            </div>
            <div class="calender-wrap" id="calenderDiv">
                <div class="card-h-100 my-3">
                @if(count($restAllAppointments))
                    @foreach ($restAllAppointments as $date => $doctorsApp)
                        <div>
                            @if(count($doctorsApp))
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $tomorrow = \Carbon\Carbon::tomorrow();
                                    $dayAfterTomorrow = \Carbon\Carbon::today()->addDays(2);

                                    $appointmentDate = \Carbon\Carbon::parse($date);

                                    if ($appointmentDate->isSameDay($today)) {
                                        $formattedAppDate = 'Today';
                                    } elseif ($appointmentDate->isSameDay($tomorrow)) {
                                        $formattedAppDate = 'Tomorrow';
                                    } elseif ($appointmentDate->isSameDay($dayAfterTomorrow)) {
                                        $formattedAppDate = 'Day after Tomorrow';
                                    } else {
                                        $formattedAppDate = $appointmentDate->format('d F Y');
                                    }
                                @endphp
                                <hr>
                                <h4 class="mb-3">{{ $formattedAppDate }}</h4>
                                @foreach ($doctorsApp as $doctorData)
                                    @php
                                        $doctord = $doctorData['user'];
                                        $appointments = $doctorData['doctor_patient_appointments'];
                                    @endphp
                                    <div class="single-doctor-appointment mb-4">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <img style="width: 60px; height: 60px; border-radius: 5px;" src="{{$doctord->user->user_img_url}}" alt="Generic placeholder image">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="font-size-20 mb-2">DR {{$doctord->user->name ?? null}}</h4>
                                                <h6 class="font-size-13 mb-0 text-primary">{{ $doctord->specialities ? $doctord->specialities->pluck('name_en')->implode(', ') : null}}</h6>
                                            </div>
                                        </div>
                                        <div class="section-area-grid-view d-flex flex-wrap">
                                            @foreach($appointments as $appointment)
                                                <div class="col-slot-item">
                                                    @php
                                                        $formattedDate = \Carbon\Carbon::parse($appointment->booking_date)->format('d F Y');
                                                    @endphp
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal1"
                                                    data-appointment-patient="{{json_encode($appointment->user)}}"
                                                    data-appointment-member="{{json_encode($appointment->member)}}"
                                                    data-appointment-url="{{route('clinic.appointmentdetail',['id'=>$appointment->id])}}"
                                                    class="border-styles {{getAppointmentStatusClassBox($appointment->booking_status)}}">
                                                        <div>
                                                            <p class="time">{{$appointment->booking_time_slot}}</p>
                                                            <p class="name">{{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}</p>
                                                            <span class="status">{{strtoupper($appointment->booking_status)}}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif

                </div>
            </div>
        </div>
    </div>
</div>

</div>
@include('clinic.layouts.footer')
<script>
function updateExportUrl() {
    let form = $('#search-form')[0];
    let formData = new FormData(form);
    let newQuery = new URLSearchParams(formData).toString();
    let exportElement = $('#export');
    let exportUrl = new URL(exportElement.attr('href') || "{{ route('clinic.appointments.export') }}");

    let existingQueryParams = new URLSearchParams(exportUrl.search);
    let newQueryParams = new URLSearchParams(newQuery);

    for (let [key, value] of newQueryParams.entries()) {
        existingQueryParams.set(key, value);
    }

    exportUrl.search = existingQueryParams.toString();
    exportElement.attr('href', exportUrl.toString());
}

$('#search-form input, #search-form select').on('change', function() {
    updateExportUrl();
});

function reformatDate(dateStr) {
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;
}
    $(document).ready(function() {
        updateExportUrl();

        var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            // minDate: "today",
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
            // minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });

        function loadMember(parentId){
            $('#memberSelct').html('<option value="">Loading..</option>');
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('clinic/get-members') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            // $('#memberSelct').empty();
                            $('#memberSelct').html('<option value="">Self</option>');
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
                // $('#memberSelct').empty();
                $('#memberSelct').html('<option value=""></option>');
            }
        }

        function loadDepartments(parentId){
            if (parentId) {
                $('#department').html('<option value="">Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('clinic/get-hospital-departments') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            // $('#department').empty();
                            $('#department').html('<option value="">Select Departments</option>');
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
                // $('#department').empty();
                $('#department').html('<option value=""></option>');
            }
        }

        function loadDoctorsHospital(hospitalId, departmentId){
            if (hospitalId) {
                $('#doctorSelct').html('<option value="">Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('clinic/get-hospital-doctors') }}/" + hospitalId,
                    success: function (res) {
                        if (res) {
                            // $('#doctorSelct').empty();
                            $('#doctorSelct').html('<option value="">Select Doctor</option>');
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
                // $('#doctorSelct').empty();
                $('#doctorSelct').html('<option value=""></option>');
            }
        }

        function loadDoctorsDepartment(departmentId){
            if(departmentId){
                $('#doctorSelct').html('<option value="">Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('clinic/get-department-doctors') }}/" + departmentId,
                    success: function (res) {
                        if (res) {
                            // $('#doctorSelct').empty();
                            $('#doctorSelct').html('<option value="">Select Doctor</option>');
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
                // $('#doctorSelct').empty();
                $('#doctorSelct').html('<option value=""></option>');
            }
        }

        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#PatientSelct').on("change", function () {
            // loadMember($(this).val());
        });

        // $('#hospital').on("change", function () {
        //     loadDepartments($(this).val())
        //     // loadDoctorsHospital($(this).val())
        // });

        $('#appointment-modal #booking_date_appointment, #appointment-modal #doctorSelct').on("change", function () {
            checkAvailibility2($('#appointment-modal #doctorSelct').val(), $('#appointment-modal #booking_date_appointment').val());
        });

        // checkAvailibility($('#doctor').val(), $('#booking_date').val());

        $('#DepartmentSelct').on("change", function () {
            // loadDoctorsDepartment($(this).val())
        });
    });

    $(document).ready(function() {
        // $('#event-modal').on('show.bs.modal', function (event) {
        //     var button = $(event.relatedTarget); // Button that triggered the modal
        //     var patient = button.data('appointment-patient');
        //     var member = button.data('appointment-member');
        //     var url = button.data('appointment-url');

        //     $('#details-link-appointment').attr('href', url);
        //     if(member){
        //         // Update the modal content
        //         $('#modal-user-name').text(member?.full_name);
        //         $('#modal-user-email').text(member.email);
        //         $('#modal-appointment-date').text(appointmentDate);
        //         $('#modal_booking_id').text(boking_id);
        //         $('#modal-booking-time').text(booking_time);
        //         // $('#modal-patient-whatsapp').text(patientwhatsapp);
        //         $('#modal-patient-gender').text(member.gender);
        //         $('#modal-patient-dob').text(member.dob);
        //     }
        // });
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
                url: "{{ url('clinic/check_doctor_availability') }}",
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

    function checkAvailibility2(doctor_id, date){
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('clinic/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                        'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        updateSlots2(res.oData.list, date);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }

    function updateSlots2(slots, selectedDate) {
        $('#appointment-modal .availiblity').prop('disabled', true);
        $('#appointment-modal .availiblity').prop('checked', false);

        // Get the current date and time
        var currentDate = new Date();
        var currentHours = currentDate.getHours();
        var currentMinutes = currentDate.getMinutes();
        var currentDateString = currentDate.toISOString().split('T')[0];
        const selectedDateFormatted = reformatDate(selectedDate);

        if (slots && Array.isArray(slots)) {
            $('#appointment-modal .availiblity').each(function() {
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
          var reason = $('#reschedule_appointment_form textarea[name="reason"]').val().trim();
        if (reason === '') {
            App.alert('Please enter a reason for rescheduling the appointment', 'Oops!', 'error');
            $('#reschedule_appointment_form textarea[name="reason"]').focus();
            return false;
        }
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
            url: "{{ url('clinic/patient_appointment_cancel') }}",
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
        url: "{{ route('clinic.patient_appointment_confirm') }}",
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
       url: "{{ route('clinic.patient_appointment_completed') }}",
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
