@extends('front.template.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="package-grid-page pt-100 mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="package-sidebar-area">
                    <div class="sidebar-wrapper">
                        <div class="title-area">
                            <h5>Filter</h5>
                            <a href="{{route('doctor_list')}}" id="clear-filters">Clear All</a>
                        </div>
                        <div class="single-widgets">
                            <div class="filter-wrapper">
                                <div class="container">
                                    <div class="filter-input-wrap p-0">
                                        <form method="GET" action="{{route('doctor_list')}}" id="search-form">
                                            <div>
                                                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by') }}">
                                                <input type="hidden" name="current_latitude" id="latitude" value="{{request('current_latitude')}}">
                                                <input type="hidden" name="current_longitude" id="longitude" value="{{request('current_longitude')}}">
                                                <div class="custom-accordion">
                                                    <h5 class="font-size-14 mb-0">
                                                        <a href="#categories-collapse" class="text-body d-block" data-bs-toggle="collapse">
                                                            Advance Filter <i class="mdi mdi-chevron-up float-end accor-down-icon"></i>
                                                        </a>
                                                    </h5>
        
                                                    <div class="collapse show mt-4" id="categories-collapse">
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="specialty_id" class="form-select select2-single w-100" data-placeholder="Doctors Specialty" id="specialty">
                                                                <option value="">Doctors Specialty</option>
                                                                @foreach($specialties as $id => $value)
                                                                <option value="{{$id}}" {{ request('specialty_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon specialty-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="insurance_id" class="form-select select2-single" data-placeholder="My Insurance Policy" id="insurance-policy">
                                                                <option value="">My Insurance Policy</option>
                                                                @foreach($insurencePolicies as $id => $value)
                                                                <option value="{{$id}}" {{ request('insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon insu-policy-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="sub_insurance_id" class="form-select select2-single" data-placeholder="Sub Insurance" id="sub-insurance-policy">
                                                                <option value="">Sub Insurance</option>
                                                                @foreach($subInsurencePolicies as $id => $value)
                                                                <option value="{{$id}}" {{ request('sub_insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon sub-policy-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="medical_condition_id" class="form-select select2-single" data-placeholder="My Medical Condition" id="interest">
                                                                <option value="">My Medical Condition</option>
                                                                @foreach($medicalConditions as $id => $value)
                                                                <option value="{{$id}}" {{ request('medical_condition_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon medical-condition-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="language_id" class="form-select select2-single" data-placeholder="Doctor’s Language" id="language">
                                                                <option value="">Doctor’s Language</option>
                                                                @foreach($languages as $id => $value)
                                                                <option value="{{$id}}" {{ request('language_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon doc-language-icn" style="margin-top: 2px;"></i>
                                                        </div>

        
                                                        <div class="position-relative input-custom-icon mb-3">
                                                            <input type="text" name="date" class="form-control flatpicker-input" id="" placeholder="Search by Date" value="{{ request('date') }}" />
                                                            <span class="custom-icon calendar-doc-icn"></span>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="cuntry_of_origin_id" class="form-select select2-single" data-placeholder="Doctor’s Country of Origin" id="countryOrigin">
                                                                <option value=""> Doctor’s Country of Origin</option>
                                                                @foreach($countries as $id => $value)
                                                                <option value="{{$id}}" {{ request('cuntry_of_origin_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon counrty-orgin-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="gender_id" class="form-select select2-single"  data-placeholder="Doctor’s Gender" id="gender">
                                                                <option value="">Doctor’s Gender</option>
                                                                @foreach($genders as $id => $value)
                                                                <option value="{{$id}}" {{ request('gender_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon doc-gender-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="emirates_id" class="form-select select2-single" data-placeholder="Emirates" id="emirates">
                                                                <option value="">Cities</option>
                                                                @foreach($emirates as $id => $value)
                                                                <option value="{{$id}}" {{ request('emirates_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon doc-location-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="area_id" class="form-select select2-single" data-placeholder="Area" id="area">
                                                                <option value="">Area</option>
                                                                @foreach($areas as $id => $value)
                                                                <option value="{{$id}}" {{ request('area_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon doc-area-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3 d-none">
                                                            <select class="form-select select2-single" data-placeholder="Direct Call for Appointment">
                                                                <option></option>
                                                                <option value="1" {{ request('direct_call_for_appointment') == '1' ? 'selected' : '' }}>Yes</option>
                                                                <option value="2" {{ request('direct_call_for_appointment') == '2' ? 'selected' : '' }}>No</option>
                                                            </select>
                                                            <i class="custom-icon direct-call-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3 d-none">
                                                            <select class="form-select select2-single" data-placeholder="Ready to consult instantly">
                                                                <option></option>
                                                                <option value="1" {{ request('ready_to_consult_instantly') == '1' ? 'selected' : '' }}>Yes</option>
                                                                <option value="2" {{ request('ready_to_consult_instantly') == '2' ? 'selected' : '' }}>No</option>
                                                            </select>
                                                            <i class="custom-icon consult-instant-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <select name="hospital_id" class="form-select select2-single" data-placeholder="Hospital/ Clinic / Dental Care" id="hospital">
                                                                <option value="">Hospital/ Clinic / Dental Care</option>
                                                                @foreach($hospitals as $id => $value)
                                                                <option value="{{$id}}" {{ request('hospital_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="custom-icon hospital-icon-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="position-relative select-custom-icon mb-3">
                                                            <input type="text" name="doctor_name" class="form-select" placeholder="Doctor’s Name" value="{{ request('doctor_name') }}" id="doctor">
                                                            <i class="custom-icon doctor-name-icn" style="margin-top: 2px;"></i>
                                                        </div>
        
                                                        <div class="form-check py-3">
                                                            <input type="checkbox" name="dirent_call_for_appointment" class="form-check-input" id="auth-remember-chec1" {{ request('dirent_call_for_appointment') ? 'checked' : '' }} value="1">
                                                            <label class="form-check-label ms-2" for="auth-remember-chec1"> Direct Calling Number for Appointment</label>
                                                        </div>
                                                        <div class="form-check py-3">
                                                            <input type="checkbox" name="ready_to_consult_instantly" class="form-check-input" id="auth-remember-check2" {{ request('ready_to_consult_instantly') ? 'checked' : '' }} value="1">
                                                            <label class="form-check-label ms-2" for="auth-remember-check2"> Ready for Consult Instantly</label>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div class="p-4 border-top">
                                                    <div>
                                                        <h5 class="font-size-14 mb-3">Search by Distance (500 Km)</h5>
                                                        <input type="range" name="distance" class="form-range" min="0" max="500" id="customRange3" value="{{ request('distance', 0) }}">
                                                        <!-- <div class="d-flex justify-content-between">
                                                            <div class="text-muted">1 km</div>
                                                            <div class="text-muted">100 km</div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                
        
                                                <div class="p-4">
                                                    <button class="btn btn-primary w-100">Search</button>
                                                </div>
                                            </div>
                                            </form>
                                        <!-- <p>Can’t find what you’re looking for? create your <a href="#">Custom Itinerary</a></p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="package-grid-top-area">
                    <span><strong>{{ count($doctors) }}</strong> Search Results</span>
                    <div class="selector-and-list-grid-area">
                        <div class="filter-btn d-lg-none d-flex">
                            <svg width="18" height="18" viewBox="0 0 18 18"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_456_25)">
                                    <path
                                        d="M0.5625 3.17317H9.12674C9.38486 4.34806 10.4341 5.2301 11.6853 5.2301C12.9366 5.2301 13.9858 4.3481 14.2439 3.17317H17.4375C17.7481 3.17317 18 2.92131 18 2.61067C18 2.30003 17.7481 2.04817 17.4375 2.04817H14.2437C13.9851 0.873885 12.9344 -0.00871277 11.6853 -0.00871277C10.4356 -0.00871277 9.38545 0.873744 9.12695 2.04817H0.5625C0.251859 2.04817 0 2.30003 0 2.61067C0 2.92131 0.251859 3.17317 0.5625 3.17317ZM10.191 2.61215L10.191 2.6061C10.1935 1.78461 10.8638 1.11632 11.6853 1.11632C12.5057 1.11632 13.1761 1.78369 13.1796 2.6048L13.1797 2.61306C13.1784 3.43597 12.5086 4.10513 11.6853 4.10513C10.8625 4.10513 10.1928 3.43663 10.191 2.61422L10.191 2.61215ZM17.4375 14.8268H14.2437C13.985 13.6525 12.9344 12.7699 11.6853 12.7699C10.4356 12.7699 9.38545 13.6524 9.12695 14.8268H0.5625C0.251859 14.8268 0 15.0786 0 15.3893C0 15.7 0.251859 15.9518 0.5625 15.9518H9.12674C9.38486 17.1267 10.4341 18.0087 11.6853 18.0087C12.9366 18.0087 13.9858 17.1267 14.2439 15.9518H17.4375C17.7481 15.9518 18 15.7 18 15.3893C18 15.0786 17.7481 14.8268 17.4375 14.8268ZM11.6853 16.8837C10.8625 16.8837 10.1928 16.2152 10.191 15.3928L10.191 15.3908L10.191 15.3847C10.1935 14.5632 10.8638 13.8949 11.6853 13.8949C12.5057 13.8949 13.1761 14.5623 13.1796 15.3834L13.1797 15.3916C13.1785 16.2146 12.5086 16.8837 11.6853 16.8837ZM17.4375 8.43751H8.87326C8.61514 7.26262 7.56594 6.38062 6.31466 6.38062C5.06338 6.38062 4.01418 7.26262 3.75606 8.43751H0.5625C0.251859 8.43751 0 8.68936 0 9.00001C0 9.31068 0.251859 9.56251 0.5625 9.56251H3.75634C4.01498 10.7368 5.06559 11.6194 6.31466 11.6194C7.56439 11.6194 8.61455 10.7369 8.87305 9.56251H17.4375C17.7481 9.56251 18 9.31068 18 9.00001C18 8.68936 17.7481 8.43751 17.4375 8.43751ZM7.80901 8.99853L7.80898 9.00458C7.80652 9.82607 7.13619 10.4944 6.31466 10.4944C5.49429 10.4944 4.82393 9.82699 4.82038 9.00591L4.82027 8.99769C4.8215 8.17468 5.49141 7.50562 6.31466 7.50562C7.13753 7.50562 7.80718 8.17408 7.80905 8.99653L7.80901 8.99853Z">
                                    </path>
                                </g>
                            </svg>
                            <span>Filters</span>
                        </div>
                        <div class="selector-area">
                            <span>Sort By:</span>
                            <select id="sortSelector">
                                <option value="" {{ request('sort_by') == '' ? 'selected' : '' }}>Default</option>
                                <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Popular</option>
                                <option value="nearest" {{ request('sort_by') == 'nearest' ? 'selected' : '' }}>Nearby</option>
                            </select>
                        </div>
                        <ul class="grid-view d-md-flex d-none">
                            <li class="column-2 active">
                                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4 11C5.65685 11 7 12.3431 7 14C7 15.6569 5.65685 17 4 17C2.34315 17 1 15.6569 1 14C1 12.3431 2.34315 11 4 11ZM14 11C15.6569 11 17 12.3431 17 14C17 15.6569 15.6569 17 14 17C12.3431 17 11 15.6569 11 14C11 12.3431 12.3431 11 14 11ZM4 1C5.65685 1 7 2.34315 7 4C7 5.65685 5.65685 7 4 7C2.34315 7 1 5.65685 1 4C1 2.34315 2.34315 1 4 1ZM14 1C15.6569 1 17 2.34315 17 4C17 5.65685 15.6569 7 14 7C12.3431 7 11 5.65685 11 4C11 2.34315 12.3431 1 14 1Z"/>
                                </svg>
                            </li>
                            <li class="column-1">
                                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.25 9.95007H0.75C0.336 9.95007 0 9.61407 0 9.20007C0 8.78607 0.336 8.45007 0.75 8.45007H17.25C17.664 8.45007 18 8.78607 18 9.20007C18 9.61407 17.664 9.95007 17.25 9.95007ZM17.25 4.20001H0.75C0.336 4.20001 0 3.86401 0 3.45001C0 3.03601 0.336 2.70001 0.75 2.70001H17.25C17.664 2.70001 18 3.03601 18 3.45001C18 3.86401 17.664 4.20001 17.25 4.20001ZM17.25 15.6999H0.75C0.336 15.6999 0 15.3639 0 14.9499C0 14.5359 0.336 14.1999 0.75 14.1999H17.25C17.664 14.1999 18 14.5359 18 14.9499C18 15.3639 17.664 15.6999 17.25 15.6999Z"/>
                                </svg>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="list-grid-product-wrap">
                    
                    <!--No Search Result Found!-->
                    <!--<div class="empty-state text-center py-4">-->
                    <!--    <img class=empty-img alt="Find Doctor" src="{{ asset('assets/img/search-empty.png') }}">-->
                    <!--    <p class="txt fw-bold mt-2 text-primary">No Result Found!</p>-->
                    <!--</div>-->
                    
                                
                    <div class="row gy-md-3 gy-2">

                        
                        @foreach($doctors as $doctor)
                        <div class="col-md-6 item wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
                            <div class="package-card">
                                <div class="package-img-wrap">
                                    <a href="{{ url('doctor-profile', $doctor->id) }}"class="package-img">
                                        <img src="{{ $doctor->user->user_img_url ?? null}}" alt="">
                                    </a>
                                    @if($doctor->user->video_conssultant==1)
                                    <div class="avil-type">
                                        <span class="item-type videoconsult">
                                            <i class='bx bxs-video'></i>
                                        </span>
                                       
                                    </div>
                                    @endif
                                    <!-- <div class="batch">
                                        <span>Hot Sale!</span>
                                    </div> -->
                                </div>
                                <div class="package-content">
                                    <h5><a href="{{ url('doctor-profile', $doctor->id) }}">Dr. {{$doctor->user->name}}</a></h5>
                                    <div class="location-and-time mb-1">
                                        <div class="location">
                                            <a href="{{ url('doctor-profile', $doctor->id) }}">{{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}</a>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="btn-and-price-area">
                                        <a href="{{ url('doctor-profile', $doctor->id) }}"class="primary-btn1">
                                            <span>
                                                Book Appointment
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"/>
                                                </svg>
                                            </span>
                                            <span>
                                                Book Appointment
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"/>
                                                </svg>
                                            </span>
                                        </a>
                                        <div class="price-area">
                                            <h6>away</h6>
                                            <span>{{ round($doctor->hospital->location[0]->distance ?? 0.0)}} km</span>
                                        </div>
                                    </div>
                                    

                                    <div class="location-and-time mt-3 mb-1">
                                        <div class="location">
                                            <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.83615 0C3.77766 0 1.28891 2.48879 1.28891 5.54892C1.28891 7.93837 4.6241 11.8351 6.05811 13.3994C6.25669 13.6175 6.54154 13.7411 6.83615 13.7411C7.13076 13.7411 7.41561 13.6175 7.6142 13.3994C9.04821 11.8351 12.3834 7.93833 12.3834 5.54892C12.3834 2.48879 9.89464 0 6.83615 0ZM7.31469 13.1243C7.18936 13.2594 7.02008 13.3342 6.83615 13.3342C6.65222 13.3342 6.48295 13.2594 6.35761 13.1243C4.95614 11.5959 1.69584 7.79515 1.69584 5.54896C1.69584 2.7134 4.00067 0.406933 6.83615 0.406933C9.67164 0.406933 11.9765 2.7134 11.9765 5.54896C11.9765 7.79515 8.71617 11.5959 7.31469 13.1243Z"/>
                                                <path
                                                    d="M6.83618 8.54554C8.4624 8.54554 9.7807 7.22723 9.7807 5.60102C9.7807 3.9748 8.4624 2.65649 6.83618 2.65649C5.20997 2.65649 3.89166 3.9748 3.89166 5.60102C3.89166 7.22723 5.20997 8.54554 6.83618 8.54554Z"/>
                                            </svg>
                                            <a href="{{ url('doctor-profile', $doctor->id) }}">{{ $doctor->hospital->name_en ?? null}}</a>
                                        </div>
                                    </div>
                                    <svg class="divider" height="6" viewBox="0 0 374 6" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 2.5L0 0.113249V5.88675L5 3.5V2.5ZM369 3.5L374 5.88675V0.113249L369 2.5V3.5ZM4.5 3.5H369.5V2.5H4.5V3.5Z"/>
                                    </svg>
                                    <div class="bottom-area">
                                        <ul>
                                            <li>
                                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M8 0C3.58853 0 0 3.58853 0 8C0 12.4115 3.58853 16 8 16C12.4115 16 16 12.4108 16 8C16 3.58916 12.4115 0 8 0ZM8 14.7607C4.27266 14.7607 1.23934 11.728 1.23934 8C1.23934 4.27203 4.27266 1.23934 8 1.23934C11.7273 1.23934 14.7607 4.27203 14.7607 8C14.7607 11.728 11.728 14.7607 8 14.7607Z"/>
                                                        <path
                                                            d="M11.0984 7.32445H8.6197V4.84576C8.6197 4.5037 8.3427 4.22607 8.00001 4.22607C7.65733 4.22607 7.38033 4.5037 7.38033 4.84576V7.32445H4.90164C4.55895 7.32445 4.28195 7.60207 4.28195 7.94414C4.28195 8.2862 4.55895 8.56382 4.90164 8.56382H7.38033V11.0425C7.38033 11.3846 7.65733 11.6622 8.00001 11.6622C8.3427 11.6622 8.6197 11.3846 8.6197 11.0425V8.56382H11.0984C11.4411 8.56382 11.7181 8.2862 11.7181 7.94414C11.7181 7.60207 11.4411 7.32445 11.0984 7.32445Z"/>
                                                    </g>
                                                </svg>
                                                Experience
                                                <div class="info">
                                                    <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                                        <g>
                                                            <path
                                                                d="M6 0.375C4.88748 0.375 3.79995 0.704901 2.87492 1.32298C1.94989 1.94107 1.22892 2.81957 0.80318 3.84741C0.377437 4.87524 0.266043 6.00624 0.483085 7.09738C0.700127 8.18853 1.23586 9.19081 2.02253 9.97748C2.8092 10.7641 3.81148 11.2999 4.90262 11.5169C5.99376 11.734 7.12476 11.6226 8.1526 11.1968C9.18043 10.7711 10.0589 10.0501 10.677 9.12508C11.2951 8.20006 11.625 7.11252 11.625 6C11.6245 4.50831 11.0317 3.07786 9.97693 2.02307C8.92215 0.968289 7.49169 0.375497 6 0.375ZM6 9.375C5.85167 9.375 5.70666 9.33101 5.58333 9.2486C5.45999 9.16619 5.36386 9.04906 5.30709 8.91201C5.25033 8.77497 5.23548 8.62417 5.26441 8.47868C5.29335 8.3332 5.36478 8.19956 5.46967 8.09467C5.57456 7.98978 5.7082 7.91835 5.85369 7.88941C5.99917 7.86047 6.14997 7.87533 6.28702 7.93209C6.42406 7.98886 6.54119 8.08499 6.62361 8.20832C6.70602 8.33166 6.75 8.47666 6.75 8.625C6.74941 8.82373 6.6702 9.01415 6.52968 9.15468C6.38915 9.2952 6.19873 9.37441 6 9.375ZM6.85875 3.55875L6.6075 6.56625C6.5944 6.71834 6.52472 6.85999 6.41224 6.9632C6.29976 7.0664 6.15266 7.12367 6 7.12367C5.84735 7.12367 5.70024 7.0664 5.58776 6.9632C5.47528 6.85999 5.40561 6.71834 5.3925 6.56625L5.14125 3.55875C5.13042 3.44226 5.1434 3.32478 5.1794 3.21346C5.2154 3.10214 5.27367 2.99931 5.35067 2.91123C5.42767 2.82314 5.52178 2.75165 5.62729 2.70108C5.73279 2.65052 5.84748 2.62195 5.96437 2.61711C6.08127 2.61227 6.19793 2.63126 6.30725 2.67294C6.41657 2.71461 6.51627 2.77808 6.60029 2.8595C6.6843 2.94092 6.75087 3.03858 6.79595 3.14655C6.84103 3.25451 6.86367 3.37051 6.8625 3.4875C6.86313 3.51131 6.86187 3.53514 6.85875 3.55875Z"/>
                                                        </g>
                                                    </svg>
                                                    <div class="tooltip-text">{{$doctor->year_of_experiance ?? 0}} years experience overall</div>
                                                </div>
                                            </li>
                                            <!--<li>-->
                                            <!--    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">-->
                                            <!--        <g>-->
                                            <!--            <path-->
                                            <!--                d="M8 0C3.58853 0 0 3.58853 0 8C0 12.4115 3.58853 16 8 16C12.4115 16 16 12.4108 16 8C16 3.58916 12.4115 0 8 0ZM8 14.7607C4.27266 14.7607 1.23934 11.728 1.23934 8C1.23934 4.27203 4.27266 1.23934 8 1.23934C11.7273 1.23934 14.7607 4.27203 14.7607 8C14.7607 11.728 11.728 14.7607 8 14.7607Z"/>-->
                                            <!--            <path-->
                                            <!--                d="M11.0984 7.32445H8.6197V4.84576C8.6197 4.5037 8.3427 4.22607 8.00001 4.22607C7.65733 4.22607 7.38033 4.5037 7.38033 4.84576V7.32445H4.90164C4.55895 7.32445 4.28195 7.60207 4.28195 7.94414C4.28195 8.2862 4.55895 8.56382 4.90164 8.56382H7.38033V11.0425C7.38033 11.3846 7.65733 11.6622 8.00001 11.6622C8.3427 11.6622 8.6197 11.3846 8.6197 11.0425V8.56382H11.0984C11.4411 8.56382 11.7181 8.2862 11.7181 7.94414C11.7181 7.60207 11.4411 7.32445 11.0984 7.32445Z"/>-->
                                            <!--        </g>-->
                                            <!--    </svg>-->
                                            <!--    Inclusion-->
                                            <!--    <div class="info">-->
                                            <!--        <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">-->
                                            <!--            <g>-->
                                            <!--                <path-->
                                            <!--                    d="M6 0.375C4.88748 0.375 3.79995 0.704901 2.87492 1.32298C1.94989 1.94107 1.22892 2.81957 0.80318 3.84741C0.377437 4.87524 0.266043 6.00624 0.483085 7.09738C0.700127 8.18853 1.23586 9.19081 2.02253 9.97748C2.8092 10.7641 3.81148 11.2999 4.90262 11.5169C5.99376 11.734 7.12476 11.6226 8.1526 11.1968C9.18043 10.7711 10.0589 10.0501 10.677 9.12508C11.2951 8.20006 11.625 7.11252 11.625 6C11.6245 4.50831 11.0317 3.07786 9.97693 2.02307C8.92215 0.968289 7.49169 0.375497 6 0.375ZM6 9.375C5.85167 9.375 5.70666 9.33101 5.58333 9.2486C5.45999 9.16619 5.36386 9.04906 5.30709 8.91201C5.25033 8.77497 5.23548 8.62417 5.26441 8.47868C5.29335 8.3332 5.36478 8.19956 5.46967 8.09467C5.57456 7.98978 5.7082 7.91835 5.85369 7.88941C5.99917 7.86047 6.14997 7.87533 6.28702 7.93209C6.42406 7.98886 6.54119 8.08499 6.62361 8.20832C6.70602 8.33166 6.75 8.47666 6.75 8.625C6.74941 8.82373 6.6702 9.01415 6.52968 9.15468C6.38915 9.2952 6.19873 9.37441 6 9.375ZM6.85875 3.55875L6.6075 6.56625C6.5944 6.71834 6.52472 6.85999 6.41224 6.9632C6.29976 7.0664 6.15266 7.12367 6 7.12367C5.84735 7.12367 5.70024 7.0664 5.58776 6.9632C5.47528 6.85999 5.40561 6.71834 5.3925 6.56625L5.14125 3.55875C5.13042 3.44226 5.1434 3.32478 5.1794 3.21346C5.2154 3.10214 5.27367 2.99931 5.35067 2.91123C5.42767 2.82314 5.52178 2.75165 5.62729 2.70108C5.73279 2.65052 5.84748 2.62195 5.96437 2.61711C6.08127 2.61227 6.19793 2.63126 6.30725 2.67294C6.41657 2.71461 6.51627 2.77808 6.60029 2.8595C6.6843 2.94092 6.75087 3.03858 6.79595 3.14655C6.84103 3.25451 6.86367 3.37051 6.8625 3.4875C6.86313 3.51131 6.86187 3.53514 6.85875 3.55875Z"/>-->
                                            <!--            </g>-->
                                            <!--        </svg>-->
                                            <!--        <div class="tooltip-text">7 years experience overall</div>-->
                                            <!--    </div>-->
                                            <!--</li>-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
@endforeach

                       

                    </div>
                </div>
                @include('front.partials.pagination', ['paginator' => $doctors])
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    
        flatpickr(".flatpicker-input", {
            dateFormat: "d-m-Y",   // 2026-02-23
            minDate: "today",      // disable past dates
            allowInput: true
        });
    
    });
    $(document).ready(function() {

        $('#insurance-policy').on('change', function() {
        lodSubIncurance($(this).val());
        
    });

        function lodSubIncurance(incuranceId) {
        if (incuranceId) {
            $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                success: function(res) {
                    if (res) {
                        $('#sub-insurance-policy').html('<option value="">Sub Insurance</option>');
                        $.each(res, function(index, data) {
                            $('#sub-insurance-policy').append('<option value="' + data.id + '">' + data.title + '</option>');
                        });
                        // $('#sub-insurance-policy').val(selectedId).trigger('change');
                        $('#sub-insurance-policy').select2(); // Reinitialize select2
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        } else {
            $('#sub-insurance-policy').empty();
            $('#sub-insurance-policy').append('<option value=""></option>');
        }
    }

$('#sortSelector').on('change', function() {
    let value = $(this).val();

    // Set hidden input value
    $('#sort_by').val(value);

    // Submit the GET form
    $('#search-form').submit(); 
});

});
    </script>
@endsection