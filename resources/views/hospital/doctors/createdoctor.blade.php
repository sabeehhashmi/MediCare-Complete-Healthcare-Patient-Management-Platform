@include('hospital.layouts.header')
<div class="position-relative mb-5">
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{route('hospital.saveDoctor')}}" class="doctorform" id="doctorform">
                @csrf
                <div class="row">
                   
                    <input type="hidden" name="id", value="{{$id}}">
                    <div class="row">
                        

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="">Select Department </label>
                                                <div class="position-relative select-custom-icon">
                                                    <select name="departments[]" id="department" class="select2-single" data-placeholder="Select Department" multiple>
                                                    <option value="">Select Department </option>
                                                        @foreach($departments as $department)
                                                            <option {{in_array($department->id, $department_id) ? 'selected':''}}  value="{{$department->id}}">{{$department->title}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fi fi-rr-bed-alt" style="margin-top: 2px;"></i>
                                                </div>
                                            </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">First Name</label>
                            <div class="position-relative input-custom-icon">
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{$first_name}}" placeholder="Enter First Name" />
                                <span class="bx bx-user"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Last Name</label>
                            <div class="position-relative input-custom-icon">
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{$last_name}}" placeholder="Enter Last Name" />
                                <span class="bx bx-user"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Qualification </label>
                            <div class="position-relative select-custom-icon">
                                <select name="qualification[]" id="qualification" class="select2-single" multiple data-placeholder="Select Qualification">
                                @foreach($qualification as $qualification)
                                    @php
                                        $selected = is_array($qualification_id) && in_array($qualification->id, $qualification_id) ? 'selected' : '' ;
                                    @endphp
                                  <option value="{{ $qualification->id }}" {{ $selected}}>{{ $qualification->title }}</option>
                                @endforeach
                                </select>
                                <i class="fi fi-rr-graduation-cap" style="margin-top: 2px;"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Specialty</label>
                            <div class="position-relative select-custom-icon">
                                <select name="specialty[]" id="specialty" class="select2-single" multiple data-placeholder="Select Specialty">
                                   @foreach($specialty as $specialty)
                                        @php
                                        $selected = is_array($speciality_id) && in_array($specialty->id, $speciality_id) ? 'selected' : '' ;
                                        @endphp
                                      <option value="{{ $specialty->id }}" {{$selected }}>{{ $specialty->name_en }}</option>           
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-doctor" style="margin-top: 2px;"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Special Interest</label>
                            <div class="position-relative select-custom-icon">
                                <select name="special_interest[]" id="special_interest" class="select2-single" multiple data-placeholder="Select Special Interest">
                                @foreach($special_interest as $special_interest)
                                    @php
                                      $selected = is_array($special_intrest_id) && in_array($special_interest->id, $special_intrest_id) ? 'selected' : '' ;
                                    @endphp
                                    <option value="{{ $special_interest->id }}" {{ $selected }}>{{ $special_interest->title }}</option>
                                @endforeach
                                </select>
                                <i class="fi fi-rr-syringe" style="margin-top: 2px;"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Years of Experiences</label>
                            <div class="position-relative input-custom-icon">
                                <input type="number" class="form-control" id="experiences" name="experiences" value="{{$experiences}}" maxlength="3" pattern="\d{1,3}" placeholder="Enter Years of Experiences" />
                                <span class="fi fi-rr-stars"></span>
                            </div>
                        </div>
                        
                <div class="col-lg-8"></div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Country of Orgin</label>
                            <div class="position-relative select-custom-icon">
                                <select name="country" id="country" class="select2-single" data-placeholder="Select Country">
                                    @foreach($country_list as $country)
                                    <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                <i class="bx bx-navigation"></i>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Language Spoken</label>
                            <div class="position-relative select-custom-icon">
                                <select name="language_spoken_id[]" id="language_spoken_id" class="select2-single" multiple data-placeholder="Select Language Spoken">
                                @foreach($language_spoken as $language_spoken)
                                    @php
                                        $selected = is_array($language_spoken_id) && in_array($language_spoken->id, $language_spoken_id) ? 'selected' : ''  ;
                                    @endphp
                                   <option value="{{ $language_spoken->id }}" {{ $selected}}>{{ $language_spoken->title }}</option>
                                @endforeach
                                </select>
                                <i class="fi fi-rs-square-a" style="margin-top: 2px;"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Gender</label>
                            <div class="position-relative select-custom-icon">
                                <select name="gender" id="gender" class="select2-single" data-placeholder="Select Gender">
                                    <option></option>
                                    <option {{($gender=='1')?'selected':''}} value="1">Male</option>
                                    <option {{($gender=='2')?'selected':''}} value="2">Female</option>
                                    <option {{($gender=='3')?'selected':''}} value="3">Other</option>
                                </select>
                                <i class="fi fi-rr-venus-mars" style="margin-top: 2px;"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Clinic Direct Number to Book an Appointment</label>
                            <div class="position-relative">
                                <input type="tel" class="form-control" id="phone" value="{{$doctor->user->phone ?? null}}" name="phone" placeholder="Enter Phone Number" />
                                <!-- <span class="bx bx-phone"></span> -->
                                <input type="hidden" id="dial_code" name="dial_code" value="{{$doctor->user->dial_code ?? 971}}">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="username">Doctor Direct number to book an appointment</label>
                            <div class="position-relative">
                            <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{$doctor->appointment_dial_code ?? 971}}">
                            <input type="text" class="form-control" id="phone1" name="direct_phone" value="{{$doctor->appointment_phone ?? null}}" placeholder="Enter Phone Number" />

                                <!-- <span class="bx bx-phone"></span> -->
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="">Email</label>
                            <div class="position-relative input-custom-icon">
                                <input type="email" class="form-control" id="email"  @if($id) readonly @endif name="email" value="{{$doctor->user->email ?? null}}" placeholder="Enter Email" />
                                <span class="bx bx-envelope"></span>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="password-input">Password</label>
                            <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                <span class="bx bx-lock-alt"></span>
                                <input type="password" class="form-control" {{!$id ? 'data-jqv-required' : ''}} id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                                <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="formrow-password-input">Profile <small class="text-muted">- maximum 250 words</small></label>
                                <textarea class="form-control" name="profile_bio" id="ckeditor-classic">{{$profile_bio}}</textarea>
                            </div>
                        </div>

                        
                        
                        <div class="col-lg-6 mb-4">
                        <div class="custom-upload">
                            <label for="uploadphotos" class="form-label">Upload Photo</label>
                            <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                            <div id="imagePreview" style="display: none;"></div>
                            @if ($doctor->user->user_img_url ?? null)
                            <a id="previewLink" href="{{$doctor->user->user_img_url}}" target="_blank">View Image</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-2">

        <label class="form-label">Doctor Documents</label>

        <button type="button"
                class="btn btn-success btn-sm"
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

            <div class="col-lg-5">

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

            <div class="col-lg-2">

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
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">{{($id!='')?'Update':'Save'}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('hospital.layouts.footer')
<script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>
<script src="{{ asset('') }}hospital/assets/js/pages/pass-addon.init.js"></script>
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



    $(document).ready(function() {
    $(".doctorform").submit(function(e) {

    e.preventDefault();

    // VALIDATE DOCUMENTS
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

    var $form = $('#doctorform');

    var formData = new FormData(this);

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({

        type: 'POST',

        url: "{{ url('hospital/saveDoctor') }}",

        data: formData,

        contentType: false,

        processData: false,

        dataType: 'json',

        headers: {
            'X-CSRF-TOKEN': csrfToken
        },

        success: function(res) {

            if (res['status'] == 0) {

                if (typeof res['errors'] !== 'undefined') {

                    toastr["error"](res['message']);

                    var error_def = $.Deferred();

                    var error_index = 0;

                    $form.find('.invalid-feedback').remove();

                    $form.find('.is-invalid').removeClass('is-invalid');

                    jQuery.each(res['errors'], function(e_field, e_message) {

                        if (e_message != '') {

                            $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');

                            $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');

                            $('<div class="invalid-feedback">' + e_message + '</div>')
                                .insertAfter($('[name="' + e_field + '"]').eq(0));

                            $('<div class="invalid-feedback">' + e_message + '</div>')
                                .insertAfter($('[name="' + e_field + '[]"]').eq(0));

                            if (error_index == 0) {
                                error_def.resolve();
                            }

                            error_index++;
                        }

                    });

                    error_def.done(function() {

                        var error = $form.find('.is-invalid').eq(0);

                        $('html, body').animate({

                            scrollTop: ($form.offset().top - 100),

                        }, 500);

                    });

                } else {

                    toastr["errors"](res['message']);

                }

            } else {

                toastr["success"](res['message']);

                setTimeout(function(){

                    window.location.href = "{{ url('hospital/doctors') }}";

                },1500);
            }
        },

        error: function(xhr, status, error) {

            toastr["error"]("An error occurred while processing the request.");

            console.error(xhr.responseText);

        }
    });
});
});
$(document).ready(function () {

    $('#addDocument').click(function () {

        let html = `

            <div class="document-row row mb-3">

                <div class="col-lg-5">

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

                <div class="col-lg-2">

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