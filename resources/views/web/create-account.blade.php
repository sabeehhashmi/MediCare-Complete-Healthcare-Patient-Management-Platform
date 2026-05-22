<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Doctor in Dubai | Mednero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{URL::asset('web')}}/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{URL::asset('web')}}/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{URL::asset('web')}}/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{URL::asset('web')}}/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{URL::asset('web')}}/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{URL::asset('web')}}/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{URL::asset('web')}}/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{URL::asset('web')}}/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{URL::asset('web')}}/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{URL::asset('web')}}/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('web')}}/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{URL::asset('web')}}/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{URL::asset('web')}}/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{URL::asset('web')}}/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{URL::asset('web')}}/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- <link rel="shortcut icon" href="{{URL::asset('web')}}/images/favicon.ico"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- plugin css -->
    <link href="{{URL::asset('web')}}/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!-- swiper css -->
    <link rel="stylesheet" href="{{URL::asset('web')}}/libs/swiper/swiper-bundle.min.css">
    <!-- nouisliderribute css -->
    <link rel="stylesheet" href="{{URL::asset('web')}}/libs/nouislider/nouislider.min.css">

    <!-- Bootstrap Css -->
    <link href="{{URL::asset('web')}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons Css -->
    <link href="{{URL::asset('web')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- Flatpicker Css -->
    <link href="{{URL::asset('web')}}/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{URL::asset('web')}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('web')}}/css/custom-web.css" id="style" rel="stylesheet" type="text/css" />
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
                                <div class="text-center mt-2">
                                    <h5 class="text-primary mx-auto" style="max-width: 320px;">Create An Account</h5>
                                    <!-- <p class="text-muted">Sign in to continue to webadmin.</p> -->
                                </div>
                                <div class="p-2 mt-4">

                                    <form action="{{url('api/v1/signup')}}" method="POST" id="signup-form">
                                        @csrf
                                        <input type="hidden" name="guest_booking_id" id="guest_booking_id">
                                        <input type="hidden" name="is_social" value="{{$is_social??0}}">
                                        <div class="mb-3 mb-md-4">
                                            <div class="avtar-placeholder text-center">
                                                <img id="preview-image" src="https://static.vecteezy.com/system/resources/thumbnails/002/534/006/small/social-media-chatting-online-blank-profile-picture-head-and-body-icon-people-standing-icon-grey-background-free-vector.jpg" alt="" class="img-place mx-auto rounded">
                                                <div class="-mt-5">
                                                    <label for="formFile" class="form-label">
                                                        <span class="btn btn-primary px-2">
                                                            <img class="icn" src="{{URL::asset('web')}}/images/camera-icn-058903.svg" alt="">
                                                        </span>
                                                    </label>
                                                    <input class="form-control position-absolute opacity-0" name="user_image" type="file" id="formFile">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                                <input type="text" name="first_name" class="form-control ps-3" id="first_name" @if(isset($is_social) && $is_social ==1) value="{{$first_name}}" @endif required="true"
                                                    placeholder="First Name">
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                                <input type="text" name="last_name" class="form-control ps-3" id="last_name" required="true"
                                                    placeholder="Last Name" @if(isset($is_social) && $is_social ==1) value="{{$last_name}}" @endif >
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>



                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="">Gender </label> -->
                                            <div class="position-relative">
                                                <select name="gender" id="GenderModal" class="select2-single no-icon" data-placeholder="Gender" required="true">
                                                    <option></option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                    <option value="3">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <div class="position-relative input-custom-icon mb-3">
                                                <input type="text" name="dob" class="form-control ps-3 flatpicker-input" id="" placeholder="Date Of Birth" />
                                                <!-- <span class="custom-icon calendar-doc-icn"></span> -->
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                                <input type="email" name="email" class="form-control ps-3" id="email" placeholder="Email Address" @if(isset($is_social) && $is_social ==1) value="{{$email}}" readonly @endif value="{{ request('email') }}" >
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>
                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                            <input type="hidden" id="dial_code" name="dial_code" value="{{ request('dial_code') }}">
                                            <input type="text" class="form-control no-zero-input numberonly" id="phone" name="phone" placeholder="Enter Phone Number" required="true" value="{{ request('phone') }}"/>
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <div class="form-check py-1">
                                                <input type="checkbox" class="form-check-input" id="ht-remember-check">
                                                <label class="form-check-label ms-2" for="ht-remember-check">Same as WhatsApp Number</label>
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                            <input type="hidden" id="whatsApp_dial_code" name="whatsap_dial_code">
                                            <input type="text" class="form-control no-zero-input numberonly" id="whatsApp" name="whatsap_number" placeholder="Enter WhatsApp Number" />
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>
                                        <div class="mb-3 mb-md-4">
                                            <label class="form-label" for="">My Insurance Network </label>
                                            <div class="position-relative">
                                                <select name="insurence_id" id="insurence_id" class="select2-single no-icon" data-placeholder="My Insurance Network">
                                                <option value="">My Insurance Network</option>
                                                @foreach($insurencePolicies as $id => $value)
                                                <option data-count="{{$value->sub_insurence_policy_count??0}}" value="{{$value->id}}">{{$value->title}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 mb-md-4">
                                            <label class="form-label" for="">My Sub Insurance Network </label>
                                            <div class="position-relative">
                                                <select name="sub_insurence_id" id="sub_insurence_id" class="sub-insurance-policy select2-single no-icon" data-placeholder="My Sub Insurance Network">
                                                    
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-check py-1">
                                            <input type="checkbox" class="form-check-input" id="auth-remember-check" required>
                                            <label class="form-check-label ms-2" for="auth-remember-check">By Proceeding I Agree to <a target="blank" href="{{route('privacy-policy')}}" class="text-primary">Privacy Policy</a></label>
                                        </div>

                                        <div class="mt-3">
                                            <!-- <a href="user-profile.php"> -->
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Create an Account</button>
                                            <!-- </a> -->
                                             
                                        </div>
                                        <div class="pull-right">Already have an account. <a href="{{route('patient.login')}}">Login Now</a></div>
                                    </form>

                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-sm-12 text-center">
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


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- JAVASCRIPT -->
        <script src="{{URL::asset('web')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/metismenujs/metismenujs.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/simplebar/simplebar.min.js"></script>
        <script src="{{URL::asset('web')}}/libs/eva-icons/eva.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
       
        <script src="{{URL::asset('web')}}/js/pages/pass-addon.init.js"></script>
        <script src="{{URL::asset('web')}}/js/flatpickr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
        <!-- two-step-verification js -->
        <script src="{{URL::asset('web')}}/js/pages/two-step-verification.init.js"></script>
        <script src="{{ URL::asset('js/app.js') }}"></script>

        <script>
            $('#insurence_id').change(function(){
                var selectedAttrValue = $('#insurence_id option:selected').attr('data-count');
                
            let incuranceId = $(this).val();
            $('#sub_insurence_id').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                    success: function (res) {
                        if (res) {
                            $('#sub_insurence_id').html('<option value="">My Insurance Network</option>');
                            $.each(res, function (index, data) {
                                $('#sub_insurence_id').append('<option  value="' + data.id+'">' + data.title + '</option>');
                            });
                            if(selectedAttrValue > 0){
                                $('#sub_insurence_id').attr('required', 'required');
                            }else{
                                $('#sub_insurence_id').attr('required', '');
                            }
                            $('#sub_insurence_id').select2()
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
        });

            document.addEventListener("DOMContentLoaded", function() {
                storageGuestBookingId = localStorage.getItem('guest_booking_id');
                if (storageGuestBookingId) {
                    document.getElementById('guest_booking_id').value = storageGuestBookingId;
                }
            });
            $(document).ready(function() {
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
                // Initialize intlTelInput for phone input
                var inputTel = document.querySelector("#phone");
                var ph = window.intlTelInput(inputTel, {
                 //   initialCountry: '{{INIT_PHONE_C_CODE}}',
                    separateDialCode: true,
                //    onlyCountries: ['ae'], // Restrict to UAE only
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
                });

                inputTel.addEventListener("input", function () {
                    var dialCode = ph.getSelectedCountryData().dialCode;
                    $('#dial_code').val(dialCode);
                });

                // Initialize intlTelInput for WhatsApp input
                var whatsAppInputTel = document.querySelector("#whatsApp");
                var wa = window.intlTelInput(whatsAppInputTel, {
                //    initialCountry: '{{INIT_PHONE_C_CODE}}',
                    separateDialCode: true,
                //    onlyCountries: ['ae'], // Restrict to UAE only
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
                });

                whatsAppInputTel.addEventListener("input", function () {
                    var dialCode = wa.getSelectedCountryData().dialCode;
                    $('#whatsApp_dial_code').val(dialCode);
                });

                // Handle "autofill whatsapp" checkbox change
                $('#ht-remember-check').on('change', function () {
                    if ($(this).prop('checked')) {
                        var dialCode = ph.getSelectedCountryData().dialCode;
                        var phoneNumber = inputTel.value;
                        //$('#whatsApp_dial_code').val(dialCode);
                        $('#whatsApp').val(phoneNumber);
                        wa.setNumber(phoneNumber);
                    } else {
                        $('#whatsApp_dial_code').val('');
                        $('#whatsApp').val('');
                        wa.setNumber('');
                    }
                });

                // Handle signup form submission
                $('#signup-form').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    var phone = $('#phone').val();
                    if(phone.length < 5 || phone.length  > 12){
                        App.alert('Please Fill a valid phone number', 'Fail!', 'error');
                        return false;
                    }

                    var phone = $('#whatsApp').val();
                    if(phone.length > 0){
                        if(phone.length < 5 || phone.length  > 12){
                            App.alert('Please Fill a valid whatsap number', 'Fail!', 'error');
                            return false;
                        }
                    }

                    var $form = $(this);
                    var formData = new FormData(this);
                    $form.find('button[type="submit"]').text('Saving').attr('disabled', true);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $form.find('button[type="submit"]').text('Create an Account').attr('disabled', false);
                            if(response.status == "1"){
                                console.log(response, 'response');
                                $('#user_id').val(response.oData.id);
                                $('#event-modal').modal('show');
                            }else{
                                if(response.errors){
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
                                }else{
                                    $form.find('button[type="submit"]').text('Create an Account').attr('disabled', false);
                                    App.alert(response.message || 'Failed to login', 'Fail!','error');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            App.alert('An error occurred: ' + error.message, 'Fail!','error');
                        }
                    });
                });

                // Handle OTP verification form submission
                $('#verify-otp-form').on('submit', function(event) {
                    event.preventDefault();
                    let userId = $('#user_id').val();
                    var otp = $('input[name="otp[]"]').map(function() {
                        return $(this).val();
                    }).get().join('');
                    var guest_booking_id = $('#guest_booking_id').val();

                    $.ajax({
                        url: '{{ url("web/verify_signup_otp_web") }}',
                        type: 'POST',
                        data: {
                            user_id: userId,
                            otp: otp,
                            guest_booking_id: guest_booking_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#event-modal').modal('hide');
                            if(response.status == '1'){
                                App.alert(response.message || 'Account verified successfully', 'Success!','success');
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
                                if(response.errors){
                                    App.alert(response.message || 'Something went wrong', 'Fail!','error');
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
                                    App.alert(response.message || 'Failed to Verify OTP', 'Fail!','error');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            App.alert('An error occurred: ' + error.message, 'Fail!','error');
                        }
                    });
                });

                // Handle OTP resend
                $('#resend-otp').on('click', function() {
                    let userId = $('#user_id').val();
                   // $('#event-modal').modal('hide');

                    $.ajax({
                        url: '{{ url("api/v1/resend_signup_otp") }}',
                        type: 'POST',
                        data: {
                            user_id: userId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.status == '1'){
                                App.alert(response.message || 'OTP resent successfully', 'Success!','success');
                                //$('#event-modal').modal('show');
                            } else {
                                if(response.errors){
                                    App.alert(response.message || 'Something went wrong', 'Fail!','error');
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
                                    App.alert(response.message || 'Failed to resend OTP', 'Fail!','error');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            App.alert('An error occurred: ' + error.message, 'Fail!','error');
                        }
                    });
                });

                // Initialize Select2
                $('.select2-single').select2({
                    placeholder: function() {
                        return $(this).data('placeholder');
                    }
                });

                // Initialize Flatpickr
                $(".flatpicker-input").flatpickr({
                    dateFormat: "d-m-Y",
                    maxDate: "today"
                });

                // Handle file input change
                document.getElementById('formFile').addEventListener('change', function(event) {
                    var reader = new FileReader();
                    reader.onload = function() {
                        var previewImage = document.getElementById('preview-image');
                        previewImage.src = reader.result;
                    }
                    if (event.target.files.length) {
                        reader.readAsDataURL(event.target.files[0]);
                    }
                });
            });

        </script>

    </body>
</html>