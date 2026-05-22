<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>@yield('title', 'MedNero - Global Telehealth Care | World\'s Best Doctors at Your Fingertips')</title>

    <meta name="description"
        content="@yield('meta_description', 'MedNero is revolutionizing global healthcare with secure telehealth consultations, medical travel concierge services, and world-class medical specialists. Your health, our priority - anywhere, anytime.')">
    <meta name="author" content="MedNero">

    <meta name="keywords"
        content="telehealth, telemedicine, global healthcare, medical travel, online doctor consultation, international medical specialists, healthcare platform, medical concierge, secure video consultation, healthcare technology">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="theme-color" content="#808080">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- The Horizon v1.0 || ex nihilo || May 2014 -->


    <!-- style start -->
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/style.css') }}">
    <!-- style end -->

    <!-- the grid start -->
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/skeleton.css') }}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/media.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
    <!-- the grid end -->
    <style>
        html {
          scroll-behavior: auto;
        }
        /*::-webkit-scrollbar {*/
        /*  width: 6px;*/
        /*}*/
        
        /*::-webkit-scrollbar-thumb {*/
        /*  background: #999;*/
        /*  border-radius: 10px;*/
        /*}*/
        
        body {
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            font-style: normal;
            font-weight: normal;
            text-align: center;
            color: #fff;
            background: #000;
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            width: 100%;
            height: 100%;
        }
        
        body {
            overflow-x: hidden !important;
            /* touch-action: manipulation; */
            -webkit-touch-callout: none;
            /* overscroll-behavior: none; */
            min-height: 100vh;
            min-height: -webkit-fill-available;
            /* touch-action: pan-x pan-y; */
        }
        /* Floating Button */
      .emergency-btn {
        /*position: fixed;*/
        /*bottom: 130px;*/
        /*right: 60px;*/
        background: #ff3b3b;
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
        transition: all 0.3s ease;
        width: 55px;
        height: 55px;
      }
    
      .emergency-btn:hover {
        background: #e60000;
        transform: scale(1.05);
      }
    
      /* Pulse Animation */
      .emergency-btn::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50px;
        background: rgba(255, 59, 59, 0.5);
        animation: pulse 1.5s infinite;
        z-index: -1;
      }
    
      @keyframes pulse {
        0% {
          transform: scale(1);
          opacity: 0.7;
        }
        100% {
          transform: scale(1.6);
          opacity: 0;
        }
      }
    
      /* Tooltip */
      .tooltip {
        position: absolute;
        right: 110%;
        background: #333;
        color: #fff;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 12px;
        opacity: 0;
        transition: 0.3s;
        white-space: nowrap;
      }
    
      .emergency-btn:hover .tooltip {
        opacity: 1;
      }
      
      .btn_bottom{
          margin-left: 0 !important;
          display: flex;
          align-items: center;
          flex-wrap: wrap;
          gap: 10px
      }
    
      /* Mobile Optimization */
      @media (max-width: 600px) {
        .emergency-btn {
            font-size: 14px;
            /*bottom: 88px;*/
            /*right: 25px;*/
        }
      }
      @media only screen and (min-width: 320px) and (max-width: 767px) {
          .btn_bottom{
              justify-content: space-between;
            padding: 10px;
          }
      }
    </style>
    <!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    @yield('styles')
</head> 
<body>
    @yield('content')

    <!-- scripts start -->
    <script type="text/javascript" src="{{ asset('js/jquery-1.10.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/supersized.3.2.7.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/supersized.3.2.7.bg.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/supersized.shutter.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('YTPlayer/jquery.mb.YTPlayer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pace-0.5.1.min.js') }}"></script>
    <!--<script type="text/javascript" src="{{ asset('js/jquery.nicescroll.3.5.4.js') }}"></script>-->
    <script type="text/javascript" src="{{ asset('js/jquery.cycle2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('twitter/jquery.tweet.js') }}"></script>
    <script type="text/javascript" src="{{ asset('twitter/ticker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-contact.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/video-fallback.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/the-horizon.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButton = document.querySelector('.filter-button');
            if (filterButton) {
                filterButton.addEventListener('click', function() {
                    console.log('Filter button clicked');
                    const extraFilters = document.querySelectorAll('.extra-filters');
                    const filterWrappers = document.querySelectorAll('.filter-wrapper');
                    
                    // toggle show class for all extra-filters
                    extraFilters.forEach(function(filter) {
                        filter.classList.toggle('show');
                    });

                    // toggle active class on the button itself
                    this.classList.toggle('active');

                    // toggle active class on all filter-wrapper elements
                    filterWrappers.forEach(function(wrapper) {
                        wrapper.classList.toggle('active');
                    });
                });
            }
        });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {

    let hasLocation = {{ session()->has('current_latitude') ? 1 : 0 }};

    if (hasLocation === 0 && navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function (position) {

            saveLocation(position.coords.latitude, position.coords.longitude);

        }, function () {

            // fallback (Dubai)
            saveLocation(25.2048, 55.2708);

        });

    }

    function saveLocation(lat, lng) {
        fetch("{{ route('store.location') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: lng
            })
        })
        .then(response => response.json())
        .then(data => {

            console.log("Location saved", data);

            // ✅ NO reload here

            // OPTIONAL: trigger something if needed
            // e.g. load hospitals, doctors etc
            // loadNearbyData(lat, lng);

        })
        .catch(err => console.error(err));
    }

});
</script>
    @yield('scripts')
</body>
</html>
