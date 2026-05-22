@include('agent.layouts.header')
<div class="position-relative mb-5">
        <div class="card">
            <h5 class="card-header">
                DR {{$doctor->user->name}}
            </h5>
            <div class="card-body">
            <form id="availabilityForm" method="post" action="{{route('agent.availability_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" name="doctor_id" value="{{$doctor->id}}">
                    <ul class="availability-list p-0">
                    @foreach($days as $shortDay => $fullDay)
                        @php
                            $dayAvailability = strtolower($fullDay) . '_availability';
                            $dayTimeSlot = strtolower($fullDay) . '_time_slot';
                        @endphp
                        <li>
                            <div class="toggle-availability">
                                <div class="toggle">
                                    <input class="day-toggle" type="checkbox" value="{{ $$dayAvailability }}" id="{{ $shortDay }}" name="{{ strtolower($fullDay) }}_availability" value="1" {{ $$dayAvailability ? 'checked' : '' }} />
                                    <label for="{{ $shortDay }}">{{ $shortDay }}</label>
                                </div>
                                <h5>{{ $fullDay }}</h5>
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                    <span>
                                        <input type="checkbox" id="{{ strtolower($shortDay) }}{{ $i+17+$i }}" {{ in_array($time_slot[$i], $$dayTimeSlot) ? 'checked' : '' }} name="booking_time_slot[{{ strtolower($shortDay) }}][]" value="{{ $time_slot[$i] }}" class="idReschedule time-slot checkbx-style {{ $shortDay }}" />
                                        <label for="{{ strtolower($shortDay) }}{{ $i+17+$i }}">{{ $time_slot[$i] }}</label>
                                    </span>
                                @endfor
                            </div>
                        </li>
                    @endforeach
                    </ul>
                    <div class="mt-3 mt-md-0 mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Update Availability</button>
                    </div>
                    <!-- end row -->
            </form>
            </div>
        </div>
    </div>
@include('agent.layouts.footer')
<script>
    $(document).ready(function() {
        function toggleTimeSlots() {
            $('.day-toggle').each(function() {
                var day = $(this).attr('id');
                var isChecked = $(this).is(':checked');
                $(this).val(isChecked ? 1 : 0);
                $('.' + day).prop('disabled', !isChecked);
                if(!isChecked){
                    $('.' + day).prop('checked', false);
                }
            });
        }

        // Initial call to set the correct state on page load
        toggleTimeSlots();

        // Attach event listener to day availability checkboxes
        $('.day-toggle').on('change', function() {
            toggleTimeSlots();
        });

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
                            $form.find('[type="submit"]').text("Update Availability");
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