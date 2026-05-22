
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Clinic Login | {{config('global.site_name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}hospital/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}hospital/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}hospital/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}hospital/assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('') }}hospital/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('') }}hospital/assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="{{ asset('') }}hospital/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('') }}hospital/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('') }}hospital/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        
        <!-- App Css-->
        <link href="{{ asset('') }}hospital/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>

<div class="authentication-bg min-vh-100">
        <!-- <div class="bg-overlay bg-light"></div> -->
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">

                        

                        <div class="card overflow-hidden">
                            <div class="card-body p-4"> 
                                <!-- <div class="text-center mb-4 title-card" style="position: absolute; width: 100%; left: 0; top: 0; padding: 15px; background: #FF416F;">
                                    <h3 class="mb-0 text-white font-size-24">Hospital Panel</h3>
                                </div> -->
                                <div class="mb-4 pb-2">
                                    <a href="" class="d-block auth-logo">
                                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-dark me-start">
                                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-light me-start">
                                    </a>
                                </div>
                                <div class="text-center mt-2">
                                    <h5>Welcome Back !</h5>
                                    <p class="text-black">Sign in to continue to Clinic.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="form-login" id="login-form" action="{{ url('clinic/check_login') }}" method="POST">
                                       @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="username">Email</label>
                                            <div class="position-relative input-custom-icon">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email">
                                                 <span class="bx bx-envelope"></span>
                                                 @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                
                                        <div class="mb-3">
                                            
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                <span class="bx bx-lock-alt"></span>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Enter password">
                                                <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" onclick="password_show_hide();">
                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                </button>
                                            </div>
                                        </div>
                
                                        <!-- <div class="form-check py-1">
                                            <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check"> by proceeding Agree <a href="#!" target="_blank">Privacy Policy</a></label>
                                        </div> -->
                                        
                                        <div class="mt-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                                        </div>

                                        <div class="text-end mt-3">
                                                <a href="#!" id="forgot-password-link" class="text-black" data-bs-toggle="modal" data-bs-target=".forgotpassword">Forgot password?</a>
                                        </div>

                                        

                                        <div class="mt-3 text-center">
                                            <p class="mb-0">Don’t have an account? <a href="{{ url('clinic/register') }}" class="fw-medium text-primary"> Sign Up </a> </p>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center p-4">
                            <p class="text-white">© <script>document.write(new Date().getFullYear())</script> © mednero.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->
 <!-- Forgot Password modal example -->
<div class="modal fade forgotpassword" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="forgot-password-form" method="POST" action="{{url('forgot-password')}}">
                    @csrf
                    <input type="hidden" name="role" value="{{CLINIC_ROLE}}">
                    <div class="mb-3">
                        <label class="form-label" for="username">Email</label>
                        <div class="position-relative input-custom-icon">
                            <input type="email" name="email" class="form-control" id="forgot-pass-email" placeholder="Enter Email" required>
                            <span class="bx bx-envelope"></span>
                        </div>
                        <span id="forgotpass-error" class="text-danger"></span>
                    </div>
                    <div class="mt-3">
                        <button id="forgot-password-submit" class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset Password</button>
                    </div>
                    <div class="mt-3 text-center">
                    <p class="mb-0">Don’t have an account? <a href="{{ url('clinic/register') }}" class="fw-medium text-primary"> Sign Up </a> </p>                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- OTP modal example -->
<div class="modal fade otpmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content bg-prime-light">
            <div class="modal-header py-3 px-4 border-bottom-0">
                <h5 class="modal-title" id="modal-title">Verify OTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="verify-otp-form" class="custom-form">
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-body p-4">
                    <div class="row">
                    <p class="text-center mb-4">To verify your email, please enter the OTP that is sent to your email address  <span id="email-domain"></span></p>
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
                    <p id="otp-error" class="text-danger"></p>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn text-primary fw-bold" style="width: 120px;" id="resend-otp">Resend</button> -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade loginOtpModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm-otp">
        <div class="modal-content bg-prime-light">

            <div class="modal-header">
                <h5 class="modal-title">Login Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="loginOtpForm">
                @csrf
                <input type="hidden" id="login_user_id" name="login_user_id">

                <div class="modal-body text-center">

                    <p class="mb-3">
                        Enter OTP sent to <strong id="login_email_text"></strong>
                    </p>

                    <div class="d-flex justify-content-center gap-2">

                        <input type="text" class="form-control text-center login-otp-input" maxlength="1">
                        <input type="text" class="form-control text-center login-otp-input" maxlength="1">
                        <input type="text" class="form-control text-center login-otp-input" maxlength="1">
                        <input type="text" class="form-control text-center login-otp-input" maxlength="1">

                    </div>

                    <p id="login_otp_error" class="text-danger mt-2"></p>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Verify & Login</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade resetpassword" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reset-pass-form">
                    @csrf
                    <input type="hidden" id="reset-user_id" name="user_id" value="">
                    <input type="hidden" id="reset-otp" name="otp" value="">
        
                <div class="mb-3">          
                        <label class="form-label" for="password-input">New Password</label>
                        <div class="position-relative auth-pass-inputgroup input-custom-icon">
                            <span class="bx bx-lock-alt"></span>
                            <input id="new-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Enter password">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" onclick="new_password_show_hide();">
                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label" for="password-input">Confirm Password</label>
                        <div class="position-relative auth-pass-inputgroup input-custom-icon">
                            <span class="bx bx-lock-alt"></span>
                            <input id="confirm-password" type="password" class="form-control @error('password') is-invalid @enderror" name="confirm_password" autocomplete="new-password" placeholder="Enter password again">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" onclick="confirm_password_show_hide();">
                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                            </button>
                        </div>
                        <span class="text-danger" id="confirm-pass-error"></span>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--  Forgot Password modal example -->
<div class="modal fade resetpassword" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reset-pass-form">
                    @csrf
                    <input type="hidden" id="reset-user_id" name="user_id" value="">
                    <input type="hidden" id="reset-otp" name="otp" value="">
        
                <div class="mb-3">          
                        <label class="form-label" for="password-input">New Password</label>
                        <div class="position-relative auth-pass-inputgroup input-custom-icon">
                            <span class="bx bx-lock-alt"></span>
                            <input id="new-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Enter password">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" onclick="new_password_show_hide();">
                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label" for="password-input">Confirm Password</label>
                        <div class="position-relative auth-pass-inputgroup input-custom-icon">
                            <span class="bx bx-lock-alt"></span>
                            <input id="confirm-password" type="password" class="form-control @error('password') is-invalid @enderror" name="confirm_password" autocomplete="new-password" placeholder="Enter password again">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" onclick="confirm_password_show_hide();">
                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                            </button>
                        </div>
                        <span class="text-danger" id="confirm-pass-error"></span>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
    <!-- JAVASCRIPT -->
    <script src="{{ asset('') }}hospital/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.js') }}"></script>
    <link href="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <script>

        function clearModalFields(modalId) {
            // $(modalId).on('show.bs.modal', function() {
                $(modalId).find('input').each(function() {
                    if($(this).attr('name') !== '_token' && $(this).attr('name') !== 'role'){
                        $(this).val('');
                    }
                });
            // })
        }
        
        $(".form-login").submit(function(e) {
    e.preventDefault();
            let $btn = $(this).find("button[type='submit']");
    let originalText = $btn.text();
    $.ajax({
        type:'POST',
        url: "{{ route('clinic.check_login') }}",
        data:{
            '_token': $('input[name=_token]').val(),
            'email': $("#email").val(),
            'password': $("#password").val()
        },
        beforeSend: function() {
            // Disable button + change text
            $btn.prop("disabled", true);
            $btn.text("Sending...");
        },
        success: function(response) {

            if(response.success){

                toastr["success"](response.message);

                // ✅ Set values
                $("#login_user_id").val(response.user_id);
                $("#login_email_text").text(response.email);

                // ✅ Open NEW modal
                $(".loginOtpModal").modal('show');

            } else {
                toastr["error"](response.message);
            }
        },
        error: function() {
            toastr["error"]("Something went wrong!");
        },

        complete: function() {
            // Re-enable button + restore text
            $btn.prop("disabled", false);
            $btn.text(originalText);
        }
    });
});
$(document).ready(function () {

    const inputs = $(".login-otp-input");

    inputs.on("input", function () {
        let value = $(this).val();

        // Allow only digits
        value = value.replace(/[^0-9]/g, '');
        $(this).val(value);

        if (value.length === 1) {
            $(this).next(".login-otp-input").focus();
        }
    });

    inputs.on("keydown", function (e) {
        if (e.key === "Backspace") {
            if ($(this).val() === "") {
                $(this).prev(".login-otp-input").focus();
            } else {
                $(this).val('');
            }
        }
    });

});
$("#loginOtpForm").submit(function(e) {
    e.preventDefault();

    let otp = '';
    $(".login-otp-input").each(function(){
        otp += $(this).val();
    });

    $.ajax({
        type: 'POST',
        url: "{{ route('clinic.verify.login.otp') }}",
        data: {
            _token: $('input[name=_token]').val(),
            user_id: $("#login_user_id").val(),
            otp: otp
        },
        success: function(response){

            if(response.success){
                toastr["success"](response.message);

                setTimeout(function(){
                    window.location.href = "{{ route('clinic.dashboard') }}";
                }, 500);

            } else {
                $("#login_otp_error").text(response.message);
            }
        }
    });
});

        $('#forgot-password-link').on('click', function(){
            clearModalFields('.forgotpassword');
            clearModalFields('.otpmodal');
            clearModalFields('.resetpassword');
        })

        $('#forgot-password-form').on('submit', function(e) {
                $('#forgotpass-error').text('');
                e.preventDefault(); // Prevent the default form submission
                let email = $('#forgot-pass-email').val();
             //   let emailDomain = email.substring(email.lastIndexOf("@"));
                let emailDomain =email;
                let form = $(this);
                form.find('[type="submit"]').prop("disabled", true).text("Processing..");
                let url = form.attr('action');
                let formData = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        form.find('[type="submit"]').prop("disabled", false).text("Reset Password");
                        if (response.success) {
                            $('#user_id').val(response.oData?.id);
                            // Hide Forgot Password modal
                            $('.forgotpassword').modal('hide');
                            $('#email-domain').text(emailDomain);
                            // Show OTP modal
                            $('.otpmodal').modal('show');
                        } else {
                            // Handle error response
                            $('#forgotpass-error').text(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        form.find('[type="submit"]').prop("disabled", false).text("Reset Password");
                        // Handle server error
                        alert('An error occurred. Please try again.');
                    }
                });
            });

        // Handle OTP verification form submission
        $('#verify-otp-form').on('submit', function(event) {
            $('#otp-error').text('');
            event.preventDefault();
            let userId = $('#user_id').val();
            var otp = $('input[name="otp[]"]').map(function() {
                return $(this).val();
            }).get().join('');
            if(otp.length < 4){
                $('#otp-error').text('Invalid OTP');
            }else{
                $('.otpmodal').modal('hide');
                $('#reset-user_id').val(userId)
                $('#reset-otp').val(otp)
                $('.resetpassword').modal('show');
            }
        });
        
        $('#reset-pass-form').on('submit', function(event) {
            $('#confirm-pass-error').text('');
            event.preventDefault();
            let userId = $('#reset-user_id').val();
            var otp = $('#reset-otp').val();
            var newPass = $('#new-password').val();
            var cnfrmPass = $('#confirm-password').val();
            
            if(newPass != cnfrmPass){
                $('#confirm-pass-error').text('Password mot matched');
                return false;
            }

            $.ajax({
                url: '{{ url("reset-password") }}',
                type: 'POST',
                data: {
                    user_id: userId,
                    otp: otp,
                    new_password: newPass,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.resetpassword').modal('hide');
                    if(response.status == '1'){
                        toastr["success"](response.message);
                    } else {
                        if(response.errors){
                            toastr["error"](response.message);
                            jQuery.each(response.errors, function (e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                }
                            });
                        } else {
                            toastr["error"](response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    App.alert('An error occurred: ' + error.message, 'Fail!','error');
                }
            });
        });

        function new_password_show_hide() {
            var x = document.getElementById("new-password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            
            if (x.type === "password") {
                x.type = "text";
              
            } else {
                x.type = "password";  
            }
        }
        
        function confirm_password_show_hide() {
            var x = document.getElementById("confirm-password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            
            if (x.type === "password") {
                x.type = "text";
              
            } else {
                x.type = "password";  
            }
        }

        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            
            if (x.type === "password") {
                x.type = "text";
              
            } else {
                x.type = "password";
               
            }
        }
    </script>


    </body>


</html>