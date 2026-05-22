@extends('admin.layouts.master')
@section('headerFiles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/table/datatable/custom_dt_zero_config.css') }}">

@endsection

@section('content')

<div class="container mb-5">
    <div class="page-header">
        <div class="page-title">
            <h3>{{$page_heading}}</h3>
        </div>
    </div>

    <div class="layout-spacing ">
        
        <div class="card mb-5">
    <!-- <div class="card-header"> -->
    <!-- <div class="card-header"> -->
    <div class="card-header">
        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-end">
                <!--<h5 class="card-title">{{$page_heading}}</h5>-->
               <button type="button"  class="btn btn-primary m-0 " data-bs-toggle="modal" data-bs-target="#addModalData">Add New Medicine</button>
            </div>
        </div>
    </div>
        <!-- <a href="https://dxbitprojects.com/K46hyg/public/admin/appointments/create" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Book an Appointment </a> -->
    <!-- </div> -->
    <!--<form action="#" id="search-form">-->
    <!--    <div class="row align-items-end mt-3 mx-2">-->
    <!--        <div class="col-lg-3 col-md-4 mb-2">-->
    <!--            <label class="form-label" for="username">From</label>-->
    <!--            <div class="position-relative input-custom-icon">-->
    <!--                <input type="text" name="booking_from" class="form-control flatpicker-input1" id="from_date" placeholder="From" />-->
    <!--                <span class="bx bx-calendar-event"></span>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--        <div class="col-lg-3 col-md-4 mb-2">-->
    <!--            <label class="form-label" for="username">To</label>-->
    <!--            <div class="position-relative input-custom-icon">-->
    <!--                <input type="text" name="booking_to" class="form-control flatpicker-input1" id="to_date" placeholder="To" />-->
    <!--                <span class="bx bx-calendar-event"></span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--                    <div class="col-lg-3 col-md-6 mb-2">-->
    <!--            <label class="form-label" for="hospital">Select Hospital</label>-->
    <!--            <div class="position-relative select-custom-icon">-->
    <!--                <select name="hospital_id" id="hospital" class="select2-single" data-placeholder="Select Hospital">-->
    <!--                    <option></option>-->
    <!--                                                <option value="32" >Ajman Clinic</option>-->
    <!--                                                <option value="27" >AsterMedical</option>-->
    <!--                                                <option value="31" >Karama Hospital</option>-->
    <!--                                        </select>-->
    <!--                <i class="fi fi-rr-bed-alt icon d-flex"></i>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--                                            <div class="col-lg-3 col-md-6 mb-2">-->
    <!--            <label class="form-label" for="doctor">Select Doctor</label>-->
    <!--            <div class="position-relative select-custom-icon">-->
    <!--                <select name="doctor_id" id="doctor" class="select2-single" data-placeholder="Select Doctor">-->
    <!--                    <option></option>-->
    <!--                                                <option value="38" >AbdulWahab</option>-->
    <!--                                                <option value="40" >Abudul Samad</option>-->
    <!--                                        </select>-->
    <!--                <i class="fi fi-rr-user-md icon d-flex"></i>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--                    <div class="col-lg-3 col-md-6 mb-2">-->
    <!--            <label class="form-label" for="booking_status">Booking Status</label>-->
    <!--            <div class="position-relative select-custom-icon">-->
    <!--                <select name="booking_status" id="booking_status" class="select2-single" data-placeholder="Select Type">-->
    <!--                    <option></option>-->
    <!--                    <option value="Pending" >Pending</option>-->
    <!--                    <option value="Confirmed" >Confirmed</option>-->
    <!--                    <option value="Cancelled" >Cancelled</option>-->
    <!--                    <option value="Rescheduled" >Rescheduled</option>-->
    <!--                    <option value="Completed" >Completed</option>-->
    <!--                </select>-->
    <!--                <i class='bx bx-info-circle' ></i>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-lg-3 col-md-6  mb-2">-->
    <!--        <label class="form-label" for="booking_type">Booking Type</label>-->
    <!--        <div class="position-relative select-custom-icon">-->
    <!--            <select name="booking_type" id="booking_type" class="select2-single" data-placeholder="Select Type">-->
    <!--                <option></option>-->
    <!--                <option value="Pending" >New</option>-->
    <!--                <option value="Confirmed" >Follow-up</option>-->
    <!--                <option value="Cancelled" >Second Opinion</option>-->
    <!--                <option value="Rescheduled" >Online Consultation</option>-->
    <!--                <option value="Completed" >Emergency</option>-->
    <!--            </select>-->
    <!--            <i class='bx bx-sync'></i>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--        <div class="col-sm mb-2">-->
    <!--            <div class="d-flex gap-2">-->
                    <!-- <div class="mt-md-0 mb-3 me-3"> -->
    <!--                    <button type="submit" id="" class="btn btn-primary">Search</button>-->
    <!--                    <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">Refresh</button>-->
    <!--                    <a href="https://dxbitprojects.com/K46hyg/public/admin/appointments/export" id="export" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export</a>-->
                    <!-- </div> -->

    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</form>-->
    <div class="card-body">

        <div class="table-responsive">
    <!-- DataTables CSS -->
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Medicine Id</th>
                <th>Image</th>
                <th>Medicine Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>#MEDN4818</td>
                    <td><img class="avatar" style="width: 55px !important;height: 55px !important; border-radius: 8px !important;" src="https://medias.watsons.com.ph/publishing/WTCPH-BP_10030303-front-zoom.jpg?version=1721934635"></td>
                    <td>RITEMED Amoxicillin 500mg </td>
                    <td><p>Used for the infections of the ENT, gastrourinary tract, skin &, soft tissues...</p></td>
                    <td><span class="text-nowrap">AED 25</span></td>
                    <td>60</td>
                    <td>
                        <div class="status-badge completed-badge">
                            <span></span>Active
                        </div>
                    </td>
                    <td>
                        <div class="dropdown mt-4 mt-sm-0">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Edit Medicine</a>
                                <a class="dropdown-item" href="#">View Medicine</a>
                                <a class="dropdown-item" href="#">Delete Medicine</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>#MEDN4818</td>
                    <td><img class="avatar" style="width: 55px !important;height: 55px !important; border-radius: 8px !important;" src="https://medias.watsons.com.ph/publishing/WTCPH-BP_10030303-front-zoom.jpg?version=1721934635"></td>
                    <td>RITEMED Amoxicillin 500mg </td>
                    <td><p>Used for the infections of the ENT, gastrourinary tract, skin &, soft tissues...</p></td>
                    <td><span class="text-nowrap">AED 25</span></td>
                    <td>60</td>
                    <td>
                        <div class="status-badge completed-badge">
                            <span></span>Active
                        </div>
                    </td>
                    <td>
                        <div class="dropdown mt-4 mt-sm-0">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Edit Medicine</a>
                                <a class="dropdown-item" href="#">View Medicine</a>
                                <a class="dropdown-item" href="#">Delete Medicine</a>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>1</td>
                    <td>#MEDN4818</td>
                    <td><img class="avatar" style="width: 55px !important;height: 55px !important; border-radius: 8px !important;" src="https://medias.watsons.com.ph/publishing/WTCPH-BP_10030303-front-zoom.jpg?version=1721934635"></td>
                    <td>RITEMED Amoxicillin 500mg </td>
                    <td><p>Used for the infections of the ENT, gastrourinary tract, skin &, soft tissues...</p></td>
                    <td><span class="text-nowrap">AED 25</span></td>
                    <td>60</td>
                    <td>
                        <div class="status-badge completed-badge">
                            <span></span>Active
                        </div>
                    </td>
                    <td>
                        <div class="dropdown mt-4 mt-sm-0">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Edit Medicine</a>
                                <a class="dropdown-item" href="#">View Medicine</a>
                                <a class="dropdown-item" href="#">Delete Medicine</a>
                            </div>
                        </div>
                    </td>
                </tr>
                
                
            </tbody>
        </table>
        <!-- DataTables JS -->

        </div>
    </div>
</div>


        
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" class="form-validate form-delete" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger deleteRecord">Delete</button>
            </div>
            <input type="hidden" name="id" id="id" value="">
        </form>
      </div>
    </div>
  </div>
  
  <!-- Add New Medicine modal -->

<div class="modal fade" id="addModalData" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Add New Medicine</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="book_appointment_form" action="#" class="custom-form">
                    <input type="hidden" name="_token" value="6Vnw3gGH9qhBt8CnRVRcj1UgPHZdKIxuEw0kGA2c" autocomplete="off">                    <div class="row">
                         

                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Medicine Id</label>
                                <div class="position-relative">
                                    <input type="text" name="" class="form-control" id="" value="MEDN4818" readonly />
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Medicine Name</label>
                                <div class="position-relative">
                                    <input type="text" name="" class="form-control" id="" placeholder="Enter" />
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="">Description</label>
                                <div class="position-relative">
                                    <textarea class="form-control" id="" name="" rows="3"></textarea>
                                </div>
                            </div>


                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label" for="username">Quantity</label>
                                    <div class="position-relative">
                                        <input type="text" name="" class="form-control" id="" placeholder="Enter" />
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12 mb-3">
                                    <label class="form-label" for="username">Price</label>
                                    <div class="position-relative">
                                        <input type="text" name="" class="form-control" id="" placeholder="Enter" />
                                    </div>
                                </div>
                                


                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label" for="">Status</label>
                                    <div class="position-relative">
                                        <select name="" id="" class="select2-single" data-placeholder="Select Status">
                                            <option value="1" >Active</option>
                                            <option value="2" >Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12 mb-3">
                                    <label class="form-label" for="username">Medicine images</label>
                                    <div class="position-relative">
                                        <input type="file" name="" class="form-control" id="" placeholder="Enter" />
                                    </div>
                                </div>
                                
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" id="book_appointment" class="btn btn-primary">Save Medicine</button>
                </div>
                </div>
            </div>
        </div>
        
        
  

@endsection

@section('footerJs')
    

@endsection