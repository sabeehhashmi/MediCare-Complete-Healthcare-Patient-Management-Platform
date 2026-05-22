<?php
$CurrentUrl = url()->current();
?>
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Hospital Panel | {{config('global.site_name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}hospital/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}hospital/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}hospital/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}hospital/assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('') }}hospital/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('') }}hospital/assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="{{ asset('') }}hospital/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('') }}hospital/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('') }}hospital/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/all/all.css" rel="stylesheet" type="text/css" />

        <!-- Datatable Css -->
        <link href="{{ asset('') }}hospital/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        <!-- Flatpicker Css -->
        <link href="{{ asset('') }}hospital/assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
        <link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
        <!-- App Css-->
        <link href="{{ asset('') }}hospital/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/custom-1.css" id="app-style" rel="stylesheet" type="text/css" />
        
    <link href="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />


    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
        import { getDatabase, ref, onValue, query, limitToLast } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey:'{{config("global.apiKey")}}',
            authDomain:'{{config("global.authDomain")}}',
            databaseURL:'{{config("global.databaseURL")}}',
            projectId:'{{config("global.projectId")}}',
            storageBucket:'{{config("global.storageBucket")}}',
            messagingSenderId:'{{config("global.messagingSenderId")}}',
            appId:'{{config("global.appId")}}',
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);
        let hopital_user_id = 0;
        @if(Auth::user()->id)
            hopital_user_id='{{Auth::user()->id}}';
        @endif
        // Reference to your database path
        const dbRef = query(ref(database, 'Hospital/'+hopital_user_id), limitToLast(5));
        const unreadCountElement = document.getElementById('unread-count');
        // Track the last known entry
        let lastKnownEntryTimestamp = null;
        let initialLoadCompleted = false;

        // Function to show Bootstrap toast notification
        function showToast(message,title='Notification') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.classList.add('toast');
            toast.classList.add('show');
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header" style="background-color:#00BFFF;color:#fff;">
                    <strong class="mr-auto" >${title}</strong>
                    
                    
                </div>
                <div class="toast-body"  style="background-color:#00BFFF;color:#fff;">
                    ${message}
                </div>
            `;
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }


        // Listen for real-time updates
        onValue(dbRef, (snapshot) => {
            const data = snapshot.val();
            const container = document.getElementById('firebase-data');
            container.innerHTML = '';  // Clear existing content

            // Convert the data object to an array
            const dataArray = Object.values(data);
            // Filter the array to get only unread notifications
            let unreadArray = dataArray.filter(item => item.read == 0);

            // Get the count of unread notifications
            let unreadCount = unreadArray.length;
            unreadCountElement.innerText = unreadCount;
            if(unreadCount > 4){
                unreadCountElement.innerText = '4+';
            }
            
            // Reverse the array to display the most recent records first
            dataArray.reverse();

            dataArray.forEach((item) => {

                const timestamp = parseAndFormatDate(item.createdAt);
                if (lastKnownEntryTimestamp === null || timestamp > lastKnownEntryTimestamp) {
                    if (initialLoadCompleted) {
                        showToast(`${item.description}`,`${item.title}`,`${item.order_id}`);
                    }
                    lastKnownEntryTimestamp = timestamp; // Update last known entry timestamp
                }

                const notificationItem = document.createElement('a');
                if (item.notificationType === 'bulk_broadcast') {
                    notificationItem.href = `{{route('hospital.notifications')}}`;
                } else if (item.notificationType === 'chat_message') {
                    notificationItem.href = `{{ route('hospital.chat.index') }}`;
                } else {
                    notificationItem.href = `{{url('hospital/appointmentdetail')}}/${item.order_id}`;
                }
                notificationItem.className = "text-reset notification-item";

                notificationItem.innerHTML = `
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <img src="${item.imageURL}" onerror="this.onerror=null; this.src='{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}'"
                                class="rounded-circle avatar-sm" alt="user-pic">
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted font-size-13 mb-0 float-end">${timeAgo(item.createdAt)}</p>
                            <h6 class="mb-1">${item.title}</h6>
                            <div>
                                <p class="mb-0">${item.description}</p>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(notificationItem);
                
            });
            // Mark initial load as completed
            initialLoadCompleted = true;
        });
        function parseAndFormatDate(dateString) {
            const parts = dateString.split(' '); // Split date and time
            const datePart = parts[0];
            const timePart = parts[1];
            
            const [day, month, year] = datePart.split('-');
            const [hours, minutes, seconds] = timePart.split(':');
            
            // JavaScript months are 0-indexed, so we need to subtract 1 from month
            const jsMonth = parseInt(month, 10) - 1;

            // Create a new Date object with parsed values
            const parsedDate = new Date(year, jsMonth, day, hours, minutes, seconds);

            return parsedDate;
        }
        
        function timeAgo(timestamp) {
            const dateTimeString = timestamp;
            const [dd, MM, yyyy, hh, mm, ss] = dateTimeString.split(/[ :\-]/);
            const dateObj = new Date(Date.UTC(yyyy, MM - 1, dd, hh, mm, ss));

            const parsedDate = dateObj.getTime(); // This gives you the timestamp in milliseconds
            
            // const parsedDate = parseAndFormatDate(timestamp); // Assuming parseAndFormatDate correctly parses the timestamp
             
            // Get current GMT time in milliseconds since Unix epoch
            const lnow = Date.now();
            
            const gmtTime = new Date(lnow).toUTCString();
            
            const now = Date.parse(gmtTime);

            // Calculate the difference in seconds between now (GMT) and the parsed date
            const seconds = Math.floor((now - parsedDate) / 1000);

            let interval = Math.floor(seconds / 31536000); // Year interval
            if (interval >= 1) {
                return interval + " year" + (interval > 1 ? "s" : "") + " ago";
            }

            interval = Math.floor(seconds / 2592000); // Month interval
            if (interval >= 1) {
                return interval + " month" + (interval > 1 ? "s" : "") + " ago";
            }

            interval = Math.floor(seconds / 86400); // Day interval
            if (interval >= 1) {
                return interval + " day" + (interval > 1 ? "s" : "") + " ago";
            }

            interval = Math.floor(seconds / 3600); // Hour interval
            if (interval >= 1) {
                return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
            }

            interval = Math.floor(seconds / 60); // Minute interval
            if (interval >= 1) {
                return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
            }
            if(seconds < 1){
                return 'justnow';
            }
            return Math.floor(seconds) + " second" + (seconds > 1 ? "s" : "") + " ago";
        }
    </script>
</head>

    <body data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">

            
    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{route('hospital.dashboard')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="26">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="48">
                        </span>
                    </a>

                    <a href="{{route('hospital.dashboard')}}" class="logo logo-light">
                        <span class="logo-lg">
                            <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="48">
                        </span>
                        <span class="logo-sm">
                            <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="26">
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
                        <span class="noti-dot bg-danger rounded-pill">6</span>
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
                                    <img src="{{ asset('') }}hospital/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                    <h6 class="mb-1">John Doe</h6>
                                    <div>
                                        <p class="mb-0">DR S.K SHETTY M.S has received a pending appointment request from John Doe for 05 May 2024 at 09:30AM.</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="appointment-details-confirmed.php" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('') }}hospital/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                    <h6 class="mb-1">John Doe</h6>
                                    <div>
                                        <p class="mb-0">DR S.K SHETTY M.S appointment with John Doe on 05 May 2024 at 09:30AM has been confirmed.</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="appointment-details-rescheduled.php" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('') }}hospital/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                    <h6 class="mb-1">John Doe</h6>
                                    <div>
                                        <p class="mb-0">DR S.K SHETTY M.S appointment with John Doe has been rescheduled to 06 May 2024 at 09:30AM.</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="appointment-details-cancelled.php" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('') }}hospital/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                    <h6 class="mb-1">John Doe</h6>
                                    <div>
                                        <p class="mb-0">DR S.K SHETTY M.S appointment with John Dow on 05 May 2024 at 09:30AM has been cancelled due to Doctor Unavailability.</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="appointment-details-completed.php" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('') }}hospital/assets/images/user.png" class="rounded-circle avatar-sm" alt="user-pic" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted font-size-13 mb-0 float-end">1 hour ago</p>
                                    <h6 class="mb-1">John Doe</h6>
                                    <div>
                                        <p class="mb-0">DR S.K SHETTY M.S appointment with John Dow on 05 May 2024 at 09:30AM has been successfully completed.</p>
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
                            <img class="rounded-circle header-profile-user" src="{{ asset('') }}hospital/assets/images/clinic-logo.png"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">{{ Auth::user()->name }} Hospital</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">Aster Hospital</h6>
                                <p class="mb-0 font-size-11 text-muted">info@bostondentaluae.com</p>
                            </div>
                            <a class="dropdown-item" href="{{ url('hospital/get_profile') }}"><i class="bx bx-user-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="#"><i class="mdi mdi-key-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Reset Password</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('hospital.logout')}}"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
                        </div>
                    </div>
            </div>
        </div>
    </header>
    <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('hospital.dashboard')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="48">
                    </span>
                </a>

                <a href="{{route('hospital.dashboard')}}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="48">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="26">
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
                        <a href="{{route('hospital.dashboard')}}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="48">
                            </span>
                        </a>

                        <a href="{{route('hospital.dashboard')}}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('') }}hospital/assets/images/logo-mednero-sm.png" alt="" height="26">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="48">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" style="height:75px !important;" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="bx bx-menu align-middle"></i>
                    </button>

                    <!-- start page title -->
                    <div class="page-title-box align-self-center d-none d-md-block">
                        <h4 class="page-title mb-0">{{$module_heading ?? 'Dashboard'}}</h4>
                    </div>
                    <!-- end page title -->

                </div>

                <div class="d-flex">

                  

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
                            <div data-simplebar style="max-height: 250px; overflow: auto;" id="firebase-data">
                            
                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 btn-block text-center h-auto" href="{{route('hospital.notifications')}}">
                                    <i class="uil-arrow-circle-right me-1"></i> <span>View More..</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ Auth::user()->user_img_url ?? null }}"
                            alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15 text-primary">{{ Auth::user()->name }} Hospital</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 text-black">{{ Auth::user()->name }} Hospital</h6>
                                <p class="mb-0 font-size-11 text-muted">{{ Auth::user()->email }}</p>
                            </div>
                            <a class="dropdown-item" href="{{ url('hospital/get_profile') }}"><i class="bx bx-user-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="{{ url('hospital/change_password') }}"><i class="mdi mdi-key-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Reset Password</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('hospital.logout')}}"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
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
                                <?php $patterns = array('hospital\/dashboard');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.dashboard')}}">
                                        <i class="bx bx-home-alt icon nav-icon"></i>
                                        <span data-key="t-dashboards">Dashboard</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/departments','hospital\/adddepartment','hospital\/editdepartment');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.departments')}}">
                                        <i class="fi fi-rr-bed-alt icon nav-icon custom-icon" style="font-size: 1.0rem;"></i>
                                        <span data-key="t-dashboards">Department</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/doctors','hospital\/doctordetail','hospital\/addoctor',   'hospital\/appointments','hospital\/availability','hospital\/temporaryunavailable','hospital\/editdoctor','hospital\/holiday','hospital\/instantappointment');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.doctors')}}">
                                        <i class="fi fi-rr-user-md icon nav-icon custom-icon" style="font-size: 1.0rem;"></i>
                                        <span data-key="t-dashboards">Doctors</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/hospitalAppointments');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.hospitalAppointments')}}">
                                        <i class="bx bx-calendar-event icon nav-icon"></i>
                                        <span data-key="t-dashboards">Total Appointments</span>
                                    </a>
                                </li>
                                
                                
                                <?php $patterns = array('hospital\/reviews');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.reviews')}}">
                                        <i class="bx bx-calendar-event icon nav-icon"></i>
                                        <span data-key="t-dashboards">Doctor Rating Report</span>
                                    </a>
                                </li> 
                                
                                <?php $patterns = array('hospital\/approval_appointments');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.appointments.approval_index')}}">
                                        <i class="bx bx-calendar-event icon nav-icon"></i>
                                        <span data-key="t-dashboards"> Document Requests </span>
                                    </a>
                                </li>
                                
                                
                                
                                
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('hospital.notifications')}}">
                                        <i class="bx bx-bell icon nav-icon"></i>
                                        <span data-key="t-dashboards">Notification</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/reports');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{route('hospital.reports')}}">
                                        <i class="bx bx-file icon nav-icon"></i>
                                        <span data-key="t-dashboards">Reports</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/chat');
                                $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{ route('hospital.chat.index') }}">
                                        <i class="bx bx-chat icon nav-icon"></i>
                                        <span data-key="t-chat">Messages</span>
                                        <span class="badge bg-danger rounded-pill ms-1" id="chat-unread-count" style="display: none; font-size: 10px;">0</span>
                                    </a>
                                </li>
                                <?php $patterns = array('hospital\/edit_profile','hospital\/get_profile');
                                      $patterns_flattened = implode('|', $patterns);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}" href="{{ url('hospital/get_profile') }}">
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