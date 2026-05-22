@extends('front.template.layout')
@section('content')
<style>
    .footer-section{
        margin-bottom: 78px;
    }
</style>
<div class="guider-details-page pt-120 mb-100">

    <div class="fixed-bottom-wrap">
        <div class="container">
            <div class="d-flex justify-content-md-between justify-content-center  align-items-center">
                
                   
                    <div class="price 	d-none d-md-block">
                      
                        <span>Consultation Fee</span>
                          <h5 class="mb-0">AED {{$doctor->user->consultation_fee ?? 'N/A'}}</h5>
                         
                    </div>
                     <div class="price 	d-none d-md-block">
                        <h5 class="mb-0">Ready to Consult With Our Specialist?</h5>
                        <span>Book your appointment with Dr. {{$doctor->user->name ?? 'N/A'}}</span>
                    </div>
                    <button class="primary-btn1 mb-w-100 justify-content-center"
                            onclick="window.location.href='{{ route('book_appointment', $doctor->id) }}'">
                        Book Appointment
                    </button>
                    
                    
                </button>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="row gy-5 justify-content-between">
            <div class="col-lg-5 col-md-8">
                <div class="guider-img-wrap">
                    <img src="{{ $doctor->user->user_img_url ?? null}}" alt="">
                    <div class="guider-social-area">
                        <span>-Availability Status-</span>
                        <h5 class="availability-status"></h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7">
                <div class="guider-details-content">
                    <div class="guider-name-desig mb-4">
                        <h2>Dr. {{$doctor->user->name ?? 'N/A'}}</h2>
                        <span>{{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}</span>
                        <ul class="items-list mt-4 ps-0">
                                <li style="list-style: none;">
                                    <svg class="me-1" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15V16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8C16 12.4183 12.4183 16 8 16V15C11.866 15 15 11.866 15 8Z" fill="#1baeff"/>
                                        <path
                                            d="M11.6947 6.45795L7.24644 10.9086C7.17556 10.9771 7.08572 11.0126 6.99596 11.0126C6.9494 11.0127 6.90328 11.0035 6.86027 10.9857C6.81727 10.9678 6.77822 10.9416 6.7454 10.9086L4.3038 8.46699C4.16436 8.32987 4.16436 8.10539 4.3038 7.96595L5.16652 7.10083C5.29892 6.96851 5.53524 6.96851 5.66764 7.10083L6.99596 8.42915L10.3309 5.09179C10.3638 5.05887 10.4028 5.03274 10.4457 5.01489C10.4887 4.99705 10.5347 4.98784 10.5812 4.98779C10.6757 4.98779 10.7656 5.02563 10.8317 5.09179L11.6944 5.95699C11.8341 6.09643 11.8341 6.32091 11.6947 6.45795Z" fill="#1baeff"/>
                                    </svg>
                                    {{$doctor->year_of_experiance ?? 0}} years experience overall
                                </li>
                                <li style="list-style: none;">
                                    <svg class="me-1" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15V16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8C16 12.4183 12.4183 16 8 16V15C11.866 15 15 11.866 15 8Z"  fill="#1baeff"/>
                                        <path
                                            d="M11.6947 6.45795L7.24644 10.9086C7.17556 10.9771 7.08572 11.0126 6.99596 11.0126C6.9494 11.0127 6.90328 11.0035 6.86027 10.9857C6.81727 10.9678 6.77822 10.9416 6.7454 10.9086L4.3038 8.46699C4.16436 8.32987 4.16436 8.10539 4.3038 7.96595L5.16652 7.10083C5.29892 6.96851 5.53524 6.96851 5.66764 7.10083L6.99596 8.42915L10.3309 5.09179C10.3638 5.05887 10.4028 5.03274 10.4457 5.01489C10.4887 4.99705 10.5347 4.98784 10.5812 4.98779C10.6757 4.98779 10.7656 5.02563 10.8317 5.09179L11.6944 5.95699C11.8341 6.09643 11.8341 6.32091 11.6947 6.45795Z"  fill="#1baeff"/>
                                    </svg>
                                    {{$doctor->hospital->name_en ?? null}}
                                </li>
                                <li style="list-style: none;">
                                    <svg class="me-1" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15V16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8C16 12.4183 12.4183 16 8 16V15C11.866 15 15 11.866 15 8Z"  fill="#1baeff"/>
                                        <path
                                            d="M11.6947 6.45795L7.24644 10.9086C7.17556 10.9771 7.08572 11.0126 6.99596 11.0126C6.9494 11.0127 6.90328 11.0035 6.86027 10.9857C6.81727 10.9678 6.77822 10.9416 6.7454 10.9086L4.3038 8.46699C4.16436 8.32987 4.16436 8.10539 4.3038 7.96595L5.16652 7.10083C5.29892 6.96851 5.53524 6.96851 5.66764 7.10083L6.99596 8.42915L10.3309 5.09179C10.3638 5.05887 10.4028 5.03274 10.4457 5.01489C10.4887 4.99705 10.5347 4.98784 10.5812 4.98779C10.6757 4.98779 10.7656 5.02563 10.8317 5.09179L11.6944 5.95699C11.8341 6.09643 11.8341 6.32091 11.6947 6.45795Z"  fill="#1baeff"/>
                                    </svg>
                                    {{round($doctor->hospital->location[0]->distance ?? 0.0)}} km away
                                </li>
                        </ul>
                    </div>

                    <hr>
                    <div class="guider-experties mb-20">
                        <h4>About Doctor-</h4>
                        <div class="guider-operator-area mt-3">
                            <!-- <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M22.8112 19.92C20.4112 18.6568 18.576 17.956 16.946 17.6792L17.1232 17.4352C17.1577 17.3879 17.1813 17.3336 17.1925 17.2762C17.2036 17.2187 17.2019 17.1595 17.1876 17.1028L16.8164 15.618C16.9526 15.4258 17.0809 15.228 17.2008 15.0252C18.6956 14.518 19.4532 13.66 19.578 12.392C20.2581 12.3508 20.8 11.79 20.8 11.1V8.1C20.8 7.41441 20.2648 6.8572 19.5916 6.8092C19.4972 4.48561 18.4748 0 12 0C5.52478 0 4.50239 4.48561 4.40841 6.8092C3.73481 6.8572 3.20002 7.41441 3.20002 8.1V11.1C3.20002 11.8168 3.78323 12.4 4.5 12.4H5.1C5.3332 12.4 5.5492 12.3332 5.73839 12.2252C5.96681 13.428 6.47039 14.6124 7.18317 15.618L6.81197 17.1028C6.79774 17.1596 6.79613 17.2187 6.80726 17.2762C6.81839 17.3336 6.84198 17.3879 6.87638 17.4352L7.05398 17.6792C5.424 17.956 3.5888 18.6568 1.18837 19.92C0.455203 20.306 2.47399e-08 21.0608 2.47399e-08 21.89V23.6C-1.8454e-05 23.6525 0.0103148 23.7046 0.0304094 23.7531C0.0505039 23.8016 0.0799659 23.8457 0.117112 23.8829C0.154257 23.92 0.198358 23.9495 0.246895 23.9696C0.295432 23.9897 0.347453 24 0.399984 24H10.1504L13.832 23.9964C13.8384 23.9968 13.844 24 13.8504 24H23.6C23.7061 24 23.8078 23.9578 23.8828 23.8828C23.9578 23.8078 24 23.7061 24 23.6V21.89C24 21.0608 23.5444 20.306 22.8112 19.92ZM17.7528 13.9184C17.98 13.3696 18.152 12.7996 18.2612 12.2252C18.4199 12.3156 18.5961 12.371 18.778 12.3876C18.7088 12.96 18.4544 13.4968 17.7528 13.9184ZM5.69358 6.95039C5.54266 6.87224 5.37775 6.82479 5.20837 6.8108C5.30681 4.39959 6.39319 0.800016 12 0.800016C17.6064 0.800016 18.6928 4.39964 18.7912 6.8108C18.6218 6.82479 18.4569 6.87224 18.306 6.95039C17.8028 3.92002 15.1704 1.59998 12 1.59998C8.82919 1.59998 6.19678 3.92002 5.69358 6.95039ZM7.63322 17.1156L7.8128 16.398C7.81439 16.4 7.81598 16.4012 7.81758 16.4028C8.784 17.4532 10.0128 18.1932 11.3984 18.3616C11.3992 18.3616 11.4 18.362 11.4012 18.362L11.22 18.7692L10.3155 20.804L7.63322 17.1156ZM12 18.9852L12.9844 21.2H11.0156L12 18.9852ZM10.488 23.1996L10.7276 22H13.272L13.512 23.1968L10.488 23.1996ZM13.684 20.804L12.7796 18.7692L12.5988 18.362C12.5996 18.362 12.6004 18.3616 12.6012 18.3616C13.9868 18.1932 15.216 17.4532 16.182 16.4028C16.1835 16.4012 16.1856 16.4 16.1867 16.398L16.3667 17.1156L13.684 20.804ZM12.4 14.8C12.3475 14.8 12.2954 14.8103 12.2469 14.8304C12.1984 14.8505 12.1543 14.88 12.1171 14.9171C12.08 14.9542 12.0505 14.9983 12.0304 15.0469C12.0103 15.0954 12 15.1474 12 15.2C12 15.2525 12.0103 15.3045 12.0304 15.3531C12.0505 15.4016 12.08 15.4457 12.1171 15.4828C12.1543 15.52 12.1984 15.5494 12.2469 15.5695C12.2954 15.5896 12.3475 15.6 12.4 15.6C13.8616 15.6 15.0732 15.5063 16.0672 15.3167C16.052 15.3367 16.0356 15.3555 16.02 15.3755C16.006 15.3887 15.9876 15.3975 15.9756 15.4131C15.8413 15.5853 15.6991 15.7512 15.5496 15.9103L15.546 15.9139C15.4496 16.0163 15.3484 16.1075 15.2484 16.2011C14.2704 17.0831 13.1088 17.5999 12 17.5999C10.8908 17.5999 9.72919 17.0831 8.75161 16.2015C8.6512 16.1075 8.55 16.0163 8.45362 15.9135C8.45241 15.9127 8.45161 15.9115 8.45044 15.9107C8.301 15.7512 8.15872 15.5852 8.02402 15.4131C8.01202 15.3975 7.99402 15.3887 7.97962 15.3755C7.0372 14.1863 6.40003 12.6011 6.40003 10.7999V7.99992C6.39998 4.91198 8.91202 2.4 12 2.4C15.0876 2.4 17.6 4.91198 17.6 8.00002V10.8C17.6 12.1284 17.25 13.3372 16.6916 14.3596C15.7364 14.6352 14.3648 14.8 12.4 14.8Z"/>
                                </g>
                            </svg> -->
                            <span>Languages known: <strong>{{ count($doctor->languages) ? $doctor->languages->pluck('title')->implode(', ') : 'N/A' }}</strong></span>
                        </div>
                        <div class="guider-operator-area mt-2">
                            <span>Qualifications : <strong>	{{ count($doctor->qualifications) ? $doctor->qualifications->pluck('title')->implode(', ') : 'N/A' }}</strong></span>
                        </div>
                        <div class="guider-operator-area mt-2">
                            <span>Specialties : <strong>{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : 'N/A' }}</strong></span>
                        </div>
                        <div class="guider-operator-area mt-2">
                            <span>Special Interest : <strong>	{{ count($doctor->interests) ? $doctor->interests->pluck('title')->implode(', ') : 'N/A' }}</strong></span>
                        </div>
                    </div>
                    <hr>
                    <div class="guider-info">
                        <!-- <h5>“ To Achieve Customer Satisfaction with Serve Quality Guide for Any Destionation with Friendly ”</h5> -->
                        {!! $doctor->profile_desciription !!}
                        <!-- <p>My goal is to provide personalized, insightful, and safe travel experiences. Whether it’s navigating bustling city streets, trekking through breathtaking landscapes, or discovering local traditions, I strive to make every trip seamless and memorable. Join me on an adventure where every moment turns into a lasting memory!</p> -->
                    </div>
                    <hr>
                    <div class="package-details-page">
                            <div class="package-details-warpper">
                                <div class="container mt-4">

    <h4 class="mb-4">Patient Reviews</h4>

    @if($doctor->feedbacks->count() > 0)

        @foreach($doctor->feedbacks as $feedback)

            <div class="card mb-3 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        
                        <!-- 👤 User Name -->
                        <h6 class="mb-0">
                            {{ $feedback->user->name ?? 'Anonymous' }}
                        </h6>

                        <!-- ⭐ Rating -->
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $feedback->rating)
                                    <i class="fa fa-star text-warning"></i>
                                @else
                                    <i class="fa fa-star text-secondary"></i>
                                @endif
                            @endfor
                        </div>

                    </div>

                    <!-- 📝 Feedback -->
                    <p class="mb-0 text-muted">
                        {{ $feedback->feeback_message }}
                    </p>

                </div>
            </div>

        @endforeach

    @else

        <div class="alert alert-info">
            No feedback available yet.
        </div>

    @endif

</div>
                                </div></div>
                    <hr>
                    <div class="contact-info">
                        <h4>Clinic Details</h4>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="single-contact mb-2">
                                    <div class="icon" style="background-image: url({{ $doctor->hospital->user->user_img_url ?? 'https://images.scalebranding.com/cool-a-logo-50afaa14-6473-4b28-b9c5-e08d50d8e7da.jpg'}}); background-size: cover;">
                                        <!-- <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M11.0027 0H10.9973C4.93213 0 0 4.9335 0 11C0 13.4062 0.7755 15.6365 2.09413 17.4474L0.72325 21.5339L4.95138 20.1823C6.69075 21.3345 8.76562 22 11.0027 22C17.0679 22 22 17.0651 22 11C22 4.93488 17.0679 0 11.0027 0Z"/>
                                                <path
                                                    d="M17.4037 15.5334C17.1384 16.2828 16.0851 16.9043 15.245 17.0858C14.6702 17.2081 13.9195 17.3058 11.3922 16.258C8.15962 14.9188 6.07788 11.6339 5.91563 11.4208C5.76025 11.2076 4.60938 9.68138 4.60938 8.10288C4.60938 6.52438 5.411 5.75575 5.73413 5.42575C5.9995 5.15488 6.43812 5.03113 6.85887 5.03113C6.995 5.03113 7.11738 5.038 7.22738 5.0435C7.5505 5.05725 7.71275 5.0765 7.92587 5.58663C8.19125 6.226 8.8375 7.8045 8.9145 7.96675C8.99287 8.129 9.07125 8.349 8.96125 8.56213C8.85813 8.78213 8.76737 8.87975 8.60512 9.06675C8.44287 9.25375 8.28887 9.39675 8.12662 9.5975C7.97812 9.77213 7.81037 9.95913 7.99738 10.2823C8.18438 10.5985 8.83063 11.6531 9.78212 12.5001C11.01 13.5933 12.0055 13.9425 12.3616 14.091C12.627 14.201 12.9432 14.1749 13.1371 13.9686C13.3832 13.7033 13.6871 13.2633 13.9965 12.8301C14.2165 12.5194 14.4943 12.4809 14.7858 12.5909C15.0828 12.694 16.6544 13.4709 16.9775 13.6318C17.3006 13.794 17.5137 13.871 17.5921 14.0071C17.6691 14.1433 17.6691 14.7826 17.4037 15.5334Z"/>
                                            </g>
                                        </svg> -->
                                    </div>
                                    <div class="content">
                                        <p class="mb-1"><strong>{{$doctor->hospital->name_en ?? 'N/A' }}</strong></p>
                                        <span>{{$doctor->hospital->address ?? 'N/A'}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        
                                
                                <div class="package-details-page">
                            <div class="package-details-warpper"></div>
                                <div class="map-area mb-60">
                                    <iframe id="map-iframe" src="" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    <a 
   href="https://www.google.com/maps/dir/?api=1&destination={{$doctor->hospital->location[0]->latitude ?? '25.204819'}} ,{{$doctor->hospital->location[0]->longitude ?? '25.204819'}}"
   target="_blank"
   class="primary-btn1 w-100"
>
    <span>Get Direction</span>
    <span>Get Direction</span>
    <div></div>
</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

$(document).ready(function(){
    const hospitalLatitude = "{{$doctor->hospital->location[0]->latitude ?? '25.204819'}}"; 
            const hospitalLongitude = "{{$doctor->hospital->location[0]->longitude ?? '25.204819'}}";
            const hospitalLocationName = "{{$doctor->hospital->name_en ?? ''}}";

           
            let mapK="{{env('GOOGLE_MAP_KEY')}}";
                // Update the map iframe with the current location
                const mapUrl = `https://www.google.com/maps/embed/v1/place?key=${mapK}&q=${hospitalLatitude},${hospitalLongitude}+(${hospitalLocationName.replace('&','')})&zoom=12`;
                $('#map-iframe').attr('src', mapUrl);

                let bookingDate = new Date();
            let day = String(bookingDate.getDate()).padStart(2, '0');
            let month = String(bookingDate.getMonth() + 1).padStart(2, '0');
            let year = bookingDate.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            checkAvailibility('{{$doctor->user_id}}', formattedDate);

                function checkAvailibility(doctor_id, date){
            $('.availability-status').removeClass('available').text('Checking Availability');
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ route('front.appointments.check_doctor_availability') }}",
                    data:{
                        'booking_date': date,
                            'doctor_user_id': doctor_id,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function (res) {
                        if (res.oData) {
                            if(res.oData.is_today_available=='yes'){
                                $('.availability-status').addClass('available').text('Available');
                            }else{
                                $('.availability-status').removeClass('available').text('Not Available');
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }
        }
});
</script>

@endsection