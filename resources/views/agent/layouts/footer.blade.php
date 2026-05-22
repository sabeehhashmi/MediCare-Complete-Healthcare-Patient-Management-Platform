
<footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <script>document.write(new Date().getFullYear())</script> © {{config('global.site_name')}}.
                            </div>
                            
                        </div>
                    </div>
                </footer>
            </div>


        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Booking Id: <span id="modal_booking_id">#MYDW1025</span></h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form novalidate>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="font-size-16">Patient Details</h4>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <img style="width: 80px; height: 80px; border-radius: 5px;" src="{{ asset('') }}hospital/assets/images/users/avatar-2.jpg" alt="Generic placeholder image" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 id="modal-user-name">John Doe</h4>
                                                <h5 class="text-muted"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 20px; justify-content: space-between;">
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15" ><i class="bx bx-envelope me-2" ></i> <span id="modal-user-email">test@example.com<span></h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxs-phone-call me-2"></i> <span id="modal-user-number">+971-50-1234567</span></h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxl-whatsapp-square me-2"></i> <span id="modal-patient-whatsapp">+971-50-1234567 </span></h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bx-calendar me-2"></i> <span id="modal-patient-dob">10-05-1999</span></h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="fas fa-transgender me-2"></i><span id="modal-patient-gender"> Male</span></h5>
                                        </div>
                                        <div class="card p-0 overflow-hidden mt-3 shadow-none">
                                            <div class="mail-list">
                                                <a href="#" class="border-bottom">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0" > <span id="modal-appointment-date">05 May 2024</span></h5>
                                                            <span class="text-muted font-size-13" ><span id="modal-booking-time">09:30 AM</span></span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-map font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0"><span id="modal-hospital-name">{{ Auth::user()->name }} Hospital</span></h5>
                                                            @php
                                                            $hospital = DB::table('hospitals')->where('user_id', Auth::user()->id)->first();
                                                            @endphp
                                                       
                                                        </div>
                                                        <div class="flex-shrink-0"></div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="modal-footer justify-content-center">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light">Confirm</button>
                                <button type="button" class="btn btn-dark waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#cancel-appointment">Cancel</button>
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-bs-target="#reschedule-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Reschedule</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#followup-modal">Follow Up</button>
                                <button type="button" class="btn btn-success waves-effect waves-light">Completed</button>
                            </div>
                        </div> -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">Cancel</button>
                            <a href="#" id="details-link-appointment" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
        <!-- end modal-->

        <!-- Modal -->
        <div class="modal fade" id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Reschedule Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('agent.rescheduleAppointment')}}" id="reschedule_appointment_form" class="custom-form">
                        @csrf
                        <input type="hidden" id="idReschedule" name="id" value="">
                        <input type="hidden" id="doctorIdReschedule" name="doctor_id" value="">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" name="reschedule_date" class="form-control" id="reschedule_date" placeholder="Select Date" />
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
                                    
                                    @php 
                                    $time_slot = TIME_SLOTS;
                                    @endphp
                                    @for($i = 0; $i < count($time_slot); $i++)
                                        <span>
                                                
                                            <input type="radio" class="availiblity" id="sat{{$i+17+$i}}" disabled name="booking_time_slot" {{($row->booking_time_slot ?? null) == $time_slot[$i] ? 'checked' : ''}}  value="{{$time_slot[$i]}}"class="idReschedule time-slot checkbx-style" 
                                            
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
                    <button type="button" id="confirm_reschedule_appointment" class="btn btn-primary">Confirm Reschedule</button>
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
                            <form method="post" id="book_appointment_form" action="{{route('agent.saveAppointment')}}" class="custom-form">
                            @csrf    
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="bookTypeSelect">Booking type </label>
                                    <div class="position-relative">
                                        <select name="bookTypeSelect" id="bookTypeSelect" class="select2-single" data-placeholder="Select type">
                                            <option></option>
                                            @if(count($bookingTypes ?? []))
                                            @foreach($bookingTypes as $type)
                                            <option value="{{ $type->name }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="">Select Hospital/ Clinic</label>
                                    <div class="position-relative select-custom-icon select2-error">
                                        <select name="hospital" id="hospitalSelct" class="select2-single" data-placeholder="Select Hospital">
                                            <option></option>
                                            @if(count($hospitals ?? []))
                                            @foreach($hospitals as $hosp)
                                                <option value="{{ $hosp->id }}" data-type="{{$hosp->type}}" >{{ $hosp->name_en }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-4" id="department-field">
                                    <label class="form-label" for="">Select Department </label>
                                    <div class="position-relative select-custom-icon select2-error">
                                    <select name="department" id="DepartmentSelct" class="select2-single" data-placeholder="Select Department">
                                        <option value=""></option>
                                        @if(count($departments ?? []))
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    </div>
                                </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="">Select Doctor </label>
                                        <div class="position-relative select-custom-icon select2-error">
                                            <select name="doctor" id="doctorSelct" class="select2-single" data-placeholder="Select Doctor">
                                                <option></option>
                                                @if(count($doctors ?? []))
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->user_id }}" >{{ $doctor->user->name }}</option>
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
                                                @foreach($patients as $pati)
                                                <option value="{{ $pati['id'] }}" {{($patient->id ?? null) == $pati['id'] ? 'selected': '' }} data-type="{{$pati['type']}}">{{ $pati['fullname'] }} ({{isset($pati['phone'])?$pati['phone']:''  }})</option>
                                                @endforeach
                                                @endif
                                            </select>
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
                <div class="modal fade" id="upload-docs" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Documents</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{route('agent.uploadAppointmentDocs')}}" id="confirm_docs_upload_form" class="custom-form"
                            enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="idUplpad" name="booking_id" value="">
                                
                                <div class="row">

                                    <!-- Lab Report Upload -->
                                    <div class="col-md-12 mb-3 lab_upload">
                                        <label class="form-label" for="">
                                            Lab Report
                                            <small class="text-muted">(PDF / Image)</small>
                                        </label>
                                        <div class="position-relative">
                                            <input type="file"
                                            name="lab_report[]"
                                            class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            multiple>
                                        </div>
                                    </div>
                                
                                    <!-- X-Ray Upload -->
                                    <div class="col-md-12 mb-3 xray_upload">
                                        
                                        <label class="form-label" for="">
                                            X-Ray
                                            <small class="text-muted">(Image preferred)</small>
                                        </label>
                                        <div class="position-relative">
                                            <input type="file"
                                                   name="xray[]"
                                                   class="form-control"
                                                   accept=".jpg,.jpeg,.png,.pdf"
                                                   multiple>
                                        </div>
                                    </div>
                                
                                </div>
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button cancel_docs_upload" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                            <button type="button" id="confirm_docs_upload" class="btn btn-primary">Upload Documents</button>
                        </div>
                        </div>
                    </div>
                </div>
            <!-- Appointment doctor Modal -->
            <div class="modal fade" id="appointment-modal-doctor" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointments </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('agent.saveDrAppointment')}}" class="custom-form" id="book_appointment_form_dr">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="bookTypeSelectDR">Booking type </label>
                                    <div class="position-relative">
                                        <select name="bookTypeSelectDR" id="bookTypeSelectDR" class="select2-single" data-placeholder="Select type">
                                            <option></option>
                                                <option value="1" >New Consultation</option>
                                                <option value="2" >Follow-up Consultation</option>
                                                <option value="3" >Second Opinion</option>
                                                <option value="4" >Online Consultation</option>
                                                <option value="5" >Emergency Consultation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="">Select Clinic/ Hospital </label>
                                    <div class="position-relative">
                                        <select name="hospital" disabled id="hospitalSelctDR" class="select2-single" data-placeholder="Select Hospital">
                                            <option></option>
                                            @if(count($hospitals ?? []))
                                            @foreach($hospitals as $hospital)
                                                <option selected value="{{ $hospital->id }}" >{{ $hospital->name_en ?? null }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="">Select Patient </label>
                                    <div class="position-relative">
                                        <select name="patient" id="PatientSelctDR" class="select2-single" data-placeholder="Select Patient">
                                            <option></option>
                                            @if(count($patients ?? []))
                                            @foreach($patients as $pati)
                                            <option value="{{ $pati['id'] }}" {{($patient->id ?? null) == $pati['id'] ? 'selected': '' }} data-type="{{$pati['type']}}">{{ $pati['fullname'] }} ({{isset($pati['phone'])?$pati['phone']:''  }})</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="">Select Doctor </label>
                                    <div class="position-relative">
                                        @if($param['doctor_id'] ?? null)
                                        <input type="hidden" name="doctor" value="{{$doctor->user_id}}">
                                        <select name="doctor" disabled id="doctorSelct1" class="select2-single" data-placeholder="Select Doctor">
                                            @if(count($doctors ?? []))
                                            @foreach($doctors as $doctor)
                                                <option selected value="{{ $doctor->user_id }}" >{{ $doctor->user->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @else
                                        <select name="doctor" id="doctorSelct1" class="select2-single" data-placeholder="Select Doctor">
                                            <option value=""></option>
                                            @if(count($doctors ?? []))
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->user_id }}" >{{ $doctor->user->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @endif
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
                                        <input type="text" class="form-control flatpicker-input" name="booking_date" id="booking_date_appointment" placeholder="Select Date" />
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
                                                    
                                                <input type="radio" class="availiblity" id="den{{$i+17+$i}}" disabled name="booking_time_slot" class="idReschedule time-slot checkbx-style" value="{{$time_slot[$i]}}"/>
                                                    <label for="den{{$i+17+$i}}">{{$time_slot[$i]}}</label>
                                        </span>
                                                </span>
                                            @endfor


                                    </div>
                                <!-- </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="book_appointment_dr">Confirm</button>
                    </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="followup-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Follow Up </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('agent.saveAppointmentFollowup') }}" class="custom-form" id="appointment_followup">
                            @csrf
                            <input type="hidden" value="{{$appointment->id ?? null}}" name="id" id="appointment-id">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Select Date & time</label>
                                    <div class="position-relative">
                                        <input type="text" name="followup_date" class="form-control flatpicker-input-date-time" id="followup_date" placeholder="Select Date & time" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="position-relative">
                                        <label class="form-label" for="">Follow Up Remark</label>
                                        <textarea class="form-control" id="followup_details" name="followup_details" rows="3" placeholder="Enter Follow Up Remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="addFollowup">Update</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Complete Booking - #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="completedappointmentform" class="custom-form">
                        @csrf
                           <input type="hidden" id="idCompleted" value="" name="appointment_id">  
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="{{ asset('') }}hospital/assets/images/success-img.svg" class="img-fluid" alt="">
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
                        <button type="button"  id="buttoncompleted" class="btn btn-primary">Completed</button>
                    </div>
                </div>
                
            </div>
        </div>


        <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Booking - #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action=""  id="cancelAppointment_form"  class="custom-form">
                           @csrf
                           <input type="hidden" id="idCancel" value="" name="appointment_id">   
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="{{ asset('') }}hospital/assets/images/cancel-img.svg" class="img-fluid" alt="">
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
                        <button type="button" id="cancelAppointment" class="btn btn-dark" style="width: 120px;">Cancel</button>
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

    
    
        </div>
        <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11;"></div>


    <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="assets/libs/metismenujs/metismenujs.min.js"></script> -->
    <!-- <script src="assets/libs/simplebar/simplebar.min.js"></script> -->
    <!-- <script src="assets/libs/eva-icons/eva.min.js"></script> -->

        
    <!-- <script src="assets/js/pages/dashboard.init.js"></script> -->
    <script src="{{ asset('') }}hospital/assets/js/dataTables.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- plugin js -->
    <script src="{{ asset('') }}hospital/assets/libs/fullcalendar/index.global.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('') }}hospital/assets/js/flatpickr.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/js/app.js"></script>
    <script src="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('admin-assets/assets/js/app.js') }}"></script>
    
    <script>
    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today"
    });
    
    $("#reschedule_date").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today",
        // disable:
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
        $("#DepartmentSelct,#hospitalSelct, #PatientSelct, #doctorSelct, #memberSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#PatientSelctDR, #doctorSelct1, #hospitalSelctDR").select2({ dropdownParent: "#appointment-modal-doctor" });
        
        $("#bookTypeSelect").select2({ dropdownParent: "#appointment-modal" });
        $("#bookTypeSelectDR").select2({ dropdownParent: "#appointment-modal-doctor" });
    });
</script>
<script>
$('.table-responsive').on('click', 'button[data-bs-toggle="dropdown"]', function (e) {
  const { top, left } = $(this).next(".dropdown-menu")[0].getBoundingClientRect();
  $(this).next(".dropdown-menu").css({
    position: "fixed",
    inset: "unset",
    transform: "unset",
    top: top + "px",
    left: left + "px",
  });
});

if ($('.table-responsive').length) {
  $(window).on('scroll', function (e) {
    $('.table-responsive .dropdown-menu').removeClass('show');
    $('.table-responsive button[data-bs-toggle="dropdown"]').removeClass('show');
  });
}

$('.reset-form').on('click', function() {
    var form = $(this).closest('form')[0];
    form.reset();
    $(form).find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="tel"], input[type="url"]').val('');
    
    $(form).find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    
    $(form).find('select').prop('selectedIndex', 0);
    
    $(form).find('select').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).val(null).trigger('change');
        }
    });
    
    $(form).find('textarea').val('');
    
    const domEditableElement = document.querySelector('.ck-editor__editable');
    
    const editorInstance = domEditableElement.ckeditorInstance;
    editorInstance.setData('');
});

$('#upload-docs #confirm_docs_upload').on('click', function (e) {
    e.preventDefault();

    var $form = $('#confirm_docs_upload_form');
    var formData = new FormData($form[0]);
    var btn=$('#confirm_docs_upload');
    var modal=$('#upload-docs');
    btn.html('Uploading..');

    btn.prop('disabled', true);
       
    App.loading(true);

    $.ajax({
        type: "POST",
        url: $form.attr('action'),
        data: formData,
        processData: false, // REQUIRED
        contentType: false, // REQUIRED
        cache: false,
        dataType: 'json',

        success: function (res) {
            setTimeout(() => {
    window.location.reload();
}, 2000);

    App.loading(false);
    btn.prop('disabled', false);
    btn.html('Upload Documents');
    $form[0].reset();

// ✅ CLOSE MODAL (Bootstrap 5 safe way)

modal.modal('hide');

    if (res.status === 1) {

        App.alert(res.message || 'Documents uploaded successfully', 'Success!', 'success');

        // ✅ RESET FORM
       

        // OPTIONAL redirect
        if (res.oData && res.oData.redirect) {
            setTimeout(() => {
                window.location.href = res.oData.redirect;
            }, 1500);
        }

    } else {
        App.alert(res.message || 'Upload failed', 'Oops!', 'error');
    }
},
        error: function (e) {
            $('#confirm_docs_upload').prop('disabled', false);
            App.loading(false);
            console.error(e);
            App.alert("Network error, please try again", 'Oops!', 'error');
        }
    });
});
$('.upload-link').click(function() {

var booking_data = $(this).data('booking-data');
var booking_id = $(this).data('booking-id');
var file_type = $(this).data('file-type');
if(file_type && file_type=='xray'){
    $('.xray_upload').show();
    $('.lab_upload').hide();
}
if(file_type && file_type=='lab'){
    $('.xray_upload').hide();
    $('.lab_upload').show();
}
$('#upload-docs, #idUplpad').val(booking_id);
$('#upload-docs .modal-title').text('Upload Documents - ' + booking_data.booking_id);
});


</script>
    </body>


</html>