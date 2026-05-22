@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
<style>
.btn-danger {
    color: #ffffff !important;
    background-color: #c62226;
    border-color: #fff;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
}

.btn-danger:hover,
.btn-danger:focus {
    color: #060606 !important;
    background-color: #c62226;
    box-shadow: none;
    border-color: #fff;
}

.btn.btn-button-7.pull-right {
    width: 50px;
    background-color: #fab213 !important;
    border: 0;
}
</style>
@stop
@section('content')

<div class="card mb-5">
    <div class="card-body">
        <div class="page-header">
            <div class="page-title">
                <h3 style="color: #000 !important">{{ $page_heading }}</h3>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-xl-12 col-lg-12 col-sm-12">
                @if (session('success'))
                <div class="alert alert-success alert-dismissable custom-success-box mb-4">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> {{ session('success') }} </strong>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissable custom-danger-box mb-4">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> {{ session('error') }} </strong>
                </div>
                @endif
                <div class="statbox">
                    <div class="">
                        <form method="post" id="admin-form" action="{{ route('admin.setting_store') }}"
                            enctype="multipart/form-data">
                            @csrf()
                            <input type="hidden" name="id" value="{{ $page->id }}">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Instant Appointment Number<b class="text-danger">*</b></label>
                                    <input type="text" name="instant_appoitment_number" class="form-control jqv-input"
                                         data-jqv-required="true"
                                        value="{{ $page->instant_appoitment_number }}" placeholder="Instant Appointment Number"
                                        required>
                                </div>
                                <div class="col-md-6 form-group">
                                        <label>Maximum Search Radius<b class="text-danger">*</b></label>
                                        <input type="text" name="doctor_search_radius" class="form-control jqv-input"
                                            oninput="validateNumber(this);" data-jqv-required="true"
                                            value="{{ $page->doctor_search_radius }}" placeholder="Maximum Search Radius" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Instant Appointment Number<b class="text-danger">*</b></label>
                                    <input type="text" name="instant_appoitment_number" class="form-control jqv-input"
                                        data-jqv-required="true" value="{{ $page->instant_appoitment_number }}" 
                                        placeholder="Instant Appointment Number" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Maximum Search Radius<b class="text-danger">*</b></label>
                                    <input type="text" name="doctor_search_radius" class="form-control jqv-input"
                                        oninput="validateNumber(this);" data-jqv-required="true"
                                        value="{{ $page->doctor_search_radius }}" placeholder="Maximum Search Radius" required>
                                </div>
                                
                                <!-- New Support Fields -->
                                <div class="col-md-6 form-group">
                                    <label>Support Email<b class="text-danger">*</b></label>
                                    <input type="email" name="support_email" class="form-control jqv-input"
                                        value="{{ $page->support_email ?? '' }}" placeholder="support@mednero.com" required>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Support Phone<b class="text-danger">*</b></label>
                                    <input type="text" name="support_phone" class="form-control jqv-input"
                                        value="{{ $page->support_phone ?? '' }}" placeholder="+971 4 123 4567" required>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Shipping Price (AED)<b class="text-danger">*</b></label>
                                    <input type="number" name="shipping_price" class="form-control jqv-input"
                                        value="{{ $page->shipping_price ?? 10.00 }}" placeholder="10.00" 
                                        step="0.01" min="0" required>
                                </div>

                               
                                <div class="col-md-6 form-group">
                                    <label>Mednero Commission (In %)<b class="text-danger">*</b></label>
                                    <input type="number" name="comission" class="form-control jqv-input"
                                        value="{{ $page->comission ?? 5 }}" placeholder="5" 
                                        required>
                                </div>
<div class="col-lg-6 mb-4">
                                <div class="custom-upload">
                        <label for="uploadphotos" class="form-label">Consent Document</label>
                        <input class="form-control jqv-input" id="consent" type="file" name="consent" accept="image/jpg, image/jpeg, image/png,.pdf" />

                        <div id="imagePreview" style="display: none;"></div>
                        @if ($page->consent_url ?? null)
                        <a id="previewLink" href="{{$page->consent_url}}" target="_blank">View consent</a>
                        @endif
                    </div>
                    </div>
                    <div class="col-md-12 form-group"><h4>Loyalty & Referral Program Settings</h4></div>
                    
                        <div class=" col-md-6 mb-4 form-group">
                    <label for="t-text">Enabled Loayallty Points</label>                                            
                    <select class="form-control jqv-input" name="loyallty_points_enable" id="loyallty_points_enable" required>
                        <option value="1" {{ $page->loyallty_points_enable == 1 ? "selected" : ""}} >Yes </option>
                        <option value="0" {{ $page->loyallty_points_enable == 0 ? "selected" : ""}} >No</option>
                    </select>                            
                </div>
                
                <div class="col-md-12 form-group"><h6>Credit Earning Flow (X AED spent = Y Credits earned)</h6></div>
                
               

                     <div class="col-md-6 form-group">
                                    <label>Transaction Paid Amount<b class="text-danger">*</b></label>
                                    <input type="number" name="loyallty_points_amount" class="form-control jqv-input"
                                        value="{{ $page->loyallty_points_amount ?? 5 }}" placeholder="5" 
                                        >
                                </div>
                            
                            <div class="col-md-6 form-group">
                                    <label>Earned Credit Amount<b class="text-danger">*</b></label>
                                    <input type="number" name="loyallty_points_on_amount" class="form-control jqv-input"
                                        value="{{ $page->loyallty_points_on_amount ?? 5 }}" placeholder="5" 
                                        >
                                </div>
                           
                           <div class="col-md-12 form-group mt-3"><h6>Credit Redemption Flow (X Credits = Y% Discount)</h6></div>
                            <div class="col-md-6 form-group">
                                    <label>Credit Amount  <b class="text-danger">*</b></label>
                                    <input type="number" name="loyallty_points_for_percentage" class="form-control jqv-input"
                                        value="{{ $page->loyallty_points_for_percentage ?? 5 }}" placeholder="5" 
                                        >
                                </div>
                            
                            <div class="col-md-6 form-group">
                                    <label>Eligible Discount(%) on Loyalty Credit<b class="text-danger">*</b></label>
                                    <input type="number" name="loyallty_points_percentage" class="form-control jqv-input"
                                        value="{{ $page->loyallty_points_percentage ?? 5 }}" placeholder="5" 
                                        >
                                </div>
                            
                            
                    </div>



                    <div class="col-12 d-flex mt-2">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                        <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('script')
<script>
var validNumber = new RegExp(/^\d*\.?\d*$/);
var lastValid = 0;

function validateNumber(elem) {
    if (validNumber.test(elem.value)) {
        lastValid = elem.value;
    } else {
        elem.value = lastValid;
    }
}
</script>
<script>
var map;
var marker = false;
var geocoder;

function initMap() {
    var latitude = 25.204819;
    var longitude = 55.270931;

    var myLatLng = {
        lat: latitude,
        lng: longitude
    };

    map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        zoom: 14,
        mapTypeControl: false,
        mapTypeId: 'roadmap'
    });
    var iconBase = 'http://localhost/snabbkart/assets/web/images/map-pin.png';
    //var marker = false; ////Has the restaurant plotted their location marker?
    marker = new google.maps.Marker({
        draggable: true,
        position: myLatLng,
        map: map,
        icon: iconBase
    });

    geocoder = new google.maps.Geocoder();

    geocoder.geocode({
        'latLng': myLatLng
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                $('#location').val(results[0].formatted_address);
            }
        }
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#location').val(results[0].formatted_address);
                }
            }
        });
    });

    google.maps.event.addListener(map, 'click', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#location').val(results[0].formatted_address);
                }
            }
        });
    });

    //Listen for any clicks on the map.
    google.maps.event.addListener(map, 'click', function(event) {
        //Get the location that the restaurant clicked.
        var clickedLocation = event.latLng;
        //If the marker hasn't been added.
        if (marker === false) {
            //Create the marker.
            marker = new google.maps.Marker({
                position: clickedLocation,
                map: map,
                draggable: true //make it draggable
            });

        } else {
            //Marker has already been added, so just change its location.
            marker.setPosition(clickedLocation);
        }
        //Get the marker's location.
        var currentLocation = marker.getPosition();
        //Add lat and lng values to a field that we can save.
        document.getElementById('latitude').value = currentLocation.lat(); //latitude
        document.getElementById('longitude').value = currentLocation.lng(); //longitude


    });

    //Listen for drag events!
    google.maps.event.addListener(marker, 'dragend', function(event) {
        var currentLocation = marker.getPosition();
        //Add lat and lng values to a field that we can save.
        document.getElementById('latitude').value = currentLocation.lat(); //latitude
        document.getElementById('longitude').value = currentLocation.lng(); //longitude
    });

    // Create the search box and link it to the UI element.
    // var options = {
    //     types: ['(cities)']
    // };
    var options = {
        fields: ["formatted_address", "geometry", "name"],
        strictBounds: false,
        types: ["establishment"],
    };
    var input = document.getElementById('location');
    var searchBox = new google.maps.places.SearchBox(input, options);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the restaurant selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }

            //alert(place.geometry.location.lat());alert(place.geometry.location.lng());
            document.getElementById('latitude').value = place.geometry.location.lat(); //latitude
            document.getElementById('longitude').value = place.geometry.location.lng(); //longitude

            marker.setPosition({
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            });
            map.setCenter(marker.getPosition());

        });

        map.fitBounds(bounds);
    });
}

let bio_counter = $("#bios_counts").val();
$('[data-role="add-bios"]').click(function() {
    var html = '<div class="row col-md-12">\
            <div class="col-md-8 form-group">\
                <input type="text" name="bios[]" class="form-control" placeholder="Profile Bio" required>\
                </div>\
                <div class="col-md-3">\
                    <button type="button" class="btn btn-danger" data-role="remove-bios"><i class="flaticon-minus-2"></i></button>\
                </div></div>';
    $('#bios-holder').append(html);

});
$('body').on("click", '[data-role="remove-bios"]', function() {
    $(this).parent().parent().remove();
});
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyW33QtbRMkT_-tjb5Ff3_Y2-B-aq98u8&libraries=places&callback=initMap">
</script>

@endsection
