<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{config('global.site_name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{config('global.site_name')}}" name="description" />
    <meta content="{{config('global.site_name')}}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('admin-assets/assets/images/favicon.png') }}">

    <!-- include head css -->
    @include('admin.layouts.head-css')
      <!--<script type="module">
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

        // Reference to your database path
        const dbRef = query(ref(database, 'Admin'), limitToLast(5));
        const unreadCountElement = document.getElementById('unread-count');
        // Track the last known entry
        let lastKnownEntryTimestamp = null;
        let initialLoadCompleted = false;

        // Function to show Bootstrap toast notification
        function showToast(message,title='Notification',order_id=0) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('a');
            toast.href = `{{url('admin/appointments/view')}}/${order_id}`;
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
                notificationItem.href = `{{url('admin/appointments/view')}}/${item.order_id}`;
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
    </script>  -->
     <!-- <script type="module">
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

console.log("✅ Firebase initialized");

// Ref
const dbRef = query(ref(database, 'Admin'), limitToLast(5));

const unreadCountElement = document.getElementById('unread-count');
const container = document.getElementById('firebase-data');

// 🔥 FIX: persistent memory (NO duplicate after reload)
let shownNotifications = new Set(
    JSON.parse(localStorage.getItem('shown_admin_notifications') || '[]')
);

// 🔗 URL Resolver
function getNotificationUrl(item) {
    if (item.notificationType === 'chat_message' || item.type === 'chat') {
        return item.url && item.url !== ''
            ? item.url
            : '{{ route("admin.chat.index") }}';
    }

    if (item.order_id && item.order_id !== '') {
        return `{{url('admin/appointments/view')}}/${item.order_id}`;
    }

    return '#';
}

// 🔔 Toast
function showToast(message, title = 'Notification', url = '#') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;

    const toast = document.createElement('a');
    toast.href = url;
    toast.classList.add('toast', 'show');
    toast.style.cursor = 'pointer';

    toast.innerHTML = `
        <div class="toast-header" style="background:#89124b;color:#fff;">
            <strong>${escapeHtml(title)}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" style="background:#89124b;color:#fff;">
            ${escapeHtml(message)}
        </div>
    `;

    toastContainer.appendChild(toast);

    new bootstrap.Toast(toast, { delay: 5000 }).show();

    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

// 🔐 Escape HTML
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;'
    }[m]));
}

// 🔥 Firebase listener
onValue(dbRef, (snapshot) => {
    const data = snapshot.val();

    console.log("🔥 Snapshot received:", data);

    if (!data) return;

    if (!container) return;

    container.innerHTML = '';

    let dataArray = [];

    // safer flatten
    Object.values(data).forEach(group => {
        if (group && typeof group === 'object') {
            Object.values(group).forEach(item => {
                dataArray.push(item);
            });
        }
    });

    console.log("📦 Flattened:", dataArray);

    // unread count
    let unreadArray = dataArray.filter(item => item && (item.read == 0 || item.read == "0"));

    if (unreadCountElement) {
        unreadCountElement.innerText = unreadArray.length > 4 ? '4+' : unreadArray.length;
    }

    dataArray.reverse();

    dataArray.forEach((item) => {
        if (!item) return;

        const targetUrl = getNotificationUrl(item);

        // 🔥 UNIQUE KEY (same logic as FRONT)
        const uniqueKey = item.createdAt + "_" + item.title;

        if (item.read == 0 || item.read == "0") {
            if (!shownNotifications.has(uniqueKey)) {

                console.log("🟢 Toast:", item.title);

                showToast(item.description, item.title, targetUrl);

                shownNotifications.add(uniqueKey);

                // 💾 SAVE (important fix)
                localStorage.setItem(
                    'shown_admin_notifications',
                    JSON.stringify([...shownNotifications])
                );
            }
        }

        const imageUrl = item.imageURL || '{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}';

        const notificationItem = document.createElement('a');
        notificationItem.href = targetUrl;
        notificationItem.className = "text-reset notification-item";

        notificationItem.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                    <img src="${escapeHtml(imageUrl)}"
                         onerror="this.src='{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}'"
                         class="rounded-circle avatar-sm">
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted font-size-13 mb-0 float-end">${timeAgo(item.createdAt)}</p>
                    <h6 class="mb-1">${escapeHtml(item.title)}</h6>
                    <p class="mb-0">${escapeHtml(item.description)}</p>
                </div>
            </div>
        `;

        container.appendChild(notificationItem);
    });
});

// ⏱️ Time ago (same stable logic)
function timeAgo(timestamp) {
    if (!timestamp) return 'just now';

    try {
        const [dd, MM, yyyy, hh, mm, ss] = timestamp.split(/[ :\-]/);
        const date = new Date(Date.UTC(yyyy, MM - 1, dd, hh, mm, ss));
        const seconds = Math.floor((Date.now() - date.getTime()) / 1000);

        if (seconds < 60) return seconds + " sec ago";
        if (seconds < 3600) return Math.floor(seconds/60) + " min ago";
        if (seconds < 86400) return Math.floor(seconds/3600) + " hr ago";
        return Math.floor(seconds/86400) + " day ago";
    } catch {
        return 'just now';
    }
}
</script>
     -->

     <script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getDatabase, ref, onValue, update } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

// Firebase config
const firebaseConfig = {
    apiKey: '{{config("global.apiKey")}}',
    authDomain: '{{config("global.authDomain")}}',
    databaseURL: '{{config("global.databaseURL")}}',
    projectId: '{{config("global.projectId")}}',
    storageBucket: '{{config("global.storageBucket")}}',
    messagingSenderId: '{{config("global.messagingSenderId")}}',
    appId: '{{config("global.appId")}}',
};

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

const dbRef = ref(database, 'Admin');

const unreadCountElement = document.getElementById('unread-count');
const container = document.getElementById('firebase-data');

let shownNotifications = new Set(JSON.parse(localStorage.getItem('shown_admin_notifications') || '[]'));

function getNotificationUrl(item) {
    if (item.notificationType === 'chat_message' || item.type === 'chat') {
        return item.url && item.url !== '' ? item.url : '{{ route("admin.chat.index") }}';
    }
    if (item.order_id && item.order_id !== '') {
        return `{{url('admin/appointments/view')}}/${item.order_id}`;
    }
    return '#';
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function showToast(message, title = 'Notification', url = '#') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;

    const toast = document.createElement('a');
    toast.href = url;
    toast.classList.add('toast', 'show');
    toast.style.cursor = 'pointer';

    toast.innerHTML = `
        <div class="toast-header" style="background:#89124b;color:#fff;">
            <strong>${escapeHtml(title)}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" style="background:#89124b;color:#fff;">
            ${escapeHtml(message)}
        </div>
    `;

    toastContainer.appendChild(toast);
    new bootstrap.Toast(toast, { delay: 5000 }).show();
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

function timeAgo(timestamp) {
    if (!timestamp) return 'just now';
    
    try {
        const parts = timestamp.split(' ');
        const datePart = parts[0];
        const timePart = parts[1];
        const [day, month, year] = datePart.split('-');
        const [hours, minutes, seconds] = timePart.split(':');
        const parsedDate = new Date(Date.UTC(year, parseInt(month) - 1, day, hours, minutes, seconds));
        const now = Date.now();
        const diff = Math.floor((now - parsedDate) / 1000);

        if (diff < 60) return diff + " sec ago";
        if (diff < 3600) return Math.floor(diff / 60) + " min ago";
        if (diff < 86400) return Math.floor(diff / 3600) + " hr ago";
        return Math.floor(diff / 86400) + " day ago";
    } catch(e) {
        return 'just now';
    }
}

// EXACT SAME LOGIC AS YOUR WORKING PAGE
onValue(dbRef, (snapshot) => {
    const data = snapshot.val();
    console.log(data);

    if (!data) return;
    if (!container) return;

    container.innerHTML = '';

    // Same as working page: convert to array and reverse
    let dataArray = Object.values(data);
    dataArray.reverse();

    // For dropdown, take only first 5 (most recent)
    const latest5 = dataArray.slice(0, 5);

    // Count unread from ALL data (not just 5)
    let unreadCount = Object.values(data).filter(item => item && (item.read == 0 || item.read === "0")).length;
    if (unreadCountElement) {
        unreadCountElement.innerText = unreadCount > 4 ? '4+' : unreadCount;
    }

    latest5.forEach((item) => {
        if (!item) return;

        const targetUrl = getNotificationUrl(item);
        const uniqueKey = item.createdAt + "_" + item.title;

        if ((item.read == 0 || item.read === "0") && !shownNotifications.has(uniqueKey)) {
            showToast(item.description, item.title, targetUrl);
            shownNotifications.add(uniqueKey);
            localStorage.setItem('shown_admin_notifications', JSON.stringify([...shownNotifications]));
        }

        const imageUrl = item.imageURL || '{{ URL::asset("admin-assets/assets/images/placeholder.jpg") }}';
        
        let readIcon = '';
        if (item.read == 0 || item.read === "0") {
            readIcon = '<span style="color:red;">*</span>';
        }

        const notificationItem = document.createElement('a');
        notificationItem.href = targetUrl;
        notificationItem.className = "text-reset notification-item";

        notificationItem.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                    <img src="${escapeHtml(imageUrl)}" onerror="this.src='{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}'"
                        class="rounded-circle avatar-sm" alt="user-pic">
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted font-size-13 mb-0 float-end">${timeAgo(item.createdAt)}</p>
                    <h6 class="mb-1">${escapeHtml(item.title)} ${readIcon}</h6>
                    <div>
                        <p class="mb-0">${escapeHtml(item.description)}</p>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(notificationItem);
    });
});
</script>
</head>

@yield('body')

<!-- Begin page -->
<div id="layout-wrapper">
    <!-- topbar -->
    @include('admin.layouts.topbar')

    <!-- sidebar components -->
    @include('admin.layouts.sidebar')
    @include('admin.layouts.horizontal')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- footer -->
        @include('admin.layouts.footer')

    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11;"></div>
<!-- customizer -->
@include('admin.layouts.right-sidebar')

<div class="modal" id="reset-password-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="appointmentModalLabel">Reset Your Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="reset-password-section">
                    <div class="form-group position-relative my-1">
                        <label for="old_password">Old Password</label>
                        <input type="password" name="old_password" id="old_password" class="form-control" required>
                        <small id="old_password_error" class="text-danger"></small>
                        <span class="position-absolute show-password" style="top: 45px; right: 20px;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="form-group my-1 position-relative">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                        <small id="new_password_error" class="text-danger"></small>
                        <span class="position-absolute show-password" style="top: 45px; right: 20px;">
                            <i class="fas fa-eye"></i>
                        </span>
                        <div class="checklist" id="checklist">
                            <ul>
                                <li id="password_limit">
                                    <span><i class="fas fa-times"></i></span>
                                    Between 6 and 12 characters
                                </li>
                                <li id="uppercase_letter">
                                    <span><i class="fas fa-times"></i></span>
                                    An Upper Case Letter
                                </li>
                                <li id="lowercase_letter">
                                    <span><i class="fas fa-times"></i></span>
                                    A Lowercase Letter
                                </li>
                                <li id="number">
                                    <span><i class="fas fa-times"></i></span>
                                    A Number
                                </li>
                                <li id="special_character">
                                    <span><i class="fas fa-times"></i></span>
                                    A Special Character
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group my-1 position-relative">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        <small id="confirm_password_error" class="text-danger"></small>
                        <span class="position-absolute show-password" style="top: 45px; right: 20px;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <button id="reset-password-btn" class="btn btn-success">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="reset-password-success-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="reset-password-section">
                    <div class="text-center">
                        <h5>Password updated successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- vendor-scripts -->
@include('admin.layouts.vendor-scripts')

</body>

</html>
