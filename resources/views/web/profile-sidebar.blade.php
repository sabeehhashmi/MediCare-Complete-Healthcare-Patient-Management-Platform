<div class="chat-leftsidebar card">
    <div class="card-body" style="flex: 0;">
        <div class="text-center bg-light rounded px-4 py-3">
            <div class="chat-user-status mt-3">
                <img src="{{Auth::User()->user_img_url}}" class="avatar-md rounded-circle" alt="" />
            </div>
            <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{Auth::User()->first_name}} {{Auth::User()->last_name}} </a></h5>
            <p class="text-muted mb-0">{{Auth::User()->email}}</p>
            <!-- <p class="text-muted mb-0">AL NASEEM MEDICAL CENTER LLC</p> -->
        </div>
    </div>

    <div class="mail-list">
        <!-- <a href="#" class=" ">
            <div class="d-flex align-items-center">
                <i class="bx bx-user-circle font-size-20 align-middle me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">My Profile</h5>
                </div>
            </div>
        </a> -->
        <a href="{{url('website/patient-profile')}}" class="border-bottom {{($page_heading ?? null) == 'Profile' ? 'active bg-primary-subtle' : ''}}">
            <div class="d-flex align-items-center">
                <i class="bx bx-edit-alt font-size-20 align-middle me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">Edit Profile</h5>
                </div>
                <div class="flex-shrink-0"></div>
            </div>
        </a>

        <a href="{{url('website/patient-appointment')}}" class="border-bottom {{($page_heading ?? null) == 'Appointments' ? 'active bg-primary-subtle' : ''}}">
            <div class="d-flex align-items-center">
                <i class="bx bx-calendar font-size-20 align-middle me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">My Appointments</h5>
                </div>
                <div class="flex-shrink-0"></div>
            </div>
        </a>

        <a href="{{url('website/patient-members')}}" class="border-bottom {{($page_heading ?? null) == 'Patients' ? 'active bg-primary-subtle' : ''}}">
            <div class="d-flex align-items-center">
                <i class="bx bx-group font-size-20 align-middle me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">My Patients</h5>
                </div>
                <div class="flex-shrink-0"></div>
            </div>
        </a>

        <!-- <a href="{{url('website/change_password')}}" class="border-bottom {{($page_heading ?? null) == 'Change Password' ? 'active bg-primary-subtle' : ''}}">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">Change Password</h5>
                </div>
                <div class="flex-shrink-0"></div>
            </div>
        </a> -->
    </div>
</div>