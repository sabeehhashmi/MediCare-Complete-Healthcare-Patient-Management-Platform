@extends('front.template.layout')

@section('title', 'Videos')

@section('styles')

@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        
        

        <!-- Breadcrumb section Start-->
    <div class="breadcrumb-section two">
        <div class="swiper home2-banner-slider mb-50 mt-20" style="border-radius: 24px; overflow: hidden;">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="banner-bg"
                        style="background-image:url({{ $tip->file }});">
                    </div>
                </div>
                
               
            </div>
        </div>
        <div class="slider-btn-grp">
            <div class="slider-btn banner-slider-prev">
                <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 10.0571H22V11.9428H0V10.0571Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.942857 11.9429C5.3768 11.9429 9.00115 8.0432 9.00115 3.88457V2.94171H7.11543V3.88457C7.11543 7.04251 4.29566 10.0571 0.942857 10.0571H0V11.9429H0.942857Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.942857 10.0571C5.3768 10.0571 9.00115 13.9568 9.00115 18.1154V19.0583H7.11543V18.1154C7.11543 14.9587 4.29566 11.9428 0.942857 11.9428H0V10.0571H0.942857Z" />
                    </g>
                </svg>
            </div>
            <div class="slider-btn banner-slider-next">
                <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M22 10.0571H-5.72205e-06V11.9428H22V10.0571Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M21.0571 11.9429C16.6232 11.9429 12.9989 8.0432 12.9989 3.88457V2.94171H14.8846V3.88457C14.8846 7.04251 17.7043 10.0571 21.0571 10.0571H22V11.9429H21.0571Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M21.0571 10.0571C16.6232 10.0571 12.9989 13.9568 12.9989 18.1154V19.0583H14.8846V18.1154C14.8846 14.9587 17.7043 11.9428 21.0571 11.9428H22V10.0571H21.0571Z" />
                    </g>
                </svg>
            </div>
        </div>
    </div>
    
        <!-- Destination Details Section Start-->
        <div class="destination-details-section mb-100">
            <div class="container">
                <div class="row justify-content-center mb-60 wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms"> 
                    <div class="col-lg-10">
                        <div class="destination-details-content">
                            <h2>{{ $tip->title }}</h2>
                            {!! $tip->description !!}
                           
                            <!--<a href="#" class="primary-btn1 two transparent" id="scroll-btn">-->
                            <!--    <span>-->
                            <!--        Best Time to Visit-->
                            <!--        <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">-->
                            <!--            <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>-->
                            <!--        </svg>-->
                            <!--    </span>-->
                            <!--    <span>-->
                            <!--        Best Time to Visit-->
                            <!--        <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">-->
                            <!--            <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>-->
                            <!--        </svg>-->
                            <!--    </span>-->
                            <!--</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Destination Details Section End-->
        
        <!--<div class="row">-->
        <!--    <div class="col-lg-12">-->
        <!--        <div class="notification-list">-->
        <!--            <div class="checkout-form-title d-flex justify-content-between align-items-center">-->
        <!--                <h4>Videos Detail</h4>-->
                       
        <!--            </div>-->

        <!--            <div id="empty-state" style="display: none;" class="text-center p-5">-->
        <!--                <i class="bx bx-bell-off" style="font-size: 48px; color: #ccc;"></i>-->
        <!--                <p class="mt-2 text-muted">No Videos Detail found.</p>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
</div>
@endsection

@section('scripts')
<script type="module">
</script>
@endsection