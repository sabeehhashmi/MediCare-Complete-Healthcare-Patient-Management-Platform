@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form action="{{ url('/admin/doctor_insctruction/update') }}" id="admin_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$faq->id}}">
                <div class="row  d-flex justify-content-between align-items-center">
                    <div class="col-md-6 mb-4 form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option  <?= $faq->type == 1 ? 'selected' : '' ?> value="1">Registering a healthcare provider </option>
                            <option <?= $faq->type == 2 ? 'selected' : '' ?> value="2">Managing Appointments</option>
                            <option <?= $faq->type == 3 ? 'selected' : '' ?> value="3">Managing Availability of Doctors.                            </option>
                            <option <?= $faq->type == 4 ? 'selected' : '' ?> value="4"> Managing Reports in Doctor Panel </option>
                           
                        </select>
                    </div>
                    <div class="col-md-6 mb-4 form-group">
                        <label>Title<b class="text-danger">*</b></label>
                        <input type="text" name="question" class="form-control jqv-input" data-jqv-required="true" value="{{$faq->title}}">
                    </div>
                    
                   
                    <div class="col-md-12 mb-4 form-group">
                        <label>Description<b class="text-danger">*</b></label>
                        <textarea class="form-control" id="ckeditor-classic" name="answer" row="5">{{$faq->description}}</textarea>
                       </div>

                 
                    <div class="col-12 d-flex mt-3">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                        <button type="button" class="reset-form btn btn-dark waves-effect waves-light">Clear</button>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection


@section('page_script')
<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script>
let fileArr =[];
$(document).ready(function() {
    
    App.initFormView();
    ClassicEditor.create(document.querySelector("#ckeditor-classic"))
    .then(function (e) {
        e.ui.view.editable.element.style.height = "200px";
    })
    .catch(function (e) {
        console.error(e);
    });
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
                            window.location.href = "{{url('/admin/doctor_insctruction')}}";
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


