@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('admin.agents.docotor_save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <input type="hidden" value="{{$agent_id ?? null}}" id="agent_id" name="agent_id">
            <div class="row">
                @if(!$hospital_id)
                <div class="col-12 mb-3">
                    <label class="form-label" for="username">Name of Hospital/Clinic Name/ Dental Care/ Home Care</label>
                    <div class="position-relative select-custom-icon">
                        <select name="hospital_id" id="hospital_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Hospital/Clinic Name/ Dental Care/ Home Care">
                        <option  value=""></option>    
                            @foreach($hospital_name as $hospital)
                                <option {{($hospital->id == $hospital_id) ? "selected" : ""}} value="{{$hospital->id}}">{{$hospital->name_en}}</option>
                            @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                @endif
                @if($hospital_id)
                <input type="hidden" value="{{$hospital_id}}" name="hospital_id">
                @endif

                <div class="col-12 mb-3">
                    <label class="form-label" for="departments">Departments</label>
                    <div class="position-relative select-custom-icon">
                        <select name="departments[]" id="departments" class="select2-single jqv-input" multiple data-jqv-required="true" role="select2" data-placeholder="Select Departments">
                            @if(count($department_list))
                            @foreach($department_list as $department)
                                <option {{in_array($department->id, $selected_departments) ? "selected" : ""}} value="{{$department->id}}">{{$department->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-3">
                    <label class="form-label" for="username">First Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="first_name" name="first_name" value="{{$first_name}}" placeholder="First Name" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Last Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="last_name" name="last_name" value="{{$last_name}}" placeholder="Last Name" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>
              
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Qualification</label>
                    <div class="position-relative select-custom-icon">
                        <select name="qualification[]" id="qualification" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Qualification">
                            @foreach($qualification as $qualification)
             
                @php
                    $selected = is_array($qualification_id) && in_array($qualification->id, $qualification_id) ? 'selected' : '' ;
                @endphp

                <option value="{{ $qualification->id }}" {{ $selected}}>{{ $qualification->title }}</option>
                @endforeach
                           
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
             
             
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Specialty</label>
                    <div class="position-relative select-custom-icon">
                        <select name="specialty[]" id="specialty" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Specialty">
                            @foreach($specialty as $specialty)

                               
                          
                                @php
                    $selected = is_array($speciality_id) && in_array($specialty->id, $speciality_id) ? 'selected' : '' ;
                @endphp
                          
                          
                <option value="{{ $specialty->id }}" {{$selected }}>{{ $specialty->name_en }}</option>           
                          
                          
                                @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Special Intrests</label>
                    <div class="position-relative select-custom-icon">
                        <select name="special_interest[]" id="special_interest" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Special Interest">
                            @foreach($special_interest as $special_interest)
           
                              
                                @php
                    $selected = is_array($special_intrest_id) && in_array($special_interest->id, $special_intrest_id) ? 'selected' : '' ;
                @endphp

              
                <option value="{{ $special_interest->id }}" {{ $selected }}>{{ $special_interest->title }}</option>
                              
                              
                                @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="experiences">Years of Experiences</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="experiences" name="experiences" value="{{$experiences}}" placeholder="Years of Experiences" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country of Origin</label>
                    <div class="position-relative select-custom-icon">
                        <select name="country" id="country" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Country">
                            @foreach($country_list as $country)
                                <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>



                <!-- <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username"> City / Province</label>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select City">
                            @foreach($emirates_list as $emirate)
                                <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div> -->

                <!-- <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Area</label>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Area">
                            @foreach($area_list as $area)
                                <option {{($area->id==$area_id)?'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div> -->

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Language Spoken</label>
                    <div class="position-relative select-custom-icon">
                        <select name="language_spoken_id[]" id="language_spoken_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" multiple data-placeholder="Select Language Spoken">
                        @foreach($language_spoken as $language_spoken)
                               
                          
                               @php
                                  $selected = is_array($language_spoken_id) && in_array($language_spoken->id, $language_spoken_id) ? 'selected' : ''  ;
                             @endphp
                               

                               <option value="{{ $language_spoken->id }}" {{ $selected}}>{{ $language_spoken->title }}</option>
                               @endforeach
                            
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
                
                    <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="gender" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Gender">
                        <option value="">Select gender</option>
                                <option {{($doctor->user->gender ?? null) ==1?'selected':''}} value="1">Male</option>
                                <option {{($doctor->user->gender ?? null) ==2?'selected':''}} value="2">Female</option>
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>
              

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="phone">Clinic Number</label>
                    <div class="position-relative">
                        <input type="number" class="form-control" id="phone" value="{{$doctor->user->phone ?? null}}" name="phone" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code" name="dial_code" value="{{$doctor->user->dial_code ?? null}}">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" data-jqv-required="true" {{($doctor->user->email ?? null) ? 'readonly' : 'false'}} id="email" name="email" value="{{old('email', $doctor->user->email ?? null)}}" placeholder="Enter Address" />
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                        <span class="bx bx-lock-alt"></span>
                        <input type="password" class="form-control" {{!$id ? 'data-jqv-required' : ''}} id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>
               
                
                <div class="col-12 mb-3">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Profile</label>
                        <textarea class="form-control" id="ckeditor-classic" name="profile_bio" row="5">{{$doctor->profile_desciription ?? null}}</textarea>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Direct Contact Number for Appointment (Optional)</label>
                    <div class="position-relative">
                        <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{$doctor->appointment_dial_code ?? null}}" >
                        <input type="text" class="form-control" id="phone1" name="direct_phone" value="{{$doctor->appointment_phone ?? null}}" placeholder="Enter Phone Number" />
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($user->user_img_url ?? null)
                        <a id="previewLink" href="{{$user->user_img_url}}" target="_blank">View Image</a>
                        @endif
                    </div>

                </div>
                <div class="col-12 d-flex">
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
                            window.location.href = res['oData']['redirect'];
                        },2500);

                    }

                },
                error: function (e) {
                    form_in_progress = 0;
                    App.loading(false);
                    $form.find('[type="submit"]').prop("disabled",false);
                    $form.find('[type="submit"]').text("Save");
                    console.log(e);
                    App.alert( "Network error please try again", 'Oops!','error');
                }
            });
         });
    });
    
});
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>

<script>

const input = document.querySelector("#phone");
        const ph=window.intlTelInput(input, {
            
           // initialCountry: '{{INIT_PHONE_C_CODE}}',
          //  onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
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
        const input1 = document.querySelector("#phone1");
        const iti = window.intlTelInput(input1, {
            
       //     initialCountry: '{{INIT_PHONE_C_CODE}}',
        //    onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
            //strictMode: true,
            geoIpLookup:"auto",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input1.addEventListener("input", function () {
            // Get the selected country's dial code
            var dialCode = iti.getSelectedCountryData().dialCode;
            $('#direct_dial_code').val(dialCode);

            // If you want to use the dial code somewhere else, you can do so here
        });
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


<script>
    $(document).ready(function() {

        function loadDepartments(hospital_id, selectedEmirateId = ''){
            if (hospital_id) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-hospital-departments') }}/" + hospital_id,
                    success: function (res) {
                        if (res) {
                            $('#departments').empty();
                            // $('#departments').append('<option value="">Select Departments</option>');
                            $.each(res, function (index, department) {
                                $('#departments').append('<option value="' + department.id+'">' + department.title + '</option>');
                            });
                            $('#departments').val(selectedEmirateId).trigger('change');
                            $('#departments').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });
            } else {
                $('#emirate_id').empty();
                $('#emirate_id').append('<option value="">Select Departments</option>');
            }
        }

    // loadDepartments($('#hospital_id').val(), {{ $hospital->emirate_id ?? null }});

    $('#hospital_id').on("change", function () {
        loadDepartments($(this).val())
    });
  
   $("#upload_imgs").change(function(){
      // check if fileArr length is greater than 0
       if (fileArr.length > 0) fileArr = [];
     
        $('#image_preview').html("");
        var total_file = document.getElementById("upload_imgs").files;
        
        if(total_file.length > 3){
            App.alert("You can select max of 3 images");
            return false;
        }
        if (!total_file.length) return;
        for (var i = 0; i < total_file.length; i++) {
        //   if (total_file[i].size > 1048576) {
        //     return false;
        //   } else {
            fileArr.push(total_file[i]);
            console.log(fileArr);
            $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(total_file[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'><button id='action-icon' value='img-div"+i+"' class='btn btn-danger btn-icon' role='"+total_file[i].name+"'><i class='fa fa-trash'></i></button></div></div>");
          //}
        }
   });
  
  $('body').on('click', '#action-icon', function(evt){
      var divName = this.value;
      var fileName = $(this).attr('role');
      $(`#${divName}`).remove();
    
      for (var i = 0; i < fileArr.length; i++) {
        if (fileArr[i].name === fileName) {
          fileArr.splice(i, 1);
        }
      }
    document.getElementById('images').files = FileListItem(fileArr);
      evt.preventDefault();
  });
  
   function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }
});
</script>
@stop