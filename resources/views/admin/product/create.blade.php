@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.save_product')}}" enctype="multipart/form-data" data-parsley-validate="true">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    @csrf()
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="mb-3">
                    <label class="form-label" for="bs-validation-name">Name<span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control jqv-input" required data-jqv-required="true"
                                data-parsley-required-message="Ticket Name required"
                            id="product_name"
                            name="product_name"
                            value="{{$product_name}}"
                        />
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <label class="form-label">Draw Type<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <select class="form-control jqv-input" data-jqv-required="true" name="product_type" id="product_type">
                            <option @if($product_type=='daily') selected @endif value="daily">Daily Draw</option>
                            <option @if($product_type=='monthly') selected @endif value="monthly">Monthly Draw</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                <label class="form-label">Price<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control jqv-input" required data-jqv-required="true"
                                data-parsley-required-message="Price required"
                            id="price"
                            name="price"
                            value="{{$price}}"
                        />
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <select class="form-control jqv-input" data-jqv-required="true" name="product_status">
                            <option @if($product_status=='1') selected @endif value="1">Active</option>
                            <option @if($product_status=='0') selected @endif value="0">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                <label class="form-label">Description<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <textarea name="description" class="form-control" required data-parsley-required-message="Description required">{{$description}}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6  @if($product_type=='daily') d-none @endif" id="drow_date_holder">
                <label class="form-label">Draw Date</label>
                    <div class="form-group">
                        <select name="drow_date" class="form-control">
                            @php
                            $d=1;
                            @endphp
                            @for($d=1;$d<=30;$d++)
                                <option {{($d==$drow_date)?'selected':''}} value="{{$d}}">{{$d}} of every month</option>
                            @endfor
                        </select>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                <label class="form-label">Draw Time<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <input
                            type="time"
                            class="form-control jqv-input" required data-jqv-required="true"
                                data-parsley-required-message="Time required"
                            id="drow_time"
                            name="drow_time"
                            value="{{$drow_time}}"
                        />
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                <label class="form-label">Image</label>
                    <div class="form-group">
                    <img id="image-preview" style="width:100px; height:90px;" class="img-responsive"
                                    @if ($id) src="{{ $file_name }}" @endif>
                                <br><br>
                                <input type="file" name="product_image" class="form-control" data-role="file-image" data-preview="image-preview" data-parsley-trigger="change"
                                    data-parsley-fileextension="jpg,png,gif,jpeg"
                                    data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" >
                                
                    
                    </div>
                </div>
    
                
    
                <div class="col-12 mb-4 mt-4">    
                    <button type="submit" class="btn-custom btn mr-2 mt-2 mb-2" >
                            Submit
                        </button>
                    </div>
            </div>
        </div>
    </form>
</div>
@endsection


@section('script')
<script>

$(document).ready(function() {
    $('#product_type').change(function(){
        if($(this).val() == 'daily'){
            $('#drow_date_holder').addClass("d-none");
        }else{
            $('#drow_date_holder').removeClass("d-none");
        }
    })
    $('.all-select').click(function(){
        $(this).siblings('.crud-items').prop('checked', this.checked);
    });
    $('.crud-items').click(function(){
        $(this).siblings('.all-select').prop('checked', false);
    });
    $('.all-p').click(function(){
        $(this).siblings('.reader').prop('checked', true);
    });
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