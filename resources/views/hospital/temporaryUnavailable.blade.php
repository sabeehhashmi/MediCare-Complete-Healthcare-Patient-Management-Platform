@include('hospital.layouts.header')
<div class="position-relative mb-5">
    <div class="card">
        <div class="card-body">
            <form id="availabilityForm" method="post" action="{{route('hospital.temporaryUnavailableSave')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor->id}}" name="doctor_id">
            <div class="row">
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label" for="username">Select Date</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control flatpicker-input" id="booking_date_appointment" name="unavailable_date" placeholder="Select Date" />
                        <span class="bx bx-calendar-alt"></span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="col-12 mb-3">
                        <div class="timeslot-selector" id="slots-div">
                        
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="text-center d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a type="button" class="btn btn-delete" href="{{route('hospital.temporaryunavailable', $doctor->id)}}">Clear All</a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@include('hospital.layouts.footer')

<script>
    function checkUnAvailibility(date){
        // $('#appointment-modal-doctor .availiblity').prop('disabled', true);
        // $('#appointment-modal-doctor .availiblity').prop('checked', false);
        // console.log(doctor_id);
        $('#slots-div').hide();
        if(date){
            $.ajax({
                type: "POST",
                url: "{{ url('hospital/check_doctor_unavailability') }}",
                data:{
                    'unavailable_date': date,
                        'doctor_id': '{{$doctor_id}}',
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
    
    function getDrAvailibility(doctor_id, date){
        $('#slots-div').html('<h4>Loading..</h4>');
        // $('#appointment-modal-doctor .availiblity').prop('disabled', true);
        // $('#appointment-modal-doctor .availiblity').prop('checked', false);
        // console.log(doctor_id);
        if(date && doctor_id){
            $.ajax({
                type: "POST",
                url: "{{ url('hospital/check_doctor_availability') }}",
                data:{
                    'booking_date': date,
                        'doctor_user_id': doctor_id,
                    '_token': '{{csrf_token()}}',
                },
                success: function (res) {
                    if (res.oData) {
                        makeSlots(res.oData.list);
                        checkUnAvailibility(date)
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Members:', error);
                }
            });
        }
    }

    function updateSlots(slots) {
        $('.time-slot').each(function() {
            var slot = $(this).val();
            var isAvailable = slots.includes(slot);
            // $(this).prop('disabled', !isAvailable);
            $(this).prop('checked', isAvailable);
        });
        $('#slots-div').show();
    }
    
    function makeSlots(slots) {
        $('#slots-div').html('<h4>Slots are not available</h4>');
        if (slots && slots.length > 0) {
            $('#slots-div').html('');
            $(slots).each(function(index, slot) {
                var slotId = 'date' + (index + 17 + index);
                $('#slots-div').append('<span><input type="checkbox" id="' + slotId + '" name="unavailable_timeslot[]" value="' + slot.slot_text + '" class="idReschedule time-slot checkbx-style" /><label for="' + slotId + '">' + slot.slot_text + '</label></span>');
            });
        }
    }

    $(document).ready(function() {
        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#availabilityForm', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true);
            $form.find('[type="submit"]').text("Saving...");
            var formData = new FormData(this);
            let i = 0;

            App.setJQueryValidationRules('#availabilityForm');
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
                        $form.find('[type="submit"]').text("Save");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            // });
        });

        $('#booking_date_appointment').on("change", function () {
            // checkUnAvailibility("{{$doctor_id}}", $('#booking_date_appointment').val());
            getDrAvailibility("{{$doctor->user_id ?? null}}", $('#booking_date_appointment').val());
        });
    });
    
</script>