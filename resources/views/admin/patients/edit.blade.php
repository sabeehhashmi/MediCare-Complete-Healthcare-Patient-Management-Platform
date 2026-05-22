@extends('admin.template.layout')

@section('content')
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

                <!-- Identification Document Section -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-3">Government Identification Document <span class="text-danger">*</span></h5>
                        <hr>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <label class="form-label" for="identification_type">Document Type <span class="text-danger">*</span></label>
                        <div class="position-relative select-custom-icon">
                            <select name="identification_type" id="identification_type" class="form-control jqv-input" required data-jqv-required="true">
                                <option value="">Select Document Type</option>
                                <option value="national_id" {{ ($patient->identification_type ?? '') == 'national_id' ? 'selected' : '' }}>National ID (Emirates ID)</option>
                                <option value="passport" {{ ($patient->identification_type ?? '') == 'passport' ? 'selected' : '' }}>Passport</option>
                                <option value="driving_license" {{ ($patient->identification_type ?? '') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                <option value="other" {{ ($patient->identification_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <i class="bx bx-id-card"></i>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <label class="form-label" for="identification_number">Document Number <span class="text-danger">*</span></label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" class="form-control jqv-input" required data-jqv-required="true" 
                                id="identification_number" name="identification_number" 
                                value="{{ $patient->identification_number ?? '' }}" 
                                placeholder="Enter document number" />
                            <span class="bx bx-hash"></span>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <label class="form-label" for="identification_document">
                            Upload Document <span class="text-danger">{{ $id ? '' : '*' }}</span>
                            <small class="text-muted d-block">(JPG, PNG, PDF - Max 5MB)</small>
                        </label>
                        <div class="position-relative">
                            <input class="form-control jqv-input" id="identification_document" type="file" 
                                name="identification_document" accept="image/jpg, image/jpeg, image/png, application/pdf" 
                                {{ $id ? '' : 'required' }} />
                            
                            @if($id && ($patient->identification_document ?? null))
                                <div class="mt-2" id="existing-document">
                                    <small>Current document: 
                                        <a href="{{ asset('storage/documents/' . $patient->identification_document) }}" target="_blank">
                                            View {{ strtoupper(pathinfo($patient->identification_document, PATHINFO_EXTENSION)) }} Document
                                        </a>
                                    </small>
                                    <br>
                                    <small class="text-muted">Upload a new file to replace the existing document.</small>
                                </div>
                            @endif
                            
                            <div id="documentPreview" style="margin-top: 10px; display: none;">
                                <img id="documentPreviewImage" src="#" alt="Document Preview" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                <div id="documentPreviewPdf" style="display: none;">
                                    <i class="bx bxs-file-pdf" style="font-size: 48px; color: #dc3545;"></i>
                                    <span>PDF Document Selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-12 d-flex">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                        <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                    </div>
                </div>
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

    // Document preview functionality
    $('#identification_document').on('change', function() {
        var file = this.files[0];
        var fileType = file.type;
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        
        if (!validTypes.includes(fileType)) {
            App.alert('Please upload a valid file type (JPG, PNG, or PDF)', 'Invalid File', 'error');
            $(this).val('');
            $('#documentPreview').hide();
            return false;
        }
        
        if (file.size > 5 * 1024 * 1024) { // 5MB
            App.alert('File size must be less than 5MB', 'File Too Large', 'error');
            $(this).val('');
            $('#documentPreview').hide();
            return false;
        }
        
        $('#documentPreview').show();
        
        if (fileType === 'application/pdf') {
            $('#documentPreviewImage').hide();
            $('#documentPreviewPdf').show();
        } else {
            $('#documentPreviewImage').show();
            $('#documentPreviewPdf').hide();
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#documentPreviewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Add validation message for document type
    $('#identification_type, #identification_number').on('change keyup', function() {
        if ($('#identification_type').val() && $('#identification_number').val()) {
            $(this).removeClass('is-invalid');
        }
    });

</script>
@stop
