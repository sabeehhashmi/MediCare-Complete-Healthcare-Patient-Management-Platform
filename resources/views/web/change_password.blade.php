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
                                            
                                        <form  id="patient-form-changepwd" action="{{url('website/change_password')}}" enctype="multipart/form-data"  class="custom-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label" for="formrow-firstname-input">Old Password</label>
                                                        <input type="password" name="cur_pswd" class="form-control" placeholder="Enter Old Password" id="formrow-firstname-input" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label" for="formrow-email-input">New Password</label>
                                                        <input type="password" name="new_pswd" class="form-control" placeholder="Enter New Password" id="formrow-email-input" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label" for="formrow-password-input">Confirm New Password</label>
                                                        <input type="password" name="confirm" class="form-control" placeholder="Confirm New Password" id="formrow-password-input" />
                                                    </div>

                                                    <div class="mt-3">
                                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                                    </div>
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
@endsection

@section('custom_js')
    <script>

        // Handle signup form submission
        $('body').on('submit', '#patient-form-changepwd', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true).text("Saving...");
            var formData = new FormData(this);
            let i = 0;
            App.setJQueryValidationRules('#patient-form-changepwd');
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
                            $form.find('[type="submit"]').prop("disabled", false).text("Submit");
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

        $(document).ready(function() {
            // getLocation();
        });
        
    </script>
@endsection