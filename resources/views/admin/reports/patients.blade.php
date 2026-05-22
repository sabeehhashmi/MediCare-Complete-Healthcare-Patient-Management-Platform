@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.confirmed { background: #d4edda; color: #155724; }
        .status-badge.completed { background: #cce5ff; color: #004085; }
        .status-badge.cancelled { background: #f8d7da; color: #721c24; }
        .status-badge.rescheduled { background: #d1ecf1; color: #0c5460; }
        .modal-xl {
            max-width: 1200px;
        }
    </style>
@stop

@section("content")

<div class="card mb-5">
    <div class="card-header">
        <h4 class="mb-0">{{ $page_heading }}</h4>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.reports.patients') }}" id="filter-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">From Date</label>
                    <input type="text" name="from_date" class="form-control datepicker" value="{{ request('from_date') }}" placeholder="From Date">
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">To Date</label>
                    <input type="text" name="to_date" class="form-control datepicker" value="{{ request('to_date') }}" placeholder="To Date">
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select select2">
                        <option value="">All</option>
                        <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>Male</option>
                        <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>Female</option>
                        <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select select2">
                        <option value="">All</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, Email, Phone, Patient ID">
                </div>
                <div class="col-sm mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.reports.patients') }}" class="btn btn-dark">Reset</a>
                        <a href="{{ route('admin.reports.patients.export', request()->all()) }}" class="btn btn-success">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Patient ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>DOB/Age</th>
                        <th>Members</th>
                        <th>Appointments</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $key => $patient)
                    <tr>
                        <td>{{ $patients->firstItem() + $key }}</td>
                        <td>{{ $patient->patient_id ?? 'N/A' }}</td>
                        <td>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                            @if($patient->email_verified_at)
                                <i class="bx bx-check-circle text-success" title="Verified"></i>
                            @endif
                        </td>
                        <td>{{ $patient->email }}</td>
                        <td>+{{ $patient->dial_code }}{{ $patient->phone }}</td>
                        <td>
                            @if($patient->gender == 1) Male
                            @elseif($patient->gender == 2) Female
                            @else Other @endif
                        </td>
                        <td>
                            @if($patient->dob)
                                {{ \Carbon\Carbon::parse($patient->dob)->format('d-m-Y') }}
                                ({{ \Carbon\Carbon::parse($patient->dob)->age }} yrs)
                            @else N/A @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $patient->member_count }}</span>
                            <button class="btn btn-sm btn-link view-members p-0 ms-1" 
                                    data-id="{{ $patient->id }}" 
                                    data-name="{{ $patient->first_name }} {{ $patient->last_name }}">
                                View
                            </button>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $patient->appointment_count }}</span>
                            <button class="btn btn-sm btn-link view-appointments p-0 ms-1" 
                                    data-id="{{ $patient->id }}" 
                                    data-name="{{ $patient->first_name }} {{ $patient->last_name }}">
                                View
                            </button>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($patient->created_at)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $patient->active == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $patient->active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.patients.edit', ['id' => $patient->id]) }}" target="_blank">
                                        <i class="bx bx-edit"></i> Edit Patient
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.patients.members', ['id' => $patient->id]) }}" target="_blank">
                                        <i class="bx bx-group"></i> Manage Members
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No patients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $patients->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<!-- Members Modal -->
<div class="modal fade" id="membersModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Patient Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="membersList"></div>
            </div>
        </div>
    </div>
</div>

<!-- Appointments Modal -->
<div class="modal fade" id="appointmentsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Patient Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentsList"></div>
            </div>
        </div>
    </div>
</div>

@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Date picker
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true
    });

    // Select2
    $('.select2').select2({
        width: '100%'
    });

    // View Members - Using ReportsController
    $('.view-members').click(function() {
        var patientId = $(this).data('id');
        var patientName = $(this).data('name');
        $('#membersModal .modal-title').text('Members of ' + patientName);
        
        $.ajax({
            url: "{{ route('admin.get.patient.members', '') }}/" + patientId,
            type: "GET",
            dataType: "json",
            success: function(res) {
                var html = '';
                if(res.success && res.data && res.data.length > 0) {
                    html = '<div class="table-responsive"><table class="table table-bordered">';
                    html += '<thead><tr><th>#</th><th>Full Name</th><th>Age</th><th>Gender</th><th>Insurance</th><th>Created Date</th></tr></thead><tbody>';
                    $.each(res.data, function(i, member) {
                        html += '<tr>';
                        html += '<td>' + (i+1) + '</td>';
                        html += '<td>' + (member.full_name || 'N/A') + '</td>';
                        html += '<td>' + (member.age || 'N/A') + ' yrs</td>';
                        html += '<td>' + (member.gender || 'N/A') + '</td>';
                        html += '<td>' + (member.insurance || 'N/A') + '</td>';
                        html += '<td>' + (member.created_at || 'N/A') + '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table></div>';
                } else {
                    html = '<p class="text-center text-muted">No members found for this patient.</p>';
                }
                $('#membersList').html(html);
                $('#membersModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                $('#membersList').html('<p class="text-center text-danger">Error loading members. Please try again.</p>');
                $('#membersModal').modal('show');
            }
        });
    });

    // View Appointments - Using ReportsController
    $('.view-appointments').click(function() {
        var patientId = $(this).data('id');
        var patientName = $(this).data('name');
        $('#appointmentsModal .modal-title').text('Appointments of ' + patientName);
        
        $.ajax({
            url: "{{ route('admin.get.patient.appointments') }}",
            type: "POST",
            data: {
                patient_id: patientId,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(res) {
                var html = '';
                if(res.success && res.data && res.data.length > 0) {
                    html = '<div class="table-responsive"><table class="table table-bordered">';
                    html += '<thead><tr>';
                    html += '<th>Booking ID</th>';
                    html += '<th>Doctor</th>';
                    html += '<th>Hospital</th>';
                    html += '<th>Booking Type</th>';
                    html += '<th>Date</th>';
                    html += '<th>Time</th>';
                    html += '<th>Status</th>';
                    html += '<th>Action</th>';
                    html += '</thead><tbody>';
                    
                    $.each(res.data, function(i, appointment) {
                        html += '<tr>';
                        html += '<td>' + (appointment.booking_id || 'N/A') + '</td>';
                        html += '<td>' + (appointment.doctor_name || 'N/A') + '</td>';
                        html += '<td>' + (appointment.hospital_name || 'N/A') + '</td>';
                        html += '<td>' + (appointment.booking_type || 'N/A') + '</td>';
                        html += '<td>' + (appointment.booking_date || 'N/A') + '</td>';
                        html += '<td>' + (appointment.booking_time_slot || 'N/A') + '</td>';
                        html += '<td>';
                        html += '<span class="status-badge ' + (appointment.status_class || 'pending') + '">' + (appointment.booking_status || 'N/A') + '</span>';
                        html += '</td>';
                        html += '<td><a href="' + appointment.view_url + '" class="btn btn-sm btn-primary" target="_blank">View</a></td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table></div>';
                } else {
                    html = '<p class="text-center text-muted">No appointments found for this patient.</p>';
                }
                $('#appointmentsList').html(html);
                $('#appointmentsModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                $('#appointmentsList').html('<p class="text-center text-danger">Error loading appointments. Please try again.</p>');
                $('#appointmentsModal').modal('show');
            }
        });
    });
</script>
@stop