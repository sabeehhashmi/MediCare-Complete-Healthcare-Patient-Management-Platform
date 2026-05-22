@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    @stop

    @section("content")
    <form id="instantappForm" method="post" action="{{route('admin.doctors.instantAppointmentSave')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor->id}}" name="doctor_id">
            <div class="row align-content-end">
            @if(count($doctor->doctorInstantAppointment ?? []))
            @foreach($doctor->doctorInstantAppointment as $key => $appointment)
            @php    
                $formattedDate = \Carbon\Carbon::parse($appointment->instant_appointment_date)->format('d-m-Y');
            @endphp
                <div class="row multiple-row">
                    <div class="col-lg-6 col-md-6 mb-3 mt-0 mt-lg-4">
                        <div class="instant-appoint-date">
                            <label class="form-label mb-0" for="instant_appointment_date_{{$key}}">{{$formattedDate}}</label>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-0 mt-lg-4 col-md-6 mb-3">
                    <a class="delete-instant"
                        data-id="{{encrypt($appointment->id)}}"
                        data-message="Do you want to remove the instant appointment?  This may be linked with other sections"
                        href="'.route('admin.doctors.instantAppointmentDelete', ['id' => encrypt($appointment->id)]).'">
                        <i class="bx bx-trash-alt"></i>
                      </a>
                    </div>
                </div>
                @endforeach
                @endif
                <div class="row multiple-row">
                    <div class="col-lg-6 col-md-6 mb-3">
                        <label class="form-label" for="instant_appointment_date_1">Select Date</label>
                        <div class="position-relative input-custom-icon">
                            <input required type="text" class="form-control flatpicker-input" name="instant_appointment_date[]" placeholder="Select Date" />
                            <span class="bx bx-calendar-alt"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-0 mt-lg-4 col-md-6 mb-3">
                        <div class="d-flex gap-2 mt-0 mt-lg-1">
                            <button type="button" class="btn btn-icon btn-delete remove-row"> <i class="bx bx-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="btn btn-icon btn-secondary" id="addRow"><i class="bx bx-plus"></i></span>
                    <!-- <span class="btn btn-icon btn-danger"><i class="bx bx-trash"></i></span> -->
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <!-- <button type="button" class="btn btn-delete" id="clearForm">Clear All</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @stop
@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js">
	</script>

    <script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>

<script type="text/javascript">
        App.initFormView();
        $('body').on('click', '.delete-instant', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this Record?';
            var id = $(this).data('id');
            var href = '{{ url("admin/doctors/instantappointment_delete") }}/' + id;
            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                                // window.location.href = res.o_data.redirect;
                            }, 1000);

                        } else {
                            App.alert(res['message'] || 'Unable to delete the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });

		$(document).ready(function() {
            initializeFlatpickr(); // Initialize flatpickr for the new elements
        var rowCounter = "{{ count($doctor->doctorInstantAppointment ?? []) }}";
        // Function to initialize flatpickr
        function initializeFlatpickr() {
            $(".flatpicker-input").flatpickr({
                dateFormat: "d-m-Y",
                minDate: "today",
            });
        }
        // initializeFlatpickr();
        // Function to add a new row
        $('#addRow').on('click', function() {
            rowCounter++;
            var newRow = `
            <div class="row multiple-row">
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label" for="instant_appointment_date_${rowCounter}">Select Date</label>
                    <div class="position-relative input-custom-icon">
                        <input required type="text" class="form-control flatpicker-input" name="instant_appointment_date[]" placeholder="Select Date" />
                        <span class="bx bx-calendar-alt"></span>
                    </div>
                </div>
                <div class="col-lg-4 mt-0 mt-lg-4 col-md-6 mb-3">
                    <div class="d-flex gap-2 mt-0 mt-lg-1">
                        <button type="button" class="btn btn-icon btn-delete remove-row"> <i class="bx bx-trash-alt"></i></button>
                    </div>
                </div>
            </div>`;
            $(this).parent().before(newRow);
            initializeFlatpickr(); // Initialize flatpickr for the new elements
        });

        // Function to remove a row
        $(document).on('click', '.remove-row', function() {
            if ($('.multiple-row').length > 1) {
                $(this).closest('.multiple-row').remove();
            }
        });

        // Function to clear the form
        $('#clearForm').on('click', function() {
            $('#instantappForm')[0].reset();
            // Remove all rows except the first one
            $('.multiple-row').not(':first').remove();
            // Reset first row's input fields
            $('.multiple-row').find('input').val('');
        });
    });

    $(document).ready(function() {
        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#instantappForm', function(e) {
            $('.error').html('');
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true);
            $form.find('[type="submit"]').text("Saving...");
            var formData = new FormData(this);
            let i = 0;

            var dates = [];
        var hasDuplicate = false;

        var checkemptyfiedls = false;

        $('input[name="instant_appointment_date[]"]').each(function() {
            var dateValue = $(this).val();
            if(dateValue == ""){
                checkemptyfiedls=true;  
                return false;
            }
            if (dateValue !== "") {
                if (dates.includes(dateValue)) {
                    hasDuplicate = true;
                    return false; // Exit the loop
                } else {
                    dates.push(dateValue);
                }
            }
        });

        if (hasDuplicate) {
            event.preventDefault(); // Prevent form submission
            //alert('Duplicate dates are not allowed.');
            var m =  'Duplicate dates are not allowed.';
             App.alert(m, 'Oops!', 'error');
             $form.find('[type="submit"]').prop("disabled", false);
            $form.find('[type="submit"]').text("Update");
            return false;
        }

        if (checkemptyfiedls) {
            event.preventDefault(); // Prevent form submission
            //alert('Duplicate dates are not allowed.');
            var me =  'Please fill all date fields';
             App.alert(me, 'Oops!', 'error');
             $form.find('[type="submit"]').prop("disabled", false);
            $form.find('[type="submit"]').text("Update");
            return false;
        }

            App.setJQueryValidationRules('#instantappForm');
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
                        // console.log(res['status']);
                        form_in_progress = 0;
                        App.loading(false);
                        if (res['status'] == 0) {
                           
                            $form.find('[type="submit"]').prop("disabled", false);
                            $form.find('[type="submit"]').text("Update");
                            if (typeof res['errors'] !== 'undefined') {
                                var error_def = $.Deferred();
                                var error_index = 0;
                                jQuery.each(res['errors'], function(e_field, e_message) {
                                    if (e_message != '') {
                                        $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '[]"]').eq(0));
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
                                var m = res['message'] || 'Unable to save data. Please try again later.';
                                App.alert(m, 'Oops!', 'error');
                            }
                        }else if(res['status'] == 3){
                            $form.find('[type="submit"]').prop("disabled", false);
                            $form.find('[type="submit"]').text("Update");
                            if (res.dates.length) {
                                var error_def = $.Deferred();
                                var error_index = 0;
                                var dateFields = $('[name="instant_appointment_date[]"]');
                                jQuery.each(res.dates, function(index, e_date) {
                                    dateFields.each(function() {
                                        var fieldValue = $(this).val();
                                        var fieldDate = moment(fieldValue, 'DD-MM-YYYY').format('YYYY-MM-DD'); // Convert field date to YYYY-MM-DD format
                                        if (fieldDate === e_date) {
                                            $(this).addClass('is-invalid');
                                            $('<div class="error">Duplicate date</div>').insertAfter($(this));
                                            if (error_index == 0) {
                                                error_def.resolve();
                                            }
                                            error_index++;
                                        }
                                        
                                    });
                                    return false;
                                });
                                error_def.done(function() {
                                    var error = $form.find('.is-invalid').eq(0);
                                    $('html, body').animate({
                                        scrollTop: (error.offset().top - 100),
                                    }, 500);
                                });
                            }
                        } else {
                            App.alert(res['message'] || 'Instant appointment saved successfully', 'Success!', 'success');
                            setTimeout(function() {
                                window.location.reload();
                                // window.location.href = res.o_data.redirect;
                            }, 1000);

                        }

                    },
                    error: function(e) {
                        form_in_progress = 0;
                        App.loading(false);
                        $form.find('[type="submit"]').prop("disabled", false);
                        $form.find('[type="submit"]').text("Update");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            // });
        });

    });
    </script>
@stop
