@extends("frond.template.user_layout")

@section('content')
<div class="inner-hero-section style--five">
    <div class="her-building-img">
        <img src="{{asset('/front/assets/')}}/images/elements/hero-building.png" class="img-fluid w-100" alt="">
    </div>
</div>

<div class="mt-minus-100 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 mb-4">
                <div class="user-card">
                    <div class="avatar-upload">
                        <!-- <div class="obj-el"><img src="{{asset('/front/assets/')}}/images/elements/team-obj.png" alt="image"></div> -->
                        <div class="avatar-edit">
                            <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url({{asset('/front/assets/')}}/images/user/1.png);"></div>
                        </div>
                    </div>
                    <!-- <h3 class="user-card__name mt-4">Albert Owens <img src="{{asset('/front/assets/')}}/images/premium.webp" class="img-fluid" style="height: 34px;" alt=""></h3> -->
                    <h3 class="user-card__name user-card-premium mt-4">{{Auth::user()->name??'Albert Owens'}} <span class="user-card-premium-tooltip"> <span>Premium Membership</span> <img src="{{asset('/front/assets/')}}/images/premium.webp" class="img-fluid" style="height: 34px; margin-top: -10px;" alt=""></span></h3>
                    
                    <span class="user-card__id">ID : {{Auth::user()->user_code??'19535909'}}</span>
                </div>
                <div class="user-action-card">
                    <ul class="user-action-list">
                        <li class="active">
                            <a href="#"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="far fa-edit mr-2"></i> Edit Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fas fa-key mr-2"></i> Change Password</a>
                        </li>
                        <li>
                            <a href="{{route('guest.home_page')}}"><i class="fas fa-sign-out-alt mr-2"></i> Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="user-card mb-30 p-0 overflow-hidden">
                    <div class="contest-card">
                        <div class="contest-card__thumb">
                            <img src="{{asset('/front/assets/')}}/images/1241580367.png" alt="image" />
                        </div>
                        <div class="contest-card__content">
                            <div class="left">
                                <h5 class="contest-card__name mb-2 text-start text-center-sm h3">Purchase Membership</h5>
                            </div>
                            <div class="right">
                                <span class="contest-card__price">$100</span>
                                <p>ticket price</p>
                            </div>
                        </div>
                        <a href="#!" class="contest-card__footer w-100">Buy Now</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-30">
                        <div class="contest-card">
                            <!-- <a href="#" class="item-link"></a> -->
                            <div class="contest-card__thumb">
                                <img src="{{asset('/front/assets/')}}/images/50450450454.png" alt="image" />
                                <!-- <a href="#" class="action-icon"><i class="far fa-heart"></i></a> -->
                                <a class="contest-num" href="#">
                                    <!-- <span>contest no:</span> -->
                                    <h4 class="number">Buy Now</h4>
                                </a>
                            </div>
                            <div class="contest-card__content">
                                <div class="left">
                                    <h5 class="contest-card__name mb-2">Daily Loto</h5>
                                </div>
                                <div class="right">
                                    <span class="contest-card__price">$2.99</span>
                                    <p>ticket price</p>
                                </div>
                            </div>
                            <div class="contest-card__footer">
                                <ul class="contest-card__meta">
                                    <li>
                                        <i class="las la-clock"></i>
                                        <span id="countdown2"></span>
                                    </li>
                                    <!-- <li>
                                <i class="las la-ticket-alt"></i>
                                <span>323</span>
                                <p>Sold</p>
                            </li> -->
                                </ul>
                            </div>
                        </div>
                        <!-- contest-card end -->
                    </div>
                    <div class="col-lg-6 mb-30">
                        <div class="contest-card">
                            <!-- <a href="#" class="item-link"></a> -->
                            <div class="contest-card__thumb">
                                <img src="{{asset('/front/assets/')}}/images/5450983021.png" alt="image" />
                                <!-- <a href="#" class="action-icon"><i class="far fa-heart"></i></a> -->
                                <a class="contest-num" href="#">
                                    <!-- <span>contest no:</span> -->
                                    <h4 class="number">Buy Now</h4>
                                </a>
                            </div>
                            <div class="contest-card__content">
                                <div class="left">
                                    <h5 class="contest-card__name mb-2">Monthly Loto</h5>
                                </div>
                                <div class="right">
                                    <span class="contest-card__price">$8.99</span>
                                    <p>ticket price</p>
                                </div>
                            </div>
                            <div class="contest-card__footer">
                                <ul class="contest-card__meta">
                                    <li>
                                        <i class="las la-clock"></i>
                                        <span id="countdown"></span>
                                        <!-- <div id="countdown"></div> -->
                                    </li>
                                    <!-- <li>
                                <i class="las la-ticket-alt"></i>
                                <span>9805</span>
                                <p>Sold</p>
                            </li> -->
                                </ul>
                            </div>
                        </div>
                        <!-- contest-card end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
<script>
    // Set the target date for the countdown
    var targetDate = new Date("2024-03-31T23:59:59").getTime();
    
    // Update the countdown every second
    var countdown = setInterval(function() {
      // Get the current date and time
      var currentDate = new Date().getTime();
    
      // Calculate the time remaining
      var timeRemaining = targetDate - currentDate;
    
      // Calculate days, hours, minutes and seconds
      var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
      var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
    
      // Display the countdown
      document.getElementById("countdown").innerHTML =  days + "d " + hours + "h "
      + minutes + "m " + seconds + "s ";
    
      // If the countdown is over, display a message
      if (timeRemaining < 0) {
        clearInterval(countdown);
        document.getElementById("countdown").innerHTML = "EXPIRED";
      }
    }, 1000); // Update every 1 second
    </script>
    <script>
      // Set the target date
      const targetDate1 = new Date('2024-03-12T23:59:59');
      
      // Function to update the countdown
      function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate1 - now;
      
        if (distance < 0) {
          clearInterval(timerInterval);
          document.getElementById('countdown2').innerHTML = 'EXPIRED';
          return;
        }
      
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      
        document.getElementById('countdown2').innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
      }
      
      // Initial call to update countdown
      updateCountdown();
      
      // Update the countdown every second
      const timerInterval = setInterval(updateCountdown, 1000);
      </script>
@stop