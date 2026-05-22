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
    <form id="admin_form" method="post" action="{{route('admin.doctors.availability_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor_id}}" name="doctor_id">
    <div class="position-relative mb-5">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 mb-5">
                    @php
                    $hospital_id=isset($_GET['hospital_id'])?'?hospital_id='.$_GET['hospital_id']:'';
                    $clinic_id=isset($_GET['clinic_id'])?'?clinic_id='.$_GET['clinic_id']:'';
                    @endphp
                    <a  href=" {{route('admin.doctors.index')}}{{$hospital_id}}{{$clinic_id}}"  class="btn btn-primary float-end">Back</a>
                </div>
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
                    <!-- <div class="mt-3 mt-md-0 mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Update Availability</button>
                    </div> -->
                    <div class="col-12 d-flex">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">Update Availability</button>
                        <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                    </div>
                    <!-- end row -->
                
            </div>
        </div>
    </div>
   </form>
    @stop

    @section("page_script")
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>


    <script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            App.alert("{{ session('success') }}", 'Success!','success');
        });
    </script>
@endif
    <script>

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
    $(document).ready(function() {

        // Initial call to set the correct state on page load
        toggleTimeSlots();
        });

        // Attach event listener to day availability checkboxes
        $('.day-toggle').on('change', function() {
            toggleTimeSlots();
        });
    </script>
    @stop