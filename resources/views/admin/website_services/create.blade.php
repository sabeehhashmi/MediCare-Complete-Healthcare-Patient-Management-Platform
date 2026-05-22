@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.website_services.submit')}}" enctype="multipart/form-data" class="custom-form">
        <div class="card-body">
            <div class="row">
                @csrf()
                <input type="hidden" name="id" value="{{$id}}">
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="bs-validation-name">Title<span class="text-danger">*</span> </label>
                    <input
                        type="text"
                        class="form-control jqv-input" data-jqv-required="true"
                        id="title"
                        name="title"
                        value="{{$title}}" />
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option @if($status=='1' ) selected @endif value="1">Active</option>
                        <option @if($status=='0' ) selected @endif value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-12 mb-4">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Description</label>
                        <textarea class="form-control" id="ckeditor-classic" name="desc" row="5">{{ $desc??''  }}</textarea>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Icon Type *</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input type="radio" name="icon_type" id="icon_type_fontawesome" {{ $icon_type == 'fontawesome' ? 'checked' : '' }} value="fontawesome" class="form-check-input" checked>
                                    <label class="form-check-label" for="icon_type_fontawesome">Font Awesome Icon</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="icon_type" id="icon_type_image" {{ $icon_type == 'image' ? 'checked' : '' }} value="image" class="form-check-input">
                                    <label class="form-check-label" for="icon_type_image">Upload Image</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="fontawesome_section" style="{{ $icon_type == 'image' ? 'display: none;' : '' }}">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="icon_fontawesome" class="form-label">Font Awesome Icon Class</label>
                            <input type="text" class="form-control" name="icon_fontawesome" id="icon_fontawesome" placeholder="e.g., fas fa-stethoscope, fas fa-globe, fas fa-shield-alt">
                            <small class="text-muted">Enter Font Awesome icon class. Example: fas fa-stethoscope, fas fa-globe-americas, fas fa-shield-alt</small>
                            <div class="mt-2" id="icon_preview" style="font-size: 48px; color: #00bfff;"></div>
                        </div>
                    </div>
                </div>

                <div class="row" id="image_section" style="{{ $icon_type == 'fontawesome' ? 'display: none;' : '' }}">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="icon_image" class="form-label">Upload Icon Image</label>
                            <input type="file" class="form-control" name="icon_image" id="icon_image" accept="image/jpeg,image/png,image/jpg,image/svg">
                            <small class="text-muted">Recommended size: 64x64 pixels. SVG, PNG, JPG allowed.</small>
                            <div class="mt-2" id="image_preview">
                                @if($icon && $icon_type == 'image')
                                    <img src="{{ get_uploaded_image_url($icon, 'website_services_dir') }}" style="width: 64px; height: 64px;">
                                @endif
                            </div>
                        </div>
                    </div>
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
        let form_in_progress = 0;

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
                dataType: 'html',
                success: function(res) {
                    res = JSON.parse(res);
                    console.log(res['status']);
                    form_in_progress = 0;
                    App.loading(false);
                    if (res['status'] == 0) {
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
                            var m = res['message'] || 'Unable to save variation. Please try again later.';
                            App.alert(m, 'Oops!', 'error');
                        }
                    } else {
                        App.alert(res['message'] || 'Record saved successfully', 'Success!', 'success');
                        setTimeout(function() {
                            window.location.href = res['oData']['redirect'];
                        }, 2500);

                    }

                },
                error: function(e) {
                    form_in_progress = 0;
                    App.loading(false);
                    console.log(e);
                    App.alert("Network error please try again", 'Oops!', 'error');
                }
            });
            // });
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

<script>
    $(document).ready(function() {
        // Toggle icon sections
        $('input[name="icon_type"]').on('change', function() {
            if ($(this).val() == 'fontawesome') {
                $('#fontawesome_section').show();
                $('#image_section').hide();
                $('#icon_fontawesome').prop('required', true);
                $('#icon_image').prop('required', false);
            } else {
                $('#fontawesome_section').hide();
                $('#image_section').show();
                $('#icon_fontawesome').prop('required', false);
                $('#icon_image').prop('required', true);
            }
        });

        // Preview Font Awesome icon
        $('#icon_fontawesome').on('input', function() {
            var iconClass = $(this).val();
            if (iconClass) {
                $('#icon_preview').html('<i class="' + iconClass + '" style="font-size: 48px;"></i>');
            } else {
                $('#icon_preview').html('');
            }
        });

        // Preview image
        $('#icon_image').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_preview').html('<img src="' + e.target.result + '" style="width: 64px; height: 64px;">');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@stop