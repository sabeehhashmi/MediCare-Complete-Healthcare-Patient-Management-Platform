@extends('admin.template.layout')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
<style>
    #imagePreview {
        width: 126px;
        height: 114px;
        background-size: cover;
        background-position: center;
        margin-top: 15px;
    }
</style>
@stop
@section('content')
<div class="card mb-5">
    <div class="p-4 pt-5">
        <form id="admin_form" method="post" action="{{route('admin.agents.save')}}" class="registerform" autocomplete="off">
            @csrf
            @if(request()->has('call_center'))
                <input type="hidden" name="call_center_redirect" value="1">
            @endif
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
                            <option value=""></option>
                            @foreach(['1'=> 'Male', '2'=> 'Female', '3'=> 'Other'] as $gender_key => $gender_value)
                            <option {{($gender_key==$gender)?'selected':''}} value="{{$gender_key}}">{{$gender_value}}</option>
                            @endforeach

                        </select>
                        <i class="fi fi-rr-venus-mars"></i>
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
                        <input type="password" class="form-control jqv-input"  @if(!$id) data-jqv-required="true" @endif  id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                        </button>
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Phone Number</label>
                    <div class="position-relative">
                        <input type="text" class="form-control jqv-input"  @if ($phone) value={{$phone}} @endif id="phone" name="phone" placeholder="Enter Phone Number" />
                        <input type="hidden" id="dial_code" @if ($dial_code) value={{$dial_code}} @endif name="dial_code">
                        <!-- <span class="bx bx-phone"></span> -->
                    </div>
                </div>


                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="callcenter">Call Center</label>
                    <div class="position-relative select-custom-icon">

                        @php

                        $callcenter_id=isset($_GET['call_center'])?$_GET['call_center']:$callcenter_id;
                        @endphp
                        <select name="callcenter_id" id="callcenter_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Country">
                            @foreach($callcenters as $callcenter)
                            <option {{($callcenter->id==$callcenter_id)?'selected':''}} value="{{$callcenter->id}}">{{$callcenter->user->name}}</option>
                            @endforeach

                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>





                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Country </label> <a href="#" title="Add more" data-bs-toggle="modal" data-bs-target="#newcountrymodel" title="Click to Add More Country" class="float-end me-2">Add More</a>
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
                    <label class="form-label" for="username"> City / Province </label> <a href="#" title="Add more City / Province" data-bs-toggle="modal" data-bs-target="#newemiratemodel" title="Click to Add More Cities" class="float-end me-2">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select City">
                            @foreach($emirates_list as $emirate)
                            <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                            @endforeach
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>



                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Area </label><a href="#" title="Add more Area" data-bs-toggle="modal" data-bs-target="#neweareamodel" title="Click to Add More Area" class="float-end me-2">Add More</a>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Area">
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
                        <input type="text" class="form-control jqv-input" @if($address) value="{{$address}}" @endif data-jqv-required="true" id="address" name="address"  placeholder="Enter Address" />
                        <span class="bx bx-building"></span>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Upload Photo</label>
                        <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />

                        <div id="imagePreview" style="display: none;"></div>

                        @if($photo ?? null)
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
@endsection


@section('page_script')
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
                    url: "{{ url('admin/get-emirates') }}/" + countryId,
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
                url: "{{ url('admin/get-areas') }}/" + emirateId,
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
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>

<script>
    $("#base-style").DataTable();


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
                // $('#previewLink').hide();
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

      //  initialCountry: '{{INIT_PHONE_C_CODE}}',
       // onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
        //strictMode: true,
        geoIpLookup: "auto",
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
    });
    document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone");

const ph = window.intlTelInput(input, {
//    initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDialCode() {
    var dialCode = ph.getSelectedCountryData().dialCode;
    document.getElementById('dial_code').value = dialCode;
}

input.addEventListener("input", updateDialCode);
input.addEventListener("countrychange", updateDialCode);

// 🔹 Set country in EDIT case
var existingDialCode = "{{ ($dial_code)?$dial_code:'' }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});
       
    setTimeout(function() {
        let phoneValue = input.value;
        if (phoneValue.startsWith('0')) {
            input.value = phoneValue.substring(1);  // Remove the leading zero
        }
    }, 1000);
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
    google.maps.event.addListener(map2, 'click', function(event) {
        updatepostition(event.latLng, "movemarker");
        //drag end event
        usermarker.addListener('dragend', function(event) {
            // alert();
            updatepostition(event.latLng, "movemarker");

        });
    });

    //drag end event
    usermarker.addListener('dragend', function(event) {
        // alert();
        updatepostition(event.latLng);

    });
}
updatepostition = function(position, movemarker) {
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
    }, function(responses) {
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

    map2.addListener('bounds_changed', function() {
        searchBox2.setBounds(map2.getBounds());
    });

    searchBox2.addListener('places_changed', function() {
        var places2 = searchBox2.getPlaces();

        if (places2.length == 0) {
            return;
        }
        $('#txt_location').val(input2.value)

        var bounds2 = new google.maps.LatLngBounds();
        places2.forEach(function(place) {
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
updatepostition = function(position, movemarker) {
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
@include('admin.layouts.masterdata')
@stop
