{{-- resources/views/admin/product_tags/create.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.product_tags.submit')}}" class="custom-form">
        <div class="card-body">
            <div class="row">
                @csrf()
                <input type="hidden" name="id" value="{{$id ?? ''}}">

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Tag Name (English)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="name_en" value="{{ $tag->name_en ?? '' }}" placeholder="Enter tag name in English">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Tag Name (Arabic)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="name_ar" value="{{ $tag->name_ar ?? '' }}" placeholder="Enter tag name in Arabic" style="direction: rtl; text-align: right;">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Tag Name (Bengali)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="name_bn" value="{{ $tag->name_bn ?? '' }}" placeholder="Enter tag name in Bengali">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Tag Color</label>
                    <input type="color" class="form-control form-control-color" name="color" value="{{ $tag->color ?? '#1baeff' }}" style="height: 38px;">
                </div>

                <div class="col-lg-12 mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Enter description">{{ $tag->description ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" {{ ($tag->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($tag->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12 d-flex mb-4">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{ $id ? 'Update' : 'Save' }}</button>
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
        var $form = $(this);
        var formData = new FormData(this);

        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('div.error').remove();

        App.loading(true);
        form_in_progress = 1;
        
        $.ajax({
            type: "POST",
            url: $form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: 'json',
            success: function(res) {
                form_in_progress = 0;
                App.loading(false);
                
                if (res.status == 0) {
                    if (typeof res.errors !== 'undefined') {
                        $.each(res.errors, function(e_field, e_message) {
                            $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                            $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                        });
                        var error = $form.find('.is-invalid').eq(0);
                        $('html, body').animate({
                            scrollTop: (error.offset().top - 100),
                        }, 500);
                    } else {
                        App.alert(res.message || 'Unable to save. Please try again.', 'Oops!', 'error');
                    }
                } else {
                    App.alert(res.message || 'Record saved successfully', 'Success!', 'success');
                    setTimeout(function() {
                        window.location.href = res.oData.redirect;
                    }, 1500);
                }
            },
            error: function(e) {
                form_in_progress = 0;
                App.loading(false);
                App.alert("Network error please try again", 'Oops!', 'error');
            }
        });
    });
});
</script>
@stop