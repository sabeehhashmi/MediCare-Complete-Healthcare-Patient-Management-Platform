<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{config('global.site_name')}} {{$page_heading??''}}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <!-- site favicon -->
  <link rel="icon" type="image/png" href="{{asset('/front/assets/')}}/images/favicon.png" sizes="16x16">
  <!-- bootstrap 4  -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/bootstrap.min.css">
  <!-- fontawesome 5  -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/all.min.css">
  <!-- line-awesome webfont -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/line-awesome.min.css">
  <!-- custom select css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/nice-select.css">
  <!-- animate css  -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/animate.min.css">
  <!-- lightcase css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/lightcase.css">
  <!-- slick slider css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/slick.css">
  <!-- jquery ui css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/jquery-ui.min.css">
  <!-- datepicker css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/vendor/datepicker.min.css">
  <!-- style main css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/main.css">
  <!-- style Custom css -->
  <link rel="stylesheet" href="{{asset('/front/assets/')}}/css/custom.css">
  <style>
    .error{
        color:#dfc56f;
    }
  </style>
  @yield('head')
</head>
  <body>

  <!-- <div class="preloader">
    <svg class="mainSVG" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
      <defs>   
        <path id="puff" d="M4.5,8.3C6,8.4,6.5,7,6.5,7s2,0.7,2.9-0.1C10,6.4,10.3,4.1,9.1,4c2-0.5,1.5-2.4-0.1-2.9c-1.1-0.3-1.8,0-1.8,0
        s-1.5-1.6-3.4-1C2.5,0.5,2.1,2.3,2.1,2.3S0,2.3,0,4.4c0,1.1,1,2.1,2.2,2.1C2.2,7.9,3.5,8.2,4.5,8.3z" fill="#fff"/>
        <circle id="dot"  cx="0" cy="0" r="5" fill="#fff"/>   
      </defs>
      
        <circle id="mainCircle" fill="none" stroke="none" stroke-width="2" stroke-miterlimit="10" cx="400" cy="300" r="130"/>
        <circle id="circlePath" fill="none" stroke="none" stroke-width="2" stroke-miterlimit="10" cx="400" cy="300" r="80"/>
      
      <g id="mainContainer" >
        <g id="car">
    <path id="carRot" fill="#FFF"  d="M45.6,16.9l0-11.4c0-3-1.5-5.5-4.5-5.5L3.5,0C0.5,0,0,1.5,0,4.5l0,13.4c0,3,0.5,4.5,3.5,4.5l37.6,0
      C44.1,22.4,45.6,19.9,45.6,16.9z M31.9,21.4l-23.3,0l2.2-2.6l14.1,0L31.9,21.4z M34.2,21c-3.8-1-7.3-3.1-7.3-3.1l0-13.4l7.3-3.1
      C34.2,1.4,37.1,11.9,34.2,21z M6.9,1.5c0-0.9,2.3,3.1,2.3,3.1l0,13.4c0,0-0.7,1.5-2.3,3.1C5.8,19.3,5.1,5.8,6.9,1.5z M24.9,3.9
      l-14.1,0L8.6,1.3l23.3,0L24.9,3.9z"/>      
        </g>
      </g>
    </svg>
  </div> -->

  <!-- scroll-to-top start -->
  <div class="scroll-to-top">
    <span class="scroll-icon">
      <i class="las la-arrow-up"></i>
    </span>
  </div>
  <!-- scroll-to-top end -->


  <!-- theme-switcher start -->
  <!-- <div class="theme-switcher">
    <div class="theme-switcher__icon">
      <i class="las la-cog"></i>
    </div>
    <div class="theme-switcher__body">
      <div class="single dark active">
        <a href="#">Dark Version</a>
      </div>
      <div class="single light mt-4">
        <a href="light-#">Light Version</a>
      </div>
    </div>
  </div> -->
  <!-- theme-switcher end -->
  <!-- page-wrapper start -->
  <div class="page-wrapper">
      <!-- login modal -->
  <div class="modal fade" id="loginModal" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <div class="account-form-area">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            <h3 class="title">Welcome Back</h3>
            <div class="account-form-wrapper">
              <form method="post" id="login-form" action="{{route('guest.login')}}">
                @csrf
                <div class="form-group">
                  <label>Email <sup>*</sup></label>
                  <input type="email" class="jqv-input" data-jqv-required="true" name="login_email" id="login_email" placeholder="Enter your Email">
                </div>
                <div class="form-group">
                  <label>password <sup>*</sup></label>
                  <input type="password" class="jqv-input" data-jqv-required="true" name="login_pass" id="login_pass" placeholder="password">
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-2">
                  <div class="custom-checkbox">
                    <input type="checkbox" name="id-1" id="id-1" checked>
                    <label for="id-1">Remember Password</label>
                    <span class="checkbox"></span>
                  </div>
                  <a href="#" class="link" data-bs-toggle="modal" data-bs-target="#forgotpassword">Forgot Password?</a>
                </div>
                <div class="form-group text-center mt-5">
                  <button class="cmn-btn">log in</button>
                </div>
              </form>
              <p class="text-center mt-4">Don't have an account? <a href="{{route('guest.register_page')}}"> Sign Up Now</a></p>
              <div class="divider">
                <span>or</span>
              </div>
              <ul class="social-link-list">
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="forgotpassword" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <div class="account-form-area">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            <h3 class="title">Reset Password</h3>
            <div class="account-form-wrapper">
              <form>
                <div class="form-group">
                  <label>Email <sup>*</sup></label>
                  <input type="email" name="signup_name" id="signup_name" placeholder="Enter Registered Email">
                </div>
                <div class="form-group text-center mt-5">
                  <button class="cmn-btn w-100">Reset Password</button>
                </div>
              </form>
              <p class="text-center mt-4"> Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sign Up modal -->
  <!-- <div class="modal fade" id="signupModal" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <div class="account-form-area">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            <h3 class="title">Open Free Account</h3>
            <div class="account-form-wrapper">
              <form>
                <div class="form-group">
                  <label>Email <sup>*</sup></label>
                  <input type="email" name="signup_name" id="signup_name" placeholder="Enter your Email">
                </div>
                <div class="form-group">
                  <label>password <sup>*</sup></label>
                  <input type="password" name="signup_pass" id="signup_pass" placeholder="password">
                </div>
                <div class="form-group">
                  <label>confirm password <sup>*</sup></label>
                  <input type="password" name="signup_re-pass" id="signup_re-pass" placeholder="Confirm Password">
                </div>
                <div class="d-flex flex-wrap mt-2">
                  <div class="custom-checkbox">
                    <input type="checkbox" name="id-2" id="id-2" checked>
                    <label for="id-2">I agree to the</label>
                    <span class="checkbox"></span>
                  </div>
                  <a href="#" class="link ml-1">Terms, Privacy Policy and Fees</a>
                </div>
                <div class="form-group text-center mt-5">
                  <button class="cmn-btn">log in</button>
                </div>
              </form>
              <p class="text-center mt-4"> Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></p>
              <div class="divider">
                <span>or</span>
              </div>
              <ul class="social-link-list">
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <!-- header-section start  -->
  <header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-6">
            <div class="left d-flex align-items-center">
              <a href="tel:65655655"><i class="las la-phone-volume"></i> Customer Support</a>
              <!-- <div class="language">
                <i class="las la-globe-europe"></i>
                <select>
                  <option>En</option>
                </select>
              </div> -->
            </div>
          </div>
          <div class="col-6">
            <div class="right">
              <!-- <div class="product__cart">
                <span class="total__amount">0.00</span>
                <a href="cart.html"  class="amount__btn">
                  <i class="las la-shopping-basket"></i>
                  <span class="cart__num">08</span>
                </a>
              </div> -->
              <a href="#" class="user__btn text-white" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="las la-user"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div><!-- header__top end -->
    <div class="header__bottom">
      <div class="container">
        <nav class="navbar navbar-expand-xl p-0 align-items-center">
          <a class="site-logo site-title" href="index.php"><img src="{{asset('/front/assets/')}}/images/logo-loto.png" alt="site-logo"><span class="logo-icon"><i class="flaticon-fire"></i></span></a>
          <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="menu-toggle"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav main-menu ms-auto">
              <li class="">
                <a href="index.php">Home</a>
              </li>
              <li class="">
                <a href="#">About Us</a>
              </li>
              <li><a href="#">How it Works</a></li>
              <li><a href="#">Know the Referrals</a></li>
            </ul>
            <div class="nav-right">
              <a href="#" class="cmn-btn style--three btn--sm"><img src="{{asset('/front/assets/')}}/images/icon/btn/tag.png" alt="icon" class="me-2"> Buy Tickets</a>
            </div>
          </div>
        </nav>
      </div>
    </div><!-- header__bottom end -->
  </header>
  <!-- header-section end  -->


  @yield('content')


  
      <!-- footer section start  -->
      <footer class="footer-section">
    <div class="bg-shape--top"><img src="{{asset('/front/assets/')}}/images/elements/round-shape-2.png" alt="image"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
          <div class="subscribe-area">
            <div class="left">
              <span class="subtitle">Subscribe to Big Loto</span>
              <h3 class="title">To Get Exclusive Benefits</h3>
            </div>
            <div class="right">
              <form class="subscribe-form">
                <input type="email" name="subscribe_email" id="subscribe_email" placeholder="Enter Your Email">
                <button type="submit">Subscribe</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container pt-50">
      <div class="row pb-5 align-items-center">
        <!-- <div class="col-lg-4">
          <ul class="app-btn">
            <li><a href="#"><img src="{{asset('/front/assets/')}}/images/icon/store-btn/1.png" alt="image"></a></li>
            <li><a href="#"><img src="{{asset('/front/assets/')}}/images/icon/store-btn/2.png" alt="image"></a></li>
          </ul>
        </div> -->
        <div class="col-lg-12">
          <ul class="short-links justify-content-center">
            <!-- <li><a href="#">Abount</a></li> -->
            <li><a href="#">FAQs</a></li>
            <!-- <li><a href="#">Contact</a></li> -->
            <li><a href="#">Terms of Services</a></li>
            <li><a href="#">Privacy</a></li>
          </ul>
        </div>
      </div>
      <hr>
      <div class="row py-5 align-items-center">
        <div class="col-lg-6">
          <p class="copy-right-text text-lg-start text-center mb-lg-0 mb-3">Copyright © 2024.All Rights Reserved</p>
        </div>
        <div class="col-lg-6">
          <ul class="social-links justify-content-lg-end justify-content-center">
            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  <!-- footer section end -->
  </div>
  <!-- page-wrapper end -->
  <!-- jQuery library -->
  <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js">
    
  </script><script src="{{asset('/front/assets/')}}/js/vendor/jquery-3.5.1.min.js"></script>
  <!-- bootstrap js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/bootstrap.bundle.min.js"></script>
  <!-- custom select js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/jquery.nice-select.min.js"></script> 
  <!-- lightcase js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/lightcase.js"></script>
  <!-- wow js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/wow.min.js"></script>
  <!-- slick slider js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/slick.min.js"></script>
  <!-- countdown js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/jquery.countdown.js"></script>
  <!-- jquery ui js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/jquery-ui.min.js"></script>
  <!-- datepicker js -->
  <script src="{{asset('/front/assets/')}}/js/vendor/datepicker.min.js"></script>
  <script src="{{asset('/front/assets/')}}/js/vendor/datepicker.en.js"></script>
  <!-- preloader -->
  <script src="{{asset('/front/assets/')}}/js/vendor/TweenMax.min.js"></script>
  <script src="{{asset('/front/assets/')}}/js/vendor/MorphSVGPlugin.min.js"></script>
  <script src="{{asset('/front/assets/')}}/js/preloader.js"></script>
  <!-- contact js -->
  <script src="{{asset('/front/assets/')}}/js/contact.js"></script>
  <!-- custom js -->
  <script src="{{asset('/front/assets/')}}/js/app.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{asset('/front/')}}/js/WebApp.js"></script>
  <script>
    jQuery(function() {
            WebApp.init({
                site_url: '{{ url("") }}',
                base_url: '{{ url("") }}',
                site_name: '{{ config('
                global.site_name ') }}',
            });

            WebApp.initTreeView();
        });

        WebApp.initFormView();
        
    $('body').off('submit', '#login-form');
    $('body').on('submit', '#login-form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();
        $form.validate({
            rules: {
                
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                element.addClass('is-invalid');
                error.addClass('error');
                error.insertAfter(element);
            }
        });

        //Bind extra rules. This must be called after .validate()
        WebApp.setJQueryValidationRules('#login-form');

        if ( $form.valid() ) {
            validation.resolve();
        } else {
            var error = $form.find('.error').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 1000),
            }, 500);
            validation.reject();
        }

        validation.done(function() {
            WebApp.loading(true);
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
                timeout: 600000,
                dataType: 'json',
                success: function(res) {
                    WebApp.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
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
                            var m = res['message'] ||
                            'Unable to submit your data. Please try again later.';
                            WebApp.alert(m, 'Oops!');
                        }
                    } else {
                        WebApp.alert(res['message'], 'Success!');
                                setTimeout(function(){
                                    window.location.href = res['oData']['redirect'];
                                },1500);
                        
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    WebApp.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                        WebApp.alert(e.responseText, 'Oops!');
                }
            });
        });
    });
  </script>
 
    @yield('script')
  </body>
</html>