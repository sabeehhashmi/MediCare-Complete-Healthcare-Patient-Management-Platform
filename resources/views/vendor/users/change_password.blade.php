@extends("vendor.template.layout")

@section("content")
<div class="card mb-5">
    <div class="card-body">
        <form method="post" id="admin-form" action="{{url('vendor/change_password')}}" enctype="multipart/form-data">
            @csrf()
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Current Password</label>
                    <div class="input-group mb-3">
                        <input id="password1"  type="password" name="cur_pswd" class="form-control jqv-input" data-jqv-required="true">

                        <div class="input-group-append"
                            style="cursor: pointer;position: relative;box-sizing: border-box;height: auto;color: #fff;font-size: 16px;border: none;background: transparent;">
                            <span class="input-group-text"
                                style="background: white;border-left: 0;"
                                onclick="password_show_hide(1);">
                                <i class=" d-none fas fa-eye" id="show_eye1"></i>
                                <i class="fas fa-eye-slash" id="hide_eye1"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>New Password</label>
                    <div class="input-group mb-3">
                        <input id="password2" type="password" name="new_pswd" class="form-control jqv-input" data-jqv-required="true">
                        <div class="input-group-append"
                            style="cursor: pointer;position: relative;box-sizing: border-box;height: auto;color: #fff;font-size: 16px;border: none;background: transparent;">
                            <span class="input-group-text"
                                style="background: white;border-left: 0;"
                                onclick="password_show_hide(2);">
                                <i class=" d-none fas fa-eye" id="show_eye2"></i>
                                <i class="fas fa-eye-slash" id="hide_eye2"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Change</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-xs-12 col-sm-6">

        </div>
    </div>
</div>
@stop

@section("script")
<script>
    App.initFormView();
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined' &&  res['errors'].length) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
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
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/vendor');
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });

        function password_show_hide(id) {
            var x = document.getElementById("password"+id);
            var show_eye = document.getElementById("show_eye"+id);
            var hide_eye = document.getElementById("hide_eye"+id);
            show_eye.classList.remove("d-none");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";
            } else {
                x.type = "password";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            }
        }

</script>

@stop