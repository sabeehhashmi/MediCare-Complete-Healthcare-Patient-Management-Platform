@extends('front.template.layout')

@section('title', 'Saved Patients')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

<style>
    #imagePreview {
        width: 126px;
        height: 114px;
        background-size: cover;
        background-position: center;
        margin-top: 15px;
    }

    span.text-danger {
        display: block;
        margin-top: 5px;
        font-weight: 500;
    }

    /* Highlight the input border red on error */
    input.error,
    select.error {
        border-color: #ff4d4d !important;
    }
</style>

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">

            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title d-flex justify-content-between">
                        <div>
                            <h4>Saved Patients</h4>
                        </div>
                        <div>
                            <button class="primary-btn1 form_modal_trigger" type="button" data-bs-toggle="modal" data-mode="add" data-bs-target="#formModal">Add New</button>
                        </div>
                    </div>
                    <!-- Faq Section Start-->
                    <div class="home1-faq-section mb-100">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="faq-wrap">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        @if($patients->count() > 0)
                                        @foreach($patients as $key => $patient)
                                        <div class="accordion-item wow animate fadeInDown" data-wow-delay="800ms" data-wow-duration="1500ms">
                                            <h5 class="accordion-header" id="flush-heading{{ $key }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$key}}"
                                                    aria-expanded="false" aria-controls="flush-collapse{{$key}}">
                                                    <div class="avil-type position-static">
                                                        <span class="item-type">
                                                            <i class='bx bxs-user'></i>
                                                        </span>
                                                    </div>
                                                    <span class="ms-2">{{ $patient->full_name }}</span>
                                                </button>
                                            </h5>
                                            <div id="flush-collapse{{$key}}" class="accordion-collapse collapse"
                                                aria-labelledby="flush-heading{{ $key }}" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Gender: <strong>{{ $patient->gender == '1' ? 'Male' : 'Female' }}</strong></span>
                                                        <span>Age: <strong>{{ $patient->age }}</strong></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Overall Appointments: <strong>{{ $patient->appointments_count }}</strong></span>
                                                        <!-- <span>Clinic: <strong>Boston Medical Centre</strong></span>  -->
                                                    </div>

                                                    <div class="d-flex justify-content-between">

                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#cancelModalModal"
                                                            class="primary-btn1 btn-outline-secondery mt-30 delete_patient_handle" data-url="{{ route('front.patients.delete', ['id' => encrypt($patient->id)]) }}">
                                                            <span>
                                                                Delete
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            <span>
                                                                Delete
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </button>

                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#formModal"
                                                            class="primary-btn1 btn-outline mt-30 edit_patient"
                                                            data-id="{{ $patient->id }}"
                                                            data-full_name="{{ $patient->full_name }}"
                                                            data-age="{{ $patient->age }}"
                                                            data-gender="{{ $patient->gender }}"
                                                            data-insurence_id="{{ $patient->insurence_id }}"
                                                            data-sub_insurence_id="{{ $patient->sub_insurence_id }}">
                                                            <span>
                                                                Edit
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            <span>

                                                                Edit
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="col-12 py-3">
                                            {{ $patients->links() }}
                                        </div>

                                        @else
                                        <p>You do not have any saved patients!</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Faq Section End-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modals')
<!-- Enquiry Modal section Start-->
<div class="modal enquiry-modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                </svg>
            </button>
            <div class="modal-body">
                <h4 class="modal-title" id="addPatientModalLabel">Edit Patient</h4>
                <form class="enquiry-form-wrapper" id="patientForm">
                    <div class="row g-4 mb-50">
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label>Full Name*</label>
                                <input type="text" placeholder="Your Name" name="full_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label>Age*</label>
                                <input type="text" placeholder="Your Age" name="age">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label>Gender*</label>
                                <select id="gender_list" class="ignore-all">
                                    <option value="1" selected>Male</option>
                                    <option value="2">Female</option>
                                </select>
                                <input type="hidden" name="gender_count">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label>My Insurance Policy*</label>
                                <select id="insurance_list_main" class="ignore-all">
                                    <option value="">Select Insurance</option>
                                    @foreach($insurence_list as $item)
                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="insurance_list_main_count">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label>Profile Photo</label>
                                <input type="file" placeholder="Your Name" name="image" accept="image/jpg, image/jpeg, image/png" id="imageUpload">

                                <div id="imagePreview"></div>
                            </div>
                        </div>
                        <div class="col-md-6" id="sub_insurance_container" style="display: none;">
                            <div class="form-inner">
                                <label>Sub Insurance*</label>
                                <select id="insurance_list_sub" class="ignore-all">
                                    <option value="">Select sub insurance</option>
                                </select>
                                <input type="hidden" name="insurance_list_sub_count">
                            </div>
                        </div>
                    </div>
                    <div class="form-inner">
                        <button id="doSubmit" type="button" class="primary-btn1 black-bg" style="z-index: 0;">
                            <span>
                                Submit & Save Patient
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                            <span>
                                Submit & Save Patient
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Enquiry Modal section End-->


<!-- Enquiry Modal section Start-->
<div class="modal enquiry-modal fade" id="cancelModalModal" tabindex="-1" aria-labelledby="cancelModallLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                </svg>
            </button>
            <div class="modal-body">
                <h4 class="modal-title" id="cancelModalLabel">Delete Patient!</h4>
                <form id="deletePatientForm" class="enquiry-form-wrapper" action="" method="post">

                    @csrf()
                    @method('DELETE')
                    <!-- <input type="hidden" name="_method" value="DELETE" > -->

                    <h4>Are you sure?</h4>
                    <p>You want to delete the patient.</p>

                    <div class="form-inner">
                        <button type="submit" class="primary-btn1 black-bg">
                            <span>
                                Yes, Procceed
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                            <span>
                                Yes, Procceed
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <button type="button" class="primary-btn1 btn-outline-secondery black-bg ms-3">
                            <span>
                                No
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                            <span>
                                No
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Enquiry Modal section End-->

<div id="subIns" data-sub-insurance-list="{{ json_encode($sub_insurence_list) }}"></div>


@endsection



@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    // new AirDatepicker('#calenderView', {
    //     inline: true,
    //     locale: localeEn
    // })

    let subInsuranceList = []

    let editId = 0

    $(document).ready(function() {

        const genderSelectHandle = document.querySelector('#gender_list');
        const genderChoices = new Choices(genderSelectHandle, {
            searchEnabled: false
        });

        const mainInsuranceSelectHandle = document.querySelector('#insurance_list_main');
        const mainInsuranceChoices = new Choices(mainInsuranceSelectHandle, {
            position: "top"
        });

        const subInsuranceSelectHandle = document.querySelector('#insurance_list_sub');
        const subInsuranceChoices = new Choices(subInsuranceSelectHandle, {
            position: "top"
        });

        const savedPatientInstance = {
            root: $(document),
            modalTrigger: 'button.form_modal_trigger',
            modal: 'div#formModal',
            doSubmit: 'button#doSubmit',
            form: '#patientForm',
            editPatientHandle: '.edit_patient',
            deletehandle: '.delete_patient_handle',

            init: function() {
                const self = this;

                // Initialize jQuery Validation
                $(this.form).validate({
                    ignore: ".ignore-all, .choices__input",
                    rules: {
                        full_name: "required",
                        // insurance_list_main_count: "required",
                        age: {
                            required: true,
                            number: true,
                            min: 1
                        },
                        gender: "required"
                    },
                    messages: {
                        full_name: "Please enter the patient's full name",
                        age: "Please enter a valid age",
                        insurance_list_main_count: "Please select the insurance",
                        insurance_list_sub_count: "Please select the sub insurance"
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger font-size-12');
                        element.closest('.form-inner').append(error);
                    }
                });

                // Insurance Change Logic
                mainInsuranceSelectHandle.addEventListener('change', function(evt) {
                    const insurence_id = evt.detail.value;
                    $('#insurance_list_main_count').val(insurence_id)

                    subInsuranceChoices.removeActiveItems();
                    $('#sub_insurance_container').hide()

                    $.ajax({
                        url: "{{ route('front.sub_insurance') }}",
                        type: "GET",
                        data: {
                            insurence_id
                        },
                        success: function(response) {
                            subInsuranceChoices.clearStore();
                            const newChoices = response.data.map(x => ({
                                label: x.title,
                                value: x.id
                            }));
                            subInsuranceChoices.setChoices(newChoices, 'value', 'label', true);

                            if(newChoices.length){
                                $('#sub_insurance_container').show()
                            }

                        }
                    });
                });

                // Submit Logic
                this.root.on('click', this.doSubmit, function() {
                    // Check if form is valid
                    if (!$(self.form).valid()) {
                        return false;
                    }

                    // if (!mainInsuranceChoices.getValue(true)) {
                    //     alert("Please select an insurance policy");
                    //     return false;
                    // }

                    let form = $(self.form)[0];
                    let data = new FormData(form);

                    data.append('_token', "{{ csrf_token() }}");

                    const gender = genderChoices.getValue(true)
                    data.append('gender', gender);

                    const insurence_id = mainInsuranceChoices.getValue(true)

                    if (insurence_id) {
                        data.append('insurence_id', insurence_id);
                    }

                    const sub_insurence_id = subInsuranceChoices.getValue(true)

                    if (sub_insurence_id) {
                        data.append('sub_insurence_id', sub_insurence_id);
                    }

                    if (editId) {
                        data.append('id', editId);
                    }

                    // Disable button to prevent double submission
                    const $btn = $(this);
                    $btn.prop('disabled', true).css('opacity', '0.5');

                    $.ajax({
                        type: "POST",
                        url: "{{ route('front.patients.store') }}",
                        data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == '0') {
                                $btn.prop('disabled', false).css('opacity', '1');
                                alert(response.message)
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function(err) {
                            $btn.prop('disabled', false).css('opacity', '1');
                            alert("Something went wrong. Please check the fields.");
                        }
                    });
                });

                this.root.on('click', this.editPatientHandle, function() {

                    const patientId = $(this).data('id');
                    editId = patientId;

                    $('#addPatientModalLabel').text('Edit Patient')
                    $('#sub_insurance_container').hide()
                    subInsuranceChoices.removeActiveItems();

                    const full_name = $(this).data('full_name');
                    const age = $(this).data('age');
                    const gender = $(this).data('gender');
                    const insurence_id = $(this).data('insurence_id');
                    const sub_insurence_id = $(this).data('sub_insurence_id');

                    genderChoices.setChoiceByValue(gender.toString());

                    mainInsuranceChoices.setChoiceByValue(insurence_id.toString());

                    // Set sub insurance
                    setTimeout(() => {
                        subInsuranceChoices.clearStore();
                        subInsuranceChoices.removeActiveItems();
                        const newChoices = subInsuranceList.filter(x => x.insurence_id == insurence_id).map(x => ({
                            label: x.title,
                            value: x.id,
                            selected: x.id == sub_insurence_id
                        }));
                        subInsuranceChoices.setChoices(newChoices, 'value', 'label', true);

                        if(newChoices.length){
                            $('#sub_insurance_container').show()
                        }

                    }, 200);

                    $('input[name="full_name"]').val(full_name)
                    $('input[name="age"]').val(age)
                    $('select[name="gender"]').val(gender)

                    // Set image preview
                    // if(patient.image){
                    //     $('#imagePreview').css('background-image', 'url(' + patient.image + ')');
                    //     $('#imagePreview').show();
                    // } else {
                    //     $('#imagePreview').hide();
                    // }

                    // $.ajax({
                    //     type: "GET",
                    //     data: { patientId },
                    //     success: function (response) {
                    //         const patient = response.data;
                    //         console.log(patient);
                    //         $('#full_name').val(patient.full_name);
                    //         $('#age').val(patient.age);
                    //         $('#phone').val(patient.phone);
                    //         $('#email').val(patient.email);
                    //         $('#address').val(patient.address);

                    //         // Set gender
                    //         $('input[name=gender][value='+patient.gender+']').prop('checked', true);

                    //         // Set insurance
                    //         mainInsuranceChoices.setChoiceByValue(patient.insurence_id);

                    //         // Set sub insurance
                    //         setTimeout(() => {
                    //             subInsuranceChoices.setChoiceByValue(patient.sub_insurence_id);
                    //         }, 1000);

                    //         // Set image preview
                    //         if(patient.image){
                    //             $('#imagePreview').css('background-image', 'url(' + patient.image + ')');
                    //             $('#imagePreview').show();
                    //         } else {
                    //             $('#imagePreview').hide();
                    //         }

                    //         // Show modal
                    //         // $('#formModal').modal('show');
                    //     }
                    // });
                });

                this.root.on('click', this.modalTrigger, function() {
                    // add new clicked now reset the form from the modal
                    editId = 0;
                    $(savedPatientInstance.form)[0].reset();
                    $('#addPatientModalLabel').text('Add New Patient')
                })

                this.root.on('click', this.deletehandle, function() {
                    const url = $(this).data('url')
                    $('form#deletePatientForm').attr('action', url)
                })

                $("#imagePreview").hide();
                $(document).on('change', '#imageUpload', function(event) {
                    const input = this;

                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                            $('#imagePreview').hide();
                            $('#imagePreview').fadeIn(650);

                            // Optional: Hide existing preview links if necessary
                            if ($('#previewLink').length) {
                                $('#previewLink').hide();
                            }
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                });
            }
        };


        subInsuranceList = $('#subIns').data('sub-insurance-list')

        savedPatientInstance.init();
    });
</script>
@endsection