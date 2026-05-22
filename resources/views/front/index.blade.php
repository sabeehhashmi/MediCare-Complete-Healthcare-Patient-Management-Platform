@extends('front.template.index-layout')

@section('title', 'Mednero')


@section('content')
 

    <style>
        @media (max-width: 767px) {
            #read-home #subscribe-wrapper {
                bottom: 95px !important;
            }
        }
        .service-detail-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        background: rgba(0, 191, 255, 0.1);
        border-radius: 50%;
        margin-left: 15px;
    }

    .service-detail-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(0, 191, 255, 0.2);
        padding-top: 8px;
    }

    .service-detail-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
        padding-top: 10px;
    }
    
     /*.contact-form-new-ui #form input{*/
     /*      width: 100%;*/
     /*}*/
     /*.contact-form-new-ui #form,*/
     /*.contact-form-new-ui #form-wrapper{*/
     /*      width: 100%;*/
         
     /*}*/
     /*.contact-form-new-ui #form div{*/
     /*    width: 100%;*/
     /*}*/
    </style>
    
    <!-- screen loader start -->
    <div class="screen-loader"></div>
    <!-- screen loader end -->


    <!-- preload start -->
    <div id="preload">
        <!-- preload status start -->
        <div id="preload-status"></div>
        <!-- preload status end -->
    </div>
    <!-- preload end -->


    <!-- curtains start -->
    <div id="curtains"></div>
    <!-- curtains end -->


    <!-- preload content start -->
    <div class="preload-content">
        <!-- home start -->
        <div id="home"></div>
        <!-- home end -->
    </div>
    <!-- preload content end -->


    <!-- top shade start -->
    <div id="top-shade" class="fade-in-7"></div>
    <!-- top shade end -->


    <!-- menu start -->
    <div class="fade-in-5">
        <div class="menu d-inline-flex align-items-center">
            <ul class="mb-0">
                <li><a id="fire-home" class="menu-state active" href="#">Home<span>Get started</span></a></li>
                <li><a id="fire-about" class="menu-state" href="#">About<span>Who we are</span></a></li>
                <li><a id="fire-services" class="menu-state" href="#">Services<span>What we do</span></a></li>
                <li><a id="fire-contact" class="menu-state" href="#">Contact<span>Get in touch</span></a></li>
                <li><a id="fire-doctor" class="menu-state fire-find-doctor" href="#">Find Doctor<span>Get a doctor</span></a></li>
                <li class="menu-item-has-children health-edu-li">
                    <a href="#">
                        Explore  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;"><path d="M16.293 9.293 12 13.586 7.707 9.293l-1.414 1.414L12 16.414l5.707-5.707z"></path></svg>
                        <span>Health Education</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/videos-list') }}"> Health Education Video</a></li>
                        <li><a href="{{ url('/wellness-list') }}">Wellness Tips</a></li>
                        <li><a href="{{ url('/faqs-list') }}">FAQs</a></li>
                    </ul>
                </li>
            </ul>

            <!-- <a href="{{ url('/auth') }}" style="margin-left: 16px; max-width: 140px;" id="" class="subscribe-btn" >
                <span>Login</span>
            </a> -->
           
           
             @guest
            <a href="{{ url('/auth') }}" style="margin-left: 8px; max-width: 140px;" class="subscribe-btn">
                <span>Login</span>
            </a>
            @endguest

            @auth
            <a href="{{ route('front.profile') }}" style="margin-left: 8px; max-width: 140px;" class="subscribe-btn">
                <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </a>
            @endauth

        </div>
    </div>
    <!-- menu end -->


    <!-- menu mobile start -->
    <div class="fade-in-5">
        <div class="menu-mobile">
            <ul>
                <li>
                    <ul class="menu-mobile-list" id="menuMobileList">
                        <li class="lifting"><a id="fire-home-mobile" class="menu-state active" href="#">Home</a></li>
                        <li class="lifting" style="margin-right: -50px;"><a id="fire-about-mobile" class="menu-state"
                                href="#">About</a></li>
                        <li class="lifting"><a id="fire-services-mobile" class="menu-state" href="#">Services</a></li>
                        <li class="lifting-first"><a id="fire-contact-mobile" class="menu-state" href="#">Contact</a></li>
                        <li class="lifting-first"><a id="fire-doctor-mobile" class="menu-state fire-find-doctor" href="#">Find Doctor</a></li>
                    </ul>
                    <a class="menu-mobile-trigger" href="#" id="menuMobileTrigger"></a>
                </li>
            </ul>

            <!-- <a href="{{ url('/auth') }}" style="margin-left: 16px; max-width: 140px;" id="" class="subscribe-btn" >
                <span>Login</span>
            </a> -->
            @guest
            <a href="{{ url('/auth') }}" style="margin-left: 16px; max-width: 140px;" class="subscribe-btn">
                <span>Login</span>
            </a>
            @endguest

            @auth
            <a href="{{ route('front.profile') }}" style="margin-left: 16px; max-width: 140px;" class="subscribe-btn">
                <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </a>
            @endauth
        </div>
    </div>

    <!-- menu mobile end -->

    @php
        $settings = \App\Models\SettingsModel::first();
    @endphp


    <!--<a href="tel:+{{ preg_replace('/[^0-9+]/', '', $settings->support_phone ?? '112') }}" class="emergency-btn fade-in-5">-->
    <!--  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="36" x="0" y="0" viewBox="0 0 90 90" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g fill-rule="evenodd" clip-rule="evenodd"><path d="M46.024 12.131a31.3 31.3 0 0 0-18.437 5.256A31.31 31.31 0 0 0 14.925 34.67c-.557-.068-1.224-.038-2.016.186-2.957.84-5.18 3.262-6.18 5.396-1.298 2.781-1.845 6.46-1.268 10.256.573 3.785 2.172 6.905 4.172 8.842 2.006 1.938 4.271 2.568 6.521 2.095 3.35-.715 5.011-1.25 4.542-4.37l-2.27-15.13a26.604 26.604 0 0 1 11.786-20.636 26.588 26.588 0 0 1 30.984 1.016A26.599 26.599 0 0 1 71.571 41.98l-1.588 10.583A26.59 26.59 0 0 1 47.5 69.923h-6.766a3.145 3.145 0 0 0-3.152 3.15v1.662a3.145 3.145 0 0 0 3.152 3.151h8.531a3.14 3.14 0 0 0 3.145-3.15v-.87A31.408 31.408 0 0 0 71.114 60.72l2.733.725c2.224.577 4.516-.158 6.521-2.095 2-1.938 3.598-5.057 4.172-8.842.58-3.797.016-7.469-1.266-10.256-1.287-2.787-3.204-4.557-5.417-5.192-.927-.267-1.933-.365-2.796-.39a31.276 31.276 0 0 0-10.99-16.085 31.29 31.29 0 0 0-18.047-6.454z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path><path d="M56.01 39.35a4.092 4.092 0 0 1 4.087 4.088 4.095 4.095 0 0 1-4.088 4.088 4.093 4.093 0 0 1-4.094-4.088 4.091 4.091 0 0 1 4.094-4.089zm-11.01 0a4.088 4.088 0 1 1-4.09 4.089 4.086 4.086 0 0 1 4.09-4.09zm-11.007 0a4.089 4.089 0 1 1 0 8.176 4.089 4.089 0 0 1 0-8.177zM45 21.786c-11.99 0-21.652 9.344-21.652 21.651 0 5.911 2.235 11.135 5.876 14.968l-1.292 5.792c-.426 1.906.896 3.188 2.61 2.234l5.655-3.155A22.035 22.035 0 0 0 45 65.09c11.994 0 21.65-9.338 21.65-21.65 0-12.308-9.656-21.652-21.65-21.652z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path></g></g></svg>-->
    <!--  <span class="tooltip">Emergency Help</span>-->
    <!--</a>-->


    <!-- Preload YouTube API for faster video loading -->
    <link rel="preload" href="https://www.youtube.com/player_api" as="script">

    <!-- YTPlayer start -->
    <!-- Desktop Video Player -->
    <a href="https://www.youtube.com/watch?v=RnPazBlgdVA" id="bgndVideo-desktop"
        class="player {containment:'body', autoPlay:true, mute:true, startAt:20, opacity:1, loop:true, showControls:false, ratio:'16/9', quality:'hd1080', realfullscreen:true, gaTrack:false, addRaster:false, anchor:'center,center', poster:''}"
        style="display: none;"></a>

    <!-- Mobile Video Player -->
    <a href="https://www.youtube.com/watch?v=UMfCe4C2XBI" id="bgndVideo-mobile"
        class="player {containment:'body', autoPlay:true, mute:true, startAt:20, opacity:1, loop:true, showControls:false, ratio:'16/9', quality:'hd1080', realfullscreen:true, gaTrack:false, addRaster:false, anchor:'center,center', poster:''}"
        style="display: none;"></a>
    <!-- YTPlayer end -->

    <!-- Mobile Video Overlay for better text visibility -->
    <div class="mobile-video-overlay"></div>


    <!-- home start -->
    <div id="read-home" class="upper-page current">


        <!-- borders start -->
        <div class="fade-in-7">
            <div class="borders"></div>
        </div>
        <!-- borders end -->


        <!-- upper content start -->
        <div class="upper-content">
            <!-- container start -->
            <div class="container center">
                <!-- intro wrapper start -->
                <div id="intro-wrapper">

                    <!-- eight columns start -->
                    <div class="eight columns column">

                        <!-- fade in 1 start -->
                        <div class="fade-in-1">
                            <h3 id="homepage-content">GLOBAL TELEHEALTH <span style="padding-left: 7px;">CARE</span>
                            </h3>
                        </div>
                        <!-- fade in 1 end -->

                        <!-- fade in 2 start -->
                        <div class="fade-in-2">
                            <div class="mednero-logo">
                                <img src="images/logo%202%20cropped.png" alt="MedNero">
                            </div>
                        </div>
                        <!-- fade in 2 end -->

                        <!-- fade in 3 start -->
                        <div class="fade-in-3" id="home-slider-wrapper-mobile">
                            <!-- slider start -->
                            <div id="slider">
                                <div class="slide">
                                    <h2>THE WORLD’S BEST DOCTORS— </br><span
                                            style="font-weight: bold; color: #00bfff;">AT YOUR FINGERTIPS. </span> </h2>
                                </div>
                                <div class="slide">
                                    <h2>YOUR HEALTH. OUR PRIORITY. </br> <span
                                            style="font-weight: bold; color: #00bfff;">ANYWHERE, ANYTIME.</span></h2>
                                </div>


                            </div>

                            <!-- slider end -->
                        </div>
                        <!-- fade in 3 end -->

                        <!-- fade in 4 start -->
                        <!-- social icons start -->
                        <div id="social-icons-wrapper" class="fade-in-4">
                            <ul class="social-icons">
                                @if($contactUs)
                                    <li><a href="{{ $contactUs->facebook ?? '#' }}"><img src="images/social/facebook.png" alt="Facebook"></a></li>
                                    <li><a href="{{ $contactUs->instagram ?? '#' }}"><img src="images/social/instagram%20white.png" alt="Instagram"></a></li>
                                    <li><a href="{{ $contactUs->linkedin ?? '#' }}"><img src="images/social/linkedin.png" alt="LinkedIn"></a></li>
                                    <li><a href="{{ $contactUs->twitter ?? '#' }}"><img src="images/social/x%20white.png" alt="X"></a></li>
                                @endif
                            </ul>
                        </div>
                        <!-- social icons end -->
                        <!-- fade in 4 end -->

                    </div>
                    <!-- eight columns end -->

                    <!-- eight columns start -->
                    <div class="eight columns column">

                        <!-- countdown start -->
                        <div id="countdown-wrapper" class="fade-in-6" style="display: none !important;">
                            <h6>We're launching in</h6>
                            <ul id="countdown">
                                <!-- days start -->
                                <li><span class="days">00</span>
                                    <p class="timeRefDays">days</p>
                                </li>
                                <!-- days end -->
                                <!-- hours start -->
                                <li><span class="hours">00</span>
                                    <p class="timeRefHours">hours</p>
                                </li>
                                <!-- hours end -->
                                <!-- minutes start -->
                                <li><span class="minutes">00</span>
                                    <p class="timeRefMinutes">minutes</p>
                                </li>
                                <!-- minutes end -->
                                <!-- seconds start -->
                                <li><span class="seconds">00</span>
                                    <p class="timeRefSeconds">seconds</p>
                                </li>
                                <!-- seconds end -->
                            </ul>
                        </div>
                        <!-- countdown end -->

                        <!-- newsletter form start -->
                        <div id="subscribe-wrapper" class="fade-in-6" style="">
                            <!-- <h6>Find the Doctor</h6> -->
                            <div id="newsletter">
                                <div class="newsletter">
                                    <form id="subscribe" method="post" action="#" name="subscribe">
                                        <!-- <label class="mail"> EMAIL *</label> -->
                                        <div class="btn_bottom">

                                            

                                            <!-- <input id="subscribeemail" name="email" type="text" class="subscriberequiredField subscribeemail"> -->
                                            <!-- <input id="fire-find-doctor" type="button" value="FIND NOW"
                                                style="background: white; border: none; padding: 0px 0px; margin-left: 0; cursor: pointer; font-weight: bold;"> -->
                                            <button id="fire-find-doctor" class="fire-find-doctor subscribe-btn" type="button"
                                                style="margin-left: 0; cursor: pointer; font-weight: bold;">
                                                <span>FIND DOCTOR NOW</span> 
                                            </button>
                                            
                                            <a href="tel:+{{ preg_replace('/[^0-9+]/', '', $settings->support_phone ?? '112') }}" class="emergency-btn fade-in-5" style="position: relative">
                                              <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="36" x="0" y="0" viewBox="0 0 90 90" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g fill-rule="evenodd" clip-rule="evenodd"><path d="M46.024 12.131a31.3 31.3 0 0 0-18.437 5.256A31.31 31.31 0 0 0 14.925 34.67c-.557-.068-1.224-.038-2.016.186-2.957.84-5.18 3.262-6.18 5.396-1.298 2.781-1.845 6.46-1.268 10.256.573 3.785 2.172 6.905 4.172 8.842 2.006 1.938 4.271 2.568 6.521 2.095 3.35-.715 5.011-1.25 4.542-4.37l-2.27-15.13a26.604 26.604 0 0 1 11.786-20.636 26.588 26.588 0 0 1 30.984 1.016A26.599 26.599 0 0 1 71.571 41.98l-1.588 10.583A26.59 26.59 0 0 1 47.5 69.923h-6.766a3.145 3.145 0 0 0-3.152 3.15v1.662a3.145 3.145 0 0 0 3.152 3.151h8.531a3.14 3.14 0 0 0 3.145-3.15v-.87A31.408 31.408 0 0 0 71.114 60.72l2.733.725c2.224.577 4.516-.158 6.521-2.095 2-1.938 3.598-5.057 4.172-8.842.58-3.797.016-7.469-1.266-10.256-1.287-2.787-3.204-4.557-5.417-5.192-.927-.267-1.933-.365-2.796-.39a31.276 31.276 0 0 0-10.99-16.085 31.29 31.29 0 0 0-18.047-6.454z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path><path d="M56.01 39.35a4.092 4.092 0 0 1 4.087 4.088 4.095 4.095 0 0 1-4.088 4.088 4.093 4.093 0 0 1-4.094-4.088 4.091 4.091 0 0 1 4.094-4.089zm-11.01 0a4.088 4.088 0 1 1-4.09 4.089 4.086 4.086 0 0 1 4.09-4.09zm-11.007 0a4.089 4.089 0 1 1 0 8.176 4.089 4.089 0 0 1 0-8.177zM45 21.786c-11.99 0-21.652 9.344-21.652 21.651 0 5.911 2.235 11.135 5.876 14.968l-1.292 5.792c-.426 1.906.896 3.188 2.61 2.234l5.655-3.155A22.035 22.035 0 0 0 45 65.09c11.994 0 21.65-9.338 21.65-21.65 0-12.308-9.656-21.652-21.65-21.652z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                                              <span class="tooltip">Emergency Help</span>
                                            </a>
                                        </div>

                                        <!-- <input id="submit" type="submit" value="SUBSCRIBE"> -->
                                    </form>
                                </div>
                            </div>





                        </div>
                        <!-- newsletter form end -->

                    </div>
                    <!-- eight columns end -->

                </div>
                <!-- intro wrapper end -->
            </div>
            <!-- container end -->
        </div>
        <!-- upper content end -->


    </div>
    <!-- home end -->


    <!-- about start -->
    <div id="about" class="lower-page">
    <!-- lower content start -->
    <div class="lower-content">

        <!-- about intro start -->
        <div class="about-intro">
            <!-- <h4>About/Who we are</h4> -->
        </div>
        <!-- about intro end -->

        <!-- container start -->
        <div class="container center">



            <!-- sixteen columns start -->
            <div class="sixteen columns">
                <!-- divider left top start -->
                <div class="divider-left-top"></div>
                <!-- divider left top end -->
            </div>
            <!-- sixteen columns end -->

            <!-- sixteen columns start -->
            <div class="sixteen columns">
                <!-- divider right top start -->
                <div class="divider-right-top"></div>
                <!-- divider right top end -->
            </div>
            <!-- sixteen columns end -->

            <!-- about full start -->
            <!-- sixteen columns start -->
            <div class="sixteen columns about-content-management">
                <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
                    <!-- <h3>About Us</h3> -->
                    <div
                        style="display: flex; align-items: center; justify-content: center; gap: 20px; margin: 0px 0; flex-wrap: wrap; margin-bottom: 15px">
                        <style>
                            @media (max-width: 600px) {
                                .mednero-logo img {
                                    max-width: 240px !important;
                                }
                            }
                        </style>
                        <div class="mednero-logo" style="margin-bottom: 0 !important;">
                            <img src="images/logo%202%20cropped.png" alt="MedNero"
                                style="max-width: 480px; height: auto;">
                        </div>
                        <h4 style="font-size: 2rem; margin: 0; padding-top: 40px; color:white; ">Transforming Global
                            Healthcare Access</h4>
                    </div>



                    <!-- <p><strong style="color: #00bfff; font-weight: bold; font-size: 2rem;">About
                            MedNero</strong><br>
                        Mednero Medical Treatment Facilitation Services LLC, headquartered in Dubai, UAE, proudly
                        introduces <span style="font-weight: bold;"> MedNero</span></span>—a pioneering hybrid
                        telehealth and medical travel platform designed to redefine access to advanced healthcare
                        worldwide. Our mission is to connect patients with top-tier medical expertise and
                        facilities, leveraging cutting-edge technology and personalized support at every stage of
                        the healthcare journey.
                        </br><span style="margin-top: 5px; display: inline-block;">With a strong presence in the
                            Middle East (including Gulf and GCC countries), Africa, and South Asia, <span
                                style="font-weight: bold;"> MedNero</span> addresses the growing demand for
                            seamless, cross-border healthcare solutions. We combine the convenience of virtual
                            medical consultations with the assurance of in-person treatment coordination, ensuring
                            every patient receives world-class care tailored to their unique needs.</span>
                        </br><span style="margin-top: 5px; display: inline-block;">Through strategic partnerships
                            with leading international hospitals and certified medical professionals, <span
                                style="font-weight: bold;"> MedNero</span> facilitates access to a comprehensive
                            range of medical services—from expert teleconsultations and second opinions to
                            full-service medical travel arrangements. Our integrated platform empowers patients to
                            make informed decisions, receive timely care, and experience the highest standards of
                            safety, privacy, and support—wherever they are in the world.</span> </p> -->


                    @if($aboutPage)
                    {!! $aboutPage->desc_en !!}
                    @endif


                </div>



                <div class="logo-message-flex"
                    style="display: flex; align-items: center; gap: 48px; flex-wrap: nowrap; margin-top: 50px;">
                    <img src="images/logo%202%20cropped.png" alt="MedNero Logo"
                        style="max-width: 50%; height: auto; display: block;" class="responsive-logo">
                    <span
                        style="font-size: 1.5rem; font-weight: bold; line-height: 1; margin-bottom:-30px;">Your
                        gateway to secure, reliable,</br> and world-class healthcare— </br>anytime,
                        anywhere.</span>
                </div>
                <style>
                    @media (max-width: 600px) {
                        .logo-message-flex {
                            flex-wrap: wrap !important;
                            flex-direction: column;
                            align-items: flex-start;
                        }

                        .responsive-logo {
                            max-width: 100% !important;
                        }
                    }
                </style>
                
                
                    
                    <!-- closer start -->
                    <div class="fire-closer">
                        <a id="fire-services-closer" class="menu-state" href="#"><img src="images/fire-closer.png" alt="Closer"></a>
                    </div>
                    <!-- closer end -->
                    
                    
            </div>
        </div>
        <!-- sixteen columns end -->
        <!-- about full end -->



        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider blank start -->
            <div class="divider-blank"></div>
            <!-- divider blank end -->
        </div>
        <!-- sixteen columns end -->

        <!-- additional content start -->
        <!-- sixteen columns start -->
        <div class="sixteen columns">

        </div>
        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider blank start -->
            <div class="divider-blank"></div>
            <!-- divider blank end -->
        </div>
        <!-- sixteen columns end -->


        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider blank start -->
            <div class="divider-blank"></div>
            <!-- divider blank end -->
        </div>
        <!-- sixteen columns end -->

        <!-- sixteen columns start -->

        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider blank start -->
            <div class="divider-blank"></div>
            <!-- divider blank end -->
        </div>
        <!-- sixteen columns end -->

        <!-- sixteen columns start -->

        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider blank start -->
            <div class="divider-blank"></div>
            <!-- divider blank end -->
        </div>
        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider left bottom start -->
            <div class="divider-left-bottom"></div>
            <!-- divider left bottom end -->
        </div>
        <!-- sixteen columns end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider right bottom start -->
            <div class="divider-right-bottom"></div>
            <!-- divider right bottom end -->
        </div>
        <!-- sixteen columns end -->

    </div>
    <!-- container end -->

    <!-- sixteen columns start -->
    <div class="sixteen columns">
        <!-- divider fin start -->
        <div class="divider-fin"></div>
        <!-- divider fin end -->
    </div>
    <!-- sixteen columns end -->

</div>
    <!-- about end -->


    <!-- services start -->
<!-- services start -->
<div id="services" class="lower-page">
    <div class="lower-content">
        <div class="services-intro">
            <h4>Services</h4>
        </div>

        <div class="container center">
            <div class="sixteen columns">
                <div class="divider-left-top"></div>
            </div>
            <div class="sixteen columns">
                <div class="divider-right-top"></div>
            </div>

            @foreach($websiteServices as $service)
            <div class="sixteen columns">
                <div class="service-detail-section">
                    <div class="service-detail-header">
                        <div class="service-detail-icon">
                            @if($service->icon_type == 'fontawesome' && $service->icon)
                                <i class="{{ $service->icon }}" style="font-size: 36px; color: #00bfff;"></i>
                            @elseif($service->icon_type == 'image' && $service->icon_url)
                                <img src="{{ $service->icon_url }}" alt="{{ $service->title }}" style="width: 36px; height: 36px;">
                            @else
                                <!-- Default icon if none set -->
                                <i class="fas fa-stethoscope" style="font-size: 36px; color: #00bfff;"></i>
                            @endif
                        </div>
                        <h5 class="service-detail-title">{{ $service->title }}</h5>
                    </div>
                    <div class="service-detail-content">
                        {!! $service->desc !!}
                    </div>
                </div>
            </div>
            @endforeach

            @if($servicePage)
            <div class="sixteen columns">
                {!! $servicePage->desc_en !!}
            </div>
            @endif
        </div>
    </div>

    <div class="fire-closer">
        <a id="fire-services-closer" class="menu-state" href="#"><img src="images/fire-closer.png" alt="Closer"></a>
    </div>

    <div class="sixteen columns">
        <div class="divider-fin"></div>
    </div>
</div>
<!-- services end -->
    <!-- services end -->


    <!-- contact start -->

    <div id="contact" class="lower-page">
    <div class="lower-content">
        <div class="contact-intro">
            <h4>Contact</h4>
        </div>
 
        <div class="container center">
            <div class="sixteen columns">
                <div class="divider-left-top"></div>
            </div>
            <div class="sixteen columns">
                <div class="divider-right-top"></div>
            </div>
 
            <div class="row" id="contact-row-mobile">
                <div class="one-third column">
                    <div class="info-address">
                        <p><strong>Address</strong></p>
                        <p>{{ $contactUs ? $contactUs->location : '' }}</p>
                    </div>
                </div>
                <div class="one-third column">
                    <div class="info-phone">
                        <p><strong>Telephone</strong></p>
                        <p>{{ $contactUs ? $contactUs->mobile : '' }}</p>
                    </div>
                </div>
                <div class="one-third column">
                    <div class="info-email">
                        <p><strong>Email</strong></p>
                        @if($contactUs)
                            <p><a href="mailto:{{ $contactUs->email }}">{{$contactUs->email}}</a></p>
                        @endif
                    </div>
                </div>
            </div>
 
            <!--<div class="sixteen columns">-->
            <!--    <div class="divider-blank"></div>-->
            <!--</div>-->
 
            <!--<div class="sixteen columns">-->
            <!--    <div id="twitter-wrap">-->
            <!--        <div id="twitter-logo">-->
            <!--            <div id="tweets">-->
            <!--                <div id="ticker" class="query"></div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div> -->
            <div class="sixteen columns">
                <!--<div class="divider-blank"></div>-->
                <div class="info-">
                    <p class="">If you have any questions, require medical assistance, or need support with appointments, billing, or your account, please complete the contact form below with accurate and detailed information. Our medical support team will carefully review your request and respond at the earliest possible time. Please note that this form is intended for general inquiries and non-emergency matters only. All information provided will be treated with strict confidentiality and handled in accordance with our privacy and data protection policies.</p>
                </div>
            </div>
 
            <div class="sixteen columns contact-form-new-ui"> 
                <div id="form-wrapper">
                    <div class="contact-intro-bottom">
                        <div class="contact-intro-bottom-title">Get in Touch</div>
                    </div>
                    
                    <form id="form" action="{{ route('front.post.contactus') }}" method="post" name="send" enctype="multipart/form-data">
                        @csrf
                        <div class="holder">
                            <div>
                                <label>NAME *</label>
                                <input id="name" name="name" type="text" autocomplete="name" class="requiredField name">
                            </div>
                            <div>
                                <label>EMAIL *</label>
                                <input id="email" name="email" type="text" autocomplete="email" class="requiredField email">
                            </div>
                            <div>
                                <label>SUBJECT *</label>
                                <input name="subject" type="text" class="requiredField subject">
                            </div>
                            <div>
                                <label>Upload File *</label>
                                <input name="image" type="file" class="requiredField subject">
                            </div>
                        </div>
                        <div class="holder">
                            <div>
                                <label>MESSAGE</label>
                                <textarea name="message" rows="6" cols="60"></textarea>
                            </div>
                            <div>
                                <input class="submit" type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="sixteen columns">
                <div class="divider-left-bottom"></div>
            </div>
            <div class="sixteen columns">
                <div class="divider-right-bottom"></div>
            </div>
        </div> <div class="sixteen columns">
            <div class="divider-fin"></div>
        </div>
    </div> <div class="fire-closer">
        <a id="fire-contact-closer" class="menu-state" href="#"><img src="images/fire-closer.png" alt="Closer"></a>
    </div>
 
    <div class="sixteen columns">
        <div class="divider-fin"></div>
    </div>
</div>




    <!-- Find Doctor start -->
    <div id="find-doctor" class="lower-page">
        <!-- lower content start -->
        <div class="lower-content">

            <!-- services intro start -->
            <div class="services-intro">
                <h4>Find A Doctor</h4>
            </div>
            <!-- services intro end -->

            <!-- container start -->
            <div class="container center">



                <!-- sixteen columns start -->
                <div class="sixteen columns">
                    <!-- divider left top start -->
                    <div class="divider-left-top"></div>
                    <!-- divider left top end -->
                </div>
                <!-- sixteen columns end -->


                <!-- sixteen columns start -->
                <div class="sixteen columns">
                    <div class="service-detail-section find-form-section">
                        <div class="service-detail-header">
                            <h5 class="service-detail-title">Search for a doctor</h5>
                        </div>

                        <form method="GET" action="{{route('doctor_list')}}" id="search-doctor-form" novalidate>
                            <!-- row-cols-lg-auto -->
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctors Specialty</label> -->
                                        <select name="specialty_id" class="form-select select2-single w-100"
                                            data-placeholder="Doctors Specialty" id="specialty">
                                            <option value="">Doctors Specialty</option>
                                            @foreach($specialties as $id => $value)
                                            <option value="{{$id}}" {{ request('specialty_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon specialty-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                                        <div class="form-floating mb-3">
                                                            <input type="email" class="form-control" id="floatingemailInput" placeholder="Enter Email address">
                                                            <label for="floatingemailInput">Doctors Specialty</label>
                                                        </div>
                                                    </div> -->
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">My Insurance Policy</label> -->
                                        <select name="insurance_id" class="form-select select2-single" data-placeholder="My Insurance Policy" id="insurance-policy">
                                            <option value="">My Insurance Policy</option>
                                            @foreach($insurencePolicies as $id => $value)
                                            <option value="{{$id}}" {{ request('insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon insu-policy-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                       
                                        <select name="sub_insurance_id" class="form-select select2-single" data-placeholder="My Insurance Network" id="sub-insurance-policy">
                                            <option value="">Sub Insurance</option>
                                            @foreach($subInsurencePolicies as $id => $value)
                                            <option value="{{$id}}" {{ request('sub_insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon sub-policy-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">My Medical Condition</label> -->
                                        <select name="medical_condition_id" class="form-select select2-single" data-placeholder="My Medical Condition" id="interest">
                                            <option value="">My Medical Condition</option>
                                            @foreach($medicalConditions as $id => $value)
                                            <option value="{{$id}}" {{ request('medical_condition_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon medical-condition-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Language</label> -->
                                        <select name="language_id" class="form-select select2-single" data-placeholder="Doctor’s Language" id="language">
                                            <option value="">Doctor’s Language</option>
                                            @foreach($languages as $id => $value)
                                            <option value="{{$id}}" {{ request('language_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-language-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                               
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for=""> Doctor’s Country of Origin</label> -->
                                        <select name="cuntry_of_origin_id" class="form-select select2-single" data-placeholder="Doctor’s Country of Origin" id="countryOrigin">
                                            <option value=""> Doctor’s Country of Origin</option>
                                            @foreach($countries as $id => $value)
                                            <option value="{{$id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon counrty-orgin-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Gender</label> -->
                                        <select name="gender_id" class="form-select select2-single"  data-placeholder="Doctor’s Gender" id="gender">
                                            <option value="">Doctor’s Gender</option>
                                            @foreach($genders as $id => $value)
                                            <option value="{{$id}}" {{ request('gender_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-gender-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Cities</label> -->
                                        <select name="emirates_id" class="form-select select2-single emirates" data-placeholder="Emirates" id="emirates">
                                            <option value="">Cities</option>
                                            @foreach($emirates as $id => $value)
                                            <option value="{{$id}}" {{ request('emirates_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <i class="custom-icon doc-location-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Area</label> -->
                                        <select name="area_id" class="form-select select2-single" data-placeholder="Area" id="area">
                                            <option value="">Area</option>
                                           
                                        </select>
                                        <i class="custom-icon doc-area-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Hospital/ Clinic / Dental Care</label> -->
                                        <select name="hospital_id" class="form-select select2-single" data-placeholder="Hospital/ Clinic / Dental Care" id="hospital">
                                                                    <option value="">Hospital/ Clinic / Dental Care</option>
                                                                    @foreach($hospitals as  $hospital)
                                                                    <option value="{{$hospital->id}}" {{ request('hospital_id') == $id ? 'selected' : '' }}>{{$hospital->name_en}}</option>
                                                                    @endforeach
                                                                </select>
                                        <i class="custom-icon hospital-icon-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <!-- <label class="form-label" for="">Doctor’s Name</label> -->
                                        <input type="text" class="form-control" placeholder="Doctor’s Name" id="doctor"
                                            name="doctor_name">
                                        <i class="custom-icon doctor-name-icn" style="margin-top: 2px;"></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-8  extra-filters">
                                    <div class="position-relative select-custom-icon mb-3">
                                        <label class="form-label" for="">Search by Distance (500 Km)</label>
                                        <input type="range" name="distance" class="form-range" min="0" max="500"
                                            id="customRange3" value="0">
                                        <!-- <div class="d-flex justify-content-between">
                                                                <div class="text-muted">1 km</div>
                                                                <div class="text-muted">100 km</div>
                                                            </div>  -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
                                            Direct Calling
                                            Number for Appointment
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4  extra-filters">
                                    <div class="form-check">
                                        <input type="checkbox" name="ready_to_consult_instantly"
                                            class="form-check-input" id="auth-remember-check2" value="1">
                                        <label class="form-check-label ms-2" for="auth-remember-check2"> Ready to
                                            consult instantly</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4">
                                    <div class="d-flex mb-3">

                                        <a type="button" class="btn btn-outline-primary filter-button me-2">
                                            <span class="filter-on">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" x="0" y="0" viewBox="0 0 511.995 511.995" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M437.126 74.939c-99.826-99.826-262.307-99.826-362.133 0C26.637 123.314 0 187.617 0 256.005s26.637 132.691 74.993 181.047c49.923 49.923 115.495 74.874 181.066 74.874s131.144-24.951 181.066-74.874c99.826-99.826 99.826-262.268.001-362.113zM409.08 409.006c-84.375 84.375-221.667 84.375-306.042 0-40.858-40.858-63.37-95.204-63.37-153.001s22.512-112.143 63.37-153.021c84.375-84.375 221.667-84.355 306.042 0 84.355 84.375 84.355 221.667 0 306.022z" fill="#00bfff"></path><path d="m341.525 310.827-56.151-56.071 56.151-56.071c7.735-7.735 7.735-20.29.02-28.046-7.755-7.775-20.31-7.755-28.065-.02l-56.19 56.111-56.19-56.111c-7.755-7.735-20.31-7.755-28.065.02-7.735 7.755-7.735 20.31.02 28.046l56.151 56.071-56.151 56.071c-7.755 7.735-7.755 20.29-.02 28.046 3.868 3.887 8.965 5.811 14.043 5.811s10.155-1.944 14.023-5.792l56.19-56.111 56.19 56.111c3.868 3.868 8.945 5.792 14.023 5.792a19.828 19.828 0 0 0 14.043-5.811c7.733-7.756 7.733-20.311-.022-28.046z" fill="#00bfff"></path></g></svg>    
                                            </span>
                                            <span  class="filter-off">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18"  x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M17 5a3 3 0 1 1 3 3 3 3 0 0 1-3-3zM2 6h12a1 1 0 0 0 0-2H2a1 1 0 0 0 0 2zm6 3a3 3 0 0 0-2.82 2H2a1 1 0 0 0 0 2h3.18A3 3 0 1 0 8 9zm14 2h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2zm-12 7H2a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2zm12 0h-3.18a3 3 0 1 0 0 2H22a1 1 0 0 0 0-2z" opacity="1"  fill="#00bfff"></path></g></svg>
                                            </span>
                                        </a>
                                        
                                        <button type="submit" id="searchBtn" class="btn btn-primary w-lg w-100">
                                            Search
                                        </button>
                                        </div>
                                </div>
                            </div>
    
                            <!-- <div class="mb-3">
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="floatingCheck">
                                                        <label class="form-check-label" for="floatingCheck">
                                                          Check me out
                                                        </label>
                                                    </div>
                                                </div> -->
                        </form>

                    </div>
                </div>
                <!-- sixteen columns end -->

            </div>
            <!-- container end -->

            <!-- sixteen columns start -->
            <div class="sixteen columns">
                <!-- divider fin start -->
                <div class="divider-fin"></div>
                <!-- divider fin end -->
            </div>
            <!-- sixteen columns end -->

        </div>
        <!-- lower content end -->

        <!-- closer start -->
        <div class="fire-closer">
            <a id="fire-services-closer" class="menu-state" href="#"><img src="images/fire-closer.png" alt="Closer"></a>
        </div>
        <!-- closer end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider fin start -->
            <div class="divider-fin"></div>
            <!-- divider fin end -->
        </div>
        <!-- sixteen columns end -->

    </div>
    <!-- Find Doctor end -->





    <!-- Find Pharmacy start -->
    <div id="find-pharmacy" class="lower-page">
        <!-- lower content start -->
        <div class="lower-content">

            <!-- services intro start -->
            
            <!-- services intro end -->

            <!-- container start -->
            <div class="container center">



                <!-- sixteen columns start -->
                <div class="sixteen columns">
                    <!-- divider left top start -->
                    <div class="divider-left-top"></div>
                    <!-- divider left top end -->
                </div>
                <!-- sixteen columns end -->


                <!-- sixteen columns start -->
                
                <!-- sixteen columns end -->

            </div>
            <!-- container end -->

            <!-- sixteen columns start -->
            <div class="sixteen columns">
                <!-- divider fin start -->
                <div class="divider-fin"></div>
                <!-- divider fin end -->
            </div>
            <!-- sixteen columns end -->

        </div>
        <!-- lower content end -->

        <!-- closer start -->
        <div class="fire-closer">
            <a id="fire-services-closer" class="menu-state" href="#"><img src="images/fire-closer.png" alt="Closer"></a>
        </div>
        <!-- closer end -->

        <!-- sixteen columns start -->
        <div class="sixteen columns">
            <!-- divider fin start -->
            <div class="divider-fin"></div>
            <!-- divider fin end -->
        </div>
        <!-- sixteen columns end -->

    </div>
    <!-- Find Pharmacy end -->


@section('scripts')
<script>
$(document).ready(function() {
    function handleHash() {
        var hash = window.location.hash;
        if (hash === '#about') {
            $('#fire-about').click();
        } else if (hash === '#contact') {
            $('#fire-contact').click();
        }
    }

    // Handle hash on page load
    // Adding a slight delay to ensure the template's own scripts are ready
    setTimeout(handleHash, 500);

    // Handle hash changes
    $(window).on('hashchange', function() {
        handleHash();
    });
});
</script>
@endsection
@endsection

@section('scripts')
<script>
 
    document.addEventListener("DOMContentLoaded", function () {
    
        let hasLocation = {{ session()->has('current_latitude') ? 'true' : 'false' }};
    
        if (!hasLocation) {
    
            if (navigator.geolocation) {
    
                navigator.geolocation.getCurrentPosition(function (position) {
    
                    fetch("{{ route('store.location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
    
                }, function (error) {
    
                    // If user denies location → fallback to Dubai
                    fetch("{{ route('store.location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude: 25.2048,
                            longitude: 55.2708
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
    
                });
    
            }
        }
    
    });
    
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("searchBtn").addEventListener("click", function () {
            
            document.getElementById("search-doctor-form").submit();
        });
    });
   
    </script>
@endsection