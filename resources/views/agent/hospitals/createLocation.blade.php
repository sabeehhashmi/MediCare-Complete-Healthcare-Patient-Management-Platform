@include('agent.layouts.header')
<div class="card mb-5">
    <form id="admin_form" method="post" action="{{route('agent.hospitals.saveLocation')}}" enctype="multipart/form-data" class="custom-form">
        <div class="card-body">
            <div class="row insu-form-row">
                    @csrf()
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="hospital_id" value="{{$hospital_id}}">
                    <input type="hidden" id="latitude" name="latitude" value="{{$row->latitude ?? null}}"/>
                    <input type="hidden" id="longitude" name="longitude" value="{{$row->longitude ?? null}}"/>
                    
                    <div class="col-12 mt-4 mb-2">
                        <div class="d-md-flex gap-3">
                            <div class="position-relative input-custom-icon w-100">
                                <input type="text" id="location-input" class="form-control" placeholder="Enter location or Drag the marker" name="location" value="{{$row->location ?? null}}"/>
                                <span class="bx bx-calendar-event"></span>
                            </div>
                        </div>
                        <div id="map" class="gmaps mt-2" style="height: 400px;"></div>
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


<script src="https://maps.google.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY"></script>
<script src="{{ asset('') }}admin-assets/assets/js/gmaps.min.js"></script>
<script src="{{ asset('') }}admin-assets/assets/js/gmaps.init.js"></script>

<script>

var lat = $('#latitude').val() ? parseFloat($('#latitude').val()) : 25.276987;
var lng = $('#longitude').val() ? parseFloat($('#longitude').val()) : 55.296249;

function initMap() {
    var initialLocation = {lat: lat, lng: lng};
    var map = new google.maps.Map(document.getElementById('map'), {
        center: initialLocation,
        zoom: 12
    });

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: initialLocation
    });

    google.maps.event.addListener(map, 'click', function(event) {
        // console.log(event);
        var clickedLocation = event.latLng;
        var lat = clickedLocation.lat();
        var lng = clickedLocation.lng();
        displayCoordinates(lat, lng);
    });

    function displayCoordinates(lat, lng) {
        var infoDiv = document.getElementById('info');
        infoDiv.innerHTML = 'Latitude: ' + lat + '<br>Longitude: ' + lng;
    }
    $('#location-input').on('input', function() {
        var value = $(this).val();
        // loadLocation(value);
        var urlMatch = value.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
        if(!urlMatch){
            var m = res['message']||'Please enter the valid location';
                            App.alert(m, 'Invalid URL!','error');
        }
        if (urlMatch) {
            var lat = parseFloat(urlMatch[1]);
            var lng = parseFloat(urlMatch[2]);

            if(!lat || !lng){
                var m = res['message']||'Please enter the valid location';
                App.alert(m, 'Invalid URL!','error');
            }
            var location = new google.maps.LatLng(lat, lng);

            map.setCenter(location);
            map.setZoom(17);
            marker.setPosition(location);

            $('#latitude').val(lat);
            $('#longitude').val(lng);
        }
    });

    // loadLocation($value){
    // }
}

$(document).ready(function () {
    initMap();
    // if($('#location-input').val()){
        // loadLocation($('#location-input').val());
    // }
});

$(document).ready(function() {
    
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
