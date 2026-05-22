@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
<style>
    .error-message{color: red;}
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
      margin-left: 10px !important;
    }
    .removeDocument{
        min-width: 50px;
        font-size: 20px !important;
        font-weight: bold !important;
    }
    .addDocument{
        min-width: 50px;
        font-size: 20px !important;
        font-weight: bold !important;
    }
</style>
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('admin.doctors.save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                
                @if(!$hospital && !$clinic)
                <div class="col-12 mb-3">
                    <label class="form-label" for="username">Name of Hospital/Clinic Name/ Dental Care/ Home Care</label>
                    <div class="position-relative select-custom-icon">
                        <select name="hospital_id" id="hospital_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Hospital/Clinic Name/ Dental Care/ Home Care">
                        <option  value=""></option>
                            @foreach($hospitals as $hospital)
                                <option {{($hospital->id == ($doctor->hospital_id ?? null)) ? "selected" : ""}} data-type="{{$hospital->type}}" value="{{$hospital->id}}">{{$hospital->name_en}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                @else
                <input type="hidden" value="{{$hospital->id ?? $clinic->id ?? null}}" name="prnt_hospital_id">
                @endif

              
                @if(($doctor->hospital->type ?? TYPE_HOSPITAL) == TYPE_HOSPITAL && !$clinic)
                <div class="col-12 mb-3" id="department-field">
                    <label class="form-label" for="departments">Departments </label>
                    <!--
                        <a href="#" title="Add more" data-bs-toggle="modal" data-bs-target="#new_dept" title="Click to Add More Department" class="float-end me-2">Add More</a>
                    -->
                    <div class="position-relative select-custom-icon">
                        <select name="departments[]" id="departments" class="select2-single jqv-input" multiple data-jqv-required="true" role="select2" data-placeholder="Select Departments">
                            @if(count($department_list))
                            @foreach($department_list as $department)
                                <option {{in_array($department->id, $selected_departments) ? "selected" : ""}} value="{{$department->id}}">{{$department->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                @endif

                <div class="col-6 mb-3 d-none">
                    <label class="form-label" for="username">Referral</label>
                    <div class="position-relative select-custom-icon">
                        <select name="referral_id" id="referral_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Referral">
                        <option  value=""></option>
                            @foreach($referrals as $referral)
                                <option {{($referral->id == ($doctor->referral_id ?? null)) ? "selected" : ""}}  value="{{$referral->id}}">{{$referral->title}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label" for="username">First Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="first_name" name="first_name" value="{{$first_name}}" placeholder="First Name" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Last Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" id="last_name" name="last_name" value="{{$last_name}}" placeholder="Last Name" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Qualification </label><a href="#" class="float-end me-2"  data-bs-toggle="modal" data-bs-target="#new_education" title="Click to Add More Qualification">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="qualification[]" id="qualification" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Qualification">
                            @foreach($qualification as $qualification)

                @php
                    $selected = is_array($qualification_id) && in_array($qualification->id, $qualification_id) ? 'selected' : '' ;
                @endphp

                <option value="{{ $qualification->id }}" {{ $selected}}>{{ $qualification->title }}</option>
                @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Specialty </label> <a href="#" class="float-end me-2"  data-bs-toggle="modal" data-bs-target="#new_special" title="Click to Add More Specialiaty">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="specialty[]" id="specialty" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Specialty">
                            @foreach($specialty as $specialty)



                                @php
                    $selected = is_array($speciality_id) && in_array($specialty->id, $speciality_id) ? 'selected' : '' ;
                @endphp


                <option value="{{ $specialty->id }}" {{$selected }}>{{ $specialty->name_en }}</option>


                                @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Special Intrests </label> <a href="#" class="float-end me-2"  data-bs-toggle="modal" data-bs-target="#new_intereset" title="Click to Add More Interest">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="special_interest[]" id="special_interest" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Special Interest">
                            @foreach($special_interest as $special_interest)


                                @php
                    $selected = is_array($special_intrest_id) && in_array($special_interest->id, $special_intrest_id) ? 'selected' : '' ;
                @endphp


                <option value="{{ $special_interest->id }}" {{ $selected }}>{{ $special_interest->title }}</option>


                                @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="experiences">Years of Experiences</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);" class="form-control jqv-input" data-jqv-required="true" id="experiences" name="experiences" value="{{$experiences}}" maxlength="3"  placeholder="Years of Experiences" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="experiences">Consultation Fee</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);" class="form-control jqv-input" data-jqv-required="true" id="consultation_fee" name="consultation_fee" value="{{$consultation_fee}}" maxlength="5"  placeholder="Consultation Fee" />
                        
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country of Origin </label> <a href="#" class="float-end me-2" title="Add more" data-bs-toggle="modal" data-bs-target="#newcountryOriginmodel" title="Click to Add More Country">Add More </a>
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
                    <label class="form-label" for="username">Language Spoken  </label> <a href="#" class="float-end me-2" title="Add more" data-bs-toggle="modal" data-bs-target="#new_lang" title="Click to Add More Language">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="language_spoken_id[]" id="language_spoken_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Language Spoken">
                        @foreach($language_spoken as $language_spoken)


                               @php
                                  $selected = is_array($language_spoken_id) && in_array($language_spoken->id, $language_spoken_id) ? 'selected' : ''  ;
                             @endphp


                               <option value="{{ $language_spoken->id }}" {{ $selected}}>{{ $language_spoken->title }}</option>
                               @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                    <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="gender" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Gender">
                        <option value="">Select gender</option>
                                <option {{($doctor->user->gender ?? null) ==1?'selected':''}} value="1">Male</option>
                                <option {{($doctor->user->gender ?? null) ==2?'selected':''}} value="2">Female</option>
                                <option {{($doctor->user->gender ?? null) ==3?'selected':''}} value="3">Other</option>
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="phone">Clinic Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control jqv-input" id="phone" data-jqv-required="true" name="phone" value="{{old('phone', $doctor->user->phone ?? null)}}" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code" name="dial_code" value="{{old('dial_code', $doctor->user->dial_code ?? null)}}">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" data-jqv-required="true" id="email" name="email" value="{{old('email', $doctor->user->email ?? null)}}" placeholder="Enter Address" />
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                        <span class="bx bx-lock-alt"></span>
                        <input type="password" class="form-control jqv-input" data-jqv-required="{{(!$id ? 'true' : 'false')}}" id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-12 mb-4">
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="video_conssultant"
                            name="video_conssultant"
                            value="1"
                            {{ isset($doctor->user) && $doctor->user->video_conssultant == 1 ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="video_conssultant">
                            Available for Video Conssultant
                        </label>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <label class="form-label" for="license_no_dha">DHA- License No</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input license_number" id="license_no_dha" name="license_no_dha" value="{{$doctor->license_no ?? null}}" placeholder="DHA License No" />
                        <div class="error-message" id="error_license_no_dha"></div>
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="form-label" for="license_no_moh">MOH- License No</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input license_number" id="license_no_moh" name="license_no_moh" value="{{$doctor->license_no_moh ?? null}}" placeholder="MOH License No" />
                        <div class="error-message" id="error_license_no_moh"></div>
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="form-label" for="license_no_doh">DOH- License No</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input license_number" id="license_no_doh" name="license_no_doh" value="{{$doctor->license_no_doh ?? null}}" placeholder="DOH License No" />
                        <div class="error-message" id="error_license_no_doh"></div>
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <label class="form-label" for="license_no_dhcc">DHCC- License No</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input license_number" id="license_no_dhcc" name="license_no_dhcc" value="{{$doctor->license_no_dhcc ?? null}}" placeholder="DOH License No" />
                        <div class="error-message" id="error_license_no_dhcc"></div>
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Profile</label>
                        <textarea class="form-control" id="ckeditor-classic" name="profile_bio" row="5">{{$doctor->profile_desciription ?? null}}</textarea>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Direct Contact Number for Appointment (Optional)</label>
                    <div class="position-relative">
                    <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{old('appointment_dial_code', $doctor->appointment_dial_code ?? null)}}" >
                    <input type="text" class="form-control jqv-input" id="phone1" name="direct_phone" value="{{old('appointment_phone', $doctor->appointment_phone ?? null)}}" placeholder="Enter Direct Call for Appointment Number" />
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($user->user_img_url ?? null)
                        <a id="previewLink" href="{{$user->user_img_url}}" target="_blank">View Image</a>
                        @endif
                    </div>
                </div>

                 <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Signature</label>
                        <input class="form-control jqv-input" id="signature" type="file" name="signature" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($doctor->signature ?? null)
                        <a id="previewLink" href="{{$doctor->user_signature}}" target="_blank">View Signature</a>
                        @endif
                    </div>
                </div>

                <div class="col-12 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-2">

        <label class="form-label">Doctor Documents</label>

        <button type="button"
                class="btn btn-success btn-sm addDocument"
                id="addDocument">

            +
        </button>

    </div>

    <div id="documentWrapper">

       @if(isset($doctor) && $doctor->documents->count())

    @foreach($doctor->documents as $doc)

        <div class="document-row row mb-3">

            <input type="hidden"
                   name="document_ids[]"
                   value="{{ $doc->id }}">

            <!-- ADD THIS -->
            <input type="hidden"
                   class="existing-document"
                   value="{{ $doc->document ? 1 : 0 }}">

            <div class="col-lg">

                <input type="text"
                       name="document_titles[]"
                       class="form-control"
                       value="{{ $doc->title }}"
                       placeholder="Enter Title">

            </div>

            <div class="col-lg-5">

                <input type="file"
                       name="documents[]"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                @if($doc->document)

                    <a href="{{ $doc->document }}"
                       target="_blank">

                        View File

                    </a>

                @endif

            </div>

            <div class="col-lg-auto">

                <button type="button"
                        class="btn btn-danger removeDocument">

                    -

                </button>

            </div>

        </div>

    @endforeach

@endif

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
<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone");

const ph = window.intlTelInput(input, {
//    initialCountry: '{{INIT_PHONE_C_CODE}}',
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
var existingDialCode = "{{($doctor)? $doctor->user->dial_code:'' }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});

setTimeout(function() {
        let phoneValue = inputTel.value;
        if (phoneValue.startsWith('0')) {
            inputTel.value = phoneValue.substring(1);  // Remove the leading zero
        }
    }, 1000);


    document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone1");

const ph = window.intlTelInput(input, {
 //   initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDialCode() {
    var dialCode = ph.getSelectedCountryData().dialCode;
    document.getElementById('direct_dial_code').value = dialCode;
}

input.addEventListener("input", updateDialCode);
input.addEventListener("countrychange", updateDialCode);

// 🔹 Set country in EDIT case
var existingDialCode = "{{($doctor)? $doctor->appointment_dial_code:'' }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});

setTimeout(function() {
        let phoneValue1 = inputTelDir.value;
        if (phoneValue1.startsWith('0')) {
            inputTelDir.value = phoneValue1.substring(1);  // Remove the leading zero
        }
    }, 1000);
let fileArr =[];
$(document).ready(function() {

    App.initFormView();
    let form_in_progress=0;

    $('body').off('submit', '#admin_form');
    $('body').on('submit', '#admin_form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
        var $form = $(this);
        var formData = new FormData(this);
        let i = 0;
        $.each(fileArr, function (k, v) {
            formData.append('images['+i+']', v);
            i++;
        });

        var isValid = false;

        // Clear previous error messages
        $('.error-message').text('');

        // Check if at least one field is filled
        $('.license_number').each(function() {
            if ($(this).val().trim() !== '') {
                isValid = true;
                return false; // break the loop if a valid field is found
            }
        });

       // VALIDATE DOCUMENTS
let hasError = false;

$('.document-row').each(function () {

    let title = $(this).find('input[name="document_titles[]"]').val();

    let fileInput = $(this).find('input[name="documents[]"]')[0];

    let existingDocument = $(this).find('.existing-document').val();

    // remove old errors
    $(this).find('.doc-error').remove();

    let hasExistingFile = existingDocument == 1;

    let hasNewFile = fileInput.files.length > 0;

    // title entered but no old/new file
    if(title && !hasExistingFile && !hasNewFile){

        hasError = true;

        $(fileInput).after(
            '<small class="text-danger doc-error">Please select document file.</small>'
        );
    }

    // file selected but no title
    if(hasNewFile && title.trim() === ''){

        hasError = true;

        $(this).find('input[name="document_titles[]"]').after(
            '<small class="text-danger doc-error">Please enter title.</small>'
        );
    }
});

if(hasError){

    toastr["error"]("Please fill all document fields properly.");

    return false;
}

        if (!isValid) {
            event.preventDefault(); // Prevent the form from submitting or the button action

            // Show error message below each input field
            $('.license_number').each(function() {
                var id = $(this).attr('id');
                $('#error_' + id).text('Please fill in at least one license number.');
            });
        }

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

        if ( $form.valid() ) {
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
            $form.find('[type="submit"]').prop("disabled",true);
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
                dataType:'html',
                success: function (res) {
                    $('.error-message').html('');
                    $('.invalid-feedback').html('');
                    res = JSON.parse(res);
                    console.log(res['status']);
                    form_in_progress = 0;
                    App.loading(false);
                    if ( res['status'] == 0 ) {
                        $form.find('[type="submit"]').prop("disabled",false);
                        $form.find('[type="submit"]').text("Save");
                        if ( typeof res['errors'] !== 'undefined' ) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function (e_field, e_message) {
                                if ( e_message != '' ) {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                    if ( error_index == 0 ) {
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
                            var m = res['message']||'Unable to save Doctor. Please try again later.';
                            App.alert(m, 'Oops!','error');
                        }
                    } else {
                        App.alert(res['message']||'Record saved successfully', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = res['oData']['redirect'];
                        },2500);

                    }

                },
                error: function (e) {
                    form_in_progress = 0;
                    App.loading(false);
                    $form.find('[type="submit"]').prop("disabled",false);
                    $form.find('[type="submit"]').text("Save");
                    console.log(e);
                    App.alert( "Network error please try again", 'Oops!','error');
                }
            });
         });
    });

});
</script>

<script>
    $("#base-style").DataTable();
    ClassicEditor.create(document.querySelector("#ckeditor-classic"))
    .then(function (e) {
        e.ui.view.editable.element.style.height = "200px";
    })
    .catch(function (e) {
        console.error(e);
    });

    ClassicEditor.create(document.querySelector("#ckeditor-classic-ar"))
    .then(function (e) {
        e.ui.view.editable.element.style.height = "200px";
    })
    .catch(function (e) {
        console.error(e);
    });

    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
</script>


<script>
    $(document).ready(function() {

        function loadDepartments(hospital_id, selectedEmirateId = ''){
            if (hospital_id) {
                document.getElementById('new_hospital_id').value = hospital_id;
                $('#departments').html('<option value="">Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-hospital-departments') }}/" + hospital_id,
                    success: function (res) {
                        if (res) {
                            // $('#departments').empty();
                            $('#departments').html('');
                            $.each(res, function (index, department) {
                                $('#departments').append('<option value="' + department.id+'">' + department.title + '</option>');
                            });
                            $('#departments').val(selectedEmirateId).trigger('change');
                            $('#departments').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });
            } else {
                // $('#emirate_id').empty();
                $('#departments').html('<option value="">Select Departments</option>');
            }
        }

    // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

    $('#hospital_id').on("change", function () {
        let selectedOption = $(this).find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
            loadDepartments($(this).val())
        }else{
            $('#departments').html('<option value=""></option>');
            $('#department-field').hide();
        }
    });

   $("#upload_imgs").change(function(){
      // check if fileArr length is greater than 0
       if (fileArr.length > 0) fileArr = [];

        $('#image_preview').html("");
        var total_file = document.getElementById("upload_imgs").files;

        if(total_file.length > 3){
            App.alert("You can select max of 3 images");
            return false;
        }
        if (!total_file.length) return;
        for (var i = 0; i < total_file.length; i++) {
        //   if (total_file[i].size > 1048576) {
        //     return false;
        //   } else {
            fileArr.push(total_file[i]);
            console.log(fileArr);
            $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(total_file[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'><button id='action-icon' value='img-div"+i+"' class='btn btn-danger btn-icon' role='"+total_file[i].name+"'><i class='fa fa-trash'></i></button></div></div>");
          //}
        }
   });

  $('body').on('click', '#action-icon', function(evt){
      var divName = this.value;
      var fileName = $(this).attr('role');
      $(`#${divName}`).remove();

      for (var i = 0; i < fileArr.length; i++) {
        if (fileArr[i].name === fileName) {
          fileArr.splice(i, 1);
        }
      }
    document.getElementById('images').files = FileListItem(fileArr);
      evt.preventDefault();
  });

   function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }
});
$(document).ready(function () {

    $('#addDocument').click(function () {

        let html = `

            <div class="document-row row mb-3">

                <div class="col-lg">

                    <input type="text"
                           name="document_titles[]"
                           class="form-control"
                           placeholder="Enter Title">

                </div>

                <div class="col-lg-5">

                    <input type="file"
                           name="documents[]"
                           class="form-control"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                </div>

                <div class="col-lg-auto">

                    <button type="button"
                            class="btn btn-danger removeDocument">

                        -

                    </button>

                </div>

            </div>
        `;

        $('#documentWrapper').append(html);

    });

    $(document).on('click', '.removeDocument', function () {

        $(this).closest('.document-row').remove();

    });

});

</script>
@include('admin.layouts.masterdata')
@stop
