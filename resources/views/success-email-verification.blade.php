<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Doctor in Dubai | Mednero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/')}}admin-assets/assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('/')}}admin-assets/assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/')}}admin-assets/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/')}}admin-assets/assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/')}}admin-assets/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{asset('/')}}admin-assets/assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('/')}}admin-assets/assets/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- <link rel="shortcut icon" href="{{asset('/')}}admin-assets/assets/images/favicon.ico"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- plugin css -->
    <link href="{{asset('/')}}admin-assets/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!-- swiper css -->
    <link rel="stylesheet" href="{{asset('/')}}admin-assets/assets/libs/swiper/swiper-bundle.min.css">
    <!-- nouisliderribute css -->
    <link rel="stylesheet" href="{{asset('/')}}admin-assets/assets/libs/nouislider/nouislider.min.css">

    <!-- Bootstrap Css -->
    <link href="{{asset('/')}}admin-assets/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons Css -->
    <link href="{{asset('/')}}admin-assets/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- Flatpicker Css -->
    <link href="{{asset('/')}}admin-assets/assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/')}}admin-assets/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}admin-assets/assets/css/custom-web.css" id="style" rel="stylesheet" type="text/css" />

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
                            <div class="card-body p-4 text-center"> 
                                <img src="{{asset('/')}}doctor/assets/images/logo-mednero.png" class="img-fluid mb-4" alt="">
                                <h5 class="text-primary mx-auto" style="max-width: 320px;">Email Verified!</h5>
                                <p class="mx-auto mb-0" style="line-height: 1.7;">Hi {{$username ?? 'N/A'}}, Your email is verified successfully. You will receive activation mail once your account is activated by Mednero.</p>
            
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

        <!-- JAVASCRIPT -->
        <script src="{{asset('/')}}admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{asset('/')}}admin-assets/assets/libs/eva-icons/eva.min.js"></script>

        <script src="{{asset('/')}}admin-assets/assets/js/pages/pass-addon.init.js"></script>
        <!-- two-step-verification js -->
        <script src="{{asset('/')}}admin-assets/assets/js/pages/two-step-verification.init.js"></script>

    </body>
</html>