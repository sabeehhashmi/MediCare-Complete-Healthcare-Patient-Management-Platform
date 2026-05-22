@include('callcenter.layouts.header')
@if(!empty($datamain->vendordatils)) 
@php
$vendor     = $datamain->vendordatils;
$bankdata   = $datamain->bankdetails;
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
    <form method="post" id="admin-form" action="{{ url('admin/hospitals/'.$datamain->id) }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        @csrf
        @method('PUT') <!-- Add this line for Laravel to recognize the PUT method -->
        <div class="">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Name (EN)<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{ old('name_en', $datamain->name_en) }}" required
                            data-parsley-required-message="Enter Name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 d-none">
                        <div class="form-group">
                            <label>Name (AR) <span style="color:red;"><span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="name_ar" value="{{ old('name_ar', $datamain->name_ar) }}"
                            data-parsley-message="Enter Name" dir="rtl">
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Country</label>
                            <select id="country" class="form-control" name="country_id">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $datamain->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>City</label>
                            <select id="emirate" class="form-control" name="emirate_id">
                                <option value="">Select Emirate</option>
                                @foreach($emirates as $emirate)
                                <option value="{{ $emirate->id }}" {{ $datamain->emirate_id == $emirate->id ? 'selected' : '' }}>{{ $emirate->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Area</label>
                            <select id="area" class="form-control" name="area_id">
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ $datamain->area_id == $area->id ? 'selected' : '' }}>{{ $area->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Specialities</label>
                            <select id="speciality_id" class="form-control" name="speciality_id[]" multiple>
                                <option value="">Select Speciality</option>
                                @foreach($specialities as $speciality)
                                    <option value="{{ $speciality->id }}" {{ $datamain->specialities->contains('id', $speciality->id) ? 'selected' : '' }}>{{ $speciality->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Services</label>
                            <select id="service_id" class="form-control" name="service_id[]" multiple>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ $datamain->services->contains('id', $service->id) ? 'selected' : '' }}>{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" class="form-control" name="website" value="{{ old('website', $datamain->website) }}">
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ old('address', $datamain->address) }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Profile Description</label>
                            <textarea class="form-control" name="profile_description" rows="3" maxlength="400">{{ old('profile_description', $datamain->profile_description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Images</label>
                            <!-- Display existing images -->
                            @foreach($datamain->images as $image)
                                <img src="{{ asset($image->image_name) }}" alt="Hospital Image" style="max-width: 100px;">
                            @endforeach
                            <!-- Input field to upload new images -->
                            <input type="file" class="form-control" name="images[]" multiple accept="image/*" onchange="previewImages(this)">
                        </div>
                    </div>
                    <div id="image-preview" class="col-sm-6 col-xs-12"></div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-sm-4 col-xs-12 other_docs" id="certificate_product_registration_div" >
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>
@include('callcenter.layouts.footer')
<script>
        $(document).ready(function() {
        $('#speciality_id').select2();
        $('#service_id').select2();
    });
    $('#country').change(function () {
        var countryId = $(this).val();
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

