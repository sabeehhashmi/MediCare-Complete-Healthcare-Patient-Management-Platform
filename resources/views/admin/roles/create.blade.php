@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('admin.user_roles.submit')}}" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 custom-form">
                    <input type = "hidden" name="is_admin_role" value = "1"> 
                    @csrf()
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="mb-3">
                    <label class="form-label" for="bs-validation-name">Name<span class="text-danger">*</span> </label>
                    <input
                        type="text"
                        class="form-control jqv-input" data-jqv-required="true"
                        id="role"
                        name="role"
                        value="{{$role_name}}"
                    />
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 custom-form">
                    <label class="form-label">Role Status<span class="text-danger">*</span></label>
                    <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option @if($status=='1') selected @endif value="1">Active</option>
                        <option @if($status=='0') selected @endif value="0">InActive</option>
                    </select>
                </div>
    
                <di class="col-12">
                    <table class="table table-stripped table-condensed">
                        <thead>
                            <th>#</th>
                            <th>Module</th>
                            <th>Operations</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @php $c=0; @endphp
                            @foreach($site_modules as $moduleKey=>$moduleValue)
                                @php $c++; @endphp
                                <tr>
                                    <td>{{$c}}</td>
                                    <td>{{$moduleValue['name']}}</td>
                                    <td>
                                        <input type="checkbox" class="all-select" style="width: fit-content;" value="1"> <label style="display: inline-block; margin-right: 20px;" class="mb-0" for="">All</label>
                                        @foreach($moduleValue['operations'] as $operationKey)
                                        <!-- <div class="mt-1 mb-1"> -->
                                            @php
                                                $options = json_decode($permissions[$moduleKey]??'');
                                            @endphp
                                             <input type="checkbox" style="width: fit-content;" class="crud-items {{($operationKey=='r')?'reader':'all-p'}}" name="permission[{{$moduleKey}}][]" @if(in_array($operationKey,$options??[])) checked @endif value="{{$operationKey}}"> <label style="display: inline-block; margin-right: 20px;" class="mb-0" for="">{{$operations[$operationKey]}}</label>
                                        
                                            <!-- </div> -->
                                        @endforeach
    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="col-12 d-flex mt-3">
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