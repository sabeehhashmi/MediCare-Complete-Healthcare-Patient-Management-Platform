@extends('front.template.layout')

@section('title', 'My Profile - MedNero')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/css/intlTelInput.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .iti {
        width: 100%;
    }

    .password-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #1baeff;
        margin-bottom: 20px;
    }

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

    .verified-badge {
        color: #28a745;
        font-size: 12px;
        margin-left: 5px;
    }

    .pending-badge {
        color: #ffc107;
        font-size: 12px;
        margin-left: 5px;
    }

    .timer {
        color: #1baeff;
        font-weight: bold;
    }
</style>
@endsection

@section('content')

<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">

            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">

                <div class="checkout-form-wrapper">

                    <div class="checkout-form-title">
                        <h4>My Profile</h4>
                    </div>

                    <div id="profileAlert"></div>

                    <form id="profileForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">

                            {{-- PROFILE IMAGE --}}
                            <div class="col-12 text-center">
                                <label for="profileImageUpload" style="cursor:pointer; position:relative;">
                                    <img src="{{ $user->user_img_url }}"
                                         class="profile-image"
                                         id="profileImagePreview">

                                    <div style="
                                        position:absolute;
                                        bottom:10px;
                                        right:10px;
                                        width:32px;
                                        height:32px;
                                        background:#1baeff;
                                        border-radius:50%;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        color:#fff;
                                    ">
                                        <i class="bx bx-camera"></i>
                                    </div>
                                </label>

                                <input type="file"
                                       id="profileImageUpload"
                                       name="profile_image"
                                       accept="image/*"
                                       hidden>
                            </div>

                            {{-- FIRST NAME --}}
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>First Name *</label>
                                    <input type="text"
                                           name="first_name"
                                           value="{{ $user->first_name }}"
                                           required>
                                </div>
                            </div>

                            {{-- LAST NAME --}}
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Last Name *</label>
                                    <input type="text"
                                           name="last_name"
                                           value="{{ $user->last_name }}"
                                           required>
                                </div>
                            </div>

                            {{-- GENDER --}}
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Gender *</label>

                                    <select name="gender" required>
                                        <option value="">Select Gender</option>

                                        <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="3" {{ $user->gender == 3 ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- DOB --}}
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Date Of Birth *</label>

                                    <input type="text"
                                           id="dob"
                                           name="dob"
                                           value="{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d-m-Y') : '' }}"
                                           required>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Email *</label>

                                    <div class="input-group">
                                        <input type="email"
                                               name="email"
                                               id="email"
                                               value="{{ $user->email }}"
                                               required>

                                        <button type="button"
                                                class="btn btn-outline-primary verify-email-btn d-none">
                                            Verify
                                        </button>
                                    </div>

                                    <small class="email-status">
                                        @if($user->email_verified_at)
                                            <span class="verified-badge">
                                                ✓ Verified
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>

                            {{-- PHONE --}}
                            <div class="col-md-6">
                                <div class="form-inner">

                                    <label>Phone Number *</label>

                                    <div class="input-group">

                                        <input type="text"
                                               id="profilePhone"
                                               name="phone"
                                               value="{{ $user->phone }}"
                                               maxlength="15"
                                               required>

                                        <input type="hidden"
                                               name="dial_code"
                                               id="dial_code_profile"
                                               value="{{ $user->dial_code }}">

                                        {{-- IMPORTANT --}}
                                        {{-- THIS BUTTON IS NOW ALWAYS AVAILABLE --}}
                                        <button type="button"
                                                class="btn btn-outline-primary verify-phone-btn d-none"
                                                id="verifyPhoneBtn">
                                            Update Phone
                                        </button>
                                    </div>

                                    <small class="phone-status d-none">

                                        @if($user->phone_verified_at)
                                            <span class="verified-badge">
                                                ✓ Verified
                                            </span>
                                        @else
                                            <span class="pending-badge">
                                                Pending Verification
                                            </span>
                                        @endif

                                    </small>

                                </div>
                            </div>

                            {{-- INSURANCE --}}
                            <div class="col-md-6">
                                <div class="form-inner">

                                    <label>My Insurance Network</label>

                                    <select name="insurence_id" id="insurence_id">
                                        <option value="">Select Insurance</option>

                                        @foreach($insurence_list as $insurance)
                                            <option value="{{ $insurance->id }}"
                                                {{ $user->insurence_id == $insurance->id ? 'selected' : '' }}>
                                                {{ $insurance->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- SUB INSURANCE --}}
                            <div class="col-md-6 sub-insurance-group"
                                 style="{{ $user->insurence_id ? 'display:block' : 'display:none' }}">

                                <div class="form-inner">

                                    <label>Sub Insurance</label>

                                    <select name="sub_insurence_id" id="sub_insurence_id">

                                        <option value="">Select Sub Insurance</option>

                                        @foreach($sub_insurence_list as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ $user->sub_insurence_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->title }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            {{-- DOCUMENT SECTION --}}
                            <div class="col-12">

                                <div class="password-section">

                                    <h5 class="mb-3">
                                        Government Identification Document
                                    </h5>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <div class="form-inner">

                                                <label>Document Type *</label>

                                                <select name="identification_type"
                                                        id="identification_type"
                                                        required>

                                                    <option value="">Select Type</option>

                                                    <option value="national_id"
                                                        {{ $user->identification_type == 'national_id' ? 'selected' : '' }}>
                                                        Emirates ID
                                                    </option>

                                                    <option value="passport"
                                                        {{ $user->identification_type == 'passport' ? 'selected' : '' }}>
                                                        Passport
                                                    </option>

                                                    <option value="driving_license"
                                                        {{ $user->identification_type == 'driving_license' ? 'selected' : '' }}>
                                                        Driving License
                                                    </option>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-inner">

                                                <label>Document Number *</label>

                                                <input type="text"
                                                       name="identification_number"
                                                       value="{{ $user->identification_number }}"
                                                       required>

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-inner">

                                                <label>Upload Document</label>

                                                <input type="file"
                                                       name="identification_document"
                                                       id="identification_document"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- PASSWORD --}}
                            <div class="col-12">

                                <div class="password-section">

                                    <h5 class="mb-3">
                                        Change Password
                                    </h5>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <div class="form-inner">
                                                <label>Current Password</label>

                                                <input type="password"
                                                       name="current_password">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-inner">
                                                <label>New Password</label>

                                                <input type="password"
                                                       name="new_password">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-inner">
                                                <label>Confirm Password</label>

                                                <input type="password"
                                                       name="new_password_confirmation">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- EMERGENCY --}}
                            <div class="col-12">
                                <div class="form-inner">

                                    <label>Emergency Contact Number</label>

                                    <input
                                        type="text"
                                        id="emergency_phone"
                                        class="w-100"
                                        oninput="this.value = this.value.replace(/\D/g, '').slice(0,12)"
                                    >

                                    <input
                                        type="hidden"
                                        name="emergency_information"
                                        id="emergency_full_number"
                                    >

                                    <small id="emergency_phone_error" class="text-danger"></small>

                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="col-12">

                                <button type="submit"
                                        class="primary-btn1"
                                        id="updateProfileBtn">

                                    <span>
                                        Update Profile
                                    </span>

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>

{{-- OTP MODAL --}}
<div class="modal fade"
     id="otpModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Verify Phone Number
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div id="otpModalAlert"></div>

                <p id="otpModalMessage"></p>

                <form id="otpModalForm">

                    @csrf

                    <div class="row g-3">

                        <div class="col-3">
                            <input type="text"
                                   class="otp-input"
                                   maxlength="1">
                        </div>

                        <div class="col-3">
                            <input type="text"
                                   class="otp-input"
                                   maxlength="1">
                        </div>

                        <div class="col-3">
                            <input type="text"
                                   class="otp-input"
                                   maxlength="1">
                        </div>

                        <div class="col-3">
                            <input type="text"
                                   class="otp-input"
                                   maxlength="1">
                        </div>

                        <div class="col-12 text-center mt-3">

                            <span class="timer" id="otpModalTimer">
                                02:00
                            </span>

                            <a href="#"
                               id="otpModalResend"
                               style="display:none;">
                                Resend OTP
                            </a>

                        </div>

                        <div class="col-12 mt-4">

                            <button type="submit"
                                    class="primary-btn1 w-100"
                                    id="verifyOtpModalBtn">

                                <span>
                                    Verify OTP
                                </span>

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    let itiProfile;
    let otpTimerInterval;

    $(document).ready(function () {

        flatpickr("#dob", {
            dateFormat: "d-m-Y",
            maxDate: "today"
        });

        // PHONE INPUT
        const input = document.querySelector("#profilePhone");

        itiProfile = window.intlTelInput(input, {
            separateDialCode: true,
            countrySearch: true,
            initialCountry: "ae",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        setTimeout(() => {

            let dialCode = "{{ $user->dial_code }}";
            let phone = "{{ $user->phone }}";

            if (dialCode && phone) {
                itiProfile.setNumber('+' + dialCode + phone);
            }

        }, 500);

        input.addEventListener('countrychange', function () {
            $('#dial_code_profile').val(
                itiProfile.getSelectedCountryData().dialCode
            );
        });

        // PHONE VERIFY
        $('#verifyPhoneBtn').click(function () {

            let phone = $('#profilePhone').val();
            let dialCode = itiProfile.getSelectedCountryData().dialCode;

            if (!phone) {
                toastr.error('Enter phone number');
                return;
            }

            $.ajax({

                url: '{{ route("front.profile.send.otp") }}',
                type: 'POST',

                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'phone',
                    value: phone,
                    dial_code: dialCode
                },

                beforeSend: function () {

                    $('#verifyPhoneBtn')
                        .prop('disabled', true)
                        .text('Sending...');
                },

                success: function (response) {

                    $('#verifyPhoneBtn')
                        .prop('disabled', false)
                        .text('Update Phone');

                    if (response.status == '1') {

                        $('#otpModalMessage').html(
                            'OTP sent to +' + dialCode + ' ' + phone
                        );

                        $('#otpModal').modal('show');

                        $('.otp-input').val('');

                        $('.otp-input:first').focus();

                        startOtpTimer();

                    } else {

                        toastr.error(response.message);

                    }

                },

                error: function () {

                    $('#verifyPhoneBtn')
                        .prop('disabled', false)
                        .text('Update Phone');

                    toastr.error('Failed to send OTP');

                }

            });

        });

        // OTP INPUT AUTO NEXT
        $('.otp-input').on('input', function () {

            this.value = this.value.replace(/\D/g, '');

            if (this.value.length == 1) {
                $(this).parent().next().find('.otp-input').focus();
            }

        });

        // VERIFY OTP
        $('#otpModalForm').submit(function (e) {

            e.preventDefault();

            let otp = [];
            let phone =$('#profilePhone').val();
            let dial_code =$('#dial_code_profile').val();

            $('.otp-input').each(function () {
                otp.push($(this).val());
            });

            $.ajax({

                url: '{{ route("front.profile.verify.phone.otp") }}',
                type: 'POST',

                data: {
                    _token: '{{ csrf_token() }}',
                    otp: otp,
                    phone: phone,
                    dial_code: dial_code
                },

                beforeSend: function () {

                    $('#verifyOtpModalBtn')
                        .prop('disabled', true)
                        .html('<span>Verifying...</span>');

                },

                success: function (response) {

                    $('#verifyOtpModalBtn')
                        .prop('disabled', false)
                        .html('<span>Verify OTP</span>');

                    if (response.status == '1') {

                        $('#otpModalAlert').html(`
                            <div class="alert alert-success">
                                ${response.message}
                            </div>
                        `);

                        $('.phone-status').html(`
                            <span class="verified-badge">
                                ✓ Phone Verified
                            </span>
                        `);

                        setTimeout(function () {
                            $('#otpModal').modal('hide');
                        }, 1000);

                    } else {

                        $('#otpModalAlert').html(`
                            <div class="alert alert-danger">
                                ${response.message}
                            </div>
                        `);

                    }

                }

            });

        });

        // PROFILE UPDATE
        $('#profileForm').submit(function (e) {

            e.preventDefault();

            // SET HIDDEN FIELD VALUE
            const fullNumber = emergencyIti.getNumber();
           

            $('#emergency_full_number').val($('#emergency_phone').val());

            console.log(fullNumber);
            console.log($('#emergency_full_number').val());

            $('#dial_code_profile').val(
                itiProfile.getSelectedCountryData().dialCode
            );

            let formData = new FormData(this);

            $.ajax({

                url: '{{ route("front.profile.update") }}',
                type: 'POST',
                data: formData,

                processData: false,
                contentType: false,

                beforeSend: function () {

                    $('#updateProfileBtn')
                        .prop('disabled', true)
                        .html('<span>Updating...</span>');

                },

               success: function (response) {

            $('#updateProfileBtn')
                .prop('disabled', false)
                .html('<span>Update Profile</span>');

            // SUCCESS
            if (response.status == '1') {

                $('#profileAlert').html(`
                    <div class="alert alert-success">
                        ${response.message}
                    </div>
                `);

                setTimeout(function () {
                    location.reload();
                }, 1500);

            }

            // PHONE OTP REQUIRED
            else if (response.status == '2') {

                $('#profileAlert').html('');

                // optional dynamic message
                $('#otpModalMessage').text(
                    response.message || 'Please verify OTP sent to your phone'
                );

                // clear old otp
                $('#otpModalInputs .otp-input').val('');

                // show modal
                $('#otpModal').modal('show');

                // focus first input
                $('#otpModalInputs .otp-input:first').focus();

                // start timer
                startOtpTimer();

            }

            // ERROR
            else {

                $('#profileAlert').html(`
                    <div class="alert alert-danger">
                        ${response.message}
                    </div>
                `);

            }

        },

                error: function () {

                    $('#updateProfileBtn')
                        .prop('disabled', false)
                        .html('<span>Update Profile</span>');

                    $('#profileAlert').html(`
                        <div class="alert alert-danger">
                            Something went wrong
                        </div>
                    `);

                }

            });

        });

        // PROFILE IMAGE
        $('#profileImageUpload').change(function (e) {

            let file = e.target.files[0];

            if (!file) return;

            let reader = new FileReader();

            reader.onload = function (e) {
                $('#profileImagePreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);

        });

    });

    // OTP TIMER
    function startOtpTimer() {

        clearInterval(otpTimerInterval);

        let seconds = 120;

        updateOtpTimer(seconds);

        otpTimerInterval = setInterval(function () {

            seconds--;

            updateOtpTimer(seconds);

            if (seconds <= 0) {

                clearInterval(otpTimerInterval);

                $('#otpModalTimer').hide();

                $('#otpModalResend').show();

            }

        }, 1000);

    }

    function updateOtpTimer(seconds) {

        let minutes = Math.floor(seconds / 60);
        let remain = seconds % 60;

        $('#otpModalTimer').text(
            `${String(minutes).padStart(2, '0')}:${String(remain).padStart(2, '0')}`
        );

    }

</script>

<script>
let emergencyIti;

$(document).ready(function () {

    const emergencyInput = document.querySelector("#emergency_phone");

    emergencyIti = window.intlTelInput(emergencyInput, {

        initialCountry: "auto",

        separateDialCode: true,

        nationalMode: false,

        utilsScript:
            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",

        geoIpLookup: function(callback) {

            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("ae"));
        }
    });

    // SET EXISTING NUMBER
    let existingNumber = "{{ $user->emergency_information }}";

    if(existingNumber){
        emergencyIti.setNumber(existingNumber);
    }

    // VALIDATE ON INPUT
    $('#emergency_phone').on('keyup change countrychange', function () {

        validateEmergencyPhone();

    });

    // FORM SUBMIT
    $('#profileForm').submit(function (e) {

        if (!validateEmergencyPhone()) {

            e.preventDefault();
            return false;
        }

        // SAVE FULL NUMBER
        $('#emergency_full_number').val(
            emergencyIti.getNumber()
        );

    });

});

function validateEmergencyPhone() {

    const phone = $('#emergency_phone').val().trim();

    if (phone === '') {

        $('#emergency_phone_error').text('');
        return true;
    }

    // Full international number
    const fullNumber = emergencyIti.getNumber();

    // Selected country dial code
    const dialCode = emergencyIti.getSelectedCountryData().dialCode;

    // Remove + and dial code
    let numberWithoutCode = fullNumber.replace('+' + dialCode, '');

    // Keep only digits
    numberWithoutCode = numberWithoutCode.replace(/\D/g, '');

    // Validate length after dial code
    if (
        numberWithoutCode.length < 9 ||
        numberWithoutCode.length > 12
    ) {

        $('#emergency_phone_error')
            .text('Phone number must be 9 to 12 digits');

        return false;
    }

    $('#emergency_phone_error').text('');

    return true;
}
</script>

@endsection