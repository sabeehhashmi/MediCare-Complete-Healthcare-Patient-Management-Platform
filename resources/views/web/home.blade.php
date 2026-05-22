@extends('web.template.layout')

@section('title', 'Home')

@php
// if function did not exit getHomePageValue then define it
if(!function_exists('getHomePageValue')){
function getHomePageValue($key, $homepageData){

if (!$homepageData) {return "";}

// if homepage data is not set then return
if(!isset($homepageData)){
return '';
}

return $homepageData[$key] ?? null;

}
}
@endphp

@section('content')



<style>
    @media(max-width:992px){
        header{
            position: relative !important;
        }
        .page-content{
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
    }
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<!-- <div class="main-content"> -->
<div class="page-content">
    <div class="container-fluid">
        <!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card horizontal-search-panel mt-4 mt-lg-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Search for a doctor in the UAE</h5>
                    </div>
                    <div class="card-body pb-0">
                        <!-- <p class="card-title-desc">Create beautifully simple form labels that float over your input fields.</p> -->
                        <form method="GET" action="{{route('find_a_doctor')}}" id="search-form">
                            <input type="hidden" name="current_latitude" id="latitude" value="">
                            <input type="hidden" name="current_longitude" id="longitude" value="">
                            <!-- row-cols-lg-auto -->
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctors Specialty</label> -->
                                        <select name="specialty_id" class="form-select select2-single w-100" data-placeholder="Doctors Specialty" id="specialty">
                                            <option value="">Doctors Specialty</option>
                                            @foreach($specialties as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon specialty-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control" id="floatingemailInput" placeholder="Enter Email address">
                                                        <label for="floatingemailInput">Doctors Specialty</label>
                                                    </div>
                                                </div> -->
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">My Insurance Policy</label> -->
                                        <select name="insurance_id" class="form-select select2-single" data-placeholder="My Insurance Policy" id="insurance-policy">
                                            <option value="">My Insurance Policy</option>
                                            @foreach($insurencePolicies as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon insu-policy-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">My Insurance Network</label> -->
                                        <select name="sub_insurance_id" class="form-select select2-single" data-placeholder="My Insurance Network" id="sub-insurance-policy">
                                            <option value="">My Insurance Network</option>
                                            @foreach($subInsurencePolicies as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon sub-policy-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">My Medical Condition</label> -->
                                        <select name="medical_condition_id" class="form-select select2-single" data-placeholder="My Medical Condition" id="interest">
                                            <option value="">My Medical Condition</option>
                                            @foreach($medicalConditions as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon medical-condition-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Language</label> -->
                                        <select name="language_id" class="form-select select2-single" data-placeholder="Doctor’s Language" id="language">
                                            <option value="">Doctor’s Language</option>
                                            @foreach($languages as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-language-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative input-custom-icon mb-3">
                                        <input type="text" name="date" class="form-control flatpicker-input-home" id="need_date_filter" placeholder="Search by Date" />
                                        <span class="custom-icon calendar-doc-icn"></span>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for=""> Doctor’s Country of Origin</label> -->
                                        <select name="cuntry_of_origin_id" class="form-select select2-single" data-placeholder="Doctor’s Country of Origin" id="countryOrigin">
                                            <option value=""> Doctor’s Country of Origin</option>
                                            @foreach($countries as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon counrty-orgin-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Gender</label> -->
                                        <select name="gender_id" class="form-select select2-single" data-placeholder="Doctor’s Gender" id="gender">
                                            <option value="">Doctor’s Gender</option>
                                            @foreach($genders as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-gender-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Cities</label> -->
                                        <select name="emirates_id" class="form-select select2-single" data-placeholder="Emirates" id="emirates">
                                            <option value="">Cities</option>
                                            @foreach($emirates as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-location-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Area</label> -->
                                        <select name="area_id" class="form-select select2-single" data-placeholder="Area" id="area">
                                            <option value="">Area</option>
                                            @foreach($areas as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-area-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 d-none">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Direct Call for Appointment</label> -->
                                        <select name="dirent_call_for_appointment" class="form-select select2-single" data-placeholder="Direct Call for Appointment">
                                            <option value="">Direct Call for Appointment</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                        </select>
                                        <i class="custom-icon direct-call-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 d-none">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Ready to consult instantly</label> -->
                                        <select name="ready_to_consult_instantly" class="form-select select2-single" data-placeholder="Ready to consult instantly">
                                            <option value="">Ready to consult instantly</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                        </select>
                                        <i class="custom-icon consult-instant-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Hospital/ Clinic / Dental Care</label> -->
                                        <select name="hospital_id" class="form-select select2-single" data-placeholder="Hospital/ Clinic / Dental Care" id="hospital">
                                            <option value="">Hospital/ Clinic / Dental Care</option>
                                            @foreach($hospitals as $id => $value)
                                            <option value="{{$id}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon hospital-icon-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Name</label> -->
                                        <input type="text" class="form-select" placeholder="Doctor’s Name" id="doctor">
                                        <i class="custom-icon doctor-name-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-8">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <label class="form-label" for="">Search by Distance</label>
                                        <input type="range" name="distance" class="form-range" min="0" max="{{$max_radius??300}}" id="customRange3" value="{{ request('distance', 0) }}">
                                        <!-- <div class="d-flex justify-content-between">
                                                            <div class="text-muted">1 km</div>
                                                            <div class="text-muted">100 km</div>
                                                        </div>  -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="form-check py-3">
                                        <input type="checkbox" name="dirent_call_for_appointment" class="form-check-input" id="auth-remember-chec1" {{ request('dirent_call_for_appointment') ? 'checked' : '' }} value="1">
                                        <label class="form-check-label ms-2" for="auth-remember-chec1"> Direct Calling Number for Appointment</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="form-check py-3">
                                        <input type="checkbox" name="ready_to_consult_instantly" class="form-check-input" id="auth-remember-check2" {{ request('ready_to_consult_instantly') ? 'checked' : '' }} value="1">
                                        <label class="form-check-label ms-2" for="auth-remember-check2"> Ready to consult instantly</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <button type="submit" class="btn btn-primary w-lg w-100 mb-3">Search</button>
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="floatingCheck">
                                                    <label class="form-check-label" for="floatingCheck">
                                                      Check me out
                                                    </label>
                                                </div>
                                            </div> -->
                        </form>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="card bg-prime-light banner-area mt-5">
            <div class="card-body p-0 pt-3">
                <div class="swiper banner-slider rounded overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach($homepageSlides as $slide)
                        <div class="swiper-slide rounded overflow-hidden ecommerce-slied-bg">
                            <img src="{{$slide->image}}" class="img-fluid w-100">
                        </div>
                        @endforeach

                    </div>
                </div>

                <!-- <div class="d-none d-lg-block">
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div> -->
            </div>
        </div>






        <div class="row mb-5 mt-5">
            <div class="col-md-12">
                <div class="text-center text-muted my-5">
                    <h1 class="mt-2 mb-4 fw-bold font-size-36">{{getHomePageValue('frm_sct_2_title', $homepageData)}}</h1>
                    <p class="text-body">{{getHomePageValue('frm_sct_2_subtitle', $homepageData)}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 mb-0">
                    <div class="card-body">
                        <img src="" class="img-fluid">
                        <h5 class="text-primary font-size-16 text-uppercase">01. {{getHomePageValue('frm_sct_2_box1_title', $homepageData)}}</h5>
                        <p class="text-muted mb-0">{{getHomePageValue('frm_sct_2_box1_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 mb-0">
                    <div class="card-body">
                        <img src="" class="img-fluid">
                        <h5 class="text-primary font-size-16 text-uppercase">02. {{getHomePageValue('frm_sct_2_box2_title', $homepageData)}}</h5>
                        <p class="text-muted mb-0">{{getHomePageValue('frm_sct_2_box2_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 mb-0">
                    <div class="card-body">
                        <img src="" class="img-fluid">
                        <h5 class="text-primary font-size-16 text-uppercase">03. {{getHomePageValue('frm_sct_2_box3_title', $homepageData)}}</h5>
                        <p class="text-muted mb-0">{{getHomePageValue('frm_sct_2_box3_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 mb-0">
                    <div class="card-body">
                        <img src="" class="img-fluid">
                        <h5 class="text-primary font-size-16 text-uppercase">04. {{getHomePageValue('frm_sct_2_box4_title', $homepageData)}}</h5>
                        <p class="text-muted mb-0">{{getHomePageValue('frm_sct_2_box4_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-5">

        <div class="col-xl-12 bg-prime-light py-5">
            <div class="intro-app-section">
                <div class="container-fluid">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="mt-2 mb-4 fw-bold font-size-36">
                                    {{getHomePageValue('frm_sct_3_title', $homepageData)}}
                                </h2>

                                @php
                                echo getHomePageValue('frm_sct_3_content', $homepageData);
                                @endphp
                                <!-- <div class="row g-0 mt-3 pt-1 mb-3 align-items-end">
                                                        <div class="col-4">
                                                            <div class="mt-1">
                                                                <h4 class="font-size-24">800</h4>
                                                                <p class="text-body mb-1">Total Selling</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="mt-1">
                                                                <h4 class="font-size-24">250</h4>
                                                                <p class="text-body mb-1">Total Stock</p>
                                                            </div>
                                                        </div>
                                                    </div> -->

                                <div class="mt-1">
                                    <a href="{{route('find_a_doctor')}}" class="btn btn-primary btn-sm mb-1 w-fit-content">Search for an Appointment within 2 hours (IMA)</a>
                                </div>
                            </div>
                            <div class="col-lg-6 text-center">
                                <div class="p-2 image-etr">
                                    <img src="{{getHomePageValue('frm_sct_3_img', $homepageData)}}" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 py-5 bg-white">
            <div class=" intro-app-section">
                <div class="container-fluid">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center">
                                <div class="p-2 image-etr">
                                    <img src="{{getHomePageValue('frm_sct_4_img', $homepageData)}}" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="mt-2 mb-4 fw-bold font-size-36">
                                    {{getHomePageValue('frm_sct_4_title', $homepageData)}}
                                </h2>
                                @php
                                echo getHomePageValue('frm_sct_4_content', $homepageData);
                                @endphp
                                <div class="mt-1">
                                    <a href="{{route('find_a_doctor')}}" data-bs-toggle="modal" data-bs-target="#panellists" class="btn btn-primary btn-sm mb-1 w-fit-content">For Doctors/Clinics/Hospitals to Enroll</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5 mb-5">
        <div class="banner-area">
            <div class="p-0">
                <div class="col-md-12">
                    <div class="text-center text-muted my-5">
                        <h1>Healthcare Partners</h1>
                        <!-- <p class="text-body">Connecting patients with care, seamlessly. Our Doctor App: Your health, our priority</p> -->
                    </div>
                </div>
                
                <div class="swiper logos-slider">
                    <div class="swiper-wrapper">

                        @foreach($partnerLogos as $logo)
                        <div class="swiper-slide">
                            <div class="h-100 text">
                                <img src="{{$logo->image}}" class="img-fluid">
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                <!-- <div class="d-none d-lg-block">
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                        </div> -->
            </div>
        </div>
    </div>


    <div class="row mt-5">

        <div class="col-xl-12 bg-prime-light py-5">
            <div class="intro-app-section">
                <div class="container-fluid">
                    <div class="card-body">
                        <div class="row align-items-center">

                            <div class="col-lg-6 text-center">
                                <div class="p-2 image-etr">
                                    <img src=" {{getHomePageValue('frm_sct_5_img', $homepageData)}}" class="img-fluid" alt="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <h2 class="mt-2 mb-4 fw-bold font-size-36">
                                    {{getHomePageValue('frm_sct_5_title', $homepageData)}}
                                </h2>
                                <h4>{{getHomePageValue('frm_sct_5_sub_title', $homepageData)}}</h4>

                                <p class="text-body mt-3">
                                    @php
                                    echo getHomePageValue('frm_sct_5_content', $homepageData);
                                    @endphp
                                </p>

                                <!-- <div class="mt-1">
                                                        <a href="list-doctors.php" class="btn btn-primary btn-sm mb-1 w-fit-content">Search for an Appointment within 2 hours (IMA)</a>
                                                    </div> -->
                                <!-- <ul>
                                                        <li class="text-black mb-2">Simply choose the "Ready to Consult Instantly" filter while searching for a doctor.</li>
                                                        <li class="text-black">Confirm Your Booking Instantly by calling our Call Centre [0971 505257794.]</li>
                                                    </ul>
                                                    
                                                    <div class="mt-1">
                                                        <a href="list-doctors.php" class="btn btn-primary btn-sm mb-1 w-fit-content">Search for an Appointment within 2 hours (IMA)</a>
                                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>





    <div class="container-fluid mt-5 mb-5">

        <div class="row justify-content-center align-items-center mb-3">
            <div class="col-md-12">
                <div class="text-center text-muted my-5">
                    <h1 class="mt-2 mb-4 fw-bold font-size-36">{{getHomePageValue('frm_sct_6_title', $homepageData)}}</h1>
                    <!-- <p class="text-body">Connecting patients with care, seamlessly. Our Doctor App: Your health, our priority</p> -->
                </div>
            </div>
            <!-- <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="px-2 col-md-4">
                                                <img src="https://img.freepik.com/free-vector/private-healthcare-abstract-concept-vector-illustration-private-medicine-healthcare-insurance-paid-medical-services-health-center-specialist-consulting-clinic-facility-abstract-metaphor_335657-4054.jpg?t=st=1713955324~exp=1713958924~hmac=05e7d8c0ae897be19ba7bd8fc8a0f42512a77b1217586b91353848749bac8ec5&w=740" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="mt-4">Wide Collection Database</h4>
                                                <p class="pt-1">4 integrations, 30 team members, advanced features </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="px-2 col-md-4">
                                                <img src="https://img.freepik.com/free-vector/call-center-abstract-concept-vector-illustration-handling-call-system-virtual-help-center-customer-service-point-product-support-market-research-communication-software-abstract-metaphor_335657-2885.jpg?w=740&t=st=1713955411~exp=1713956011~hmac=c66ad46e6d70fecab899a1b6160502d86577de4b0b0fb3904afd170c30886b51" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="mt-4">24x7 Instant Booking</h4>
                                                <p class="pt-1">4 integrations, 30 team members, advanced features </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="px-2 col-md-4">
                                                <img src="https://img.freepik.com/free-vector/hands-free-phone-calling-abstract-concept-illustration_335657-3859.jpg?w=740&t=st=1713955489~exp=1713956089~hmac=f6604d2b9c7c688aab23709ea8fb3e72fb4c025604920332f2f1e1cfd7464fca" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="mt-4">Smart Calling System</h4>
                                                <p class="pt-1">4 integrations, 30 team members, advanced features </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="px-2 col-md-4">
                                                <img src="https://img.freepik.com/free-vector/telehealth-abstract-concept-vector-illustration-virtual-medical-care-remote-admission-doctor-advice-telehealth-appointment-coronavirus-pandemic-lockdown-social-distancing-abstract-metaphor_335657-4157.jpg?w=740&t=st=1713955560~exp=1713956160~hmac=143257685df81c3753cba8cd0dacc6586dace65596381ecb4e0cab2b84d481dc" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="mt-4">Appointment Notifications</h4>
                                                <p class="pt-1">4 integrations, 30 team members, advanced features </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="card mb-3">
                    <div class="card-body text-lg-end">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box1_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box1_desc', $homepageData)}}.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body text-lg-end">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box2_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box2_desc', $homepageData)}}</p>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body text-lg-end">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box3_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box3_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                <img src="{{getHomePageValue('frm_sct_6_img', $homepageData)}}" class="img-fluid" alt="">
                <!-- <img src="{{ URL::asset('web/') }}/images/features.png" class="img-fluid" alt=""> -->
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box4_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box4_desc', $homepageData)}}</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box5_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box5_desc', $homepageData)}}</p>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="text-primary fw-bold">{{getHomePageValue('frm_sct_6_box6_title', $homepageData)}}</h4>
                        <p class="mb-0">{{getHomePageValue('frm_sct_6_box6_desc', $homepageData)}}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white py-3 mb-0 d-none">
        <div class="container-fluid">
            <!-- <div class="text-center text-muted my-5">
                                    <h1>FAQs</h1>
                                    <p class="text-body">Answers to your questions, empowering your journey. FAQs: Your key to clarity in a complex world.</p>
                                </div> -->
            <div class="d-none">
                <div class="card-body">
                    <div class="accordion" id="accordipOnexample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepOne" aria-expanded="true" aria-controls="collapsepOne">
                                    <h5 class="mb-0">How do I book my online appointment?</h5>
                                    <h5 class="mb-0"></h5>
                                </button>
                            </h2>
                            <div id="collapsepOne" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    Once you have found the doctor through the Mednero app for an online appointment, you can make an appointment with that doctor just following a few simple steps.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepTwo" aria-expanded="false" aria-controls="collapsepTwo">
                                    <h5 class="mb-0">How do I find a specialist doctor in Mednero?</h5>
                                </button>
                            </h2>
                            <div id="collapsepTwo" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    In Mednero online appointment booking platform finding a specialist doctor is easy for everyone. Simply entering the Name of the doctor, Specialty, Hospital name, and preferred language search inputs
                                    will instantly derive the best answers.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepThree" aria-expanded="false" aria-controls="collapsepThree">
                                    <h5 class="mb-0">How will I understand the cancellation of my appointment?</h5>
                                </button>
                            </h2>
                            <div id="collapsepThree" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    Mednero customer support staff or the hospitals will contact you directly if there are any changes in your scheduled appointments prior to the scheduled day.
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->

            <div class="row d-none">
                <div class="col-lg-6 mb-4">
                    <div class="text-center text-muted my-5">
                        <h1>FAQs For Patients</h1>
                        <p class="text-body">Understanding your requirements and objectives is important to us. We listen and work together to create a truly unique and unforgettable experience.</p>
                    </div>

                    <div class="accordion" id="accordipOnexample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepOne" aria-expanded="true" aria-controls="collapsepOne">
                                    <h5 class="mb-0">How do I book my online appointment?</h5>
                                    <h5 class="mb-0"></h5>
                                </button>
                            </h2>
                            <div id="collapsepOne" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    Once you have found the doctor through the Mednero app for an online appointment, you can make an appointment with that doctor just following a few simple steps.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepTwo" aria-expanded="false" aria-controls="collapsepTwo">
                                    <h5 class="mb-0">How do I find a specialist doctor in Mednero?</h5>
                                </button>
                            </h2>
                            <div id="collapsepTwo" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    In Mednero online appointment booking platform finding a specialist doctor is easy for everyone. Simply entering the Name of the doctor, Specialty, Hospital name, and preferred language search inputs
                                    will instantly derive the best answers.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepThree" aria-expanded="false" aria-controls="collapsepThree">
                                    <h5 class="mb-0">How will I understand the cancellation of my appointment?</h5>
                                </button>
                            </h2>
                            <div id="collapsepThree" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    Mednero customer support staff or the hospitals will contact you directly if there are any changes in your scheduled appointments prior to the scheduled day.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end accordion -->
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="text-center text-muted my-5">
                        <h1>FAQs For Doctors</h1>
                        <p class="text-body">Understanding your requirements and objectives is important to us. We listen and work together to create a truly unique and unforgettable experience.</p>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <h5 class="mb-0">Why do Doctors join Mednero online appointment platform?</h5>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Mednero efficiently categorises doctors’ areas of Specialization. Build trust around the most effective practice management. The profile link will be published online and will gain visibility through
                                    our proven methodology. The branded communications guarantee that each communication is personalised (responsive emails and SMS) to your patients.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <h5 class="mb-0">How do patients find me online?</h5>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Patients can find your profile listing on the Mednero website by the broad search criteria. They can access it via mobile or desktop. Patients can also see your availability at various practice
                                    locations (i.e., the different clinics/hospitals) and book an appointment with you in just a few clicks.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <h5 class="mb-0">What is the benefit of healthcare providers by joining this app?</h5>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    The healthcare providers can manage the outpatient flow and schedule the hospital staff according to their convenience.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end accordion -->
                </div>
            </div>
            <!-- <div class="row justify-content-center">
                                   <div class="col-lg-6">
                                       <div class="text-center my-5">
                                           <img src="{{ URL::asset('web/') }}/images/faq-img.png" class="img-fluid" alt="">
                                       </div>
                                   </div>
                               </div> -->
            <!-- end row -->
        </div>
    </div>


    <div class="row py-3" style="margin-bottom: -16px !important;">
        <div class="col-xl-12 bg-prime-light py-5">
            <div class="intro-app-section  mb-0">
                <div class="">
                    <!-- <div class="card intro-app-section  mb-0">
                                <div class="card-body"> -->
                    <div class="row align-items-center">
                        <div class="col-lg-6 text-center">
                            <div class="p-2 image-etr">
                                <img src="{{getHomePageValue('frm_sct_7_img', $homepageData)}}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <!-- <span class="badge bg-primary-subtle text-primary  font-size-10 text-uppercase ls-05"> Popular Item</span> -->
                            <h2 class="mt-2 mb-4 fw-bold font-size-36">
                                {{getHomePageValue('frm_sct_7_title', $homepageData)}}
                            </h2>
                            <h4>{{getHomePageValue('frm_sct_7_sub_title', $homepageData)}}</h4>
                            <!-- <ul class="my-4">
                                                <li class="text-body mb-1"><b>100,000</b> Verified doctors</li>
                                                <li class="text-body mb-1"><b>3M+</b> Patient recommendations</li>
                                                <li class="text-body mb-1"><b>25M</b> Patients/year</li>
                                            </ul> -->
                            <div class="text-black mt-4">
                                @php
                                echo getHomePageValue('frm_sct_7_content', $homepageData);
                                @endphp
                            </div>

                            <div class="mt-1">
                                <a target="blank" href="https://apps.apple.com/us/app/Mednero/id6569243893" class="btn-sm d-inline-block mb-2 me-3"> <img style="max-width: 165px;" src="{{ URL::asset('web/') }}/images/appstore_badge.webp" alt="" /> </a>
                                <a target="blank" href="https://play.google.com/store/apps/details?id=co.okdok.Mednero.Mednero" class="btn-sm d-inline-block"> <img style="max-width: 165px;" src="{{ URL::asset('web/') }}/images/google_badge.webp" alt="" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->

    @include('web.template.footer-content')
</div>
<!-- </div> -->
<!-- end main content-->
@endsection

@section('custom_js')
<script>
    function getLocation() {
        let currentLatitude = localStorage.getItem('current_latitude');
        let currentLongitude = localStorage.getItem('current_longitude');
        $("#latitude").val(currentLatitude);
        $("#longitude").val(currentLongitude);
    }

    $(document).ready(function() {
        getLocation();
    });

    function lodHospitals() {
        var filters = {};

        if ($('#insurance-policy').val()) {
            filters["insurance_id"] = $('#insurance-policy').val()
        }
        if ($('#sub-insurance-policy').val()) {
            filters["sub_insurance_id"] = $('#sub-insurance-policy').val()
        }
        if ($('#emirates').val()) {
            filters["emirate_id"] = $('#emirates').val()
        }
        if ($('#area').val()) {
            filters["area_id"] = $('#area').val()
        }
        if ($('#specialty').val()) {
            filters["dr_specialty"] = $('#specialty').val()
        }
        if ($('#interest').val()) {
            filters["dr_interest"] = $('#interest').val()
        }
        if ($('#language').val()) {
            filters["dr_language"] = $('#language').val()
        }
        if ($('#countryOrigin').val()) {
            filters["dr_countryOrigin"] = $('#countryOrigin').val()
        }
        if ($('#gender').val()) {
            filters["dr_gender"] = $('#gender').val()
        }

        if (filters) {
            $('#hospital').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('get-hospitals') }}",
                data: filters,
                success: function(res) {
                    if (res) {
                        $('#hospital').html('<option value="">Hospital/ Clinic / Dental Care</option>');
                        $.each(res, function(index, data) {
                            $('#hospital').append('<option value="' + data.id + '">' + data.name_en + '</option>');
                        });
                        // $('#hospital').val(selectedId).trigger('change');
                        $('#hospital').select2(); // Reinitialize select2
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        } else {
            $('#hospital').empty();
            $('#hospital').append('<option value=""></option>');
        }
    }

    function lodSubIncurance(incuranceId) {
        if (incuranceId) {
            $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                success: function(res) {
                    if (res) {
                        $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
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

    function lodArea(emiratesId) {
        if (emiratesId) {
            $('#area').html('<option value="" disabled>Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('get-area') }}/" + emiratesId,
                success: function(res) {
                    if (res) {
                        $('#area').html('<option value="">Area</option>');
                        $.each(res, function(index, data) {
                            $('#area').append('<option value="' + data.id + '">' + data.name_en + '</option>');
                        });
                        // $('#area').val(selectedId).trigger('change');
                        $('#area').select2(); // Reinitialize select2
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        } else {
            $('#area').empty();
            $('#area').append('<option value=""></option>');
        }
    }

    $('#insurance-policy').on('change', function() {
        lodSubIncurance($(this).val());
        lodHospitals();
    })

    $('#sub-insurance-policy').on('change', function() {
        lodHospitals();
    })

    $('#emirates').on('change', function() {
        lodArea($(this).val());
        lodHospitals();
    })

    $('#area').on('change', function() {
        lodHospitals();
    })
    $('#specialty').on('change', function() {
        lodHospitals();
    })
    $('#interest').on('change', function() {
        lodHospitals();
    })
    $('#language').on('change', function() {
        lodHospitals();
    })
    $('#countryOrigin').on('change', function() {
        lodHospitals();
    })
    $('#gender').on('change', function() {
        lodHospitals();
    })
    $('#need_date_filter').on('change', function(){
            lodHospitals();
        })

    $("#customRange3").ionRangeSlider({
        // type: "double",
        // grid: true,
        skin: "round",
        min: 0,
        max: '{{$max_radius??300}}',
        // from: 100,
        // to: 150,
        postfix: " KM"
    });
    
    var swipert = new Swiper(".banner-slider", {
                        spaceBetween: 15,
                        loop: true,
                        speed: 1500,
                        parallax: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: true,
                        },
            });

    var swiperl = new Swiper(".logos-slider", {
                spaceBetween: 15,
                loop: true,
                speed: 1500,
                parallax: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: true,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                    },
                    600: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 4,
                    },
                    1500: {
                        slidesPerView: 5,
                    },
                },
            });
</script>
@endsection