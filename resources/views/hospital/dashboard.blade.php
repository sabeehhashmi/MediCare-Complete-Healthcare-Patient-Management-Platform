@include('hospital.layouts.header')
<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.departments')}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-black">
                                <i class="fi fi-rr-bed-alt icon font-size-22 mb-0 text-white" style="top: 2px;"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Departments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                            {{$totaldepartments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.doctors')}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-danger">
                                <i class="fi fi-rr-user-md icon font-size-22 mb-0 text-white" style="top: 2px;"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Doctors</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                            {{$totaldoctors}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.totalappointments')}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-primary-subtle">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-black"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Appointments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                        {{$totalappointments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_status' => BOOKING_STATUS_PENDING])}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-warning">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-black"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Pending Appointments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                        {{$pendingappointments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_status' => BOOKING_STATUS_CONFIRMED])}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-primary">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Confirmed Appointments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                        {{$confirmappointments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_status' => BOOKING_STATUS_COMPLETED])}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-success">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Completed Appointments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                        {{$completedappointments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_status' => BOOKING_STATUS_CANCELLED])}}" class="card bg-white">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-black">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Cancelled Appointments</h6>
                        </div>
                    </div>
                    <div>
                        <h4 class="mt-2 pt-1 mb-0 h1">
                        {{$cancelledappointments}}
                        </h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    
    
    <div class="col-md-6 col-xl-3 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_type' => 'New Consultation'])}}"  class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0 font-size-15">New Consultation</h6>
                        <h4 class="mt-3 mb-0 font-size-22">{{$NewConsultation}}</h4>
                        </div>

                    <div class="">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-primary-subtle ">
                                <i class="fi fi-rr-hexagon-check d-flex align-items-center font-size-24 mb-0 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_type' => 'Follow-up Consultation'])}}"  class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0 font-size-15">Follow-up Consultation</h6>
                        <h4 class="mt-3 mb-0 font-size-22">{{$FollowupConsultation}}</h4>
                        </div>

                    <div class="">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-warning ">
                                <i class="fi fi-rr-pending d-flex align-items-center font-size-24 mb-0 text-dark"></i>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <div class="col-md-6 col-xl-3 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_type' => 'Second Opinion'])}}"  class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0 font-size-15">Second Opinion</h6>
                        <h4 class="mt-3 mb-0 font-size-22">{{$SecondOpinion}}  </h4>
                        </div>

                    <div class="">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-primary ">
                                <i class="fi fi-rr-hexagon-check d-flex align-items-center font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_type' => 'Online Consultation'])}}"  class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0 font-size-15">Online Consultation</h6>
                        <h4 class="mt-3 mb-0 font-size-22">{{$OnlineConsultation}}  </h4>
                        </div>

                    <div class="">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-success ">
                                <i class="fi fi-rr-assessment d-flex align-items-center font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <a href="{{route('hospital.totalappointments', ['booking_type' => 'Emergency Consultation'])}}"  class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0 font-size-15">Emergency Consultation</h6>
                        <h4 class="mt-3 mb-0 font-size-22">{{$EmergencyConsultation}}</h4>
                        </div>

                    <div class="">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-dark ">
                                <i class="fi fi-rr-times-hexagon d-flex align-items-center font-size-24 mb-0 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    
</div>

<div class="mb-5">
    <div class="card">
        <h5 class="card-header">Latest Appointments</h5>
        <div class="card-body">
            <div class="table-wrap" id="tableDiv">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle" id="example2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Id</th>
                                <th>Patient Name</th>
                                <th>Time Slot</th>
                                <th>Doctor</th>
                                <th>Processed By</th>
                                <th>Booking Status</th>
                                <th>Booking Date</th>
                                <th></th>
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
                                    <a href="#!" class="patient-link"><img src="{{ $appointment->member ? ($appointment->member->user_img_url ?? null) : ($appointment->user->user_img_url ?? null) }}" width="32" height="32" class="me-2" alt="" />{{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}</a>
                                </td>
                                <td>{{ $appointment->booking_time_slot }}</td>
                                <td>DR {{$appointment->doctor->user->name}}<br>Specialist- {{($appointment->doctor->specialities ?? null) ? $appointment->doctor->specialities->pluck('name_en')->unique()->implode(', ') : null}}</td>
                                <td>
                                    @php
                                        if ($appointment->status_history->count()) {
                                            $history = $appointment->status_history->first();
                                            echo  !empty($history['changedBy']) ? ($history['changedBy']['name'] != '' ? $history['changedBy']['name'] : 'N/A') : 'N/A';
                                        } else {
                                            echo ( $appointment->user)? $appointment->user->name:'';
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
                                                <a class="dropdown-item" href="{{route('hospital.appointmentdetail',['id'=>$appointment->id])}}">View Appointment</a>
                                                @if(($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_CONFIRMED || $appointment->booking_status === BOOKING_STATUS_RESCHEDULED))
                                                <a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" data-bs-target="#cancel-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Cancel Appointment</a>
                                                @endif
                                                @if(($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_RESCHEDULED))
                                                <a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" data-bs-target="#confirm-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Confirm Appointment</a>
                                                @endif
                                                @if(($appointment->booking_status === BOOKING_STATUS_PENDING || $appointment->booking_status === BOOKING_STATUS_CONFIRMED))
                                                <a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" data-bs-target="#reschedule-modal" data-booking-data="{{json_encode($appointment)}}" data-appointment-doctor_id="{{$appointment->doctor->user_id}}">Reschedule Appointment</a>
                                                @endif
                                                @if($appointment->booking_status === BOOKING_STATUS_CONFIRMED)
                                                <a class="dropdown-item followup-link" href="#!" data-bs-toggle="modal" data-bs-target="#completed-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id }}">Complete Appointment</a>
                                                @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal -->
  
@include('hospital.layouts.footer')

<script>
    function reformatDate(dateStr) {
        const [day, month, year] = dateStr.split('-');
        return `${year}-${month}-${day}`;
    }
    $(document).ready(function() {

        function loadMember(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('hospital/get-members') }}/" + parentId,
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
                    url: "{{ url('hospital/get-hospital-departments') }}/" + parentId,
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
                    url: "{{ url('hospital/get-hospital-doctors') }}/" + hospitalId,
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

        function loadDoctorsDepartment(departmentId){
            if(departmentId){
                $.ajax({
                    type: "GET",
                    url: "{{ url('hospital/get-department-doctors') }}/" + departmentId,
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

        $('#DepartmentSelct').on("change", function () {
            loadDoctorsDepartment($(this).val())
        });
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
    
    function checkAvailibility(doctor_id, date, selectedTime = null){
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('hospital/check_doctor_availability') }}",
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
                url: "{{ url('hospital/check_doctor_availability') }}",
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
            url: "{{ url('hospital/patient_appointment_cancel') }}",
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
        url: "{{ route('hospital.patient_appointment_confirm') }}",
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
       url: "{{ route('hospital.patient_appointment_completed') }}",
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
    
//     $('.followup-link').click(function() {
        
//         var appointmentId = $(this).data('appointment-id');
//         var booking_id = $(this).data('booking-id');
//         $('#idCompleted').val(appointmentId);
//         $('#completed-appointment .modal-title').text('Cancel Booking - ' + booking_id);
//     });
//     $('.accept-link').click(function() {
        
//         var appointmentId = $(this).data('appointment-id');
//         var booking_id = $(this).data('booking-id');
//         $('#idConfirmed').val(appointmentId);
//         $('#confirm-appointment .modal-title').text('Cancel Booking - ' + booking_id);
//     });
//      $('.cancel-link').click(function() {
        
//             var appointmentId = $(this).data('appointment-id');
//             var booking_id = $(this).data('booking-id');
//             $('#idCancel').val(appointmentId);
//             $('#cancel-appointment .modal-title').text('Cancel Booking - ' + booking_id);
//     });
//     $("#cancelAppointment").click(function(e){
        
//      e.preventDefault();
//      let form = $('#cancelAppointment_form')[0];
//      let data = new FormData(form);
    
//       $.ajax({
//         url: "{{ url('hospital/patient_appointment_cancel') }}",
//         type: "POST",
//         data : data,
//         dataType:"JSON",
//         processData : false,
//         contentType:false,
        
//      success: function(response) {
//         location.reload();
   
//  },
//     error: function(xhr, status, error) {
      
        
//     }
 
//       });
   
// })


// $("#confirmAppointment").click(function(e){
    
//      e.preventDefault();
//      let form = $('#confirmAppointment_form')[0];
//      let dataconfirm = new FormData(form);
    
//       $.ajax({
//         url: "{{ route('hospital.patient_appointment_confirm') }}",
//         type: "POST",
//         data : dataconfirm,
//         dataType:"JSON",
//         processData : false,
//         contentType:false,
        
//      success: function(response) {
//         location.reload();
   
//  },
//     error: function(xhr, status, error) {
      
        
//     }
 
//       });
   
// })

// $("#buttoncompleted").click(function(e){
    
//     e.preventDefault();
//     let form = $('#completedappointmentform')[0];
//     let dataconfirm = new FormData(form);
   
//      $.ajax({
//        url: "{{ route('hospital.patient_appointment_completed') }}",
//        type: "POST",
//        data : dataconfirm,
//        dataType:"JSON",
//        processData : false,
//        contentType:false,
       
//     success: function(response) {
//        location.reload();
  
// },
//    error: function(xhr, status, error) {
     
       
//    }

//      });
  
// })
    </script>