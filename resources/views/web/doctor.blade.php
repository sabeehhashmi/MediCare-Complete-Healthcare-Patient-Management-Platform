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
                            <div class="col-xl-3 col-lg-4 px-md-2 px-0 mb-4">
                                <div class="card filter-sidebar">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h5 class="mb-0">Filters</h5>
                                    </div>
                                    <!-- <div class="p-4">
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="text" class="form-control bg-light border-light rounded" placeholder="Search...">
                                                <i class="bx bx-search search-icon"></i>
                                            </div>
                                        </div>
                                    </div> -->
                                    <form method="GET" action="{{route('doctor_list')}}" id="search-form">
                                    <div>
                                        <input type="hidden" name="current_latitude" id="latitude" value="{{request('current_latitude')}}">
                                        <input type="hidden" name="current_longitude" id="longitude" value="{{request('current_longitude')}}">
                                        <div class="custom-accordion p-4">
                                            <h5 class="font-size-14 mb-0">
                                                <a href="#categories-collapse" class="text-body d-block" data-bs-toggle="collapse">
                                                    Advance Filter <i class="mdi mdi-chevron-up float-end accor-down-icon"></i>
                                                </a>
                                            </h5>

                                            <div class="collapse show mt-4" id="categories-collapse">
                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="specialty_id" class="form-select select2-single w-100" data-placeholder="Doctors Specialty" id="specialty">
                                                        <option value="">Doctors Specialty</option>
                                                        @foreach($specialties as $id => $value)
                                                        <option value="{{$id}}" {{ request('specialty_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon specialty-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="insurance_id" class="form-select select2-single" data-placeholder="My Insurance Policy" id="insurance-policy">
                                                        <option value="">My Insurance Policy</option>
                                                        @foreach($insurencePolicies as $id => $value)
                                                        <option value="{{$id}}" {{ request('insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon insu-policy-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="sub_insurance_id" class="form-select select2-single" data-placeholder="My Insurance Network" id="sub-insurance-policy">
                                                        <option value="">My Insurance Network</option>
                                                        @foreach($subInsurencePolicies as $id => $value)
                                                        <option value="{{$id}}" {{ request('sub_insurance_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon sub-policy-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="medical_condition_id" class="form-select select2-single" data-placeholder="My Medical Condition" id="interest">
                                                        <option value="">My Medical Condition</option>
                                                        @foreach($medicalConditions as $id => $value)
                                                        <option value="{{$id}}" {{ request('medical_condition_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon medical-condition-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="language_id" class="form-select select2-single" data-placeholder="Doctor’s Language" id="language">
                                                        <option value="">Doctor’s Language</option>
                                                        @foreach($languages as $id => $value)
                                                        <option value="{{$id}}" {{ request('language_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-language-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative input-custom-icon mb-3">
                                                    <input type="text" name="date" class="form-control flatpicker-input" id="" placeholder="Search by Date" value="{{ request('date') }}" />
                                                    <span class="custom-icon calendar-doc-icn"></span>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="cuntry_of_origin_id" class="form-select select2-single" data-placeholder="Doctor’s Country of Origin" id="countryOrigin">
                                                        <option value=""> Doctor’s Country of Origin</option>
                                                        @foreach($countries as $id => $value)
                                                        <option value="{{$id}}" {{ request('cuntry_of_origin_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon counrty-orgin-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="gender_id" class="form-select select2-single"  data-placeholder="Doctor’s Gender" id="gender">
                                                        <option value="">Doctor’s Gender</option>
                                                        @foreach($genders as $id => $value)
                                                        <option value="{{$id}}" {{ request('gender_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-gender-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="emirates_id" class="form-select select2-single" data-placeholder="Emirates" id="emirates">
                                                        <option value="">Cities</option>
                                                        @foreach($emirates as $id => $value)
                                                        <option value="{{$id}}" {{ request('emirates_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-location-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="area_id" class="form-select select2-single" data-placeholder="Area" id="area">
                                                        <option value="">Area</option>
                                                        @foreach($areas as $id => $value)
                                                        <option value="{{$id}}" {{ request('area_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-area-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3 d-none">
                                                    <select class="form-select select2-single" data-placeholder="Direct Call for Appointment">
                                                        <option></option>
                                                        <option value="1" {{ request('direct_call_for_appointment') == '1' ? 'selected' : '' }}>Yes</option>
                                                        <option value="2" {{ request('direct_call_for_appointment') == '2' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                    <i class="custom-icon direct-call-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3 d-none">
                                                    <select class="form-select select2-single" data-placeholder="Ready to consult instantly">
                                                        <option></option>
                                                        <option value="1" {{ request('ready_to_consult_instantly') == '1' ? 'selected' : '' }}>Yes</option>
                                                        <option value="2" {{ request('ready_to_consult_instantly') == '2' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                    <i class="custom-icon consult-instant-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="hospital_id" class="form-select select2-single" data-placeholder="Hospital/ Clinic / Dental Care" id="hospital">
                                                        <option value="">Hospital/ Clinic / Dental Care</option>
                                                        @foreach($hospitals as $id => $value)
                                                        <option value="{{$id}}" {{ request('hospital_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon hospital-icon-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <input type="text" name="doctor_name" class="form-select" placeholder="Doctor’s Name" value="{{ request('doctor_name') }}" id="doctor">
                                                    <i class="custom-icon doctor-name-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="form-check py-3">
                                                    <input type="checkbox" name="dirent_call_for_appointment" class="form-check-input" id="auth-remember-chec1" {{ request('dirent_call_for_appointment') ? 'checked' : '' }} value="1">
                                                    <label class="form-check-label ms-2" for="auth-remember-chec1"> Direct Calling Number for Appointment</label>
                                                </div>
                                                <div class="form-check py-3">
                                                    <input type="checkbox" name="ready_to_consult_instantly" class="form-check-input" id="auth-remember-check2" {{ request('ready_to_consult_instantly') ? 'checked' : '' }} value="1">
                                                    <label class="form-check-label ms-2" for="auth-remember-check2"> Ready for Consult Instantly</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 border-top">
                                            <div>
                                                <h5 class="font-size-14 mb-3">Search by Distance</h5>
                                                <input type="range" name="distance" class="form-range" min="0" max="300" id="customRange3" value="{{ request('distance', 0) }}">
                                                <!-- <div class="d-flex justify-content-between">
                                                    <div class="text-muted">1 km</div>
                                                    <div class="text-muted">100 km</div>
                                                </div> -->
                                            </div>
                                        </div>
                                        

                                        <div class="p-4">
                                            <button class="btn btn-primary w-100">Search</button>
                                        </div>
                                    </div>
                                    </form>

                                </div>    
                            </div>
 
                            <div class="col-xl-9 col-lg-8 px-md-2 px-0 mb-5">

                                <div class="card md-no-card">
                                    <div class="card-body px-md-3 px-0">
                                        <div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <div>
                                                        <h5>Showing result for "Doctors in Dubai"</h5>
                                                        <ol class="breadcrumb p-0 bg-transparent">
                                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dubai</a></li>
                                                            <li class="breadcrumb-item active">Doctor</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                              <!-- Tab panes -->
                                                <div class="tab-content p-0 text-muted">
                                                    <div class="tab-pane active" id="popularity" role="tabpanel">
                                                        <div class="row">
                                                            @if($doctors->count() == 0)
                                                                <div class="col-12 text-center">
                                                                    <img src="{{ URL::asset('web/') }}/images/no-result-found.svg" style="max-width: 210px; margin-top: 50px;" class="img-fluid mb-3 mx-auto" alt="">
                                                                    <h4>No Result Found</h4>
                                                                </div>
                                                            @endif
                                                            @foreach($doctors as $key => $doctor)
                                                            <div class="col-xl-6 col-lg-6 col-md-6 mb-4">
                                                                <div class="product-box rounded p-2  doctor-card h-100 position-relative">
                                                                    <div class="row align-items-center">
                                                                        <!-- <a href="{{url('web/book-dr-appointment', $doctor->id)}}"> -->
                                                                            <div class="col-4 pe-0">
                                                                                <div class="product-img bg-light">
                                                                                    <img src="{{ $doctor->user->user_img_url ?? null}}" alt="" class="img-fluid mx-auto d-block">
                                                                                    <a href="{{ url('website/doctor-profile', $doctor->id) }}" class="link-profile font-size-11 text-center">View Full Profile</a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                <div class="product-content pt-0">
                                                                                    <h5 class="mt-1 mb-2"><a href="{{ url('website/doctor-profile', $doctor->id) }}" class="text-body font-size-20">Dr. {{$doctor->user->name}}</a></h5>
                                                                                    <p class="text-body font-size-14 mb-1">{{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}</p>
                                                                                    <p class="text-muted font-size-11 mb-1">{{$doctor->year_of_experiance ?? 0}} years experience overall</p>
                                                                                    <h5 class="font-size-13 mt-1 mb-1">{{$doctor->hospital->name_en ?? null}}</h5>  
                                                                                    <p class="text-muted font-size-13 mb-0">{{round($doctor->hospital->location[0]->distance ?? 0.0)}} km away</p>
                                                                                </div>
                                                                            </div>
                                                                        <!-- </a> -->
                                                                        <div class="col-md-12 d-flex justify-content-between mt-2 align-items-center">
                                                                            @if($doctor->doctorInstantAppointmentToday ?? null)
                                                                            <a href="#" class="btn btn-primary w-100 dr-show-phone-number">Call to book Appointment</a>
                                                                            <span class="btn btn-primary w-100 dr-phone-number-tshow" style="display: none;"><strong>
                                                                            {{$doctor->appointment_phone ? ('+'.$doctor->appointment_dial_code.' '.$doctor->appointment_phone) : 'N/A'}}
                                                                            </strong></span>
                                                                            @else
                                                                            <a href="{{url('web/book-dr-appointment', $doctor->id)}}" class="btn btn-primary w-100">Book an Appointment</a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <a href="{{url('website/doctor-profile', $doctor->id)}}" class="overlay-product-link"></a>
                                                                </div>
                                                            </div>
                                                            
                                                            @endforeach
                                                        </div>
                                                        <!-- end row -->
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-sm-6">
                                                        <div>
                                                            <p class="mb-sm-0">Page {{ $doctors->currentPage() }} of {{ $doctors->lastPage() }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="float-sm-end">
                                                            {{ $doctors->appends($requestParams)->links('pagination::bootstrap-4') }}
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- container-fluid -->
                </div>
            <!-- </div> -->
            <!-- end main content-->
@endsection

@section('custom_js')
    <script>

        function getLocation() {
            let currentLatitude = localStorage.getItem('current_latitude');
            let currentLongitude = localStorage.getItem('current_longitude');
            $("#latitude").val(currentLatitude);
            $("#longitude").val(currentLongitude);
        }

        $(document).ready(function() {
            $('.dr-show-phone-number').on('click', function(event) {
                event.preventDefault();
                $(this).hide(); // Hide the "Call to book Appointment" button
                $('.dr-phone-number-tshow').show(); // Show the phone number span
            });
            getLocation();
        });
        function lodHospitals(){
            var filters = {};

            if($('#insurance-policy').val()){
                filters["insurance_id"]= $('#insurance-policy').val()
            }
            if($('#sub-insurance-policy').val()){
                filters["sub_insurance_id"]= $('#sub-insurance-policy').val()
            }
            if($('#emirates').val()){
                filters["emirate_id"]= $('#emirates').val()
            }
            if($('#area').val()){
                filters["area_id"]= $('#area').val()
            }
            if($('#specialty').val()){
                filters["dr_specialty"]= $('#specialty').val()
            }
            if($('#interest').val()){
                filters["dr_interest"]= $('#interest').val()
            }
            if($('#language').val()){
                filters["dr_language"]= $('#language').val()
            }
            if($('#countryOrigin').val()){
                filters["dr_countryOrigin"]= $('#countryOrigin').val()
            }
            if($('#gender').val()){
                filters["dr_gender"]= $('#gender').val()
            }

            if (filters) {
                $('#hospital').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-hospitals') }}",
                    data: filters,
                    success: function (res) {
                        if (res) {
                            $('#hospital').html('<option value="">Hospital/ Clinic / Dental Care</option>');
                            $.each(res, function (index, data) {
                                $('#hospital').append('<option value="' + data.id+'">' + data.name_en + '</option>');
                            });
                            // $('#hospital').val(selectedId).trigger('change');
                            $('#hospital').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#hospital').empty();
                $('#hospital').append('<option value=""></option>');
            }
        }
        
        function lodSubIncurance(incuranceId){
            if (incuranceId) {
                $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                    success: function (res) {
                        if (res) {
                            $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
                            $.each(res, function (index, data) {
                                $('#sub-insurance-policy').append('<option value="' + data.id+'">' + data.title + '</option>');
                            });
                            // $('#sub-insurance-policy').val(selectedId).trigger('change');
                            $('#sub-insurance-policy').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#sub-insurance-policy').empty();
                $('#sub-insurance-policy').append('<option value=""></option>');
            }
        }
        
        function lodArea(emiratesId){
            if (emiratesId) {
                $('#area').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-area') }}/" + emiratesId,
                    success: function (res) {
                        if (res) {
                            $('#area').html('<option value="">Area</option>');
                            $.each(res, function (index, data) {
                                $('#area').append('<option value="' + data.id+'">' + data.name_en + '</option>');
                            });
                            // $('#area').val(selectedId).trigger('change');
                            $('#area').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#area').empty();
                $('#area').append('<option value=""></option>');
            }
        }

        $('#insurance-policy').on('change', function(){
            lodSubIncurance($(this).val());
            lodHospitals();
        })
        
        $('#sub-insurance-policy').on('change', function(){
            lodHospitals();
        })

        $('#emirates').on('change', function(){
            lodArea($(this).val());
            lodHospitals();
        })
        
        $('#area').on('change', function(){
            lodHospitals();
        })
        $('#specialty').on('change', function(){
            lodHospitals();
        })
        $('#interest').on('change', function(){
            lodHospitals();
        })
        $('#language').on('change', function(){
            lodHospitals();
        })
        $('#countryOrigin').on('change', function(){
            lodHospitals();
        })
        $('#gender').on('change', function(){
            lodHospitals();
        })

        $("#customRange3").ionRangeSlider({
            // type: "double",
            // grid: true,
            skin: "round",
            min: 0,
            max: 300,
            // from: ,
            // to: 150,
            postfix: " KM"
        });
    </script>
@endsection