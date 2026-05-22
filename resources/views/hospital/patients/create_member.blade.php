@extends('hospital.template.layout')
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
        <form id="admin_form" method="post" action="{{route('hospital.patients.saveMember')}}" class="registerform" autocomplete="off" data-parsley-validate="true">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <input type="hidden" value="{{$patient->id}}" name="patient">

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="full_name">Full Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="full_name" name="full_name" value="{{$row->full_name ?? null}}" placeholder="Full Name" />
                        <span class="bx bx-user"></span>
                    </div>
                </div>


                <div class="col-lg-6 mb-3">
                    <label class="form-label" for="age">Age</label>
                    <div class="position-relative input-custom-icon">
                        <input type="number" class="form-control jqv-input" onKeyPress="if(this.value.length==3) return false;" data-jqv-required="true" id="age" name="age" value="{{$row->age ?? null}}" placeholder="Age" />
                        <span class="bx bx-user"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="gender">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="gender" class="jqv-input" require data-jqv-required="true" role="select2" data-placeholder="Select Gender">
                            <option  value=""></option>
                            @foreach(['1'=> 'Male', '2'=> 'Female'] as $gender_key => $gender_value)
                                <option {{($row->gender ?? 0) == $gender_key ?'selected':''}} value="{{$gender_key}}">{{$gender_value}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-male" style="margin-top: 2px;"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-insurence">Insurance </label>
                    <select name="insurence_id" id="insurence_id" class="form-control jqv-inuput" role="select2">
                        <option value="">Select</option>
                        @foreach($insurence_list as $item)
                        <option {{($row->insurence_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-insurence">Sub Insurance </label>
                    <select name="sub_insurence_id" id="sub_insurence_id" class="form-control jqv-inuput" role="select2">
                        <option value="">Select</option>
                        @foreach($sub_insurence_list as $item)
                        <option {{($row->sub_insurence_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($row->user_image ?? null)
                        <a id="previewLink" href="{{$row->user_img_url}}" target="_blank">View Image</a>
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
<script>
    $(document).ready(function() {

        function loadSubInsurance(parentId, selectedId = ''){
        if (parentId) {
            $.ajax({
                type: "GET",
                url: "{{ url('hospital/get-sub-insurance') }}/" + parentId,
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
            loadSubInsurance($(this).val(), {{$row->insurance_id ?? null}})
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


    // Get the intial country for the phone based on the dial code
    const dialCode = $('#dial_code').val();
    const countryIsoCode = getCountryCodeByDialCode(dialCode);


    const input = document.querySelector("#phone");
    const ph = window.intlTelInput(input, {

     //   initialCountry: '{{INIT_PHONE_C_CODE}}',
     //   //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,

        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input.addEventListener("input", function() {
        // Get the selected country's dial code
        var dialCode = ph.getSelectedCountryData().dialCode;
        $('#dial_code').val(dialCode);

        // If you want to use the dial code somewhere else, you can do so here
    });

    const dialCode2 = $('#whatsap_dial_code').val();
    const countryIsoCode2 = getCountryCodeByDialCode(dialCode2);


    const input2 = document.querySelector("#whatsapp_phone");
    const ph2 = window.intlTelInput(input2, {

      //  initialCountry: countryIsoCode2,
        //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,

        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input.addEventListener("input", function() {
        // Get the selected country's dial code
        var dialCode2 = ph2.getSelectedCountryData().dialCode;
        $('#whatsap_dial_code').val(dialCode2);

        // If you want to use the dial code somewhere else, you can do so here
    });
</script>
@stop
