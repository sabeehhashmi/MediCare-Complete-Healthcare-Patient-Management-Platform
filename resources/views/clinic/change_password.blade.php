@include('clinic.layouts.header')
<div class="position-relative">
    <div class="d-lg-flex">
    @include('clinic.layouts.left_nav_profile')
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-5">
                    <form  id="hospital-form-changepwd" action="{{url('clinic/change_password')}}" enctype="multipart/form-data"  class="custom-form">
                        @csrf
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Old Password</label>
                                    <input type="password" name="cur_pswd" class="form-control" placeholder="Enter " id="formrow-firstname-input" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-email-input">New Password</label>
                                    <input type="password" name="new_pswd" class="form-control" placeholder="Enter" id="formrow-email-input" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Confirm New Password</label>
                                    <input type="password" name="confirm" class="form-control" placeholder="Enter" id="formrow-password-input" />
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

@include('clinic.layouts.footer')
<script>
    $(document).ready(function() {
        App.initFormView();
        let form_in_progress=0;
        $('body').on('submit', '#hospital-form-changepwd', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            $form.find('[type="submit"]').prop("disabled", true);
            $form.find('[type="submit"]').text("Saving...");
            var formData = new FormData(this);
            let i = 0;
            App.setJQueryValidationRules('#hospital-form-changepwd');
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