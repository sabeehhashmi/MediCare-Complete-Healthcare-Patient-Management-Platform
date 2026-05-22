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
    <form id="holidayForm" method="post" action="{{route('admin.doctors.holiday_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor->id}}" name="doctor_id">
            <div class="row align-content-end">
            @if(count($holidays ?? []))
            @foreach($holidays as $key => $holiday)
            @php    
                $formattedDate = \Carbon\Carbon::parse($holiday->holiday_date)->format('d-m-Y');
            @endphp
                <div class="row multiple-row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label class="form-label" for="holiday_name_{{$key}}">Holiday</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" class="form-control" name="holiday_name[]" required placeholder="Holiday Name" value="{{$holiday->holiday_name ?? null}}" />
                            <span class="bx bx-calendar-alt"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label class="form-label" for="holiday_date_{{$key}}">Select Date</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" class="form-control flatpicker-input" name="holiday_date[]" placeholder="Select Date" value="{{$formattedDate}}"/>
                            <span class="bx bx-calendar-alt"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-0 mt-lg-4 col-md-6 mb-3">
                        <div class="d-flex gap-2 mt-0 mt-lg-1">
                            <button type="button" class="btn btn-icon btn-delete remove-row"> <i class="bx bx-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
            @else
                <div class="row multiple-row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label class="form-label" for="holiday_name_1">Holiday</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" class="form-control" name="holiday_name[]" required placeholder="Holiday Name" />
                            <span class="bx bx-calendar-alt"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label class="form-label" for="holiday_date_1">Select Date</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" class="form-control flatpicker-input" name="holiday_date[]" placeholder="Select Date" />
                            <span class="bx bx-calendar-alt"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-0 mt-lg-4 col-md-6 mb-3">
                        <div class="d-flex gap-2 mt-0 mt-lg-1">
                            <button type="button" class="btn btn-icon btn-delete remove-row"> <i class="bx bx-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
            @endif
            </div>
                <div class="col-md-12 mb-3">
                    <span class="btn btn-icon btn-secondary" id="addRow"><i class="bx bx-plus"></i></span>
                    <!-- <span class="btn btn-icon btn-danger"><i class="bx bx-trash"></i></span> -->
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Holiday</button>
                            @if(count($holidays ?? []))
                            <button type="button" class="btn btn-delete" id="deleteHoliday">Delete All</button>
                            @endif
                        </div>
                    </div>
                </div>
        </form>
        @stop

    @section("page_script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
   <!-- Intel Input Js-->
   <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>

<script type="text/javascript">
		$(document).ready(function() {
        var rowCounter = "{{ count($holidays ?? []) }}";
        // Function to initialize flatpickr
        function initializeFlatpickr() {
            $(".flatpicker-input").flatpickr({
                dateFormat: "d-m-Y",
                minDate: "today",
            });
        }
        initializeFlatpickr();
        // Function to add a new row
        $('#addRow').on('click', function() {
            rowCounter++;
            var newRow = `
            <div class="row multiple-row">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label class="form-label" for="holiday_name_${rowCounter}">Holiday</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control" name="holiday_name[]" required placeholder="Holiday Name" />
                        <span class="bx bx-calendar-alt"></span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label class="form-label" for="holiday_date_${rowCounter}">Select Date</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input" name="holiday_date[]" placeholder="Select Date" />
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
            
                $(this).closest('.multiple-row').remove();
            
        });

        // Function to clear the form
        $('#clearForm').on('click', function() {
            $('#holidayForm')[0].reset();
            // Remove all rows except the first one
            $('.multiple-row').not(':first').remove();
            // Reset first row's input fields
            $('.multiple-row').find('input').val('');
        });
    });

    $(document).ready(function() {

        $('body').on('click', '#deleteHoliday', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete all holidays?';

            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: "{{route('admin.doctors.holiday_delete', encrypt($doctor->id))}}",
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                // window.location.reload();
                                window.location.href = res.o_data.redirect;
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

        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#holidayForm', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true);
            $form.find('[type="submit"]').text("Saving...");
            var formData = new FormData(this);
            let i = 0;
            App.setJQueryValidationRules('#holidayForm');
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
                            $form.find('[type="submit"]').text("Update Holiday");
                            if (typeof res['errors'] !== 'undefined') {
                                var error_def = $.Deferred();
                                var error_index = 0;
                                jQuery.each(res['errors'], function(e_field, e_message) {
                                    if (e_message != '') {

                                        if(e_message=='The holiday date field has duplicate dates.'){

                                            var m = e_message || 'Unable to save data. Please try again later.';
                                App.alert(m, 'Oops!', 'error');
                                        }
                                        else{
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                        $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                        if (error_index == 0) {
                                            error_def.resolve();
                                        }
                                        error_index++;
                                    }
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
                        } else {
                             App.alert(res['message'] || 'Record saved successfully', 'Success!', 'success');
                             $form.find('[type="submit"]').prop("disabled", false);
            $form.find('[type="submit"]').text("Update Holiday");
                            console.log(res, 'res');
                            // setTimeout(function() {
                               // window.location.href = res['oData']['redirect'];
                            // }, 2500);

                        }

                    },
                    error: function(e) {
                        form_in_progress = 0;
                        App.loading(false);
                        $form.find('[type="submit"]').prop("disabled", false);
                        $form.find('[type="submit"]').text("Update Holiday");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            // });
        });

    });

	</script>
      <script>
    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today",
    });
    
    // $(".flatpicker-input-date-time").flatpickr({
    //     minDate: "today",
    //     enableTime: true,
    //     dateFormat: "d-m-Y H:i"
    // });

    $(".flatpicker-input-date-time").flatpickr({
        minDate: "today",
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        onReady: function(selectedDates, dateStr, instance) {
                // Create OK button
                var okButton = document.createElement("button");
                okButton.innerText = "OK";
                okButton.classList.add("btn", "btn-primary", "ms-2");
                
                // Add click event to OK button
                okButton.addEventListener("click", function() {
                    instance.close();
                });

                // Create Clear button
                var clearButton = document.createElement("button");
                clearButton.innerText = "Clear";
                clearButton.classList.add("btn", "btn-outline-secondary"
                , "waves-effect",  "waves-light");
                
                // Add click event to Clear button
                clearButton.addEventListener("click", function() {
                    instance.clear();
                    // instance.close();
                });

                // Append OK and Clear buttons to flatpickr calendar
                var buttonContainer = document.createElement("div");
                buttonContainer.classList.add("flatpickr-button-container", "d-flex", "justify-content-end", "px-3", "pb-2");
                buttonContainer.appendChild(clearButton);
                buttonContainer.appendChild(okButton);

                instance.calendarContainer.appendChild(buttonContainer);
        }
    });
    
    $(".flatpicker-input-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
    $(".flatpicker-input-multiple").flatpickr({
        dateFormat: "d-m-Y",
        mode: "multiple",
        minDate: "today"
    });
    $(document).ready(function() {
        $('.select2-single').select2({
            placeholder: $(this).data('placeholder'),

        });
        $("#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
    });
    </script>
@stop
