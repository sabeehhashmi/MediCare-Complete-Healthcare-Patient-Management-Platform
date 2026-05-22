
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <script>document.write(new Date().getFullYear())</script> © Mednero.
                            </div>
                            
                        </div>
                    </div>
                </footer>
            </div>


        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Booking Id: #MYDW1025</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form novalidate>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="font-size-16">Patient Details</h4>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <img style="width: 80px; height: 80px; border-radius: 5px;" src="assets/images/users/avatar-2.jpg" alt="Generic placeholder image" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4>John Doe</h4>
                                                <h5 class="text-muted"><i class="fas fa-map-marker-alt me-2"></i> Downtown. Dubai</h5>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 20px; justify-content: space-between;">
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bx-envelope me-2"></i> test@example.com</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxs-phone-call me-2"></i> +971-50-1234567</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bxl-whatsapp-square me-2"></i> +971-50-1234567</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="bx bx-calendar me-2"></i> 10-05-1999</h5>
                                            <h5 style="display: flex; align-items: center;" class="mb-0 font-size-15"><i class="fas fa-transgender me-2"></i> Male</h5>
                                        </div>
                                        <div class="card p-0 overflow-hidden mt-3 shadow-none">
                                            <div class="mail-list">
                                                <a href="#" class="border-bottom">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-calendar font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0">05 May 2024</h5>
                                                            <span class="text-muted font-size-13">09:30 AM</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icn-bx me-3">
                                                            <i class="bx bx-map font-size-20 align-middle"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="font-size-14 mb-0">NMC Royal Hospital</h5>
                                                            <span class="text-muted font-size-13">Falcon House - Plot no # 598/122, DIPark 1, Dubai</span>
                                                        </div>
                                                        <div class="flex-shrink-0"></div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="modal-footer justify-content-center">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light">Confirm</button>
                                <button type="button" class="btn btn-dark waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#cancel-appointment">Cancel</button>
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-bs-target="#reschedule-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Reschedule</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#followup-modal">Follow Up</button>
                                <button type="button" class="btn btn-success waves-effect waves-light">Completed</button>
                            </div>
                        </div> -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">Cancel</button>
                            <a href="appointment-details.php" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
        <!-- end modal-->

        <!-- Modal -->
        <div class="modal fade" id="reschedule-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Reschedule Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="custom-form">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Reason of Reschedule</label>
                                <div class="position-relative">
                                    <textarea class="form-control" id="" name="" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <ul class="availability-indicator">
                                    <li>
                                        <span class="not-available-color"></span> Not Available
                                    </li>
                                    <li>
                                        <span class="available-color"></span> Available
                                    </li>
                                    
                                    
                                    <li>
                                        <span class="selected-color"></span> Selected
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="username">Select Slot</label>
                                    <div class="timeslot-selector timeslot-selector-modal">
                                    

                                    <span>
                                        <input type="radio" id="1" name="slot" class="time-slot" />
                                        <label for="1">08:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat10" class="time-slot" disabled/>
                                        <label for="sat10">08:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="2" name="slot" class="time-slot" />
                                        <label for="2">08:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat12" class="time-slot" />
                                        <label for="sat12">08:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="3" name="slot" class="time-slot" />
                                        <label for="3">09:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat14" class="time-slot" />
                                        <label for="sat14">09:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="4" name="slot" class="time-slot" disabled/>
                                        <label for="4">09:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat16" class="time-slot" disabled/>
                                        <label for="sat16">09:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat17" name="slot" class="time-slot" />
                                        <label for="sat17">10:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat18" class="time-slot" />
                                        <label for="sat18">10:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat19" name="slot" class="time-slot" />
                                        <label for="sat19">10:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat20" class="time-slot" disabled/>
                                        <label for="sat20">10:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat21" name="slot" class="time-slot" />
                                        <label for="sat21">11:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat22" class="time-slot" />
                                        <label for="sat22">11:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat23" name="slot" class="time-slot" />
                                        <label for="sat23">11:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat24" class="time-slot" />
                                        <label for="sat24">11:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat21-1" name="slot" class="time-slot" />
                                        <label for="sat21-1">12:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat22-1" class="time-slot" />
                                        <label for="sat22-1">12:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat23-1" name="slot" class="time-slot" />
                                        <label for="sat23-1">12:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat24-1" class="time-slot" />
                                        <label for="sat24-1">12:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat25"  name="slot" class="time-slot" />
                                        <label for="sat25">13:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat26" class="time-slot" />
                                        <label for="sat26">13:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat27" name="slot" class="time-slot" />
                                        <label for="sat27">13:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat28" class="time-slot" />
                                        <label for="sat28">13:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat29" name="slot" class="time-slot" />
                                        <label for="sat29">14:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat30" class="time-slot" />
                                        <label for="sat30">14:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat31" name="slot" class="time-slot" />
                                        <label for="sat31">14:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat32" class="time-slot" />
                                        <label for="sat32">14:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat33" name="slot" class="time-slot" />
                                        <label for="sat33">15:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat34" class="time-slot" />
                                        <label for="sat34">15:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat35" name="slot" class="time-slot" />
                                        <label for="sat35">15:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat36" class="time-slot" />
                                        <label for="sat36">15:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat37" name="slot" class="time-slot" />
                                        <label for="sat37">16:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat38" class="time-slot" />
                                        <label for="sat38">16:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat39" name="slot" class="time-slot" />
                                        <label for="sat39">16:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat40" class="time-slot" />
                                        <label for="sat40">16:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat41" name="slot" class="time-slot" />
                                        <label for="sat41">17:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat42" class="time-slot" />
                                        <label for="sat42">17:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat43" name="slot" class="time-slot" />
                                        <label for="sat43">17:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat44" class="time-slot" />
                                        <label for="sat44">17:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat45" name="slot" class="time-slot" />
                                        <label for="sat45">18:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat46" class="time-slot" />
                                        <label for="sat46">18:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat47" name="slot" class="time-slot" />
                                        <label for="sat47">18:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat48" class="time-slot" />
                                        <label for="sat48">18:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat49" name="slot" class="time-slot" />
                                        <label for="sat49">19:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat50" class="time-slot" />
                                        <label for="sat50">19:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat51" name="slot" class="time-slot" />
                                        <label for="sat51">19:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat52" class="time-slot" />
                                        <label for="sat52">19:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat53" name="slot" class="time-slot" />
                                        <label for="sat53">20:00</label>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Confirm Reschedule</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Appointment Modal -->
        <div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="custom-form">
                        <div class="row">
                            <div class="col-12 mb-3 d-none">
                                <label class="form-label" for="">Clinic </label>
                                <!-- <div class="position-relative">
                                    <select name="" id="ClinicSelect" class="select2-single" data-placeholder="Select Clinic/Hospital">
                                        <option></option>
                                        <option value="1">Hospital 1</option>
                                        <option value="2">Hospital 2</option>
                                        <option value="3">Hospital 3</option>
                                        <option value="4">Hospital 4</option>
                                        <option value="5">Hospital 5</option>
                                        <option value="6">Hospital 6</option>
                                        <option value="7">Hospital 7</option>
                                    </select>
                                </div> -->
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="" value="Boston Dental Center" disabled placeholder="Clinic">
                                    <!-- <span class="bx bx-building"></span> -->
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Department </label>
                                <div class="position-relative">
                                    <select name="" id="DepartmentSelct" class="select2-single" data-placeholder="Select Department">
                                        <option></option>
                                        <option value="1">Anesthetics</option>
                                        <option value="2">Cardiology</option>
                                        <option value="3">Critical Care</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Doctor </label>
                                <div class="position-relative">
                                    <select name="" id="DocSelct" class="select2-single" data-placeholder="Select Doctor">
                                        <option></option>
                                        <option value="1">DR S.K SHETTY M.S</option>
                                        <option value="2">DR SHAMIM SALIM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Patient </label>
                                <div class="position-relative">
                                    <select name="" id="PatientSelct" class="select2-single" data-placeholder="Select Patient">
                                        <option></option>
                                        <option value="1">Patient name 1</option>
                                        <option value="2">Patient name 2</option>
                                        <option value="3">Patient name 3</option>
                                        <option value="4">Patient name 4</option>
                                        <option value="5">Patient name 5</option>
                                        <option value="6">Patient name 6</option>
                                        <option value="7">Patient name 7</option>
                                    </select>
                                </div>
                            </div>

                            
                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="username">Select Patient</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="">Reason of Reschedule</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <textarea class="form-control" id="" name="" rows="2"></textarea>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-12">
                                <ul class="availability-indicator">
                                    <li>
                                        <span class="not-available-color"></span> Not Available
                                    </li>
                                    <li>
                                        <span class="available-color"></span> Available
                                    </li>
                                    
                                    
                                    <li>
                                        <span class="selected-color"></span> Selected
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="username">Select Slot</label>
                                    <div class="timeslot-selector timeslot-selector-modal">
                                    

                                    <span>
                                        <input type="radio" id="sat91" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat91">08:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat101" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat101">08:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat111" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat111">08:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat121" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat121">08:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat131" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat131">09:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat141" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat141">09:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat151" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat151">09:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat161" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat161">09:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat171" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat171">10:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat181" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat181">10:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat191" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat191">10:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat201" name="slot11" class="time-slot checkbx-style" disabled/>
                                        <label for="sat201">10:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat211" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat211">11:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat221" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat221">11:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat231" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat231">11:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat241" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat241">11:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat251" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat251">12:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat261" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat261">12:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat271" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat271">12:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat281" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat281">12:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat291" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat291">13:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat311" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat311">13:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat321" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat321">13:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="radio" id="sat331" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat331">13:45</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat341" name="slot11" class="time-slot checkbx-style" />
                                        <label for="sat341">14:00</label>
                                    </span>

                                    <span>
                                        <input type="radio" id="sat341" name="slot11" class="time-slot" />
                                        <label for="sat341">14:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat32" class="time-slot" />
                                        <label for="sat32">14:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat343" name="slot11" class="time-slot" />
                                        <label for="sat343">15:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat34" class="time-slot" />
                                        <label for="sat34">15:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat344" name="slot11" class="time-slot" />
                                        <label for="sat344">15:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat36" class="time-slot" />
                                        <label for="sat36">15:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat345" name="slot11" class="time-slot" />
                                        <label for="sat345">16:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat38" class="time-slot" />
                                        <label for="sat38">16:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat346" name="slot11" class="time-slot" />
                                        <label for="sat346">16:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat40" class="time-slot" />
                                        <label for="sat40">16:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat347" name="slot11" class="time-slot" />
                                        <label for="sat347">17:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat42" class="time-slot" />
                                        <label for="sat42">17:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat348" name="slot11" class="time-slot" />
                                        <label for="sat348">17:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat44" class="time-slot" />
                                        <label for="sat44">17:45</label>
                                    </span> -->



                                    <span>
                                        <input type="radio" id="sat349" name="slot11" class="time-slot" />
                                        <label for="sat349">18:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat46" class="time-slot" />
                                        <label for="sat46">18:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat350" name="slot11" class="time-slot" />
                                        <label for="sat350">18:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat48" class="time-slot" />
                                        <label for="sat48">18:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat351" name="slot11" class="time-slot" disabled/>
                                        <label for="sat351">19:00</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat50" class="time-slot" />
                                        <label for="sat50">19:15</label>
                                    </span> -->

                                    <span>
                                        <input type="radio" id="sat352" name="slot11" class="time-slot" />
                                        <label for="sat352">19:30</label>
                                    </span>

                                    <!-- <span>
                                        <input type="checkbox" id="sat52" class="time-slot" />
                                        <label for="sat52">19:45</label>
                                    </span> -->


                                    <span>
                                        <input type="radio" id="sat353" name="slot11" class="time-slot" />
                                        <label for="sat353">20:00</label>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Confirm</button>
                </div>
                </div>
            </div>
        </div>



                <!-- Appointment Modal -->
                <div class="modal fade" id="appointment-modal-doctor" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Make an Appointment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="custom-form">
                        <div class="row">
                            <!-- <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Department </label>
                                <div class="position-relative">
                                    <select name="" id="DepartmentSelct1" class="select2-single" data-placeholder="Select Department">
                                        <option></option>
                                        <option value="1">Anesthetics</option>
                                        <option value="2">Cardiology</option>
                                        <option value="3">Critical Care</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Doctor </label>
                                <div class="position-relative">
                                    <select name="" id="DocSelct1" class="select2-single" disabled data-placeholder="Select Doctor">
                                        <option></option>
                                        <option value="1" selected>DR S.K SHETTY M.S</option>
                                        <option value="2">DR SHAMIM SALIM</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Select Patient </label>
                                <div class="position-relative">
                                    <select name="" id="PatientSelct1" class="select2-single" data-placeholder="Select Patient">
                                        <option></option>
                                        <option value="1">Patient name 1</option>
                                        <option value="2">Patient name 2</option>
                                        <option value="3">Patient name 3</option>
                                        <option value="4">Patient name 4</option>
                                        <option value="5">Patient name 5</option>
                                        <option value="6">Patient name 6</option>
                                        <option value="7">Patient name 7</option>
                                    </select>
                                </div>
                            </div>

                            
                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="username">Select Patient</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Select Date</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control flatpicker-input" id="" placeholder="Select Date" />
                                </div>
                            </div>

                            <!--<div class="col-12 mb-3">-->
                            <!--    <label class="form-label" for="">Reason of Reschedule</label>-->
                            <!--    <div class="position-relative">-->
                            <!--        <textarea class="form-control" id="" name="" rows="2"></textarea>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-12">
                                <ul class="availability-indicator">
                                    <li>
                                        <span class="not-available-color"></span> Not Available
                                    </li>
                                    <li>
                                        <span class="available-color"></span> Available
                                    </li>
                                    
                                    
                                    <li>
                                        <span class="selected-color"></span> Selected
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="username">Select Slot</label>
                                    <div class="timeslot-selector timeslot-selector-modal">
                                    

                                    <span>
                                        <input type="radio" id="das1" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das1">08:00</label>
                                    </span>



                                    <span>
                                        <input type="radio" id="das2" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das2">08:30</label>
                                    </span>

        


                                    <span>
                                        <input type="radio" id="das3" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das3">09:00</label>
                                    </span>

                         

                                    <span>
                                        <input type="radio" id="das4" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das4">09:30</label>
                                    </span>



                                    <span>
                                        <input type="radio" id="das5" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das5">10:00</label>
                                    </span>

                        

                                    <span>
                                        <input type="radio" id="das6" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das6">10:30</label>
                                    </span>

                 


                                    <span>
                                        <input type="radio" id="das7" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das7">11:00</label>
                                    </span>

                            

                                    <span>
                                        <input type="radio" id="das8" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das8">11:30</label>
                                    </span>

                           

                                    <span>
                                        <input type="radio" id="das9" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das9">12:00</label>
                                    </span>


                                    <span>
                                        <input type="radio" id="das10" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das10">12:30</label>
                                    </span>

                               

                                    <span>
                                        <input type="radio" id="das11" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das11">13:00</label>
                                    </span>

                            

                                    <span>
                                        <input type="radio" id="das12" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das12">13:30</label>
                                    </span>

                                 

                                    <span>
                                        <input type="radio" id="das13" name="doctor-appointment-slot" class="time-slot checkbx-style" />
                                        <label for="das13">14:00</label>
                                    </span>

                                    <span>
                                        <input type="radio" id="das14" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das14">14:30</label>
                                    </span>



                                    <span>
                                        <input type="radio" id="das15" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das15">15:00</label>
                                    </span>

                

                                    <span>
                                        <input type="radio" id="das16" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das16">15:30</label>
                                    </span>




                                    <span>
                                        <input type="radio" id="das17" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das17">16:00</label>
                                    </span>

                          
                                    <span>
                                        <input type="radio" id="das18" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das18">16:30</label>
                                    </span>

                                   


                                    <span>
                                        <input type="radio" id="das19" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das19">17:00</label>
                                    </span>

                                  

                                    <span>
                                        <input type="radio" id="das20" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das20">17:30</label>
                                    </span>

                            


                                    <span>
                                        <input type="radio" id="das21" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das21">18:00</label>
                                    </span>


                                    <span>
                                        <input type="radio" id="das22" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das22">18:30</label>
                                    </span>

                                    


                                    <span>
                                        <input type="radio" id="das23" name="doctor-appointment-slot" class="time-slot" disabled/>
                                        <label for="das23">19:00</label>
                                    </span>

                                
                                    <span>
                                        <input type="radio" id="das24" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das24">19:30</label>
                                    </span>

                                  


                                    <span>
                                        <input type="radio" id="das25" name="doctor-appointment-slot" class="time-slot" />
                                        <label for="das25">20:00</label>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Confirm</button>
                </div>
                </div>
            </div>
        </div>

        

         <!-- Modal -->
        <div class="modal fade" id="cancel-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Booking - #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/cancel-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to cancel the appointment.</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="">Reason of Cancellation</label>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="" name="" rows="2"></textarea>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-dark" style="width: 120px;">Cancel</button>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="modal fade" id="followup-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Follow Up</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Select Date & time</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control flatpicker-input-date-time" id="" placeholder="Select Date & time" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="position-relative">
                                        <label class="form-label" for="">Follow Up Remark</label>
                                        <textarea class="form-control" id="" name="" rows="3" placeholder="Enter Follow Up Remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="confirm-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm Booking - #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/success-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to confirm the appointment.</p>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary">Confirm Appointment</button>
                    </div>
                </div>
                
            </div>
        </div>



        <div class="modal fade" id="completed-appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Complete Booking - #MYDW1025</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="custom-form">
                            <div class="row">
                                <div class="col-12 mb-3 text-center">
                                    <img src="assets/images/success-img.svg" class="img-fluid" alt="">
                                </div>

                                <div class="col-12 text-center mb-3">
                                    <h4>Are you sure?</h4>
                                    <p class="mb-0">You want to complete the appointment.</p>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary">Completed</button>
                    </div>
                </div>
                
            </div>
        </div>

    
    
        </div>
        <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->





    <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="assets/libs/metismenujs/metismenujs.min.js"></script> -->
    <!-- <script src="assets/libs/simplebar/simplebar.min.js"></script> -->
    <!-- <script src="assets/libs/eva-icons/eva.min.js"></script> -->

        
    <!-- <script src="assets/js/pages/dashboard.init.js"></script> -->
    <script src="assets/js/dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>


    <!-- plugin js -->
    <script src="assets/libs/fullcalendar/index.global.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/flatpickr.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script>
    $(".flatpicker-input").flatpickr({
        dateFormat: "d-m-Y",
        minDate: "today"
    });

    // $(".flatpicker-input-date-time").flatpickr({
    //     minDate: "today",
    //     enableTime: true,
    //     dateFormat: "d-m-Y H:i"
    // });

    $(".flatpicker-input-date-time").flatpickr({
        minDate: "today",
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        onReady: function(selectedDates, dateStr, instance) {
                // Create OK button
                var okButton = document.createElement("button");
                okButton.innerText = "OK";
                okButton.classList.add("btn", "btn-primary", "ms-2");
                
                // Add click event to OK button
                okButton.addEventListener("click", function() {
                    instance.close();
                });

                // Create Clear button
                var clearButton = document.createElement("button");
                clearButton.innerText = "Clear";
                clearButton.classList.add("btn", "btn-outline-secondary"
                , "waves-effect",  "waves-light");
                
                // Add click event to Clear button
                clearButton.addEventListener("click", function() {
                    instance.clear();
                    // instance.close();
                });

                // Append OK and Clear buttons to flatpickr calendar
                var buttonContainer = document.createElement("div");
                buttonContainer.classList.add("flatpickr-button-container", "d-flex", "justify-content-end", "px-3", "pb-2");
                buttonContainer.appendChild(clearButton);
                buttonContainer.appendChild(okButton);

                instance.calendarContainer.appendChild(buttonContainer);
        }
    });
    
    $(".flatpicker-input-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
    $(".flatpicker-input-multiple").flatpickr({
        dateFormat: "d-m-Y",
        mode: "multiple",
        minDate: "today"
    });
    $(document).ready(function() {
        $('.select2-single').select2({
            placeholder: $(this).data('placeholder'),
        });
        $("#DepartmentSelct, #PatientSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#PatientSelct1, #DepartmentSelct1").select2({ dropdownParent: "#appointment-modal-doctor" });
    });
</script>
    </body>


</html>