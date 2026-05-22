@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post"  action="{{ url('/admin/faq-for-hospital/create') }}" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$cms_page->id??''}}" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-4 form-group">
                            <label>Question<b class="text-danger">*</b></label>
                            <input type="text" name="question" class="form-control jqv-input" data-jqv-required="true" required
                                data-parsley-required-message="Question required">
                        </div>

                        <div class="col-md-6 mb-4 form-group">
                            <label>Status</label>
                            <select name="active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4 form-group">
                            <label>Answer<b class="text-danger">*</b></label>
                            <textarea name="answer" class="form-control jqv-input" data-jqv-required="true" required
                                data-parsley-required-message="Answer required"></textarea>
                        </div>

                

                        <div class="col-12 d-flex mt-2">
                            <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                            <button type="button" class="reset-form btn btn-dark waves-effect waves-light">Clear</button>
                        </div>
                    </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('page_script')
<script>
let fileArr =[];
$(document).ready(function() {
    
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
                        App.alert(res['message']||'Record saved successfully', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = "{{url('/admin/faq-for-hospital')}}";
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

@stop
