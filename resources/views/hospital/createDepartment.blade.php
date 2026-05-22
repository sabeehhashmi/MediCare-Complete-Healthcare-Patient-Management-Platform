@include('hospital.layouts.header')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
<div class="position-relative mb-5">
    <div class="card">
        <div class="card-body">
            <form id="admin_form" method="post" action="{{route('hospital.saveDepartment')}}" enctype="multipart/form-data" class="custom-form">
            <div class="card-body">
                <div class="row">
                        @csrf()
                        <input type="hidden" name="id" value="{{$id}}">
                        <input type="hidden" name="hospital_id" value="{{$hospital_id}}">
                        <div class="col-lg-6 mb-4">
                        <label class="form-label" for="department">Departments</label>
                        <div class="position-relative select-custom-icon">
                            <select name="department" id="department" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Departments">
                                <option value=""></option>
                                @foreach($departments as $department)
                                    <option {{$department->id == ($row->department_id ?? null) ? 'selected' : ''}} value="{{$department->id}}">{{$department->title}}</option>
                                @endforeach
                                
                            </select>
                            <i class="bx bx-navigation"></i>
                        </div>
                    </div>
                        <div class="col-lg-6 mb-4">
                        <label class="form-label" for="bs-validation-name">Department Manager<span class="text-danger">*</span> </label>
                        <input
                            type="text"
                            class="form-control jqv-input" data-jqv-required="true"
                            id="manager_name"
                            placeholder="Enter the Department Manager Name"
                            name="manager_name"
                            value="{{$row->manager_name ?? null}}"
                        />
                        </div>
                        
                    
                    <div class="col-lg-6 mb-4">
                        <label class="form-label" for="username">Email Address</label>
                        <div class="position-relative input-custom-icon">
                            <input type="email" class="form-control jqv-input" data-jqv-required="true" id="email" name="email" value="{{old('email', $row->email ?? null)}}" placeholder="Enter Email Address" />
                            <!-- <span class="bx bx-envelope"></span> -->
                        </div>
                    </div>
        
                
                    <div class="col-12 mb-4">    
                        <button type="submit" class="btn btn-primary waves-effect waves-light" >
                                Submit
                            </button>
                        </div>
                </div>
            </div>
        </form>

        </div>
    </div>
</div>
@include('hospital.layouts.footer')

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>

<script>
  

    
    $(document).ready(function() {
        let form_in_progress=0;
        $('body').on('submit', '#admin_form', function(e) {
        e.preventDefault();
        var validation = $.Deferred();
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
                dataType:'html',
                success: function (res) {
                    res = JSON.parse(res);
                    console.log(res['status']);
                    form_in_progress = 0;
                    // App.loading(false);
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
                    // loading(false);
                    console.log(e);
                    App.alert( "Network error please try again", 'Oops!','error');
                }
            });
        });
    });

</script>