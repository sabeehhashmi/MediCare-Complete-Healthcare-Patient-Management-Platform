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
        <form id="admin_form" method="post" action="{{route('admin.hospitals.saveAppointment')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <label class="form-label" for="bs-validation-insurence">Patient </label>
                    <select name="patient" id="patient" class="form-control jqv-inuput" role="select2">
                        <option value=""></option>
                        @foreach($patients as $item)
                        <option {{($row->user_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" value="{{$hospital_id}}" name="hospital">
                <div class="col-xs-12 col-sm-6">
                    <label class="form-label" for="bs-validation-insurence">Department </label>
                    <select name="department" id="department" class="form-control jqv-inuput" role="select2">
                        <option value=""></option>
                        @foreach($departments as $item)
                        <option {{($row->department_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <label class="form-label" for="bs-validation-insurence">Doctor </label>
                    <select name="doctor" id="doctor" class="form-control jqv-inuput" role="select2">
                        <option value=""></option>
                        @foreach($doctors as $item)
                        <option {{($row->doctor_id ??  null) == $item->user_id ? 'selected':''}} value="{{$item->user_id}}">{{$item->user->name ?? N/A}} {{$item->last_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xs-12 col-sm-6 mb-3">
                    <label class="form-label" for="username">Booking Date</label>
                    <div class="position-relative">
                        <input type="text" name="booking_date" id="reschedule_booking_date" value="{{$row->booking_date ?? null}}" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="username">Select Slot</label>
                        <div class="timeslot-selector timeslot-selector-modal">
                        

                        @for($i = 0; $i < count($time_slot); $i++)
                            <span>
                                    
                                <input type="radio" id="sat{{$i+17+$i}}"  name="booking_time_slot" {{($row->booking_time_slot ?? null) == $time_slot[$i] ? 'checked' : ''}}  value="{{$time_slot[$i]}}"class="idReschedule time-slot checkbx-style" 
                                
                                />
                                    
                                    <label for="sat{{$i+17+$i}}">{{$time_slot[$i]}}</label>
                        </span>
                                </span>
                            @endfor


                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6 mb-3">
                    <label class="form-label" for="bs-validation-insurence">Member </label>
                    <select name="member" id="member" class="form-control jqv-inuput" role="select2">
                        <option value=""></option>
                        @foreach($members as $item)
                        <option {{($row->member_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->full_name}}</option>
                        @endforeach
                    </select>
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

        function loadMember(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-members') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            $('#member').empty();
                            // $('#departments').append('<option value="">Select Departments</option>');
                            $.each(res, function (index, data) {
                                $('#member').append('<option value="' + data.id+'">' + data.full_name + '</option>');
                            });
                            // $('#member').val(selectedId).trigger('change');
                            $('#member').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            } else {
                $('#member').empty();
                $('#member').append('<option value=""></option>');
            }
        }
        
        function loadDepartments(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-hospital-departments') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            $('#department').empty();
                            $('#department').append('<option value="">Select Departments</option>');
                            $.each(res, function (index, data) {
                                $('#department').append('<option value="' + data.id+'">' + data.title+ '</option>');
                            });
                            // $('#department').val(selectedId).trigger('change');
                            $('#department').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            } else {
                $('#department').empty();
                $('#department').append('<option value=""></option>');
            }
        }
        
        function loadDoctorsHospital(hospitalId, departmentId){
            if (hospitalId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-hospital-doctors') }}/" + hospitalId,
                    success: function (res) {
                        if (res) {
                            $('#doctor').empty();
                            $('#doctor').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctor').append('<option value="' + data.user_id+'">' + data.user?.name + '</option>');
                            });
                            // $('#doctor').val(selectedId).trigger('change');
                            $('#doctor').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#doctor').empty();
                $('#doctor').append('<option value=""></option>');
            }
        }
        
        function loadDoctorsDepartment(departmentId){
            if(departmentId){
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-department-doctors') }}/" + departmentId,
                    success: function (res) {
                        if (res) {
                            $('#doctor').empty();
                            $('#doctor').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctor').append('<option value="' + data.user_id+'">' + data.user?.name + '</option>');
                            });
                            // $('#doctor').val(selectedId).trigger('change');
                            $('#doctor').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('#doctor').empty();
                $('#doctor').append('<option value=""></option>');
            }
        }

        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#patient').on("change", function () {
            loadMember($(this).val())
        });
        
        $('#hospital').on("change", function () {
            loadDepartments($(this).val())
            loadDoctorsHospital($(this).val())
        });
        
        $('#department').on("change", function () {
            loadDoctorsDepartment($(this).val())
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
        minDate: "today",
    });
    

    // Get the intial country for the phone based on the dial code
    const dialCode = $('#dial_code').val();
    const countryIsoCode = getCountryCodeByDialCode(dialCode);


    const input = document.querySelector("#phone");
    const ph = window.intlTelInput(input, {

     //   initialCountry: '{{INIT_PHONE_C_CODE}}',
        //strictMode: true,
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