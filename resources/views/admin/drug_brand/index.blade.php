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
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <!--<h5 class="card-title">{{$page_heading}}</h5>-->
                       <button type="button"  class="btn btn-primary m-0 " data-bs-toggle="modal" data-bs-target="#addModalData">Add New Drug Brand</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
        
                <div class="table-responsive">
            <!-- DataTables CSS -->
                <table class="table table-condensed table-striped" id="table_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Created date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Drug Brand Value</td>
                            <td><span class="text-nowrap">18 Dec 2025, 10:30AM</span></td>
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
                                        <a class="dropdown-item" href="#">Edit </a>
                                        <a class="dropdown-item" href="#">View </a>
                                        <a class="dropdown-item" href="#">Delete </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Drug Brand Value</td>
                            <td><span class="text-nowrap">18 Dec 2025, 10:30AM</span></td>
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
                                        <a class="dropdown-item" href="#">Edit </a>
                                        <a class="dropdown-item" href="#">View </a>
                                        <a class="dropdown-item" href="#">Delete </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Drug Brand Value</td>
                            <td><span class="text-nowrap">18 Dec 2025, 10:30AM</span></td>
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
                                        <a class="dropdown-item" href="#">Edit </a>
                                        <a class="dropdown-item" href="#">View </a>
                                        <a class="dropdown-item" href="#">Delete </a>
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
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="appointmentModalLabel">Add New Drug Brand</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="book_appointment_form" action="#" class="custom-form">
                    <input type="hidden" name="_token" value="6Vnw3gGH9qhBt8CnRVRcj1UgPHZdKIxuEw0kGA2c" autocomplete="off">                    <div class="row">
                         

                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Title</label>
                                <div class="position-relative">
                                    <input type="text" name="" class="form-control" id="" placeholder="Enter" />
                                </div>
                            </div>
                        

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="bookTypeSelct">Status </label>
                                    <div class="position-relative">
                                        <select name="bookTypeSelct" id="bookTypeSelct" class="select2-single" data-placeholder="Select Status">
                                            <option></option>
                                            <option value="1" >Active</option>
                                            <option value="2" >In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
                    <button type="button" id="book_appointment" class="btn btn-primary">Save Details</button>
                </div>
                </div>
            </div>
        </div>

@endsection

@section('footerJs')
    

@endsection