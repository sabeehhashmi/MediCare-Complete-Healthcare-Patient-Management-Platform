@extends('front.template.layout')

@section('title', 'Order Details')

@section('content')
<div class="checkout-page user-account-page order-detail-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            
            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title d-flex justify-content-between align-items-center">
                        <h4>Order Details</h4>
                        <a href="{{ route('front.orders') }}" class="btn btn-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Order Details Section Start-->
                    <div class="home1-faq-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="faq-wrap">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
                                            <h5 class="accordion-header border-bottom" id="flush-heading-{{ $order->id }}">
                                                <div class="accordion-button" type="button" data-bs-toggle="collapse"  data-bs-target="#flush-collapse-{{ $order->id }}" aria-expanded="true" aria-controls="flush-collapse-{{ $order->id }}">
                                                    {{ $order->order_number }}
                                                    
                                                    @php
                                                        $statusClasses = [
                                                            1 => 'bg-warning', // Pending
                                                            2 => 'bg-info',    // Confirmed
                                                            3 => 'bg-primary', // Processing
                                                            4 => 'bg-success', // Dispatched
                                                            5 => 'bg-success', // Delivered
                                                            6 => 'bg-danger',  // Cancelled
                                                            7 => 'bg-secondary' // Refunded
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
                                                        
                                                        $paymentStatusTexts = [
                                                            0 => 'Pending',
                                                            1 => 'Paid',
                                                            2 => 'Failed',
                                                            3 => 'Refunded'
                                                        ];
                                                        
                                                        $statusClass = $statusClasses[$order->order_status] ?? 'bg-secondary';
                                                        $statusText = $statusTexts[$order->order_status] ?? 'Unknown';
                                                        $paymentStatusText = $paymentStatusTexts[$order->payment_status] ?? 'Unknown';
                                                    @endphp
                                                    
                                                    <span class="badge {{ $statusClass }} ms-2">{{ $statusText }}</span>
                                                    @if($order->payment_status == 1)
                                                        <span class="badge bg-success ms-2">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning ms-2">{{ $paymentStatusText }}</span>
                                                    @endif
                                                </div>
                                            </h5>
                                            <div id="flush-collapse-{{ $order->id }}" class="accordion-collapse collapse show"
                                                aria-labelledby="flush-heading-{{ $order->id }}" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body pt-3">
                                                    <!-- Order Information -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <div class="d-flex mb-2">
                                                                <span class="text-muted me-1">Order Date:</span>
                                                                <strong>{{ $order->created_at->format('d M Y - h:i A') }}</strong>
                                                            </div>
                                                            <div class="d-flex mb-2">
                                                                <span class="text-muted me-1">Payment Method:</span>
                                                                <strong>{{ ucfirst($order->payment_method) }}</strong>
                                                            </div>
                                                            @if($order->payment_intent_id)
                                                            <div class="d-flex mb-2">
                                                                <span class="text-muted me-1 nowrap">Transaction ID:</span>
                                                                <strong class="text-truncate" style="max-width: 140px;">{{ $order->payment_intent_id }}</strong>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex mb-2 justify-content-end">
                                                                <span class="text-muted me-1">Delivery Status:</span>
                                                                <strong>{{ $statusText }}</strong>
                                                            </div>
                                                            @if($order->order_status == 5 && $order->delivered_at)
                                                            <div class="d-flex mb-2">
                                                                <span class="text-muted me-1">Delivered on:</span>
                                                                <strong>{{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y') }}</strong>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Delivery Address -->
                                                    @if($order->address)
                                                    <div class="mb-4">
                                                        <h6 class="mb-2">Delivery Address</h6>
                                                        <div class="p-3 bg-light rounded">
                                                            <strong>{{ $order->address->name }}</strong><br>
                                                            {{ $order->address->plot_office_no }}, {{ $order->address->building_name }}<br>
                                                            {{ $order->address->locality }}, {{ $order->address->emirates }}<br>
                                                            <span class="text-muted">Phone: {{ $order->address->mobile_number }}</span>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <!-- Order Items -->
                                                    <div class="cart-page">
                                                        <div class="cart-shopping-wrapper mt-30">
                                                            <table class="cart-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Info</th>
                                                                        <th>Price</th>
                                                                        <th>Quantity</th>
                                                                        <th class="text-end" >Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($order->items as $item)
                                                                    <tr>
                                                                        <td data-label="Product Info">
                                                                            <div class="product-info-wrapper">
                                                                                <div class="product-info-img">
                                                                                    @if($item->medicine && $item->medicine->image)
                                                                                        <img src="{{ $item->medicine->image_url }}" alt="{{ $item->medicine_name }}">
                                                                                    @else
                                                                                        <img src="{{ asset('assets/img/product-img2.jpg') }}" alt="{{ $item->medicine_name }}">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="product-info-content">
                                                                                    <h6>{{ $item->medicine_name }}</h6>
                                                                                    <p><span>SKU: </span>{{ $item->sku }}</p>
                                                                                    @if($item->prescription_required)
                                                                                        <span class="badge bg-warning">Prescription Required</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td data-label="Price"><span>AED {{ number_format($item->price, 2) }}</span></td>
                                                                        <td class="text-center" data-label="Quantity">{{ $item->quantity }}</td>
                                                                        <td class="text-end"  data-label="Total">AED {{ number_format($item->total, 2) }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Order Summary -->
                                                        <div class="cart-order-sum-area ps-0">
                                                            <div class="order-summary-wrap">
                                                                <ul class="order-summary-list">
                                                                    <li>
                                                                        <strong>Sub Total</strong>
                                                                        <strong>AED {{ number_format($order->subtotal, 2) }}</strong>
                                                                    </li>
                                                                     @if($order->coupon_discount > 0 && $order->coupon_data)
                                                                    <li class="text-success">
                                                                        <strong>
                                                                            <i class="fas fa-tag"></i> Coupon ({{ $order->coupon_data['code'] ?? 'Discount' }})
                                                                        </strong>
                                                                        <strong>- AED {{ number_format($order->coupon_discount, 2) }}</strong>
                                                                    </li>
                                                                    @endif
                                                                    <li>
                                                                        Shipping Fee
                                                                        <div class="order-info">
                                                                            <span>AED {{ number_format($order->shipping_fee, 2) }}</span>
                                                                        </div>
                                                                    </li>
                                                                     @if($order->coupon_discount > 0)
                                                                    <!-- <li class="text-muted small">
                                                                        <span>Original Total:</span>
                                                                        <span class="text-decoration-line-through">AED {{ number_format($order->subtotal + $order->shipping_fee, 2) }}</span>
                                                                    </li> -->
                                                                    @endif
                                                                    <li class="total">
                                                                        <strong>Total</strong>
                                                                        <strong>AED {{ number_format($order->total, 2) }}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <!-- Prescription -->
                                                        @if($order->prescription_path)
                                                        @php
                                                        $disk = config('global.upload_bucket') ?? 'public';
                                                        $dir = config('global.prescription_upload_dir') ?? 'prescriptions/';
                                                        @endphp

                                                        <div class="d-flex justify-content-between pt-30 border-top">
                                                            <span>Prescription:</span>
                                                            <span>
                                                                <a href="{{ Storage::disk($disk)->url($dir.$order->prescription_path) }}" target="_blank" class="text-primary">
                                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                                                    </svg>
                                                                    View Prescription
                                                                </a>
                                                            </span>
                                                        </div>
                                                        @endif

                                                        <!-- Order Notes -->
                                                        @if($order->notes)
                                                        <div class="pt-3">
                                                            <h6 class="text-primary">Order Notes:</h6>
                                                            <p class="text-muted">{{ $order->notes }}</p>
                                                        </div>
                                                        @endif

                                                        @if($order->cancellation_reason)
                                                        <div class="pt-3">
                                                            <h6 class="text-danger">Cancellation Reason:</h6>
                                                            <p class="text-muted">{{ $order->cancellation_reason }}</p>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="d-flex justify-content-between border-top mt-4 pt-4">
                                                        @if(in_array($order->order_status, [1, 2])) {{-- Pending or Confirmed --}}
                                                        <button type="button" 
                                                                onclick="showCancelModal({{ $order->id }}, '{{ $order->order_number }}')"
                                                                class="primary-btn1 btn-outline-secondery">
                                                            <span>
                                                                Cancel Order
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                            <span>
                                                                Cancel Order
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        @endif

                                                        <button type="button" 
                                                                onclick="reorder({{ $order->id }})"
                                                                class="primary-btn1 btn-outline">
                                                            <span>
                                                                Order Again
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                            <span>
                                                                Order Again
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                        </button>

                                                        <a href="{{ route('front.order.invoice', $order->id) }}" class="primary-btn1 btn-outline-dark" target="_blank">
                                                            <span>
                                                                Download Invoice
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                            <span>
                                                                Download Invoice
                                                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Order Details Section End-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Cancel Order Modal (same as in orders view) -->
<div class="modal enquiry-modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753C2.56515 0.606635 2.27482 0.493906 2.00247 0.500545Z" />
                </svg>
            </button>
            <div class="modal-body">
                <h4 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h4>
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <p>Are you sure you want to cancel order <strong id="cancelOrderNumber"></strong>?</p>
                        <p class="text-muted small">This action cannot be undone.</p>
                    </div>

                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for cancellation <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="cancellation_reason" 
                                  name="cancellation_reason" 
                                  rows="3" 
                                  required
                                  placeholder="Please tell us why you're cancelling this order"></textarea>
                    </div>

                    <div class="form-inner d-flex justify-content-between">
                        <button type="button" class="primary-btn1 black-bg" data-bs-dismiss="modal">
                            <span>
                                Close
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                </svg>
                            </span>
                            <span>
                                Close
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                </svg>
                            </span>
                        </button>
                        <button type="submit" class="primary-btn1 btn-outline-secondery">
                            <span>
                                Yes, Cancel Order
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                </svg>
                            </span>
                            <span>
                                Yes, Cancel Order
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showCancelModal(orderId, orderNumber) {
        $('#cancelOrderNumber').text(orderNumber);
        $('#cancelOrderForm').attr('action', '{{ url("useraccount/orders") }}/' + orderId + '/cancel');
        $('#cancelOrderModal').modal('show');
    }

    function reorder(orderId) {
         toastr.warning('', 'Reorder this order?', {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: true,
            tapToDismiss: false,
            positionClass: 'toast-top-center',
            onShown: function() {
                $(this).find('.toast-close-button').after(
                    '<div class="mt-2 text-center">' +
                    '<button class="btn btn-sm btn-success me-2" onclick="confirmReorder(' + orderId + ')">Yes, Reorder</button>' +
                    '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                    '</div>'
                );
            }
        });

        // $.ajax({
        //     url: '{{ url("useraccount/orders") }}/' + orderId + '/reorder',
        //     type: 'POST',
        //     data: {
        //         _token: '{{ csrf_token() }}'
        //     },
        //     success: function(response) {
        //         if (response.status === '1') {
        //             toastr.success(response.message);
        //             if (response.redirect) {
        //                 window.location.href = response.redirect;
        //             }
        //         } else {
        //             toastr.error(response.message);
        //         }
        //     },
        //     error: function(xhr) {
        //         toastr.error(xhr.responseJSON?.message || 'Something went wrong');
        //     }
        // });
    }
    function confirmReorder(orderId) {
        toastr.clear();
        
        $.ajax({
            url: '{{ url("useraccount/orders") }}/' + orderId + '/reorder',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === '1') {
                    toastr.success(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    }

    $('#cancelOrderForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === '1') {
                    $('#cancelOrderModal').modal('hide');
                    toastr.success(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    });
</script>
@endsection