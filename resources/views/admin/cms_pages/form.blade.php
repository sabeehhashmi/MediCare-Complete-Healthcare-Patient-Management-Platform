@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('admin.cms_pages.save',['type'=>$type])}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$cms_page->id??''}}" name="id">
            <div class="row">
                <div class=" col-md-4 mb-4 form-group">
                    <label for="t-text">Name en</label>
                    <input type="text" name="title_en" id="title_en" value="{{ $cms_page->title_en }}" class="form-control jqv-input" placeholder="Enter title in English" required
                data-parsley-required-message="Name en is required">
                </div>

                <div class=" col-md-4 mb-4 form-group">
                    <label for="t-text">Name Ar</label>
                    <input type="text" name="title_ar" id="title_ar" value="{{ $cms_page->title_ar }}" class="form-control jqv-input" dir="rtl" placeholder="Enter title in Arabic" >
                </div>
                <div class=" col-md-4 mb-4 form-group">
                    <label for="t-text">Status</label>                                            
                    <select class="form-control jqv-input" name="status" id="status" required>
                        <option value="1" {{ $cms_page->status == 1 ? "selected" : ""}} >Active</option>
                        <option value="0" {{ $cms_page->status == 0 ? "selected" : ""}} >Inactive</option>
                    </select>                            
                </div>

            
                
                <div class="col-12 mb-4">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Description</label>
                        <textarea class="form-control" id="ckeditor-classic" name="desc_en" row="5">{{ $cms_page->desc_en??''  }}</textarea>
                    </div>
                </div>

                <div class="col-12 d-flex mt-2">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                    <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
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
                            window.location.href = "{{url('admin/cms_pages?type='.$type)}}";
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

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>

<script>
    $("#base-style").DataTable();
    ClassicEditor.create(document.querySelector("#ckeditor-classic"))
    .then(function (e) {
        e.ui.view.editable.element.style.height = "200px";
    }) 
    .catch(function (e) {
        console.error(e);
    });

    ClassicEditor.create(document.querySelector("#ckeditor-classic-ar"))
    .then(function (e) {
        e.ui.view.editable.element.style.height = "200px";
    })
    .catch(function (e) {
        console.error(e);
    });

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
$("#imageUpload").change(function() {
    readURL(this);
});
</script>

@stop