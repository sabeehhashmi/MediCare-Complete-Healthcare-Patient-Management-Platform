@include('doctor.template.header')

<style>
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    .status-badge.pending {
        background: #fff3e0;
        color: #ed6c02;
    }
    .status-badge.approved {
        background: #e3f2fd;
        color: #0288d1;
    }
    .status-badge.paid {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .status-badge.rejected {
        background: #ffebee;
        color: #c62828;
    }
    .status-badge.cancelled {
        background: #f5f5f5;
        color: #757575;
    }
</style>

<div class="mb-5 position-relative">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Withdrawal History</h5>
            <a href="{{ route('doctor.earnings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Back to Earnings
            </a>
        </div>
        <div class="card-body">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Admin Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ web_date_in_timezone($withdrawal->created_at, 'd M Y h:i A') }}</td>
                                <td class="fw-semibold">AED {{ number_format($withdrawal->amount, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
                                <td>
                                    <span class="status-badge {{ $withdrawal->status }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td>{{ $withdrawal->transaction_id ?? '-' }}</td>
                                <td>{{ $withdrawal->admin_notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-receipt fs-1"></i>
                    <p class="mb-0 mt-3">No withdrawal requests found</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('doctor.template.footer')