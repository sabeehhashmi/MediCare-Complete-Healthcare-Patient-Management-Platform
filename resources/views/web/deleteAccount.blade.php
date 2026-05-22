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
                                    <a href="{{url('/website')}}" class="d-block auth-logo">
                                        <img src="{{URL::asset('web')}}/images/mednero.svg" alt="" height="60" class="auth-logo-dark me-start">
                                        <img src="{{URL::asset('web')}}/images/mednero.svg" alt="" height="60" class="auth-logo-light me-start">
                                    </a>
                                    <!-- <h5 class="text-primary mx-auto" style="max-width: 320px;">Delete Account</h5> -->
                                    <!-- <p class="text-muted">Sign in to continue to webadmin.</p> -->
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary mx-auto" style="max-width: 320px;">Delete Account</h5>
                                    </div>
                                </div>
                                <div class="p-2 mt-4">

                                    <form action="{{url('website/delete-account-submit')}}" method="POST" id="delete-account-form">
                                        @csrf

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                                <input type="text" name="name" class="form-control ps-3" id="name" required="true"
                                                    placeholder="Name">
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                                <input type="email" name="email" class="form-control ps-3" id="email" required="true" placeholder="Email Address" value="{{ request('email') }}">
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>
                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                            <input type="hidden" id="dial_code" name="dial_code" value="{{ request('dial_code') }}">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" value="{{ request('phone') }}"/>
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>

                                        <div class="mb-3 mb-md-4">
                                            <!-- <label class="form-label" for="username">Username</label> -->
                                            <div class="position-relative input-custom-icon">
                                            <textarea class="form-control" id="comments" name="comments" placeholder="Your Comments"></textarea>
                                                <!-- <span class="bx bx-mobile-alt"></span> -->
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <!-- <a href="user-profile.php"> -->
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Submit</button>
                                            <!-- </a> -->
                                        </div>
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
            
            $(document).ready(function() {
                // Initialize intlTelInput for phone input
                var inputTel = document.querySelector("#phone");
                var ph = window.intlTelInput(inputTel, {
                //    initialCountry: '{{INIT_PHONE_C_CODE}}',
                    separateDialCode: true,
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
                });

                inputTel.addEventListener("input", function () {
                    var dialCode = ph.getSelectedCountryData().dialCode;
                    $('#dial_code').val(dialCode);
                });


                // Handle signup form submission
                $('#delete-account-form').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission
                    var $form = $(this);
                    var formData = new FormData(this);
                    $form.find('button[type="submit"]').text('Processing..').attr('disabled', true);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $form.find('button[type="submit"]').text('Submit').attr('disabled', false);
                            if(response.success == "1"){
                                App.alert(response.message || 'Request is submited', 'Success!','success');
                                setTimeout(function () {
                                    window.location.href = "{{url('/website')}}";
                                }, 1500);
                            }else{
                                App.alert(response.message || 'Failed to Submit Request', 'Fail!','error');
                            }
                        },
                        error: function(xhr, status, error) {
                            $form.find('button[type="submit"]').text('Submit').attr('disabled', false);
                            if(xhr.status == 422){
                                if(xhr.responseJSON.errors){
                                    jQuery.each(xhr.responseJSON.errors, function (e_field, e_message) {
                                        if (e_message != '') {
                                            $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                            $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                            $('<div class="invalid-feedback">' + e_message + '</div>')
                                                .insertAfter($('[name="' + e_field + '"]').eq(0));
                                            $('<div class="invalid-feedback">' + e_message + '</div>')
                                                .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                        }
                                    });
                                }
                                App.alert(xhr.responseJSON.message, 'Fail!','error');
                            }else{
                                App.alert('Something Went wrong', 'Fail!','error');
                            }
                        }
                    });
                });
            });

        </script>

    </body>
</html>