@extends('admin.template.layout')
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    @stop
@section('content')
<div class="row">
                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.hospitals.index')}}" class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-15">Hospitals</h6>
                                                <h4 class="mt-3 pt-1 mb-0 font-size-22">{{$hospital}}  </h4>
                                            </div>
                                            <div class="">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded bg-primary ">
                                                        <i class="fi fi-rr-hospital d-flex align-items-center font-size-24 mb-0 text-white"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.clinics.index')}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-15">Clinic</h6>
                                                <h4 class="mt-3 pt-1 mb-0 font-size-22">{{$clinic}}  </h4>
                                            </div>
                                            <div class="">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded bg-secondary-subtle ">
                                                        <i class="fi fi-rr-hospitals d-flex align-items-center font-size-24 mb-0 text-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.doctors.index')}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Doctors</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$doctor}}  </h4>
                                                
                                            </div>

                                            <div class="">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded bg-danger ">
                                                        <i class="fi fi-rr-user-md d-flex align-items-center font-size-24 mb-0 text-white"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.patients.index')}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Patients</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$patient}}  </h4>
                                                </div>

                                            <div class="">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded bg-primary-subtle ">
                                                        <i class="fi fi-rr-user-injured d-flex align-items-center font-size-24 mb-0 text-primary"></i>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.appointments.index')}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Total Appointments</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$totalappointments}}</h4>
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
                                <a href="{{route('admin.appointments.index', ['booking_status' => BOOKING_STATUS_PENDING])}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Pending Appointments</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$pendingappointments}}</h4>
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
                                <a href="{{route('admin.appointments.index', ['booking_status' => BOOKING_STATUS_CONFIRMED])}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Confirmed Appointments</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$confirmappointments}}  </h4>
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
                                <a href="{{route('admin.appointments.index', ['booking_status' => BOOKING_STATUS_COMPLETED])}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Completed Appointments</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$completedappointments}}  </h4>
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
                                <a href="{{route('admin.appointments.index', ['booking_status' => BOOKING_STATUS_CANCELLED])}}"  class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 font-size-15">Cancelled Appointments</h6>
                                                <h4 class="mt-3 mb-0 font-size-22">{{$cancelledappointments}}</h4>
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
                            
                            
                            
                            <div class="col-md-6 col-xl-3 mb-4">
                                <a href="{{route('admin.appointments.index', ['booking_type' => 'New Consultation'])}}"  class="card">
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
                                <a href="{{route('admin.appointments.index', ['booking_type' => 'Follow-up Consultation'])}}"  class="card">
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
                                <a href="{{route('admin.appointments.index', ['booking_type' => 'Second Opinion'])}}"  class="card">
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
                                <a href="{{route('admin.appointments.index', ['booking_type' => 'Online Consultation'])}}"  class="card">
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
                                <a href="{{route('admin.appointments.index', ['booking_type' => 'Emergency Consultation'])}}"  class="card">
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
                        <!-- END ROW -->


                        <div class="row  d-none">
                            <div class="col-xl-8 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Patients Report</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <span class="fw-semibold">Sort By:</span>
                                                        <span class="text-muted">Yearly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Today</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="font-size-22">$23,590.00</h4>
                                            </div>
                                            <div class="col-md-8">
                                                <ul class="list-inline main-chart text-md-end mb-0">
                                                    <li class="list-inline-item chart-border-left me-0 border-0">
                                                        <h4 class="text-primary font-size-22">$584k <span class="text-muted d-inline-block font-size-14 align-middle ms-2">Incomes</span></h4>
                                                    </li>
                                                    <li class="list-inline-item chart-border-left me-0">
                                                        <h4 class="font-size-22">$497k<span class="text-muted d-inline-block font-size-14 align-middle ms-2">Expenses</span>
                                                        </h4>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            <div id="sales-report" data-colors='["#1f58c7","#e6ecf9"]' class="apex-charts" dir="ltr"></div>  
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Source of Purchases</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-muted" href="#"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        Today<i class="mdi mdi-chevron-down ms-1"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Today</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div>
                                            <div id="chart-radialBar" class="apex-charts" data-colors='["#1f58c7"]'></div>
                                        </div>

                                       <div class="mt-4 px-1 pt-1">
                                            <div class="mx-n4" data-simplebar style="max-height: 214px;">
                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-facebook font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Facebook</h5>
                                                            <p class="text-muted text-truncate mb-0">3.2k Sale - 4.2k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                50% <i class="mdi mdi-arrow-top-right text-success align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-twitter font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Twitter</h5>
                                                            <p class="text-muted text-truncate mb-0">3.1k Sale - 3.7k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                34% <i class="mdi mdi-arrow-bottom-left text-danger align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-linkedin font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Linkedin </h5>
                                                            <p class="text-muted text-truncate mb-0">2.1k Sale - 4.3k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                64% <i class="mdi mdi-arrow-top-right text-success align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-youtube font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Youtube</h5>
                                                            <p class="text-muted text-truncate mb-0">5.7k Sale - 8.4k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                47% <i class="mdi mdi-arrow-bottom-left text-danger align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-google font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Google</h5>
                                                            <p class="text-muted text-truncate mb-0">2.4k Sale - 3.7k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                61% <i class="mdi mdi-arrow-bottom-left text-danger align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-bottom sale-social-box">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar">
                                                                <div class="avatar-title rounded bg-primary-subtle ">
                                                                    <i class="bx bxl-github font-size-24 mb-0 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <h5 class="font-size-15 mb-1 text-truncate">Github</h5>
                                                            <p class="text-muted text-truncate mb-0">1.3k Sale - 8.6k Like</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="font-size-14 mb-0 text-truncate w-xs bg-light p-2 rounded text-center">
                                                                50% <i class="mdi mdi-arrow-bottom-left text-danger align-middle"></i></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END ROW -->

                        <div class="row d-none">
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-1">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title">Sales History</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-muted" href="#"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal font-size-22"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Today</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mx-n4" data-simplebar style="max-height: 390px;">
                                            <p class="text-muted mb-0">Recent</p>
                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-4.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">Neal Matthews</h5>
                                                        <p class="font-size-14 text-muted text-truncate mb-0">United States</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-danger-subtle text-danger ">- $62.45</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-5.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">Jamal Burnett</h5>
                                                        <p class="font-size-14 text-muted text-truncate mb-0">India</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-success-subtle text-success ">+ $45.84</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="text-muted mt-3 mb-0">Yesterday</p>

                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-7.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">Barry Dick </h5>
                                                        <p class="text-muted text-truncate mb-0">United States</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-success-subtle text-success ">+ $25.52</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-8.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">Ronald Taylor</h5>
                                                        <p class="text-muted text-truncate mb-0">United States</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-danger-subtle text-danger ">- $84.45</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-2.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">Jacob Hunter</h5>
                                                        <p class="text-muted text-truncate mb-0">England</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-success-subtle text-success ">+ $53.23</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border-bottom sales-history">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-3.jpg" class="rounded-circle avatar img-thumbnail" alt="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <h5 class="font-size-15 mb-1 text-truncate">William Cruz</h5>
                                                        <p class="text-muted text-truncate mb-0">United States</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge font-size-12 bg-success-subtle text-success ">+ $42.63</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="p-3 border-bottom">
                                        <div class="row">
                                            <div class="col-xl-4 col-7">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 avatar me-3 d-sm-block d-none">
                                                        <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-6.jpg" alt="" class="img-fluid d-block rounded-circle">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="font-size-16 mb-1 text-truncate"><a href="#" class="text-body">Jennie Sherlock</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Online</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-8 col-5">
                                                <ul class="list-inline user-chat-nav text-end mb-0">
                                                    <li class="list-inline-item">
                                                        <div class="dropdown">
                                                            <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="bx bx-search"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                                                                <form class="px-2">
                                                                    <div>
                                                                        <input type="text" class="form-control border bg-light-subtle " placeholder="Search...">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </li>
    
                                                    <li class="list-inline-item">
                                                        <div class="dropdown">
                                                            <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Profile</a>
                                                                <a class="dropdown-item" href="#">Archive</a>
                                                                <a class="dropdown-item" href="#">Muted</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>                                                                                                                                                                                                                                                                                        
                                            </div>

                                        </div>
                                    </div>

                                    <div class="small-chat">
                                        <div class="chat-conversation p-3" data-simplebar style="max-height: 316px;">
                                            <ul class="list-unstyled mb-0">
                                                <li class="chat-day-title"> 
                                                    <span class="title">Today</span>
                                                </li>

                                                <li>
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-6.jpg" class="rounded-circle avatar" alt="">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:00</span></div>
                                                                        <p class="mb-0">Hi Jordan! <br>
                                                                            Feels like it's been a while! Home are you?
                                                                           with an ongoing project?</p>
                                                                        
                                                                    </div>
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
    
                                                <li class="right">
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:02</span></div>
                                                                        <p class="mb-0 text-start">Hi Martin, Glad to hear from you, I'm fine,what about you? and how it's going with the velocity website?
                                                                     
                                                                        </p>
                                                                        
                                                                    </div>
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-3.jpg" class="rounded-circle avatar" alt="">
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </li>
    
                                                <li>
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-6.jpg" class="rounded-circle avatar" alt="">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:04</span></div>
                                                                        <p class="mb-0">
                                                                            Super, I will get you the new brief for our own site over to you this evening, so you have time to read over I'm good thank you!
                                                                        </p>
                                                                    </div>
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </li>
    
                                                <li class="right">
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:08</span></div>
                                                                        <p class="mb-0 text-start">
                                                                            Of course I can, just catching up with Steve on it and i'll write 
                                                                            it out.
                                                                        </p>
    
                                                                        <p class="mb-0 text-start mt-2">
                                                                            img-1.jpg & img-2.jpg images for a New Projects
                                                                        </p>
    
                                                                        <ul class="list-inline message-img mt-2 mb-0">
                                                                            <li class="list-inline-item message-img-list">
                                                                                <a class="d-inline-block" href="#">
                                                                                    <img src="{{ URL::asset('admin-assets/assets') }}/images/small/img-1.jpg" alt="" class="rounded img-thumbnail">
                                                                                </a>                                                                  
                                                                            </li>
                    
                                                                            <li class="list-inline-item message-img-list">
                                                                                <a class="d-inline-block" href="#">
                                                                                    <img src="{{ URL::asset('admin-assets/assets') }}/images/small/img-2.jpg" alt="" class="rounded img-thumbnail">
                                                                                </a>                                                                 
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-3.jpg" class="rounded-circle avatar" alt="">
                                                        </div>
                                                    </div>
                                                </li>
    
                                                <li>
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-6.jpg" class="rounded-circle avatar" alt="">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:06</span></div>
                                                                        <p class="mb-0">
                                                                            Thank You very much, I am waiting Project.
                                                                        </p>
                                                                    </div>
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                
                                                <li class="right">
                                                    <div class="conversation-list">
                                                        <div class="d-flex">
                                                            <div class="flex-1">
                                                                <div class="ctext-wrap">
                                                                    <div class="ctext-wrap-content">
                                                                        <div class="conversation-name"><span class="time">10:08</span></div>
                                                                        <p class="mb-0 text-start">
                                                                            When someone thanks us, our automatic response is to say, “You’re welcome.” This is something that we have 
                                                                            learned from our parents and family and have been doing for a long time.
                                                                        </p>
                                                                    </div>
                                                                    
                                                                    <div class="dropdown align-self-start">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#">Copy</a>
                                                                            <a class="dropdown-item" href="#">Save</a>
                                                                            <a class="dropdown-item" href="#">Forward</a>
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <img src="{{ URL::asset('admin-assets/assets') }}/images/users/avatar-3.jpg" class="rounded-circle avatar" alt="">
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
    
                                        <div class="p-3 border-top">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control border bg-light-subtle " placeholder="Enter Message...">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary chat-send w-md waves-effect waves-light"><span class="d-none d-sm-inline-block me-2">Send</span> <i class="mdi mdi-send float-end"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-xl-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title">Top Sales Countries</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <span class="fw-semibold">Sort By:</span>
                                                        <span class="text-muted">Yearly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Today</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                               <div class="text-center">
                                                <h4 class="font-size-18">23,568</h4>
                                                    <p class="mb-1 text-muted font-size-14">Completed <span class="badge bg-success-subtle text-success ">+40%</span></p>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="text-center">
                                                    <h4 class="font-size-18">12,865</h4>
                                                     <p class="mb-1 text-muted font-size-14">Pending <span class="badge bg-danger-subtle text-danger ">-10%</span></p>
                                                    
                                                </div>
                                             </div>

                                             <div class="col-4">
                                                <div class="text-center">
                                                    <h4 class="font-size-18">2,355</h4>
                                                     <p class="mb-1 text-muted font-size-14">Cancel <span class="badge bg-success-subtle text-success ">+20%</span></p>
                                                    
                                                </div>
                                             </div>
                                        </div>

                                        <div>
                                            <div id="sales-countries" data-colors='["#1f58c7"]'  class="apex-charts" dir="ltr"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                          
                        </div> 
                        <!-- end row -->

                        <div class="row d-none">
                            <div class="col-xl-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title">Latest Appointments</h5>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
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
                                                @foreach ($appointments as $key => $appointment)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ $appointment->booking_id }}</td>
                                                        <td>
                                                            <div class="patient-link"><img src="{{ $appointment->doctor->user->user_img_url ?? null }}" width="32" height="32" class="me-2" alt="" />DR {{$appointment->doctor->user->first_name ??''}} {{$appointment->doctor->user->last_name ??''}}</div>
                                                            <!-- <div class="doctor-link"><img src="{{ $appointment->doctor->user->user_img_url ?? null }}" width="32" height="32" class="me-2" alt="" />{{$appointment->doctor->user->first_name ??''}} {{$appointment->doctor->user->last_name ??''}}</div> -->
                                                        </td>
                                                        <td>
                                                        <a href="#!" class="patient-link"><img src="{{ $appointment->member ? ($appointment->member->user_img_url ?? null) : ($appointment->user->user_img_url ?? null) }}" width="32" height="32" class="me-2" alt="" />{{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}</a>
                                                            <!-- <div class="patient-link"><img src="{{ $appointment->user->user_img_url ?? null }}" width="32" height="32" class="me-2" alt="" />DR {{$appointment->user->first_name ??''}} {{$appointment->user->last_name ??''}}</div> -->
                                                        </td>
                                                        <td>{{ $appointment->booking_time_slot }}</td>
                                                        <!-- <td>DR {{$appointment->doctor->user->name ?? null}}<br>Specialist- {{($appointment->doctor->specialities ?? null) ? $appointment->doctor->specialities->pluck('name_en')->unique()->implode(', ') : null}}</td> -->
                                                        <!-- <td>{{$appointment->department->title ?? ""}}</td> -->
                                                        <td><div class="status-badge @if(strtolower($appointment->booking_status) == 'pending') pending-badge 
                                                                                        @elseif(strtolower($appointment->booking_status) == 'completed') completed-badge 
                                                                                        @elseif(strtolower($appointment->booking_status) == 'cancelled') cancelled-badge 
                                                                                        @elseif(strtolower($appointment->booking_status) == 'confirmed') confirmed-badge 
                                                                                        @elseif(strtolower($appointment->booking_status) == 'rescheduled') reschedule-badge 
                                                                                        @endif">
                                                                <span></span> {{strtoupper($appointment->booking_status)}}
                                                            </div></td>
                                                        <td>{{ $appointment->booking_date }}</td>
                                                        <td>
                                                            <div class="dropdown mt-4 mt-sm-0">
                                                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="{{route('admin.appointments.view',['id'=>$appointment->id])}}">View Appointment</a>
                                                                        @if ((strtolower($appointment->booking_status) === 'pending' || strtolower($appointment->booking_status) === 'confirmed' || strtolower($appointment->booking_status) === 'rescheduled'))
                                                                        <a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal"  data-bs-target="#cancel-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id}}">Cancel Appointment</a>
                                                                        @endif
                                                                        @if ((strtolower($appointment->booking_status) === 'pending' || strtolower($appointment->booking_status) === 'rescheduled'))
                                                                        <a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal"  data-bs-target="#confirm-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id}}">Confirm Appointment</a>
                                                                        @endif
                                                                        @if (strtolower($appointment->booking_status) === 'confirmed' )
                                                                        <a class="dropdown-item complete-link" href="#!" data-bs-toggle="modal"  data-bs-target="#completed-appointment" data-booking-id="{{$appointment->booking_id}}" data-appointment-id="{{ $appointment->id}}">Complete Appointment</a>
                                                                        @endif
                                                                        @if ((strtolower($appointment->booking_status) === 'pending' || strtolower($appointment->booking_status) === 'confirmed'))
                                                                        <a class="dropdown-item reschedule-link" href="#!" data-bs-toggle="modal" data-bs-target="#reschedule-modal"  data-booking-id="{{$appointment->booking_id}}" data-booking-data="{{json_encode($appointment)}}" data-appointment-doctor_id="{{$appointment->doctor->user_id ?? null}}">Reschedule Appointment</a>
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
                        <!-- end row -->


<!-- End Page -->

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
<!-- cancel Modal -->
<div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- {{$appointment->booking_id??''}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="cancelAppointment_form" class="custom-form">
                        @csrf 
                            <div class="row">
                            <input type="hidden" id="idCancel" value="{{$appointment->id??''}}" name="appointment_id">    
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

       


    

      
<!-- confirm Modal -->
<div class="modal fade" id="confirm-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel1">Confirm Appointment- {{$appointment->booking_id??''}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action=""  id="confirmAppointment_form" class="custom-form">
                @csrf 
                    <div class="row">
                    <input type="hidden" id="idConfirmed" value="{{$appointment->id??''}}" name="appointment_id">    
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
<!-- reschedule modal -->
<div class="modal fade"  id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel3">Reschedule Booking {{$appointment->booking_id??''}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.appointments.appointmentRescheduled')}}" id="reschedule_appointment_form" class="custom-form">
            @csrf 
                <div class="row">
                <input type="hidden" id="idReschedule" value="" name="appointment_id">    
                <input type="hidden" id="doctor_id_reschedule" value="" name="doctor_id">    
                <div class="col-12 mb-3">
                        <label class="form-label" for="username">Select Date</label>
                        <div class="position-relative">
                            <input type="text" name="booking_date" value="{{$appointment->booking_date??''}}" class="form-control flatpicker-input" id="reschedule_date" placeholder="Select Date" />
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
                                <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot"  value="{{$time_slot[$i]}}" class="time-slot checkbx-style availiblity" {{!empty($appointment->booking_time_slot) && $time_slot[$i] == $appointment->booking_time_slot ? 'checked': ''}} />
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

<!-- complete modal -->
<div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel2">Complete Appointment- {{$appointment->booking_id??''}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action=""  id="completed_form" class="custom-form">
                @csrf 
                    <div class="row">
                    <input type="hidden" id="idCompleted" value="{{$appointment->id??''}}" name="appointment_id">    
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
<!-- followup Modal -->
<div class="modal fade" id="followup-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Follow Up </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.appointments.saveAppointmentFollowup') }}" class="custom-form" id="appointment_followup">
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
@stop

@section('page_script')
<!-- apexcharts -->
<script src="{{ URL::asset('admin-assets/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="{{ URL::asset('admin-assets/assets/js/pages/dashboard-sales.init.js') }}"></script>
<script>
// $(document).ready(function(){
    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today",
    });
// })
function reformatDate(dateStr) {
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;
}
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
             var reason = $('#reschedule_appointment_form textarea[name="reason"]').val().trim();
            if (reason === '') {
                App.alert('Please enter a reason for rescheduling the appointment', 'Oops!', 'error');
                $('#reschedule_appointment_form textarea[name="reason"]').focus();
                return false;
            }
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
        function checkAvailibility2(doctor_id, date){
        // console.log(doctor_id);
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