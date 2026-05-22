@extends('front.template.layout')

@section('title', 'Videos')

@section('styles')

@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        
        <!-- Destination Details Gallery Section Start-->
     <div class="destination-details-gallery-section video-wrapper-video-sec mb-50 mt-30">
                                    <iframe width="100%" height="100%" src="{{ $video->file }}" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                </div>
    <!-- Destination Details Gallery Section End-->

    <!-- Destination Details Section Start-->
    <div class="destination-details-section mb-100">
        <div class="container">
            <div class="row justify-content-center mb-60 wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms"> 
                <div class="col-lg-10">
                    <div class="destination-details-content">
                        <h2> {{ $video->title }}</h2>
                       {!! $video->description !!}
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