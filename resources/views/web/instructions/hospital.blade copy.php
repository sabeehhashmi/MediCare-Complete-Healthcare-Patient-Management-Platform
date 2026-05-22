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
            
            <div class="col-12 text-primary text-center mb-4 mt-4">
                <h1>User Instructions Hospital Panel</h1>
            </div>
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active" id="v-pills-managing-account-doctors-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-account-doctors" type="button" role="tab" aria-controls="v-pills-managing-account-doctors" aria-selected="true">Accessing Appointments and Doctors</button>
                    <button class="nav-link text-start" id="v-pills-managing-appointments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-appointments" type="button" role="tab" aria-controls="v-pills-managing-appointments" aria-selected="false">Managing Appointments</button>
                    <button class="nav-link text-start" id="v-pills-managing-doctors-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-doctors" type="button" role="tab" aria-controls="v-pills-managing-doctors" aria-selected="false">Managing Doctors</button>
                    <button class="nav-link text-start" id="v-pills-managing-doctor-availability-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-doctor-availability" type="button" role="tab" aria-controls="v-pills-managing-doctor-availability" aria-selected="false">Managing Doctor’s Availability</button>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-managing-account-doctors" role="tabpanel" aria-labelledby="v-pills-managing-account-doctors-tab" tabindex="0">

                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep1" aria-expanded="true" aria-controls="collapsep1">
                                        <h5 class="mb-0">How to Access details (profile, and appointments) of all doctors of any clinic and make changes through clinic panel by clinic managers ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep1" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Log in to the clinic panel on Mednero.</li>
                                            <li class="mb-2">You will see the total doctors list and   latest appointments of that clinic on the clinic panel’s homepage.</li>
                                            <li class="mb-2">You can make action for any doctors details such as view, edit and delete profile of any doctors, view and action appointment of any doctors, schedule appointment slot and date, mark holiday date, temporary availability date and mark instant appointment availability date of any doctors in that clinic by clicking the action menu in the right column of the displayed doctors list.</li>
                                            <li class="mb-2">Additionally, clinic manager can click on "Total Appointments" in the header menu and you can access all appointments and filter them by date, appointment status, doctor's name, and department</li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-appointments" role="tabpanel" aria-labelledby="v-pills-managing-appointments-tab" tabindex="0">
                    

                        
                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep2" aria-expanded="true" aria-controls="collapsep2">
                                        <h5 class="mb-0">How to View, Cancel, Reschedule, and Complete an Appointment of any doctors in the clinic through the Clinic Panel by clinic manager ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep2" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                            
                                            <li class="mb-2">Log in to the clinic panel on Mednero.</li>
                                            <li class="mb-2">In the header menu, select the " Doctor" option or click “Total Doctors” tab in the homepage view all the doctors in the clinic</li>
                                            <li class="mb-2">In the displayed doctors list, click the action menu in the right column and select “View Appointments” to see a list of appointments with patients' names and other details.</li>
                                            <li class="mb-2">You can  view ,cancel, reschedule, and complete each appointment by clicking the action menu in the right column of the displayed appointment list.</li>
                                            <li class="mb-2">Once you have done this action a corresponding message will sent to that patient, call centre.</li>

                                        </ul>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-doctors" role="tabpanel" aria-labelledby="v-pills-managing-doctors-tab" tabindex="0">

                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep3" aria-expanded="true" aria-controls="collapsep3">
                                        <h5 class="mb-0">How to, Add Doctors profile through Clinic Panel by clinic manager ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep3" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            
                                                
                                            <li class="mb-2">Log in to the hospital panel/clinic panel.</li>
                                            <li class="mb-2">In the header menu, select the " Doctor" option or click “Total Doctors” tab in the homepage</li>
                                            <li class="mb-2">On the doctors' page, you will see the list of already added doctors displayed in a table format.</li>
                                            <li class="mb-2">To add a new doctor, click on the "Add Doctor" button located at the top right of the doctor's page and fill the details .</li>

                                        </ul> 

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepTwo" aria-expanded="false" aria-controls="collapsepTwo">
                                        <h5 class="mb-0">How to View, and Edit Doctors details through Clinic Panel by clinic manager</h5>
                                    </button>
                                </h2>
                                <div id="collapsepTwo" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                    <ul>
                            
                                        
                                    <li class="mb-2">Log in to the clinic panel.</li>
                                        <li class="mb-2">In the header menu, click the " Doctor" option or click “Total Doctors” tab in the homepage, you will see the list of already added doctors displayed in a table format in doctors’ page.</li>
                                        <li class="mb-2">To manage existing doctor details, click on the action menu in the right end column of the respective doctor. From there, you can view, edit, or delete doctor profiles.</li>

                                    </ul> 

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-primary mb-4"></h3>
                        
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-doctor-availability" role="tabpanel" aria-labelledby="v-pills-managing-doctor-availability-tab" tabindex="0">
                        

                    <div class="accordion accordion-flush" id="accordipOnexample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep4" aria-expanded="true" aria-controls="collapsep4">
                                        <h5 class="mb-0">How to Add Available Date and Time Slots for Each Doctor through the Clinic Panel clinic manager ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep4" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li class="mb-2">Log in to the clinic panel.</li>
                                            <li class="mb-2">In the header menu, click the " Doctor" option or click “Total Doctors” tab in the homepage, you will see the list of already added doctors displayed in a table format in doctors’ page.</li>
                                            <li class="mb-2">Click on the action menu at the right end column of the respective doctor. From there, select "Schedule Appointment Slot."</li>
                                            <li class="mb-2">Here, you can select time slots, day basis, which will be assigned to all upcoming dates until holidays or temporary unavailability for the respective doctor is updated through the action menu.</li>

                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep5" aria-expanded="false" aria-controls="collapsep5">
                                        <h5 class="mb-0">How to Mark Temporary Unavailability of a Doctor Hospital/Clinic Panel ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep5" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            

                                            <li class="mb-2">Log in to the clinic panel.</li>
                                            <li class="mb-2">In the header menu, click the " Doctor" option or click “Total Doctors” tab in the homepage, you will see the list of already added doctors displayed in a table format in doctors’ page.</li>
                                            <li class="mb-2">Click on the action menu in the right end column of the respective doctor. From there, select "Mark Temporary Unavailability."</li>
                                            <li class="mb-2">Choose the date and time slot for the temporary unavailability and update it.</li>
                                            
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep6" aria-expanded="false" aria-controls="collapsep6">
                                        <h5 class="mb-0">How to Set a Doctor's Holiday through the Clinic Panel by clinic manager ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep6" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            

                                            
                                            <li class="mb-2">Log in to the clinic panel.</li>
                                            <li class="mb-2">In the header menu, click the " Doctor" option or click “Total Doctors” tab in the homepage, you will see the list of already added doctors displayed in a table format in doctors’ page.</li>
                                            <li class="mb-2">On the action menu (represented by three dots) in the rightmost column of the respective doctor. From there, select "Mark Holiday Date."</li>
                                            <li class="mb-2">Choose the date for the holiday and update it.</li>

                                            
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep7" aria-expanded="false" aria-controls="collapsep7">
                                        <h5 class="mb-0">How to Set a doctor as Available for Instant Medical Appointments through the Clinic Panel clinic manager ?</h5>
                                    </button>
                                </h2>
                                <div id="collapsep7" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        <ul>
                                            
                                            <li class="mb-2">Log in to the clinic panel.</li>
                                            <li class="mb-2">In the header menu, click the " Doctor" option or click “Total Doctors” tab in the homepage, you will see the list of already added doctors displayed in a table format in doctors’ page.</li>
                                            <li class="mb-2">Click on the action menu (represented by three dots) in the rightmost column. From there, select "Mark Instant Appointment Date."</li>
                                            <li class="mb-2">Choose the date and time slots the doctor is available for instant appointments and update it.</li>

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


