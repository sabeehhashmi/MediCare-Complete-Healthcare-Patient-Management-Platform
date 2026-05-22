@include('doctor.template.header')


<div class="position-relative mb-5">
    <div class="d-lg-flex">
        <div class="chat-leftsidebar card">
            <div class="card-body" style="flex: 0;">
                <div class="text-center bg-light rounded px-4 py-3">
                    <div class="chat-user-status mt-4">
                        <!-- <img src="assets/images/doctor.png" class="avatar-md rounded-circle" alt="" /> -->
                        <div class="avatar-upload position-relative">
                            <div class="avatar-edit">
                                <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                            <img src="{{$doctor->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                            </div>
                        </div>
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="{{ url('doctor/get_profile') }}" class="text-reset">DR {{$doctor->user->first_name ?? null}} {{$doctor->user->last_name ?? null}} </a></h5>
                    <p class="text-muted mb-0"> 
                    {{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}
                    </p>
                    <p class="text-muted mb-0">{{ $hospital->name_en ?? '' }}</p>
                </div>
            </div>

            <div class="mail-list">
                <a href="{{ url('doctor/get_profile') }}" class=" ">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-user-circle font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">My Profile</h5>
                        </div>
                    </div>
                </a>
                <a href="{{ url('doctor/edit_profile') }}" class="border-bottom active bg-primary-subtle">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-star-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Edit Profile</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>

                <a href="{{ url('doctor/change_password') }}" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Change Password</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-5">
                    <form id="doctor_form" action="{{route('doctor.save_profile')}}" method="POST" enctype="multipart/form-data">
                       
                    @csrf
                    <input type="hidden" value="{{$id}}" name="id">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Name of Hospital/Clinic Name/ Dental Care/ Home Care</label>
                                
                                <div class="position-relative select-custom-icon">
                                    <select name="hospital_id" id="hospital_id" class="select2-single" data-placeholder="Select Hospital/Clinic Name/ Dental Care/ Home Care">
                                        <option></option>
                                        @foreach($hospital_name as $hospital)
                                            <option  value="{{$hospital->id}}" data-type="{{$hospital->type}}" {{($hospital->id==$hospital_id)?'selected':''}}>{{$hospital->name_en}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx bx-buildings"></i>
                                </div>
                            </div>
                            
                            

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">First Name</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{$first_name}}" placeholder="Enter First Name" />
                                    <span class="bx bx-user"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Last Name</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{$last_name}}" placeholder="Enter Last Name" />
                                    <span class="bx bx-user"></span>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Qualification </label>
                                <div class="position-relative select-custom-icon">
                                    <select name="qualification[]" id="qualification" class="select2-single" multiple data-placeholder="Select Qualification">
                                        @foreach($qualification as $qualification)
                                            @php
                                                $selected = is_array($qualification_id) && in_array($qualification->id, $qualification_id) ? 'selected' : '' ;
                                            @endphp
                                            <option value="{{ $qualification->id }}" {{ $selected}}>{{ $qualification->title }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fi fi-rr-graduation-cap" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Specialty</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="specialty[]" id="specialty" class="select2-single" multiple data-placeholder="Select Specialty">
                                    @foreach($specialty as $specialty)                         
                                        @php
                                        $selected = is_array($speciality_id) && in_array($specialty->id, $speciality_id) ? 'selected' : '' ;
                                        @endphp
                                        <option value="{{ $specialty->id }}" {{$selected }}>{{ $specialty->name_en }}</option>           
                                    @endforeach
                                    </select>
                                    <i class="fi fi-rr-doctor" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Special Interest</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="special_interest[]" id="special_interest" class="select2-single" multiple data-placeholder="Select Special Interest">
                                    @foreach($special_interest as $special_interest)                 
                                        @php
                                            $selected = is_array($special_intrest_id) && in_array($special_interest->id, $special_intrest_id) ? 'selected' : '' ;
                                        @endphp
                                        <option value="{{ $special_interest->id }}" {{ $selected }}>{{ $special_interest->title }}</option>
                                   @endforeach
                                    </select>
                                    <i class="fi fi-rr-syringe" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Years of Experiences</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="experiences" name="experiences" value="{{$experiences}}" placeholder="Enter Years of Experiences" />
                                    <span class="fi fi-rr-stars"></span>
                                </div>
                            </div>
                           
                           
                          
                            <!-- <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">License Type</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="license_type[]" id="license_type" class="select2-single" multiple data-placeholder="Select License Type">
                                    @foreach($license_type as $license_type)                 
                                        @php
                                            $selected = is_array($license_type_id) && in_array($license_type->id, $license_type_id) ? 'selected' : ''  ;
                                        @endphp
                                    @endforeach

                                <option value="{{ $license_type->id }}" {{ $selected}}>{{ $license_type->title }}</option>
                                    </select>
                                    <i class="fi-rr-angle-small-down" style="margin-top: 2px;"></i>
                                </div>
                            </div> -->

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Country of Orgin</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="country" id="country" class="select2-single" data-placeholder="Select Country">
                                        @foreach($country_list as $country)
                                            <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Language Spoken</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="language_spoken_id[]" id="language_spoken_id" class="select2-single" multiple data-placeholder="Select Language Spoken">
                                        @foreach($language_spoken as $language_spoken)
                                            @php
                                                $selected = is_array($language_spoken_id) && in_array($language_spoken->id, $language_spoken_id) ? 'selected' : ''  ;
                                            @endphp
                                            <option value="{{ $language_spoken->id }}" {{ $selected}}>{{ $language_spoken->title }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fi fi-rs-square-a" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Gender</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="gender" id="gender" class="select2-single" data-placeholder="Select Gender">
                                        <option value="1" {{ $gender == 1 ? 'selected' : '' }}>Male</option>
                                        <option value="2" {{ $gender == 2 ? 'selected' : '' }}>Female</option>
                                        <option value="3" {{ $gender == 3 ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <i class="fi fi-rr-venus-mars" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Clinic Direct Number to book an appointment</label>
                                <div class="position-relative">
                                <input type="text" class="form-control" id="phone" name="phone" value="{{$phone}}" placeholder="Enter Phone Number" />
                                 <input type="hidden" id="dial_code" name="dial_code" value="{{$dial_code}}" >
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Doctor Direct Number to book an appointment</label>
                                <div class="position-relative">
                                <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{$appointment_dial_code}}">
                                    <input type="text" class="form-control" id="phone1" name="direct_phone" value="{{$appointment_phone}}" placeholder="Enter Phone Number" />
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="">Email</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="email" id="email" name="email" class="form-control" value="{{$email}}" placeholder="Enter Email" />
                                    <span class="bx bx-envelope"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Profile <small class="text-muted">- maximum 250 words</small></label>
                                    <textarea class="form-control" id="ckeditor-classic" name="profile_bio">{{$profile_bio}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Signature</label>
                        <input class="form-control jqv-input" id="signature" type="file" name="signature" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($user_signature_url ?? null)
                        <a id="previewLink" href="{{$user_signature_url}}" target="_blank">View Signature</a>
                        @endif
                    </div>
                </div>

                            
                            
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end user chat -->
        </div>
        <!-- End d-lg-flex  -->
    </div>
</div>
@include('doctor.template.footer')
<script src="{{ asset('') }}doctor/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}doctor/assets/js/form-editor.init.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyCtSAR45TFgZjOs4nBFFZnII-6mMHLfSYI"></script>
<script src="{{ asset('') }}doctor/assets/js/gmaps.min.js"></script>
<script src="{{ asset('') }}doctor/assets/js/gmaps.init.js"></script>
<script>
    $("#base-style").DataTable();
</script>
<script>

    function loadDepartments(hospital_id, selectedEmirateId = ''){
        if (hospital_id) {
            // document.getElementById('new_hospital_id').value = hospital_id;
            $('#departments').html('<option value="">Loading..</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('doctor/get-hospital-departments') }}/" + hospital_id,
                success: function (res) {
                    if (res) {
                        // $('#departments').empty();
                        $('#departments').html('');
                        $.each(res, function (index, department) {
                            $('#departments').append('<option value="' + department.id+'">' + department.title + '</option>');
                        });
                        $('#departments').val(selectedEmirateId).trigger('change');
                        $('#departments').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching departments:', error);
                }
            });
        } else {
            // $('#emirate_id').empty();
            $('#departments').html('<option value="">Select Departments</option>');
        }
    }
    
    $(document).ready(function() {
        let selectedOption = $('#hospital_id').find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
            // loadDepartments($('#hospital_id').val())
        }else{
            $('#departments').html('<option value=""></option>');
            $('#department-field').hide();
        }

        $('#doctor_form').on('submit', function(e) {
    e.preventDefault();

    var $form = $(this);
    $form.find('[type="submit"]').prop("disabled", true).text("Saving...");

    // ✅ USE THIS (IMPORTANT)
    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: $form.attr('action'),
        data: formData,

        processData: false, // 🔥 REQUIRED
        contentType: false, // 🔥 REQUIRED

        dataType: "json",

        success: function(res) {

            if (res['status'] == 0) {

                $form.find('[type="submit"]').prop("disabled", false).text("Submit");

                if (typeof res['errors'] !== 'undefined') {

                    var error_def = $.Deferred();
                    var error_index = 0;

                    jQuery.each(res['errors'], function(e_field, e_message) {
                        if (e_message != '') {
                            $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                            $('<div class="error">' + e_message + '</div>')
                                .insertAfter($('[name="' + e_field + '"]').eq(0));

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
                    App.alert(res['message'] || 'Something went wrong', 'Oops!', 'error');
                }

            } else {

                setTimeout(function() {
                    window.location.href = res['oData']['redirect'];
                }, 1500);
            }
        },

        error: function(xhr) {

            $form.find('[type="submit"]').prop("disabled", false).text("Submit");

            console.log(xhr.responseText);
            App.alert("Network error please try again", 'Oops!', 'error');
        }
    });
});

       });

    $('#hospital_id').on("change", function () {
        let selectedOption = $(this).find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
            loadDepartments($(this).val())
            $('#h-name-holder').text("hospital");
        }else{
            $('#departments').html('<option value=""></option>');
            $('#department-field').hide();
            $('#h-name-holder').text("clinic");
        }
    });

</script>
<script>
    //  const input = document.querySelector("#phone");
    //     const ph=window.intlTelInput(input, {
            
    //         initialCountry: '{{INIT_PHONE_C_CODE}}',
    //     //    onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
    //         //strictMode: true,
    //         geoIpLookup:"auto",
    //         separateDialCode: true,
           
    //         utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    //     });
    //     input.addEventListener("input", function () {
    //         // Get the selected country's dial code
    //         var dialCode = ph.getSelectedCountryData().dialCode;
    //         $('#dial_code').val(dialCode);

    //         // If you want to use the dial code somewhere else, you can do so here
    //     });
    // Set initial values
    // input.value = "{{ $phone }}"; // Set initial phone number

    document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone");

const ph = window.intlTelInput(input, {
  //  initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDialCode() {
    var dialCode = ph.getSelectedCountryData().dialCode;
    document.getElementById('dial_code').value = dialCode;
}

input.addEventListener("input", updateDialCode);
input.addEventListener("countrychange", updateDialCode);

// 🔹 Set country in EDIT case
var existingDialCode = "{{ Auth::user()->dial_code }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});


       
        


    const input1 = document.querySelector("#phone1");
        const iti = window.intlTelInput(input1, {
            
       //     initialCountry: '{{INIT_PHONE_C_CODE}}',
        //    onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
            //strictMode: true,
            geoIpLookup:"auto",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input1.addEventListener("input", function () {
            // Get the selected country's dial code
            var dialCode = iti.getSelectedCountryData().dialCode;
            $('#direct_dial_code').val(dialCode);

            // If you want to use the dial code somewhere else, you can do so here
        });
    // const input1 = document.querySelector("#phone1");
    // const dialCodeInput1 = document.querySelector("#direct_dial_code");
    
    // // Initialize intlTelInput with initial country set based on dial_code
    // const ph1= window.intlTelInput(input1, {
    //     initialCountry: "auto", // Set country automatically based on dial_code
    //     // geoIpLookup: function(callback) {
    //     //     const countryCode = dialCodeInput1.value; // Remove the '+' from dial_code
            
    //     //     callback(countryCode);
    //     // },
    //     initialCountry: '{{INIT_PHONE_C_CODE}}',
    //     onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
    //     separateDialCode: true,
    //     utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/js/utils.js",
    // });

    // // Set initial values
    // input1.value = "{{ $appointment_phone }}";
       

    $("#base-style").DataTable();
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                $("#imagePreview").hide();
                $("#imagePreview").fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    // $("#imageUpload").change(function () {
    //     readURL(this);
    // });
    
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            var formData = new FormData();
            var file = $('#imageUpload')[0].files[0];
            formData.append('image', file);
            formData.append('user_id', "{{$doctor->user_id}}");
            formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

            $.ajax({
                url: '{{ route("doctor.save_profile_image") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Reload the page if the image is successfully uploaded
                        location.reload();
                    } else {
                        // Handle the error
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error
                    alert('An error occurred while uploading the image.');
                }
            });
        });
        var fileArr = [];
        $('#hospital_form').on('submit', function(e) {
            // alert();
            e.preventDefault(); // Prevent normal form submission
            
            var formData = new FormData(this); // Serialize form data
            let i = 0;
                $.each(fileArr, function (k, v) {
                    formData.append('images['+i+']', v);
                    i++;
                });
                
            
            
            // Submit form data via Ajax
            $.ajax({
                type: "POST",
                url: $(this).attr('action'), // Form action URL
                data: formData,
                contentType: false, // Tell jQuery not to set content type
                processData: false,
                dataType: "json", // Expected data type from server
                success: function(response) {
                    
                    // Handle success response
                    console.log(response);
                    
                    // Check if redirect URL is provided in response
                    if (response.oData && response.oData.redirect) {
                        // Redirect to the specified URL
                        window.location.href = response.oData.redirect;
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });


        $("#upload_imgs").change(function(){
            // check if fileArr length is greater than 0
            if (fileArr.length > 0) fileArr = [];
            
                // $('#image_preview').html("");
                var total_file = document.getElementById("upload_imgs").files;
                // console.log(total_file.length, parseInt($('.img-div').length));
                if(total_file.length + parseInt($('.img-div').length) > 3){
                    var m = 'Maximum 3 images are allowed';
                                App.alert(m, 'Exceed maximum!','error');
                                $(this).val('');
                                return
                }
                if (!total_file.length) return;
                for (var i = 0; i < total_file.length; i++) {
                if (total_file[i].size > 1048576) {
                    return false;
                } else {
                    fileArr.push(total_file[i]);
                    
                    $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'></div></div>");
                }
                }
        });
        var existingImages = @json($hospital->images ?? null); // Convert PHP array to JSON format
        
        function previewExistingImages() {
            if(existingImages){
                $('#image_preview').html("");
                for (var i = 0; i < existingImages.length; i++) {
                    $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='existing-img-div" + existingImages[i].image_url + "'><img src='" + existingImages[i].image_url + "' class='img-fluid w-100' title='Existing Image " + (i + 1) + "'><div class='middle'><button id='action-icon' value='existing-img-div" + i + "' class='btn btn-danger btn-icon' role='existing_image'><i class='fa fa-trash'></i></button></div></div>");
                }
            }
        }

                // previewExistingImages();
        
        
        $('.action-icon-deleteImg').on('click', function(evt){
            evt.preventDefault();
            var file_id = $(this).data('id');
            $(this).parents('.img-div').remove();
            
            var remove_ids = $('#remove-images').val();
            if (remove_ids) {
                // Append the new ID to the existing list of IDs
                remove_ids += ',' + file_id;
            } else {
                // If the hidden input is empty, set it to the new ID
                remove_ids = file_id;
            }
            $('#remove-images').val(remove_ids);
        });
        function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }
});

</script>
