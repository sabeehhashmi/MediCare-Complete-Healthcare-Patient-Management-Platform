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
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered align-middle" id="table_list">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Booking Id</th>
                                                            <th>Doctor Name</th>
                                                            <th>Time Slot</th>
                                                            <th>Patient Name</th>
                                                            <th>Booking Status</th>
                                                            <th>Booking Date</th>
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
@endsection

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
@stop
@section('custom_js')
    <script>

        $(document).ready(function() {
            var table = $('#table_list').DataTable({
                processing: true,
                serverSide: true,
                filter: true,
                searching: true,
                ajax: {
                    'type': 'POST',
                    'url': '{{ route("patients.MyAppointmentLoadData") }}',
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
                    { data: 'booking_id', name: 'booking_id'},
                    { data: 'doctor_first_name', name: 'doctor_first_name'},
                    { data: 'booking_time_slot', name: 'booking_time_slot'},
                    { data: 'patient_name', name: 'patient_name'},
                    { data: 'booking_status', name: 'booking_status'},
                    { data: 'booking_date', name: 'booking_date'},
                    { data: 'action', orderable: false, searchable: false }
                ],
                // dom: 'Bfrtip',
                // buttons: [
                        
                //         {
                //             extend: 'excelHtml5',
                //             text: '<i class="mdi mdi-file-excel"></i>',
                //             titleAttr: 'Excel'
                //         },
                //     ],
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

        // Handle signup form submission
        $('#update-profile-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var $form = $(this);
            var formData = new FormData(this);
            $form.find('button[type="submit"]').text('Processing..').attr('disabled', true);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $form.find('button[type="submit"]').text('Update').attr('disabled', false);
                    // console.log(JSON.parse(response));
                    console.log(response);
                    if(response.success == "1"){
                        App.alert(response.message || 'Profile Updated successfully', 'Success!','success');
                        setTimeout(function () {
                            window.location.href = "{{url('/website/patient-profile')}}";
                        }, 1500);
                    }else{
                        App.alert(response.message || 'Failed to Update Profile', 'Fail!','error');
                        if(response.errors){
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
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $form.find('button[type="submit"]').text('Update').attr('disabled', false);
                    App.alert('Something Went wrong', 'Fail!','error');
                }
            });
        });

        $(document).ready(function() {
            // getLocation();
        });
        
        // function lodSubIncurance(incuranceId){
        //     if (incuranceId) {
        //         $('#sub-insurance-policy').html('<option value="" disabled>Loading..</option>');
        //         $.ajax({
        //             type: "GET",
        //             url: "{{ url('get-sub-insurance') }}/" + incuranceId,
        //             success: function (res) {
        //                 if (res) {
        //                     $('#sub-insurance-policy').html('<option value="">My Insurance Network</option>');
        //                     $.each(res, function (index, data) {
        //                         $('#sub-insurance-policy').append('<option value="' + data.id+'">' + data.title + '</option>');
        //                     });
        //                     // $('#sub-insurance-policy').val(selectedId).trigger('change');
        //                     $('#sub-insurance-policy').select2(); // Reinitialize select2
        //                 }
        //             },
        //             error: function (xhr, status, error) {
        //                 console.error('Error fetching Members:', error);
        //             }
        //         });
        //     }else {
        //         $('#sub-insurance-policy').empty();
        //         $('#sub-insurance-policy').append('<option value=""></option>');
        //     }
        // }

        // $('#insurance-policy').on('change', function(){
        //     lodSubIncurance($(this).val());
        // })
        
    </script>
@endsection