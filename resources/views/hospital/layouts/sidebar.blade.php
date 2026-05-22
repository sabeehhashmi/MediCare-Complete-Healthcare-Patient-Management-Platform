<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{route('admin.dashboard')}}" class="logo logo-dark">

            <span class="logo-lg">
                <img src="{{ URL::asset('admin-assets/assets/images/Mednero.svg') }}" alt="" height="28">
            </span>
        </a>

        <a href="{{route('admin.dashboard')}}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ URL::asset('admin-assets/assets/images/Mednero.svg') }}" alt="" height="30">
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('admin-assets/assets/images/logo-sm.svg') }}" alt="" height="26">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Dashboard</li>

               <li>
                    <a href="{{route('admin.dashboard')}}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Dashboard</span>

                    </a>
                </li>
                <li class="menu-title" data-key="t-applications">Application</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-envelope icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Masters</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if(get_user_permission('services','r'))
                        <li><a href="{{route('admin.services.list')}}" data-key="t-inbox">Services</a></li>
                        @endif
                        @if(get_user_permission('qualifications','r'))
                        <li><a href="{{route('admin.qualifications.list')}}" data-key="t-inbox">Qualifications</a></li>
                        @endif
                        @if(get_user_permission('licencetype','r'))
                        <li><a href="{{route('admin.licencetype.list')}}" data-key="t-inbox">Licence Types</a></li>
                        @endif
                        @if(get_user_permission('special_intrests','r'))
                        <li><a href="{{route('admin.special_intrests.list')}}" data-key="t-inbox">Special Intrests</a></li>
                        @endif
                        @if(get_user_permission('languages','r'))
                        <li><a href="{{route('admin.languages.list')}}" data-key="t-inbox">Languages</a></li>
                        @endif
                        @if(get_user_permission('medical_condition','r'))
                        <li><a href="{{route('admin.medical_condition.list')}}" data-key="t-inbox">Medical Condition</a></li>
                        @endif
                        @if(get_user_permission('insurence_policy','r'))
                        <li><a href="{{route('admin.insurence_policy.list')}}" data-key="t-inbox">Insurance Policy</a></li>
                        @endif
                        @if(get_user_permission('sub_insurence_policy','r'))
                        <li><a href="{{route('admin.sub_insurence_policy.list')}}" data-key="t-inbox">Sub Insurance Policy</a></li>
                        @endif
                        @if(get_user_permission('specialties','r'))
                        <li><a href="{{route('admin.specialties.index')}}" data-key="t-read-email">Specialties</a></li>
                        @endif
                        @if(get_user_permission('countries','r'))
                        <li><a href="{{route('admin.countries.index')}}" data-key="t-read-email">Countries</a></li>
                        @endif
                        @if(get_user_permission('emirates','r'))
                        <li><a href="{{route('admin.emirates.index')}}" data-key="t-read-email">Cities</a></li>
                        @endif
                        @if(get_user_permission('areas','r'))
                        <li><a href="{{route('admin.areas.index')}}" data-key="t-read-email">Areas</a></li>
                        @endif
                        @if(get_user_permission('hospitals','r'))
                        <li><a href="{{route('admin.hospitals.index')}}" data-key="t-read-email">Hospitals</a></li>
                        @endif
                        @if(get_user_permission('doctors','r'))
                        <li><a href="{{route('admin.doctors.index')}}" data-key="t-read-email">Doctors</a></li>
                        @endif

                    </ul>
                </li>

                @if(Auth::user()->role_id == 1)
                <li class="menu-title" data-key="t-administration">Administration </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-user-circle icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Staff Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    @if(get_user_permission('agents','r'))
                        <li><a href="{{route('admin.agents.index')}}" data-key="t-read-email">Agents</a></li>
                        @endif
                        <li><a href="{{route('admin.user_roles.list')}}" data-key="t-inbox">User Role</a></li>
                        <li><a href="{{route('admin.admin_users.index')}}" data-key="t-read-email">Admin Users</a></li>
                    </ul>
                </li>
                @endif

                @if(get_user_permission('settings','r'))
                <li class="menu-title" data-key="t-pages">Pages</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-user-circle icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Site Pages</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.contact_details')}}" data-key="t-read-email">Contact Details</a></li>
                    </ul>
                </li>
                @endif




            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
