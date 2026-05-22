@extends('admin.template.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@section('content')
<div class="card mb-5">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4>{{ $page_heading }}</h4>
            </div>
           
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="card-header bg-light">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list') }}">
                    All ({{ $status_counts['all'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '1' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 1]) }}">
                    <span class="badge bg-warning me-1">Pending ({{ $status_counts['pending'] }})</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '2' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 2]) }}">
                    <span class="badge bg-info me-1">Confirmed ({{ $status_counts['confirmed'] }})</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '3' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 3]) }}">
                    <span class="badge bg-primary me-1">Processing ({{ $status_counts['processing'] }})</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '4' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 4]) }}">
                    <span class="badge bg-secondary me-1">Dispatched ({{ $status_counts['dispatched'] }})</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '5' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 5]) }}">
                    <span class="badge bg-success me-1">Delivered ({{ $status_counts['delivered'] }})</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == '6' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.list', ['status' => 6]) }}">
                    <span class="badge bg-danger me-1">Cancelled ({{ $status_counts['cancelled'] }})</span> 
                </a>
            </li>
          
        </ul>
    </div>

    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="{{ route('admin.orders.list') }}" method="GET" class="row g-3">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Order # or Customer" value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <select name="payment_status" class="form-select">
                            <option value="">Payment Status</option>
                            <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Paid</option>
                            <option value="2" {{ request('payment_status') == '2' ? 'selected' : '' }}>Failed</option>
                            <option value="3" {{ request('payment_status') == '3' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="from_date" 
                               placeholder="From Date" value="{{ request('from_date') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="to_date" 
                               placeholder="To Date" value="{{ request('to_date') }}">
                    </div>
                    
                   <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bx bx-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary flex-grow-1">
                            <i class="bx bx-reset"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                         <th>Coupon</th>
                        <th>Order Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                        <td>
                            <strong>{{ $order->order_number }}</strong>
                            @if($order->stripe_session_id)
                                <br><small class="text-muted">Stripe</small>
                            @endif
                        </td>
                        <td>
                            {{ $order->user->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $order->items->count() }}</span>
                        </td>
                        <td>
                            <strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->total, 2) }}</strong>
                        </td>
                        <td> 
                            @if($order->coupon_discount > 0 && $order->coupon_data)
                                <span class="badge bg-success">
                                    {{ $order->coupon_data['code'] ?? 'Coupon' }}
                                </span>
                                <br>
                                <small class="text-success">-<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->coupon_discount, 2) }}</small>
                            @else
                                <span class="badge bg-secondary">No Coupon</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    1 => 'bg-warning',
                                    2 => 'bg-info',
                                    3 => 'bg-primary',
                                    4 => 'bg-secondary',
                                    5 => 'bg-success',
                                    6 => 'bg-danger',
                                    7 => 'bg-dark'
                                ];
                                
                                $statusTexts = [
                                    1 => 'Pending',
                                    2 => 'Confirmed',
                                    3 => 'Processing',
                                    4 => 'Dispatched',
                                    5 => 'Delivered',
                                    6 => 'Cancelled',
                                    7 => 'Refunded'
                                ];
                            @endphp
                            
                            @if(get_user_permission('orders', 'u') && $order->order_status != 5 && $order->order_status != 6)
                                <select class="form-select form-select-sm change-order-status" 
                                        data-id="{{ $order->id }}"
                                        data-url="{{ route('admin.orders.change_status') }}">
                                    @foreach($statusTexts as $key => $text)
                                        @if($key <= 5 || $key == 6)
                                        <option value="{{ $key }}" 
                                                {{ $order->order_status == $key ? 'selected' : '' }}>
                                            {{ $text }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <span class="badge {{ $statusClasses[$order->order_status] ?? 'bg-secondary' }}">
                                    {{ $statusTexts[$order->order_status] ?? 'Unknown' }}
                                </span>
                                @if($order->order_status == 5 && $order->delivered_at)
                                    <br><small>{{ $order->delivered_at->format('d M Y') }}</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            @php
                                $paymentClasses = [
                                    0 => 'bg-warning',
                                    1 => 'bg-success',
                                    2 => 'bg-danger',
                                    3 => 'bg-dark'
                                ];
                                
                                $paymentTexts = [
                                    0 => 'Pending',
                                    1 => 'Paid',
                                    2 => 'Failed',
                                    3 => 'Refunded'
                                ];
                            @endphp
                            <span class="badge {{ $paymentClasses[$order->payment_status] ?? 'bg-secondary' }}">
                                {{ $paymentTexts[$order->payment_status] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            {{ $order->created_at->format('d M Y') }}<br>
                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if(get_user_permission('orders', 'r'))
                                    <a class="dropdown-item" href="{{ route('admin.orders.view', ['id' => encrypt($order->id)]) }}">
                                        <i class="bx bx-show"></i> View Details
                                    </a>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('admin.orders.print', ['id' => encrypt($order->id)]) }}" target="_blank">
                                        <i class="bx bx-printer"></i> Print Invoice
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <img src="{{ asset('admin-assets/img/empty.svg') }}" alt="No orders" style="max-width: 150px;">
                            <h5 class="mt-3">No Orders Found</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="row mt-3">
            <div class="col-md-12">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Change order status
    $('.change-order-status').on('change', function() {
        let select = $(this);
        let orderId = select.data('id');
        let url = select.data('url');
        let newStatus = select.val();
        let currentStatus = select.find('option:selected').text();

        Swal.fire({
            title: 'Change Order Status',
            html: `Are you sure you want to change status to <strong>${currentStatus}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: orderId,
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.status == '1') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            select.val({{ $order->order_status ?? '1' }});
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Network error occurred',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        select.val({{ $order->order_status ?? '1' }});
                    }
                });
            } else {
                select.val({{ $order->order_status ?? '1' }});
            }
        });
    });
});
</script>
@stop