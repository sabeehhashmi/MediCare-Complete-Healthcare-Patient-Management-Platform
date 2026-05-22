@include('callcenter.layouts.header')
<style>
    #imagePreview {
        width: 126px;
        height: 114px;
        background-size: cover;
        background-position: center;
        margin-top: 15px;
    }
</style>

<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('callcenter.agents.save')}}" class="registerform" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Agent Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="name" name="name" value="{{$name}}" placeholder="Agent Name" />
                        <span class="bx bx-user"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="gender">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="gender" class="select2-single jqv-input" require data-jqv-required="true" role="select2" data-placeholder="Select Gender">
                            <!-- @foreach(['male'=> 'Male', 'female'=> 'Female'] as $gender_key => $gender_value)
                            <option {{($gender_key==$gender)?'selected':''}} value="{{$gender_key}}">{{$gender_value}}</option>
                            @endforeach -->
                            <option></option>
                            <option {{($gender=='1')?'selected':''}} value="1">Male</option>
                            <option {{($gender=='2')?'selected':''}} value="2">Female</option>
                            <option {{($gender=='3')?'selected':''}} value="3">Other</option>

                        </select>
                        <i class="bx bx-male" style="margin-top: 2px;"></i>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" data-jqv-required="true" id="email" @if ($email) value={{$email}} @endif name="email" placeholder="Enter Address" />
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="password">Password</label>
                    <!-- <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                <span class="bx bx-lock-alt"></span>
                                                <input type="password" class="form-control" id="password-input" name="password" placeholder="Enter password">
                                                <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                </button>
                                            </div> -->

                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                        <span class="bx bx-lock-alt"></span>
                        <input type="password" class="form-control jqv-input" @if(!$id) data-jqv-required="true" @endif id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Phone Number</label>
                    <div class="position-relative">
                        <input type="tex" class="form-control jqv-input"  data-jqv-required="true" @if ($phone) value={{$phone}} @endif id="phone" name="phone" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code" @if ($dial_code) value={{$dial_code}} @endif name="dial_code">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>




                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country</label>
                    <div class="position-relative select-custom-icon">
                        <select name="country_id" id="country" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Country">
                            <option value="0" selected>Select Country</option>
                            @foreach($country_list as $country)
                            <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">City / Province</label>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select City">
                            <option></option>
                            @foreach($emirates_list as $emirate)
                            <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>



                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Area</label>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Area">
                        <option></option>
                            @foreach($area_list as $area)
                            <option {{($area->id==$area_id)?'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" @if ($address) value="{{$address}}" @endif data-jqv-required="true" id="address" name="address" placeholder="Enter Address" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <!-- <div id="imagePreview" style="display: none;"></div> -->
                        @if ($photo)
                        <a id="previewLink" href="{{$photo}}" target="_blank">View Image</a>
                        @endif
                    </div>

                </div>

                <div class="col-12 d-flex">
                    <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) != '')?'Update':'Save'}}</button>
                    <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                </div>

            </div>
        </form>
    </div>
</div>
@include('callcenter.layouts.footer')
<script>
    let fileArr = [];
    $(document).ready(function() {
        if ("{{ $emirate_id }}") {
            loadEmirates($('#country').val(), "{{ $emirate_id }}")
        }
        App.initFormView();
        let form_in_progress = 0;

        $('body').off('submit', '#admin_form');
        $('body').on('submit', '#admin_form', function(e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            var formData = new FormData(this);
            let i = 0;
            $.each(fileArr, function(k, v) {
                formData.append('images[' + i + ']', v);
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

            if ($form.valid()) {
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
                $form.find('[type="submit"]').prop("disabled", true);
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
                    dataType: 'html',
                    success: function(res) {
                        res = JSON.parse(res);
                        console.log(res['status']);
                        form_in_progress = 0;
                        App.loading(false);
                        if (res['status'] == 0) {
                            $form.find('[type="submit"]').prop("disabled", false);
                            $form.find('[type="submit"]').text("Save");
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
                        $form.find('[type="submit"]').prop("disabled", false);
                        $form.find('[type="submit"]').text("Save");
                        console.log(e);
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            });
        });


        function emptyEmirates() {
            $('#emirate_id').empty();
            $('#emirate_id').append('<option value="">Select Emirate</option>');
        }

        function emptyArea() {
            $('#area_id').empty();
            $('#area_id').append('<option value="">Select Area</option>');
        }

        $('body').off("change", '#country');
        $('body').on("change", '#country', function() {
            loadEmirates($(this).val())
        });

        function loadEmirates(id, selected_id = '') {
            var countryId = id;
            if (countryId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-emirates') }}/" + countryId,
                    success: function(res) {
                        if (res) {
                            emptyEmirates();
                            emptyArea();
                            $.each(res, function(index, emirate) {
                                let selected = '';
                                if (emirate.id == selected_id) {
                                    selected = 'selected';
                                    if ("{{ $area_id }}") {
                                        loadAreas(emirate.id, "{{ $area_id }}")
                                    }
                                }
                                $('#emirate_id').append('<option ' + selected + ' value="' + emirate.id + '">' + emirate.name_en + '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching emirates:', error);
                    }
                });
            } else {
                emptyEmirates();
                emptyArea()
            }
        }

        function loadAreas(emirateId, selected_id = '') {
            $.ajax({
                type: "GET",
                url: "{{ url('callcenter/get-areas') }}/" + emirateId,
                success: function(res) {
                    if (res) {
                        emptyArea();
                        $.each(res, function(index, area) {
                            let selected = '';
                            if (area.id == selected_id) {
                                selected = 'selected';
                            }
                            $('#area_id').append('<option ' + selected + ' value="' + area.id + '">' + area.name_en + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching area:', error);
                }
            });
        }

        $('body').off("change", '#emirate_id');
        $('body').on("change", '#emirate_id', function() {
            var emirateId = $(this).val();
            if (emirateId) {
                loadAreas(emirateId)
            } else {
                emptyArea();
            }
        });
    });
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/pages/pass-addon.init.js') }}"></script>

<script>
    $("#base-style").DataTable();


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
                $('#previewLink').hide();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>


<script>

    function getCountryCodeByDialCode(dialCode) {
        const countryData = window.intlTelInputGlobals.getCountryData();
        for (let i = 0; i < countryData.length; i++) {
            if (countryData[i].dialCode === dialCode) {
                return countryData[i].iso2;
            }
        }
        return "AE"; // Return "AE" if no country matches the dial code
    }

    // Get the intial country for the phone based on the dial code
    const dialCode = $('#dial_code').val();
    const countryIsoCode = getCountryCodeByDialCode(dialCode);


    const input = document.querySelector("#phone");
    const ph = window.intlTelInput(input, {

    //    initialCountry: '{{INIT_PHONE_C_CODE}}',
      //  onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
        //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,

        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input.addEventListener("input", function() {
        // Get the selected country's dial code
        var dialCode = ph.getSelectedCountryData().dialCode;
        $('#dial_code').val(dialCode);

        // If you want to use the dial code somewhere else, you can do so here
    });
</script>
