@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-card.primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stats-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card.danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stats-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stats-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .stats-icon {
            font-size: 40px;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.confirmed { background: #d4edda; color: #155724; }
        .status-badge.completed { background: #cce5ff; color: #004085; }
        .status-badge.cancelled { background: #f8d7da; color: #721c24; }
        .status-badge.rescheduled { background: #d1ecf1; color: #0c5460; }
        .mini-stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .mini-stat-number {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
@stop

@section("content")

<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $page_heading }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>

    <!-- Stats Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card primary">
                <div class="stats-number">{{ $totalPatients }}</div>
                <div class="stats-label">Total Patients</div>
                <i class="bx bx-user stats-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number">{{ $totalDoctors }}</div>
                <div class="stats-label">Total Doctors</div>
                <i class="bx bx-user-md stats-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card warning">
                <div class="stats-number">{{ $totalHospitals + $totalClinics }}</div>
                <div class="stats-label">Total Facilities</div>
                <i class="bx bx-building stats-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card info">
                <div class="stats-number">{{ $totalAppointments }}</div>
                <div class="stats-label">Total Appointments</div>
                <i class="bx bx-calendar-check stats-icon"></i>
            </div>
        </div>
    </div>

    <!-- Appointment Status Mini Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-warning">{{ $pendingAppointments }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-success">{{ $confirmedAppointments }}</div>
                <div class="text-muted small">Confirmed</div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-info">{{ $completedAppointments }}</div>
                <div class="text-muted small">Completed</div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-danger">{{ $cancelledAppointments }}</div>
                <div class="text-muted small">Cancelled</div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-secondary">{{ $rescheduledAppointments }}</div>
                <div class="text-muted small">Rescheduled</div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="mini-stat-card">
                <div class="mini-stat-number text-primary">{{ $totalHospitals }}</div>
                <div class="text-muted small">Hospitals</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bx bx-pie-chart me-1"></i>
                    Appointment Status Distribution
                </div>
                <div class="card-body">
                    <canvas id="appointmentStatusChart" width="100%" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bx bx-line-chart me-1"></i>
                    Monthly Appointments ({{ date('Y') }})
                </div>
                <div class="card-body">
                    <canvas id="monthlyAppointmentsChart" width="100%" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Export Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bx bx-file me-1"></i>
                    Quick Export Reports
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reports.patients.export') }}" class="btn btn-outline-primary w-100">
                                <i class="bx bx-user"></i> Export Patients
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reports.appointments.export') }}" class="btn btn-outline-success w-100">
                                <i class="bx bx-calendar"></i> Export Appointments
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reports.doctors.export') }}" class="btn btn-outline-info w-100">
                                <i class="bx bx-user-md"></i> Export Doctors
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reports.hospitals.export') }}" class="btn btn-outline-warning w-100">
                                <i class="bx bx-building"></i> Export Hospitals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bx bx-time me-1"></i>
            Recent Appointments
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Hospital</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->booking_id }}</td>
                            <td>{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</td>
                            <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->hospital->name_en ?? 'N/A' }}</td>
                            <td>{{ Carbon\Carbon::parse($appointment->booking_date)->format('d-m-Y') }}</td>
                            <td>{{ $appointment->booking_time_slot }}</td>
                            <td>
                                <span class="status-badge {{ strtolower($appointment->booking_status) }}">
                                    {{ ucfirst($appointment->booking_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No recent appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section("page_script")
<script>
    // Appointment Status Chart
    var ctx1 = document.getElementById('appointmentStatusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'Rescheduled'],
            datasets: [{
                data: [{{ $pendingAppointments }}, {{ $confirmedAppointments }}, {{ $completedAppointments }}, {{ $cancelledAppointments }}, {{ $rescheduledAppointments }}],
                backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#dc3545', '#6c757d'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Appointments Chart
    var monthlyData = @json($monthlyAppointments);
    var months = monthlyData.map(item => item.month);
    var totals = monthlyData.map(item => item.total);

    var ctx2 = document.getElementById('monthlyAppointmentsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Appointments',
                data: totals,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@stop