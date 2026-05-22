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
    </style>
@stop

@section("content")

<div class="card mb-5">
    <div class="card-header">
        <h4 class="mb-0">{{ $page_heading }}</h4>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.reports.appointments') }}" id="filter-form">
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
                    <label class="form-label">Hospital</label>
                    <select name="hospital_id" class="form-select select2">
                        <option value="">All Hospitals</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>{{ $hospital->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Doctor</label>
                    <select name="doctor_id" class="form-select select2">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->user->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="booking_status" class="form-select select2">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('booking_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rescheduled" {{ request('booking_status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Booking Type</label>
                    <select name="booking_type" class="form-select select2">
                        <option value="">All Types</option>
                        @foreach($bookingTypes as $type)
                            <option value="{{ $type->name }}" {{ request('booking_type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.reports.appointments') }}" class="btn btn-dark">Reset</a>
                        <a href="{{ route('admin.reports.appointments.export', request()->all()) }}" class="btn btn-success">
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
                        <th>Booking ID</th>
                        <th>Hospital</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Booking Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $key => $appointment)
                    <tr>
                        <td>{{ $appointments->firstItem() + $key }}</td>
                        <td>{{ $appointment->booking_id }}</td>
                        <td>{{ $appointment->hospital->name_en ?? 'N/A' }}</td>
                        <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                        <td>
                            {{ $appointment->user->first_name }} {{ $appointment->user->last_name }}
                            @if($appointment->member)
                                <br><small class="text-muted">(Member: {{ $appointment->member->full_name }})</small>
                            @endif
                        </td>
                        <td>{{ $appointment->booking_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->booking_date)->format('d-m-Y') }}</td>
                        <td>{{ $appointment->booking_time_slot }}</td>
                        <td>
                            <span class="status-badge {{ strtolower($appointment->booking_status) }}">
                                {{ ucfirst($appointment->booking_status) }}
                            </span>
                        </td>
                        <td>{{ $appointment->created_by_user->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.appointments.view', ['id' => $appointment->id]) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No appointments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $appointments->appends(request()->all())->links() }}
        </div>
    </div>
</div>

@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true
    });

    $('.select2').select2({
        width: '100%'
    });
</script>
@stop