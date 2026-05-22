@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content">
                    <div class="container-fluid">

                        <div class="position-relative mb-5">
                            <div class="d-lg-flex">
                                @include('web.profile-sidebar')
                                <!-- end chat-leftsidebar -->
                        
                                <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
                                    <div class="card">
                                        <div class="p-4 pt-5">
                                            <form action="{{url('/website/patient-profile-save')}}" class="row" method="POST" id="update-profile-form">
                                                @csrf
                                                <input type="hidden" name="otp" id="otp">
                                                <div class="mb-3 mb-md-4">
                                                    <div class="avtar-placeholder text-center">
                                                        <img src="{{$user->user_image ? $user->user_img_url : 'https://static.vecteezy.com/system/resources/thumbnails/002/534/006/small/social-media-chatting-online-blank-profile-picture-head-and-body-icon-people-standing-icon-grey-background-free-vector.jpg'}} " alt="" class="img-place mx-auto rounded" id="imagePreview">
                                                        <div class="-mt-5">
                                                            <label for="imageUpload" class="form-label">
                                                                <span class="btn btn-primary px-2">
                                                                    <img class="icn" src="{{URL::asset('web')}}/images/camera-icn-058903.svg" alt="">
                                                                </span>
                                                            </label>
                                                            <input class="form-control position-absolute opacity-0" name="image" type="file" id="imageUpload">
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control ps-3" name="first_name" value="{{$user->first_name}}" id="first_name" placeholder="First Name">
                                                        <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control ps-3" name="last_name" value="{{$user->last_name}}" id="last_name" placeholder="Last Name">
                                                        <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
        
        
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <!-- <label class="form-label" for="">Gender </label> -->
                                                    <div class="position-relative">
                                                        <select name="gender" class="select2-single no-icon" data-placeholder="Gender">
                                                            <option></option>
                                                            <option {{$user->gender == 1 ? 'selected' : ''}} value="1">Male</option>
                                                            <option {{$user->gender == 2 ? 'selected' : ''}} value="2">Female</option>
                                                            <option {{$user->gender == 3 ? 'selected' : ''}} value="3">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <div class="position-relative">
                                                        @php
                                                        $foramted_dob = ''; 
                                                        if($user->dob){
                                                            $foramted_dob = $user->dob = \Carbon\Carbon::createFromFormat('Y-m-d', $user->dob)->format('d-m-Y');
                                                        }
                                                        @endphp
                                                        <input type="text" class="form-control ps-3 flatpicker-input-past" id="dob" name="dob" value="{{$foramted_dob ?? null}}" placeholder="Date Of Birth" />
                                                        <!-- <span class="custom-icon calendar-doc-icn"></span> -->
                                                    </div>
                                                </div>
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control ps-3" id="email" name="email" value="{{$user->email}}" @if($user->is_social == 1) readonly @endif placeholder="Email Address">
                                                        <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
        
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative">
                                                    <input type="hidden" id="dial_code" name="dial_code" value="{{$user->dial_code}}">
                                                    <input type="text" class="form-control no-zero-input" id="phone" name="phone" value="{{$user->phone}}" placeholder="Enter Phone Number" required="true"/>
                                                        <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
        
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <div class="form-check py-1 mb-2">
                                                        <input type="checkbox" class="form-check-input" id="ht-remember-check" @if($user->phone == $user->whatsap_phone) checked @endif >
                                                        <label class="form-check-label ms-2" for="ht-remember-check">Same as WhatsApp Number</label>
                                                    </div>
                                                    <!-- <label class="form-label" for="username">Username</label> -->
                                                    <div class="position-relative input-custom-icon">
                                                    <input type="hidden" id="whatsApp_dial_code" name="whatsap_dial_code" value="{{$user->whatsap_dial_code}}">
                                                    <input type="text" class="form-control no-zero-input" id="whatsApp" name="whatsap_phone" value="{{$user->whatsap_phone}}" placeholder="Enter WhatsApp Number" />
                                                        <!-- <span class="bx bx-mobile-alt"></span> -->
                                                    </div>
                                                </div>
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <label class="form-label" for="">My Insurance Network </label>
                                                    <div class="position-relative">
                                                        <select name="insurence_id" id="insurence_id" class="select2-single no-icon" data-placeholder="My Insurance Network">
                                                        <option value="">My Insurance Network</option>
                                                        @foreach($insurencePolicies as $id => $value)
                                                        <option data-count="{{$value->sub_insurence_policy_count??0}}" {{($value->id == $user->insurence_id)?'selected':''}} value="{{$value->id}}">{{$value->title}}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mb-md-4 col-md-6">
                                                    <label class="form-label" for="">My Sub Insurance Network </label>
                                                    <div class="position-relative">
                                                        <select name="sub_insurence_id" id="sub_insurence_id" class="sub-insurance-policy select2-single no-icon" data-placeholder="My Sub Insurance Network">
                                                            <option @if(!empty($sub_insurence) && $sub_insurence->count() > 0) required @endif value="">Sub Inusurence Network</option>
                                                            @foreach($sub_insurence as $sub)
                                                                <option @if($sub->id == $user->sub_insurence_id) selected @endif value="{{$sub->id}}">{{$sub->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
        
        
                                                <div class="mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                    <!-- end user chat -->

                            </div>
                    <!-- container-fluid -->
                    </div>
            <!-- </div> -->
            <!-- end main content-->

            <!-- Add New Event MODAL -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content bg-prime-light">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Verify OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="verify-otp-form" class="custom-form">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit1-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit2-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit3-input" name="otp[]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" id="digit4-input" name="otp[]">
                                </div>
                            </div>
                        </div>
                        <p class="text-body">To verify your mobile number, please enter the OTP that is sent to your mobile device.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary fw-bold" style="width: 120px;" id="resend-otp">Resend</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal-->
@endsection

@section('custom_js')
    <script>
        $('#insurence_id').change(function(){
            let incuranceId = $(this).val();
            let selected_id = '{{$user->sub_insurence_id}}';
            var selectedAttrValue = $('#insurence_id option:selected').attr('data-count');
            $('#sub_insurence_id').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                    success: function (res) {
                        if (res) {
                            $('#sub_insurence_id').html('<option value="">My Insurance Network</option>');
                            $.each(res, function (index, data) {
                                $('#sub_insurence_id').append('<option '+ (selected_id == data.id ? 'selected' : '') +' value="' + data.id+'">' + data.title + '</option>');
                            });
                            if(selectedAttrValue > 0){
                                $('#sub_insurence_id').attr('required', 'required');
                            }else{
                                $('#sub_insurence_id').attr('required', '');
                            }
                            $('#sub_insurence_id').select2()
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
        });
        $(document).ready(function() {
            $('.no-zero-input').on('keydown', function(event) {
                    if($(this).val().length == 0){
                        if (event.key === '0') {
                            event.preventDefault();
                        }
                    }
                });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                        // $('#previewLink').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });

            // $(".flatpicker-input").flatpickr({
            //     dateFormat: "d-m-Y",
            //     // maxDate: "today",
            // });
            // Initialize intlTelInput for phone input
            var inputTel = document.querySelector("#phone");
            var ph = window.intlTelInput(inputTel, {
             //   initialCountry: '{{INIT_PHONE_C_CODE}}',
                separateDialCode: true,
                geoIpLookup: "auto",
             //   onlyCountries: ['ae'], // Restrict to UAE only
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
            });

            inputTel.addEventListener("input", function () {
                var dialCode = ph.getSelectedCountryData().dialCode;
                $('#dial_code').val(dialCode);
            });

            // Initialize intlTelInput for WhatsApp input
            var whatsAppInputTel = document.querySelector("#whatsApp");
            var wa = window.intlTelInput(whatsAppInputTel, {
            //    initialCountry: '{{INIT_PHONE_C_CODE}}',
                separateDialCode: true,
                geoIpLookup: "auto",
             //   onlyCountries: ['ae'], // Restrict to UAE only
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
            });

            whatsAppInputTel.addEventListener("input", function () {
                var dialCode = wa.getSelectedCountryData().dialCode;
                $('#whatsApp_dial_code').val(dialCode);
            });

            

            // Handle "autofill whatsapp" checkbox change
            $('#ht-remember-check').on('change', function () {
                if ($(this).prop('checked')) {
                    console.log(ph.getSelectedCountryData());
                    var dialCode = ph.getSelectedCountryData().dialCode;
                    var phoneNumber = inputTel.value;
                    $('#whatsApp_dial_code').val(dialCode);
                    $('#whatsApp').val(phoneNumber);
                    wa.setNumber(phoneNumber);
                } else {
                    $('#whatsApp_dial_code').val('');
                    $('#whatsApp').val('');
                    wa.setNumber('');
                }
            });
        });

        function submitProfileFOrm(form){
            var $form = $(form);
            var formData = new FormData(form);
            $form.find('button[type="submit"]').text('Processing..').attr('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $form.find('button[type="submit"]').text('Update').attr('disabled', false);
                    // console.log(JSON.parse(response));
                    if(response.success == "1"){
                        App.alert(response.message || 'Profile Updated successfully', 'Success!','success');
                        setTimeout(function () {
                            window.location.href = "{{url('/website/patient-profile')}}";
                        }, 1500);
                    }else if(response.success == '3'){
                        $('#event-modal').modal('show');
                    }else{
                        App.alert(response.message || 'Failed to Update Profile', 'Fail!','error');
                        if(response.errors){
                            jQuery.each(response.errors, function (e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                }
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $form.find('button[type="submit"]').text('Update').attr('disabled', false);
                    App.alert('Something Went wrong', 'Fail!','error');
                }
            });
        }

        // Handle signup form submission
        $('#update-profile-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            submitProfileFOrm(this);
        });

        // Handle OTP verification
        $('#verify-otp-form').on('submit', function (event) {
            event.preventDefault();
            var otp = $('input[name="otp[]"]').map(function () {
                return $(this).val();
            }).get().join('');
            $('#otp').val(otp);
            $('#event-modal').modal('hide');
            submitProfileFOrm($('#update-profile-form')[0]);
        });

        $(document).ready(function() {
            // getLocation();
        });
        
        // function lodSubIncurance(incuranceId){
        //     if (incuranceId) {
        //         $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
        //         $.ajax({
        //             type: "GET",
        //             url: "{{ url('get-sub-insurance') }}/" + incuranceId,
        //             success: function (res) {
        //                 if (res) {
        //                     $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
        //                     $.each(res, function (index, data) {
        //                         $('#sub-insurance-policy').append('<option value="' + data.id+'">' + data.title + '</option>');
        //                     });
        //                     // $('#sub-insurance-policy').val(selectedId).trigger('change');
        //                     $('#sub-insurance-policy').select2(); // Reinitialize select2
        //                 }
        //             },
        //             error: function (xhr, status, error) {
        //                 console.error('Error fetching Members:', error);
        //             }
        //         });
        //     }else {
        //         $('#sub-insurance-policy').empty();
        //         $('#sub-insurance-policy').append('<option value=""></option>');
        //     }
        // }

        // $('#insurance-policy').on('change', function(){
        //     lodSubIncurance($(this).val());
        // })
        
    </script>
@endsection