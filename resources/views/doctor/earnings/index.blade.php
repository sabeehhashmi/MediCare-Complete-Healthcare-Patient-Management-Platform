@include('doctor.template.header')

<style>
    .stat-card {
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
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
    .status-badge.completed {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
</style>

<div class="mb-5 position-relative">
    <!-- Statistics Cards -->
     <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-card bg-primary text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">Total Earned</small>
                        <h3 class="mt-1 mb-0">AED {{ number_format($wallet->total_earned, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bx bx-wallet fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3">
            <div class="stat-card bg-success text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">Available Balance</small>
                        <h3 class="mt-1 mb-0">AED {{ number_format($wallet->current_balance, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bx bx-dollar-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3">
            <div class="stat-card bg-warning text-dark p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">Pending Commission</small>
                        <h3 class="mt-1 mb-0">AED {{ number_format($wallet->pending_balance ?? 0, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bx bx-time fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3">
            <div class="stat-card bg-info text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">Total Withdrawn</small>
                        <h3 class="mt-1 mb-0">AED {{ number_format($wallet->total_withdrawn, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bx bx-transfer fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    </div>

    <!-- Action Button -->
    <div class="row mb-4">
        <div class="col-12 text-end">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                <i class="bx bx-plus-circle me-1"></i> Request Withdrawal
            </button>
        </div>
    </div>

    <!-- Pending Withdrawals Section -->
    @if($pendingWithdrawals->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">Pending Withdrawal Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Request Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingWithdrawals as $withdrawal)
                        <tr>
                            <td>{{ web_date_in_timezone($withdrawal->created_at, 'd M Y h:i A') }}</td>
                            <td class="fw-semibold">AED {{ number_format($withdrawal->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
                            <td><span class="status-badge pending">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger cancel-withdrawal" data-id="{{ $withdrawal->id }}">
                                    <i class="bx bx-x"></i> Cancel
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Two Column Layout for Transactions -->
    <div class="row">
        <!-- Commission Transactions Column -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="section-title mb-0">Recent Commission Payments</h5>
                </div>
                <div class="card-body">
                    @if($commissionTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissionTransactions as $transaction)
                                    <tr>
                                        <td>{{ web_date_in_timezone($transaction->payment_completed_at, 'd M Y') }}</td>
                                        <td>
                                            @if($transaction->member_id)
                                                {{ $transaction->member->full_name ?? 'N/A' }}
                                            @else
                                                {{ $transaction->user->first_name ?? '' }} {{ $transaction->user->last_name ?? '' }}
                                            @endif
                                        </td>
                                        <td class="text-success fw-semibold">+ AED {{ number_format($transaction->doctor_earning, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-receipt fs-4"></i>
                            <p class="mb-0 mt-2">No commission payments yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Withdrawal History Column -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="section-title mb-0">Recent Withdrawal History</h5>
                    <a href="{{ route('doctor.withdrawal.history') }}" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body">
                    @if($withdrawalHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawalHistory as $withdrawal)
                                    <tr>
                                        <td>{{ web_date_in_timezone($withdrawal->created_at, 'd M Y') }}</td>
                                        <td class="text-danger fw-semibold">- AED {{ number_format($withdrawal->amount, 2) }}</td>
                                        <td>
                                            <span class="status-badge {{ $withdrawal->status }}">
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-history fs-4"></i>
                            <p class="mb-0 mt-2">No withdrawal history</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Request Withdrawal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="withdrawalForm">
                @csrf
                <div class="modal-body pt-0">
                    <div class="alert alert-info mb-3">
                        <i class="bx bx-info-circle me-1"></i>
                        Available Balance: <strong>AED {{ number_format($wallet->current_balance, 2) }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (AED) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" min="50" max="{{ $wallet->current_balance }}" step="1" required placeholder="Enter amount">
                        <small class="text-muted">Minimum withdrawal: AED 50</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Details <span class="text-danger">*</span></label>
                        <textarea name="account_details" class="form-control" rows="3" placeholder="Bank Name, Account Number, IBAN, etc." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any additional information"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('doctor.template.footer')
<script>
$(document).ready(function() {
    $('#withdrawalForm').on('submit', function(e) {
        e.preventDefault();
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');
        
        $.ajax({
            url: '{{ route("doctor.withdrawal.request") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.status == 1) {
                    $('#withdrawalModal').modal('hide');
                    App.alert(res.message, 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    App.alert(res.message, 'Error', 'error');
                    btn.prop('disabled', false).html('Submit Request');
                }
            },
            error: function() {
                App.alert('Something went wrong', 'Error', 'error');
                btn.prop('disabled', false).html('Submit Request');
            }
        });
    });
    
    $('.cancel-withdrawal').on('click', function() {
        var withdrawalId = $(this).data('id');
        
        App.confirm('Are you sure?', 'This action cannot be undone.', function() {
            $.ajax({
                url: '{{ route("doctor.withdrawal.cancel") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', withdrawal_id: withdrawalId },
                success: function(res) {
                    if (res.status == 1) {
                        App.alert(res.message, 'Success');
                        location.reload();
                    } else {
                        App.alert(res.message, 'Error', 'error');
                    }
                }
            });
        });
    });
});
</script>

