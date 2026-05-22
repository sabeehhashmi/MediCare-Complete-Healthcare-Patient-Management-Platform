@include('hospital.layouts.header')
<div class="position-relative mb-5">
    <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
        <div class="card">
            <div class="text-center bg-light rounded px-4 py-3">
                <div class="chat-user-status mt-3">
                    <img src="{{ asset('') }}hospital/assets/images/doctor.png" class="avatar-md rounded-circle" alt="" />
                    <!-- <div class="">
                                                    <div class="status"></div>
                                                </div> -->
                </div>
                <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">DR S.K SHETTY M.S </a></h5>
                    <p class="text-muted mb-0">AL NASEEM MEDICAL CENTER LLC</p>
                    <p class="text-muted mb-0">General Dentist</p>
            </div>

            <div class="p-4 pt-0">
                    <div class="table-responsive mt-3 pb-0">
                        <table class="table align-middle table-sm table-nowrap table-borderless table-centered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Name of Hospital/Clinic Name/ Dental Care/ Home Care :</td>
                                    <th class="fw-bold">AL NASEEM MEDICAL CENTER LLC</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Doctor Name :</td>
                                    <th class="fw-bold">DR S.K SHETTY M.S</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Qualifications :</td>
                                    <th class="fw-bold">ORTHOPIDIC SURGERY, MBBS,M.S</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Specialties :</td>
                                    <th class="fw-bold">General Dentist</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Special Interest :</td>
                                    <th class="fw-bold">Specialit ORTHOPIDIC SURGEON</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Experience :</td>
                                    <th class="fw-bold">40+</th>
                                </tr>
                                <!-- end tr -->
                                
                                <tr>
                                    <td class="text-muted">License Number :</td>
                                    <th class="fw-bold">D391</th>
                                </tr>
                                <tr>
                                    <td class="text-muted">License Type :</td>
                                    <th class="fw-bold">HAAD</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Country of Origin :</td>
                                    <th class="fw-bold">India</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Languages Spoken :</td>
                                    <th class="fw-bold">ENGLISH , HINDI , ARABIC , TELUGU</th>
                                </tr>
                                <!-- end tr -->
                                
                                <tr>
                                    <td class="text-muted">Gender :</td>
                                    <th class="fw-bold">Male</th>
                                </tr>
                                

                                <tr>
                                    <td class="text-muted">Doctor Contact Number</td>
                                    <th class="fw-bold">+91 5968268555</th>
                                </tr>
                                

                                <tr>
                                    <td class="text-muted">Email</td>
                                    <th class="fw-bold">name@email.com</th>
                                </tr>
                                
                                <tr>
                                    <td class="text-muted">Available for instant appointment :</td>
                                    <th class="fw-bold">Yes</th>
                                </tr>
                                <!-- end tr -->

                                <tr>
                                    <td class="text-muted">Direct Contact Number for Appointment</td>
                                    <th class="fw-bold">+91 5968268555</th>
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
                                            <!-- end tr -->
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
                                            <!-- end tr -->
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
                                            <!-- end tr -->
                                        </tbody>
                                        <!-- end tbody -->
                                    </table>
                                    </td>
                                </tr>                                

                                <!--<tr>-->
                                <!--    <td class="text-muted">Insurance Type :</td>-->
                                <!--    <th class="fw-bold">Abu Dhabi National Insurance Company</th>-->
                                <!--</tr>-->
                                
                                
                                <!--<tr>-->
                                <!--    <td class="text-muted">Sub Insurances :</td>-->
                                <!--    <th class="fw-bold">Abu Dhabi National Network</th>-->
                                <!--</tr>-->
                                <!-- <tr>
                                    <td class="text-muted">License Document</td>
                                    <th class="fw-bold"><a href="#" class="btn btn-primary btn-sm mb-1 w-auto" style="height: 30px; width: 60px !important;">View</a></th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Cities Id</td>
                                    <th class="fw-bold"> 5154515</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Cities Expiry</td>
                                    <th class="fw-bold"> 10-05-2028</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Cities Document</td>
                                    <th class="fw-bold"><a href="#" class="btn btn-primary btn-sm mb-1 w-auto" style="height: 30px; width: 60px !important;">View</a></th>
                                </tr>
                                <tr>
                                    <td class="text-muted">Passport Number</td>
                                    <th class="fw-bold"> 10-05-2028</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Passport Document</td>
                                    <th class="fw-bold"><a href="#" class="btn btn-primary btn-sm mb-1 w-auto" style="height: 30px; width: 60px !important;">View</a></th>
                                </tr> -->
                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                </div>
        </div>
        <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="font-size-16 mb-3">About Me</h5>
                            <div class="mt-3">
                                <p class="font-size-15">Dr Samay Tahilramani is a specialist orthodontist with 8 years experience in the field along with nearly 3 years of UAE experience. <br>
                                                        He completed his graduation from Nair hospital dental college in Mumbai in 2010 and his master's from MGM dental college and hospital in 2015.<br>
                                                        Keeping a simplistic and biological friendly approach towards treatment of patients is something that he believes very strongly in. <br>
                                                        He specialises in metal and ceramic braces , Damon systems along with aligner treatments.<br>
                                                        An orthodontist with a  gentle hand  and caring attitude towards the patient is something that describes Dr. Samay aptly.<br> 
                                                        He has worked at the prestigious bhabha atomic research centre as well as global hospital and research center in Mumbai. <br>
                                                        Feel free to schedule an appointment for your smile related concerns and venture into the journey of achieving your dream smile with Dr. Samay at MVR diagnostic&nbsp;centre.</p>
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
                                    72
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
                                    60
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
                                    60
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
                                    112
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
                                    112
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