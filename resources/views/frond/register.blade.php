@extends("frond.template.layout")

@section('content')
<div class="inner-hero-section style--five"></div>

<div class="mt-minus-100 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="user-card">
                    <h3 class="title mb-3">Register Now</h3>
                    <form method="post" id="register-form" action="{{route('guest.signup')}}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start">
                                    <label>First Name <sup>*</sup></label>
                                    <input type="text" name="first_name" id="first_name" class="jqv-input" data-jqv-required="true" placeholder="Enter First Name">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start">
                                    <label>Last Name <sup>*</sup></label>
                                    <input type="text" name="last_name" id="last_name" class="jqv-input" data-jqv-required="true" placeholder="Enter Last Name">
                                </div>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <div class="form-group mb-0 text-start">
                                    <label>DialCode <sup>*</sup></label>
                                    <select name="dial_code" class="jqv-input form-control" data-jqv-required="true">
                                        <option value="">Select</option>
                                        @foreach($country_list as $country)
                                            <option value="{{$country->dial_code}}">{{$country->dial_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <div class="form-group mb-0 text-start">
                                    <label>Mobile Number <sup>*</sup></label>
                                    <input type="number" name="phone" id="phone" class="jqv-input" data-jqv-required="true" placeholder="Enter Mobile Number">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start">
                                    <label>Email Id <sup>*</sup></label>
                                    <input type="email" name="email" id="email" class="jqv-input" data-jqv-required="true" placeholder="Enter Email Id">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start position-relative">
                                    <label>Password <sup>*</sup></label>
                                    <input type="password" name="password" id="password" class="jqv-input" data-jqv-required="true" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start position-relative">
                                    <label>Confirm Password <sup>*</sup></label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="jqv-input" data-jqv-required="true" placeholder="Enter Confirm Password">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="form-group mb-0 text-start position-relative">
                                    <label>Referral Code <sup>*</sup></label>
                                    <input type="text" name="ref_code" id="ref_code" class="jqv-input"  placeholder="Enter Referral Code">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4"></div>
                            <div class="col-lg-6 mt-3">
                                <div class="form-group text-start mb-0">
                                    <button type="submit" class="cmn-btn w-100">Register Now</button>
                                </div>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
    $('document').ready(function(){
        //WebApp.alert("sss");
    })
    
        
    $('body').off('submit', '#register-form');
    $('body').on('submit', '#register-form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();
        $form.validate({
            rules: {
                password: {
                    minlength: 3,
                },
                confirm_password: {
                    minlength: 3,
                    equalTo: "#password"
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                element.addClass('is-invalid');
                error.addClass('error');
                error.insertAfter(element);
            }
        });

        //Bind extra rules. This must be called after .validate()
        WebApp.setJQueryValidationRules('#register-form');

        if ( $form.valid() ) {
            validation.resolve();
        } else {
            var error = $form.find('.error').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 1000),
            }, 500);
            validation.reject();
        }

        validation.done(function() {
            WebApp.loading(true);
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
                timeout: 600000,
                dataType: 'json',
                success: function(res) {
                    WebApp.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
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
                            var m = res['message'] ||
                            'Unable to submit your data. Please try again later.';
                            WebApp.alert(m, 'Oops!');
                        }
                    } else {
                        WebApp.alert(res['message'], 'Success!');
                                setTimeout(function(){
                                    window.location.reload();
                                },1500);
                        
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    WebApp.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                        WebApp.alert(e.responseText, 'Oops!');
                }
            });
        });
    });
</script>
@stop