@include('callcenter.layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">

<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('callcenter.clinics.saveInsurance')}}" enctype="multipart/form-data" class="custom-form">
        <div class="card-body">
            <div class="row insu-form-row">
                    @csrf()
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="hospital_id" value="{{$hospital_id}}">
                    <div class="col-lg-6 mb-4">
                        <label class="form-label" for="department">Insurance Type</label>
                        <div class="position-relative select-custom-icon">
                            <select name="insurance_id" id="insurance_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Insurance">
                                <option value=""></option>
                                @foreach($insurances as $insurance)
                                    <option {{$insurance->id == ($row->insurance_id ?? null) ? 'selected' : ''}} value="{{$insurance->id}}">{{$insurance->title}}</option>
                                @endforeach
                                
                            </select>
                            <i class="bx bx-navigation"></i>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <label class="form-label" for="bs-validation-insurance">Sub Insurance </label>
                        <select name="sub_insurance_id[]" multiple id="sub_insurance_id" class="form-control jqv-inuput" role="select2">
                            <option></option>
                            @foreach($sub_insurance_list as $item)
                            <option {{($row->sub_insurance_id ??  null) == $item->id ? 'selected':''}} value="{{$item->id}}">{{$item->title}}</option>
                            @endforeach
                        </select>
                    </div>
               
                    <div class="col-12 d-flex">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                        <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                    </div>
            </div>
        </div>
    </form>
</div>
@include('callcenter.layouts.footer')

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>

<script>

$(document).ready(function() {
    
    function loadSubInsurance(parentId, selectedId = ''){
        if (parentId) {
            $.ajax({
                type: "GET",
                url: "{{ url('callcenter/get-subInsurence') }}/" + parentId,
                success: function (res) {
                    if (res) {
                        $('#sub_insurance_id').empty();
                        // $('#departments').append('<option value="">Select Departments</option>');
                        $.each(res, function (index, data) {
                            $('#sub_insurance_id').append('<option value="' + data.id+'">' + data.title + '</option>');
                        });
                        $('#sub_insurance_id').val(selectedId).trigger('change');
                        $('#sub_insurance_id').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching Sub Insurance:', error);
                }
            });
        } else {
            $('#sub_insurance_id').empty();
            $('#sub_insurance_id').append('<option value=""></option>');
        }
        }

        // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

        $('#insurance_id').on("change", function () {
            loadSubInsurance($(this).val(), {{$patient->insurance_id ?? null}})
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

    const input = document.querySelector("#phone");
    const ph=window.intlTelInput(input, {
        
    //    initialCountry: '{{INIT_PHONE_C_CODE}}',
        //strictMode: true,
        geoIpLookup:"auto",
        separateDialCode: true,
        
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input.addEventListener("input", function () {
        // Get the selected country's dial code
        var dialCode = ph.getSelectedCountryData().dialCode;
        $('#dial_code').val(dialCode);

        // If you want to use the dial code somewhere else, you can do so here
    });
});
</script>
