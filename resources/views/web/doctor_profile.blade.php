@extends('web.template.layout')

@section('title', 'Home')

@section('content')
<style>
    .action-bottom-area .btn-primary:focus-visible,
    .action-bottom-area .btn-primary:focus-within,
    .action-bottom-area .btn-primary:active,
    .action-bottom-area .btn-primary:hover,
    .action-bottom-area .btn-primary:focus{
        color: #FFF !important;
    }
    .dr-phone-number-tshow strong{
    color: #FFF !important;
    }
    .btn-primary.dr-phone-number-tshow {
        background: #000 !important;
    }

</style>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->

            <div class="action-bottom-area py-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <div class="d-md-block d-none">
                                <span class="font-size-10">Availability Status</span>
                                <span class="availability-status">
                                    <p class="status fw-bold mb-0">Checking Availability</p>
                                </span>
                            </div>
                            @if($doctor->hospital->is_contract_signed==0)
                                <a href="tel:+{{$doctor->user->dial_code}}{{$doctor->user->phone}}" class="btn btn-primary w-md-280 w-100" data-role="show-direct-number" data-direct-number="+{{$doctor->user->dial_code}}{{$doctor->user->phone}}">Call for Appointment</a>
                            @else
                                @php $insAp = $_GET['instant']??0; @endphp
                                @if($insAp)
                                <a href="tel:+{{$doctor->appointment_phone ? ('+'.$doctor->appointment_dial_code.' '.$doctor->appointment_phone) : 'N/A'}}" class="btn btn-primary w-md-280 w-100 dr-show-phone-number">Call for Instant Appointment</a>
                                <span class="btn btn-primary w-md-280 w-100 dr-phone-number-tshow" style="display: none;">
                                {{$doctor->appointment_phone ? ('+'.$doctor->appointment_dial_code.' '.$doctor->appointment_phone) : 'N/A'}}
                                </span>
                                @else
                                <a href="{{url('web/book-dr-appointment', $doctor->id)}}" class="btn btn-primary w-md-280 w-100">Book an Appointment</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content doc-detail-wrapper">
                <div class="container-fluid" style="max-width: 100%;">

                    <div class="row">

                        <div class="col-lg-5 px-md-2 px-0">
                            <div class="product-box rounded p-md-3 p-2 doctor-card in-detail-page">
                                <div class="row align-items-center">
                                    <div class="col-md-5 col-4 pe-0">
                                        <!-- <div class="pricing-badge">
                                                        <span class="badge">Available</span>
                                                    </div> -->
                                        <div class="product-img bg-light">
                                            <img src="{{ $doctor->user->user_img_url ?? null}}" alt=""
                                                class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-8">
                                        <div class="product-content pt-0">
                                            <h5 class="mt-1 mb-2"><a href="#" class="text-body fw-bold font-size-18">DR {{$doctor->user->name ?? 'N/A'}}</a></h5>
                                            <p class="text-body font-size-16 mb-1">{{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}</p>
                                            <p class="text-muted font-size-13 mb-0">{{$doctor->year_of_experiance ?? 0}} years experience overall</p>
                                            <h5 class="font-size-15 mt-1 mb-2">{{$doctor->hospital->name_en ?? null}}</h5>
                                            <p class="text-muted font-size-14 mb-0">{{round($doctor->hospital->location[0]->distance ?? 0.0)}} km away</p>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 d-flex justify-content-between mt-2 align-items-center">
                                        <div class="">
                                            <span class="font-size-10">Availability Status</span>
                                            <p class="status fw-bold mb-0 available">Available</p>
                                        </div>
                                        <a href="bookin-appointment.php" class="btn btn-primary">Book an Appointment</a>
                                    </div> -->
                                </div>
                            </div>

                            <div class="bg-light-green rounded p-md-3 p-2 mt-md-4 mt-2 doctor-card">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-center align-items-center flex-column">
                                        <span class="font-size-12">Availability Status</span>
                                        <span class="availability-status">
                                            <!-- <p class="status fw-bold mb-0 available"></p> -->
                                            <p class="status font-size-18 fw-bold mb-0">Checking Availability</p>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-box rounded p-md-3 p-2 mt-md-4 mt-2 doctor-card">
                                <a href="{{route('get_hospital_profile',['id'=>$doctor->hospital->id])}}" style="position: absolute; top:0; left:0; width: 100%; height: 100%;z-index: 9"></a>
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-4 pe-0">
                                        <!-- <div class="pricing-badge">
                                                        <span class="badge">Available</span>
                                                    </div> -->
                                        <div class="product-img bg-light">
                                            <img src="{{ $doctor->hospital->user->user_img_url ?? null}}" alt=""
                                                class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-9 col-8">
                                        
                                        <div class="product-content pt-0">
                                            <h5 class="mt-1 mb-2 fw-bold">
                                                <a href="{{route('get_hospital_profile',['id'=>$doctor->hospital->id])}}" class="text-body font-size-16"> {{$doctor->hospital->name_en ?? 'N/A' }} </a>
                                            </h5>
                                            <p class="text-muted font-size-13 mb-0">{{$doctor->hospital->address ?? 'N/A'}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card mt-md-4 mt-2">
                                <div class="card-body location-card">
                                    <div class="d-flex mb-3 align-items-center">
                                        <img class="me-2" src="assets/images/doc-location.svg" height="18" alt="">
                                        <h5 class="font-size-15 mb-0">{{$doctor->hospital->location[0]->location ?? 'N/A'}}</h5>
                                        <a href="#" class="btn btn-sm btn-secondary font-size-13 ms-3" id="get-direction">Get Direction</a>
                                    </div>
                                        <iframe id="map-iframe" src="" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>


                        </div>
                        <div class="col-lg-7 px-md-2 px-0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive mt-3 pb-0">
                                            <table class="table align-middle table-sm table-borderless table-centered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-muted">Qualifications :</td>
                                                        <th class="fw-bold">{{ count($doctor->qualifications) ? $doctor->qualifications->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                    </tr>
                                                    <!-- end tr -->
                                                    <tr>
                                                        <td class="text-muted">Specialties :</td>
                                                        <th class="fw-bold">{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : 'N/A' }}</th>
                                                    </tr>
                                                    <!-- end tr -->
                                                    <tr>
                                                        <td class="text-muted">Special Interest :</td>
                                                        <th class="fw-bold">{{ count($doctor->interests) ? $doctor->interests->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                    </tr>
                                                    <!-- end tr -->
                                                    <tr>
                                                        <td class="text-muted">Experience :</td>
                                                        <th class="fw-bold">{{$doctor->year_of_experiance}}+</th>
                                                    </tr>
                                                    <!-- end tr -->
                    
                                                    <tr>
                                                        <td class="text-muted">Languages Spoken :</td>
                                                        <th class="fw-bold">{{ count($doctor->languages) ? $doctor->languages->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                    </tr>
                                                    <!-- end tr -->
                    
                                                    <tr>
                                                        <td class="text-muted">Country of Origin :</td>
                                                        <th class="fw-bold">{{ $doctor->country->name ?? 'N/A' }}</th>
                                                    </tr>
                                                    @if($doctor->appointment_phone)
                                                    <tr>
                                                        <td class="text-muted">Doctor Direct Number to Book Appointment</td>
                                                        <th class="fw-bold">+{{$doctor->appointment_dial_code ?? null}} {{$doctor->appointment_phone}}</th>
                                                    </tr>
                                                    @endif
                                                    @if($doctor->user->phone)

                                                    <tr>
                                                        <td class="text-muted">Clinic Direct Number to Book Appointment</td>
                                                        <th class="fw-bold">{{$doctor->user->phone ? '+ '.$doctor->user->dial_code : null}} {{$doctor->user->phone ?? null}}</th>
                                                    </tr>
                                                    @endif
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
                                    <h5 class="font-size-16 mb-3 fw-bold">Profile</h5>
                                    <div class="mt-3">
                                        <p class="font-size-15">
                                        {!! $doctor->profile_desciription !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div>
                <!-- container-fluid -->
            </div><!-- </div> -->

            <!-- end main content-->
@endsection

@section('custom_js')
    <script>
        $('body').off("click",'[data-role="show-direct-number"]');
        $('body').on("click",'[data-role="show-direct-number"]',function(e){
           // e.preventDefault();
            $(this).text($(this).attr("data-direct-number"));
        });
        function checkAvailibility(doctor_id, date){
            $('.availability-status p').removeClass('available').text('Checking Availability');
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ route('web.appointments.check_doctor_availability') }}",
                    data:{
                        'booking_date': date,
                            'doctor_user_id': doctor_id,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function (res) {
                        if (res.oData) {
                            if(res.oData.list){
                                $('.availability-status p').addClass('available').text('Available');
                            }else{
                                $('.availability-status p').removeClass('available').text('Not Available');
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }
        }
        
        $(document).ready(function(){
            let bookingDate = new Date();
            let day = String(bookingDate.getDate()).padStart(2, '0');
            let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
            let year = bookingDate.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            checkAvailibility('{{$doctor->user_id}}', formattedDate);

            $('.dr-show-phone-number').on('click', function(event) {
                //event.preventDefault();
                $(this).hide(); // Hide the "Call to book Appointment" button
                $('.dr-phone-number-tshow').show(); // Show the phone number span
            });
            
            const currentLatitude = localStorage.getItem('current_latitude');
            const currentLongitude = localStorage.getItem('current_longitude');
            const hospitalLatitude = "{{$doctor->hospital->location[0]->latitude ?? '25.204819'}}"; 
            const hospitalLongitude = "{{$doctor->hospital->location[0]->longitude ?? '25.204819'}}";
            const hospitalLocationName = "{{$doctor->hospital->name_en ?? ''}}";

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