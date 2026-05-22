@extends('front.template.layout')

@section('title', 'Notifications')

@section('styles')
<style>
    .notification-list {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .notification-item {
        display: block;
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
        text-decoration: none !important;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background: #f8f9fa;
    }
    .notification-item.unread {
        background: #1baeff05;
        border-left: 3px solid #1baeff;
    }
    .notif-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    .notif-desc {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .notif-time {
        font-size: 12px;
        color: #999;
    }
</style>
@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="notification-list">
                    <div class="checkout-form-title d-flex justify-content-between align-items-center">
                        <h4>Notifications</h4>
                        <span id="load-status" class="badge bg-light text-dark">Connecting...</span>
                    </div>

                    <div id="notification-tbody">
                        <div class="text-center p-5" id="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <div id="empty-state" style="display: none;" class="text-center p-5">
                        <i class="bx bx-bell-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="mt-2 text-muted">No notifications found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getDatabase, ref, onValue, query, limitToLast, orderByKey, update } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

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

const userKey = '{{ $user->firebase_user_key }}';
const notifRef = ref(database, 'Nottifications/' + userKey);
const container = document.getElementById('notification-tbody');
const spinner = document.getElementById('loading-spinner');
const emptyState = document.getElementById('empty-state');
const statusBadge = document.getElementById('load-status');

if (!userKey) {
    spinner.style.display = 'none';
    emptyState.style.display = 'block';
    statusBadge.innerText = 'Key Missing';
} else {
    // Basic real-time listener for last 50 notifications
    const recentNotifsQuery = query(notifRef, limitToLast(50));

    onValue(recentNotifsQuery, (snapshot) => {
        spinner.style.display = 'none';
        statusBadge.innerText = 'Live';
        
        const data = snapshot.val();
        if (!data) {
            container.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        container.innerHTML = '';

        // Convert to array and sort by key (timestamp) descending
        const items = Object.entries(data).sort((a, b) => b[0] - a[0]);

        items.forEach(([key, item]) => {
            const isUnread = item.read == 0 || item.read == "0";
            const row = document.createElement('div');
            row.className = `notification-item ${isUnread ? 'unread' : ''}`;
            
            const targetUrl = getNotificationUrl(item);

            const localTime = item.createdAt ? formatLocalTime(item.createdAt) : '';

            row.innerHTML = `
                <a href="${targetUrl}" class="d-flex align-items-center" onclick="markAsRead('${key}')">
                    <div class="flex-grow-1">
                        <div class="notif-title">${item.title}</div>
                        <div class="notif-desc">${item.description}</div>
                        <div class="notif-time">${localTime}</div>
                    </div>
                </a>
            `;
            container.appendChild(row);
        });
    });
}

function getNotificationUrl(item) {
    const type = item.notificationType || item.type;
    const order_id = item.order_id || '0';
    const channel = item.channel_name || order_id;

    if (type === 'call' || type === 'video_call') {
        return `{{ url('video-call') }}/${channel}`;
    }
    if (type === 'bulk_broadcast') {
        return `{{ route('front.notifications') }}`;
    }
    if (type === 'chat_message') {
        return `{{ route('front.chat.index') }}`;
    }
    if (order_id !== '0' && order_id !== '') {
        return `{{ url('useraccount-appointment-details') }}/${order_id}`;
    }
    return 'javascript:void(0)';
}

function parseDate(dateString) {
    const [dd, mm, yyyy, hh, ii, ss] = dateString.split(/[ :\-]/);
    return new Date(Date.UTC(yyyy, mm - 1, dd, hh, ii, ss)).getTime();
}

function formatLocalTime(dateString) {
    const timestamp = parseDate(dateString);
    const date = new Date(timestamp);
    const pad = (num) => String(num).padStart(2, '0');
    return `${pad(date.getDate())}-${pad(date.getMonth() + 1)}-${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
}

window.markAsRead = function(key) {
    const updates = {};
    updates[`Nottifications/${userKey}/${key}/read`] = "1";
    update(ref(database), updates);
};

</script>
@endsection