@extends('admin.template.layout')

@section('css')
<style>
    .urgent-badge {
        background-color: #dc3545 !important;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
</style>
@stop

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section("content")

<div class="card mb-5">
    <div class="card-header">
        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-dark waves-effect waves-light">All Appointments</a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="alert alert-warning mb-3">
            <i class="bx bx-info-circle"></i> 
            Urgent appointments require immediate attention.
        </div>
        
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="urgent_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking Id</th>
                        <th>Doctor Name</th>
                        <th>Patient Name</th>
                        <th>Booking Date</th>
                        <th>Time Slot</th>
                        <th>Consultation Fee</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section("page_script")

<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var startIndex = 0;
    
    var table = $('#urgent_table').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching: true,
        ajax: {
            'type': 'POST',
            'url': '{{ route("admin.appointments.loadUrgentData") }}',
            'data': function(d) {
                d._token = '{{ csrf_token() }}';
            }
        },
        columns: [
            { 
                data: null, 
                orderable: false, 
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + 1 + (meta.settings._iDisplayStart || 0);
                }
            },
            { 
                data: 'booking_id', 
                name: 'booking_id',
                render: function(data) {
                    return data ;
                }
            },
            { data: 'dr_name', name: 'dr_name' },
            { data: 'patient_name', name: 'patient_name' },
            { data: 'booking_date', name: 'booking_date' },
            { data: 'booking_time_slot', name: 'booking_time_slot' },
            { data: 'consultation_fee', name: 'consultation_fee' },
            { 
                data: 'payment_status', 
                name: 'payment_status',
                render: function(data) {
                    if (data === 'Paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    } else if (data === 'Pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                    return '<span class="badge bg-secondary">' + data + '</span>';
                }
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false 
            }
        ],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        language: {
            loadingRecords: "No Urgent Appointments Available",
        }
    });
});
</script>

@stop