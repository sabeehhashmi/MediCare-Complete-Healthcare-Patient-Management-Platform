{{-- resources/views/admin/coupons/create.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.coupons.submit')}}" class="custom-form">
        <div class="card-body">
            <div class="row">
                @csrf()
                <input type="hidden" name="id" value="{{$id ?? ''}}">

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Coupon Code<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input text-uppercase" 
                           data-jqv-required="true" name="code" 
                           value="{{ $coupon->code ?? '' }}" 
                           placeholder="Enter coupon code (e.g., SUMMER2024)">
                    <small class="text-muted">Only letters, numbers, hyphens and underscores allowed</small>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (English)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control jqv-input" data-jqv-required="true" 
                           name="title_en" value="{{ $coupon->title_en ?? '' }}" 
                           placeholder="Enter title in English">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (Arabic)</label>
                    <input type="text" class="form-control" name="title_ar" 
                           value="{{ $coupon->title_ar ?? '' }}" 
                           placeholder="Enter title in Arabic" 
                           style="direction: rtl; text-align: right;">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Title (Bengali)</label>
                    <input type="text" class="form-control" name="title_bn" 
                           value="{{ $coupon->title_bn ?? '' }}" 
                           placeholder="Enter title in Bengali">
                </div>

                <div class="col-lg-12 mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Enter description">{{ $coupon->description ?? '' }}</textarea>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Coupon Type<span class="text-danger">*</span></label>
                    <select name="type" class="form-control jqv-input" data-jqv-required="true" id="coupon_type">
                        <option value="fixed" {{ ($coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ ($coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Value<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" id="value_prefix"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"></span>
                        <input type="number" step="0.01" class="form-control jqv-input" 
                               data-jqv-required="true" name="value" 
                               value="{{ $coupon->value ?? '' }}" 
                               placeholder="Enter value" id="value_input">
                        <span class="input-group-text" id="value_suffix" style="display: none;">%</span>
                    </div>
                </div>

                <!-- <div class="col-lg-6 mb-4 percentage-field" style="display: none;">
                    <label class="form-label">Maximum Discount</label>
                    <div class="input-group">
                        <span class="input-group-text"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"></span>
                        <input type="number" step="0.01" class="form-control" 
                               name="max_discount" value="{{ $coupon->max_discount ?? '' }}" 
                               placeholder="Enter maximum discount amount">
                    </div>
                </div> -->

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Total Uses (Global)</label>
                    <input type="number" class="form-control" name="total_uses" 
                           value="{{ $coupon->total_uses ?? '' }}" 
                           placeholder="Leave empty for unlimited">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Per User Uses<span class="text-danger">*</span></label>
                    <input type="number" class="form-control jqv-input" data-jqv-required="true" 
                           name="per_user_uses" value="{{ $coupon->per_user_uses ?? 1 }}" 
                           placeholder="Enter max uses per user" min="1">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Start Date</label>
                    <input type="datetime-local" class="form-control" name="start_date" 
                           value="{{ isset($coupon->start_date) ? date('Y-m-d\TH:i', strtotime($coupon->start_date)) : '' }}">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">End Date</label>
                    <input type="datetime-local" class="form-control" name="end_date" 
                           value="{{ isset($coupon->end_date) ? date('Y-m-d\TH:i', strtotime($coupon->end_date)) : '' }}">
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label">Minimum Order Amount</label>
                    <div class="input-group">
                        <span class="input-group-text"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"></span>
                        <input type="number" step="0.01" class="form-control" name="min_order_amount" 
                               value="{{ $coupon->min_order_amount ?? '' }}" 
                               placeholder="Enter minimum order amount">
                    </div>
                </div>

                <div class="col-lg-12 mb-4">
                    <label class="form-label">Apply On<span class="text-danger">*</span></label>
                    <select name="apply_on" class="form-control jqv-input" data-jqv-required="true" id="apply_on">
                        <option value="all" {{ ($coupon->apply_on ?? '') == 'all' ? 'selected' : '' }}>All Products</option>
                        <option value="specific_products" {{ ($coupon->apply_on ?? '') == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                        <option value="specific_categories" {{ ($coupon->apply_on ?? '') == 'specific_categories' ? 'selected' : '' }}>Specific Categories</option>
                    </select>
                </div>

                <div class="col-lg-12 mb-4" id="products_section" style="display: none;">
                    <label class="form-label">Select Products<span class="text-danger">*</span></label>
                    <select name="products[]" class="form-control select2-multiple" multiple="multiple">
                        @foreach($products as $product)
                        <option value="{{$product->id}}" 
                            {{ in_array($product->id, $selected_products ?? []) ? 'selected' : '' }}>
                            {{$product->title_en}} ({{$product->sku}})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-12 mb-4" id="categories_section" style="display: none;">
                    <label class="form-label">Select Categories<span class="text-danger">*</span></label>
                    <select name="categories[]" class="form-control select2-multiple" multiple="multiple">
                        @foreach($categories as $category)
                        <option value="{{$category->id}}" 
                            {{ in_array($category->id, $selected_categories ?? []) ? 'selected' : '' }}>
                            {{$category->title}}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-12 mb-4">
                    <h5>Restrictions</h5>
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="for_new_users_only" 
                                       value="1" {{ ($coupon->for_new_users_only ?? 0) ? 'checked' : '' }}>
                                <label class="form-check-label">New Users Only</label>
                                <small class="d-block text-muted">Coupon valid only for users who haven't placed any order</small>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="for_first_order_only" 
                                       value="1" {{ ($coupon->for_first_order_only ?? 0) ? 'checked' : '' }}>
                                <label class="form-check-label">First Order Only</label>
                                <small class="d-block text-muted">Coupon valid only for user's first order</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" {{ ($coupon->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($coupon->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12 d-flex mb-4">
                    <button class="btn btn-primary me-2" type="submit">
                        {{ $id ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" class="reset-form btn btn-info">Clear</button>
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
        placeholder: "Select items",
        allowClear: true
    });

    // Handle coupon type change
    function toggleTypeFields() {
        var type = $('#coupon_type').val();
        if (type === 'percentage') {
            $('#value_prefix').hide();
            $('#value_suffix').show();
            $('.percentage-field').show();
            $('#value_input').attr('step', '0.01').attr('max', '100');
        } else {
            $('#value_prefix').show();
            $('#value_suffix').hide();
            $('.percentage-field').hide();
            $('#value_input').attr('step', '0.01').removeAttr('max');
        }
    }

    $('#coupon_type').change(toggleTypeFields);
    toggleTypeFields();

    // Handle apply_on change
    function toggleApplyOn() {
        var applyOn = $('#apply_on').val();
        $('#products_section, #categories_section').hide();

        if (applyOn === 'specific_products') {
            $('#products_section').show();
        } else if (applyOn === 'specific_categories') {
            $('#categories_section').show();
        }
    }

    $('#apply_on').change(toggleApplyOn);
    toggleApplyOn();

    // Form submission
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
                            $('<div class="error">' + e_message + '</div>')
                                .insertAfter($('[name="' + e_field + '"]').eq(0));
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