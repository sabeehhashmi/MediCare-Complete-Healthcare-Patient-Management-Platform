@include('callcenter.layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">

<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('callcenter.hospitals.save')}}" class="registerform"
              autocomplete="off">
            @csrf
            <input type="hidden" value="{{$id}}" name="id">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <label class="form-label" for="username">Hospital Name</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" id="name_en"
                               name="name_en" value="{{old('name_en', $hospital->name_en ?? null)}}"
                               placeholder="Hospital Name"/>
                        <span class="bx bx-building"></span>
                    </div>
                </div>
                


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country</label>
                    <div class="position-relative select-custom-icon">
                        <select name="country" id="country" class="select2-single jqv-input" data-jqv-required="true"
                                role="select2" data-placeholder="Select Country">
                            @foreach($country_list as $country)
                                <option
                                    {{($country_id == $country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">City / Province</label>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate_id" class="select2-single jqv-input"
                                data-jqv-required="true" role="select2" data-placeholder="Select City">
                            @foreach($emirates_list as $emirate)
                                <option
                                    {{($hospital->emirate_id ?? null) == $emirate_id ? 'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Area</label>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single jqv-input" data-jqv-required="true"
                                role="select2" data-placeholder="Select Area">
                            @foreach($area_list as $area)
                                <option
                                    {{$area->id == ($hospital->area_id ?? null) ? 'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label" for="username">Address Of Organization</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" data-jqv-required="true" name="address"
                               value="{{old('address', $hospital->address??null)}}"
                               placeholder="Enter Address Of Organization"/>
                        <span class="bx bx-building"></span>

                    </div>

                </div>

                <div class="col-12 mb-3">
                    <label class="form-label" for="username">Location</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input autocomplete" data-jqv-required="true"
                               id="txt_location" name="txt_location"
                               value="{{old('txt_locations', $hospital->txt_location??null)}}"
                               placeholder="673C+W8V - Dubai - United Arab Emirates"/>
                        <span class="bx bx-building"></span>
                        <input type="hidden" id="location" name="location">
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map_canvas" style="height: 200px;width:100%;"></div>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Hospital Main Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control jqv-input" id="phone" name="phone"
                               value="{{old('phone', $hospital->user->phone ?? null)}}"
                               placeholder="Enter Phone Number"/>
                        <input type="hidden" id="dial_code" name="dial_code"
                               value="{{old('dial_code', $hospital->user->dial_code ?? null)}}">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Email Address</label>
                    <div class="position-relative input-custom-icon">
                        <input type="email" class="form-control jqv-input" data-jqv-required="true"
                               {{($hospital->user->email ?? null) ? 'readonly' : 'false'}} id="email" name="email"
                               value="{{old('email', $hospital->user->email ?? null)}}" placeholder="Enter Address"/>
                        <span class="bx bx-envelope"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                        <span class="bx bx-lock-alt"></span>
                        <input type="password" class="form-control jqv-input" @if(!$id) data-jqv-required="true"
                               @endif id="password-input" name="password" placeholder="Enter password"
                               autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0"
                                id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Website</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" class="form-control jqv-input" id="website"
                               value="{{old('website', $hospital->website ?? null)}}" name="website"
                               placeholder="Enter Website"/>
                        <span class="bx bx-globe"></span>
                    </div>
                </div>

                {{-- <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Direct Call for Appointment Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control" id="phone1" name="direct_phone"
                               value="{{old('direct_phone', $appointment_phone ?? null)}}"
                               placeholder="Enter Direct Call for Appointment Number"/>
                        <input type="hidden" id="direct_dial_code" name="direct_dial_code"
                               value="{{old('direct_dial_code', $hospital->appointment_dial_code ?? null)}}">

                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div> --}}

                <div class="col-12 mb-4">
                    <div class="custom-textarea">
                        <label class="form-label" for="username">Hospital Profile</label>
                        <textarea class="form-control" id="ckeditor-classic" name="profile_bio"
                                  row="5">{{old('profile_description', $hospital->profile_description ?? null)}}</textarea>
                    </div>
                </div>
               
                <div class="col-12 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Hospital Images <span>(Allowed 3 Photos with Dim 750px X 750px)</span></label>
                        <input class="form-control" type="file" id="upload_imgs" accept="image/jpg, image/jpeg"
                               multiple/>
                    </div>
                    <input type="hidden" name="remove_images" id="remove_images">
                    <div id="image_preview" class="row">
                        @foreach($hospital->images ?? [] as $hsk => $hospital_image)
                            <div class='old-image img-div col-lg-4 col-md-6 mb-3 mt-2'
                                 id='existing-img-div-{{$hospital_image->image_url}}'><img
                                    src='{{$hospital_image->image_url}}' class='img-fluid w-100'
                                    title='Existing Image {{$hospital_image->image_url}}'>
                                <div class='middle'>
                                    <button type="button" id='delete-image' value='{{$hospital_image->id}}'
                                            class='btn btn-danger btn-icon' role='existing_image'><i
                                            class='fa fa-trash'></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary waves-effect waves-light"
                            type="submit">{{($id!='')?'Update':'Save'}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('callcenter.layouts.footer')
<script>
    let fileArr = [];
    $(document).ready(function () {

        App.initFormView();
        let form_in_progress = 0;

        $('body').off('submit', '#admin_form');
        $('body').on('submit', '#admin_form', function (e) {
            e.preventDefault();
            var validation = $.Deferred();
            var $form = $(this);
            var formData = new FormData(this);
            let i = 0;
            $.each(fileArr, function(i, file) {
                formData.append('images[' + i + ']', file);
            });


            $form.validate({
                rules: {},
                errorElement: 'div',
                errorPlacement: function (error, element) {
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

            validation.done(function () {
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
                    success: function (res) {
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
                                jQuery.each(res['errors'], function (e_field, e_message) {
                                    if (e_message != '') {
                                        $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                        $('<div class="error">' + e_message + '</div>').insertAfter($('[name="' + e_field + '"]').eq(0));
                                        if (error_index == 0) {
                                            error_def.resolve();
                                        }
                                        error_index++;
                                    }
                                });
                                error_def.done(function () {
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
                            setTimeout(function () {
                                window.location.href = res['oData']['redirect'];
                            }, 2500);

                        }

                    },
                    error: function (e) {
                        form_in_progress = 0;
                        App.loading(false);
                        $form.find('[type="submit"]').prop("disabled", false);
                        $form.find('[type="submit"]').text("Save");
                        console.log(e, 'e');
                        App.alert("Network error please try again", 'Oops!', 'error');
                    }
                });
            });
        });

        function loadArea(emirateId, selectedAreaId = '') {
            if (emirateId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-areas') }}/" + emirateId,
                    success: function (res) {
                        if (res) {
                            $('#area_id').empty();
                            $('#area_id').append('<option value="">Select Area</option>');
                            $.each(res, function (index, area) {
                                $('#area_id').append('<option value="' + area.id + '">' + area.name_en + '</option>');
                            });


                            $('#area_id').val(selectedAreaId).trigger('change');
                            $('#area_id').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching area:', error);
                    }
                });
            } else {
                $('#area_id').empty();
                $('#area_id').append('<option value="">Select Area</option>');
            }
        }

        // $('body').off("change",'#country');
        function loadEmirates(countryId, selectedEmirateId = '') {
            if (countryId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('callcenter/get-emirates') }}/" + countryId,
                    success: function (res) {
                        if (res) {
                            $('#emirate_id').empty();
                            $('#emirate_id').append('<option value="">Select Emirate</option>');
                            $.each(res, function (index, emirate) {
                                $('#emirate_id').append('<option value="' + emirate.id + '">' + emirate.name_en + '</option>');
                            });
                            $('#emirate_id').val(selectedEmirateId).trigger('change');
                            $('#emirate_id').select2(); // Reinitialize select2
                            if (selectedEmirateId) {
                                loadArea(selectedEmirateId, {{ $hospital->area_id ?? null }});
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching emirates:', error);
                    }
                });
            } else {
                $('#emirate_id').empty();
                $('#emirate_id').append('<option value="">Select Emirate</option>');
            }
        }

        loadEmirates($('#country').val(), {{ $hospital->emirate_id ?? null }});

        $('#country').on("change", function () {
            loadEmirates($(this).val())
        });

        $('#emirate_id').on("change", function () {
            loadArea($(this).val());
        });
    });
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
<script src="{{ URL::asset('admin-assets/assets/js/pages/pass-addon.init.js') }}"></script>
<script>
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
            reader.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageUpload").change(function () {
        readURL(this);
    });
</script>


<script>
    $(document).ready(function () {
        {{--var existingImages = @json($hospital->images ?? null); // Convert PHP array to JSON format--}}

        {{--function previewExistingImages() {--}}
        {{--    // if (existingImages) {--}}
        {{--    //     $('#image_preview').html("");--}}
        {{--    //     for (var i = 0; i < existingImages.length; i++) {--}}
        {{--    //         $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='existing-img-div" + existingImages[i].image_url + "'><img src='" + existingImages[i].image_url + "' class='img-fluid w-100' title='Existing Image " + (i + 1) + "'><div class='middle'><button id='action-icon' value='" + existingImages[i].id + "' class='btn btn-danger btn-icon remove-image' role='existing_image'><i class='fa fa-trash'></i></button></div></div>");--}}
        {{--    //     }--}}
        {{--    // }--}}
        {{--}--}}

        {{--previewExistingImages();--}}

        $("body").on('change', '#upload_imgs', function () {
            if (fileArr.length > 0) fileArr = [];

            var total_file = document.getElementById("upload_imgs").files;
            var oldImageCount = $('#image_preview .img-div').length;

            if (!total_file.length) return;

            if (oldImageCount + total_file.length > 3) {
                App.alert("You can select max of 3 images");
                $('#upload_imgs').val('');
                return false;
            }

            for (var i = 0; i < total_file.length; i++) {
                fileArr.push(total_file[i]);
                $('#image_preview').append(
                    "<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div" + i + "'>" +
                    "<img src='" + URL.createObjectURL(total_file[i]) + "' class='img-fluid w-100' title='" + total_file[i].name + "'>" +
                    "<div class='middle'>" +
                    "<button type='button' class='btn btn-danger btn-icon delete-uploaded-image' data-index='" + i + "'><i class='fa fa-trash'></i></button>" +
                    "</div>" +
                    "</div>"
                );
            }
        });

        $("body").on('click', '.delete-uploaded-image', function () {
            var index = $(this).data('index');

            // Remove the image div
            $(this).closest('.img-div').remove();

            // Remove the file from the fileArr
            fileArr.splice(index, 1);

            // Update the input field value to remove the deleted file
            var input = document.getElementById('upload_imgs');
            var dt = new DataTransfer();
            fileArr.forEach(function (file) {
                dt.items.add(file);
            });
            input.files = dt.files;
        });

        $('body').on('click', '#delete-image', function (evt) {
            var currentValue = $('#remove_images').val();

            var value = this.value;

            if (currentValue) {
                currentValue += ',' + value;
            } else {
                currentValue = value;
            }

            $('#remove_images').val(currentValue);

            $(this).parents('.old-image').remove();
        });

        function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }

        // $('body').on('click', '#action-icon', function(evt){
        //     var divName = this.value;
        //     var fileName = $(this).attr('role');
        //     $(`#${divName}`).remove();
        //
        //     for (var i = 0; i < fileArr.length; i++) {
        //       if (fileArr[i].name === fileName) {
        //         fileArr.splice(i, 1);
        //       }
        //     }
        //   document.getElementById('images').files = FileListItem(fileArr);
        //     evt.preventDefault();
        // });
        //
        //  function FileListItem(file) {
        //           file = [].slice.call(Array.isArray(file) ? file : arguments)
        //           for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
        //           if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
        //           for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
        //           return b.files
        //       }
    });


    const input = document.querySelector("#phone");
    const ph = window.intlTelInput(input, {

    //    initialCountry: '{{INIT_PHONE_C_CODE}}',
       // onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
        //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,

        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input.addEventListener("input", function (e) {
        let value = e.target.value.replace(/[^0-9]/g, '');

        // Prevent starting with 0
        if (value.startsWith('0')) {
            value = value.substring(1);
        }

        // Update the input field with the cleaned value
        e.target.value = value;
        // Get the selected country's dial code
        var dialCode = ph.getSelectedCountryData().dialCode;
        $('#dial_code').val(dialCode);

        // If you want to use the dial code somewhere else, you can do so here
    });

    const input1 = document.querySelector("#phone1");
    const iti = window.intlTelInput(input1, {

    //    initialCountry: '{{INIT_PHONE_C_CODE}}',
       // onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
        //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    input1.addEventListener("input", function (e) {
        let value = e.target.value.replace(/[^0-9]/g, '');

        // Prevent starting with 0
        if (value.startsWith('0')) {
            value = value.substring(1);
        }

        // Update the input field with the cleaned value
        e.target.value = value;
        // Get the selected country's dial code
        var dialCode = iti.getSelectedCountryData().dialCode;
        $('#direct_dial_code').val(dialCode);

        // If you want to use the dial code somewhere else, you can do so here
    });
</script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}&v=weekly&libraries=places">
</script>
<script>

    var currentLat = <?php echo (isset($datamain) && $datamain->latitude) ? $datamain->latitude : 25.204819 ?>;
    var currentLong = <?php echo (isset($datamain) && $datamain->longitude) ? $datamain->longitude : 55.270931 ?>;
    $("#location").val(currentLat + "," + currentLong);

    currentlocation = {
        "lat": currentLat,
        "lng": currentLong,
    };
    initMap();
    initAutocomplete();

    function initMap() {
        map2 = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: currentlocation.lat,
                lng: currentlocation.lng
            },
            zoom: 14,
            gestureHandling: 'greedy',
            mapTypeControl: false,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            streetViewControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM
            },
        });

        geocoder = new google.maps.Geocoder();

        // geocoder2 = new google.maps.Geocoder;
        usermarker = new google.maps.Marker({
            position: {
                lat: currentlocation.lat,
                lng: currentlocation.lng
            },
            map: map2,
            draggable: true,

            animation: google.maps.Animation.BOUNCE
        });


        //map click
        google.maps.event.addListener(map2, 'click', function (event) {
            updatepostition(event.latLng, "movemarker");
            //drag end event
            usermarker.addListener('dragend', function (event) {
                // alert();
                updatepostition(event.latLng, "movemarker");

            });
        });

        //drag end event
        usermarker.addListener('dragend', function (event) {
            // alert();
            updatepostition(event.latLng);

        });
    }

    updatepostition = function (position, movemarker) {
        geocodePosition(position);
        usermarker.setPosition(position);
        map2.panTo(position);
        map2.setZoom(15);
        let createLatLong = position.lat() + "," + position.lng();
        console.log("Address Lat/long=" + createLatLong);
        $("#location").val(createLatLong);
    }

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function (responses) {
            if (responses && responses.length > 0) {
                usermarker.formatted_address = responses[0].formatted_address;
            } else {
                usermarker.formatted_address = 'Cannot determine address at this location.';
            }
            $('#txt_location').val(usermarker.formatted_address);
        });
    }

    function initAutocomplete() {
        // Create the search box and link it to the UI element.
        var input2 = document.getElementById('txt_location');
        var searchBox2 = new google.maps.places.SearchBox(input2);

        map2.addListener('bounds_changed', function () {
            searchBox2.setBounds(map2.getBounds());
        });

        searchBox2.addListener('places_changed', function () {
            var places2 = searchBox2.getPlaces();

            if (places2.length == 0) {
                return;
            }
            $('#txt_location').val(input2.value)

            var bounds2 = new google.maps.LatLngBounds();
            places2.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                updatepostition(place.geometry.location);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds2.union(place.geometry.viewport);
                } else {
                    bounds2.extend(place.geometry.location);
                }
            });
            map2.fitBounds(bounds2);
        });
    }

    updatepostition = function (position, movemarker) {
        console.log(position);
        geocodePosition(position);
        usermarker.setPosition(position);
        map2.panTo(position);
        map2.setZoom(15);
        let createLatLong = position.lat() + "," + position.lng();
        // console.log("Address Lat/long="+createLatLong);
        $("#location").val(createLatLong);
    }
</script>
<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> -->
