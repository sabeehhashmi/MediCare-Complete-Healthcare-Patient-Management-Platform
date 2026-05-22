@include('callcenter.layouts.header')
<div class="position-relative">
    <div class="card">
        <div class="card-body">
        <form id="holidayForm" method="post" action="{{route('callcenter.holiday_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor->id}}" name="doctor_id">
            <div class="row align-content-end">
            @if(count($doctor->doctorHolidays ?? []))
            @foreach($doctor->doctorHolidays as $key => $holiday)
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
                <div class="row">
                    <div class="col--12 text-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Holiday</button>
                            @if(count($doctor->doctorHolidays ?? []))
                                <button type="button" class="btn btn-delete" id="deleteHoliday">Delete All</button>
                            @endif
                        </div>
                    </div>
                </div>
        </form>

        </div>
    </div>
</div>
@include('callcenter.layouts.footer')
<script>
    
    $(document).ready(function() {

        $('body').on('click', '#deleteHoliday', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete all holidays?';

            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: "{{route('callcenter.holiday_delete', encrypt($doctor->id))}}",
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
        var rowCounter = "{{ count($doctor->doctorHolidays ?? []) }}";
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
            if ($('.multiple-row').length > 1) {
                $(this).closest('.multiple-row').remove();
            }
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
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
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
                        $form.find('[type="submit"]').text("Update Holiday");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            // });
        });

    });
    
</script>