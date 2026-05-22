@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.medicin_categories.submit')}}" enctype="multipart/form-data" class="custom-form">
        <div class="card-body">
            <div class="row">
                    @csrf()
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                    <input
                        type="text"
                        class="form-control jqv-input" data-jqv-required="true"
                        id="title_en"
                        name="title_en"
                        value="{{$title_en}}"
                    />
                    </div>
                    <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-name">Title (ar)<span class="text-danger">*</span> </label>
                    <input
                        type="text"
                        class="form-control jqv-input" data-jqv-required="true"
                        id="title_ar"
                        name="title_ar"
                        value="{{$title_ar}}"
                        style="direction: rtl; text-align: right;"
                    />
                    </div>
                    <div class="col-lg-6 mb-4">

                    <label class="form-label" for="bs-validation-name">Title (bn)<span class="text-danger">*</span> </label>
                    <input
                        type="text"
                        class="form-control jqv-input" data-jqv-required="true"
                        id="title_ban"
                        name="title_ban"
                        value="{{$title_ban}}"
                    />
                    </div>
                    
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option @if($status=='1') selected @endif value="1">Active</option>
                        <option @if($status=='0') selected @endif value="0">Inactive</option>
                    </select>
                </div>
    
               
                <div class="col-12 d-flex mb-4">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                    <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@section('page_script')
<script>

$(document).ready(function() {
    
    App.initFormView();
    let form_in_progress=0;

    $('body').off('submit', '#admin_form');
    $('body').on('submit', '#admin_form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
        var $form = $(this);
        var formData = new FormData(this);

        // $form.validate({
        //     rules: {

        //     },
        //     errorElement: 'div',
        //     errorPlacement: function(error, element) {
        //         element.addClass('is-invalid');
        //         error.addClass('error');
        //         error.insertAfter(element);
        //     }
        // });

        // Bind extra rules. This must be called after .validate()
        // App.setJQueryValidationRules('#admin_form');

        // if ( $form.valid() ) {
        //     validation.resolve();
        // } else {
        //     var error = $form.find('.is-invalid').eq(0);
        //     $('html, body').animate({
        //         scrollTop: (error.offset().top - 100),
        //     }, 500);
        //     validation.reject();
        // }

        // validation.done(function() {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('div.error').remove();


            App.loading(true);


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
                        App.alert(res['message']||'Record saved successfully', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = res['oData']['redirect'];
                        },2500);

                    }

                },
                error: function (e) {
                    form_in_progress = 0;
                    App.loading(false);
                    console.log(e);
                    App.alert( "Network error please try again", 'Oops!','error');
                }
            });
        // });
    });
});
</script>
@stop