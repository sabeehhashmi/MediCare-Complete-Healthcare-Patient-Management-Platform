@extends('admin.template.layout')

@section('content')
@if(!empty($hospital->vendordatils ?? null)) 
@php
$vendor     = $hospital->vendordatils ?? null;
$bankdata   = $hospital->bankdetails ?? null;
@endphp
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if ( session('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('success') }} </strong>
</div>
@endif
@if ( session('error'))
<div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('error') }} </strong>
</div>
@endif
<div class="card mb-5">
    <!--<div class="card p-4">-->
    <form method="post" id="admin-form" action="{{ url('admin/hospitals/'.$hospital->id) }}" enctype="multipart/form-data"
        data-parsley-validate="true" >
        @csrf
        @method('PUT') <!-- Add this line for Laravel to recognize the PUT method -->
        <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <label class="form-label" for="username">Clinic Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="name_en" name="name_en" value="{{$name}}" placeholder="Clinic Name" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                
                

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country</label>
                    <div class="position-relative select-custom-icon">
                        <select name="country" id="country" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Country">
                            @foreach($country_list as $country)
                                <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>



                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">City / Province</label>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select City">
                            @foreach($emirates_list as $emirate)
                                <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Area</label>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Area">
                            @foreach($area_list as $area)
                                <option {{($area->id==$area_id)?'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                
                
                <div class="col-12 mb-4">
                    <label class="form-label" for="username">Address Of Organization</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="address" name="address" placeholder="Enter Address Of Organization" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Clinic Main Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code" name="dial_code" >
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" data-jqv-required="true" id="email" name="email" placeholder="Enter Address" />
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                        <span class="bx bx-lock-alt"></span>
                        <input type="password" class="form-control" {{!$id ? 'data-jqv-required' : ''}} id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Website</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="website" name="website" placeholder="Enter Website" />
                        <span class="bx bx-globe"></span>
                    </div>
                </div>

                {{-- <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Direct Call for Appointment Number</label>
                    <div class="position-relative">
                        <input type="hidden" id="direct_dial_code" name="direct_dial_code" >
                        <input type="text" class="form-control" id="phone1" name="direct_phone" placeholder="Enter Direct Call for Appointment Number" />
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div> --}}

                
                
                <div class="col-12 mb-4">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Clinic Profile</label>
                        <textarea class="form-control" id="ckeditor-classic" name="profile_bio" row="5"></textarea>
                    </div>
                </div>
                <div class="col-12 mb-4 d-none">
                    <div class="custom-textarea">
                        <label class="form-label" for="arabi_profile">Clinic Profile(ar)</label>
                        <textarea class="form-control" id="ckeditor-classic-ar" name="profile_bio_ar" row="5" dir="rtl"></textarea>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Clinic Images <span>(Allowed 3 Photos with Dim 750px X 750px)</span></label>
                        <input class="form-control" type="file" id="upload_imgs"  accept="image/jpg, image/jpeg"  multiple />
                    </div>
                    <div id="image_preview" class="row"></div>
                </div>

                <div class="col-12 d-flex">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                    <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                </div>
            </div>
    </form>
</div>
@stop

@section('page_script')
<script>
        $(document).ready(function() {
        $('#speciality_id').select2();
        $('#service_id').select2();
    });
    function loadEmirates(country_id){
        if (countryId) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-emirates') }}/" + countryId,
                success: function (res) {
                    if (res) {
                        $('#emirate').empty();
                        $('#emirate').append('<option value="">Select Emirate</option>');
                        $.each(res, function (index, emirate) {
                            $('#emirate').append('<option value="' + emirate.id + '">' + emirate.name_en + '</option>');
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching emirates:', error);
                }
            });
        } else {
            $('#emirate').empty();
            $('#emirate').append('<option value="">Select Emirate</option>');
        }
    }
    loadEmirates($('#country').val());
    $('#country').change(function () {
        loadEmirates($(this).val())
    });

    $('#emirate').change(function () {
        var emirateId = $(this).val();
        if (emirateId) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-areas') }}/" + emirateId,
                success: function (res) {
                    if (res) {
                        $('#area').empty();
                        $('#area').append('<option value="">Select Area</option>');
                        $.each(res, function (index, area) {
                            var areaName = area.name_ar ? (area.name_en + ' (' + area.name_ar + ')') : area.name_en || 'Empty';
                            $('#area').append('<option value="' + area.id + '">' + areaName + '</option>');
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching areas:', error);
                }
            });
        } else {
            $('#area').empty();
            $('#area').append('<option value="">Select Area</option>');
        }
    });

    function previewImages(input) {
        var preview = document.getElementById('image-preview');

        if (input.files) {
            var filesAmount = input.files.length;

            for (var i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    var imgElement = document.createElement('img');
                    imgElement.setAttribute('src', event.target.result);
                    imgElement.setAttribute('class', 'img-fluid');
                    imgElement.setAttribute('alt', 'Image');
                    var divElement = document.createElement('div');
                    divElement.setAttribute('class', 'col-md-3 mb-3');
                    divElement.appendChild(imgElement);
                    preview.appendChild(divElement);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }
    }

</script>
@stop
