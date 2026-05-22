<!-- ========== Left Sidebar Start ========== -->
<?php
    $CurrentUrl = url()->current();
    if (!function_exists('isActiveCMS')) {
        function isActiveCMS($patterns, $CurrentUrl) {
            $patterns_flattened = implode('|', $patterns);
            return preg_match('/' . $patterns_flattened . '/', $CurrentUrl) ? true : false;
        }
    }
    
?>
@php
    $userType = request()->get('user_type');
@endphp
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{route('admin.dashboard')}}" class="logo logo-dark">

            <span class="logo-lg">
                <img src="{{ URL::asset('admin-assets/assets/images/logo-mednero.png') }}" alt="" height="28">
            </span>
            <span class="logo-sm py-4">
                <img src="{{ URL::asset('admin-assets/assets/images/logo-mednero-sm.png') }}" alt="" height="28">
            </span>
        </a>

        <a href="{{route('admin.dashboard')}}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ URL::asset('admin-assets/assets/images/logo-mednero.png') }}" alt="" height="30">
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('admin-assets/assets/images/logo-mednero-sm.png') }}" alt="" height="26">
            </span>
        </a>
    </div>

    <button type="button" id="sidebarHide" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Dashboard</li>

               <?php $patterns = array('admin\/dashboard');
                    $patterns_flattened = implode('|', $patterns);
                ?>
                <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
                    <a href="{{route('admin.dashboard')}}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Dashboard</span>

                    </a>
                </li>


               <!-- Add this to your admin sidebar menu -->
                <li class="menu-title" data-key="t-menu">Chat</li>

                <li class="{{ request()->routeIs('admin.chat.index') ? 'active' : null }}">
                    <a href="{{ route('admin.chat.index') }}">
                        <i class="bx bx-chat icon nav-icon"></i>
                        <span class="menu-item" data-key="t-chat">My Chat</span>
                        <span class="badge bg-danger rounded-pill ms-1" id="chat-unread-count" style="display: none; font-size: 10px;">0</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.chat.monitor*') ? 'active' : null }}">
                    <a href="{{ route('admin.chat.monitor') }}">
                        <i class="bx bx-show-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-chat-monitor">Monitor Chats</span>
                    </a>
                </li>

   
                <li class="menu-title" data-key="t-applications">Application</li>

                @if(get_user_permission('appoitments','r'))
                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-check-shield icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Appointments</span>
                    </a>

                    <?php
                        // ✅ FIX: Use route-based detection instead of regex (no overlap bugs)

                        $isAppointmentsActive = request()->routeIs('admin.appointments.index');
                        $isApprovalActive = request()->routeIs('admin.appointments.approval_index');
                        $isUrgentActive = request()->routeIs('admin.appointments.urgent');

                        $activeMain = $isAppointmentsActive || $isApprovalActive || $isUrgentActive;

                        $urgentCount = \App\Models\DoctorPatientAppointment::where('is_urgent', true)
                            ->where('payment_status', 'pending')
                            ->count();
                    ?>

                    <ul class="sub-menu accordion-item"
                        aria-expanded="{{ $activeMain ? 'true' : 'false' }}"
                        {{ $activeMain ? 'style=display:block;' : '' }}>

                        <li class="{{ $isAppointmentsActive ? 'active' : '' }}">
                            <a href="{{ route('admin.appointments.index') }}" data-key="t-inbox">
                                Appointments
                            </a>
                        </li>

                        <li class="{{ $isUrgentActive ? 'active' : '' }}">
                            <a href="{{ route('admin.appointments.urgent') }}">
                                🚨 Urgent Appointments

                                @if($urgentCount > 0)
                                    <span class="badge bg-danger float-end">{{ $urgentCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="{{ $isApprovalActive ? 'active' : '' }}">
                            <a href="{{ route('admin.appointments.approval_index') }}" data-key="t-inbox">
                                Document Requests
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

                @if(get_user_permission('appoitments','r'))
                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-check-shield icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Activity Logs</span>
                    </a>

                    <?php
                        // ✅ FIX: Use route-based detection instead of regex (no overlap bugs)

                        $isAppointmentsActive = request()->routeIs('admin.activity.logs');

                        $activeMain = $isAppointmentsActive;

                       
                    ?>

                    <ul class="sub-menu accordion-item"
                        aria-expanded="{{ $activeMain ? 'true' : 'false' }}"
                        {{ $activeMain ? 'style=display:block;' : '' }}>

                        <li class="{{ $userType == 6 ? 'active' : '' }}">
    <a href="{{ route('admin.activity.logs', ['user_type' => 6]) }}">
        Doctor
    </a>
                    </li> 

                    <li class="{{ $userType == 5 ? 'active' : '' }}">
                        <a href="{{ route('admin.activity.logs', ['user_type' => 5]) }}">
                            Hospital
                        </a>
                    </li>  

                    <li class="{{ $userType == 8 ? 'active' : '' }}">
                        <a href="{{ route('admin.activity.logs', ['user_type' => 8]) }}">
                            Clinic
                        </a>
                    </li> 

                    <li class="{{ $userType == 4 ? 'active' : '' }}">
                        <a href="{{ route('admin.activity.logs', ['user_type' => 4]) }}">
                            Service Center
                        </a>
                    </li>

                    <li class="{{ $userType == 3 ? 'active' : '' }}">
                        <a href="{{ route('admin.activity.logs', ['user_type' => 3]) }}">
                            Agent
                        </a>
                    </li>

                        

                        

                    </ul>
                </li>
                @endif

                <li class="{{ request()->routeIs('admin.bulk_notifications.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bulk_notifications.index') }}">
                        <i class="bx bx-broadcast icon nav-icon"></i>
                        <span class="menu-item" data-key="t-bulk-notifications">Bulk Notifications</span>
                    </a>
                </li>

               @if(get_user_permission('earnings','r'))
                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-dollar-circle icon nav-icon"></i>
                        <span class="menu-item" data-key="t-earnings">Earnings & Commission</span>
                    </a>

                    <?php
                        // Route-based detection for earnings pages
                        $isEarningsIndexActive = request()->routeIs('admin.earnings.index');
                        $isWithdrawalsActive = request()->routeIs('admin.earnings.withdrawals');
                        
                        $activeMain = $isEarningsIndexActive || $isWithdrawalsActive;
                        
                        // Pending commission count
                        $pendingCommissionCount = \App\Models\DoctorPatientAppointment::where('booking_status', BOOKING_STATUS_COMPLETED)
                            ->where('payment_status', 'paid')
                            ->where(function($q) {
                                $q->where('commission_status', 'pending')
                                ->orWhereNull('commission_status');
                            })
                            ->count();
                        
                        // Pending withdrawal requests count
                        $pendingWithdrawalsCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                    ?>

                    <ul class="sub-menu accordion-item"
                        aria-expanded="{{ $activeMain ? 'true' : 'false' }}"
                        {{ $activeMain ? 'style=display:block;' : '' }}>

                        <li class="{{ $isEarningsIndexActive ? 'active' : '' }}">
                            <a href="{{ route('admin.earnings.index') }}" data-key="t-commissions">
                                Commission Management
                                @if($pendingCommissionCount > 0)
                                    <span class="badge bg-warning float-end">{{ $pendingCommissionCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="{{ $isWithdrawalsActive ? 'active' : '' }}">
                            <a href="{{ route('admin.earnings.withdrawals') }}" data-key="t-withdrawals">
                                Withdrawal Requests
                                @if($pendingWithdrawalsCount > 0)
                                    <span class="badge bg-danger float-end">{{ $pendingWithdrawalsCount }}</span>
                                @endif
                            </a>
                        </li>

                    </ul>
                </li>
                @endif
                <!-- <li class="menu-title" data-key="t-administration">Administration </li> -->




                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-user-circle icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">User</span>
                    </a>
                    <?php $patternsMain = array('admin\/hospitals', 'admin\/clinics', 'admin\/doctors', 'admin\/callcenter', 'admin\/agents', 'admin\/patients');
                        $patterns_flattened_main = implode('|', $patternsMain);
                        $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                    ?>
                    <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>


                        @if(get_user_permission('hospitals','r'))
                        <?php $patterns = array('admin\/hospitals');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.hospitals.index')}}" data-key="t-read-email">Hospitals</a></li>
                        @endif
                        @if(get_user_permission('clinics','r'))
                        <?php $patterns = array('admin\/clinics');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.clinics.index')}}" data-key="t-read-email">Clinics</a></li>
                        @endif
                        @if(get_user_permission('doctors','r'))
                        <?php $patterns = array('admin\/doctors');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.doctors.index')}}" data-key="t-read-email">Doctors</a></li>
                        @endif
                        @if(get_user_permission('call_centers','r'))
                        <?php $patterns = array('admin\/callcenter');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.callcenter.index')}}" data-key="t-inbox">Service centers</a></li>
                        @endif

                        
                        @if(get_user_permission('agents','r'))
                        <?php $patterns = array('admin\/agents');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class=" {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.agents.index')}}" data-key="t-read-email">Agents</a></li>
                        @endif
                        @if(get_user_permission('patients','r'))
                        <?php $patterns = array('admin\/patients');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.patients.index')}}" data-key="t-read-email">Patients</a></li>
                        @endif
                    </ul>
                </li>


                {{-- Add this in your sidebar menu where appropriate --}}
             
                



                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-layout icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Masters</span>
                    </a>
                    <?php $patternsMain = array('admin\/frequencies','admin\/directions','admin\/brands','admin\/special_intrests','admin\/languages','admin\/medical_condition','admin\/insurence_policy','admin\/sub_insurence_policy','admin\/specialties','admin\/countries','admin\/emirates','admin\/areas','admin\/settings','admin\/qualifications','admin\/departments', 'admin\/banners');
                        $patterns_flattened_main = implode('|', $patternsMain);
                        $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                    ?>
                    <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                        @if(get_user_permission('services','r'))
                        <!--
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.services.list')}}" data-key="t-inbox">Services</a></li> -->
                        @endif
                        @if(get_user_permission('qualifications','r'))
                        <?php $patterns = array('admin\/qualifications');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.qualifications.list')}}" data-key="t-inbox">Qualifications</a></li>
                        @endif
                        @if(get_user_permission('departments','r'))
                        <?php $patterns = array('admin\/departments');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.departments.list')}}" data-key="t-inbox">Departments</a></li>
                        @endif
                        <!--
                        @if(get_user_permission('licencetype','r'))
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.licencetype.list')}}" data-key="t-inbox">Licence Types</a></li>
                        @endif
                        -->
                        @if(get_user_permission('special_intrests','r'))
                        <?php $patterns = array('admin\/special_intrests');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.special_intrests.list')}}" data-key="t-inbox">Special Intrests</a></li>
                        @endif
                        @if(get_user_permission('languages','r'))
                        <?php $patterns = array('admin\/languages');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.languages.list')}}" data-key="t-inbox">Languages</a></li>
                        @endif
                      {{--  @if(get_user_permission('medical_condition','r'))
                        <?php $patterns = array('admin\/medical_condition');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.medical_condition.list')}}" data-key="t-inbox">Medical Condition</a></li>
                        @endif --}}
                        @if(get_user_permission('insurence_policy','r'))
                        <?php $patterns = array('admin\/insurence_policy');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.insurence_policy.list')}}" data-key="t-inbox">Insurance Policy</a></li>
                        @endif
                        @if(get_user_permission('sub_insurence_policy','r'))
                        <?php $patterns = array('admin\/sub_insurence_policy');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.sub_insurence_policy.list')}}" data-key="t-inbox">Sub Insurance Policy</a></li>
                        @endif
                        @if(get_user_permission('specialties','r'))
                        <?php $patterns = array('admin\/specialties');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.specialties.index')}}" data-key="t-read-email">Specialties</a></li>
                        @endif
                        @if(get_user_permission('countries','r'))
    <?php 
        $patterns = array('admin\/countries');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{route('admin.countries.index')}}" data-key="t-read-email">Countries</a>
    </li>

    <?php 
        $patterns = array('admin\/country-of-origins');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{route('admin.country-of-origin.index')}}" data-key="t-read-email">Country Origins</a>
    </li>

    <?php 
        $patterns = array('admin\/directions');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{url('admin/directions/list')}}" data-key="t-read-email"> Directions</a>
    </li>

    <?php 
        $patterns = array('admin\/durations');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{url('admin/durations/list')}}" data-key="t-read-email"> Durations</a>
    </li>

    <?php 
        $patterns = array('admin\/dosage');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{url('admin/dosage/list')}}" data-key="t-read-email"> Dosage</a>
    </li>

    <?php 
    $patterns = array('admin\/frequencies');
    $patterns_flattened = implode('|', $patterns);
?>
<li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
    <a href="{{url('admin/frequencies/list')}}" data-key="t-read-email"> Frequencies</a>
</li>
 
    <?php 
        $patterns = array('admin\/medicin_categories');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{url('admin/medicin_categories/list')}}" data-key="t-read-email">Medicine Category</a>
    </li>

      <?php 
        $patterns = array('admin\/product-tags');
        $patterns_flattened = implode('|', $patterns);
    ?>
    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
        <a href="{{url('admin/product-tags/list')}}" data-key="t-read-email">Product Tags</a>
    </li>
    

@endif

                        
                        @if(get_user_permission('emirates','r'))
                        <?php $patterns = array('admin\/emirates');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.emirates.index')}}" data-key="t-read-email">City</a></li>
                        @endif
                        @if(get_user_permission('areas','r'))
                        <?php $patterns = array('admin\/areas');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.areas.index')}}" data-key="t-read-email">Areas</a></li>
                        @endif

                        @if(get_user_permission('banners','r'))
                        <?php $patterns = array('admin\/banners');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.banners.index')}}" data-key="t-read-email">Banners</a></li>
                        @endif

                        @if(get_user_permission('website_services','r'))
                        <?php $patterns = array('admin\/website_services');
                                $patterns_flattened = implode('|', $patterns);
                        ?>
                        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.website_services.list')}}" data-key="t-read-email">Website Services</a></li>
                        @endif



                    </ul>
                </li>
                
                
                @if(get_user_permission('reviews','r'))
                <?php $patternsMain = array('admin\/referrals');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? 'active' : null}}">
                <a href="{{url('admin/referrals/list')}}">
                        <i class="bx bx-note icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Referrals</span>
                    </a>
                </li>
                @endif
                @if(get_user_permission('reviews','r'))
                <?php $patternsMain = array('admin\/reviews');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? 'active' : null}}">
                <a href="{{route('admin.hospitals.reviews')}}">
                        <i class="bx bx-star icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Reviews</span>
                    </a>
                </li>
                @endif
                

                @if(get_user_permission('reviews','r'))
                <?php $patternsMain = array('admin\/medicines');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? 'active' : null}}">
                <a href="{{url('admin/medicines/list')}}">
                        <i class="bx bx-box icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Medicines</span>
                    </a>
                </li>
                @endif

                @if(get_user_permission('coupons','r'))
                <?php 
                    $patternsMain = array('admin\/coupons\/list');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{ $activeMain ? 'active' : null }} d-none">
                    <a href="{{ url('admin/coupons/list') }}">
                        <i class="bx bx-gift icon nav-icon"></i>
                        <span class="menu-item">Coupons</span>
                    </a>
                </li>
                @endif
                @if(get_user_permission('coupons','r'))
                <?php 
                    $patternsMain = array('admin\/coupons\/report');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{ $activeMain ? 'active' : null }} d-none" >
                    <a href="{{ url('admin/coupons/report') }}">
                        <i class="bx bx-bar-chart icon nav-icon"></i>
                        <span class="menu-item">Coupons Report</span>
                    </a>
                </li>
                @endif

                 @if(get_user_permission('reviews','r'))
                <?php $patternsMain = array('admin\/orders');
                    $patterns_flattened_main = implode('|', $patternsMain);
                    $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                ?>
                <li class="{{preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? 'active' : null}} d-none">
                <a href="{{url('admin/orders')}}">
                        <i class="bx bx-box icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Medicines Orders</span>
                    </a>
                </li>
                @endif

                {{-- Add this in your sidebar menu where appropriate --}}
                {{-- Add this in your sidebar menu where appropriate --}}
                @php
                    $currentRoute = Route::currentRouteName();
                @endphp

                <li class="menu-title" data-key="t-reports">Reports</li>

                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-chart icon nav-icon"></i>
                        <span class="menu-item" data-key="t-reports">Reports</span>
                    </a>
                    <ul class="sub-menu accordion-item" aria-expanded="{{ str_contains($currentRoute, 'admin.reports') ? 'true' : 'false' }}" {{ str_contains($currentRoute, 'admin.reports') ? 'style=display:block;' : '' }}>
                        <!-- <li class="{{ $currentRoute == 'admin.reports.index' ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.index') }}" data-key="t-dashboard">Dashboard</a>
                        </li> -->
                        <li class="{{ str_contains($currentRoute, 'admin.reports.patients') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.patients') }}" data-key="t-patients">Patients Report</a>
                        </li>
                        <li class="{{ str_contains($currentRoute, 'admin.reports.appointments') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.appointments') }}" data-key="t-appointments">Appointments Report</a>
                        </li>
                        <li class="{{ str_contains($currentRoute, 'admin.reports.doctors') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.doctors') }}" data-key="t-doctors">Doctors Report</a>
                        </li>
                        <li class="{{ str_contains($currentRoute, 'admin.reports.hospitals') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.hospitals') }}" data-key="t-hospitals">Hospitals / Clinics Report</a>
                        </li>
                        <!-- <li class="{{ str_contains($currentRoute, 'admin.reports.financial') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.financial') }}" data-key="t-financial">Financial Report</a>
                        </li> -->
                    </ul>
                </li>

              

                
                @if(get_user_permission('admin_users','r') || get_user_permission('user_roles','r'))
                    <li class="menu-title d-none" data-key="t-administration ">Administration </li>

                    <li class="accordion-trigger ">
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="bx bx-user-pin icon nav-icon"></i>
                            <span class="menu-item" data-key="t-email">Staff Management</span>
                        </a>
                        <?php $patternsMain = array('admin\/user_roles', 'admin\/admin_users');
                            $patterns_flattened_main = implode('|', $patternsMain);
                            $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                        ?>
                        <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                            @if(get_user_permission('user_roles','r'))
                            <?php $patterns = array('admin\/user_roles');
                                    $patterns_flattened = implode('|', $patterns);
                            ?>
                            <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.user_roles.list')}}" data-key="t-inbox">User Role</a></li>
                            @endif
                            @if(get_user_permission('admin_users','r'))
                            <?php $patterns = array('admin\/admin_users');
                                    $patterns_flattened = implode('|', $patterns);
                            ?>
                            <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.admin_users.index')}}" data-key="t-read-email">Admin Users</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(get_user_permission('settings','r'))
                <li class="menu-title" data-key="t-pages">Pages</li>

                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-file icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">CMS Pages</span>
                    </a>

                    <?php $patternsMain = array('admin\/cms_pages',  'admin\/faq-for-hospital', 'admin\/faq-for-doctor', 'admin\/contact_details', 'admin\/settings');
                        $patterns_flattened_main = implode('|', $patternsMain);
                        $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                    ?>
                    <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <?php
                        $subMenuItems = [
                            ['pattern' => 'admin\/cms_pages\?type=1', 'url' => url('admin/cms_pages?type=1'), 'text' => 'Hospital'],
                            ['pattern' => 'admin\/cms_pages\?type=2', 'url' => url('admin/cms_pages?type=2'), 'text' => 'Website'],
                            ['pattern' => 'admin\/cms_pages\?type=3', 'url' => url('admin/cms_pages?type=3'), 'text' => 'Clinic'],
                              ['pattern' => 'admin\/cms_pages\?type=6', 'url' => url('admin/cms_pages?type=6'), 'text' => 'Cancellation Policy'],
                            ['pattern' => 'admin\/contact_details', 'url' => route('admin.contact_details'), 'text' => 'Contact Details'],
                            ['pattern' => 'admin\/settings', 'url' => route('admin.settings'), 'text' => 'Settings'],
                            
                        ];

                        foreach ($subMenuItems as $item) {
                            $isActiveSubItem = isActiveCMS([$item['pattern']], $CurrentUrl);
                            echo '<li class="' . ($isActiveSubItem ? 'active' : '') . '"><a href="' . $item['url'] . '" data-key="t-read-email">' . $item['text'] . '</a></li>';
                        }
                    ?>
                     
                    
                    </ul>

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=2') }}" data-key="t-read-email">App/Website</a></li>-->
                    <!--</ul>-->

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=3') }}" data-key="t-read-email">Clinic</a></li>-->
                    <!--</ul>-->

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=4') }}" data-key="t-read-email">Doctor</a></li>-->
                    <!--</ul>-->

                   


                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.contact_details')}}" data-key="t-read-email">Contact Details</a></li>-->
                    <!--</ul>-->




                </li>


                <li class="accordion-trigger">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-file icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Health Education</span>
                    </a>

                    <?php $patternsMain = array('admin\/videos', 'admin\/faq', 'admin\/wellness_tips');
                        $patterns_flattened_main = implode('|', $patternsMain);
                        $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;
                    ?>
                    <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    
                     
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/faq') }}" data-key="t-read-email">FAQ</a></li>
                      
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/wellness_tips') }}" data-key="t-read-email">Wellness Tips</a></li>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/videos') }}" data-key="t-read-email">Videos</a></li>
                    
                    </ul>

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=2') }}" data-key="t-read-email">App/Website</a></li>-->
                    <!--</ul>-->

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=3') }}" data-key="t-read-email">Clinic</a></li>-->
                    <!--</ul>-->

                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{ url('admin/cms_pages?type=4') }}" data-key="t-read-email">Doctor</a></li>-->
                    <!--</ul>-->

                   


                    <!--<ul class="sub-menu" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>
                    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.contact_details')}}" data-key="t-read-email">Contact Details</a></li>-->
                    <!--</ul>-->




                </li>

                <!--@if(get_user_permission('homepage_management','u'))-->
                <!--<li class="accordion-trigger">-->
                <!--    <a href="javascript: void(0);" class="has-arrow">-->
                <!--        <i class="bx bx-globe icon nav-icon"></i>-->
                <!--        <span class="menu-item" data-key="t-email">Homepage Management</span>-->
                <!--    </a>-->
                <!--    <//?php $patternsMain = array('admin\/homepage-management', 'admin\/hp-slides', 'admin\/hp-partner-logos');-->
                <!--        $patterns_flattened_main = implode('|', $patternsMain);-->
                <!--        $activeMain = preg_match('/'.$patterns_flattened_main.'/', $CurrentUrl) ? true : false;-->
                <!--    ?>-->
                <!--    <ul class="sub-menu accordion-item" aria-expanded="{{$activeMain ? 'true' : 'false'}}" {{ $activeMain ? 'style=display:block;' : '' }}>-->
                <!--    <//?php $patterns = array('admin\/homepage-management');-->
                <!--                $patterns_flattened = implode('|', $patterns);-->
                <!--        ?>-->
                <!--    <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.homepage-management')}}" data-key="t-inbox">Manage Homepage</a></li>-->
                <!--        <//?php $patterns = array('admin\/hp-slides');-->
                <!--                $patterns_flattened = implode('|', $patterns);-->
                <!--        ?>-->
                <!--        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.hp-slides.index')}}" data-key="t-inbox">Slides</a></li>-->
                <!--        <//?php $patterns = array('admin\/hp-partner-logos');-->
                <!--                $patterns_flattened = implode('|', $patterns);-->
                <!--        ?>-->
                <!--        <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}"><a href="{{route('admin.hp-partner-logos.index')}}" data-key="t-read-email">Partner Logos</a></li>-->
                <!--    </ul>-->
                <!--</li>-->
                <!--@endif-->

                @if(get_user_permission('contact_us_entries','u'))
                <?php $patterns = array('admin\/contact-us-entries');
                    $patterns_flattened = implode('|', $patterns);
                ?>
                <li class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
                    <a href="{{route('admin.contact-us-entries.index')}}">
                        <i class="bx bx-support icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Help Desk</span>

                    </a>
                </li>
                @endif

                @endif

                @if(get_user_permission('bulkupload','r'))
                <li class="menu-title d-none" data-key="t-pages">Bulk Upload</li>

                <?php $patterns = array('admin\/bulk_upload');
                    $patterns_flattened = implode('|', $patterns);
                ?>
                <li class="d-none {{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}}">
                <a href="{{route('admin.bulk_upload')}}">
                    <i class="bx bx-cloud-upload icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Bulk Upload</span>
                    </a>
                </li>
                @endif




            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<style>
    #sidebar-menu .has-arrow:after {
        content: "\f0415";
        font-family: "Material Design Icons";
    }
    #sidebar-menu .accordion-trigger.active .has-arrow:after {
        content: "\f0374";
        font-family: "Material Design Icons";
    }
    .accordion-item{
        display: none;
    }
</style>
<script>
    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
        trigger.addEventListener('click', function () {
            // Close all other sub-menus
            document.querySelectorAll('.accordion-trigger').forEach(item => {
                if (item !== trigger) {
                    item.classList.remove('active');
                    item.querySelector('.sub-menu').style.display = 'none';
                }
            });
            // Toggle the clicked sub-menu
            this.classList.toggle('active');
            let subMenu = this.querySelector('.sub-menu');
            let isExpanded = subMenu.style.display === 'block';
            subMenu.style.display = isExpanded ? 'none' : 'block';
        });
    });
</script>
