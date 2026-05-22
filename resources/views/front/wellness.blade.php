@extends('front.template.layout')

@section('title', 'Wellness')

@section('styles')

@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        
        
                        <div class="row justify-content-center mb-50">
                            <div class="col-xl-8 col-lg-10">
                                <div class="section-title text-center">
                                    <h2>Wellness Tips</h2>
                                    <p>We are committed to delivering more than just healthcare services—we strive to provide a comprehensive and patient-centered experience.</p>
                                </div>
                            </div>
                        </div> 
                        
                        
                    <div class="row gy-md-5 gy-4">
                         @if($tips->first())
                                        @foreach($tips as $tip)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="destination-card">
                                @if($tip->file)
                                <a href="{{ url('/wellness-detail') }}?id={{  $tip->id }}" class="destination-img">
                                    <img src="{{ $tip->file }}" alt="">
                                </a>
                                @endif
                                <div class="destination-content">
                                    <a href="{{ url('/wellness-detail') }}?id={{  $tip->id }}" class="title-area">
                                        {{ $tip->title }}
                                    </a>
                                    <div class="content">
                                       {!! $tip->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif  
                        
                        
                    </div>
        
        
        <!--<div class="row">-->
        <!--    <div class="col-lg-12">-->
        <!--        <div class="notification-list">-->
        <!--            <div class="checkout-form-title d-flex justify-content-between align-items-center">-->
        <!--                <h4>Wellness</h4>-->
                       
        <!--            </div>-->


        <!--            <div id="empty-state" style="display: none;" class="text-center p-5">-->
        <!--                <i class="bx bx-bell-off" style="font-size: 48px; color: #ccc;"></i>-->
        <!--                <p class="mt-2 text-muted">No Wellness found.</p>-->
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