@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
<style>
    #imagePreview {
        width: 126px;
        height: 114px;
        background-size: cover;
        background-position: center;
        margin-top: 15px;
    }
</style>
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('admin.patients.save')}}" class="registerform" autocomplete="off" data-parsley-validate="true">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" required id="first_name" name="first_name" value="{{$patient->first_name ?? null}}" placeholder="First Name" />
                        <span class="bx bx-user"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" required id="last_name" name="last_name" value="{{$patient->last_name ?? null}}" placeholder="Last Name" />
                        <span class="bx bx-user"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="gender">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="gender" class="select2-single jqv-input" required data-jqv-required="true" role="select2" data-placeholder="Select Gender">
                            <option value=""></option>
                            @foreach(['1'=> 'Male', '2'=> 'Female', '3' => 'Other'] as $gender_key => $gender_value)
                                <option {{($patient->gender ?? 0) == $gender_key ?'selected':''}} value="{{$gender_key}}">{{$gender_value}}</option>
                            @endforeach

                        </select>
                        <i class="fi fi-rr-venus-mars"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Select Date of Birth</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="dob" value="{{ $patient && $patient->dob ? date('d-m-Y', strtotime($patient->dob)) : '' }}" required data-jqv-required="true" class="form-control flatpicker-input" id="" placeholder="Select Date of birth" />
                        <span class="bx bx-calendar"></span>
                    </div>
                </div>
                <!-- <div class="col-lg-6 mb-4">
                    <label class="form-label" for="language_spoken_id">Language Spoken</label>
                    <div class="position-relative select-custom-icon">
                        <select name="language_spoken_id" id="language_spoken_id" class="select2-single jqv-input" data-placeholder="Select Language Spoken" role="select2">
                        <option value="" ></option>
                        @foreach($language_spoken as $language_spoken)
                            <option value="{{ $language_spoken->id }}" >{{ $language_spoken->title }}</option>
                        @endforeach
                        </select>
                        <i class="fi fi-rs-square-a" style="margin-top: 2px;"></i>
                    </div>
                </div> -->

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" id="email" name="email" value="{{old('email', $patient->email ?? null)}}" placeholder="Enter Address" />
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>


{{--                <div class="col-lg-6 mb-4">--}}
{{--                    <label class="form-label" for="password">Password</label>--}}
{{--                    <div class="position-relative auth-pass-inputgroup input-custom-icon">--}}
{{--                        <span class="bx bx-lock-alt"></span>--}}
{{--                        <input type="password" class="form-control" id="password-input" name="password" placeholder="Enter password">--}}
{{--                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">--}}
{{--                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Phone Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control" minlength="7" required data-jqv-required="true"  maxlength="12"  value="{{ $patient->phone ?? null}}"   id="phone" name="phone" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code"  value="{{ $patient->dial_code ?? null }}"  name="dial_code">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="whatsapp_phone">WhatsApp Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control" minlength="7"  maxlength="12"  value="{{ $patient->whatsap_phone ?? null}}"  id="whatsapp_phone" name="whatsapp_phone" placeholder="Enter WhatsApp Number" />
                        <input type="hidden" id="whatsap_dial_code"  value="{{ $patient->whatsap_dial_code ?? null }}"  name="whatsap_dial_code">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-insurence">Insurance </label><a href="#"  data-bs-toggle="modal" data-bs-target="#new_insurance" title="Click to Add More Insurance" class="float-end me-2">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="insurence_id" id="insurence_id" class="form-control jqv-inuput" role="select2" data-placeholder="Select Insurance">
                            <option value=""></option>
                            @foreach($insurence_list as $item)
                            <option {{($patient->insurence_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                            @endforeach
                        </select>
                        <i class='bx bx-file'></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-insurence">Insurance Network </label> <a href="#"  data-bs-toggle="modal" data-bs-target="#new_subinsurance" title="Click to Add More Insurance Network" class="float-end me-2">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="sub_insurence_id" id="sub_insurence_id" class="form-control jqv-inuput" role="select2" data-placeholder="Select Insurance Network">
                            <option value=""></option>
                            @foreach($sub_insurence_list as $item)
                            <option {{($patient->sub_insurence_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                            @endforeach
                        </select>
                        <i class='bx bx-file'></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($patient->user_img_url ?? null)
                        <a id="previewLink" href="{{$patient->user_img_url}}" target="_blank">View Image</a>
                        @endif
                    </div>

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
                                        <a href="{{ $patient->identification_document_url }}" target="_blank">
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

                <div class="col-12 d-flex">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                    <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('page_script')
<script>
    $(document).ready(function() {

        function loadSubInsurance(parentId, selectedId = ''){
        if (parentId) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-sub-insurance') }}/" + parentId,
                success: function (res) {
                    if (res) {
                        $('#sub_insurence_id').empty();
                        // $('#departments').append('<option value="">Select Departments</option>');
                        $.each(res, function (index, data) {
                            $('#sub_insurence_id').append('<option value="' + data.id+'">' + data.title + '</option>');
                        });
                        $('#sub_insurence_id').val(selectedId).trigger('change');
                        $('#sub_insurence_id').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Sub Insurance:', error);
                }
            });
        } else {
            $('#sub_insurence_id').empty();
            $('#sub_insurence_id').append('<option value=""></option>');
        }
        }

        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#insurence_id').on("change", function () {
            loadSubInsurance($(this).val(), {{$patient->insurance_id ?? null}})
        });
    })
    let fileArr = [];
    $(document).ready(function() {

        App.initFormView();
        let form_in_progress = 0;

        $('body').off('submit', '#admin_form');
        $('body').on('submit', '#admin_form', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            var formData = new FormData(this);
            let i = 0;
            $.each(fileArr, function(k, v) {
                formData.append('images[' + i + ']', v);
                i++;
            });



            $form.validate({
                rules: {

                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    element.addClass('is-invalid');
                    error.addClass('error');
                    error.insertAfter(element);
                }
            });


            App.setJQueryValidationRules('#admin_form');

            if ($form.valid()) {
                validation.resolve();
            } else {
                var error = $form.find('.is-invalid').eq(0);
                $('html, body').animate({
                    scrollTop: (error.offset().top - 100),
                }, 500);
                validation.reject();
            }

            validation.done(function() {
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('div.error').remove();


                App.loading(true);
                $form.find('[type="submit"]').prop("disabled", true);
                $form.find('[type="submit"]').text("Processing..");


                form_in_progress = 1;
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: $form.attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    dataType: 'html',
                    success: function(res) {
                        res = JSON.parse(res);
                        console.log(res['status']);
                        form_in_progress = 0;
                        App.loading(false);
                        if (res['status'] == 0) {
                            $form.find('[type="submit"]').prop("disabled", false);
                            $form.find('[type="submit"]').text("Save");
                            if (typeof res['errors'] !== 'undefined') {
                                var error_def = $.Deferred();
                                var error_index = 0;
                                jQuery.each(res['errors'], function(e_field, e_message) {
                                    if (e_message != '') {
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                        if (error_index == 0) {
                                            error_def.resolve();
                                        }
                                        error_index++;
                                    }
                                });
                                error_def.done(function() {
                                    var error = $form.find('.is-invalid').eq(0);
                                    $('html, body').animate({
                                        scrollTop: (error.offset().top - 100),
                                    }, 500);
                                });
                            } else {
                                var m = res['message'] || 'Unable to save variation. Please try again later.';
                                App.alert(m, 'Oops!', 'error');
                            }
                        } else {
                            App.alert(res['message'] || 'Record saved successfully', 'Success!', 'success');
                            console.log(res, 'res');
                            setTimeout(function() {
                                window.location.href = res['oData']['redirect'];
                            }, 2500);

                        }

                    },
                    error: function(e) {
                        form_in_progress = 0;
                        App.loading(false);
                        $form.find('[type="submit"]').prop("disabled", false);
                        $form.find('[type="submit"]').text("Save");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            });
        });


        function emptyEmirates() {
            $('#emirate_id').empty();
            $('#emirate_id').append('<option value="">Select Emirate</option>');
        }

        function emptyArea() {
            $('#area_id').empty();
            $('#area_id').append('<option value="">Select Area</option>');
        }

        $('body').off("change", '#country');
        $('body').on("change", '#country', function() {
            var countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-emirates') }}/" + countryId,
                    success: function(res) {
                        if (res) {
                            emptyEmirates();
                            emptyArea();
                            $.each(res, function(index, emirate) {
                                $('#emirate_id').append('<option value="' + emirate.id + '">' + emirate.name_en + '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching emirates:', error);
                    }
                });
            } else {
                emptyEmirates();
                emptyArea()
            }
        });

        $('body').off("change", '#emirate_id');
        $('body').on("change", '#emirate_id', function() {
            var emirateId = $(this).val();
            if (emirateId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-areas') }}/" + emirateId,
                    success: function(res) {
                        if (res) {
                            emptyArea();
                            $.each(res, function(index, area) {
                                $('#area_id').append('<option value="' + area.id + '">' + area.name_en + '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching area:', error);
                    }
                });
            } else {
                emptyArea();
            }
        });
    });
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

<script>
    $("#base-style").DataTable();


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
                $('#previewLink').hide();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>


<script>

    function getCountryCodeByDialCode(dialCode) {
        const countryData = window.intlTelInputGlobals.getCountryData();
        for (let i = 0; i < countryData.length; i++) {
            if (countryData[i].dialCode === dialCode) {
                return countryData[i].iso2;
            }
        }
        return "AE"; // Return "AE" if no country matches the dial code
    }

    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        maxDate: "today",
    });


    document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone");

const ph = window.intlTelInput(input, {
 //   initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDialCode() {
    var dialCode = ph.getSelectedCountryData().dialCode;
    document.getElementById('dial_code').value = dialCode;
}

input.addEventListener("input", updateDialCode);
input.addEventListener("countrychange", updateDialCode);

// 🔹 Set country in EDIT case
var existingDialCode = "{{($patient)? $patient->dial_code:'' }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});



document.addEventListener("DOMContentLoaded", function () {

const input1 = document.querySelector("#whatsapp_phone");

const iti1 = window.intlTelInput(input1, {
   // initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDirectDialCode() {
    var dialCode = iti1.getSelectedCountryData().dialCode;
    document.getElementById('whatsap_dial_code').value = dialCode;
}

// when typing
input1.addEventListener("input", updateDirectDialCode);

// when changing flag
input1.addEventListener("countrychange", updateDirectDialCode);

// 🔹 Set country for edit case
var existingDialCode = "{{($patient)? $patient->whatsap_dial_code : '' }}";
if(existingDialCode){
    iti1.setNumber("+" + existingDialCode + input1.value);
}

updateDirectDialCode();

});

    
    setTimeout(function() {
        let phoneValue1 = input2.value;
        if (phoneValue1.startsWith('0')) {
            input2.value = phoneValue1.substring(1);  // Remove the leading zero
        }
    }, 500);
</script>
@include('admin.layouts.masterdata')
@stop
