
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Doctor Register | Mednero</title>
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
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
        <link href="{{ asset('') }}hospital/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        
        <link href="{{ asset('') }}hospital/assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
        <link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
        <!-- App Css-->
        <link href="{{ asset('') }}hospital/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />

        <link
          rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
        />    
        
       
        <style>
            .has-iframe .fancybox__content{
                padding:0 !important;
            }
            .select2-error{
                display: flex;
                flex-direction: column-reverse;
            }
        </style>

</head>

<body>

<div class="authentication-bg min-vh-100">
        <!-- <div class="bg-overlay bg-light"></div> -->
    <div class="container">
        <div class="d-flex flex-column min-vh-100 px-3 pt-4">
            <div class="row justify-content-center my-auto">
                <div class="col-12">

                    
                <div class="card">
                        <div class="card-body p-4"> 
                            <div class="mb-4 pb-2">
                                <a href="" class="d-block auth-logo">
                                    <img src="{{ asset('') }}doctor/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-dark me-start">
                                    <img src="{{ asset('') }}doctor/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-light me-start">
                                </a>
                            </div>
                            <div class="text-center mt-2">
                                <h5>Create An Account</h5>
                                <p class="text-black">Welcome to Doctor Panel.</p>
                            </div>
                            <div class="p-2 mt-4">
                            <form id="msform" action="{{ url('save_doctor') }}" class="registerform"  enctype="multipart/form-data" method="post" data-parsley-validate="true">
                            @csrf
                            <input type="hidden" value="0" name="termsaccpeted" id="termsaccpeted">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label" for="username">First Name</label>
                                            <div class="position-relative input-custom-icon">
                                                <input type="text" class="form-control" required name="first_name" placeholder="Enter First Name">
                                                <span class="bx bx-user"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label" for="username">Last Name</label>
                                            <div class="position-relative input-custom-icon">
                                                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                                                <span class="bx bx-user"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label" for="username">Phone Number</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control numberonly no-zero-input" required id="phone" name="phone" placeholder="Enter Phone Number">
                                                <input type="hidden" id="dial_code" name="dial_code" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label" for="username">Email Address</label>
                                            <div class="position-relative input-custom-icon">
                                                <input type="email" class="form-control" id="" name="email" required placeholder="Enter Email Address">
                                                <span class="bx bx-envelope"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                    <span class="bx bx-lock-alt"></span>
                                                    <input type="password" class="form-control" required id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon-1">
                                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="conf-password-input">Confirm Password</label>
                                                <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                    <span class="bx bx-lock-alt"></span>
                                                    <input type="password" class="form-control" required id="conf-password-input" name="confpassword" placeholder="Enter Confirm Password" autocomplete="new-password">
                                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon-2">
                                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                    </button>
                                                    </div>
                                                <span class="text-danger" id="confirm-pass-match"></span>
                                            </div>
                                        <!-- <div class="col-lg-6 mb-3">
                                            <div class="custom-upload">
                                                <label for="uploadphotos" class="form-label">Upload Photo</label>
                                                <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />
                                                <div id="imagePreview" style="display: none;"></div>
                                            </div>
                                        </div> -->

                                        
                                        
                                        
                                        <div class="col-12 mb-3">
                                            <div class="form-check py-1 d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                                <label class="form-check-label" for="auth-remember-check" required> By proceeding <a href="#!" data-bs-toggle="modal" data-bs-target="#terms-conditions-modal">I Agree to Privacy Policy</a></label>
                                                <!-- <label class="form-check-label" for="auth-remember-check" required> by proceeding Agree <a href="{{url('hospital/terms-conditions')}}" data-fancybox data-type="iframe">Terms and conditions</a></label> -->
                                            </div>
                                        </div>
                                        
                                        <!--<div class="col-12 mb-3">-->
                                        <!--    <iframe src="terms-conditions.php" height="500" style="width:100%" title="Iframe Example"></iframe>-->
                                        <!--</div>-->
                                        
                                        <div class="col-12">
                                            <button class="btn btn-primary mx-auto waves-effect waves-light" type="submit">Create An Account</button>
                                        </div>
                                    </div>
            
                                    <div class="mt-3 text-center">
                                        <p class="mb-0">Already have an account? <a href="{{url('doctorlogin')}}" class="fw-medium text-primary">Log In</a> </p>
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
    <div class="modal fade" id="terms-conditions-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Terms & Conditions</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
        <div style="overflow-y: scroll; height:400px;" id="termsdiv">
            <p>
                <?php echo $terms->desc_en ?? null;?>
            </p>
        </div>
            <button type="button" class="btn btn-primary w-100" id="acceptbutton" disabled data-bs-dismiss="modal">Accept Terms & Conditions</button>
        </div>
        </div>
    </div>
    </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="{{ asset('') }}doctor/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
  
    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/js/flatpickr.min.js"></script>
    <script src="{{URL::asset('web/js/select2.min.js')}}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script>

    document.addEventListener('DOMContentLoaded', function() {
        // Function to toggle password visibility
        function togglePasswordVisibility(buttonId, inputId) {
            const button = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            button.addEventListener('click', function() {
                // Toggle the type attribute
                if (input.type === 'password') {
                    input.type = 'text';
                    button.querySelector('i').classList.remove('mdi-eye-outline');
                    button.querySelector('i').classList.add('mdi-eye-off-outline');
                } else {
                    input.type = 'password';
                    button.querySelector('i').classList.remove('mdi-eye-off-outline');
                    button.querySelector('i').classList.add('mdi-eye-outline');
                }
            });
        }

        // Initialize the toggle functions
        togglePasswordVisibility('password-addon-1', 'password-input');
        togglePasswordVisibility('password-addon-2', 'conf-password-input');
    });

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

       
        
        const input = document.querySelector("#phone");
        const ph = window.intlTelInput(input, {
            
            initialCountry: '{{INIT_PHONE_C_CODE}}',
            //onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
            strictMode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input.addEventListener("input", function () {
            var dialCode = ph.getSelectedCountryData().dialCode;
            $('#dial_code').val(dialCode);
        });
        
    </script>
 
    <script src="{{asset('js/app.js')}}"></script>
                <!-- App js -->
    <!-- <script src="{{asset('admin-assets/assets/js/app.js')}}"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" integrity="sha512-Fq/wHuMI7AraoOK+juE5oYILKvSPe6GC5ZWZnvpOO/ZPdtyA29n+a5kVLP4XaLyDy9D1IBPYzdFycO33Ijd0Pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{URL::asset('web/js/parsley.min.js')}}"></script>
<script src="{{URL::asset('web/js/parsley.js')}}"></script>
    <script>

        $(document).on('click', '#auth-remember-check', function(e) {
            e.preventDefault();
            $('#terms-conditions-modal').modal('show');
        });
        $(document).ready(function() {
            $("#termsdiv").scroll(function() {
                var div = $(this);
                var scrolled = div.scrollTop() + div.innerHeight();
                var contentHeight = div.get(0).scrollHeight;
                if (scrolled >= (contentHeight - 10)) {
                    $("#acceptbutton").prop("disabled", false);
                }
            });

            // Accept terms button click handler
            $('body').off('click', '#acceptbutton');
            $('body').on('click', '#acceptbutton', function() {
                $("#termsaccepted").val(1);
                $("#auth-remember-check").prop("checked", true).prop("disabled", false);
            });
        });

        $('#conf-password-input, #password-input').on('keyup', function(){
            $('#confirm-pass-match').text('');
        })

        $('body').off('submit', '#msform');
        $('body').on('submit', '#msform', function(e) {
            $('#confirm-pass-match').text('');
            e.preventDefault();
            if($('#password-input').val() != $('#conf-password-input').val()){
                $('#confirm-pass-match').text('Confirm Password not matched.');
                return false;
            }
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();
            var terms =  $("#auth-remember-check").prop('checked');
            if(!terms)
            {
                $('#terms-conditions-modal').modal('show'); 
                return false;
            }
            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                    if (error_index == 0) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        } else {
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        // $(".fld_set").addClass('d-none');
                        $("#msform").addClass('d-none');
                        $('#s-message').removeClass("d-none");
                        // $(".sh_msg").trigger('click');
                        App.loading(false);
                         App.alert(res['message']);
                         setTimeout(function() {
                            window.location.href = "{{url('/doctorlogin')}}";
                         }, 3000);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });

    </script>

    </body>


</html>