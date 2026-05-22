@extends('front.template.layout')

@section('title', 'My Bookings')

@section('content')
 <div class="checkout-page pt-100 mb-100">
        <div class="container">
            <div class="row g-lg-4 gy-5">
                <div class="col-lg-7">
                    <div class="checkout-form-wrapper">
                        <div class="checkout-form-title">
                            <h4>Check Availability</h4>
                        </div>
                        <div class="checkout-form">
                            

                            <h5>Booking Type</h5>
                            <div class="custom-checkbx-auto-wrap mb-40">
                                <ul>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" checked="checked" name="radio1">
                                            <span class="checkmark"></span>
                                            <span class="label-text">
                                                <i class='bx bxs-buildings me-2' ></i> In Person
                                            </span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio1">
                                            <span class="checkmark"></span>
                                            <span class="label-text">
                                                <i class='bx bxs-video me-2'></i> Video Consultation
                                            </span>
                                        </label>
                                    </li>
                                </ul>

                            </div>


                            <h5>Preferred Date</h5>
                            <div class="mb-30" id="calenderView"></div>

                            <h5>Preferred Time Range</h5>
                            <div class="time-slots time-range-slots mb-40">
                                <ul>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" checked="checked" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">
                                                Morning
                                                <span class="small-label">(08:00 AM - 12:00 PM)</span>
                                            </span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio" disabled>
                                            <span class="checkmark"></span>
                                            <span class="label-text">Afternoon
                                                <span class="small-label">(12:00 PM - 04:00 PM)</span>
                                            </span>
                                        </label>
                                    </li>
                                    <li class="">
                                        <label class="checkbox-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <span class="label-text">Evening
                                                <span class="small-label">(04:00 PM - 08:00 PM)</span>
                                            </span>
                                        </label>
                                    </li>
                                </ul>

                                <ul class="color-indicaters-wrap">
                                    <li>
                                        <span class="clr-indicate unavailable"></span>
                                        <span class="text-indicate">Unavailable</span>
                                    </li>
                                    <li>
                                        <span class="clr-indicate available"></span>
                                        <span class="text-indicate">Available</span>
                                    </li>
                                    <li>
                                        <span class="clr-indicate selected"></span>
                                        <span class="text-indicate">Selected</span>
                                    </li>
                                </ul>
                                
                                <div class="form-inner2 mb-25 mt-30">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="contactCheck11">
                                        <label class="form-check-label" for="contactCheck11">
                                            I'm flexible with time
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <form>
                                <h5>Notes or Report Upload</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-inner two mb-25">
                                            <!-- <label>Your Location</label> -->
                                            <input type="text" placeholder="Write any notes for the appointment...">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label>Lab Reports</label>
                                            <div class="custom-file">
                                                <input type="file">
                                                <label>
                                                    <span class="btn">Choose File</span>
                                                    <span class="file-name">No file selected</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label>X-Ray / Imaging Report</label>
                                            <div class="custom-file">
                                                <input type="file">
                                                <label>
                                                    <span class="btn">Choose File</span>
                                                    <span class="file-name">No file selected</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <h5>Choose the Patient</h5>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <div class="form-inner two mb-25">
                                            <label>Select Patient*</label>
                                            <select>
                                                <option>Yourself </option>
                                                <option>Daniel Scoot</option>
                                                <option>John Doe</option>
                                                <option>Jane Smith</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        
                                            <!-- <label>Select Patient*</label> -->
                                        <button type="button" data-bs-toggle="modal"
                                data-bs-target="#addPatientModal" class="primary-btn1 btn-outline mt-30 w-100">
                                            <span>
                                                Add New Patient
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                                </svg>
                                            </span>
                                            <span>
                                                Add New Patient
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                                </svg>
                                            </span>
                                        </button>

                                    </div>
                                    <div class="col-md-12">
                                        <p style="font-size: 14px;" class="mt-30">We'll submit your request to the doctor. If they're available, 
                                            our team will confirm your appointment shortly.</p>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-inner two mb-25">
                                            <label>Phone Number*</label>
                                            <input type="text" placeholder="(212)+ 455 645 678">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner two mb-25">
                                            <label>Email Address <span>(Optional)</span></label>
                                            <input type="email" placeholder="info@gmail.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner two mb-25">
                                            <label>Your Location</label>
                                            <input type="text" placeholder="Type Location">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner two mb-25">
                                            <label>Street Address*</label>
                                            <input type="text" placeholder="Street address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner two mb-25">
                                            <label>Postal Code*</label>
                                            <input type="text" placeholder="Postal code">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-inner two mb-25">
                                            <label>Short Notes*</label>
                                            <textarea placeholder="Write Something..."></textarea>
                                        </div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="checkout-form-wrapper">
                        <div class="checkout-form-title">
                            <h4>Booking Summary</h4>
                        </div>
                        <div class="order-sum-area">
                            <form>
                                <div class="cart-menu">
                                    <div class="cart-body">
                                        <ul>
                                            <li class="single-item">
                                                <div class="item-area">
                                                    <div class="main-item">
                                                        <div class="item-img">
                                                            <img src="images/portrait-of-a-happy-young-doctor-in-his-clinic-royalty-free-image-1661432441.jpg"
                                                                alt="">
                                                        </div>
                                                        <div class="content-and-quantity">
                                                            <div class="content">
                                                                <span>Dentistry</span>
                                                                <h6><a href="#">
                                                                    Dr. Ashik Muhammed</a></h6>
                                                                <span  class="mt-2 d-inline-block">Boston Medical Centre</span>
                                                            </div>
                                                            <!-- <div class="quantity-area">
                                                                <div class="quantity">
                                                                    <a class="quantity__minus"><span><i
                                                                                class="bi bi-dash"></i></span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="01">
                                                                    <a class="quantity__plus"><span><i
                                                                                class="bi bi-plus"></i></span></a>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                    <!-- <button type="reset" class="close-btn"><i
                                                            class="bi bi-x"></i></button> -->
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="cart-footer">
                                        <!-- <div class="pricing-area mb-40">
                                            <ul>
                                                <li>
                                                    <strong>Sub Total</strong>
                                                    <strong>$348.00</strong>
                                                </li>
                                                <li>
                                                    <strong>Service Charge</strong>
                                                    <strong>$348.00</strong>
                                                </li>
                                                <li>
                                                    <strong>Total</strong>
                                                    <strong>$214.00</strong>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <!-- <div class="choose-payment-method">
                                            <h6>Select Payment Method</h6>
                                            <div class="payment-option">
                                                <ul>
                                                    <li class="paypal active">
                                                        <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.52786 15.7817L4.65952 19.0681L1.48638 19.1366C0.526434 17.324 0.0164046 15.3017 0.000389197 13.2444C-0.0156262 11.1872 0.462855 9.15692 1.39446 7.32922L4.21949 7.85465L5.45745 10.7019C4.90089 12.3528 4.92577 14.1483 5.52786 15.7827V15.7817Z" fill="#FBBB00"/>
                                                            <path d="M24.7248 10.781C25.148 13.0356 24.9578 15.3645 24.1745 17.5178C23.3911 19.6711 22.0443 21.5676 20.2785 23.0037L16.7201 22.8193L16.2165 19.632C17.6863 18.7588 18.8155 17.4001 19.4151 15.7835H12.7461V10.781H24.7258H24.7248Z" fill="#518EF8"/>
                                                            <path d="M20.2778 23.0037C18.8506 24.1649 17.189 24.9936 15.4107 25.4311C13.6323 25.8685 11.7808 25.904 9.98746 25.5351C8.19408 25.1661 6.5027 24.4017 5.03323 23.2961C3.56377 22.1904 2.35218 20.7706 1.48438 19.1373L5.52585 15.7834C5.90541 16.809 6.50134 17.7381 7.27197 18.5057C8.04261 19.2734 8.96935 19.8612 9.9874 20.2279C11.0054 20.5947 12.0902 20.7315 13.1659 20.6289C14.2415 20.5263 15.2821 20.1868 16.2148 19.634L20.2778 23.0037Z" fill="#28B446"/>
                                                            <path d="M20.4324 3.40922L16.3919 6.76306C15.4453 6.16549 14.3781 5.792 13.2695 5.67035C12.1609 5.54871 11.0395 5.68205 9.98871 6.06045C8.93796 6.43886 7.98488 7.05259 7.20039 7.85599C6.41589 8.65938 5.82016 9.63177 5.45752 10.7008L1.39453 7.33013C2.25161 5.6526 3.46849 4.19106 4.95579 3.05287C6.44309 1.91468 8.16301 1.12877 9.9892 0.752872C11.8154 0.376974 13.7014 0.420639 15.5088 0.880661C17.3161 1.34068 18.9989 2.20537 20.4334 3.41121L20.4324 3.40922Z" fill="#F14336"/>
                                                        </svg>

                                                        <div class="checked">
                                                            <i class="bi bi-check"></i>
                                                        </div>
                                                    </li>
                                                    <li class="stripe">
                                                        <svg width="26" height="17" viewBox="0 0 26 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M25.603 4.89652V2.44041C25.603 1.3549 24.7149 0.466797 23.6294 0.466797H2.90696C1.82145 0.466797 0.93335 1.3549 0.93335 2.44041V4.89652H25.603Z" fill="#C723C3"/>
                                                            <path d="M0.93335 7.36353V14.5067C0.93335 15.5922 1.82145 16.4803 2.90696 16.4803H23.6294C24.7149 16.4803 25.603 15.5922 25.603 14.5067V7.36353H0.93335ZM14.5739 13.2842H4.80867V10.8173H14.5734V13.2842H14.5739ZM21.728 13.2842H18.4593V10.8173H21.728V13.2842Z" fill="#A0A0A0"/>
                                                        </svg>

                                                        <div class="checked">
                                                            <i class="bi bi-check"></i>
                                                        </div>
                                                    </li>
                                                    <li class="offline">
                                                        <svg width="22" height="26" viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_2509_20282)">
                                                        <path d="M21.4324 19.1319C20.9311 20.6771 20.1791 22.0877 19.1701 23.3572C18.7009 23.947 18.1996 24.5241 17.6405 25.0306C16.7729 25.8192 15.7253 26.0436 14.5942 25.7038C13.9515 25.5114 13.3281 25.255 12.6854 25.0626C11.4257 24.6779 10.2046 24.8639 8.98994 25.3191C8.39224 25.5435 7.78169 25.7487 7.15186 25.8513C6.25852 25.9987 5.50658 25.6076 4.83819 25.0434C3.85488 24.2163 3.10293 23.184 2.42169 22.1133C1.11703 20.0744 0.332955 17.8368 0.0758807 15.4324C-0.129779 13.5346 0.0501732 11.6752 1.0142 9.97613C2.26101 7.78977 4.14409 6.58439 6.6827 6.59722C7.42821 6.60363 8.17373 6.91138 8.91282 7.11014C9.41411 7.24479 9.90256 7.43714 10.3974 7.60384C10.7188 7.71283 11.0401 7.71283 11.3679 7.60384C12.2548 7.30249 13.1417 6.98832 14.0415 6.73186C16.3873 6.06505 19.0994 6.93062 20.5776 8.7964C20.6354 8.87334 20.6933 8.95027 20.7447 9.02721C16.7536 11.2328 16.9464 17.1251 21.4324 19.1319Z" fill="black"/>
                                                        <path d="M16.0392 0.500016C16.1742 1.87851 15.795 3.07748 14.9981 4.15463C14.1883 5.23819 13.205 6.09735 11.8425 6.41151C11.6433 6.4564 11.444 6.47563 11.2384 6.49486C10.7692 6.53975 10.7114 6.49486 10.7242 6.03323C10.7499 5.12278 10.9941 4.27004 11.4504 3.48782C12.4466 1.78234 13.9505 0.820596 15.885 0.506428C15.93 0.493604 15.9685 0.500016 16.0392 0.500016Z" fill="black"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_2509_20282">
                                                        <rect width="21.4336" height="25.4028" fill="white" transform="translate(0 0.5)"/>
                                                        </clipPath>
                                                        </defs>
                                                        </svg>

                                                        <div class="checked">
                                                            <i class="bi bi-check"></i>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="pt-25" id="StripePayment" style="display: none;">
                                                <div class="row g-4">
                                                    <div class="col-md-12">
                                                        <div class="form-inner two">
                                                            <label>Card Number</label>
                                                            <input type="text" placeholder="1234 1234 1234 1234">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-inner two">
                                                            <label>Expiry</label>
                                                            <input type="text" placeholder="MM/YY">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-inner two">
                                                            <label>CVC</label>
                                                            <input type="text" placeholder="CVC">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <p style="font-size: 14px;" class="mb-4">Tip: Book in advance for more flexible appointment options.</p>
                                        <ul>
                                            <li>
                                                                                <p style="font-size: 14px; " class="mt-0 mb-0">we will try to give you closest availability to your date</p>
                                        </li> <li>
                                                                                <p style="font-size: 14px; " class="mt-0 mb-0">You will be notified of the appointment details if 
                                                                                    the doctor accepts your request. Please check your email and phone.</p>
                                        </li>
                                        </ul>

                                        <button type="button" class="primary-btn1" onclick="window.location.href='success-message.html'">
                                            <span>
                                                SUBMIT REQUEST
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                                </svg>
                                            </span>
                                            <span>
                                                SUBMIT REQUEST
                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('modals')
 <div class="modal enquiry-modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                    </svg>
                </button>
                <div class="modal-body">
                    <h4 class="modal-title" id="addPatientModalLabel">Add New Patient!</h4>
                    <form class="enquiry-form-wrapper">
                        <div class="row g-4 mb-50">
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Full Name*</label>
                                    <input type="text" placeholder="Your Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Gender*</label>
                                    <select>
                                        <option>Female</option>
                                        <option>Male</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Age*</label>
                                    <input type="text" placeholder="Your Age">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>My Insurance Policy*</label>
                                    <select>
                                        <option>Female</option>
                                        <option>Male</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>My Insurance Network*</label>
                                    <select>
                                        <option>Female</option>
                                        <option>Male</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inner">
                                    <label>Profile Photo</label>
                                    <input type="file" placeholder="Your Name">
                                </div>
                            </div>
                            <!-- <div class="col-lg-12">
                                <div class="form-inner">
                                    <label>Tour Details</label>
                                    <textarea placeholder="Write about tour info"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-inner2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="formCheck2">
                                        <label class="form-check-label" for="formCheck2">
                                            Save my email address & name when I comment further time.
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="form-inner">
                            <button type="submit" class="primary-btn1 black-bg">
                                <span>
                                    Submit & Save Patient
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                                <span>
                                    Submit & Save Patient
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection