@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .summary-card:hover {
            transform: translateY(-5px);
        }
        .summary-card.primary { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .summary-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .summary-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .summary-number {
            font-size: 36px;
            font-weight: bold;
        }
        .summary-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .filter-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
@stop

@section("content")

<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $page_heading }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active">Financial Report</li>
    </ol>

    <!-- Filter Form -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.reports.financial') }}" id="filter-form">
            <div class="row align-items-end">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="text" name="from_date" class="form-control datepicker" value="{{ $fromDate->format('d-m-Y') }}" placeholder="From Date">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="text" name="to_date" class="form-control datepicker" value="{{ $toDate->format('d-m-Y') }}" placeholder="To Date">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search"></i> Apply Filter
                        </button>
                        <a href="{{ route('admin.reports.financial') }}" class="btn btn-dark">
                            <i class="bx bx-refresh"></i> Reset
                        </a>
                        <a href="{{ route('admin.reports.financial.export', request()->all()) }}" class="btn btn-success">
                            <i class="bx bx-file"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards Row -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="summary-card primary">
                                <div class="summary-number">AED {{ number_format($totalConsultationFees, 2) }}</div>
                <div class="summary-label mt-2">Total Consultation Fees</div>
                <small>Across all doctors</small>
                <i class="bx bx-dollar-circle stats-icon" style="position: absolute; right: 20px; top: 20px; font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card warning">
                <div class="summary-number">{{ $completedAppointments }}</div>
                <div class="summary-label mt-2">Completed Appointments</div>
                <small>In selected period</small>
                <i class="bx bx-calendar-check stats-icon" style="position: absolute; right: 20px; top: 20px; font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card info">
                <div class="summary-number">AED {{ $totalDoctors > 0 ? number_format($totalConsultationFees / max($totalDoctors, 1), 2) : '0.00' }}</div>
                <div class="summary-label mt-2">Average Consultation Fee</div>
                <small>Per doctor average</small>
                <i class="bx bx-calculator stats-icon" style="position: absolute; right: 20px; top: 20px; font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bx bx-line-chart me-1"></i>
                    Monthly Appointments Trend ({{ date('Y') }})
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bx bx-pie-chart me-1"></i>
                    Appointment Status Distribution
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="bx bx-table me-1"></i>
            Recent Completed Appointments (Last 30 Days)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="appointments-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking ID</th>
                            <th>Hospital</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Booking Type</th>
                            <th>Date</th>
                            <th>Consultation Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentAppointments = \App\Models\DoctorPatientAppointment::with(['doctor.user', 'user', 'hospital'])
                                ->where('booking_status', 'completed')
                                ->whereBetween('created_at', [\Carbon\Carbon::now()->subDays(30), \Carbon\Carbon::now()])
                                ->orderBy('id', 'desc')
                                ->limit(20)
                                ->get();
                        @endphp
                        @forelse($recentAppointments as $key => $appointment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $appointment->booking_id }}</td>
                            <td>{{ $appointment->hospital->name_en ?? 'N/A' }}</td>
                            <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</td>
                            <td>{{ $appointment->booking_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->booking_date)->format('d-m-Y') }}</td>
                            <td class="text-end">
                                AED {{ number_format(is_numeric($appointment->doctor->user->consultation_fee ?? 0) ? floatval($appointment->doctor->user->consultation_fee) : 0, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No completed appointments found in the last 30 days</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="7" class="text-end">Total:</td>
                            <td class="text-end">
                                AED {{ number_format($recentAppointments->sum(function($app) {
                                    $fee = $app->doctor->user->consultation_fee ?? 0;
                                    return is_numeric($fee) ? floatval($fee) : 0;
                                }), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>
<script>
    // Initialize date pickers
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true,
        maxDate: "today"
    });

    // Monthly Revenue Chart
    var monthlyData = @json($monthlyRevenue);
    var months = monthlyData.map(item => {
        // Convert YYYY-MM to Month name
        let [year, month] = item.month.split('-');
        let date = new Date(year, month - 1);
        return date.toLocaleString('default', { month: 'short' }) + ' ' + year;
    });
    var totals = monthlyData.map(item => item.total_appointments);

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Completed Appointments',
                data: totals,
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Appointments: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Number of Appointments'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    var statusData = @json($statusDistribution);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'Rescheduled'],
            datasets: [{
                data: [
                    statusData.pending || 0,
                    statusData.confirmed || 0,
                    statusData.completed || 0,
                    statusData.cancelled || 0,
                    statusData.rescheduled || 0
                ],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545', '#6c757d'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@stop