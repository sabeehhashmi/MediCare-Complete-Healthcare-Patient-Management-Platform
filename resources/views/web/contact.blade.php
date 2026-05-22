@extends('web.template.layout')

@section('title', 'Home')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if ( session('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('success') }} </strong>
</div>
@endif
@if ( session('error'))
<div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('error') }} </strong>
</div>
@endif
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<!-- <div class="main-content"> -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center text-muted my-5">
                    <h1>Contact Us</h1>
                    <p class="font-size-16 text-muted">{{$contactUsSettings["desc_en"]}}</p>
                </div>
            </div>
        </div>

        @csrf

        <div class="row">


            <div class="col-lg-6 mb-4">
            <form id="admin_form" method="post"  action="{{ route('contact_us') }}" autocomplete="off">
                @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Full Name <b class="text-danger">*</b></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control no-icon" name="name" id="" placeholder="Enter Your Full Name" />
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Email <b class="text-danger">*</b></label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control no-icon" name="email" placeholder="Enter Your Email" />
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Phone Number <b class="text-danger">*</b></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control no-icon" name="phone" id="phone1" placeholder="Enter Your Phone Number" />
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Subject <b class="text-danger">*</b></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control no-icon" name="subject" placeholder="Your Subject" />
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Message <b class="text-danger">*</b></label>
                                    <div class="position-relative">
                                        <textarea class="form-control no-icon" rows="5" name="message" placeholder="Your Message"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                             
                                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card mb-3">
                    <h5 class="card-header">We Are Here</h5>
                    <div class="card-body">
                        <!-- <h5 class="mb-4 lh-base">{{$contactUsSettings["uk_location"]}}
                            Phone: <a href="tel:{{$contactUsSettings["uk_phone"]}}">{{$contactUsSettings["uk_phone"]}}</a>
                            Email: <a href="mailto: {{$contactUsSettings["uk_email"]}}">{{$contactUsSettings["uk_email"]}}</a>
                        </h5> -->
                        <h5 class="mb-0 lh-base">
                        {!! str_replace(',', '<br>', $contactUsSettings["location"]) !!}
                            </br>
                            Phone: <a href="tel:{{$contactUsSettings["uae_phone"]}}">{{$contactUsSettings["uae_phone"]}}</a>
                            Email: <a href="mailto: {{$contactUsSettings["uae_email"]}}">{{$contactUsSettings["uae_email"]}}</a>
                        </h5>
                    </div>
                </div>
                <!-- <div class="card mb-3">
                                <h5 class="card-header">Patient Experience</h5>
                                <div class="card-body">
                                    <h5 class="mb-0 lh-base">Sheikh Mohammed bin Rashid Blvd,<br>
                                        Downtown Dubai, Dubai, United Arab Emirates<br>
                                        Phone: <a href="tel:+971 55 623894">+971 55 623894</a>
                                        Email: <a href="mailto: info@example.com">info@example.com</a>
                                    </h5>
                                </div>
                            </div> -->
                <div class="card mb-0">
                    <h5 class="card-header">Working Hours</h5>
                    <div class="card-body">
                        <h5 class="mb-0 lh-base">
                            @php
                            echo $contactUsSettings["working_hours"];
                            @endphp
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('web.template.footer-content')


</div>
<!-- </div> -->
<!-- end main content-->
@endsection

@section('custom_js')
    
    <script>
let fileArr =[];
$(document).ready(function() {
    
    console.log('ready');

    App.initFormView();
    let form_in_progress=0;

    $('body').off('submit', '#admin_form');
    $('body').on('submit', '#admin_form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
        var $form = $(this);
        var formData = new FormData(this);
        let i = 0;
        $.each(fileArr, function (k, v) {
            formData.append('images['+i+']', v);
            i++;
        });

        

        $form.validate({
            rules: {

            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                element.addClass('is-invalid');
                error.addClass('error');
                error.insertAfter(element);
            }
        });

        
        App.setJQueryValidationRules('#admin_form');

        if ( $form.valid() ) {
            validation.resolve();
        } else {
            var error = $form.find('.is-invalid').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 100),
            }, 500);
            validation.reject();
        }

        validation.done(function() {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('div.error').remove();


            App.loading(true);
            $form.find('[type="submit"]').prop("disabled",true);
            $form.find('[type="submit"]').text("Processing..");


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
                dataType:'html',
                success: function (res) {
                    res = JSON.parse(res);
                    console.log(res['status']);
                    form_in_progress = 0;
                    App.loading(false);
                    if ( res['status'] == 0 ) {
                        $form.find('[type="submit"]').prop("disabled",false);
                        $form.find('[type="submit"]').text("Save");
                        if ( typeof res['errors'] !== 'undefined' ) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function (e_field, e_message) {
                                if ( e_message != '' ) {
                                    $('[name="'+ e_field +'"]').eq(0).addClass('is-invalid');
                                    $('<div class="error">'+ e_message +'</div>').insertAfter($('[name="'+ e_field +'"]').eq(0));
                                    if ( error_index == 0 ) {
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
                            var m = res['message']||'Unable to save variation. Please try again later.';
                            App.alert(m, 'Oops!','error');
                        }
                    } else {
                        App.alert(res['message']||'Form submitted successfully!', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = "{{route('contact_us')}}";
                        },2500);

                    }

                },
                error: function (e) {
                    form_in_progress = 0;
                    App.loading(false);
                    $form.find('[type="submit"]').prop("disabled",false);
                    $form.find('[type="submit"]').text("Save");
                    console.log(e, 'e');
                    App.alert( "Network error please try again", 'Oops!','error');
                }
            });
         });
    });
   
  
  
});
</script>

@endsection