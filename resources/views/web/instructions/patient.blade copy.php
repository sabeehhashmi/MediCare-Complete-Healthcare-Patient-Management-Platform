@extends('web.template.layout')

@section('title', 'Home')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<!-- <div class="main-content"> -->
<div class="page-content">
    <div class="container-fluid cms-page">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="text-center text-muted my-5">
                    <h1>User Instructions for Patients</h1>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active" id="v-pills-managing-account-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-account" type="button" role="tab" aria-controls="v-pills-managing-account" aria-selected="true">Managing An Account</button>
                    <button class="nav-link text-start" id="v-pills-book-appointment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-book-appointment" type="button" role="tab" aria-controls="v-pills-book-appointment" aria-selected="false">Booking An Appointment</button>
                    <button class="nav-link text-start" id="v-pills-manage-appointment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manage-appointment" type="button" role="tab" aria-controls="v-pills-manage-appointment" aria-selected="false">Managing An Appointment</button>
                    <button class="nav-link text-start" id="v-pills-adding-family-frndz-tab" data-bs-toggle="pill" data-bs-target="#v-pills-adding-family-frndz" type="button" role="tab" aria-controls="v-pills-adding-family-frndz" aria-selected="false">Adding Family or Friends</button>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-managing-account" role="tabpanel" aria-labelledby="v-pills-managing-account-tab" tabindex="0">
            
                        <div class="accordion" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep01" aria-expanded="true" aria-controls="collapsep01">
                                        <h5 class="mb-0">Log In or Create an Account ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep01" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Navigate to the "Login or Create an Account" option located in the My Accounts Menu or Side Menu.</li>
                                            <li class="mb-2">Upon selecting this option, you'll be directed to the Login or Signup page where you'll find three methods to proceed:</li>
                                            <ol>
                                                <li class="mb-2">
                                                    <b>Email or Phone Number with OTP:</b><br>
                                                    <b>New Users:</b> New Users: Upon receiving the OTP, proceed to create an account by filling out the form with basic information and submitting it. Mandatory fields include your mobile number and name. Other fields are optional. You can also add your family members and friends to book appointments for them using the same account and login.<br>
                                                    <b>Returning Users:</b> Simply log in using your email or phone number along with the OTP sent to you.
                                                </li>
                                                <li class="mb-2"><b>UAE Pass: </b>Utilize the UAE Pass option for a secure and convenient login experience. Follow the authentication process provided by UAE Pass.</li>
                                                <li class="mb-2"><b>Social Login:</b> Google and Apple: Seamlessly log in using your existing Google or Apple account credentials. Select the desired social login option and follow the authentication prompts provided by Google or Apple.</li>
                                            </ol>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="v-pills-book-appointment" role="tabpanel" aria-labelledby="v-pills-book-appointment-tab" tabindex="0">
                        


                        <div class="accordion" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep1" aria-expanded="true" aria-controls="collapsep1">
                                        <h5 class="mb-0">How do I Search and book an appointment through Mednero App ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep1" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Download the Mednero app from the App Store or Google Play Store.</li>
                                            <li class="mb-2">On the Welcome page, Click the button “ For Patients to Book Doctor’s Appointment. “</li>
                                            <li class="mb-2">You will be directed to the homepage, where you will find a search form.</li>
                                            <li class="mb-2">Begin your search by selecting the necessary filters. To view additional filters, tap “View More” under the search filters. Once you've chosen the appropriate filters, click “Submit.”. </li>
                                            <li class="mb-2">You will be taken to the search results page, which displays a list of doctors matching your search filter.</li>
                                            <li class="mb-2">You can modify your filter selection on this page if needed.</li>
                                            <li class="mb-2">When you find a doctor you want to book, </li>
                                            <ol>
                                                <li class="mb-2">To view more details about the doctor, tap “View Full Profile” just below the doctor’s profile picture.</li>
                                                <li class="mb-2">To book an appointment, click the “Book an Appointment” button on their profile.</li>
                                            </ol>
                                            <li class="mb-2">Pick a date/time that works for you. </li>
                                            <li class="mb-2">At this point, you will be prompted to log in or Create an Account if you have not created account yet (Refer Log in or Create an Account) </li>
                                            <li class="mb-2">Once logged in, choose the patient( you or your family members or friends ) for whom you are booking the appointment. You can also add a new patient if needed at this stage and book for them with same account and login.</li>
                                            <li class="mb-2">Review the appointment details and complete your booking on the next page. Once you submit, you will see the message (Thank you, Your Booking has been received). Mednero will send you message once it is confirmed. </li>
                                            <li class="mb-2">After this you can go back to home page or search doctors again. </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-manage-appointment" role="tabpanel" aria-labelledby="v-pills-manage-appointment-tab" tabindex="0">
                                            

                    
                        <div class="accordion" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepOne" aria-expanded="true" aria-controls="collapsepOne">
                                        <h5 class="mb-0">How do I access my appointments?</h5>
                                    </button>
                                </h2>
                                <div id="collapsepOne" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                            <ul>
                                        
                                        <li class="mb-2">Login to your Account through the Mednero App </li>
                                        <li class="mb-2">Navigate to the Appointments section either through the navigation bar or by selecting "Our Appointments" in the My Accounts menu.</li>
                                        <li class="mb-2">In the Appointments section, you'll see a list of patients (members) you have already registered.</li>
                                        <li class="mb-2">Click on the patient(members) whose appointment you want to access</li>
                                        <li class="mb-2">You'll see a list of appointments categorized as pending, confirmed, completed, or cancelled.</li>
                                        <li class="mb-2">If you want to view more information related to a specific appointment, tap anywhere in the Appointment tab, which will take you to the appointment detail page.</li>
                                    </ul>   

                                    <p>Mednero lets you manage your appointments online without the hassle of contacting any Clinic or Hospitals via the phone. And Mednero will send you message once your change has been confirmed.</p>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepTwo" aria-expanded="false" aria-controls="collapsepTwo">
                                        <h5 class="mb-0">How do I reschedule an appointment?</h5>
                                    </button>
                                </h2>
                                <div id="collapsepTwo" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Login to your Account through the Mednero App </li>
                                            <li class="mb-2">Navigate to the Appointments section either through the navigation bar or by selecting "Our Appointments" in the My Accounts menu.</li>
                                            <li class="mb-2">In the Appointments section, you'll see a list of  patients (members) you have already registered.</li>
                                            <li class="mb-2">Click on the patient(members) whose appointment you want to reschedule.</li>
                                            <li class="mb-2">You'll see a list of appointments categorized as pending, confirmed, completed, or cancelled</li>
                                            <li class="mb-2">Choose the appointment you wish to reschedule and click on the 'Reschedule Appointment' button.</li>
                                            <li class="mb-2">You'll be prompted to choose a new date and time and confirm the appointment(submit). </li>
                                            <li class="mb-2">Once you confirm (submit) reschedule, you will see the message (Thank you, Your Booking has been received). Mednero will send you another message by SMS/email once it is confirmed. </li>

                                        </ul> 
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepThree" aria-expanded="false" aria-controls="collapsepThree">
                                        <h5 class="mb-0">How do I cancel an Appointment?</h5>
                                    </button>
                                </h2>
                                <div id="collapsepThree" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            
                                            
                                            <li class="mb-2">Login to your Account through the Mednero App </li>
                                            <li class="mb-2">Navigate to the Appointments section either through the navigation bar or by selecting "Our Appointments" in the My Accounts menu.</li>
                                            <li class="mb-2">In the Appointments section, you'll see a list of patients (members) you have already registered.</li>
                                            <li class="mb-2">Click on the patient whose appointment you want to cancel</li>
                                            <li class="mb-2">You'll see a list of appointments categorized as pending, confirmed, completed, or cancelled.</li>
                                            <li class="mb-2">Choose the appointment you wish to cancel and click on the 'Cancel Appointment' button.</li>
                                            <li class="mb-2">You'll be prompted to write a reason and confirm (submit) cancel the appointment. </li>
                                            <li class="mb-2">Once   you confirm (submit) cancel , you will see the message (Thank you, Your cancellation has been received). Mednero will send you another message by SMS/email once it is confirmed. </li>
                                        </ul>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion -->
                        
                    </div>
                    <div class="tab-pane fade" id="v-pills-adding-family-frndz" role="tabpanel" aria-labelledby="v-pills-adding-family-frndz-tab" tabindex="0">
                                            


                        <div class="accordion" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep001" aria-expanded="true" aria-controls="collapsep001">
                                        <h5 class="mb-0">How do I add my family members and friends to my Account ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep001" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Log in to your Mednero account through the app.</li>
                                            <li class="mb-2">Navigate to the Patients section by selecting "Patients" in the My Accounts menu or side menu.</li>
                                            <li class="mb-2">In the Patients section, tap the "Add Patient" button. This action will launch a form to add a new patient(members).</li>
                                            <li class="mb-2">Fill out the required details in the form.</li>
                                            <li class="mb-2">Submit the form.</li>
                                            <li class="mb-0">Now you will be able to see the new patient(members )added to the patient(members)list</li>
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
    @include('web.template.footer-content')
</div>


