<!doctype html>
<html lang="en">
<head>

        <meta charset="utf-8" />
        <title>{{config('global.site_name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{config('global.site_name')}}" name="description" />
        <meta content="{{config('global.site_name')}}" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('/')}}admin-assets/assets/images/favicon.png">

        <!-- Bootstrap Css -->
        <link href="{{asset('/')}}admin-assets/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('/')}}admin-assets/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('/')}}admin-assets/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{asset('/')}}admin-assets/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    </head>


    <body>

    <!-- <body data-layout="horizontal"> -->

    <div class="authentication-bg min-vh-100">
        <!--<div class="bg-overlay bg-light"></div>-->
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">



                        <div class="card bg-white">
                            <div class="card-body p-4">
                                <div class="mb-4 pb-2">
                                    <a href="#" class="d-block auth-logo">
                                        <img src="{{asset('/')}}admin-assets/assets/images/logo-mednero.png" alt="{{config('global.site_name')}}" height="50" class="auth-logo-dark me-start">

                                    </a>
                                </div>
                                <div class="text-center mt-2">
                                    <h5>Welcome Back !</h5>
                                    <p class="text-black">Sign in to continue .</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="form-login" method="post" action="{{ route('admin.check_login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <div class="position-relative input-custom-icon">
                                                <input type="email" class="form-control" id="email" value="admin@admin.com" required placeholder="Enter Email" autocomplete="off" autofocus>
                                                 <span class="bx bx-user"></span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                <!-- <a href="#" class="text-muted text-decoration-underline">Forgot password?</a> -->
                                            </div>
                                            <label class="form-label" for="password">Password</label>
                                            <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                <span class="bx bx-lock-alt"></span>
                                                <input type="password" class="form-control" id="password-input" name="password" placeholder="Enter password">
                                                <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- <div class="form-check py-1">
                                            <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div> -->

                                        <div class="mt-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
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
                            <p class="text-white">© <script>document.write(new Date().getFullYear())</script> {{config('global.site_name')}}. </p>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('/')}}admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/eva-icons/eva.min.js"></script>

        <script src="{{asset('/')}}admin-assets/assets/js/pages/pass-addon.init.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.js') }}"></script>
        <script>
            // Toaster options
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "rtl": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": 300,
                "hideDuration": 1000,
                "timeOut": 2000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            $(document).ready(function() {
            @if (\Session::has('error') && \Session::get('error') != null)
                toastr["error"]("{{\Session::get('error')}}");
            @endif

            })
            $(".form-login").submit(function(e) {
                e.preventDefault();
                var $form = $(this);
                var formData = new FormData($form[0]);
                $form.find('[type="submit"]').prop("disabled", true).text("Processing..");
                const email = $("#email").val();
                const password = $("#password-input").val();

                if (email == "") {
                    toastr["error"]("Please enter email address");
                    $form.find('[type="submit"]').prop("disabled", false).text("Log In");
                    return false;
                } else if (password == "") {
                    toastr["error"]("Please enter password");
                    $form.find('[type="submit"]').prop("disabled", false).text("Log In");
                    return false;
                }

                $.ajax({
                        type:'POST',
                        url: "{{ route("admin.check_login")}}",
                        data:{
                            '_token': $('input[name=_token]').val(),
                            'email': $("#email").val(),
                            'password': $("#password-input").val(),
                            'timezone': Intl.DateTimeFormat().resolvedOptions().timeZone
                        },
                        success: function(response) {
                            // console.log(response);
                            if(response.success){
                                toastr["success"](response.message);
                                setTimeout(function(){
                                    // $form.find('[type="submit"]').prop("disabled", false).text("Log In");
                                    window.location.href = "{{ route("admin.dashboard")}}";
                                }, 500);

                            } else {
                                toastr["error"](response.message);
                                $form.find('[type="submit"]').prop("disabled", false).text("Log In");
                            }
                        }
                    });
            });

        </script>
    </body>


</html>
