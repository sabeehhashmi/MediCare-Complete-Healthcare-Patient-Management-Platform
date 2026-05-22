
@section('styles')
<style>
    .profile-image-small {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>


<style>
/* Patient ID Styles */
.patient-id-container {
    margin: 8px 0 12px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    display: inline-block;
    width: auto;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.patient-id-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    display: block;
}

.patient-id-value {
    font-size: 14px;
    font-weight: 600;
    color: #1baeff;
    letter-spacing: 0.5px;
    font-family: 'Courier New', monospace;
}

.patient-id-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #1baeff10;
    border-left: 3px solid #1baeff;
    border-radius: 4px;
    font-size: 12px;
    margin-top: 5px;
    margin-bottom: 10px;
}

.patient-id-badge i {
    color: #1baeff;
    margin-right: 5px;
}

.patient-id-badge span {
    color: #495057;
    font-weight: 500;
}

.patient-id-badge strong {
    color: #1baeff;
    font-family: monospace;
    font-size: 13px;
}

/* Profile Image Styles */
.profile-image-small {
    width: 80px;
    height: 80px;
    border-radius: 50% !important;
    object-fit: cover;
    border: 3px solid #1baeff;
    cursor: pointer;
    transition: opacity 0.3s ease, transform 0.2s ease;
    margin-bottom: 10px;
    display: block;
}

.profile-image-small:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

#sidebarProfileImageUpload {
    display: none;
}

/* Camera icon overlay */
.profile-image-wrapper {
    position: relative;
    display: inline-block;
}

.profile-image-wrapper::after {
    /*content: "📷";*/
    /*position: absolute;*/
    /*bottom: 5px;*/
    /*right: 5px;*/
    /*background: #1baeff;*/
    /*border-radius: 50%;*/
    /*width: 28px;*/
    /*height: 28px;*/
    /*display: flex;*/
    /*align-items: center;*/
    /*justify-content: center;*/
    /*font-size: 14px;*/
    /*color: white;*/
    /*opacity: 0;*/
    /*transition: opacity 0.3s ease;*/
    /*pointer-events: none;*/
        content: "\ec84";
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: #1baeff;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    font-family: boxicons !important;
}

.profile-image-wrapper:hover::after {
    opacity: 1;
}
</style>
@endsection
<div class="home8-country-serve-section">
    <div class="single-item">
        <div class="title-area">
            @auth
                @php
                    $user = auth()->user();
                    $fullName = trim($user->first_name . ' ' . $user->last_name);
                @endphp

            <div class="profile-image-wrapper">
                <img class="profile-image-small" src="{{ $user->user_img_url }}" alt="" id="sidebarProfileImage">
                <input type="file" id="sidebarProfileImageUpload" accept="image/jpeg,image/png,image/jpg,image/gif" style="display: none;">
            </div>
            <div>
                <h4>
                    {{ $fullName ?: $user->name }}
                </h4>
                 @if($user->role == USER_ROLE && $user->patient_id)
                    <!-- Option 1: Clean badge style -->
                    <div class="patient-id-badge">
                        <i class="bx bx-id-card"></i>
                        <span>Patient ID:</span>
                        <strong>{{ $user->patient_id }}</strong>
                    </div>
                    
                    <!-- Option 2: Or use the container style (uncomment if preferred) -->
                    <!-- <div class="patient-id-container">
                        <span class="patient-id-label">Patient ID</span>
                        <span class="patient-id-value">{{ $user->patient_id }}</span>
                    </div> -->
                @endif
                @else
                    Guest User
                @endauth
            </div>
        </div>
        <svg class="line" height="6" viewBox="0 0 262 6" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 2.5L0 0.113249V5.88675L5 3.5V2.5ZM257 3.5L262 5.88675V0.113249L257 2.5V3.5ZM4.5 3.5H257.5V2.5H4.5V3.5Z"/>
        </svg>
        <ul>
            <li>
                <a href="{{ route('front.bookings') }}" class="{{ Route::is('front.bookings') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M7 11h2v2H7zm0 4h2v2H7zm4-4h2v2h-2zm0 4h2v2h-2zm4-4h2v2h-2zm0 4h2v2h-2z"></path><path d="M5 22h14c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2h-2V2h-2v2H9V2H7v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2zM19 8l.001 12H5V8h14z"></path></svg>
                    My Appointments</a>
            </li>
            <li>
                <a href="{{ route('front.invoices.index') }}" class="{{ Route::is('front.invoices.*') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                        <path d="M20 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 2v.001L12 12 4 5h16zM4 7.154V19h16V7.154l-6.427 5.787c-.328.293-.744.439-1.156.439-.412 0-.828-.146-1.156-.439L4 7.154z"/>
                    </svg>
                    Invoices
                </a>
            </li>
            <!-- <li>
                <a href="{{ url('/useraccount-orders') }}" class="{{ Request::is('useraccount-orders') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="fill: rgba(0, 0, 0, 1);"><g><path d="M19.24 9.81v6.87c0 .33-.37.53-.65.36l-2.37-1.46a.392.392 0 0 0-.44 0l-2.37 1.46a.429.429 0 0 1-.65-.36V9.81zM11.419 8.31 12.131 2H7.06c-.68 0-1.31.39-1.6 1.01l-2.49 5.3zM29.03 8.31l-2.49-5.3c-.29-.62-.92-1.01-1.6-1.01h-5.071l.711 6.31zM19.071 8.31 18.36 2h-4.72l-.711 6.31z"></path><path d="M29.65 9.81h-8.91v6.87a1.928 1.928 0 0 1-2.929 1.643l-1.813-1.117-1.802 1.112a1.933 1.933.0 0 1-2.328-.236 1.922 1.922 0 0 1-.609-1.4V9.81H2.35c-.06.24-.09.49-.09.74v17.69c0 .97.79 1.76 1.76 1.76h23.96c.97 0 1.76-.79 1.76-1.76V10.55c0-.25-.03-.5-.09-.74zM6.563 21.35h2.781a.9.9 0 0 1 0 1.8H6.563a.9.9 0 0 1 0-1.8zm4.75 5.206h-4.75a.9.9 0 0 1 0-1.8h4.75a.9.9 0 0 1 0 1.8z"></path></g></svg>
                    My Orders</a>
            </li> -->
            <li>
                <a href="{{ route('front.patients') }}" class="{{ Route::is('front.patients') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20 2H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-6 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM19 15H9v-.25C9 12.901 11.254 11 14 11s5 1.901 5 3.75V15z"></path><path d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8z"></path></svg>
                    Saved Patients</a>
            </li>
             <li>
                <a href="{{ route('front.chat.index') }}" class="{{ Route::is('front.chat.index') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20 2H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-6 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM19 15H9v-.25C9 12.901 11.254 11 14 11s5 1.901 5 3.75V15z"></path><path d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8z"></path></svg>
                    Messages</a>
            </li>
            <li>
                <a href="{{ url('/useraccount-profile') }}" class="{{ Request::is('useraccount-profile') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M15 11h7v2h-7zm1 4h6v2h-6zm-2-8h8v2h-8zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2zm4-7c1.995 0 3.5-1.505 3.5-3.5S9.995 5 8 5 4.5 6.505 4.5 8.5 6.005 12 8 12z"></path></svg>
                    My Profile</a>
            </li>
            <li>
                <a href="{{ url('/useraccount-reports?type=lab') }}" class="{{ Request::is('useraccount-reports') ? 'active-item' : '' }}">
                    <svg  class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="fill: rgba(0, 0, 0, 1);"><g><path d="M22.49 2H5.709a2 2 0 0 0-2 2v20.197a2 2 0 0 0 2 2h16.78a2 2 0 0 0 2-2V4c.001-1.105-.895-2-1.999-2zM9.782 19.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm0-4.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm0-4.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm9.909 9.3h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8zm0-4.65h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8zm0-4.65h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8z"></path><path d="m26.29 5.8-.001 18.397c0 2.095-1.705 3.8-3.8 3.8H7.51V28c0 1.1.9 2 2 2h16.78a2 2 0 0 0 2-2V7.8a2 2 0 0 0-2-2z"></path></g></svg>
                    My Reports</a>
            </li>
            <li>
                <a href="{{ url('/useraccount-feedback') }}" class="{{ Request::is('useraccount-feedback') ? 'active-item' : '' }}">
                    <svg  class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="fill: rgba(0, 0, 0, 1);"><g><path d="M22.49 2H5.709a2 2 0 0 0-2 2v20.197a2 2 0 0 0 2 2h16.78a2 2 0 0 0 2-2V4c.001-1.105-.895-2-1.999-2zM9.782 19.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm0-4.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm0-4.65H8.511a.9.9 0 0 1 0-1.8h1.271a.9.9 0 0 1 0 1.8zm9.909 9.3h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8zm0-4.65h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8zm0-4.65h-6.48a.9.9 0 0 1 0-1.8h6.48a.9.9 0 0 1 0 1.8z"></path><path d="m26.29 5.8-.001 18.397c0 2.095-1.705 3.8-3.8 3.8H7.51V28c0 1.1.9 2 2 2h16.78a2 2 0 0 0 2-2V7.8a2 2 0 0 0-2-2z"></path></g></svg>
                    My Reviews</a>
            </li>
            <li>
                <a href="{{ url('/useraccount-points') }}" class="{{ Request::is('useraccount-points') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="fill: rgba(0, 0, 0, 1);"><g><path d="M19.24 9.81v6.87c0 .33-.37.53-.65.36l-2.37-1.46a.392.392 0 0 0-.44 0l-2.37 1.46a.429.429 0 0 1-.65-.36V9.81zM11.419 8.31 12.131 2H7.06c-.68 0-1.31.39-1.6 1.01l-2.49 5.3zM29.03 8.31l-2.49-5.3c-.29-.62-.92-1.01-1.6-1.01h-5.071l.711 6.31zM19.071 8.31 18.36 2h-4.72l-.711 6.31z"></path><path d="M29.65 9.81h-8.91v6.87a1.928 1.928 0 0 1-2.929 1.643l-1.813-1.117-1.802 1.112a1.933 1.933.0 0 1-2.328-.236 1.922 1.922 0 0 1-.609-1.4V9.81H2.35c-.06.24-.09.49-.09.74v17.69c0 .97.79 1.76 1.76 1.76h23.96c.97 0 1.76-.79 1.76-1.76V10.55c0-.25-.03-.5-.09-.74zM6.563 21.35h2.781a.9.9 0 0 1 0 1.8H6.563a.9.9 0 0 1 0-1.8zm4.75 5.206h-4.75a.9.9 0 0 1 0-1.8h4.75a.9.9 0 0 1 0 1.8z"></path></g></svg>
                    Loyalty & Rewards</a>
            </li>
            <li>
                <a href="{{ route('front.notifications') }}" class="{{ Route::is('front.notifications') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.073 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14V10c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path></svg>
                    Notifications</a>
            </li>
            <li>
                <a href="{{ route('front.settings') }}" class="{{ Route::is('front.settings') ? 'active-item' : '' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m2.344 15.271 2 3.46a1 1 0 0 0 1.257.433l2.87-1.15a8.312 8.312 0 0 0 2.529 1.464l.432 3.061A1.002 1.002 0 0 0 11.417 23h4.032a1.002 1.002 0 0 0 .984-.861l.432-3.061a8.307 8.307 0 0 0 2.529-1.464l2.87 1.15a1 1 0 0 0 1.257-.433l2-3.46a1 1 0 0 0-.203-1.282l-2.457-1.921c.053-.42.083-.846.083-1.278s-.03-1.02-.083-1.44l2.457-1.921a1 1 0 0 0 .203-1.282l-2-3.46a1 1 0 0 0-1.257-.433L19.14 2.37a8.307 8.307 0 0 0-2.529-1.464l-.432-3.061A1.002 1.002 0 0 0 15.117 0h-4.032a1.002 1.002 0 0 0-.984.861l-.432 3.061a8.312 8.312 0 0 0-2.529 1.464L4.27 4.236a1 1 0 0 0-1.257.433l-2 3.46a1 1 0 0 0 .203 1.282l2.457 1.921c-.053.42-.083.846-.083 1.278s.03.858.083 1.278L2.141 13.99a1 1 0 0 0-.203 1.281l1.406-.001zm11.656-3.271a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"></path></svg>
                    Settings</a>
            </li>
            <li>
                <a href="{{ url('/') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m2 12 5 4v-3h9v-2H7V8z"></path><path d="M13.001 2.999a8.938 8.938 0 0 0-6.364 2.637L8.051 7.05c1.322-1.322 3.08-2.051 4.95-2.051s3.628.729 4.95 2.051 2.051 3.08 2.051 4.95-.729 3.628-2.051 4.95-3.08 2.051-4.95 2.051-3.628-.729-4.95-2.051l-1.414 1.414c1.699 1.7 3.959 2.637 6.364 2.637s4.665-.937 6.364-2.637c1.7-1.699 2.637-3.959 2.637-6.364s-.937-4.665-2.637-6.364a8.938 8.938 0 0 0-6.364-2.637z"></path></svg>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('front.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
        <a href="{{ url('/doctors-list') }}" class="primary-btn1 ">
            <span>
                Make an Appointment
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                    </path>
                </svg>
            </span>
            <span>
                Make an Appointment
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                    </path>
                </svg>
            </span>
        </a>
    </div>
</div>


