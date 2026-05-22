@extends('admin.template.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@section('content')
<div class="card mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ $page_heading }} - {{ $order->order_number }}</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Orders
            </a>

            <a href="{{ route('admin.orders.print', ['id' => encrypt($order->id)]) }}" target="_blank" class="btn btn-primary">
                <i class="bx bx-printer"></i> Print Invoice
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Order Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">Order Status:</h5>
                    
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
                    
                    <span class="badge {{ $statusClasses[$order->order_status] }} fs-6 p-2">
                        {{ $statusTexts[$order->order_status] }}
                    </span>
                    
                    @if(get_user_permission('orders', 'u') && $order->order_status != 5 && $order->order_status != 6)
                        <select class="form-select form-select-sm w-auto change-order-status" 
                                data-id="{{ $order->id }}"
                                data-url="{{ route('admin.orders.change_status') }}">
                            @foreach($statusTexts as $key => $text)
                                @if($key <= 5 || $key == 6)
                                <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                                    {{ $text }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 40%;">Order Number:</th>
                                <td><strong>{{ $order->order_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Order Date:</th>
                                <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <td>
                                    <span class="badge {{ $paymentClasses[$order->payment_status] }}">
                                        {{ $paymentTexts[$order->payment_status] }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->coupon_discount > 0 && $order->coupon_data)
                            <tr>
                                <th>Coupon Applied:</th>
                                <td>
                                    <span class="badge bg-success">{{ $order->coupon_data['code'] ?? 'N/A' }}</span>
                                    <br>
                                    <small class="text-success">Discount: -<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->coupon_discount, 2) }}</small>
                                </td>
                            </tr>
                            @endif
                            @if($order->payment_intent_id)
                            <tr>
                                <th>Transaction ID:</th>
                                <td><small>{{ $order->payment_intent_id }}</small></td>
                            </tr>
                            @endif
                            @if($order->delivered_at)
                            <tr>
                                <th>Delivered On:</th>
                                <td>{{ $order->delivered_at->format('d M Y h:i A') }}</td>
                            </tr>
                            @endif
                            @if($order->cancelled_at)
                            <tr>
                                <th>Cancelled On:</th>
                                <td>{{ $order->cancelled_at->format('d M Y h:i A') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 40%;">Name:</th>
                                <td><strong>{{ $order->user->name ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    {{ $order->user->dial_code ?? '' }}{{ $order->user->phone ?? 'N/A' }}
                                </td>
                            </tr>
                            @if($order->address)
                            <tr>
                                <th>Delivery Address:</th>
                                <td>
                                    {{ $order->address->plot_office_no }}, {{ $order->address->building_name }}<br>
                                    {{ $order->address->locality }}, {{ $order->address->emirates }}<br>
                                    <small>Phone: {{ $order->address->mobile_number }}</small>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Prescription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->medicine_name }}</strong>
                                            @if($item->medicine && $item->medicine->image)
                                                @php
                                                    $image_disk = config('global.upload_bucket') ?? 'public';
                                                    $image_dir = config('global.medicine_image_upload_dir') ?? 'medicines/';
                                                @endphp
                                                <br>
                                                <img src="{{ Storage::disk($image_disk)->url($image_dir . $item->medicine->image) }}" 
                                                     alt="{{ $item->medicine_name }}" 
                                                     style="max-width: 50px; max-height: 50px;" class="mt-2">
                                            @endif
                                        </td>
                                        <td>{{ $item->sku ?? 'N/A' }}</td>
                                        <td><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($item->total, 2) }}</strong></td>
                                        <td>
                                            @if($item->prescription_required)
                                                <span class="badge bg-warning">Required</span>
                                            @else
                                                <span class="badge bg-secondary">Not Required</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Subtotal:</th>
                                        <th><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->subtotal, 2) }}</th>
                                        <td></td>
                                    </tr>
                                    @if($order->coupon_discount > 0)
                                    <tr class="text-success">
                                        <th colspan="5" class="text-end">Coupon Discount:</th>
                                        <th>-<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->coupon_discount, 2) }}</th>
                                        <td></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="5" class="text-end">Shipping Fee:</th>
                                        <th><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->shipping_fee, 2) }}</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Total:</th>
                                        <th class="text-primary fs-5"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->total, 2) }}</th>
                                        <td></td>
                                    </tr>
                                    @if($order->coupon_discount > 0)
                                    <!-- <tr class="text-muted">
                                        <th colspan="5" class="text-end">Original Total:</th>
                                        <th class="text-decoration-line-through"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->subtotal + $order->shipping_fee, 2) }}</th>
                                        <td></td>
                                    </tr> -->
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row mt-4">
            <!-- Prescription -->
            @if($order->prescription_path)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Prescription</h5>
                        </div>
                        <div class="card-body text-center">
                            @php
                                $disk = config('global.upload_bucket') ?? 'public';
                                $dir = config('global.prescription_upload_dir') ?? 'prescriptions/';
                                $prescription_url = Storage::disk($disk)->url($dir . $order->prescription_path);
                            @endphp

                            <p class="mb-4">Your prescription is ready. You can view or download it below:</p>

                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ $prescription_url }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="bx bx-show"></i> View
                                </a>
                                <a href="{{ $prescription_url }}" download class="btn btn-primary">
                                    <i class="bx bx-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            <!-- Order Notes -->
            @if($order->notes)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Cancellation Reason -->
            @if($order->cancellation_reason)
            <div class="col-md-12 mt-3">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Cancellation Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->cancellation_reason }}</p>
                        <small class="text-muted">Cancelled on: {{ $order->cancelled_at->format('d M Y h:i A') }}</small>
                    </div>
                </div>
            </div>
            @endif
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
                            select.val({{ $order->order_status }});
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Network error occurred',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        select.val({{ $order->order_status }});
                    }
                });
            } else {
                select.val({{ $order->order_status }});
            }
        });
    });
});
</script>
@stop