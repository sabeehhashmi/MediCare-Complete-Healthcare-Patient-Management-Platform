@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content find-doc-list-wrapper">
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
                                    <form method="post" action="#" id="search-form">
                                        @csrf
                                    <div>
                                        <!-- <input type="hidden" name="current_lattiude" id="latitude" value="{{request('current_latitude')}}">
                                        <input type="hidden" name="current_longitude" id="longitude" value="{{request('current_longitude')}}"> -->
                                        <div class="custom-accordion p-4">
                                            <h5 class="font-size-14 mb-0">
                                                <a href="#categories-collapse" class="text-body d-block" data-bs-toggle="collapse">
                                                    Advance Filter <i class="mdi mdi-chevron-up float-end accor-down-icon"></i>
                                                </a>
                                            </h5>

                                            <div class="collapse show mt-4" id="categories-collapse">
                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="speciality_id" class="form-select select2-single w-100" data-placeholder="Doctors Specialty" id="specialty">
                                                        <option value="">Doctors Specialty</option>
                                                        @foreach($specialties as $id => $value)
                                                        <option value="{{$id}}" {{ request('specialty_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon specialty-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="main_insurence_id" class="form-select select2-single" data-placeholder="My Insurance Policy" id="insurance-policy">
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
                                                    <select name="medical_condition" class="form-select select2-single" data-placeholder="My Medical Condition" id="interest">
                                                        <option value="">My Medical Condition</option>
                                                        @foreach($medicalConditions as $id => $value)
                                                        <option value="{{$id}}" {{ request('medical_condition_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon medical-condition-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="doctor_language" class="form-select select2-single" data-placeholder="Doctor’s Language" id="language">
                                                        <option value="">Doctor’s Language</option>
                                                        @foreach($languages as $id => $value)
                                                        <option value="{{$id}}" {{ request('language_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-language-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative input-custom-icon mb-3">
                                                    <input type="text" name="need_date" class="form-control flatpicker-input-home" id="need_date_filter" placeholder="Search by Date" value="{{ request('date') }}" />
                                                    <span class="custom-icon calendar-doc-icn"></span>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="country_id" class="form-select select2-single" data-placeholder="Doctor’s Country of Origin" id="countryOrigin">
                                                        <option value=""> Doctor’s Country of Origin</option>
                                                        @foreach($countries as $id => $value)
                                                        <option value="{{$id}}" {{ request('cuntry_of_origin_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon counrty-orgin-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="gender" class="form-select select2-single"  data-placeholder="Doctor’s Gender" id="gender">
                                                        <option value="">Doctor’s Gender</option>
                                                        @foreach($genders as $id => $value)
                                                        <option value="{{$id}}" {{ request('gender_id') == $id ? 'selected' : '' }}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon doc-gender-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <select name="emirate_id" class="form-select select2-single" data-placeholder="Emirates" id="emirates">
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
                                                        <option value="{{$value->id}}" {{ request('hospital_id') == $value->id ? 'selected' : '' }}>{{$value->name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="custom-icon hospital-icon-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="position-relative select-custom-icon mb-3">
                                                    <input type="text" name="doctor_name" class="form-select" placeholder="Doctor’s Name" value="{{ request('doctor_name') }}" id="doctor">
                                                    <i class="custom-icon doctor-name-icn" style="margin-top: 2px;"></i>
                                                </div>

                                                <div class="form-check py-3">
                                                    <input type="checkbox" name="direct_call_enabled" class="form-check-input" id="auth-remember-chec1" {{ request('dirent_call_for_appointment') ? 'checked' : '' }} value="1">
                                                    <label class="form-check-label ms-2" for="auth-remember-chec1"> Direct Calling Number for Appointment</label>
                                                </div>
                                                <div class="form-check py-3">
                                                    <input type="checkbox" name="instend_need" class="form-check-input" id="auth-remember-check2" {{ request('ready_to_consult_instantly') ? 'checked' : '' }} value="1">
                                                    <label class="form-check-label ms-2" for="auth-remember-check2"> Ready for Consult Instantly</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 border-top">
                                            <div>
                                                <h5 class="font-size-14 mb-3">Search by Distance</h5>
                                                <input type="range" name="filter_distance" class="form-range" min="0" max="{{$max_radius}}" id="customRange3" value="{{ request('distance', 0) }}">
                                                <!-- <div class="d-flex justify-content-between">
                                                    <div class="text-muted">1 km</div>
                                                    <div class="text-muted">100 km</div>
                                                </div> -->
                                            </div>
                                        </div>
                                        

                                        <div class="p-4">
                                            <button type="submit" class="btn btn-primary w-100">Search</button>
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
                                                        <h5>Showing results <span data-role="search-result-count"></span></h5>
                                                        <!-- <ol class="breadcrumb p-0 bg-transparent">
                                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dubai</a></li>
                                                            <li class="breadcrumb-item active">Doctor</li>
                                                        </ol> -->
                                                    </div>
                                                </div>
                                            </div>
                                              <!-- Tab panes -->
                                                <div class="tab-content p-0 text-muted ">
                                                    <div class="tab-pane active position-relative overflow-hidden doctor-loader" id="popularity" role="tabpanel">
                                                        <div class="row" data-role="doc-card">
                                                            
                                                           
                                                        </div>
                                                        <!-- end row -->
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                <div class="col-sm-6 pag-ination">
                                                    <div>
                                                        <p class="mb-sm-0">Page <span id="page">1</span> of <span id="total_pages"></span></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 pag-ination">
                                                    <div class="float-sm-end">
                                                        <ul class="pagination pagination-rounded mb-sm-0" id="paginationContainer">
                                                            
                                                        </ul>
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
        totalPages = 0;
        let page = 1;
        let limit = 10;
        const processViewCard = (data)=>{
            let profileHref = '{{url("/website/doctor-profile/")}}/'+data.id;
            let bookHref = '{{url("web/book-dr-appointment")}}/'+data.id;
            var bookButton = `<a href="${bookHref}" class="btn btn-primary w-100">Book an Appointment</a>`;
            if(data.instant_appoitment_number !=''){
                profileHref+='?instant=1';
                bookButton = `<a href="tel:+${data.instant_appoitment_number}" class="btn btn-primary w-100" data-role="show-instant-number" data-instant-number="+${data.instant_appoitment_number}">Call for Instant Appointment</a>`;
            }
            if(data.direct_call_enabled == 1){
                profileHref+='?direct=1';
                //bookButton+=`<a href="#" class="btn btn-primary w-100" data-role="show-direct-number" data-direct-number="+${data.appointment_dial_code}${data.appointment_phone}">Call Book Appointment</a>`;
                
            }
            if(data.hospital.is_contract_signed == 0){
                bookButton=`<a href="tel:+${data.user.dial_code}${data.user.phone}" class="btn btn-primary w-100" data-role="show-direct-number" data-direct-number="+${data.user.dial_code}${data.user.phone}">Call for Appointment</a>`;
            }
            let sepeciality = '';
            $.each(data.doctor_specialities, function(index,value){
                sepeciality+=value.speciality.name_en+',';
            });
            sepeciality.endsWith("'") ? sepeciality.slice(0, -1) : sepeciality;
            let dimg = '{{url("public/admin-assets/assets/images/doctor_placeholder.jpg")}}';
            var html = `<div class="col-xl-6 col-lg-6 col-md-6 mb-4">
                            <div class="product-box rounded p-2  doctor-card h-100">
                                <div class="row align-items-center">
                                    <div class="col-4 pe-0">
                                        <!-- <div class="pricing-badge">
                                            <span class="badge">Available</span>
                                        </div> -->
                                        <div class="product-img bg-light">
                                            <img src="${data.user.user_img_url}" onerror="this.onerror=null; this.src='${dimg}';" alt="" class="img-fluid mx-auto d-block">
                                            <a href="${profileHref}" class="link-profile font-size-11 text-center">View Full Profile</a>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="product-content pt-0">
                                            <h5 class="mt-1 mb-2"><a href="doctor-detail.php" class="text-body font-size-20">${data.user.name}</a></h5>
                                            <p class="text-body font-size-14 mb-1">${sepeciality}</p>
                                            <p class="text-muted font-size-11 mb-1">${data.year_of_experiance} years experience overall</p>
                                            <h5 class="font-size-13 mt-1 mb-1">${data.hospital.name_en}</h5>  
                                            <p class="text-muted font-size-13 mb-0">${Math.round(data.distance,2)} km away</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-between mt-2 align-items-center">
                                      
                                        ${bookButton}
                                      
                                    </div>
                                </div>
                                <a href="${profileHref}" class="overlay-product-link"></a>
                            </div>
                        </div>`;
            return html;
        }
        const doctorsList = () => {
            $("#popularity").addClass("doctor-loader");
            $('[data-role="doc-card"').html('');
            var formData = new FormData($('#search-form')[0]);
            formData.append('current_lattiude',localStorage.getItem('current_latitude')||'25.2048');
            formData.append('current_longitude',localStorage.getItem('current_longitude')||'55.2708');
            //formData.append("_token",'{{csrf_token()}}')
            formData.append("page",page);
            formData.append("limit",limit);
            $.ajax({
                url:"{{route('get_doctors')}}",
                type:"post",
                data:formData,
                processData:false,
                contentType:false,
                cache:false,
                dataType: "json",
                async:false,
                success: function(data){
                    $("#popularity").removeClass("doctor-loader");
                    if(data.status == 1){
                        $('.pag-ination').show();
                        let result_span = `(${data.oData.total_count}/${data.oData.over_all_doctor_count})`;
                        $('#page').html(data.oData.page);
                        totalPages=data.oData.total_pages;
                        $('#total_pages').html(totalPages);
                        $('[data-role="search-result-count"]').html(result_span);
                        createPaginator(totalPages, page);

                        $.each(data.oData.list, function(index, value) {
                            $('[data-role="doc-card"').append(processViewCard(value));
                        });
                         
                         const section = document.getElementById(`popularity`);
        
                        if (section) {
                            const sectionTop = section.offsetTop;
                            window.scrollTo({
                                top: sectionTop - 30,
                                behavior: "smooth"
                            });
                        }
                    }else{
                        $('.pag-ination').hide();
                        $('[data-role="search-result-count"]').html('');
                        let imgurl = '{{url("no-result-found.svg")}}';
                        let h = `<div class="text-center no-result-img">
                        <img src="${imgurl}" /><h4 class="mt-2">No Results Found</h4></div>`;
                        $('[data-role="doc-card"').html(h);
                        const section = document.getElementById(`popularity`);
        
                        if (section) {
                            const sectionTop = section.offsetTop;
                            window.scrollTo({
                                top: sectionTop - 30,
                                behavior: "smooth"
                            });
                        }
                    }
                }
            });

        }
        $('body').off("click",'[data-role="show-direct-number"]');
        $('body').on("click",'[data-role="show-direct-number"]',function(e){
            //e.preventDefault();
            $(this).text($(this).attr("data-direct-number"));
        });
        $('body').off("click",'[data-role="show-instant-number"]');
        $('body').on("click",'[data-role="show-instant-number"]',function(e){
            //e.preventDefault();
            $(this).text($(this).attr("data-instant-number"));
        })

        function createPaginator(totalPages, currentPage) {
            let paginatorHtml = `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a href="#" class="page-link"><i class="fas fa-chevron-left"></i></a>
                </li>`;

            for (let i = 1; i <= totalPages; i++) {
                paginatorHtml += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a href="#" class="page-link">${i}</a>
                    </li>`;
            }

            paginatorHtml += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a href="#" class="page-link"><i class="fas fa-chevron-right"></i></a>
                </li>`;

            $('#paginationContainer').html(paginatorHtml);
        }
        $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                const $link = $(this);
                const $pageItem = $link.closest('.page-item');
                
                if ($pageItem.hasClass('disabled')) return;

                if ($link.find('i').hasClass('fa-chevron-left')) {
                    page = Math.max(1, page - 1);
                } else if ($link.find('i').hasClass('fa-chevron-right')) {
                    page = Math.min(totalPages, page + 1);
                } else {
                    page = parseInt($link.text(), 10);
                }

                createPaginator(totalPages, page);
                doctorsList();
                // Handle page change logic here (e.g., load new data)
                console.log('Page changed to:', page);
            });

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
            var formData = new FormData($('#search-form')[0]);
            $.ajax({
                    type: "post",
                    url: "{{ route('get_hospital_list') }}",
                    data: formData,
                    processData:false,
                    contentType:false,
                    cache:false,
                    dataType: "json",
                    async:false,
                    success: function (res) {
                        if (res) {
                            $('#hospital').html('<option value="">Hospital/ Clinic / Dental Care</option>');
                            $.each(res.oData.list, function (index, data) {
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
        }
        function lodHospitalsOld(){
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

        $('#need_date_filter').on('change', function(){
            lodHospitals();
        })

        $("#customRange3").ionRangeSlider({
            // type: "double",
            // grid: true,
            skin: "round",
            min: 0,
            max: '{{$max_radius??300}}',
            // from: ,
            // to: 150,
            postfix: " KM"
        });
        $('#search-form').submit(function(e){
            e.preventDefault();
            page=1;
            doctorsList();
        })
        $(document).ready(function(){
            doctorsList();
        })
    </script>
@endsection