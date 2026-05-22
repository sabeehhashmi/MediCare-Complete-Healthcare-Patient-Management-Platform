<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Doctor in Dubai | Mednero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{URL::asset('web/images/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{URL::asset('web/images/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{URL::asset('web/images/favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{URL::asset('web/images/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{URL::asset('web/images/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{URL::asset('web/images/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{URL::asset('web/images/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{URL::asset('web/images/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{URL::asset('web/images/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{URL::asset('web/images/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('web/images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{URL::asset('web/images/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{URL::asset('web/images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{URL::asset('web/images/favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{URL::asset('web/images/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    <!-- <link rel="shortcut icon" href="images/favicon.ico"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- plugin css -->
    <link href="{{URL::asset('web/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- swiper css -->
    <link rel="stylesheet" href="{{URL::asset('web/libs/swiper/swiper-bundle.min.css')}}">
    <!-- nouisliderribute css -->
    <link rel="stylesheet" href="{{URL::asset('web/libs/nouislider/nouislider.min.css')}}">

    <!-- Bootstrap Css -->
    <link href="{{URL::asset('web/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons Css -->
    <link href="{{URL::asset('web/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Flatpicker Css -->
    <link href="{{URL::asset('web/css/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{URL::asset('web//css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('web//css/custom-web.css')}}" id="style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">

    </head>

    
    <body>

    <!-- <body data-layout="horizontal"> -->

    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">

                        <div class="card">
                            <div class="card-body p-4"> 
                                <input type="hidden" name="guest_booking_id" id="guest_booking_id" value="{{$guestBookingId ?? null}}">
                                <div class="mb-4 pb-2">
                                    <a href="{{url('/website')}}" class="d-block auth-logo">
                                        <img src="{{URL::asset('web')}}/images/Mednero.svg" alt="" height="60" class="auth-logo-dark me-start">
                                        <img src="{{URL::asset('web')}}/images/Mednero.svg" alt="" height="60" class="auth-logo-light me-start">
                                    </a>
                                </div>
                                <div class="text-center mt-2">
                                    <h5 class="text-primary mx-auto" style="max-width: 320px;">To Login or Create an Account 
                                        please enter your mobile number</h5>
                                    <!-- <p class="text-muted">Sign in to continue to webadmin.</p> -->
                                </div>
                                <div class="p-2 mt-4">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills nav-center justify-content-center px-3" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-phone"></i></span>
                                                <span class="d-none d-sm-block">Mobile</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">Email</span> 
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="home-1" role="tabpanel">
                                            <form action="#" method="post">
        
                                                <div class="mb-3 mb-md-4">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative input-custom-icon">
                                                            <input type="hidden" id="dial_code" name="dial_code">
                                                            <input required type="text" class="form-control no-zero-input numberonly" minlength="7" maxlength="12" id="phone" name="phone" placeholder="Enter Phone Number" />
                                                            <!-- <input type="text" class="form-control" id="username" placeholder="Mobile number"> -->
                                                         <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <button class="btn btn-primary w-100 waves-effect waves-light" id="phone-login-button" type="button">Log In</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="profile-1" role="tabpanel">
                                            <form action="#" method="post" id="email-form">
        
                                                <div class="mb-3 mb-md-4">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative input-custom-icon">
                                                        <input type="text" class="form-control" id="email" placeholder="Email address">
                                                         <span class="bx bx-user"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <button class="btn btn-primary w-100 waves-effect waves-light" id="email-login-button" type="button">Log In</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-4 text-center">
                                                <h5 class="font-size-14 mb-3 mt-2 title"><a href="{{route('patient.signup')}}"> Signup</a> </h5>
                                            </div>
        
                                        <div class="mt-4 text-center">
                                            
                                            <div class="signin-other-title">
                                                
                                                <h5 class="font-size-14 mb-3 mt-2 title"> Social Login </h5>
                                            </div>
            
                                            <ul class="list-inline mt-2 social-list">
                                                <li class="list-inline-item">
                                                    <a href="{{route('google_login')}}" class="social-list-item bg-white text-white border">
                                                        <i class="icn icn-google"></i>
                                                    </a>
                                                </li>
                                                <!-- <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-dark text-white border-dark">
                                                        <i class="icn icn-apple"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-white text-white border">
                                                        <i class="icn icn-pass"></i>
                                                    </a>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </div>
                                    
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-sm-12 text-center text-white">
                        <script>document.write(new Date().getFullYear())</script> © Mednero.
                    </div>
                </div>

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->

    <!-- Add New Event MODAL -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content bg-prime-light">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Verify OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="verify-otp-form" class="custom-form">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="user_email" id="user_email">
                    <input type="hidden" name="user_phone" id="user_phone">
                    <input type="hidden" name="user_dialCode" id="user_dialCode">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit1-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit2-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit3-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit4-input" name="otp[]">
                                </div>
                            </div>
                        </div>
                        <p class="text-body">To verify your mobile number, please enter the OTP that is sent to your mobile device.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary fw-bold" style="width: 120px;" id="resend-otp">Resend</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal-->

        <!-- JAVASCRIPT -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{URL::asset('web')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/metismenujs/metismenujs.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/simplebar/simplebar.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/eva-icons/eva.min.js"></script>
        <script src="{{URL::asset('web')}}/js/pages/pass-addon.init.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
        <!-- two-step-verification js -->
        <script src="{{URL::asset('web')}}/js/pages/two-step-verification.init.js"></script>
        <script src="{{ URL::asset('js/app.js') }}"></script>
        <script>
            $(".numberonly").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                //var er=$(this).attr('id')+"errmsg";
                //var myElem = document.getElementById(er);
                //if error span id is not same as id of current textbox +errmsg displays this
            
                
                //$("#"+er).html("Digits Only").show().fadeOut("slow");
                return false;
            }
        });
        $('.no-zero-input').on('keydown', function(event) {
                    if($(this).val().length == 0){
                        if (event.key === '0') {
                            event.preventDefault();
                        }
                    }
                });
            document.addEventListener("DOMContentLoaded", function() {
                const guestBookingId = document.getElementById('guest_booking_id').value;
                if (guestBookingId) {
                    localStorage.setItem('guest_booking_id', guestBookingId);
                }
            });

       $(document).ready(function () {

        @if(session('message'))
            App.alert('{{ session("message") }}', 'Fail!', 'error');
        @endif
            // Initialize phone input
            const inputTel = document.querySelector("#phone");
            const ph = window.intlTelInput(inputTel, {
            //    initialCountry: '{{INIT_PHONE_C_CODE}}',
                geoIpLookup: "auto",
                separateDialCode: true,
             //   onlyCountries: ['ae'], // Restrict to UAE only
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
            });

            inputTel.addEventListener("input", function () {
                var dialCode = ph.getSelectedCountryData().dialCode;
                $('#dial_code').val(dialCode);
            });
            const phone_login = () => {
                var phone = $('#phone').val();
                if(phone.length < 5 || phone.length  > 12){
                    App.alert('Please Fill a valid phone number', 'Fail!', 'error');
                    return false;
                }
                var dial_code = $('#dial_code').val();
                var $button = $(this);
                $button.text('Processing').attr('disabled', true);
                $.ajax({
                    url: "{{url('web/sign_in_with_phone_web')}}",
                    type: 'POST',
                    data: {
                        phone: phone,
                        dial_code: dial_code,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $button.text('Log In').attr('disabled', false);
                        if (response.status) {
                            if (response.status == '1') {
                                console.log();
                                $('#user_email').val('');
                                $('#user_id').val(response.oData.id);
                                $('#user_phone').val(response.oData.phone);
                                $('#user_dialCode').val(response.oData.dial_code);
                                $('#event-modal').modal('show');
                            } else {
                                $('#event-modal').modal('hide');
                                App.alert(response.message || 'Failed to login', 'Fail!', 'error');
                                if (response.status == '3') {
                                    setTimeout(function () {
                                        window.location.href = "{{url('/website/patient-signup')}}?phone=" + phone + "&dial_code=" + dial_code;
                                    }, 1500);
                                }
                            }
                        } else {
                            App.alert(response.message || 'Failed to login', 'Fail!', 'error');
                            if (response.status == '3') {
                                $('#event-modal').modal('hide');
                                setTimeout(function () {
                                    window.location.href = "{{url('/website/patient-signup')}}?phone=" + phone + "&dial_code=" + dial_code;
                                }, 1500);
                            }
                        }
                    },
                    error: function () {
                        $button.text('Log In').attr('disabled', false);
                        alert('An error occurred while processing your request.');
                    }
                });
            }
            // Handle phone login
            $('#phone-login-button').click(function (e) {
                e.preventDefault();
                phone_login();
            });
            $('#phone-form').submit(function(e){
                e.preventDefault();
                phone_login();
            });
            const email_login = ()=>{
                var email = $('#email').val();
                var $button = $(this);
                $button.text('Processing').attr('disabled', true);
                $.ajax({
                    url: "{{url('web/email_login_web')}}",
                    type: 'POST',
                    data: {
                        email: email,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $button.text('Log In').attr('disabled', false);
                        if (response.status) {
                            // console.log(response, 'response');
                            if (response.status == '1') {
                                $('#user_email').val(response.oData.email);
                                $('#user_id').val(response.oData.id);
                                $('#user_phone').val('');
                                $('#event-modal').modal('show');
                            } else {
                                $('#event-modal').modal('hide');
                                App.alert(response.message || 'Failed to login', 'Fail!', 'error');
                                if (response.status == '3') {
                                    setTimeout(function () {
                                        window.location.href = "{{url('/website/patient-signup')}}?email=" + email;
                                    }, 1500);
                                }
                            }
                        } else {
                            App.alert(response.message || 'Failed to login', 'Fail!', 'error');
                            $('#event-modal').modal('hide');
                            if (response.status == '3') {
                                setTimeout(function () {
                                    window.location.href = "{{url('/website/patient-signup')}}?email=" + email;
                                }, 1500);
                            }
                        }
                    },
                    error: function () {
                        $button.text('Log In').attr('disabled', false);
                        alert('An error occurred while processing your request.');
                    }
                });
            }
            $('#email-form').submit(function(e){
                e.preventDefault();
                email_login();
            });
            // Handle email login
            $('#email-login-button').click(function (e) {
                e.preventDefault();
                email_login();
            });

            // Handle OTP verification
            $('#verify-otp-form').on('submit', function (event) {
                event.preventDefault();
                let userId = $('#user_id').val();
                var otp = $('input[name="otp[]"]').map(function () {
                    return $(this).val();
                }).get().join('');
                var guest_booking_id = $('#guest_booking_id').val();
                let email = $('#user_email').val();
                let phone = $('#user_phone').val();
                let dial_code = $('#user_dialCode').val();
                let url = email ? "{{ route('confirm_email_code_web') }}" : "{{ url('web/verify_sign_in_with_phone_otp_web') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        email: email,
                        phone: phone,
                        dial_code: dial_code,
                        otp: otp,
                        guest_booking_id: guest_booking_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#event-modal').modal('hide');
                        if (response.status == '1') {
                            App.alert(response.message || 'Account verified successfully', 'Success!', 'success');
                            if(response.oData.guest_booking){
                                const url = `{{ url('web/guest-booking-overview') }}?guest_booking_id=${encodeURIComponent(guest_booking_id)}`;
                                localStorage.removeItem('guest_booking_id');
                                setTimeout(function () {
                                    window.location.href = url;
                                }, 1500);
                            }else{
                                setTimeout(function () {
                                    window.location.href = "{{url('/website')}}";
                                }, 1500);
                            }
                        } else {
                            App.alert(response.message || 'Something went wrong', 'Fail!', 'error');
                            if (response.errors) {
                                $.each(response.errors, function (e_field, e_message) {
                                    if (e_message != '') {
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                        $('<div class="invalid-feedback">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                        $('<div class="invalid-feedback">' + e_message + '</div>').insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                    }
                                });
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        App.alert('Failed to Verify OTP', 'Fail!', 'error');
                    }
                });
            });

            // Handle resend OTP
            $('#resend-otp').on('click', function () {
                let userId = $('#user_id').val();
                let email = $('#user_email').val();
                let phone = $('#user_phone').val();
                let url = email ? "{{ url('api/v1/auth/resend_code') }}" : "{{ url('web/resend_phone_otp') }}";

                //$('#event-modal').modal('hide');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status == '1') {
                            if(email){
                                App.alert('An OTP has been sent to your Email', 'Success!', 'success');
                            }else{
                                App.alert('An OTP has been sent to your Phone', 'Success!', 'success');
                            }
                            //$('#event-modal').modal('show');
                        } else {
                            App.alert(response.message || 'Something went wrong', 'Fail!', 'error');
                            if (response.errors) {
                                $.each(response.errors, function (e_field, e_message) {
                                    if (e_message != '') {
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                        $('<div class="invalid-feedback">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                        $('<div class="invalid-feedback">' + e_message + '</div>').insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                    }
                                });
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        App.alert('Failed to Resend OTP', 'Fail!', 'error');
                    }
                });
            });
        });

        </script>
    </body>
</html>