@include('hospital.layouts.header')
<div class="position-relative mb-5">
    <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
        <div class="card">
            <div class="text-center bg-light rounded px-4 py-3">
                <div class="chat-user-status mt-3">
                    <img src="{{$doctor->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                    <!-- <div class="">
                                                    <div class="status"></div>
                                                </div> -->
                </div>
                <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">DR {{$doctor->user->name ?? null}} </a></h5>
                    <p class="text-muted mb-0">{{$doctor->hospital->name_en ?? null}}</p>
                    <p class="text-muted mb-0">{{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}</p>
            </div>

            <div class="p-4 pt-0">
                    <div class="table-responsive mt-3 pb-0">
                        <table class="table align-middle table-sm table-nowrap table-borderless table-centered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Name of Hospital/Clinic Name/ Dental Care/ Home Care :</td>
                                    <th class="fw-bold">{{$doctor->hospital->name_en ?? null}}</th>
                                </tr>
                                <!-- end tr -->
                                @if(count($doctor->departments))
                                <tr>
                                    <td class="text-muted">Department :</td>
                                    <th class="fw-bold">
                                        {{ $doctor->departments->pluck('title')->implode(', ')}}
                                    </th>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Doctor Name :</td>
                                    <th class="fw-bold">DR {{$doctor->user->name ?? null}}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Qualifications :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->qualifications) ? $doctor->qualifications->pluck('title')->implode(', ') : '' }} <th>
                                    </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Specialties :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}
                                   </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Special Interest :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->interests) ? $doctor->interests->pluck('title')->implode(', ') : '' }}
                                    </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Experience :</td>
                                    <th class="fw-bold">{{$doctor->year_of_experiance ?? 0}}</th>
                                </tr>
                                <!-- end tr -->
                            

                                <tr>
                                    <td class="text-muted">Country of Origin :</td>
                                    <th class="fw-bold">{{$doctor->country->name}}</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Languages Spoken :</td>
                                    <th class="fw-bold">{{ count($doctor->languages) ? $doctor->languages->pluck('title')->implode(', ') : null}}</th>
                                </tr>
                                <!-- end tr -->
                                
                                <tr>
                                    <td class="text-muted">Gender :</td>
                                    <th class="fw-bold">{{ GENDERS[$doctor->gender] ?? 'N/A' }}</th>
                                </tr>
                                

                                <tr>
                                    <td class="text-muted">Clinic Direct Number to Book an Appointment</td>
                                    <th class="fw-bold">{{$doctor->user->phone ? '+ '.$doctor->user->dial_code : null}} {{$doctor->user->phone ?? null}}</th>
                                </tr>
                                

                                <tr>
                                    <td class="text-muted">Email</td>
                                    <th class="fw-bold">{{$doctor->user->email}}</th>
                                </tr>
                                
                                <tr>
                                    <td class="text-muted">Available for instant appointment :</td>
                                    <th class="fw-bold">Yes</th>
                                </tr>
                                <!-- end tr -->

                                <tr>
                                    <td class="text-muted">Doctor Direct number to book an appointment</td>
                                    <th class="fw-bold">{{$doctor->appointment_dial_code ? '+ '.$doctor->appointment_dial_code : null}} {{$doctor->appointment_phone ?? null}}</th>
                                </tr>
                                @if(isset($doctor) && $doctor->documents->count())

<tr>

    <td class="text-muted align-top">
        Doctor Documents :
    </td>

    <th class="fw-bold">

        <div class="d-flex flex-column gap-2">

            @foreach($doctor->documents as $doc)

                <div>

                    <span class="me-2">
                        {{ $doc->title }}
                    </span>

                    <a href="{{ $doc->document }}"
                       target="_blank"
                       class="btn btn-primary btn-sm">

                        View File
                    </a>

                </div>

            @endforeach

        </div>

    </th>

</tr>

@endif
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-sm table-nowrap table-striped table-bordered table-centered my-3">
                                        <thead class="">
                                            <th>Insurance Type</th>
                                            <th>Sub Insurances</th>
                                        </thead>
                                        <tbody>
                                            @foreach($insurances as $insurance)
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">{{$insurance['insurance']->title ?? null}}</b>
                                                </td>
                                                <td>
                                                    <ul>
                                                    @foreach($insurance['sub_insurances'] as $sub_insurance)
                                                        <li>{{$sub_insurance->title ?? null}}</li>
                                                    @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- end tbody -->
                                    </table>
                                    </td>
                                </tr>  
                                <!-- <tr>
                                    <td colspan="2">
                                        <table class="table table-sm table-nowrap table-striped table-bordered table-centered my-3">
                                        <thead class="">
                                            <th>Insurance Type</th>
                                            <th>Sub Insurances</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">Abudhabi National Insurance Company</b>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>ABUDHABI NATIONAL NETWORK</li>
                                                        <li>HIGH-GN,GNPLUS,GNR-INS017</li>
                                                        <li>HIGH-RNA,RNB-INS017</li>
                                                        <li>HIGH-SILVER-INS017</li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <b class="fw-bold">AETNA</b> 
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>AHP-TPA005</li>
                                                        <li>IHP-TPA005</li>
                                                        <li>MOFA-TPA005</li>
                                                        <li>RHP-TPA005</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">AL Buhaira National Insurance Company</b>
                                                </td>
                                                <td>
                                                <ul>
                                                    <li>Al Buhaira Network</li>
                                                    <li>MEDIUM-LIMITED-INS020</li>
                                                    <li>HIGH-COMPREHENSIVE PLUS-INS020</li>
                                                    <li>HIGH-COMPREHENSIVE-INS020</li>
                                                    <li>MEDIUM-STANDARDS-INS020</li>
                                                    <li>MEDIUM-RESTRICTED-INS020</li>
                                                </ul>
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                    </td>
                                </tr> -->
                            </tbody>

                        </table>
                    </div>
                </div>
        </div>
        <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="font-size-16 mb-3">About Me</h5>
                            <div class="mt-3">
                                {!! $doctor->profile_desciription !!}
                            </div>
                        </div>
                    </div>
        <div class="row mt-4">
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <a href="#!" class="card bg-white">
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
                <a href="#!" class="card bg-white">
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
                <a href="#!" class="card bg-white">
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
                <a href="#!" class="card bg-white">
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
                <a href="#!" class="card bg-white">
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
        </div>
    </div>
</div>
@include('hospital.layouts.footer')