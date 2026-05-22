@include('doctor.template.header')
<form id="doct_appointment_form" method="post" action="{{route('doctorlogin.availability_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$doctor_id}}" name="doctor_id">
<div class="position-relative mb-5">
        <div class="card">
            <div class="card-body">
                
                    <ul class="availability-list p-0">
                        <li>
                            <div class="toggle-availability">
                            <div class="toggle"><input type="checkbox" name="sunday_availability" value="1" id="Sun" 
                                @if($sunday_availability == 1)
                                      checked 
                                     @endif 
                                /><label for="Sun">Sun</label></div>

                                <h5>Sunday</h5>
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="sunday_time_slot[]" value="{{$time_slot[$i]}}" id="sun{{$i}}" class="time-slot" 
                                        @if(is_array($sunday_time_slot) && in_array($time_slot[$i], $sunday_time_slot))
                                        checked 
                                        @endif />
                                        <label for="sun{{$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor



                


                            </div>
                        </li>

                        <li>

                            <div class="toggle-availability">
                            <div class="toggle"><input type="checkbox" name="monday_availability" value="1" id="Mon"  
                                @if($monday_availability == 1)
                                      checked 
                                     @endif 
                                /><label for="Mon">Sun</label></div>

                                <h5>Monday</h5>
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="monday_time_slot[]" value="{{$time_slot[$i]}}" id="mon{{$i+9+$i}}" class="time-slot" 
                                        @if(is_array($monday_time_slot) && in_array($time_slot[$i], $monday_time_slot))
                                        checked 
                                        @endif />
                                    
                                        <label for="mon{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor

                            </div>
                        </li>

                        <li>
                            <div class="toggle-availability">
                            <div class="toggle"><input type="checkbox" name="tuesday_availability" value="1" id="Tue"  
                                @if($tuesday_availability == 1)
                                      checked 
                                     @endif 
                                /><label for="Tue">Tue</label></div>

                                <h5>Tuesday</h5> 
                            </div>
                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="tuesday_time_slot[]" value="{{$time_slot[$i]}}" id="tue{{$i+9+$i}}" class="time-slot" 
                                        @if(is_array($tuesday_time_slot) && in_array($time_slot[$i], $tuesday_time_slot))
                                        checked 
                                        @endif 
                                        />
                                        <label for="tue{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor


                            </div>
                        </li>

                        <li>

                            <div class="toggle-availability">
                            <div class="toggle"><input type="checkbox" name="wednesday_availability" value="1" id="Wed" 
                                @if($wednesday_availability == 1)
                                      checked 
                                     @endif 
                                /><label for="Wed">Wed</label></div>

                                <h5>Wednesday</h5> 
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="wednesday_time_slot[]" value="{{$time_slot[$i]}}" id="wed{{$i+9+$i}}" class="time-slot" 
                                        @if(is_array($wednesday_time_slot) && in_array($time_slot[$i], $wednesday_time_slot))
                                        checked 
                                        @endif 
                                    
                                        />
                                        <label for="wed{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor

                            </div>
                        </li>

                        <li>

                            <div class="toggle-availability">
                              <div class="toggle"><input type="checkbox" name="thursday_availability" value="1" id="Thu"  
                                @if($thursday_availability == 1)
                                      checked 
                                     @endif 
                                /><label for="Thu">Thu</label></div>

                                <h5>Thursday</h5> 
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="thursday_time_slot[]" value="{{$time_slot[$i]}}" id="thu{{$i+9+$i}}" class="time-slot" 
                                        @if(is_array($thursday_time_slot) && in_array($time_slot[$i], $thursday_time_slot))
                                        checked 
                                        @endif 
                                        />
                                        <label for="thu{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor

                            </div>
                        </li>

                        <li>

                            <div class="toggle-availability">
                                <div class="toggle"><input type="checkbox" name="friday_availability" value="1" id="Fri" 
                                    @if($friday_availability == 1)
                                        checked 
                                        @endif 
                                    /><label for="Fri">Fri</label></div>

                                <h5>Friday</h5> 
                            </div>

                            <div class="timeslot-selector">
                                @for($i = 0; $i < count($time_slot); $i++)
                                <span>
                                        <input type="checkbox" name="friday_time_slot[]" value="{{$time_slot[$i]}}" id="fri{{$i+9+$i}}" class="time-slot" 
                                        @if(is_array($friday_time_slot) && in_array($time_slot[$i], $friday_time_slot))
                                        checked 
                                        @endif 
                                        />
                                        <label for="fri{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                    </span>
                                @endfor

                            </div>
                        </li>

                        <li>

                            <div class="toggle-availability">
                                <div class="toggle"><input type="checkbox" name="saturday_availability" value="1" id="Sat"  
                                    @if($saturday_availability == 1)
                                        checked 
                                        @endif 
                                    /><label for="Sat">Sat</label></div>

                                <h5>Saturday</h5> 
                            </div>

                            <div class="timeslot-selector">
                            @for($i = 0; $i < count($time_slot); $i++)
                            <span>
                                    <input type="checkbox" name="saturday_time_slot[]" value="{{$time_slot[$i]}}" id="sat{{$i+9+$i}}" class="time-slot"
                                    @if(is_array($saturday_time_slot) && in_array($time_slot[$i], $saturday_time_slot))
                                      checked 
                                     @endif
                                    />
                                    <label for="sat{{$i+9+$i}}">{{$time_slot[$i]}}</label>
                                </span>
                            @endfor

                            </div>
                        </li>
                        
                    </ul>
                    <div class="mt-3 mt-md-0 mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Update Availability</button>
                    </div>
                    <!-- end row -->
                
            </div>
        </div>
    </div>
</form>
@include('doctor.template.footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#doct_appointment_form').on('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission

            // Serialize form data
            var formData = $(this).serialize();

            // Submit form data via Ajax
            $.ajax({
                type: "POST",
                url: $(this).attr('action'), // Form action URL
                data: formData,
                dataType: "json", // Expected data type from server
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    if (response.status == 1) {
                        // Show success message (you can use SweetAlert2 for this)
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Redirect to the specified URL if provided
                            location.reload();
                        });

                        // Redirect to the availability page
                        
                    } else {
                        // Show error message (you can use SweetAlert2 for this)
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                    alert("An error occurred while processing your request.");
                }
            });
        });
    });
</script>