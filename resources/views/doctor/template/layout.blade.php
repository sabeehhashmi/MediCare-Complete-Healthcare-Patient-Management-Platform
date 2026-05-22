@extends('template.backend-Dashboard')

@section('header')


<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Doctor Panel | Mednero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}doctor/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}doctor/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}doctor/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}doctor/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}doctor//images/favicon/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('') }}doctor/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('') }}doctor/assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="{{ asset('') }}doctor/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('') }}doctor/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('') }}doctor/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}doctor/assets/css/all/all.css" rel="stylesheet" type="text/css" />

        <!-- Datatable Css -->
        <link href="{{ asset('') }}doctor/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        <!-- Flatpicker Css -->
        <link href="{{ asset('') }}doctor/assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
        <link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
        <!-- App Css-->
        <link href="{{ asset('') }}doctor/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}doctor/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}doctor/assets/css/custom-1.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

    <body data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">

            
    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{url('doctor/dashboard')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                        </span>
                    </a>

                    <a href="{{url('doctor/dashboard')}}" class="logo logo-light">
                        <span class="logo-lg">
                            <img src="{{ asset('') }}doctor/assets/images/Mednero.svg" alt="" height="30">
                        </span>
                        <span class="logo-sm">
                            <img src="{{ asset('') }}doctor/assets/images/Mednero.svg" alt="" height="26">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                    <i class="bx bx-menu align-middle"></i>
                </button>

                <!-- start page title -->
                <!-- <div class="page-title-box align-self-center d-none d-md-block">
                    <h4 class="page-title mb-0">Dashboard</h4>
                </div> -->
                <!-- end page title -->

            </div>

            <div class="d-flex">


                <!-- <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search icon-sm align-middle"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                        <form class="p-2">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control rounded bg-light border-0" placeholder="Search...">
                                    <i class="bx bx-search search-icon"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> -->

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown-v"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell icon-sm align-middle"></i>
                        <span class="noti-dot bg-danger rounded-pill">4</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown-v">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="m-0 font-size-15"> Notifications </h5>
                                </div>
                                
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 250px; overflow: auto;">
                        <a href="appointment-details-pending.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">A pending appointment request has been received from John Doe for 05 May 2024 at 09:30AM at AL NASEEM MEDICAL CENTER LLC.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>

                                    <a href="appointment-details-confirmed.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been confirmed.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                                    <a href="appointment-details-rescheduled.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC has been rescheduled to 06 May 2024 at 09:30AM.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>

                                    <a href="appointment-details-cancelled.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been cancelled due to doctor unavailability.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                                    
                                    
                                    <a href="appointment-details-completed.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been successfully completed.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                        </div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center h-auto" href="notifications.php">
                                <i class="uil-arrow-circle-right me-1"></i> <span>View More..</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('') }}doctor/assets/images/doctor.png"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">DR S.K SHETTY M.S</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">DR S.K SHETTY M.S</h6>
                                <p class="mb-0 font-size-11 text-muted">martin.gurley@email.com</p>
                            </div>
                            <a class="dropdown-item" href="profile.php"><i class="bx bx-user-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="change-password.php"><i class="mdi mdi-key-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Reset Password</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
                        </div>
                    </div>
            </div>
        </div>
    </header>

@stop


@section('sidebar_ul')
<div class="vertical-menu">

            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{url('doctor/dashboard')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('') }}doctor/assets/images/Mednero.svg" alt="" height="28">
                    </span>
                </a>

                <a href="{{url('doctor/dashboard')}}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="bx bx-menu align-middle"></i>
            </button>

           
        </div>
@stop


@section('right_bar_dropdown')
<header class="ishorizontal-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{url('doctor/dashboard')}}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('') }}doctor/assets/images/Mednero.svg" alt="" height="28">
                            </span>
                        </a>

                        <a href="{{url('doctor/dashboard')}}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('') }}doctor/assets/images/logo-sm.svg" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('') }}doctor/assets/images/Mednero.svg" alt="" height="30">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" style="height:75px !important;" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="bx bx-menu align-middle"></i>
                    </button>

                    <!-- start page title -->
                    <div class="page-title-box align-self-center d-none d-md-block">
                        <h4 class="page-title mb-0">Dashboard</h4>
                    </div>
                    <!-- end page title -->

                </div>

                <div class="d-flex">

                  

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-bell icon-sm align-middle"></i>
                            <span class="noti-dot bg-danger rounded-pill">4</span>
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
                            <div data-simplebar style="max-height: 250px; overflow: auto;">
                            <a href="appointment-details-pending.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">A pending appointment request has been received from John Doe for 05 May 2024 at 09:30AM at AL NASEEM MEDICAL CENTER LLC.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>

                                    <a href="appointment-details-confirmed.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been confirmed.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                                    <a href="appointment-details-rescheduled.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC has been rescheduled to 06 May 2024 at 09:30AM.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>

                                    <a href="appointment-details-cancelled.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been cancelled due to doctor unavailability.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                                    
                                    
                                    <a href="appointment-details-completed.php" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('') }}doctor/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                                <h6 class="mb-1">John Doe</h6>
                                                <div>
                                                    <p class="mb-0">Your appointment with John Doe at AL NASEEM MEDICAL CENTER LLC on 05 May 2024 at 09:30AM has been successfully completed.</p>
                                                </div>
                                            </div>
                                
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 btn-block text-center h-auto" href="notifications.php">
                                    <i class="uil-arrow-circle-right me-1"></i> <span>View More..</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('') }}doctor/assets/images/doctor.png"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">DR S.K SHETTY M.S</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">DR S.K SHETTY M.S</h6>
                                <p class="mb-0 font-size-11 text-muted">martin.gurley@email.com</p>
                            </div>
                            <a class="dropdown-item" href="profile.php"><i class="bx bx-user-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="change-password.php"><i class="mdi mdi-key-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Reset Password</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">
                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-home-alt icon nav-icon"></i>
                                        <span data-key="t-dashboards">Dashboards</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a href="{{url('doctor/dashboard')}}"  class="dropdown-item" data-key="t-ecommerce">Ecommerce</a>
                                        <a href="dashboard-sales.html"  class="dropdown-item" data-key="t-sales">Sales</a>
                                    </div>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{url('doctor/dashboard')}}">
                                        <i class="bx bx-home-alt icon nav-icon"></i>
                                        <span data-key="t-dashboards">Dashboard</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="appointments.php">
                                        <i class="bx bx-calendar-event icon nav-icon"></i>
                                        <span data-key="t-dashboards">Total Appointments</span>
                                    </a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx bx-time-five nav-icon"></i>
                                        <span data-key="t-dashboards">Schedule Appointment Slots</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a href="availability.php"  class="dropdown-item" data-key="t-ecommerce">Availability</a>
                                        <a href="holiday.php"  class="dropdown-item" data-key="t-ecommerce">Holiday</a>
                                        <a href="temporary-unavailable.php"  class="dropdown-item" data-key="t-sales">Temporary Unavailable</a>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="instant-appointment.php">
                                        <i class="bx bx-calendar-check icon nav-icon"></i>
                                        <span data-key="t-dashboards">Schedule Instant Appointment Date</span>
                                    </a>
                                </li>
                                
                                
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="notifications.php">
                                        <i class="bx bx-bell icon nav-icon"></i>
                                        <span data-key="t-dashboards">Notification</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="reports.php">
                                        <i class="bx bx-file icon nav-icon"></i>
                                        <span data-key="t-dashboards">Reports</span>
                                    </a>
                                </li>
                                
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="availability.php">
                                        <i class="bx bx-time-five icon nav-icon"></i>
                                        <span data-key="t-dashboards">Availability</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="holiday.php">
                                        <i class="bx bx-calendar-x icon nav-icon"></i>
                                        <span data-key="t-dashboards">Holiday</span>
                                    </a>
                                </li> -->


                                
                                
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="temporary-unavailable.php">
                                        <i class="bx bx-user-circle icon nav-icon"></i>
                                        <span data-key="t-dashboards">Temporary Unavailable</span>
                                    </a>
                                </li> -->

                                
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">
                                        <i class="bx bx-user-circle icon nav-icon"></i>
                                        <span data-key="t-dashboards">Profile</span>
                                    </a>
                                </li>

                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="setting.php">
                                        <i class="bx bx-cog icon nav-icon"></i>
                                        <span data-key="t-dashboards">Setting</span>
                                    </a>
                                </li> -->

                                

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <div class="main-content">
                <div class="page-content">
                <div class="container-fluid">
@stop


        @section('content')
            @yield('content')
        @stop
 


@section('footer')
<footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <script>document.write(new Date().getFullYear())</script> © Mednero.
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
                            <h5 class="modal-title" id="modal-title">Booking Id: #MYDW1025</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form novalidate>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="font-size-16">Patient Details</h4>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <img style="width: 80px; height: 80px; border-radius: 5px;" src="{{ asset('') }}doctor/assets/images/users/avatar-2.jpg" alt="Generic placeholder image" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4>John Doe</h4>
                                                <h5 class="text-muted"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 20px; justify-content: space-between;">
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bx-envelope me-2"></i> test@example.com</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxs-phone-call me-2"></i> +971-50-1234567</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxl-whatsapp-square me-2"></i> +971-50-1234567</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bx-calendar me-2"></i> 10-05-1999</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="fas fa-transgender me-2"></i> Male</h5>
                                        </div>
                                        <div class="card p-0 overflow-hidden mt-3 shadow-none">
                                            <div class="mail-list">
                                                <a href="#" class="border-bottom">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0">05 May 2024</h5>
                                                            <span class="text-muted font-size-13">09:30 AM</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-map font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0">NMC Royal Hospital</h5>
                                                            <span class="text-muted font-size-13">Falcon House - Plot no # 598/122, DIPark 1, Dubai</span>
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
                            <a href="appointment-details-pending.php" class="btn btn-primary">View Details</a>
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
                    <form action="" class="custom-form">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Reason of Reschedule</label>
                                <div class="position-relative">
                                    <textarea class="form-control" id="" name="" rows="2"></textarea>
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
                                    

                                    <span>
                                        <input type="radio" id="1" name="slot" class="time-slot" />
                                        <label for="1">08:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat10" class="time-slot" disabled/>
                                        <label for="sat10">08:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="2" name="slot" class="time-slot" />
                                        <label for="2">08:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat12" class="time-slot" />
                                        <label for="sat12">08:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="3" name="slot" class="time-slot" />
                                        <label for="3">09:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat14" class="time-slot" />
                                        <label for="sat14">09:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="4" name="slot" class="time-slot" disabled/>
                                        <label for="4">09:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat16" class="time-slot" disabled/>
                                        <label for="sat16">09:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat17" name="slot" class="time-slot" />
                                        <label for="sat17">10:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat18" class="time-slot" />
                                        <label for="sat18">10:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat19" name="slot" class="time-slot" />
                                        <label for="sat19">10:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat20" class="time-slot" disabled/>
                                        <label for="sat20">10:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat21" name="slot" class="time-slot" />
                                        <label for="sat21">11:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat22" class="time-slot" />
                                        <label for="sat22">11:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat23" name="slot" class="time-slot" />
                                        <label for="sat23">11:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat24" class="time-slot" />
                                        <label for="sat24">11:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat21-1" name="slot" class="time-slot" />
                                        <label for="sat21-1">12:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat22-1" class="time-slot" />
                                        <label for="sat22-1">12:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat23-1" name="slot" class="time-slot" />
                                        <label for="sat23-1">12:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat24-1" class="time-slot" />
                                        <label for="sat24-1">12:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat25"  name="slot" class="time-slot" />
                                        <label for="sat25">13:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat26" class="time-slot" />
                                        <label for="sat26">13:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat27" name="slot" class="time-slot" />
                                        <label for="sat27">13:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat28" class="time-slot" />
                                        <label for="sat28">13:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat29" name="slot" class="time-slot" />
                                        <label for="sat29">14:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat30" class="time-slot" />
                                        <label for="sat30">14:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat31" name="slot" class="time-slot" />
                                        <label for="sat31">14:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat32" class="time-slot" />
                                        <label for="sat32">14:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat33" name="slot" class="time-slot" />
                                        <label for="sat33">15:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat34" class="time-slot" />
                                        <label for="sat34">15:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat35" name="slot" class="time-slot" />
                                        <label for="sat35">15:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat36" class="time-slot" />
                                        <label for="sat36">15:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat37" name="slot" class="time-slot" />
                                        <label for="sat37">16:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat38" class="time-slot" />
                                        <label for="sat38">16:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat39" name="slot" class="time-slot" />
                                        <label for="sat39">16:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat40" class="time-slot" />
                                        <label for="sat40">16:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat41" name="slot" class="time-slot" />
                                        <label for="sat41">17:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat42" class="time-slot" />
                                        <label for="sat42">17:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat43" name="slot" class="time-slot" />
                                        <label for="sat43">17:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat44" class="time-slot" />
                                        <label for="sat44">17:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat45" name="slot" class="time-slot" />
                                        <label for="sat45">18:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat46" class="time-slot" />
                                        <label for="sat46">18:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat47" name="slot" class="time-slot" />
                                        <label for="sat47">18:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat48" class="time-slot" />
                                        <label for="sat48">18:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat49" name="slot" class="time-slot" />
                                        <label for="sat49">19:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat50" class="time-slot" />
                                        <label for="sat50">19:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat51" name="slot" class="time-slot" />
                                        <label for="sat51">19:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat52" class="time-slot" />
                                        <label for="sat52">19:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat53" name="slot" class="time-slot" />
                                        <label for="sat53">20:00</label>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Confirm Reschedule</button>
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
                    <form action="" class="custom-form">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Patient </label>
                                <div class="position-relative">
                                    <select name="" id="PatientSelct" class="select2-single" data-placeholder="Select Patient">
                                        <option></option>
                                        <option value="1">Patient name 1</option>
                                        <option value="2">Patient name 2</option>
                                        <option value="3">Patient name 3</option>
                                        <option value="4">Patient name 4</option>
                                        <option value="5">Patient name 5</option>
                                        <option value="6">Patient name 6</option>
                                        <option value="7">Patient name 7</option>
                                    </select>
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
                                    <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />
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
                                    

                                    <span>
                                        <input type="radio" id="sat91" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat91">08:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat101" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat101">08:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat111" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat111">08:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat121" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat121">08:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat131" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat131">09:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat141" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat141">09:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat151" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat151">09:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat161" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat161">09:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat171" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat171">10:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat181" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat181">10:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat191" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat191">10:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat201" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat201">10:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat211" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat211">11:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat221" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat221">11:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat231" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat231">11:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat241" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat241">11:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat251" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat251">12:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat261" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat261">12:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat271" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat271">12:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat281" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat281">12:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat291" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat291">13:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat311" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat311">13:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat321" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat321">13:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat331" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat331">13:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat341" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat341">14:00</label>
                                    </span>

                                    <span>
                                        <input type="radio" id="sat341" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat341">14:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat32" class="time-slot" />
                                        <label for="sat32">14:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat343" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat343">15:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat34" class="time-slot" />
                                        <label for="sat34">15:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat344" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat344">15:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat36" class="time-slot" />
                                        <label for="sat36">15:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat345" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat345">16:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat38" class="time-slot" />
                                        <label for="sat38">16:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat346" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat346">16:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat40" class="time-slot" />
                                        <label for="sat40">16:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat347" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat347">17:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat42" class="time-slot" />
                                        <label for="sat42">17:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat348" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat348">17:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat44" class="time-slot" />
                                        <label for="sat44">17:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat349" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat349">18:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat46" class="time-slot" />
                                        <label for="sat46">18:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat350" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat350">18:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat48" class="time-slot" />
                                        <label for="sat48">18:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat351" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat351">19:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat50" class="time-slot" />
                                        <label for="sat50">19:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat352" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat352">19:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat52" class="time-slot" />
                                        <label for="sat52">19:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat353" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat353">20:00</label>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Confirm</button>
                </div>
                </div>
            </div>
        </div>

        

         <!-- Modal -->
        <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="{{ asset('') }}doctor/assets/images/cancel-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to cancel the appointment.</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="">Reason of Cancellation</label>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="" name="" rows="2"></textarea>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-dark" style="width: 120px;">Cancel</button>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="modal fade" id="followup-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Follow Up</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Select Date & Time</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control flatpicker-input-date-time" id="" placeholder="Select Date & Time" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="position-relative">
                                        <label class="form-label" for="">Follow Up Remark</label>
                                        <textarea class="form-control" id="" name="" rows="3" placeholder="Enter Follow Up Remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="confirm-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="{{ asset('') }}doctor/assets/images/success-img.svg" class="img-fluid" alt="">
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
                        <button type="button" class="btn btn-primary">Confirm Appointment</button>
                    </div>
                </div>
                
            </div>
        </div>



        <div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Complete Appointment- #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="{{ asset('') }}doctor/assets/images/success-img.svg" class="img-fluid" alt="">
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
                        <button type="button" class="btn btn-primary">Completed</button>
                    </div>
                </div>
                
            </div>
        </div>





            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


    <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('') }}doctor/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="assets/libs/metismenujs/metismenujs.min.js"></script> -->
    <!-- <script src="assets/libs/simplebar/simplebar.min.js"></script> -->
    <!-- <script src="assets/libs/eva-icons/eva.min.js"></script> -->

        
    <!-- <script src="assets/js/pages/dashboard.init.js"></script> -->
    <script src="{{ asset('') }}doctor/assets/js/dataTables.min.js"></script>
    <script src="{{ asset('') }}doctor/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}doctor/assets/js/flatpickr.min.js"></script>

    <!-- plugin js -->
    <script src="{{ asset('') }}doctor/assets/libs/fullcalendar/index.global.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="{{URL::asset('web/js/select2.min.js')}}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{ asset('') }}doctor/assets/js/flatpickr.min.js"></script>
    <script src="{{ asset('') }}doctor/assets/js/app.js"></script>
    
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
    </body>


</html>
@stop