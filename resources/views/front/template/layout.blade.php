<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Title -->
    <title>@yield('title', "MedNero - Global Telehealth Care | World's Best Doctors at Your Fingertips")</title>
    <meta name="description" content="@yield('meta_description', 'MedNero is revolutionizing global healthcare with secure telehealth consultations, medical travel concierge services, and world-class medical specialists. Your health, our priority - anywhere, anytime.')">
    <meta name="author" content="MedNero">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/gif" sizes="20x20">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jquery-ui.css') }}" rel="stylesheet">
    <!-- Bootstrap Icon CSS -->
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- CSS -->
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <!-- FancyBox CSS -->
    <link href="{{ asset('assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <!-- Swiper slider CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Slick slider CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <!-- Toastr CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- BoxIcon  CSS -->
    <link href="{{ asset('assets/css/boxicons.min.css') }}" rel="stylesheet">
    <!--  Style CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.6.0/air-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getDatabase, ref, onValue, query, limitToLast } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

// Firebase config
const firebaseConfig = {
    apiKey:'{{config("global.apiKey")}}',
    authDomain:'{{config("global.authDomain")}}',
    databaseURL:'{{config("global.databaseURL")}}',
    projectId:'{{config("global.projectId")}}',
    storageBucket:'{{config("global.storageBucket")}}',
    messagingSenderId:'{{config("global.messagingSenderId")}}',
    appId:'{{config("global.appId")}}',
};

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

// ✅ CHANGE HERE (VERY IMPORTANT)
let user_id = 0;
@if(Auth::check())
    user_id = '{{ Auth::id() }}';
@else
    user_id = 0;
@endif

// ✅ Use correct node (User instead of Doctor)
const dbRef = query(ref(database, 'Doctor/' + user_id), limitToLast(5));

const unreadCountElement = document.getElementById('unread-count');
const container = document.getElementById('firebase-data');

let bellNotifications = {}; // Central store for merging different sources
let lastKnownEntryTimestamp = null;
let lastPatientNotifTimestamp = Date.now();
let initialLoadCompleted = false;

// Toast
function showToast(message, title='Notification', url='javascript:void(0)') {
    const toastContainer = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.classList.add('toast','show');
    toast.style.cursor = 'pointer';
    toast.onclick = () => { 
        if(url !== 'javascript:void(0)') window.location.href = url; 
    };

    toast.innerHTML = `
        <div class="toast-header bg-primary text-white justify-content-between">
            <strong class="me-auto">${title}</strong>
            <small>Just now</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body bg-primary text-white">
            ${message}
            <div class="mt-1 small opacity-75">Click to open</div>
        </div>
    `;

    toastContainer.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
    bsToast.show();
}

/**
 * Centrailzed logic to determine where a notification should lead
 */
function getNotificationUrl(item) {
    const type = item.notificationType || item.type;
    const order_id = item.order_id || '0';
    const channel = item.channel_name || order_id;
    const userRole = @json(Auth::check() ? Auth::user()->role : 0);

    // 1. Video Call / Live Interaction
    if (type === 'call' || type === 'video_call') {
        return `{{ url('video-call') }}/${channel}`;
    }

    // 2. Public / Bulk Broadcasts
    if (type === 'bulk_broadcast') {
        return `{{ route('front.notifications') }}`;
    }
     if (type === 'chat_message') {
        return `{{ route('front.chat.index') }}`;
    }

    // 3. Appointment / Booking details (Role specific)
    if (order_id !== '0' && order_id !== '') {
        if (userRole == 6) { // Doctor Role
            return `{{ url('doctor/appointmentdetail') }}/${order_id}`;
        } else { // Patient Role (7)
            return `{{ url('useraccount-appointment-details') }}/${order_id}`;
        }
    }

    // 4. Default fallback
    return `{{ route('front.notifications') }}`;
}


/**
 * Renders the top 5 notifications from both sources into the bell dropdown
 */
function refreshHeaderDropdown() {
    if (!container) return;

    const dataArray = Object.values(bellNotifications);
    
    // 1. Calculate unread count across all sources
    let unreadCount = dataArray.filter(item => item.read == 0 || item.read == "0").length;
    unreadCountElement.innerText = unreadCount > 4 ? '4+' : unreadCount;

    // 2. Sort by date descending
    dataArray.sort((a, b) => {
        return parseDate(b.createdAt) - parseDate(a.createdAt);
    });

    // 3. Render Top 5
    container.innerHTML = '';
    const displayItems = dataArray.slice(0, 5);

    if (displayItems.length === 0) {
        container.innerHTML = '<div class="p-3 text-center border-bottom text-muted">No notifications</div>';
        return;
    }

    displayItems.forEach((item) => {
        const targetUrl = getNotificationUrl(item);
        const notificationItem = document.createElement('a');
        notificationItem.href = targetUrl;
        notificationItem.className = "text-reset notification-item border-bottom d-block";

        notificationItem.innerHTML = `
            <div class="d-flex p-3">
                <div class="flex-shrink-0 me-3">
                    <img src="${item.imageURL || '{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}'}"
                        class="rounded-circle avatar-sm">
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted small float-end">${timeAgo(item.createdAt)}</p>
                    <h6 class="mb-1">${item.title}</h6>
                    <p class="mb-0 text-muted small">${item.description}</p>
                </div>
            </div>
        `;

        container.appendChild(notificationItem);
    });
}

// 1️⃣ Listener for Doctor/ node
onValue(dbRef, (snapshot) => {
    const data = snapshot.val();
    if (data) {
        // Merge into central store
        Object.keys(data).forEach(key => {
            bellNotifications['doc_' + key] = data[key];
        });

        refreshHeaderDropdown();

        // Handle real-time alerts (Toasts)
        const dataArray = Object.values(data);
        dataArray.forEach((item) => {
            const timestamp = parseDate(item.createdAt);
            const targetUrl = getNotificationUrl(item);

            if (lastKnownEntryTimestamp === null || timestamp > lastKnownEntryTimestamp) {
                if (initialLoadCompleted) {
                    showToast(item.description, item.title, targetUrl);
                }
                lastKnownEntryTimestamp = timestamp;
            }
        });
    }
    initialLoadCompleted = true;
});

// 2️⃣ New Requirement: Patient Specific Notifications listener
@if(Auth::check() && !empty(Auth::user()->firebase_user_key))
const patientNotifRef = query(ref(database, 'Nottifications/{{ Auth::user()->firebase_user_key }}'), limitToLast(20));

onValue(patientNotifRef, (snapshot) => {
    const data = snapshot.val();
    if (data) {
        // Merge into central store
        Object.keys(data).forEach(key => {
            bellNotifications['pat_' + key] = data[key];
        });

        refreshHeaderDropdown();

        // Handle real-time alerts
        const items = Object.values(data);
        let maxTimestamp = lastPatientNotifTimestamp;

        items.forEach((item) => {
            const timestamp = item.createdAt ? parseDate(item.createdAt) : 0;
            
            // Show toast only if initial load is done AND the notification is newer than our tracking
            if (initialLoadCompleted && timestamp > lastPatientNotifTimestamp) {
                const targetUrl = getNotificationUrl(item);
                showToast(item.description, item.title, targetUrl);
            }

            if (timestamp > maxTimestamp) {
                maxTimestamp = timestamp;
            }
        });

        // Update tracking to the newest one we've seen
        lastPatientNotifTimestamp = maxTimestamp;
    }
    initialLoadCompleted = true;
});
@endif

// Helpers
function parseDate(dateString) {
    // Expected format: dd-mm-yyyy hh:mm:ss (GMT)
    const [dd, mm, yyyy, hh, ii, ss] = dateString.split(/[ :\-]/);
    // Use Date.UTC to ensure it's parsed as GMT/UTC
    return new Date(Date.UTC(yyyy, mm - 1, dd, hh, ii, ss)).getTime();
}

function formatLocalTime(dateString) {
    const timestamp = parseDate(dateString);
    const date = new Date(timestamp);
    
    // Format: DD-MM-YYYY HH:mm:ss (Local)
    const pad = (num) => String(num).padStart(2, '0');
    return `${pad(date.getDate())}-${pad(date.getMonth() + 1)}-${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
}

function timeAgo(dateString) {
    const parsedDate = parseDate(dateString);
    const now = Date.now();
    const seconds = Math.floor((now - parsedDate) / 1000);

    if (seconds < 60) return seconds + " sec ago";
    if (seconds < 3600) return Math.floor(seconds/60) + " min ago";
    if (seconds < 86400) return Math.floor(seconds/3600) + " hr ago";
    return Math.floor(seconds/86400) + " day ago";
}
</script>
    @yield('styles')
</head>

<body class="tt-magic-cursor">

    <div id="magic-cursor">
        <div id="ball"></div>
    </div>

    <!-- Back To Top -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
        </svg>
        <svg class="arrow" width="22" height="25" viewBox="0 0 24 23" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0.556131 11.4439L11.8139 0.186067L13.9214 2.29352L13.9422 20.6852L9.70638 20.7061L9.76793 8.22168L3.6064 14.4941L0.556131 11.4439Z"/>
            <path d="M23.1276 11.4999L16.0288 4.40105L15.9991 10.4203L20.1031 14.5243L23.1276 11.4999Z"/>
        </svg>
    </div>

    @include('front.layouts.header')

    @yield('content')
    
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    @include('front.layouts.footer')
    

    <!-- Enquiry Modal section Start-->
    @yield('modals')

    <!--  Main jQuery  -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    
    <!-- Popper and Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <!-- Swiper slider JS -->
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <!-- Waypoints JS -->
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <!-- Counterup JS -->
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Wow JS -->
    <!--<script src="{{ asset('assets/js/wow.min.js') }}"></script>-->
    <!-- Nice Select JS -->
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Gsap  JS -->
    <script src="{{ asset('assets/js/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
    <!-- Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.6.0/air-datepicker.min.js"></script>
    <script src="{{ asset('assets/js/select-dropdown.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- Toastr JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   <script>
function showSimpleToast(message, type = 'success') {
    // Create container if not exists
    if ($('#simpleToastContainer').length === 0) {
        $('body').append('<div id="simpleToastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 99999; min-width: 300px;"></div>');
    }
    
    // Set color based on type
    let bgColor = 'bg-success';
    if (type === 'error') bgColor = 'bg-danger';
    if (type === 'warning') bgColor = 'bg-warning';
    if (type === 'info') bgColor = 'bg-info';
    
    // Create unique ID
    const toastId = 'toast_' + Date.now();
    
    // Create toast HTML
    const toastHtml = `
        <div id="${toastId}" class="toast show mb-2" role="alert" style="min-width: 250px;">
            <div class="toast-header ${bgColor} text-white">
                <strong class="me-auto">${type.toUpperCase()}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body ${bgColor} text-white">
                ${message}
            </div>
        </div>
    `;
    
    // Add to container
    $('#simpleToastContainer').append(toastHtml);
    
    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    // Remove from DOM after hide
    toastElement.addEventListener('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("form").forEach(function (form) {
            form.setAttribute("autocomplete", "off");
        });

        document.querySelectorAll("input").forEach(function (input) {
            if (input.type === "password") {
                input.setAttribute("autocomplete", "new-password");
            } else {
                input.setAttribute("autocomplete", "off");
            }
        });
        
    });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    
        let hasLocation = {{ session()->has('current_latitude') ? 1 : 0 }};

       
    
        if (hasLocation === 0) {
           
            if (navigator.geolocation) {
                
    
                navigator.geolocation.getCurrentPosition(function (position) {
    
                    fetch("{{ route('store.location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
    
                }, function (error) {
    
                    // If user denies location â†’ fallback to Dubai
                    fetch("{{ route('store.location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude: 25.2048,
                            longitude: 55.2708
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
    
                });
    
            }
        }
    
    });
    </script>





  

<script>
$(document).ready(function() {
    // Make profile image clickable
    $('#sidebarProfileImage').on('click', function() {
        $('#sidebarProfileImageUpload').click();
    });
    
    // Handle image upload
    $('#sidebarProfileImageUpload').on('change', function(e) {
        var file = e.target.files[0];
        
        if (!file) {
            return;
        }
        
        // Validate file type
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showSimpleToast('Please upload a valid image file (JPEG, PNG, or GIF)', 'error');
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
             showSimpleToast('Image size must be less than 2MB', 'error');
            return;
        }
        
        // Preview image
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#sidebarProfileImage').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
        
        // Upload image
        var formData = new FormData();
        formData.append('profile_image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("front.profile.upload.image") }}',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#sidebarProfileImage').css('opacity', '0.6');
               // toastr.info('Uploading...');
            },
            success: function(response) {
                $('#sidebarProfileImage').css('opacity', '1');
                if (response.status == '1') {
                    showSimpleToast(response.message, 'success');
                    if (response.image_url) {
                        $('#sidebarProfileImage').attr('src', response.image_url + '?t=' + Date.now());
                    }
                    // Also update profile page image if it exists
                    if ($('#profileImagePreview').length) {
                        $('#profileImagePreview').attr('src', response.image_url + '?t=' + Date.now());
                    }
                } else {
                    showSimpleToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                $('#sidebarProfileImage').css('opacity', '1');
                 showSimpleToast('Failed to upload image. Please try again.', 'error');
            }
        });
    });
});
</script>
    @yield('scripts')
</body>

</html>
