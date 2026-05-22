<style>
    .overlay-product-link{
        content: '';
        width:100%;
        height:100%;
        top:0;
        left:0;
        z-index:1;
        position:absolute;
        }

        .product-box .btn{
            position:relative;
            z-index:2;
        }
</style>
    <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            <!-- Left Sidebar End -->
            <header class="ishorizontal-topbar">
                <div class="navbar-header ">
                    <div class="d-flex w-100 justify-content-between">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{url('/website')}}" class="logo logo-dark">
                                <span class="logo">
                                    <img src="{{ URL::asset('web/') }}/images/Mednero.svg" alt="" height="55">
                                </span>
                                <!-- <span class="logo-lg">
                                    <img src="{{ URL::asset('web/') }}/images/logo-dark.png" alt="" height="28">
                                </span> -->
                            </a>

                            <!-- <a href="index.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ URL::asset('web/') }}/images/logo-light-sm.png" alt="" height="26">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ URL::asset('web/') }}/images/logo-light.png" alt="" height="30">
                                </span>
                            </a> -->
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <i class="bx bx-menu align-middle"></i>
                        </button>

                        <!-- start page title -->
                        <!-- <div class="page-title-box align-self-center d-none d-md-block">
                            <h4 class="page-title mb-0">Horizontal</h4>
                        </div> -->
                        <!-- end page title -->

                    </div>

                    <div class="d-flex">
                        @if(Auth::check() && Auth::User()->role == USER_ROLE)
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-bell icon-sm align-middle"></i>
                                <span class="noti-dot bg-danger rounded-pill" id="unread-count">0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="m-0 font-size-15"> Notifications </h5>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 250px; overflow:scroll;" id="firebase-data">
                                            
                                    
                                </div>
                                <div class="p-2 border-top d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="{{route('notifications')}}">
                                        <i class="uil-arrow-circle-right me-1"></i> <span>View More..</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{Auth::User()->user_img_url}}"
                                alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{Auth::User()->name}}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end pt-0">
                                <div class="p-3 border-bottom">
                                    <h6 class="mb-0">{{Auth::User()->name}}</h6>
                                    <p class="mb-0 font-size-11 text-muted">{{Auth::User()->email}}</p>
                                </div>
                                <a class="dropdown-item" href="{{url('website/patient-profile')}}"><i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Profile</span></a>
                                <a class="dropdown-item" href="{{url('website/patient-appointment')}}"><i class="mdi mdi-calendar-month text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">My Appointments</span></a>
                                <a class="dropdown-item" href="{{url('website/patient-members')}}"><i class="mdi mdi-account-group text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">My Patients</span></a>
                                <!-- <a class="dropdown-item d-flex align-items-center" href="#"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Settings</span><span class="badge bg-success-subtle text-success  ms-auto">New</span></a> -->
                                <!-- <a class="dropdown-item" href="auth-lock-screen.php"><i class="mdi mdi-lock text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Lock screen</span></a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{url('website/logout')}}"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
                            </div>
                        </div>
                        @else
                        <a href="{{route('patient.login')}}" style="white-space: pre;" class="btn btn-primary btn-sm mb-1 me-2 d-none d-lg-flex"> <i class="fas fa-stethoscope me-md-2"></i> <span class="">For Patients to Book Doctor’s Appointment</span> </a>
                        <a href="#!" style="white-space: pre;" class="btn btn-primary btn-sm mb-1 d-none d-lg-flex" data-bs-toggle="modal" data-bs-target="#panellists"><i class="fas fa-hospital-alt me-md-2"></i> <span class="">For Doctors / Clinic / Hospital to enroll</span> </a>
                        @endif
                    </div>
                </div>

                <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('/website')}}" id="topnav-dashboard" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-home-alt icon nav-icon"></i>
                                            <span data-key="t-dashboards">Home</span> 
                                        </a>
                                    </li>
    
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('find_a_doctor')}}" id="find-doctors-link" role="button">
                                            <i class="fi fi-rr-user-md icon nav-icon custom-icon" style="font-size: 1rem;position: relative;top: 2px;"></i>
                                            <span data-key="t-elements">Find Doctors</span>
                                        </a>
                                    </li>
    
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('/website/about-us')}}" id="topnav-pages" role="button">
                                            <i class="bx bx-store icon nav-icon"></i>
                                            <span data-key="t-apps">About Us</span>
                                        </a>
                                    </li>
    
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('/website/contact-us')}}" id="topnav-pages" role="button">
                                            <i class="mdi mdi-lifebuoy  icon nav-icon"></i>
                                            <span data-key="t-apps">Contact Us</span>
                                        </a>
                                    </li>
    
                                </ul>
                            </div>
                            @if(!Auth::check() || Auth::User()->role != USER_ROLE)
                            <div class="w-100 d-block d-lg-none">
                                <a style="font-size: 13px; height: auto;" href="{{route('patient.login')}}" class="btn btn-primary btn-sm mb-1 gap-2"> <i class="fas fa-stethoscope me-md-2"></i> For Patients to Book Doctor’s Appointment </a>
                                <a style="font-size: 13px; height: auto;" href="#!" class="btn btn-primary btn-sm mb-1 gap-2" data-bs-toggle="modal" data-bs-target="#panellists"><i class="fas fa-hospital-alt me-md-2"></i> For Doctors / Clinic / Hospital to enroll </a>
                            </div>
                            @endif
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#locationModal" class="current-loc">
                                <img class="icon" src="{{ URL::asset('web/') }}/images/current-location.svg" alt="">
                                <b class="ms-2 user-current-location" id="">Getting Your Location</b>
                            </a>
                        </nav>
                    </div>
                </div>
            </header>

            <!-- ============================================================== -->
            <!-- Start Bottom Bar -->
            <!-- ============================================================== -->

            <div class="bottom-bar-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{url('/website')}}" id="topnav-dashboard" role="button">
                            <span class="custom-icon home-icon"></span>
                            <span data-key="t-dashboards">Home</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('patient.appointments')}}" id="topnav-uielement" role="button">
                            <span class="custom-icon appointments-icon"></span>
                            <span data-key="t-elements">Appointments</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('find_a_doctor')}}" id="topnav-pages" role="button">
                            <span class="custom-icon search-icon center-circle"></span>
                            <span data-key="t-apps">Search</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('patient.login')}}" id="topnav-pages" role="button">
                            <span class="custom-icon notifications-icon"></span>
                            <span data-key="t-apps">Notifications</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('patient.profile')}}" id="topnav-uielement" role="button">
                            <span class="custom-icon account-icon"></span>
                            <span data-key="t-elements">My Account</span>
                        </a>
                    </li>

                </ul>
            </div>
