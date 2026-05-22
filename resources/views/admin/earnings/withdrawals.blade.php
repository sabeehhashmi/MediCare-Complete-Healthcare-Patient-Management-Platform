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
    .nav-tabs .nav-link {
        border: none;
        padding: 10px 20px;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 2px solid #0d6efd;
        color: #0d6efd;
        background: transparent;
    }
    .details-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
    }
    .details-table th {
        padding: 10px;
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@stop

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
@stop

@section('content')
<div class="card mb-5">
    <div class="card-header">
        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                <h4>{{ $page_heading ?? 'Withdrawal Requests' }}</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.earnings.exportWithdrawals') }}" class="btn btn-success">
                        <i class="bx bx-download"></i> Export CSV
                    </a>
                </div>
            </div>
            <ul class="nav nav-tabs card-header-tabs mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.earnings.index') }}">
                        <i class="bx bx-dollar-circle"></i> Commission Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.earnings.withdrawals') }}">
                        <i class="bx bx-money-withdraw"></i> Withdrawal Requests
                        @php
                            $pendingCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3 mx-2">
        <div class="col-md-3">
            <div class="summary-card bg-warning text-dark">
                <h5>Pending Withdrawals</h5>
                <h3>{{ number_format($pendingTotal, 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-info text-white">
                <h5>Approved Withdrawals</h5>
                <h3>{{ number_format($approvedTotal, 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-success text-white">
                <h5>Paid Withdrawals</h5>
                <h3>{{ number_format($paidTotal, 2) }} AED</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-danger text-white">
                <h5>Rejected Withdrawals</h5>
                <h3>{{ number_format($rejectedTotal, 2) }} AED</h3>
            </div>
        </div>
    </div>

    <form action="#" id="search-form" class="mt-3 mx-2">
        <div class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="form-label">Doctor</label>
                <div class="position-relative select-custom-icon">
                    <select name="doctor_id" id="doctor_id" class="select2-single" data-placeholder="Select Doctor">
                        <option></option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Status</label>
                <div class="position-relative select-custom-icon">
                    <select name="status" id="status" class="select2-single" data-placeholder="Select Status">
                        <option></option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">From Date</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="from_date" id="from_date" class="form-control flatpickr" placeholder="From Date">
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">To Date</label>
                <div class="position-relative input-custom-icon">
                    <input type="text" name="to_date" id="to_date" class="form-control flatpickr" placeholder="To Date">
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <label>&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" id="clear-search" class="btn btn-dark">Clear</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="withdrawals_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor Name</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewWithdrawalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="withdrawalDetails">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paidForm">
                @csrf
                <input type="hidden" name="id" id="withdrawalId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Transaction ID <span class="text-danger">*</span></label>
                            <input type="text" name="transaction_id" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Withdrawal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <input type="hidden" name="id" id="rejectWithdrawalId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
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
@endsection

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    flatpickr("#from_date, #to_date", { 
        dateFormat: "d-m-Y",
        allowInput: true
    });
    
    $('.select2-single').select2({
        placeholder: $(this).data('placeholder'),
    });
    
    var table = $('#withdrawals_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.earnings.loadWithdrawals") }}',
            type: 'POST',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
                d.doctor_id = $('#doctor_id').val();
                d.status = $('#status').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [
            { data: 'sl_no', orderable: false, searchable: false },
            { data: 'doctor_name', name: 'doctor_name' },
            { data: 'amount_formatted', name: 'amount' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'request_date', name: 'created_at' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        order: [[4, 'desc']],
        language: {
            loadingRecords: "No Data Available",
        }
    });
    
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    
    $('#clear-search').on('click', function() {
        $('#doctor_id').val(null).trigger('change');
        $('#status').val(null).trigger('change');
        $('#from_date').val('');
        $('#to_date').val('');
        table.ajax.reload();
    });
    
    // View withdrawal details via AJAX
    $(document).on('click', '.view-withdrawal-details', function() {
        var id = $(this).data('id');
        $('#viewWithdrawalModal').modal('show');
        $('#withdrawalDetails').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.ajax({
            url: '{{ url("admin/earnings/get-withdrawal-details") }}/' + id,
            type: 'GET',
            success: function(res) {
                if (res.status == 1) {
                    var data = res.data;
                    var statusColor = 'warning';
                    if (data.status === 'Approved') statusColor = 'info';
                    else if (data.status === 'Paid') statusColor = 'success';
                    else if (data.status === 'Rejected') statusColor = 'danger';
                    
                    var html = `
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">Doctor Name:</th>
                                <td>${data.doctor_name}</td>
                            </tr>
                            <tr>
                                <th>Doctor Email:</th>
                                <td>${data.doctor_email}</td>
                            </tr>
                            
                            <tr>
                                <th>Amount:</th>
                                <td><strong>${data.amount} AED</strong></td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td>${data.payment_method}</td>
                            </tr>
                           
                            <tr>
                                <th>Request Date:</th>
                                <td>${data.request_date}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge bg-${statusColor}">${data.status}</span></td>
                            </tr>`;
                    
                    if (data.approved_by !== 'N/A') {
                        html += `<tr><th>Approved By:</th><td>${data.approved_by}</td></tr>`;
                    }
                    if (data.approved_at !== 'N/A') {
                        html += `<tr><th>Approved Date:</th><td>${data.approved_at}</td></tr>`;
                    }
                    if (data.transaction_id !== 'N/A') {
                        html += `<tr><th>Transaction ID:</th><td>${data.transaction_id}</td></tr>`;
                    }
                    if (data.paid_at !== 'N/A') {
                        html += `<tr><th>Paid Date:</th><td>${data.paid_at}</td></tr>`;
                    }
                    if (data.admin_notes !== 'N/A') {
                        html += `<tr><th>Admin Notes:</th><td>${data.admin_notes}</td></tr>`;
                    }
                    
                    html += `</table>`;
                    $('#withdrawalDetails').html(html);
                } else {
                    $('#withdrawalDetails').html('<div class="alert alert-danger">' + (res.message || 'Error loading details') + '</div>');
                }
            },
            error: function() {
                $('#withdrawalDetails').html('<div class="alert alert-danger">Failed to load withdrawal details</div>');
            }
        });
    });
    
    // Approve withdrawal
    $(document).on('click', '.approve-withdrawal', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.earnings.approveWithdrawal") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function(res) {
                if(res.status) {
                    App.alert(res.message, 'Success');
                    table.ajax.reload();
                } else {
                    App.alert(res.message, 'Error', 'error');
                }
            },
            error: function() {
                App.alert('Something went wrong', 'Error', 'error');
            }
        });
    });
    
    // Mark as paid
    $(document).on('click', '.mark-paid-withdrawal', function() {
        $('#withdrawalId').val($(this).data('id'));
        $('#paidModal').modal('show');
    });
    
    $('#paidForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.earnings.markWithdrawalPaid") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#paidModal').modal('hide');
                App.alert(res.message, 'Success');
                table.ajax.reload();
                $('#paidForm')[0].reset();
            },
            error: function() {
                App.alert('Something went wrong', 'Error', 'error');
            }
        });
    });
    
    // Reject withdrawal
    $(document).on('click', '.reject-withdrawal', function() {
        $('#rejectWithdrawalId').val($(this).data('id'));
        $('#rejectModal').modal('show');
    });
    
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.earnings.rejectWithdrawal") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#rejectModal').modal('hide');
                App.alert(res.message, 'Success');
                table.ajax.reload();
                $('#rejectForm')[0].reset();
            },
            error: function() {
                App.alert('Something went wrong', 'Error', 'error');
            }
        });
    });
});
</script>
@stop