@extends('admin.template.layout')

@section('css')
<style>
    .summary-card {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-5px);
    }
    .summary-card h5 {
        font-size: 14px;
        margin-bottom: 10px;
        opacity: 0.9;
    }
    .summary-card h3 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 0;
    }
</style>
@stop

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
@stop

@section("content")
<div class="card mb-5">
    <div class="card-header">
        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-between align-items-center">
                <h4>{{ $page_heading ?? 'Commission Management' }}</h4>
                <div class="d-flex gap-2">
                    <button type="button" id="exportBtn" class="btn btn-success">
                        <i class="bx bx-download"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mx-2">
        <div class="col-md-3">
            <div class="summary-card bg-primary text-white">
                <h5>Total Consultation Fee</h5>
                <h3>{{ number_format($summary['total_consultation_fee'], 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-info text-white">
                <h5>Total Admin Commission</h5>
                <h3>{{ number_format($summary['total_admin_commission'], 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-success text-white">
                <h5>Total Doctor Earning</h5>
                <h3>{{ number_format($summary['total_doctor_earning'], 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-warning text-dark">
                <h5>Pending Commission</h5>
                <h3>{{ number_format($summary['pending_commission'], 2) }} AED</h3>
            </div>
        </div>
    </div>

    <form action="#" id="search-form">
        <div class="row align-items-end mt-3 mx-2">
            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label">Doctor</label>
                <div class="position-relative select-custom-icon">
                    <select name="doctor_id" id="doctor_id" class="select2-single" data-placeholder="Select Doctor">
                        <option></option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label">Commission Status</label>
                <div class="position-relative select-custom-icon">
                    <select name="commission_status" id="commission_status" class="select2-single" data-placeholder="Select Status">
                        <option></option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label">From Date</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="from_date" class="form-control flatpicker-input1" id="from_date" placeholder="From Date">
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-2">
                <label class="form-label">To Date</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="to_date" class="form-control flatpicker-input1" id="to_date" placeholder="To Date">
                </div>
            </div>

            <div class="col-sm mb-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" id="clear-search" class="btn btn-dark waves-effect waves-light">Refresh</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="table_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking Id</th>
                        <th>Doctor Name</th>
                        <th>Consultation Fee</th>
                        <th>Admin Commission</th>
                        <th>Doctor Earning</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="commissionDetails">
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="paidModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paidForm">
                @csrf
                <input type="hidden" name="id" id="paidCommissionId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="text" name="payment_date" id="payment_date" class="form-control flatpicker-input" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Commission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <input type="hidden" name="id" id="rejectCommissionId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Reason for Rejection</label>
                            <textarea name="notes" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    var fromDate = flatpickr("#from_date", {
        dateFormat: "d-m-Y",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                toDate.set('minDate', selectedDates[0]);
            }
        }
    });

    var toDate = flatpickr("#to_date", {
        dateFormat: "d-m-Y",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                fromDate.set('maxDate', selectedDates[0]);
            }
        }
    });

    flatpickr("#payment_date", {
        dateFormat: "d-m-Y",
        allowInput: true,
        minDate: "today",
        defaultDate: "today"
    });

    $('.select2-single').select2({
        placeholder: $(this).data('placeholder'),
    });

    var table = $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching: true,
        ajax: {
            'type': 'POST',
            'url': '{{ route("admin.earnings.loadData") }}',
            'data': function(d) {
                d._token = '{{ csrf_token() }}';
                d.doctor_id = $('#doctor_id').val();
                d.commission_status = $('#commission_status').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [
            { data: 'sl_no', orderable: false, searchable: false },
            { data: 'booking_id', name: 'booking_id' },
            { data: 'doctor_name', name: 'doctor_name' },
            { data: 'consultation_fee', name: 'consultation_fee' },
            { data: 'admin_commission', name: 'admin_commission' },
            { data: 'doctor_earning', name: 'doctor_earning' },
            { data: 'commission_status_badge', name: 'commission_status' },
            { data: 'payment_date', name: 'payment_completed_at' },
            { data: 'action', orderable: false, searchable: false }
        ],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        language: {
            loadingRecords: "No Data Available",
        }
    });

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#clear-search').on('click', function() {
        $('#search-form')[0].reset();
        $('#doctor_id').val(null).trigger('change');
        $('#commission_status').val(null).trigger('change');
        table.ajax.reload();
    });

    $(document).on('click', '.approve-commission', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.earnings.approve") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function(res) {
                App.alert(res.message, 'Success');
                table.ajax.reload();
            }
        });
    });

    $(document).on('click', '.mark-paid', function() {
        $('#paidCommissionId').val($(this).data('id'));
        $('#paidModal').modal('show');
    });

    $('#paidForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.earnings.markPaid") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#paidModal').modal('hide');
                App.alert(res.message, 'Success');
                table.ajax.reload();
                $('#paidForm')[0].reset();
            }
        });
    });

    $(document).on('click', '.reject-commission', function() {
        $('#rejectCommissionId').val($(this).data('id'));
        $('#rejectModal').modal('show');
    });

    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.earnings.reject") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#rejectModal').modal('hide');
                App.alert(res.message, 'Success');
                table.ajax.reload();
                $('#rejectForm')[0].reset();
            }
        });
    });

    $(document).on('click', '.view-details', function() {
        var id = $(this).data('id');
        $('#viewModal').modal('show');
        $('#commissionDetails').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.ajax({
            url: '{{ url("admin/earnings/get-details") }}/' + id,
            type: 'GET',
            success: function(res) {
                if (res.status == 1) {
                    var data = res.data;
                    var statusColor = 'warning';
                    if (data.commission_status === 'Approved') statusColor = 'info';
                    else if (data.commission_status === 'Paid') statusColor = 'success';
                    else if (data.commission_status === 'Rejected') statusColor = 'danger';
                    
                    var html = `
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">Doctor Name:</th>
                                <td>${data.doctor_name}</td>
                            </tr>
                            <tr>
                                <th>Patient Name:</th>
                                <td>${data.patient_name}</td>
                            </tr>
                            <tr>
                                <th>Booking ID:</th>
                                <td>${data.booking_id}</td>
                            </tr>
                            <tr>
                                <th>Booking Date:</th>
                                <td>${data.booking_date}</td>
                            </tr>
                            <tr>
                                <th>Consultation Fee:</th>
                                <td>${data.consultation_fee} AED</td>
                            </tr>
                            <tr>
                                <th>Admin Commission :</th>
                                <td>${data.admin_commission} AED</td>
                            </tr>
                            <tr>
                                <th>Doctor Earning:</th>
                                <td>${data.doctor_earning} AED</td>
                            </tr>
                            <tr>
                                <th>Commission Status:</th>
                                <td><span class="badge bg-${statusColor}">${data.commission_status}</span></td>
                            </tr>
                            <tr>
                                <th>Payment Completed Date:</th>
                                <td>${data.payment_completed_at}</td>
                            </tr>`;
                    
                    if (data.commission_approved_at !== 'N/A') {
                        html += `<tr><th>Commission Approved Date:</th><td>${data.commission_approved_at}</td></tr>`;
                    }
                    if (data.commission_payment_date !== 'N/A') {
                        html += `<tr><th>Commission Paid Date:</th><td>${data.commission_payment_date}</td></tr>`;
                    }
                    if (data.commission_transaction_id !== 'N/A') {
                        html += `<tr><th>Transaction ID:</th><td>${data.commission_transaction_id}</td></tr>`;
                    }
                    if (data.commission_notes !== 'N/A') {
                        html += `<tr><th>Notes:</th><td>${data.commission_notes}</td></tr>`;
                    }
                    
                    html += `</table>`;
                    $('#commissionDetails').html(html);
                } else {
                    $('#commissionDetails').html('<div class="alert alert-danger">' + (res.message || 'Error loading details') + '</div>');
                }
            },
            error: function() {
                $('#commissionDetails').html('<div class="alert alert-danger">Failed to load commission details</div>');
            }
        });
    });

    $('#exportBtn').on('click', function() {
        var params = {
            doctor_id: $('#doctor_id').val(),
            commission_status: $('#commission_status').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };
        var queryString = $.param(params);
        window.location.href = '{{ route("admin.earnings.export") }}?' + queryString;
    });
});
</script>
@stop