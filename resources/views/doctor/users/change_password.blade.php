@include('doctor.template.header')

<div class="position-relative mb-5">
        <div class="d-lg-flex">
                            <div class="chat-leftsidebar card">
                                <div class="card-body" style="flex: 0;">
                                    
                                   <div class="text-center bg-light rounded px-4 py-3">
                                            <!-- <div class="text-end">
                                                <div class="dropdown chat-noti-dropdown">
                                                    <button class="btn dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bx bx-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="profile.php">Profile</a>
                                                        <a class="dropdown-item" href="profile-edit.php">Edit</a>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="chat-user-status mt-4">
                                                <div class="avatar-upload position-relative">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                        <label for="imageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <img src="{{$doctor->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                                                    </div>
                                                </div>
                                                <!-- <img src="assets/images/doctor.png" class="avatar-md rounded-circle" alt=""> -->
                                                <!-- <div class="">
                                                    <div class="status"></div>
                                                </div> -->
                                            </div>
                                            <h5 class="font-size-16 mb-1 mt-3"><a href="{{ url('doctor/get_profile') }}" class="text-reset">DR {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </a></h5>
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
                                                    <a href="{{ url('doctor/edit_profile') }}" class="border-bottom">
                                                        <div class="d-flex align-items-center">
                                                            <i class="mdi mdi-star-outline font-size-20 align-middle me-3"></i>
                                                            <div class="flex-grow-1">
                                                                <h5 class="font-size-14 mb-0">Edit Profile</h5>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                            </div>
                                                        </div>
                                                    </a>
            
                                                    <a href="{{ url('doctor/change_password') }}" class="border-bottom active bg-primary-subtle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                                                            <div class="flex-grow-1">
                                                                <h5 class="font-size-14 mb-0">Change Password</h5>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                            </div>
                                                        </div>
                                                    </a>
            
                                                </div>

                            </div>
                            <!-- end chat-leftsidebar -->

                            <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
                                <div class="card">
                                    <div class="p-4 pt-5">
                                    <form id="doctor-form-changepwd" action="{{url('doctor/change_password')}}" enctype="multipart/form-data" class="custom-form">    
                                    @csrf()
                                    <div class="row"> 
                                      
                                                            <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-oldpwd-input">Old Password</label>
                                                            <input type="password" name="cur_pswd" class="form-control" placeholder="Enter " id="formrow-oldpwd-input" required>
                                                        </div>
                                                        </div>

                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="formrow-passwprd-input">New Password</label>
                                                                    <input type="password" name="new_pswd" class="form-control" placeholder="Enter" id="formrow-password-input" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="formrow-password-input">Confirm New Password</label>
                                                                    <input type="password" name="confirm" class="form-control" placeholder="Enter" id="formrow-password-input" required>
                                                                </div>
                                                            </div>   
                                                        </div>

                                                        <!-- <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="formrow-customCheck">
                                                            <label class="form-check-label" for="formrow-customCheck">Sub-Insurances</label>
                                                        </div> -->
                                                        
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-primary w-md">Submit</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $('#base-style').DataTable();

   $("#base-style").DataTable();
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
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
</script>
<script>
    $(document).ready(function() {
        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#doctor-form-changepwd', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true);
            $form.find('[type="submit"]').text("Saving...");
            var formData = new FormData(this);
            let i = 0;
            App.setJQueryValidationRules('#doctor-form-changepwd');
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
                            $form.find('[type="submit"]').text("Submit");
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
                        $form.find('[type="submit"]').text("Submit");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            // });
        });

    });
</script>