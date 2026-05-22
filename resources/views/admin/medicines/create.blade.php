{{-- resources/views/admin/medicines/create.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.medicines.submit')}}" enctype="multipart/form-data" class="custom-form">
        <div class="card-body">
            <div class="row">
                @csrf()
                <input type="hidden" name="id" value="{{$id ?? ''}}">

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Medicine Category<span class="text-danger">*</span></label>
                    <select name="medicin_category_id" class="form-control jqv-input select2" data-jqv-required="true">
                        <option value="">Select Category</option>
                        @foreach($medicin_categories as $item)
                        <option {{ ($medicine->medicin_category_id ?? '') == $item->id ? 'selected' : '' }} value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" value="{{ $medicine->sku ?? '' }}" placeholder="Enter SKU">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (English)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="title_en" value="{{ $medicine->title_en ?? '' }}" placeholder="Enter title in English">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (Arabic)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="title_ar" value="{{ $medicine->title_ar ?? '' }}" placeholder="Enter title in Arabic" style="direction: rtl; text-align: right;">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (Bengali)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" name="title_bn" value="{{ $medicine->title_bn ?? '' }}" placeholder="Enter title in Bengali">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Manufacturer</label>
                    <input type="text" class="form-control" name="manufacturer" value="{{ $medicine->manufacturer ?? '' }}" placeholder="Enter manufacturer name">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Price (<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control jqv-input" data-jqv-required="true" name="price" value="{{ $medicine->price ?? '' }}" placeholder="Enter price">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Discount Price (<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">)</label>
                    <input type="number" step="0.01" class="form-control" name="discount_price" value="{{ $medicine->discount_price ?? '' }}" placeholder="Enter discount price">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="stock_quantity" value="{{ $medicine->stock_quantity ?? 0 }}" placeholder="Enter stock quantity">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Product Tags</label>
                    <select name="tags[]" class="form-control select2-multiple" multiple="multiple">
                        @foreach($product_tags as $tag)
                        <option {{ in_array($tag->id, $selected_tags ?? []) ? 'selected' : '' }} value="{{$tag->id}}">{{$tag->name_en}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" class="form-control ckeditor" rows="3" placeholder="Enter short description">{{ $medicine->short_description ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Description<span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control jqv-input ckeditor" data-jqv-required="true" rows="5" placeholder="Enter description">{{ $medicine->description ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Uses</label>
                    <textarea name="uses" class="form-control ckeditor" rows="3" placeholder="Enter uses">{{ $medicine->uses ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Benefits</label>
                    <textarea name="benefits" class="form-control ckeditor" rows="3" placeholder="Enter benefits">{{ $medicine->benefits ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Side Effects</label>
                    <textarea name="side_effects" class="form-control ckeditor" rows="3" placeholder="Enter side effects">{{ $medicine->side_effects ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">How to Use</label>
                    <textarea name="how_to_use" class="form-control ckeditor" rows="3" placeholder="Enter how to use">{{ $medicine->how_to_use ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Other Information</label>
                    <textarea name="other_info" class="form-control ckeditor" rows="3" placeholder="Enter other information">{{ $medicine->other_info ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" value="{{ $medicine->meta_title ?? '' }}" placeholder="Enter meta title">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2" placeholder="Enter meta description">{{ $medicine->meta_description ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" class="form-control" name="meta_keywords" value="{{ $medicine->meta_keywords ?? '' }}" placeholder="Enter meta keywords (comma separated)">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Main Image</label>
                    <input class="form-control"  id="mainImageUpload" type="file" name="image" accept="image/*">
                    <div id="mainImagePreview" class="mt-2">
                        @if($medicine->image ?? false)
                            <img src="{{ $medicine->image_url }}" alt="Main Image" style="max-height: 100px;">
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Gallery Images</label>
                    <input class="form-control"  id="galleryImagesUpload"  type="file" name="gallery_images[]" multiple accept="image/*">
                    <div id="galleryImagesPreview" class="mt-2 d-flex flex-wrap gap-2">
                    @if($medicine->gallery_images ?? false)
                        @foreach($medicine->gallery_images_url as $image)
                            <img src="{{ $image }}" alt="Gallery Image" style="max-height: 50px; max-width: 50px; object-fit: cover;">
                        @endforeach
                    @endif
                </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <label class="form-label">Prescription Required</label>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" class="form-check-input" name="prescription_required" value="1" {{ ($medicine->prescription_required ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <label class="form-label">Featured</label>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" class="form-check-input" name="featured" value="1" {{ ($medicine->featured ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" {{ ($medicine->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($medicine->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
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
    $('.select2').select2();
    $('.select2-multiple').select2({
        placeholder: "Select tags",
        allowClear: true
    });
    
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
            enctype: 'multipart/form-data',
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
    

     function validateDiscount() {
        
        var $price = $('[name="price"]');
        var $discount = $('[name="discount_price"]');

        var price = parseFloat($price.val()) || 0;
        var discount = parseFloat($discount.val()) || 0;

        // Remove existing error
        $discount.removeClass('is-invalid');
        $discount.siblings('.error').remove();

        // Validation
        if (discount > price) {
            $discount.addClass('is-invalid');
            $('<div class="error text-danger mt-1">Discount cannot exceed price</div>').insertAfter($discount);
        }
    }

    // Bind validation to both fields
    $('[name="price"], [name="discount_price"]').on('input', validateDiscount);
});
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize CKEditor for all textareas with class 'ckeditor'
    document.querySelectorAll('textarea.ckeditor').forEach(function(el) {
        ClassicEditor.create(el)
            .then(editor => {
                editor.ui.view.editable.element.style.height = "200px";
            })
            .catch(error => {
                console.error(error);
            });
    });
});
</script>

<script>
function readImage(input, previewContainer) {
    previewContainer.innerHTML = ''; // clear previous
    if (input.files) {
        Array.from(input.files).forEach(file => {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement("img");
                img.src = e.target.result;
                img.style.maxHeight = "100px";
                img.style.marginRight = "10px";
                img.style.objectFit = "cover";
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}



document.getElementById('mainImageUpload')?.addEventListener('change', function() {
    readImage(this, document.getElementById('mainImagePreview'));
});

document.getElementById('galleryImagesUpload')?.addEventListener('change', function() {
    readImage(this, document.getElementById('galleryImagesPreview'));
});
</script>
@stop