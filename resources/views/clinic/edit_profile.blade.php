@include('clinic.layouts.header')
<div class="position-relative mb-5">
    <div class="d-lg-flex">
    @include('clinic.layouts.left_nav_profile')
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-5">
                    <form id="hospital_form" action="{{route('clinic.save_profile')}}" method="POST" class="registerform">
                           @csrf
                          <input type="hidden" value="{{$hospitalId}}" name="id">
                          <input type="hidden" id="remove-images" value="" name="remove_images">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Clinic Name</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="name_en" name="name_en" value="{{$name}}" placeholder="Clinic Name" />
                                    <span class="bx bx-building"></span>
                                </div>
                            </div>
                            

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Country</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="country" id="country" class="select2-single" data-placeholder="Select Country">
                                        @foreach($country_list as $country)
                                            <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>



                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">City / Province</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="emirate_id" id="emirate_id" class="select2-single" data-placeholder="Select City">
                                        @foreach($emirates_list as $emirate)
                                            <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Area</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="area_id" id="area_id" class="select2-single" data-placeholder="Select Area">
                                        @foreach($area_list as $area)
                                            <option {{($area->id==$area_id)?'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>
                            
                           
                            
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Address Of Organization</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="address" name="address" value="{{$hospital->address}}" placeholder="Enter Address Of Organization" />
                                    <span class="bx bx-building"></span>
                                </div>
                            </div>
                            <!-- <div class="col-12 mb-3">
                                <label class="form-label" for="username">Location or Drag the marker</label>
                                <div class="position-relative input-custom-icon">
                                
                                <input type="text" id="location-input" class="form-control" placeholder="Enter location or Drag the marker" name="location" value="{{$hospital->location[0]->location ?? null}}"/>
                                    <span class="bx bx-map-pin"></span>
                                </div>

                                <div id="map" class="gmaps mt-2"></div>
                            </div> -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Location</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control autocomplete" required placeholder="673C+W8V - Dubai - United Arab Emirates" id="txt_location" name="location" value="{{$hospital->location[0]->location ?? null}}"/>
                                    <input type="hidden" id="latitude" name="latitude" value="{{$hospital->location[0]->latitude ?? null}}"/>
                                    <input type="hidden" id="longitude" name="longitude" value="{{$hospital->location[0]->longitude ?? null}}"/>
                                    <span class="bx bx-calendar-event"></span>
                                    <input type="hidden" id="location" name="">
                                </div>
                                <div class="form-group col-md-12">
                                    <div id="map_canvas" style="height: 200px;width:100%;"></div>
                                </div>
                            </div>
        
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Clinic Main Number</label>
                                <div class="position-relative">
                                  <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}" placeholder="Enter Phone Number" />
                                  <input type="hidden" id="dial_code" name="dial_code" value="{{ Auth::user()->dial_code }}">
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Email Address</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" placeholder="Enter Address" readonly />
                                    <span class="bx bx-envelope"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Website</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="website" value="{{$hospital->website}}" name="website" placeholder="Enter Website" />
                                    <span class="bx bx-globe"></span>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Direct Call for Appointment Number</label>
                                <div class="position-relative">
                                 <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{$hospital->appointment_dial_code}}">
                                 <input type="text" class="form-control" id="phone1" value="{{$hospital->appointment_phone}}" name="direct_phone" placeholder="Enter Direct Call for Appointment Number" />
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div> --}}

                           
                            
                            <div class="col-12 mb-3">
                                <div class="custom-textarea">
                                    <label class="form-label" for="username">Clinic Profile</label>
                                    <textarea class="form-control" name="profile_bio" id="ckeditor-classic" row="5">{{$hospital->profile_description}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="custom-upload">
                                    <label for="uploadphotos" class="form-label">Clinic Images <span>(Allowed 3 Photos with Dim 750px X 750px)</span></label>
                                    <input class="form-control" type="file" id="upload_imgs" accept="image/jpg, image/jpeg" maxlength="3" multiple />
                                </div>
                                <div id="image_preview" class="row">
                                @if(count($hospital->images))
                                    @foreach($hospital->images as $key => $image)
                                    <div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='existing-img-div-{{$image->image_url}}'><img src='{{$image->image_url}}' class='img-fluid w-100' title='Existing Image " + (i + 1) + "'><div class='middle'><button type="button" id='' data-url="{{$image->image_url}}" data-id="{{$image->id}}" value='{{$image->id}}' class='action-icon-deleteImg btn btn-danger btn-icon' role='existing_image'><i class='fa fa-trash'></i></button></div></div>
                                    @endforeach
                                @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end user chat -->
        </div>
        <!-- End d-lg-flex  -->
    </div>
</div>
@include('clinic.layouts.footer')

<!-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}&v=weekly&libraries=places"></script>
<script src="{{ asset('') }}admin-assets/assets/js/gmaps.min.js"></script>
<script src="{{ asset('') }}admin-assets/assets/js/gmaps.init.js"></script>
<script src="{{ asset('') }}clinic/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}clinic/assets/js/form-editor.init.js"></script>
<script>

var currentLat = $('#latitude').val() ? parseFloat($('#latitude').val()) : 25.276987;
var currentLong = $('#longitude').val() ? parseFloat($('#longitude').val()) : 55.296249;
$("#location").val(currentLat + "," + currentLong);

currentlocation = {
    "lat": currentLat,
    "lng": currentLong,
};

$(document).ready(function() {
    initMap();
    initAutocomplete();
});

function initMap() {
    map2 = new google.maps.Map(document.getElementById('map_canvas'), {
        center: currentlocation,
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

    usermarker = new google.maps.Marker({
        position: currentlocation,
        map: map2,
        draggable: true,
        animation: google.maps.Animation.BOUNCE
    });

    google.maps.event.addListener(map2, 'click', function(event) {
        updatePosition(event.latLng);
    });

    usermarker.addListener('dragend', function(event) {
        updatePosition(event.latLng);
    });
}

function updatePosition(position) {
    geocodePosition(position);
    usermarker.setPosition(position);
    map2.panTo(position);
    map2.setZoom(15);

    let createLatLong = position.lat() + "," + position.lng();
    $("#latitude").val(position.lat());
    $("#longitude").val(position.lng());
    $("#location").val(createLatLong);
}

function geocodePosition(pos) {
    geocoder.geocode({ latLng: pos }, function(responses) {
        if (responses && responses.length > 0) {
            usermarker.formatted_address = responses[0].formatted_address;
        } else {
            usermarker.formatted_address = 'Cannot determine address at this location.';
        }
        $('#txt_location').val(usermarker.formatted_address);
    });
}

function initAutocomplete() {
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

        var bounds2 = new google.maps.LatLngBounds();
        places2.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }

            updatePosition(place.geometry.location);

            if (place.geometry.viewport) {
                bounds2.union(place.geometry.viewport);
            } else {
                bounds2.extend(place.geometry.location);
            }
        });
        map2.fitBounds(bounds2);
    });
}

</script>

<script>
    App.initFormView();

    $("#base-style").DataTable();

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
        var fileArr = [];
        $('#hospital_form').on('submit', function(e) {
            // alert();
            e.preventDefault(); // Prevent normal form submission
            
            var formData = new FormData(this); // Serialize form data
            let i = 0;
                $.each(fileArr, function (k, v) {
                    formData.append('images['+i+']', v);
                    i++;
                });
                
            
            
            // Submit form data via Ajax
            $.ajax({
                type: "POST",
                url: $(this).attr('action'), // Form action URL
                data: formData,
                contentType: false, // Tell jQuery not to set content type
                processData: false,
                dataType: "json", // Expected data type from server
                success: function(response) {
                    
                    // Handle success response
                    console.log(response);
                    
                    // Check if redirect URL is provided in response
                    if (response.oData && response.oData.redirect) {
                        // Redirect to the specified URL
                        window.location.href = response.oData.redirect;
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });


        $("#upload_imgs").change(function(){
            // check if fileArr length is greater than 0
            if (fileArr.length > 0) fileArr = [];
            
                // $('#image_preview').html("");
                var total_file = document.getElementById("upload_imgs").files;
                // console.log(total_file.length, parseInt($('.img-div').length));
                if(total_file.length + parseInt($('.img-div').length) > 3){
                    var m = 'Maximum 3 images are allowed';
                                App.alert(m, 'Exceed maximum!','error');
                                $(this).val('');
                                return
                }
                if (!total_file.length) return;
                for (var i = 0; i < total_file.length; i++) {
                if (total_file[i].size > 1048576) {
                    return false;
                } else {
                    fileArr.push(total_file[i]);
                    
                    $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'></div></div>");
                }
                }
        });
        var existingImages = @json($hospital->images ?? null); // Convert PHP array to JSON format
        
        function previewExistingImages() {
            if(existingImages){
                $('#image_preview').html("");
                for (var i = 0; i < existingImages.length; i++) {
                    $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='existing-img-div" + existingImages[i].image_url + "'><img src='" + existingImages[i].image_url + "' class='img-fluid w-100' title='Existing Image " + (i + 1) + "'><div class='middle'><button id='action-icon' value='existing-img-div" + i + "' class='btn btn-danger btn-icon' role='existing_image'><i class='fa fa-trash'></i></button></div></div>");
                }
            }
        }

                // previewExistingImages();
        
        
        $('.action-icon-deleteImg').on('click', function(evt){
            evt.preventDefault();
            var file_id = $(this).data('id');
            $(this).parents('.img-div').remove();
            
            var remove_ids = $('#remove-images').val();
            if (remove_ids) {
                // Append the new ID to the existing list of IDs
                remove_ids += ',' + file_id;
            } else {
                // If the hidden input is empty, set it to the new ID
                remove_ids = file_id;
            }
            $('#remove-images').val(remove_ids);
        });
        function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }
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
var existingDialCode = "{{ Auth::user()->dial_code }}";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});



document.addEventListener("DOMContentLoaded", function () {

const input1 = document.querySelector("#phone1");

const iti1 = window.intlTelInput(input1, {
//    initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDirectDialCode() {
    var dialCode = iti1.getSelectedCountryData().dialCode;
    document.getElementById('direct_dial_code').value = dialCode;
}

// when typing
input1.addEventListener("input", updateDirectDialCode);

// when changing flag
input1.addEventListener("countrychange", updateDirectDialCode);

// 🔹 Set country for edit case
var existingDialCode = "{{ $direct_dial_code ?? '' }}";
if(existingDialCode){
    iti1.setNumber("+" + existingDialCode + input1.value);
}

updateDirectDialCode();

});
</script>

