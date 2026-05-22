<!-- resources/views/layout.blade.php -->
<!doctype html>
<html lang="en">

<head>
    <!-- Meta Tags and CSS Links -->
    <meta charset="utf-8" />
    <title>Home | Mednero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- Favicon Links -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('web/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('web/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('web/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('web/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('web/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('web/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('web/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('web/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('web/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ URL::asset('web/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('web/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('web/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('web/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('web/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ URL::asset('web/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts and CSS Links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link href="{{ URL::asset('web/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('web/libs/swiper/swiper-bundle.min.css') }}">
    <link href="{{ URL::asset('web/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css" integrity="sha512-rd0qOHVMOcez6pLWPVFIv7EfSdGKLt+eafXh4RO/12Fgr41hDQxfGvoi1Vy55QIVcQEujUE1LQrATCLl2Fs+ag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('web/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('web/css/all/all.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('web/css/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('web/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('web/css/custom-web.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        .pac-container{
            z-index: 99999999!important;
        }
        
    </style>
     @if(Auth::check() && Auth::User()->role == USER_ROLE)
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
        const user_firebase_user_key = '{{Auth::User()->firebase_user_key}}';
        
        // Reference to your database path
        const dbRef = query(ref(database, `Nottifications/${user_firebase_user_key}/`), limitToLast(5));
        const unreadCountElement = document.getElementById('unread-count');
        // Track the last known entry
        let lastKnownEntryTimestamp = null;
        let initialLoadCompleted = false;

        // Function to show Bootstrap toast notification
        function showToast(message,title='Notification',order_id=0) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('a');
            toast.href = `{{url('website/patient-appointment_detail')}}/${order_id}`;
            toast.classList.add('toast');
            toast.classList.add('show');
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header" style="background-color:#89124b;color:#fff;">
                    <strong class="mr-auto" >${title}</strong>
                    
                    
                </div>
                <div class="toast-body"  style="background-color:#89124b;color:#fff;">
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
                notificationItem.href = `{{url('website/patient-appointment_detail')}}/${item.order_id}`;
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
    @endif
    @yield("s-header")
</head>

<body data-layout="horizontal">

    @include('web.template.header')

    <div class="main-content" style="min-height: fit-content;">
        @yield('content')
        @include('web.template.footer')
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Modals -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasActivity" aria-labelledby="offcanvasActivityLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasActivityLabel">Offcanvas right</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            ...
        </div>
    </div>

    <div class="modal fade" id="panellists" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">For Doctors / Clinic / Hospital to enroll</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <a href="{{url('/doctorlogin')}}" target="_blank" class="btn btn-primary mb-3">For Doctors</a>
                    <a href="{{url('/clinic/login')}}" target="_blank" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center mb-3">For Clinic Manager</a>
                    <a href="{{url('/hospital/login')}}" target="_blank" class="btn btn-dark w-100 d-flex align-items-center justify-content-center mb-1">For Hospital Manager</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userinteractionmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">User Instructions</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 h-100 mb-4">
                            <a href="{{url('website/patient-instructions')}}" target="_blank" class="card text-center mb-0">
                                <div class="card-body h-100">
                                    <i class="fi fi-tr-user-injured fw-bold font-size-36 text-primary"></i>
                                    <p class="mb-0 fw-bold font-size-14">User Instructions for Patients</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 h-100 mb-4">
                            <a href="{{url('website/doctor-instructions')}}" target="_blank" class="card text-center mb-0">
                                <div class="card-body h-100">
                                    <i class="fi fi-tr-user-md fw-bold font-size-36 text-primary"></i>
                                    <p class="mb-0 fw-bold font-size-14">User Instructions for Doctors</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 h-100 mb-4 mb-md-0">
                            <a href="{{url('website/clinic-instructions')}}" target="_blank" class="card text-center mb-0">
                                <div class="card-body h-100">
                                    <i class="fi fi-tr-hospital-user fw-bold font-size-36 text-primary"></i>
                                    <p class="mb-0 fw-bold font-size-14">User Instructions for Clinic</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 h-100 mb-4 mb-md-0">
                            <a href="{{url('website/hospital-instructions')}}" target="_blank" class="card text-center mb-0">
                                <div class="card-body h-100">
                                    <i class="fi fi-tr-user-nurse fw-bold font-size-36 text-primary"></i>
                                    <p class="mb-0 fw-bold font-size-14">User Instructions for Hospital.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11;"></div>
    @include('web.template.vendor-scripts')
    <!-- JavaScript Bundle with Popper -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <!-- <script src="{{ URL::asset('web/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-wfPh/QqOSyp6fr7l1w3T3ntZnFjO8x1mfXThDNYUSvRxkNVz8wp3RDEHT3e3jN1YV4oQaW9gIhUOnG0EXCcdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{URL::asset('web/js/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    
    <script src="{{ URL::asset('web/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('web/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <!-- <script src="{{asset('')}}web/libs/js/dataTables.min.js"></script> -->
    <!-- <script src="{{asset('')}}web/libs/js/dataTables.bootstrap5.min.js"></script> -->
    <script src="{{URL::asset('web/js/flatpickr.min.js')}}"></script>
    <!-- <script src="{{URL::asset('web/js/app.js')}}"></script> -->
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}&libraries=places"></script>
    <script>
    $(".numberonly").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //var er=$(this).attr('id')+"errmsg";
        var myElem = document.getElementById(er);
        //if error span id is not same as id of current textbox +errmsg displays this
    if (myElem === null){
      //console.log("create a span with id "+er+" to display message to user");
    }
        
        //$("#"+er).html("Digits Only").show().fadeOut("slow");
        return false;
    }
   });

    var currentLatitude = 25.204819;
    var currentLongitude = 55.270931;
    var locationText, locationTextComp;
    
    $(document).ready(function() {
        $('#set-current-loc').click(function(){
            setUserCurrentLocation();

        });
        const link = $('#find-doctors-link');
        const userLocationElement = $('.user-current-location');
        
        let currentLatitude = localStorage.getItem('current_latitude');
        let currentLongitude = localStorage.getItem('current_longitude');
        let locationText = localStorage.getItem('location_text') || 'Your location';
        let locationTextComp = localStorage.getItem('location_text_comp') || '';

        if (currentLatitude && currentLongitude) {
            currentLatitude = parseFloat(currentLatitude);
            currentLongitude = parseFloat(currentLongitude);
            currentlocation = { "lat": currentLatitude, "lng": currentLongitude };
            userLocationElement.text(trimString(locationText));
            
            $('.user-current-location-input').val(locationTextComp);
            setFormInputs(currentLatitude, currentLongitude, locationTextComp);
            // const url = `/website/doctors-list?current_latitude=${currentLatitude}&current_longitude=${currentLongitude}`;
            // link.attr('href', url);
            
            userLocationElement.text(trimString(locationText));
            $('.user-current-location-input').val(locationTextComp);
            initMap();
        } else {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    currentLatitude = parseFloat(latitude);
                    currentLongitude = parseFloat(longitude);
                    currentlocation = { "lat": currentLatitude, "lng": currentLongitude };
                    getLocationText(latitude, longitude).then((locationTextData) => {
                        setLocationAndInitMap(latitude, longitude, locationTextData);
                    }).catch((error) => {
                        console.error("Error fetching location text: ", error);
                        //alert("Could not get your location text. Please try again.");
                    });
                }, (error) => {
                    console.error("Error fetching location: ", error);
                    //alert("Could not get your location. Please enable location services and try again.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        
        

        

        $('#set-location-button').on('click', function() {
            const latitude = $('#current-latitude').val();
            const longitude = $('#current-longitude').val();
            const location = $('#txt_location').val();

            const locationTextData = {
                short_address: location,
                full_address: location
            };

            setLocationAndInitMap(latitude, longitude, locationTextData);

            $('#locationModal').modal('hide');
        });

        
        

        
    });

    </script>


    <script>

    $(document).ready(function() {

    })
        $(document).ready(function(){
            $('.select2-single').select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            });
            
            // const input = document.querySelector("#phone");
            // window.intlTelInput(input, {
           
            //     strictMode: true,
            //     utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
            // });
            
            // const input1 = document.querySelector("#phone1");
            // window.intlTelInput(input1, {
           
            //     strictMode: true,
            //     utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
            // });

            $(".flatpicker-input-home").flatpickr({
                dateFormat: "d-m-Y",
                minDate: "today",
            });

            $(".flatpicker-input").flatpickr({
                dateFormat: "d-m-Y",
                minDate: "today",
                defaultDate: new Date(),
            });

            $(".flatpicker-input-past").flatpickr({
                dateFormat: "d-m-Y",
                maxDate: "today",
                defaultDate: new Date(),
            });

            $(".flatpicker-input-date-time").flatpickr({
                minDate: "today",
                defaultDate: new Date(),
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
            $(".datepicker-inline").flatpickr({
                dateFormat: "d-m-Y",
                inline: true,
                minDate: "today",
                defaultDate: new Date(),
            });
        })
    </script>
    @yield('custom_js')

    <script>
        function  setUserCurrentLocation (){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    getLocationText(latitude, longitude).then((locationTextData) => {
                        setLocationAndInitMap(latitude, longitude, locationTextData);
                        $('#locationModal').modal('hide');
                    }).catch((error) => {
                        console.error("Error fetching location text: ", error);
                        //alert("Could not get your location text. Please try again.");
                    });
                }, (error) => {
                    console.error("Error fetching location: ", error);
                    //alert("Could not get your location. Please enable location services and try again.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        function setLocationInSession(lat,log){
             $.ajax(
            {
                type:"POST",
                url: "{{route('set_session_location')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    current_latitude: lat,
                    current_longitude:log
                },
                dataType: "html",
                success: function (data) {
                    
                }
             
            }
        );

        }
        function setLocationAndInitMap(latitude, longitude, locationTextData) {
            
            const link = $('#find-doctors-link');
            const userLocationElement = $('.user-current-location');
            currentLatitude = latitude;
            currentLongitude = longitude;
            locationText = locationTextData.short_address;
            locationTextComp = locationTextData.full_address;

            localStorage.setItem('current_latitude', latitude);
            localStorage.setItem('current_longitude', longitude);
            localStorage.setItem('location_text', locationTextData.short_address);
            localStorage.setItem('location_text_comp', locationTextData.full_address);
            setLocationInSession(latitude,longitude);

            const currentUrl = window.location.href;

            // Check if the current URL contains the specified text
            if (currentUrl.includes("find-doctors")) {
                doctorsList();
            }
            // const url = `/website/doctors-list?current_latitude=${latitude}&current_longitude=${longitude}`;
            // link.attr('href', url);
            userLocationElement.text(trimString(locationText));
            $('.user-current-location-input').val(locationTextData.full_address);
            setFormInputs(latitude, longitude, locationTextData.full_address);
            initMap();
            
            
        }
        function setFormInputs(latitude, longitude, address) {
            $('#locationModal #current-latitude').val(latitude);
            $('#locationModal #current-longitude').val(longitude);
            $('#locationModal #current-location').val(address);
            $('#locationModal #txt_location').val(address);
            $("#search-form #latitude").val(currentLatitude);
            $("#search-form #longitude").val(currentLongitude);
        }
        function initMap() {
            map2 = new google.maps.Map(document.getElementById('map_canvas'), {
                center: {
                    lat: currentlocation.lat,
                    lng: currentlocation.lng
                },
                zoom: 14,
                gestureHandling: 'greedy',
                mapTypeControl: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.LEFT_BOTTOM
                },
            });

            geocoder = new google.maps.Geocoder();

            usermarker = new google.maps.Marker({
                position: {
                    lat: currentlocation.lat,
                    lng: currentlocation.lng
                },
                map: map2,
                draggable: true,
                animation: google.maps.Animation.BOUNCE
            });

            // Map click
            google.maps.event.addListener(map2, 'click', function(event) {
                updatePosition(event.latLng, "movemarker");
            });

            // Drag end event
            usermarker.addListener('dragend', function(event) {
                updatePosition(event.latLng);
            });
            

            initAutocomplete(); // Initialize autocomplete after map initialization
        }

        function updatePosition(position, movemarker) {
            geocodePosition(position);
            usermarker.setPosition(position);
            map2.panTo(position);
            map2.setZoom(15);
            $('#current-latitude').val(position.lat());
            $('#current-longitude').val(position.lng());
            let createLatLong = position.lat() + "," + position.lng();
            console.log("Address Lat/long=" + createLatLong);
            $("#current-location").val(createLatLong);
        }

        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    usermarker.formatted_address = responses[0].formatted_address;
                } else {
                    usermarker.formatted_address = 'Cannot determine address at this location.';
                }
                $('#locationModal #txt_location').val(usermarker.formatted_address);
            });
        }

        function initAutocomplete() {
            var input2 = $('#locationModal #txt_location')[0];
            var autocomplete = new google.maps.places.Autocomplete(input2);
            autocomplete.bindTo('bounds', map2);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.log("Autocomplete's returned place contains no geometry");
                    return;
                }

                if (place.geometry.viewport) {
                    map2.fitBounds(place.geometry.viewport);
                } else {
                    map2.setCenter(place.geometry.location);
                    map2.setZoom(17); // Why 17? Because it looks good.
                }

                usermarker.setPosition(place.geometry.location);
                updatePosition(place.geometry.location);
            });
        }
        function getLocationText(latitude, longitude) {
            const apiKey = '{{ env('GOOGLE_MAP_KEY') }}';
            const location_url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;

            return fetch(location_url)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'OK' && data.results.length > 0) {
                        let city = '';
                        let country = '';

                        data.results[0].address_components.forEach(component => {
                            if (component.types.includes('locality')) {
                                city = component.long_name;
                            }
                            if (component.types.includes('country')) {
                                country = component.long_name;
                            }
                        });
                        return {
                            short_address: (city && country ? `${city}, ${country}` : 'Location not found'),
                            full_address: data.results[0].formatted_address
                        };
                    } else {
                        throw new Error('No results found');
                    }
                });
        }
        function trimString(str) {
            if (str.length > 50) {
                return str.substring(0, 50) + '...';
            }
            return str;
        }
    </script>
</body>
</html>
