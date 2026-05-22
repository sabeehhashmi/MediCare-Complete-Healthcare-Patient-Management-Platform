
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Hospital Panel | Mednero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/all/all.css" rel="stylesheet" type="text/css" />

        <!-- Datatable Css -->
        <link href="assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        <!-- Flatpicker Css -->
        <link href="assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom-1.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

    <body data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">

            
    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="dashboard.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.svg" alt="" height="26">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-sm.svg" alt="" height="26">
                        </span>
                    </a>

                    <a href="dashboard.php" class="logo logo-light">
                        <span class="logo-lg">
                            <img src="assets/images/Mednero.svg" alt="" height="30">
                        </span>
                        <span class="logo-sm">
                            <img src="assets/images/Mednero.svg" alt="" height="26">
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
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="assets/images/user-avatar.svg" class="rounded-circle avatar-sm" alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                        <h6 class="mb-1">James Lemire</h6>
                                        <div>
                                            <p class="mb-0">It will seem like simplified English.</p>
                                        </div>
                                    </div>
                        
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-18">
                                            <i class="bx bx-cart"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted font-size-13 mb-0 float-end">3 min ago</p>
                                        <h6 class="mb-1">Your order is placed</h6>
                                        <div>
                                            <p class="mb-0">If several languages coalesce the grammar</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                        <span class="avatar-title bg-success rounded-circle font-size-18">
                                            <i class="bx bx-badge-check"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted font-size-13 mb-0 float-end">8 min ago</p>
                                        <h6 class="mb-1">Your item is shipped</h6>
                                        <div>
                                            <p class="mb-0">If several languages coalesce the grammar</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="assets/images/users/avatar-6.jpg" class="rounded-circle avatar-sm" alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                        <h6 class="mb-1">Salena Layfield</h6>
                                        <div>
                                            <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
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
                            <img class="rounded-circle header-profile-user" src="assets/images/user-avatar.svg"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">Aster Hospital</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">Aster Hospital</h6>
                                <p class="mb-0 font-size-11 text-muted">info@bostondentaluae.com</p>
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
    <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="dashboard.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/Mednero.svg" alt="" height="28">
                    </span>
                </a>

                <a href="dashboard.php" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="26">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="bx bx-menu align-middle"></i>
            </button>

           
        </div>
        <!-- Left Sidebar End -->
        <header class="ishorizontal-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="dashboard.php" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm.svg" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/Mednero.svg" alt="" height="28">
                            </span>
                        </a>

                        <a href="dashboard.php" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm.svg" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/Mednero.svg" alt="" height="30">
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
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="assets/images/user-avatar.svg" class="rounded-circle avatar-sm" alt="user-pic">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">James Lemire</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">It will seem like simplified English.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>1 hour ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="bx bx-cart"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="bx bx-cart"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="bx bx-cart"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span></p>
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
                            <img class="rounded-circle header-profile-user" src="assets/images/user-avatar.svg"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">Aster Hospital</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">Aster Hospital</h6>
                                <p class="mb-0 font-size-11 text-muted">info@bostondentaluae.com</p>
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
                                        <a href="dashboard.php"  class="dropdown-item" data-key="t-ecommerce">Ecommerce</a>
                                        <a href="dashboard-sales.html"  class="dropdown-item" data-key="t-sales">Sales</a>
                                    </div>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link active" href="dashboard.php">
                                        <i class="bx bx-home-alt icon nav-icon"></i>
                                        <span data-key="t-dashboards">Dashboard</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="department.php">
                                        <i class="fi fi-rr-bed-alt icon nav-icon custom-icon" style="font-size: 1.0rem;"></i>
                                        <span data-key="t-dashboards">Department</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="doctors.php">
                                        <i class="fi fi-rr-user-md icon nav-icon custom-icon" style="font-size: 1.0rem;"></i>
                                        <span data-key="t-dashboards">Doctors</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="appointments.php">
                                        <i class="bx bx-calendar-event icon nav-icon"></i>
                                        <span data-key="t-dashboards">Total Appointments</span>
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

                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">
                                        <i class="bx bx-user-circle icon nav-icon"></i>
                                        <span data-key="t-dashboards">Profile</span>
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

                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx bx-time-five nav-icon"></i>
                                        <span data-key="t-dashboards">Availability</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a href="availability.php"  class="dropdown-item" data-key="t-ecommerce">Availability</a>
                                        <a href="holiday.php"  class="dropdown-item" data-key="t-ecommerce">Holiday</a>
                                        <a href="temporary-unavailable.php"  class="dropdown-item" data-key="t-sales">Temporary Unavailable</a>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">
                                        <i class="bx bx-user-circle icon nav-icon"></i>
                                        <span data-key="t-dashboards">Profile</span>
                                    </a>
                                </li>

                                <li class="nav-item">
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