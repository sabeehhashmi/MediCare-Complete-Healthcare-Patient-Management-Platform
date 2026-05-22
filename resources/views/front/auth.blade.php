@extends('front.template.layout')

@section('title', 'Login / Register - MedNero')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/css/intlTelInput.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <style>
        .otp-input {
            width: 100%;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .otp-input:focus {
            border-color: #1baeff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(27,174,255,0.2);
        }
        .iti {
            width: 100%;
        }
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .success-message {
            color: #28a745;
            font-size: 14px;
            margin-top: 5px;
        }
        .timer {
            color: #1baeff;
            font-weight: bold;
        }
        .sub-insurance-group {
            display: none;
        }
        .password-requirements {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        a.link {
            color: #1baeff;
            text-decoration: underline;
        }

        a.link:hover {
            color: #000;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
    </style>
@endsection

@section('content')
<div class="contact-page pt-100 pb-100" style="background: #1baeff;">
    <div class="container">
        <div class="contact-form">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6 col-md-8">

                    <!-- Login Section -->
                    <div id="loginSec" class="contact-form-wrap px-3 py-3 pt-5">
                        <div class="section-title text-center mb-20">
                            <h3>Let's Start Here</h3>
                            <p>To Login or Create an Account please enter your mobile number</p>
                        </div>
                        
                        <div id="loginAlert"></div>
                        
                        <form id="loginForm">
                            @csrf
                            <input type="hidden" name="guest_booking_id" id="guest_booking_id" value="{{$guestBookingId ?? null}}">
                             
                            <div class="row g-4 mb-30">
                                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Mobile OTP</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Email & Password</button>
                                    </li>
                                </ul>
                                
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="col-md-12">
                                            <div class="form-inner">
                                                <label>Phone Number</label>
                                                <input class="w-100" type="number" id="country" name="phone" placeholder="567 *** ***"  maxlength="10" oninput="this.value = this.value.replace(/\D/g, '')">
                                                <input type="hidden" name="dial_code" id="dial_code">
                                                <input type="hidden" name="login_type" value="mobile">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-inner">
                                                <label>Email Address</label>
                                                <input type="email" name="email" placeholder="info@example.com" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-inner">
                                                <label>Password</label>
                                                <input type="password" name="password" placeholder="********" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree_terms" id="contactCheck22" required>
                                <label class="form-check-label" for="contactCheck22">
                                    I agree with your 
                                </label>
                                <a class="link" href="{{ route('front.privacy-policy') }}" target="_blank">privacy policy</a> & 
                                <a class="link" href="{{ route('front.terms-conditions') }}" target="_blank">terms & conditions</a>.
                            </div>
                            
                            <button type="submit" class="primary-btn1 w-100" id="loginBtn">
                                <span>
                                    Login Now
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                <span>
                                    Login Now
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>
                            
                            <button type="button" onclick="showCreateAccount()" class="primary-btn1 btn-outline mt-3 w-100">
                                <span>
                                    Sign Up
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                <span>
                                    Sign Up
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- OTP Verification Section -->
                    <div id="otpSec" style="display:none;" class="contact-form-wrap px-3 py-3 pt-5">
                        <div class="section-title text-center mb-20">
                            <h3>Verify OTP</h3>
                            <p id="otpMessage">To verify your mobile number, please enter the OTP that is sent to your mobile device</p>
                        </div>
                        
                        <div id="otpAlert"></div>
                        
                        <form id="otpForm">
                            @csrf
                            <input type="hidden" name="guest_booking_id" id="guest_booking_id" value="{{$guestBookingId ?? null}}">
                            <div class="row g-4 mb-30">
                                <div class="col-md-12">
                                    <div class="row" id="otpInputs">
                                        <div class="col-3">
                                            <div class="form-inner">
                                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-inner">
                                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-inner">
                                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-inner">
                                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-inner2 text-center">
                                        <span class="timer" id="timer">02:00</span>
                                        <a href="#" class="link" id="resendOtp" style="display:none;">Resend OTP</a>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="primary-btn1 w-100" id="verifyOtpBtn">
                                <span>
                                    Verify & Continue
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                 <span>
                                    Verify & Continue
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>

                            <button type="button" onclick="showLogin()" class="primary-btn1 btn-outline mt-3 w-100">
                                <span>
                                    Back to Login
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                 <span>
                                    Back to Login
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Registration Section -->
                    <div id="createSec" style="display:none;" class="contact-form-wrap px-3 py-3 pt-5">
                        <div class="section-title text-center mb-20">
                            <h3>Create An Account</h3>
                            <p>Please complete your registration details</p>
                        </div>
                        
                        <div id="registerAlert"></div>
                        
                        <form id="registerForm" >
                            @csrf
                            <div class="row g-4 mb-30">
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>First Name *</label>
                                        <input type="text" name="first_name" placeholder="Washington" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Last Name *</label>
                                        <input type="text" name="last_name" placeholder="Mongla" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Gender *</label>
                                        <select name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                            <option value="3">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Date of Birth *</label>
                                        <input class="w-100" type="text" name="dob" id="dob" placeholder="DD-MM-YYYY" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Email Address</label>
                                        <input type="email" name="email" placeholder="info@example.com">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Phone Number *</label>
                                        <input class="w-100" type="text" id="countryRegister" name="phone" placeholder="567 *** ***" required maxlength="10" oninput="this.value = this.value.replace(/\D/g, '')">
                                        <input type="hidden" name="dial_code" id="dial_code_register">
                                    </div>
                                </div>

                                <!-- Add this after the Phone Number field and before My Insurance Network -->
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Identification Document Type <span class="text-danger">*</span></label>
                                        <select name="identification_type" id="identification_type" required>
                                            <option value="">Select Document Type</option>
                                            <option value="national_id">National ID (Emirates ID)</option>
                                            <option value="passport">Passport</option>
                                            <option value="driving_license">Driving License</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Identification Document Number <span class="text-danger">*</span></label>
                                        <input type="text" id="identification_number" name="identification_number" placeholder="Enter document number" required>
                                        <small class="text-muted">e.g., 784-XXXX-XXXXXXX-1</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Upload Identification Document <span class="text-danger">*</span></label>
                                        <input type="file" name="identification_document" id="identification_document" 
                                            accept=".jpg,.jpeg,.png,.pdf" required>
                                        <small class="text-muted">Allowed formats: JPG, PNG, PDF (Max 5MB)</small>
                                        <div id="documentPreview" style="margin-top: 10px; display: none;">
                                            <img id="docPreview" src="#" alt="Preview" style="max-width: 150px; max-height: 100px;">
                                            <div id="pdfPreview" style="display: none;">
                                                <i class="bx bxs-file-pdf" style="font-size: 40px; color: #dc3545;"></i>
                                                <span>PDF Document</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>Password</label>
                                        <input type="password" name="password" placeholder="********" minlength="8">
                                        <div class="password-requirements">Minimum 8 characters (optional)</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-inner">
                                        <label>My Insurance Network</label>
                                        <select name="insurence_id" id="insurence_id" class="ignore-all">
                                            <option value="">Select Insurance</option>
                                            @foreach($insurence_list as $insurance)
                                                <option value="{{ $insurance->id }}">{{ $insurance->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 sub-insurance-group">
                                    <div class="form-inner">
                                        <label>My Sub Insurance Network</label>
                                        <select name="sub_insurence_id" id="sub_insurence_id" class="ignore-all">
                                            <option value="">Select Sub Insurance</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 pb-3">
                                    <div class="form-inner2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="agree_terms" id="contactCheck22" required>
                                            <label class="form-check-label" for="contactCheck22">
                                                I agree with your 
                                            </label>
                                            <a class="link" href="{{ route('front.privacy-policy') }}" target="_blank">privacy policy</a> & 
                                            <a class="link" href="{{ route('front.terms-conditions') }}" target="_blank">terms & conditions</a>.
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="primary-btn1 w-100" id="registerBtn">
                                <span>
                                    Create an Account
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                 <span>
                                    Create an Account
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>

                            <button type="button" onclick="showLogin()" class="primary-btn1 btn-outline mt-3 w-100">
                                <span>
                                    Already have an account? Login
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                                <span>
                                    Already have an account? Login
                                    <svg width="10" height="10" viewBox="0 0 10 10">
                                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    
    <script>
        let iti, itiRegister;
        let timerInterval;
        let otpTimer = 120; // 2 minutes in seconds
        
        $(document).ready(function() {

            const mainInsuranceSelectHandle = document.querySelector('#insurence_id');
            const mainInsuranceChoices = new Choices(mainInsuranceSelectHandle, {
                position: 'top',
                shouldSort: false,
            });

            const subInsuranceSelectHandle = document.querySelector('#sub_insurence_id');
            const subInsuranceChoices = new Choices(subInsuranceSelectHandle, {
                position: 'top',
                shouldSort: false,
            });


            // Initialize Datepicker
            flatpickr("#dob", {
                dateFormat: "d-m-Y",
                maxDate: "today"
            });

            // Initialize Intl-Tel-Input for login
            const input = document.querySelector("#country");
            if (input) {
                iti = window.intlTelInput(input, {
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    separateDialCode: true,
                //    initialCountry: "ae",
                    countrySearch: true,
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback(data.country_code))
                            .catch(() => callback("ae"));
                    }
                });
                
                // Set dial code on change
                input.addEventListener('countrychange', function() {
                    $('#dial_code').val(iti.getSelectedCountryData().dialCode);
                });
                
                // Set initial dial code
                setTimeout(() => {
                    $('#dial_code').val(iti.getSelectedCountryData().dialCode);
                }, 500);
            }

            // Initialize Intl-Tel-Input for registration
            const inputRegister = document.querySelector("#countryRegister");
            if (inputRegister) {
                itiRegister = window.intlTelInput(inputRegister, {
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    separateDialCode: true,
                //    initialCountry: "ae",
                    countrySearch: true,
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback(data.country_code))
                            .catch(() => callback("ae"));
                    }
                });
                
                // Set dial code on change
                inputRegister.addEventListener('countrychange', function() {
                    $('#dial_code_register').val(itiRegister.getSelectedCountryData().dialCode);
                });
                
                // Set initial dial code
                setTimeout(() => {
                    $('#dial_code_register').val(itiRegister.getSelectedCountryData().dialCode);
                }, 500);
            }

            // Login Form Submit
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                let activeTab = $('#pills-tab .nav-link.active').attr('id');
                var guest_booking_id = $('#guest_booking_id').val();
                
                if (activeTab === 'pills-home-tab') {
                    // Mobile OTP Login
                    // if (!iti.isValidNumber()) {
                    //     $('#loginAlert').html('<div class="alert alert-danger">Please enter a valid phone number</div>');
                    //     return;
                    // }
                    
                    $('#dial_code').val(iti.getSelectedCountryData().dialCode);
                    
                    $.ajax({
                        url: '{{ route("front.send.otp") }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        beforeSend: function() {
                            $('#loginBtn').prop('disabled', true).html('<span>Sending OTP...</span> <span>Sending OTP...</span>');
                            $('#loginAlert').html('');
                        },
                        success: function(response) {
                            $('#loginBtn').prop('disabled', false).html('<span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                            
                            if (response.status == '1') {
                                $('#loginSec, #createSec').hide();
                                $('#otpSec').show();
                                window.scrollTo(0, 0);
                                
                                let message = response.is_new_user ? 
                                    'A verification code has been sent to your mobile number' : 
                                    'Welcome back! Please enter the OTP sent to your mobile number';
                                $('#otpMessage').text(message);
                                
                                startOtpTimer();
                                
                                // Clear OTP inputs
                                $('.otp-input').val('');
                                $('.otp-input').first().focus();
                            } else {
                                $('#loginAlert').html('<div class="alert alert-danger">' + response.message + '</div>');
                            }
                        },
                        error: function() {
                            $('#loginBtn').prop('disabled', false).html('<span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                            $('#loginAlert').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        }
                    });
                } else {
                    // Email & Password Login
                    $.ajax({
                        url: '{{ route("front.login.email") }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        beforeSend: function() {
                            $('#loginBtn').prop('disabled', true).html('<span>Logging in...</span> <span>Logging in...</span>');
                            $('#loginAlert').html('');
                        },
                        success: function(response) {
                            $('#loginBtn').prop('disabled', false).html('<span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                            
                            if (response.status == '1') {

    // ✅ EMAIL OTP FLOW
                                if(response.otp_required){

                                    window.emailLoginUserId = response.user_id;

                                    $('#loginSec, #createSec').hide();
                                    $('#otpSec').show();

                                    $('#otpMessage').text(
                                        'A verification OTP has been sent to your email address'
                                    );

                                    startOtpTimer();

                                    $('.otp-input').val('');
                                    $('.otp-input').first().focus();

                                    return;
                                }

                            } else {
                                $('#loginAlert').html('<div class="alert alert-danger">' + response.message + '</div>');
                            }
                        },
                        error: function() {
                            $('#loginBtn').prop('disabled', false).html('<span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Login Now <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                            $('#loginAlert').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        }
                    });
                }
            });

            // OTP Input Handling with auto focus
            $('.otp-input').on('input', function () {
                // allow only numbers
                this.value = this.value.replace(/[^0-9]/g, '');

                if (this.value.length === 1) {
                    $(this).closest('.col-3').next().find('.otp-input').focus();
                }
            });

            $('.otp-input').on('keydown', function (e) {
                if (e.key === 'Backspace' && this.value === '') {
                    $(this).closest('.col-3').prev().find('.otp-input').focus();
                }
            });
            $('.otp-input').on('paste', function (e) {
                let pasteData = e.originalEvent.clipboardData.getData('text').replace(/\D/g, '');
                let inputs = $('.otp-input');

                inputs.each(function (i) {
                    $(this).val(pasteData[i] || '');
                });

                inputs.eq(pasteData.length).focus();
            });

            // OTP Form Submit
            $('#otpForm').on('submit', function(e) {
                e.preventDefault();
                
                let otp = [];
                let isValid = true;
                
                $('.otp-input').each(function() {
                    let val = $(this).val();
                    if (!val || val.length === 0) {
                        isValid = false;
                    }
                    otp.push(val);
                });
                
                if (!isValid) {
                    $('#otpAlert').html('<div class="alert alert-danger">Please enter complete OTP</div>');
                    toastr.warning('Please enter complete OTP');
                    return;
                }
                var guest_booking_id = $('#guest_booking_id').val();
                let verifyRoute = '{{ route("front.verify.otp") }}';
                    let verifyData = {
                        _token: '{{ csrf_token() }}',
                        otp: otp,
                        guest_booking_id: guest_booking_id
                    };

                    // email login otp
                    if(window.emailLoginUserId){

                        verifyRoute = '{{ route("front.verify.email.login.otp") }}';

                        verifyData = {
                            _token: '{{ csrf_token() }}',
                            user_id: window.emailLoginUserId,
                            otp: otp.join('')
                        };
                    }
                $.ajax({
                   url: verifyRoute,
                    data: verifyData,
                    type: 'POST',
                 
                    beforeSend: function() {
                        $('#verifyOtpBtn').prop('disabled', true).html('<span>Verifying...</span> <span>Verifying...</span>');
                        $('#otpAlert').html('');
                    },
                    success: function(response) {
                        if (response.status == '1') {
                            
                            if (response.redirect === 'registration') {
                                // Pre-fill phone number in registration form
                                if (itiRegister && response.phone) {
                                    itiRegister.setNumber('+' + response.dial_code + response.phone);
                                    $('#countryRegister').prop('readonly', true);
                                }
                                
                                $('#otpSec').hide();
                                $('#createSec').show();
                                window.scrollTo(0, 0);
                            } else {
                                if(response.guest_booking){
                            const url = `{{ url('front/guest-booking-overview') }}?guest_booking_id=${encodeURIComponent(guest_booking_id)}`;
                                localStorage.removeItem('guest_booking_id');
                                setTimeout(function () {
                                    
                                    window.location.href = url;
                                }, 1500);
                            }
                            else{
                                window.location.href = response.redirect;
                            }
                                
                            }
                        } else {
                            $('#otpAlert').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                        $('#verifyOtpBtn').prop('disabled', false).html('<span>Verify & Continue <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Verify & Continue <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                    },
                    error: function() {
                        $('#verifyOtpBtn').prop('disabled', false).html('<span>Verify & Continue <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span> <span>Verify & Continue <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                        $('#otpAlert').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    }
                });
            });

            // Resend OTP
            $('#resendOtp').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '{{ route("front.resend.otp") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == '1') {
                            $('#otpAlert').html('<div class="alert alert-success">OTP resent successfully</div>');
                            resetOtpTimer();
                        } else {
                            $('#otpAlert').html('<div class="alert alert-danger">' + response.message + '</div>');
                            $('html, body').animate({
                                scrollTop: $('#otpAlert').offset().top - 100
                            }, 600);
                        }
                    }
                });
            });




            mainInsuranceSelectHandle.addEventListener('change', function(evt){

                const insurence_id = evt.detail.value;

                subInsuranceChoices.removeActiveItems();
                $('.sub-insurance-group').hide()

                $.ajax({
                    url: '{{ route("front.get.sub.insurances", "") }}/' + insurence_id,
                    type: "GET",
                    success: function (response) {

                        if(response.length){
                            $('.sub-insurance-group').show()
                            subInsuranceChoices.clearStore();
                            const newChoices = response.map(x => ({
                                label: x.title,
                                value: x.id
                            }));
                            subInsuranceChoices.setChoices(newChoices, 'value', 'label', true);
                        }

                    }
                });

            })

            // Insurance change - load sub insurances
            // $('#insurence_id').on('change', function() {
            //     let insurence_id = $(this).val();
            //     if (insurence_id) {
            //         $.ajax({
            //             url: '{{ route("front.get.sub.insurances", "") }}/' + insurence_id,
            //             type: 'GET',
            //             success: function(data) {
            //                 let options = '<option value="">Select Sub Insurance</option>';
            //                 data.forEach(function(item) {
            //                     options += '<option value="' + item.id + '">' + item.title + '</option>';
            //                 });
            //                 $('#sub_insurence_id').html(options);
            //                 $('#sub_insurence_id').niceSelect('update');
            //                 $('.sub-insurance-group').show();
            //             }
            //         });
            //     } else {
            //         $('.sub-insurance-group').hide();
            //     }
            // });

            // Registration Form Submit
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                let errorMessages = [];
                
                if (!$('#identification_type').val()) {
                    isValid = false;
                    errorMessages.push('Please select document type');
                    $('#identification_type').addClass('is-invalid');
                }
                
                if (!$('#identification_number').val()) {
                    isValid = false;
                    errorMessages.push('Please enter document number');
                    $('#identification_number').addClass('is-invalid');
                }
                
                const documentFile = $('#identification_document')[0].files[0];
                if (!documentFile) {
                    isValid = false;
                    errorMessages.push('Please upload identification document');
                    $('#identification_document').addClass('is-invalid');
                }
                
                if (!isValid) {
                    let errorHtml = '<div class="alert alert-danger">';
                    errorMessages.forEach(msg => {
                        errorHtml += '<p>' + msg + '</p>';
                    });
                    errorHtml += '</div>';
                    $('#registerAlert').html(errorHtml);
                    $('html, body').animate({
                        scrollTop: $('#registerAlert').offset().top - 100
                    }, 600);
                    return;
                }
                
                // Set dial code for registration
                if (itiRegister) {
                    $('#dial_code_register').val(itiRegister.getSelectedCountryData().dialCode);
                }
                
                // Create FormData object
                let formData = new FormData(this);
                
                // Debug: Log form data
                console.log('Submitting registration form');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                



                // Set dial code for registration
                if (itiRegister) {
                    $('#dial_code_register').val(itiRegister.getSelectedCountryData().dialCode);
                }
                
                $.ajax({
                    url: '{{ route("front.register") }}',
                      type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,  // CRITICAL: Don't process the data
                    contentType: false,  // CRITICAL: Let browser set the content type with boundary
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#registerBtn').prop('disabled', true).html('<span>Creating Account...</span>');
                        $('#registerAlert').html('');
                    },
                    success: function(response) {
                        if (response.status == '1') {
                            window.location.href = response.redirect;
                        } else {
                            let errorHtml = '<div class="alert alert-danger">';
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    errorHtml += '<p>' + value + '</p>';
                                });
                            } else {
                                errorHtml += response.message;
                            }
                            errorHtml += '</div>';
                            $('#registerAlert').html(errorHtml);
                            $('html, body').animate({
                                scrollTop: $('#registerAlert').offset().top - 100
                            }, 600);
                        }
                        $('#registerBtn').prop('disabled', false).html('<span>Create an Account <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                    },
                    error: function() {
                        $('#registerBtn').prop('disabled', false).html('<span>Create an Account <svg width="10" height="10" viewBox="0 0 10 10"><path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path></svg></span>');
                        $('#registerAlert').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        $('html, body').animate({
                                scrollTop: $('#registerAlert').offset().top - 100
                            }, 600);
                    }
                });
            });
        });

        // Navigation Functions
        window.showOtpVer = function() {
            $('#loginSec, #createSec').hide();
            $('#otpSec').show();
            window.scrollTo(0, 0);
            startOtpTimer();
            $('.otp-input').first().focus();
        };

        window.showCreateAccount = function() {
            $('#loginSec, #otpSec').hide();
            $('#createSec').show();
            window.scrollTo(0, 0);
        };

        window.showLogin = function() {
            $('#otpSec, #createSec').hide();
            $('#loginSec').show();
            window.scrollTo(0, 0);
            clearInterval(timerInterval);
        };

        // OTP Timer Functions
        function startOtpTimer() {
            otpTimer = 120;
            updateTimerDisplay();
            
            timerInterval = setInterval(function() {
                otpTimer--;
                updateTimerDisplay();
                
                if (otpTimer <= 0) {
                    clearInterval(timerInterval);
                    $('#timer').hide();
                    $('#resendOtp').show();
                }
            }, 1000);
        }

        function resetOtpTimer() {
            clearInterval(timerInterval);
            otpTimer = 120;
            updateTimerDisplay();
            $('#timer').show();
            $('#resendOtp').hide();
            startOtpTimer();
        }

        function updateTimerDisplay() {
            let minutes = Math.floor(otpTimer / 60);
            let seconds = otpTimer % 60;
            $('#timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
        }


        // Document preview and validation
        $('#identification_document').on('change', function() {
            const file = this.files[0];
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (file) {
                if (!validTypes.includes(file.type)) {
                    toastr.error('Please upload a valid file type (JPG, PNG, or PDF)');
                    $(this).val('');
                    $('#documentPreview').hide();
                    return;
                }
                
                if (file.size > maxSize) {
                    toastr.error('File size must be less than 5MB');
                    $(this).val('');
                    $('#documentPreview').hide();
                    return;
                }
                
                $('#documentPreview').show();
                
                if (file.type === 'application/pdf') {
                    $('#docPreview').hide();
                    $('#pdfPreview').show();
                } else {
                    $('#pdfPreview').hide();
                    $('#docPreview').show();
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#docPreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // Add validation for document type and number
        $('#identification_type, #identification_number').on('change keyup', function() {
            if ($('#identification_type').val() && $('#identification_number').val()) {
                $(this).removeClass('is-invalid');
            }
        });
    </script>
@endsection