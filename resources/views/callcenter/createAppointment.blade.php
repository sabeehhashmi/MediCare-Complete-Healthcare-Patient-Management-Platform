@include('callcenter.layouts.header')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="appointmentForm" method="post" action="{{route('callcenter.saveAppointment')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Booking type </label>
                                <div class="position-relative">
                                    <select name="patient" id="PatientSelct" class="select2-single" data-placeholder="Select type">
                                        <option></option>
                                            <option value="1" >New Consultation</option>
                                            <option value="2" >Follow-up Consultation</option>
                                            <option value="3" >Second Opinion</option>
                                            <option value="4" >Online Consultation</option>
                                            <option value="5" >Emergency Consultation</option>
                                    </select>
                                </div>
                            </div>
                            
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
                        <option {{($row->doctor_id ??  null) == $item->user_id ? 'selected':''}} data-id="{{$item->id}}" value="{{$item->user_id}}">{{$item->user->name ?? N/A}} {{$item->last_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xs-12 col-sm-6 mb-3">
                    <label class="form-label" for="username">Booking Date</label>
                    <div class="position-relative">
                        <input type="text" name="booking_date" id="booking_date" value="{{$row->booking_date ?? null}}" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="username">Select Slot</label>
                        <div class="timeslot-selector timeslot-selector-modal">
                        

                        @for($i = 0; $i < count($time_slot); $i++)
                            <span>
                                    
                                <input type="radio" class="availiblity" id="sat{{$i+17+$i}}" disabled name="booking_time_slot" {{($row->booking_time_slot ?? null) == $time_slot[$i] ? 'checked' : ''}}  value="{{$time_slot[$i]}}"class="idReschedule time-slot checkbx-style" 
                                
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
                        <option value="">Self</option>
                        @foreach($members as $item)
                        <option {{($row->member_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->full_name}}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="col-12">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">{{($id!='')?'Update':'Save'}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('callcenter.layouts.footer') 

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
</script>

<script>
    $(document).ready(function() {

        function loadMember(parentId){
            if (parentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-members') }}/" + parentId,
                    success: function (res) {
                        if (res) {
                            $('#member').empty();
                            $('#member').append('<option value="">Self</option>');
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
                    url: "{{ url('callcenter/get-hospital-departments') }}/" + parentId,
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
                    url: "{{ url('callcenter/get-hospital-doctors') }}/" + hospitalId,
                    success: function (res) {
                        if (res) {
                            $('#doctor').empty();
                            $('#doctor').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctor').append('<option value="' + data.user_id+'" data-id="'+data.id+'">' + data.user?.name + '</option>');
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
                    url: "{{ url('callcenter/get-department-doctors') }}/" + departmentId,
                    success: function (res) {
                        if (res) {
                            $('#doctor').empty();
                            $('#doctor').append('<option value="">Select Doctor</option>');
                            $.each(res, function (index, data) {
                                if(data.user)
                                $('#doctor').append('<option value="' + data.user_id+'" data-id="'+data.id+'">' + data.user?.name + '</option>');
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
        
        function checkAvailibility(doctor_id, date){
            if(date && doctor_id){
                $.ajax({
                    type: "POST",
                    url: "{{ url('callcenter/check_doctor_availability') }}",
                    data:{
                        'booking_date': date,
                         'doctor_user_id': doctor_id,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function (res) {
                        if (res.oData) {
                            updateSlots(res.oData.list);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }
        }

        function updateSlots(slots) {
        $('.availiblity').each(function() {
            var slot = $(this).val();
            var isAvailable = slots.find(s => s.slot_text === slot)?.is_available === "1";
            $(this).prop('disabled', !isAvailable);
        });
    }

        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#patient').on("change", function () {
            loadMember($(this).val())
        });

        $('#hospital').on("change", function () {
            loadDepartments($(this).val())
            // loadDoctorsHospital($(this).val())
        });
        
        $('#booking_date, #doctor').on("change", function () {
            checkAvailibility($('#doctor').val(), $('#booking_date').val());
        });
        
        checkAvailibility($('#doctor').val(), $('#booking_date').val());

        $('#department').on("change", function () {
            loadDoctorsDepartment($(this).val())
        });
    });


    $(document).ready(function() {
        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#appointmentForm', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            var formData = new FormData(this);
            let i = 0;
            // $.each(fileArr, function(k, v) {
            //     formData.append('images[' + i + ']', v);
            //     i++;
            // });

            // $form.validate({
            //     rules: {

            //     },
            //     errorElement: 'div',
            //     errorPlacement: function(error, element) {
            //         element.addClass('is-invalid');
            //         error.addClass('error');
            //         error.insertAfter(element);
            //     }
            // });


            App.setJQueryValidationRules('#appointmentForm');

            // if ($form.valid()) {
            //     validation.resolve();
            // } else {
            //     var error = $form.find('.is-invalid').eq(0);
            //     $('html, body').animate({
            //         scrollTop: (error.offset().top - 100),
            //     }, 500);
            //     validation.reject();
            // }

            // validation.done(function() {
            //     $form.find('.is-invalid').removeClass('is-invalid');
            //     $form.find('div.error').remove();


            //     App.loading(true);
            //     $form.find('[type="submit"]').prop("disabled", true);
            //     $form.find('[type="submit"]').text("Processing..");


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
            // });
        });
    });
</script>