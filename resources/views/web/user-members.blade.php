@extends('web.template.layout')

@section('title', 'Home')
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop
@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content">
                    <div class="container-fluid">

                        <div class="position-relative mb-5">
                            <div class="d-lg-flex">
                                @include('web.profile-sidebar')
                                <!-- end chat-leftsidebar -->
                        
                                <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#event-modal" id="add-patient" class="btn btn-outline-primary">Add Patient </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle" id="table_list">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Age</th>
                                                        <th>Gender</th>
                                                        <th>My Insurance Policy</th>
                                                        <th>My Insurance Network</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>

                                </div>
                                    <!-- end user chat -->

                            </div>
                    <!-- container-fluid -->
                    </div>
            <!-- </div> -->
            <!-- end main content-->

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Add Patient</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="{{url('web/save-members')}}" method="POST" class="custom-form" id="save-patient-form">
                            @csrf
                            <input type="hidden" name="patient" value="{{Auth::User()->id}}">
                            <div class="modal-body p-4">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="image">Image</label>
                                        <div class="position-relative">
                                            <input type="file" name="image" class="form-control"  accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Full Name</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control no-icon full_name" id="" name="full_name" placeholder="Enter Full Name" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">Gender </label>
                                        <div class="position-relative">
                                            <select name="gender" id="" class="GenderModal select2-single no-icon" data-placeholder="Gender">
                                                <option></option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                                <option value="3">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Age</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control no-icon age" name="age" placeholder="Enter Age" maxlength="3" pattern="\d{1,3}" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Policy </label>
                                        <div class="position-relative">
                                            <select name="insurence_id" id="" class="insurance-policy select2-single no-icon" data-placeholder="My Insurance Policy">
                                            <option value="">My Insurance Policy</option>
                                            @foreach($insurencePolicies as $id => $value)
                                            <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Network </label>
                                        <div class="position-relative">
                                            <select name="sub_insurence_id" id="" class="sub-insurance-policy select2-single no-icon" data-placeholder="My Insurance Network">
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-12 d-inline-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary" id="add-more">Add More</a>
                                    </div> -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="finish-continue">Finish Adding</button>
                            </div>
                        </form>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
            
            <div class="modal fade" id="edit-event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Update Patient</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="{{url('web/save-members')}}" method="POST" class="custom-form" id="update-patient-form">
                            @csrf
                            <input type="hidden" name="id" id="member-id">
                            <input type="hidden" name="patient" value="{{Auth::User()->id}}">
                            <div class="modal-body p-4">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="image">Image</label>
                                        <div class="position-relative">
                                            <input type="file" name="image" class="form-control"  accept="image/*">
                                        </div>
                                        <div class="postion-relative user_img_url">
                                            
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Full Name</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control no-icon full_name" id="" name="full_name" placeholder="Enter Full Name" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">Gender </label>
                                        <div class="position-relative">
                                            <select name="gender" id="" class="GenderModal select2-single no-icon" data-placeholder="Gender">
                                                <option></option>
                                                <option value="1">Female</option>
                                                <option value="2">Male</option>
                                                <option value="3">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Age</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control no-icon age" name="age" placeholder="Enter Age" maxlength="3" pattern="\d{1,3}" />
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Policy </label>
                                        <div class="position-relative">
                                            <select name="insurence_id" id="" class="insurance-policy select2-single no-icon" data-placeholder="My Insurance Policy">
                                            <option value="">My Insurance Policy</option>
                                            @foreach($insurencePolicies as $id => $value)
                                            <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="">My Insurance Network </label>
                                        <div class="position-relative">
                                            <select  name="sub_insurence_id" id="" class="sub-insurance-policy sub_insurence_id select2-single no-icon" data-placeholder="My Insurance Network">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect waves-light" style="width: 120px;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="update-finish">Update</button>
                            </div>
                        </form>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
            <!-- end modal-->
@endsection

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
@stop
@section('custom_js')
    <script>
            var table = $('#table_list').DataTable({
                processing: true,
                serverSide: true,
                filter: true,
                searching: true,
                ajax: {
                    'type': 'POST',
                    'url': '{{ route("web.load-members") }}',
                    'data': function(d) {
                        // Send additional parameters to the server
                        d._token = '{{ csrf_token() }}';
                        d.patient_id = '{{ Auth::User()->id }}';
                        d.search['filters'] = $('#search-form').serializeArray().reduce(function(obj, item) {
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
                    }
                },
                columns: [
                    { data: 'sl_no', orderable: false, searchable: false },
                    { data: 'full_name', name: 'full_name'},
                    { data: 'age', name: 'age'},
                    { data: 'gender', name: 'gender'},
                    { data: 'insurence_policy', name: 'insurence_id'},
                    { data: 'sub_insurence_policy', name: 'sub_insurence_id'},
                    { data: 'action', orderable: false, searchable: false }
                ],
                    lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    pageLength: 10,
                    order: [],
                    language: {
                        loadingRecords: "No Data Available",
                    },
                    
            });

            // Implement search functionality
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
            
        $(document).ready(function() {

            $(document).on('click', '.edit-member', function() {
                var data = $(this).data('member_data')
                $('#edit-event-modal').modal('show');
                if(data.user_img_url !==''){
                    $('.user_img_url').html('<img src="'+data.user_img_url+'">')
                }
                else
                {
                    $('.user_img_url').html('');
                }
                $('.full_name').val(data.full_name)
                $('.GenderModal').val(data.gender)
                $('.age').val(data.age)
                $('.insurance-policy').val(data.insurence_id)
                $('#member-id').val(data.id)
                lodSubIncurance('edit-event-modal', data.insurence_id, data.sub_insurence_id);
                $(".GenderModal, .insurance-policy").select2({ dropdownParent: "#edit-event-modal" });
            });
            
            $(document).on('click', '.delete-member', function() {
                var id = $(this).data('id');
                var msg = 'Are you sure you want to delete this member?';
                var href = '{{ url("web/delete-member") }}/' + id;

                App.confirm('Confirm Delete', msg, function() {
                    var ajxReq = $.ajax({
                        url: href,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.status == 1) {
                                App.alert(res.message || 'Deleted successfully', 'Success!');
                                setTimeout(function() {
                                    // Destroy and reload the datatable to reflect the changes
                                    $('#table_list').DataTable().destroy();
                                    location.reload();
                                }, 1500);
                            } else {
                                App.alert(res.message || 'Unable to delete the record.', 'Failed!');
                            }
                        },
                        error: function(jqXhr, textStatus, errorMessage) {
                            App.alert('An error occurred while trying to delete the appointment.', 'Error');
                        }
                    });
                });
            });

            // Optional: Clear the search form
            $('#clear-search').on('click', function(e) {
                e.preventDefault();
                let form = $('#search-form')[0];    
                form.reset();  // Reset the search form
                $(form).find('select').prop('selectedIndex', 0);
            
                $(form).find('select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).val(null).trigger('change');
                    }
                });
                table.ajax.reload();
            });
            
        });

        function lodSubIncurance(modal = 'event-modal', incuranceId, selected_id = null){
            if (incuranceId) {
                $('.sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('get-sub-insurance') }}/" + incuranceId,
                    success: function (res) {
                        if (res) {
                            $('.sub-insurance-policy').html('<option value="">My Insurance Network</option>');
                            $.each(res, function (index, data) {
                                $('.sub-insurance-policy').append('<option '+ (selected_id == data.id ? 'selected' : '') +' value="' + data.id+'">' + data.title + '</option>');
                            });
                            // $('.sub-insurance-policy').val(selectedId).trigger('change');
                            $('.sub-insurance-policy').select2({ dropdownParent: "#"+modal })
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching Members:', error);
                    }
                });
            }else {
                $('.sub-insurance-policy').empty();
                $('.sub-insurance-policy').append('<option value=""></option>');
            }
        }

        function resetPatientForm(){
            var form = $('#save-patient-form')[0];
            form.reset();
            $(form).find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="tel"], input[type="url"]').val('');
            $(".GenderModal, .insurance-policy, .sub-insurance-policy").select2({ dropdownParent: "#event-modal" });
        }
        function savePatient(close = false){
            event.preventDefault();
            var $form = $('#save-patient-form');
            let formData = $('#save-patient-form').serialize();
            $('#add-mode').text('Processing..').attr('disabled', true);
            $('#finish-continue').attr('disabled', true);
            $.ajax({
                url: $('#save-patient-form').attr('action'),
                method: $('#save-patient-form').attr('method'),
                data: formData,
                success: function(response) {
                    $('#add-mode').text('Add More').attr('disabled', false);
                    $('#finish-continue').attr('disabled', false);
                    if (response.status == '1') {
                        App.alert(response['message'] || 'Patients saved successfully', 'Success!', 'success');
                        table.ajax.reload();
                        if(close){
                            $('#event-modal').modal('hide');
                        }else{
                            resetPatientForm();
                        }
                    } else {
                        // Handle error response
                        if(response.errors){
                           // App.alert(response.message || 'Something went wrong', 'Fail!','error');
                            jQuery.each(response.errors, function (e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                }
                            });
                        } else {
                            App.alert(response.message || 'Failed to Verify OTP', 'Fail!','error');
                            $('#add-mode').text('Add More').attr('disabled', false);
                            $('#finish-continue').attr('disabled', false);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    App.alert(res['message'] || 'Someting went wrong', 'Fail!', 'error');
                }
            });
        }
        
        function updatePatient(){
            event.preventDefault();
            var $form = $('#update-patient-form');
            let formData = $('#update-patient-form').serialize();
            $('#update-finish').text('Processing..').attr('disabled', true);
            $.ajax({
                url: $('#update-patient-form').attr('action'),
                method: $('#update-patient-form').attr('method'),
                data: formData,
                success: function(response) {
                    $('#update-finish').text('Update').attr('disabled', false);
                    if (response.status == '1') {
                        App.alert(response['message'] || 'Patients updated successfully', 'Success!', 'success');
                        $('#edit-event-modal').modal('hide');
                        table.ajax.reload();
                    } else {
                        // Handle error response
                        if(response.errors){
                            
                            jQuery.each(response.errors, function (e_field, e_message) {
                                if (e_message != '') {
                                   
                                    $('.'+ e_field).eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');

                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($(('.'+ e_field)).eq(0));
                    
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                }
                            });
                        } else {
                            App.alert(response.message || 'Failed to Verify OTP', 'Fail!','error');
                            $('#update-finish').text('Update').attr('disabled', false);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    App.alert(res['message'] || 'Someting went wrong', 'Fail!', 'error');
                }
            });
        }
        $('#add-more').on('click', function(event) {
            savePatient();
        });
        
        $('#finish-continue').on('click', function(event) {
            savePatient(true);
        });
        
        $('#update-finish').on('click', function(event) {
            updatePatient();
        });

        $('#event-modal .insurance-policy').on('change', function(){
            lodSubIncurance('event-modal', $(this).val());
        })
        $('#edit-event-modal .insurance-policy').on('change', function(){
            lodSubIncurance('edit-event-modal', $(this).val());
        })

        $(document).ready(function() {
            $(".GenderModal, .insurance-policy, .sub-insurance-policy").select2({ dropdownParent: "#event-modal" });
            $(".GenderModal, .insurance-policy, .sub-insurance-policy").select2({ dropdownParent: "#edit-event-modal" });
        });

        $('#add-patient').on('click', function(){
            resetPatientForm()
        })
        
    </script>
@endsection