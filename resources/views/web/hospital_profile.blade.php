@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->

            <div class="page-content">
                <div class="container-fluid" style="max-width: 100%;">

                    <div class="row">

                        <div class="col-lg-5 px-md-2 px-0">

                            @if($data->images && !empty($data->images))
                            <div class="card">
                                <div class="card-body3">
                                    <div class="swiper banner-slider rounded overflow-hidden">
                                        <div class="swiper-wrapper">
                                            @foreach($data->images as $img)
                                            <div class="swiper-slide rounded overflow-hidden ecommerce-slied-bg">
                                                <img src="{{$img->image_url}}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="product-box rounded p-md-3 p-2 doctor-card">
                                <div class="row align-items-center">
                                    <div class="col-md-4 col-4 pe-0">
                                        <!-- <div class="pricing-badge">
                                                        <span class="badge">Available</span>
                                                    </div> -->
                                        <div class="product-img bg-light">
                                            <img src="{{$data->user->user_img_url}}" alt="" class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-8">
                                        <div class="product-content pt-0">
                                            <h5 class="mt-1 mb-2 fw-bold">
                                                <a href="#" class="text-body font-size-16">{{$data->name_en??''}} </a>
                                            </h5>
                                            <p class="text-muted font-size-13 mb-0">{{$data->address??''}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-md-4 mt-2">
                                <div class="card-body location-card">
                                    <div class="d-flex mb-3 align-items-center">
                                        <img class="me-2" src="{{asset('/web/images/doc-location.svg')}}" height="18" alt="">
                                        <h5 class="font-size-15 mb-0">{{$data->address??''}}tes</h5>
                                        <a href="https://www.google.com/maps/dir/?api=1&amp;origin=9.9312328,76.2673041&amp;destination={{$data->latitude}},{{$data->latitude}}&amp;travelmode=driving" class="btn btn-sm btn-secondary font-size-13 ms-3" id="get-direction" target="_blank">Get Direction</a>
                                    </div>
                                        <iframe id="map-iframe" src="" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>


                        </div>
                        <div class="col-lg-7 px-md-2 px-0">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="font-size-16 mb-3 fw-bold">Info</h5>
                                    <div class="row">
                                        <div class="table-responsive mt-3 pb-0">
                                            <table class="table align-middle table-sm table-borderless table-centered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-muted">Country :</td>
                                                        <th class="fw-bold">{{$data->country->name??'NA'}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">City :</td>
                                                        <th class="fw-bold">{{$data->emirate->name_en??'NA'}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Area :</td>
                                                        <th class="fw-bold">{{$data->area->name_en??'NA'}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Hospital Main Number :</td>
                                                        <th class="fw-bold"><a href="tel:{{$data->user->dial_code}}{{$data->user->phone}}">+{{$data->user->dial_code}}{{$data->user->phone}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Email address :</td>
                                                        <th class="fw-bold"><a href="mailto:{{$data->user->email}}">{{$data->user->email}}</a></th>
                                                    </tr>
                                                    @if($data->website !='')
                                                    <tr>
                                                        <td class="text-muted">Website :</td>
                                                        <th class="fw-bold"><a target="blank" href="{{$data->website}}">{{$data->website}}</a></th>
                                                    </tr>
                                                    @endif
                                                    <!-- end tr -->
                                                    <!--<tr>-->
                                                    <!--    <td class="text-muted">Specialties :</td>-->
                                                    <!--    <th class="fw-bold">-->
                                                    <!--        <span class="badge-custom">Dermatology</span> -->
                                                    <!--        <span class="badge-custom">Neurology</span>  -->
                                                    <!--        <span class="badge-custom">Orthology</span>-->
                                                    <!--        <span class="badge-custom">Dermatology</span> -->
                                                    <!--        <span class="badge-custom">Neurology</span>  -->
                                                    <!--        <span class="badge-custom">Orthology</span>-->
                                                    <!--        <span class="badge-custom">Dermatology</span> -->
                                                    <!--        <span class="badge-custom">Neurology</span>  -->
                                                    <!--        <span class="badge-custom">Orthology</span>-->
                                                    <!--    </th>-->
                                                    <!--</tr>-->
                                                    <!-- end tr -->
                                                </tbody>
                                                <!-- end tbody -->
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </div>
                            </div>
                            

                            <div class="card mt-md-4 mt-2">
                                <div class="card-body">
                                    <h5 class="font-size-16 mb-3 fw-bold">@if($data->type==20) Clinic @else Hopital @endif Profile</h5>
                                    <div class="mt-3">
                                        <p class="font-size-15">
                                            {!!$data->profile_description!!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- end row -->
                     
                    <style>
                        .badge-custom{
                            color: #000;
                            padding: 8px 15px;
                            border-radius: 5px;
                            border: 1px solid #969699;
                            display: inline-block;
                            font-size: 13px;
                            font-weight: 400;
                            margin-right: 8px;
                            margin-bottom: 10px;
                        }
                        .doctor-card .product-img img{
                            object-fit: contain;
                        }
                        .swiper-pagination-bullet {
                            /*background-color: var(--primary-color);*/
                            opacity: 1;
                            border: 1px solid var(--primary-color);
                        }
                        .swiper-pagination-bullet-active{
                            background-color: var(--primary-color);
                        }
                        @media (max-width: 767.98px){
                            body[data-layout=horizontal] .page-content {
                                padding: 190px calc(24px / 2) 60px calc(24px / 2);
                                margin-top: 24px;
                            }
                        }
                    </style>

                </div>
                <!-- container-fluid -->
            </div><!-- </div> -->

            <!-- end main content-->
@endsection

@section('custom_js')
    <script>
        var swipert = new Swiper(".banner-slider", {
            spaceBetween: 15,
            loop: true,
            speed: 1500,
            parallax: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: true,
            },
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
        $(document).ready(function(){
            
            
            const currentLatitude = localStorage.getItem('current_latitude');
            const currentLongitude = localStorage.getItem('current_longitude');
            const hospitalLatitude = "{{$datalatitude ?? '25.204819'}}"; 
            const hospitalLongitude = "{{$data->longitude ?? '25.204819'}}";
            const hospitalLocationName = "{{$data->name_en ?? ''}}";

            if (currentLatitude && currentLongitude) {
                let mapK="{{env('GOOGLE_MAP_KEY')}}";
                // Update the map iframe with the current location
                const mapUrl = `https://www.google.com/maps/embed/v1/place?key=${mapK}&q=${hospitalLatitude},${hospitalLongitude}+(${hospitalLocationName.replace('&','')})&zoom=12`;
                $('#map-iframe').attr('src', mapUrl);

                // Update the "Get Direction" link with the current location
                const directionUrl = `https://www.google.com/maps/dir/?api=1&origin=${currentLatitude},${currentLongitude}&destination=${hospitalLatitude},${hospitalLongitude}&travelmode=driving`;
                $('#get-direction').attr('href', directionUrl).attr('target', '_blank');
                //$('.user-current-location').text(`Lat: ${currentLatitude}, Long: ${currentLongitude}`);
            } else {
                // If location is not in local storage, try to get it using Geolocation API
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        // Store location in local storage
                        localStorage.setItem('current_latitude', latitude);
                        localStorage.setItem('current_longitude', longitude);

                        // Update the map iframe with the current location
                        const mapUrl = `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14442.159542891353!2d${longitude}!3d${latitude}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f69e181efecf5%3A0x6b0965111b8ce496!2sPullman%20Dubai%20Downtown!5e0!3m2!1sen!2sin!4v1714053540710!5m2!1sen!2sin`;
                        $('#map-iframe').attr('src', mapUrl);

                        // Update the "Get Direction" link with the current location
                        const directionUrl = `https://www.google.com/maps/dir/?api=1&origin=${latitude},${longitude}&destination=${hospitalLatitude},${hospitalLongitude}&travelmode=driving`;
                        $('#get-direction').attr('href', directionUrl).attr('target', '_blank');

                        // Optionally, update the displayed location text
                        //$('.user-current-location').text(`Lat: ${latitude}, Long: ${longitude}`);
                    }, (error) => {
                        console.error("Error fetching location: ", error);
                        alert("Could not get your location. Please enable location services and try again.");
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
        });
    </script>
@endsection