@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
<style>
    .card-header .btn-primary.disable-appointmnet-create {
  background-color: #aa909c !important;
  border-color: #aa909c !important;
}
</style>
@stop
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @stop

    <link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
@section("content")

<div class="card mb-5">
    <!-- <div class="card-header"> -->
    <!-- <div class="card-header"> -->
    <div class="card-header">
        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-end">
                @if($clinic)
                <a  href=" {{route('admin.clinics.index')}}"  class=" btn btn-dark waves-effect waves-light">Back</a>
                @endif
                @if($hospital)
                <a  href=" {{route('admin.hospitals.index')}}"  class=" btn btn-dark waves-effect waves-light">Back</a>
                @endif
                @if($doctor)
                <a  href=" {{route('admin.doctors.index')}}"  class=" btn btn-dark waves-effect waves-light">Back</a>
                @endif
                @php
                $disabled = false;
                if (!empty($hospital) && !$hospital->is_contract_signed && empty($doctor)) {
                    $disabled = true;
                }

                if (!empty($clinic) && !$clinic->is_contract_signed && empty($doctor)) {
                    $disabled = true;
                }

                if (!empty($doctor)) {
                    if (!empty($doctor->hospital)) {
                        if (!$doctor->hospital->is_contract_signed) {
                            $disabled = true;
                        }
                    }
                }
                @endphp
                @if($disabled)
                <button type="button"  class="btn btn-primary m-0  disable-appointmnet-create  ">Make an Appointment</button>
            @else
            <button type="button"  class="btn btn-primary m-0 " data-bs-toggle="modal" data-bs-target="#appointment-modal">Make an Appointment</button>

            @endif
            </div>
        </div>
    </div>
        <!-- <a href="{{route('admin.appointments.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Book an Appointment </a> -->
    <!-- </div> -->
    <form action="#" id="search-form">
        <div class="row align-items-end mt-3 mx-2">
            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label" for="username">From</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="booking_from" class="form-control flatpicker-input1" id="from_date" placeholder="From" />
                    <span class="bx bx-calendar-event"></span>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label" for="username">To</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="booking_to" class="form-control flatpicker-input1" id="to_date" placeholder="To" />
                    <span class="bx bx-calendar-event"></span>
                </div>
            </div>
            @if(!$hospital && !$clinic && !$doctor)
            <div class="col-lg-3 col-md-6 mb-2">
                <label class="form-label" for="hospital">Select Hospital</label>
                <div class="position-relative select-custom-icon">
                    <select name="hospital_id" id="hospital" class="select2-single" data-placeholder="Select Hospital">
                        <option></option>
                        @foreach($hospitals as $hospt)
                            <option value="{{ $hospt->id }}" >{{ $hospt->name_en }}</option>
                        @endforeach
                    </select>
                    <i class="fi fi-rr-bed-alt icon d-flex"></i>
                </div>
            </div>
            @endif
            @if(!$clinic && !$doctor && 1==2)
            <div class="col-lg-3 col-md-6 mb-2">
                <label class="form-label" for="department">Select Department</label>
                <div class="position-relative select-custom-icon">
                    <select name="department_id" id="department" class="select2-single" data-placeholder="Select Department">
                        <option></option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                        @endforeach
                    </select>
                    <i class="fi fi-rr-bed-alt icon d-flex"></i>
                </div>
            </div>
            @endif
            @if(!$doctor)
            <div class="col-lg-3 col-md-6 mb-2">
                <label class="form-label" for="doctor">Select Doctor</label>
                <div class="position-relative select-custom-icon">
                    <select name="doctor_id" id="doctor" class="select2-single" data-placeholder="Select Doctor">
                        <option></option>
                        @foreach($doctors as $dr)
                            <option value="{{ $dr->id }}" >{{ $dr->user->name }}</option>
                        @endforeach
                    </select>
                    <i class="fi fi-rr-user-md icon d-flex"></i>
                </div>
            </div>
            @endif
            <div class="col-lg-3 col-md-6 mb-2">
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
                    <i class='bx bx-info-circle' ></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6  mb-2">
            <label class="form-label" for="booking_type">Booking Type</label>
            <div class="position-relative select-custom-icon">
                <select name="booking_type" id="booking_type" class="select2-single" data-placeholder="Select Type">
                    <option></option>
                    @foreach($bookingTypes as $type)
        <option value="{{ $type->name }}" {{($booking_type==$type->name)?'selected':''}}>
            {{ $type->name }}
        </option>
    @endforeach
                </select>
                <i class='bx bx-sync'></i>
            </div>
        </div>
            <div class="col-sm mb-2">
                <div class="d-flex gap-2">
                    <!-- <div class="mt-md-0 mb-3 me-3"> -->
                        <button type="submit" id="" class="btn btn-primary">Search</button>
                        <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">Refresh</button>
                        <a href="{{ route('admin.appointments.export', $params) }}" id="export" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export</a>
                    <!-- </div> -->

                </div>
            </div>
        </div>
    </form>
    <div class="card-body">

        <div class="table-responsive">
    <!-- DataTables CSS -->
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Booking Id</th>
                <th>Doctor Name</th>
                <th>Patient Name</th>
                    <th>Booking Date</th>
                    <th>Time Slot</th>
                    <th>Processed By</th>
                    <th>Access Status</th>
                    <th>Booking Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <!-- DataTables JS -->

        </div>
    </div>
</div>


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
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- {{$appointment->booking_id ?? null}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="cancelAppointment_form" class="custom-form">
                        @csrf
                            <div class="row">
                            <input type="hidden" id="idCancel" value="{{$appointment->id ?? null}}" name="appointment_id">
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
                        <h1 class="modal-title fs-5" id="exampleModalLabel1">Confirm Appointment- {{$appointment->booking_id ?? null}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action=""  id="confirmAppointment_form" class="custom-form">
                        @csrf
                            <div class="row">
                            <input type="hidden" id="idConfirmed" value="{{$appointment->id ?? null}}" name="appointment_id">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel3">Reschedule Booking {{$appointment->booking_id ?? null}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.appointments.appointmentRescheduled')}}" id="reschedule_appointment_form" class="custom-form">
                    @csrf
                        <div class="row">
                        <input type="hidden" id="idReschedule" value="{{$appointment->id ?? null}}" name="appointment_id">
                        <input type="hidden" id="doctor_id_reschedule" value="{{$appointment->id ?? null}}" name="doctor_user_id">
                        <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="booking_date" value="{{$appointment->booking_date ?? null}}" class="form-control flatpicker-input" id="reschedule_date" placeholder="Select Date" />
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
                                        <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot"  value="{{$time_slot[$i]}}" class="time-slot checkbx-style availiblity" {{$time_slot[$i] == ($appointment->booking_time_slot ?? null) ? 'checked': ''}} />
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
                    <button type="button" id="confirm_reschedule_appointment" class="btn btn-primary">Confirm Reschedule</button>
                </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel2">Complete Appointment- {{$appointment->booking_id ?? null}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action=""  id="completed_form" class="custom-form">
                @csrf
                    <div class="row">
                    <input type="hidden" id="idCompleted" value="{{$appointment->id ?? null}}" name="appointment_id">
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

<!-- make appointment modal -->

<div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="book_appointment_form" action="{{route('admin.appointments.save')}}" class="custom-form">
                    @csrf
                    <div class="row">
                        
                        <div class="col-12 mb-3">
                            <label class="form-label" for="bookTypeSelct">Booking type </label>
                            <div class="position-relative select-custom-icon">
                                <select name="bookTypeSelect" id="bookTypeSelect" class="select2-single" data-placeholder="Select type">
                                    <option></option>
                                    @foreach($bookingTypes as $type)
                                    <option value="{{ $type->name }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                                </select>
                                <i class="fi fi-rr-source-document icon d-flex"></i>
                            </div>
                        </div>
                        
                        @if($spc_hospital_id)
                            <input type="hidden" name="hospital" value="{{$spc_hospital_id}}">
                        @endif
                        @php
                            $hospital_type = '';
                            if ($is_clinic && !$is_hospital)
                                $hospital_type = 'clinic';

                            if (!$is_clinic && $is_hospital)
                                $hospital_type = 'hospital';
                        @endphp
                            @if($hospital_type)
                                <input type="hidden" name="hospital_type" value="{{ $hospital_type }}">
                            @endif
                            <div class="col-12 mb-3">
                                <label class="form-label" for="HospitalSelct">Select Hospital </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="hospital" id="HospitalSelct" {{$spc_hospital_id ? 'disabled' : ''}} class="select2-single" data-placeholder="Select Hospital">
                                        <option value=""></option>
                                        @if(count($hospitals ?? []))
                                        @foreach($hospitals as $hosp)
                                            <option value="{{ $hosp->id }}" {{$spc_hospital_id == $hosp->id ? 'selected' : ''}} data-type="{{$hosp->type}}" >{{ $hosp->name_en }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <i class="fi fi-rr-bed-alt icon d-flex"></i>
                                </div>
                            </div>
                            @if(!$clinic)
                            <div class="col-12 mb-3" id="department-field">
                                <label class="form-label" for="DepartmentSelct">Select Department </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="department" id="DepartmentSelct" class="select2-single" data-placeholder="Select Department">
                                        <option value=""></option>
                                        @if(count($departments ?? []))
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <i class="fi fi-rr-syringe icon d-flex"></i>
                                </div>
                            </div>
                            @endif
                            <!--  -->
                            @if($doctor)
                            <input type="hidden" name="doctor" value="{{$doctor->user_id}}">
                            @endif
                            <div class="col-12 mb-3">
                                <label class="form-label" for="doctorSelct">Select Doctor </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="doctor" id="doctorSelct" {{($doctor->user_id ?? null) ? 'disabled': '' }} class="select2-single" data-placeholder="Select Doctor">
                                        <option></option>
                                        @if(count($doctors ?? []))
                                        @foreach($doctors as $dr)
                                            <option value="{{ $dr->user_id }}" {{($doctor->user_id ?? null) == $dr->user_id ? 'selected': '' }}>{{ $dr->user->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <i class="fi fi-rr-user-md icon d-flex"></i>
                                </div>
                            </div>
                            @if($patient)
                            <input type="hidden" name="patient" value="{{$patient->id}}">
                            @endif
                            <input type="hidden" name="patient_type" id="patient_type" value="10">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="PatientSelct">Select Patient </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="patient" id="PatientSelct" {{($patient->id ?? null) ? 'disabled': '' }} class="select2-single" data-placeholder="Select Patient">
                                        <option></option>
                                        @if(count($patientMembers ?? []))
                                        @foreach($patientMembers as $pati)
                                            <option value="{{ $pati['id'] }}" {{($patient->id ?? null) == $pati['id'] ? 'selected': '' }} data-type="{{$pati['type']}}">{{ $pati['fullname']}}
                                            @if($pati['phone'] ?? null)
                                                | +{{ $pati['dial_code'] ?? 971 }}{{ $pati['phone'] }}
                                            @endif
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <i class="fi fi-rr-portrait d-flex"></i>
                                </div>
                            </div>

                            <!-- <div class="col-12 mb-3">
                                <label class="form-label" for="DocSelct">Member </label>
                                <div class="position-relative">
                                    <select name="member_id" id="memberSelct" class="select2-single" data-placeholder="Select Member">
                                        <option></option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" name="booking_date" class="form-control flatpicker-input" id="booking_date_appointment" placeholder="Select Date" />
                                    <div class="error" id="booking_date_error"></div>
                                    <span class="bx bx-calendar"></span>
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
                                    @endfor
                                </div>
                                <br> <div class="error" id="slot_error"></div>
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
@stop



@section("page_script")



<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>

<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- jQuery -->


<script>


$(document).on('click', '.disable-appointmnet-create', function(event) {

        event.preventDefault(); // Prevent the default action
        event.stopPropagation();
        App.alert( 'Please sign the contract first', 'Error!');
      //  alert("This button is currently disabled.");
       // $('#appointment-modal').modal('hide');
        return false;

});



    function updateExportUrl() {
        let form = $('#search-form')[0];
        let formData = new FormData(form);
        let newQuery = new URLSearchParams(formData).toString();
        let exportElement = $('#export');
        let exportUrl = new URL(exportElement.attr('href') || "{{ route('admin.appointments.export') }}");

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


    var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            minDate: "",
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
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
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
        // $("#HospitalSelct", "#DepartmentSelct",  "#doctorSelct", "#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#doctorSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#DepartmentSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#HospitalSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#bookTypeSelect").select2({ dropdownParent: "#appointment-modal" });
        let selectedOption = $('#HospitalSelct').find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
        }else{
            $('#DepartmentSelct').html('<option value=""></option>');
            $('#department-field').hide();
        }
        $("#bookTypeSelct").select2({ dropdownParent: "#appointment-modal" });
    });
    </script>
<script>

$(document).ready(function () {
    function loadDoctorsHospital(hospitalId, departmentId){
        if (hospitalId) {
            $('#doctor').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-hospital-doctors') }}/" + hospitalId,
                success: function (res) {
                    if (res) {
                        $('#doctor').html('<option value="">Select Doctor</option>');
                        $.each(res, function (index, data) {
                            if(data.user)
                            $('#doctor').append('<option value="' + data.id+'">' + data.user?.name + '</option>');
                        });
                        // $('#doctor').val(selectedId).trigger('change');
                        $('#doctor').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }else {
            $('#doctor').empty();
            $('#doctor').append('<option value=""></option>');
        }
    }

    function loadDoctorsDepartment(departmentId){
        if(departmentId){
            var hospital_id = $('#hospital').val();
            $('#doctor').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-department-doctors') }}/" + departmentId+'/'+hospital_id,
                success: function (res) {
                    if (res) {
                        // $('#doctor').empty();
                        $('#doctor').html('<option value="">Select Doctor</option>');
                        $.each(res, function (index, data) {
                            if(data.user)
                            $('#doctor').append('<option value="' + data.id+'">' + data.user?.name + '</option>');
                        });
                        // $('#doctor').val(selectedId).trigger('change');
                        $('#doctor').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }else {
            $('#doctor').empty();
            $('#doctor').append('<option value=""></option>');
        }
    }

    function loadDepartments(hospital_id){
        if (hospital_id) {
            $('#department').html('<option value="">Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-hospital-departments') }}/" + hospital_id,
                success: function (res) {
                    if (res) {
                        // $('#department').empty();
                        $('#department').html('<option value="">Select Departments</option>');
                        $.each(res, function (index, department) {
                            $('#department').append('<option value="' + department.id+'">' + department.title + '</option>');
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching departments:', error);
                }
            });
        } else {
            $('#department').empty();
            $('#department').append('<option value="">Select Departments</option>');
        }
    }

    function loadDoctorsHospitalMDL(hospitalId, departmentId){
        if (hospitalId) {
            $('#doctorSelct').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-hospital-doctors') }}/" + hospitalId,
                success: function (res) {
                    if (res) {
                        $('#doctorSelct').html('<option value="">Select Doctor</option>');
                        $.each(res, function (index, data) {
                            if(data.user)
                            $('#doctorSelct').append('<option value="' + data.user_id+'">' + data.user?.name + '</option>');
                        });
                        // $('#doctorSelct').val(selectedId).trigger('change');
                        $("#doctorSelct").select2({ dropdownParent: "#appointment-modal" });
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

    function loadDoctorsDepartmentMDL(departmentId){
        if(departmentId){
            var hospital_id = $('#HospitalSelct').val();
            $('#doctorSelct').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-department-doctors') }}/" + departmentId+'/'+hospital_id,
                success: function (res) {
                    if (res) {
                        // $('#doctorSelct').empty();
                        $('#doctorSelct').html('<option value="">Select Doctor</option>');
                        $.each(res, function (index, data) {
                            if(data.user)
                            $('#doctorSelct').append('<option value="' + data.user_id+'">' + data.user?.name + '</option>');
                        });
                        // $('#doctorSelct').val(selectedId).trigger('change');
                        $("#doctorSelct").select2({ dropdownParent: "#appointment-modal" });
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

    function loadDepartmentsMDL(hospital_id){
        if (hospital_id) {
            $('#DepartmentSelct').html('<option value="">Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-hospital-departments') }}/" + hospital_id,
                success: function (res) {
                    if (res) {
                        // $('#DepartmentSelct').empty();
                        $('#DepartmentSelct').html('<option value="">Select Departments</option>');
                        $.each(res, function (index, department) {
                            $('#DepartmentSelct').append('<option value="' + department.id+'">' + department.title + '</option>');
                        });
                        $("#DepartmentSelct").select2({ dropdownParent: "#appointment-modal" });
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

    // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

    $('#hospital').on("change", function () {
            loadDepartments($(this).val())
            loadDoctorsHospital($(this).val())
    });

    $('#PatientSelct').on("change", function () {
        let selectedOption = $(this).find('option:selected')
        $('#patient_type').val(selectedOption.data('type'));
    });

    $('#HospitalSelct').on("change", function () {
        // alert('222')
        let selectedOption = $(this).find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
            loadDepartmentsMDL($(this).val())
        }else{
            $('#DepartmentSelct').html('<option value=""></option>');
            $('#department-field').hide();
        }
        loadDoctorsHospitalMDL($(this).val())
    });

    $('#department').on("change", function () {
        loadDoctorsDepartment($(this).val())
    });
    @if(empty($doctor))
        $('#DepartmentSelct').on("change", function () {
            loadDoctorsDepartmentMDL($(this).val())
        });
    @endif

    var table = $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching: true,
        ajax: {
            'type': 'POST',
            'url': '{{ route("admin.appointments.loadApprovalData") }}',
            'data': function(d) {
                // Send additional parameters to the server
                d._token = '{{ csrf_token() }}';
                d.hospital_id = '{{ $hospital->id ?? null }}';
                d.doctor_id = '{{ $doctor->id ?? null }}';
                d.clinic_id = '{{ $clinic->id ?? null }}';
                d.patient_id = '{{ $patient->id ?? null }}';
                d.booking_type = $('#booking_type').val();
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
            { data: 'patient_name', name: 'patients.name'},
            { data: 'booking_date', name: 'booking_date'},
            { data: 'booking_time_slot', name: 'booking_time_slot'},
            { data: 'processed_by', name: 'processed_by'},
            { data: 'status', name: 'status'},
            { data: 'booking_status', name: 'booking_status'},
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

    $(document).on('click', '.complete-link', function() {
        var appointmentId = $(this).data('appointment-id');
        var booking_id = $(this).data('booking-id');
        $('#idCompleted').val(appointmentId);
        $('#completed-appointment .modal-title').text('Complete Booking - ' + booking_id);
    });
    $(document).on('click', '.accept-link', function() {

        var appointmentId = $(this).data('appointment-id');
        var booking_id = $(this).data('booking-id');
        $('#idConfirmed').val(appointmentId);
        $('#confirm-appointment .modal-title').text('Confirm Booking - ' + booking_id);
    });
     $(document).on('click', '.cancel-link', function() {

            var appointmentId = $(this).data('appointment-id');
            var booking_id = $(this).data('booking-id');
            $('#idCancel').val(appointmentId);
            $('#cancel-appointment .modal-title').text('Cancel Booking - ' + booking_id);
    });

    $(document).on('click', '.reschedule-link', function() {
        var booking_data = $(this).data('booking-data');
        var doctor_id = $(this).data('appointment-doctor_id');
        $('#reschedule-modal, #idReschedule').val(booking_data.id);
        let bookingDate = new Date(booking_data.booking_date);
        let day = String(bookingDate.getDate()).padStart(2, '0');
        let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
        let year = bookingDate.getFullYear();
        let formattedDate = `${day}-${month}-${year}`;
        $('#reschedule-modal, #reschedule_date').val(formattedDate);
        $('#reschedule-modal #doctor_id_reschedule').val(doctor_id);
        $('#reschedule-modal .modal-title').text('Reschedule Booking - ' + booking_data.booking_id);
        checkAvailibility(doctor_id, formattedDate, booking_data.booking_time_slot);
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
        $("#cancelViewAppointment").click(function(e){
            e.preventDefault();
            let form = $('#cancelAppointment_form')[0];
            let data = new FormData(form);

            $.ajax({
                url: "{{ route('admin.appointments.appointmentCancel') }}",
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
                url: "{{ route('admin.appointments.appointmentCompleted') }}",
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
                url: "{{ route('admin.appointments.appointmentConfirmed') }}",
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

        function reformatDate(dateStr) {
            const [day, month, year] = dateStr.split('-');
            return `${year}-${month}-${day}`;
        }

        function checkAvailibility2(doctor_id, date){
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.appointments.check_doctor_availability') }}",
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
                var [slotHours, slotMinutes] = slot.split(':').map(Number);

                var isAvailable = slots.find(s => s.slot_text === slot)?.is_available === "1";
                var isSlotEnabled = isAvailable;

                if (selectedDateFormatted === currentDateString) {
                    if (slotHours < currentHours || (slotHours === currentHours && slotMinutes < currentMinutes)) {
                        isSlotEnabled = false;
                    }
                }

                $(this).prop('disabled', !isSlotEnabled);
            });
        }
    }


    function checkAvailibility(doctor_id, date, selectedTime = null){
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ route('admin.appointments.check_doctor_availability') }}",
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

            $('#appointment-modal #booking_date_appointment, #appointment-modal #doctorSelct').on("change", function () {
                checkAvailibility2($('#appointment-modal #doctorSelct').val(), $('#appointment-modal #booking_date_appointment').val());
            });

            $('#reschedule_date').on("change", function () {
                checkAvailibility($('#doctor_id_reschedule').val(), $('#reschedule_date').val());
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
                // console.log(formattedDate);
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
                                window.location.reload();
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
                $form.find('[type="submit"]').prop("disabled", true).text("processing..");

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

                                        if (e_field === 'booking_date') {
                                            $('#booking_date_error').text(e_message)
                                        }

                                        if (e_field === 'booking_time_slot') {
                                            $('#slot_error').text(e_message)
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
                                window.location.reload();
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
    </script>

    
@stop
