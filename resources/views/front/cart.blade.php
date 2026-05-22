@extends('front.template.layout')
@section('title', 'My Cart')

@section('content')
<div class="cart-page pt-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            <div class="col-xl-8 col-lg-7">
                <div class="cart-shopping-wrapper">
                    <div class="cart-widget-title">
                        <h4>My Shopping</h4>
                    </div>
                    
                    @if($cart_items->isEmpty())
                    <div class="text-center py-5">
                        <h4>Your cart is empty</h4>
                        <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                        <a href="{{ route('front.pharmacy-list') }}" class="primary-btn1 mt-3">
                            <span>Continue Shopping</span>
                            <span>Continue Shopping</span>
                        </a>
                    </div>
                    @else
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product Info</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart_items as $item)
                            <tr id="cart-item-{{ $item->id }}">
                                <td data-label="Product Info">
                                    <div class="product-info-wrapper">
                                        <div class="product-info-img">
                                            <img src="{{ $item->medicine->image_url }}" alt="{{ $item->medicine->title_en }}">
                                        </div>
                                        <div class="product-info-content">
                                            <h6>{{ $item->medicine->title_en }}</h6>
                                            <p><span>SKU: </span>{{ $item->medicine->sku ?? 'N/A' }}</p>
                                            @if($item->medicine->prescription_required)
                                            <span class="badge bg-warning text-dark">Prescription Required</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Price"><span><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($item->price, 2) }}</span></td>
                                 <td data-label="Quantity">
                                    <div class="quantity-area">
                                        <div class="quantity d-flex align-items-center gap-2">

                                            <button type="button" 
                                                class="btn btn-light btn-sm quantity__btn"
                                                onclick="updateQuantity({{ $item->id }}, 'decrease')">
                                                <i class="bi bi-dash"></i>
                                            </button>

                                            <input type="text"
                                                class="form-control text-center quantity__input"
                                                id="quantity-{{ $item->id }}"
                                                value="{{ str_pad($item->quantity, 2, '0', STR_PAD_LEFT) }}"
                                                readonly
                                                style="width:60px;">

                                            <button type="button"
                                                class="btn btn-light btn-sm quantity__btn"
                                                onclick="updateQuantity({{ $item->id }}, 'increase')">
                                                <i class="bi bi-plus"></i>
                                            </button>

                                        </div>
                                    </div>
                                </td>
                                <td data-label="Total" class="item-total" id="item-total-{{ $item->id }}">
                                    <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($item->total, 2) }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-link text-danger" onclick="removeFromCart({{ $item->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('front.pharmacy-list') }}" class="details-button">
                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 9L9 1M9 1C7.22222 1.33333 3.33333 2 1 1M9 1C8.66667 2.66667 8 6.33333 9 9" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Continue Shopping
                        </a>
                        
                        <button type="button" class="btn btn-link text-danger" onclick="clearCart()">
                            Clear Cart
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            
            @if(!$cart_items->isEmpty())
            <div class="col-xl-4 col-lg-5">
                <div class="cart-order-sum-area">
                    <div class="cart-widget-title">
                        <h4>Order Summary</h4>
                    </div>
                    <div class="order-summary-wrap">
                        <ul class="order-summary-list">
                           <li>
                                <div class="coupon-area">
                                    <span>Coupon Code</span>
                                    
                                   @php
                                        $appliedCoupon = null;
                                        $couponDiscount = 0;

                                        if (auth()->check()) {
                                            $cartItem = $cart_items->first();
                                            if ($cartItem && $cartItem->applied_coupon_id) {
                                                $appliedCoupon = $cartItem->coupon_data;
                                                $couponDiscount = $cartItem->coupon_discount;
                                            }
                                        } else {
                                            $appliedCoupon = Session::get('applied_coupon');
                                            $couponDiscount = $appliedCoupon['discount'] ?? 0;
                                        }
                                    @endphp

                                    @if($appliedCoupon)
                                        <div class="applied-coupon p-2 mb-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-success">{{ $appliedCoupon['code'] }}</strong>
                                                <div class="small text-muted">
                                                    {{ $appliedCoupon['title'] ?? 'Coupon applied' }}
                                                    @if(isset($appliedCoupon['type']))
                                                        @if($appliedCoupon['type'] == 'percentage')
                                                            ({{ $appliedCoupon['value'] ?? 0 }}% off)
                                                        @else
                                                            (<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($appliedCoupon['value'] ?? 0, 2) }} off)
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- <span class="fw-bold text-success">- <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($couponDiscount, 2) }}</span> -->
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">
                                                    <i class="bi bi-x"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        
                                        <form id="coupon-form" onsubmit="applyCoupon(event)">
                                            <div class="form-inner">
                                                <input type="text" placeholder="Enter coupon code" id="coupon-code"  required>
                                                <button type="submit" class="apply-btn" id="apply-coupon-btn">Apply</button>
                                            </div>
                                            <div id="coupon-message" class="small text-danger"></div>
                                        </form>
                                    @endif
                                </div>
                            </li>

                            <li>
                                <strong>Sub Total</strong>
                                <strong id="subtotal"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($subtotal, 2) }}</strong>
                            </li>

                            @if($couponDiscount > 0)
                            <li>
                                <strong>Coupon Discount</strong>
                                <strong class="text-success" id="coupon-discount">- <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($couponDiscount, 2) }}</strong>
                            </li>
                            @endif

                            <li>
                                <strong>Shipping</strong>
                                <div class="order-info">
                                    <p>Shipping Fee*</p>
                                    <span id="shipping-fee"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($shipping_fee, 2) }}</span>
                                </div>
                            </li>

                            <li>
                                <strong>Total</strong>
                                <strong id="total"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($total) }}</strong>
                            </li>
                        </ul>
                        <a href="{{ auth()->check() ? route('front.checkout') : route('front.auth') }}" class="primary-btn1 mt-40">
                            <span>
                                Processed Checkout
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                </svg>
                            </span>
                            <span>
                                Processed Checkout
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function updateQuantity(cartId, action) {
    let input = document.getElementById('quantity-' + cartId);
    let currentQty = parseInt(input.value);
    let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
    
    if (newQty < 1) {
        toastr.warning('', 'Remove item from cart?', {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: true,
            tapToDismiss: false,
            positionClass: 'toast-top-center',
            onShown: function() {
                $(this).find('.toast-close-button').after(
                    '<div class="mt-2 text-center">' +
                    '<button class="btn btn-sm btn-danger me-2" onclick="confirmRemove(' + cartId + ')">Yes, Remove</button>' +
                    '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                    '</div>'
                );
            }
        });
        return;
    }
    
    $.ajax({
        url: '{{ route("front.cart.update") }}',
        type: 'POST',
        data: {
            cart_id: cartId,
            quantity: newQty,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                if (response.coupon_discount > 0) {
                    validateAppliedCoupon();
                }
                input.value = newQty.toString().padStart(2, '0');
                $('#item-total-' + cartId).text('$' + response.item_total);
                $('#subtotal').html('<img class="aed-symbol" src="{{ asset("assets/img/Dirham_Symbol.svg") }}">' + response.subtotal);

                if (response.coupon_discount > 0) {
                    $('#coupon-discount').html('- <img class="aed-symbol" src="{{ asset("assets/img/Dirham_Symbol.svg") }}">' + response.coupon_discount);
                }

                $('#total').html('<img class="aed-symbol" src="{{ asset("assets/img/Dirham_Symbol.svg") }}">' + response.total);
                updateCartCount();
                toastr.success('Cart updated successfully');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                alert(response.message);
            }
        }
    });
}

function removeFromCart(cartId) {
    toastr.warning('', 'Remove item from cart?', {
        timeOut: 0,
        extendedTimeOut: 0,
        closeButton: true,
        tapToDismiss: false,
        positionClass: 'toast-top-center',
        onShown: function() {
            $(this).find('.toast-close-button').after(
                '<div class="mt-2 text-center">' +
                '<button class="btn btn-sm btn-danger me-2" onclick="confirmRemove(' + cartId + ')">Yes, Remove</button>' +
                '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                '</div>'
            );
        }
    });
}

function confirmRemove(cartId) {
    toastr.clear();
    
    $.ajax({
        url: '{{ route("front.cart.remove", "") }}/' + cartId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                $('#cart-item-' + cartId).fadeOut(300, function() {
                    $(this).remove();
                    if ($('.cart-table tbody tr').length === 0) {
                        location.reload();
                    }
                });
                updateCartCount();
                toastr.success('Item removed from cart');
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to remove item');
        }
    });
}

function clearCart() {
    toastr.warning('', 'Clear your entire cart?', {
        timeOut: 0,
        extendedTimeOut: 0,
        closeButton: true,
        tapToDismiss: false,
        positionClass: 'toast-top-center',
        onShown: function() {
            $(this).find('.toast-close-button').after(
                '<div class="mt-2 text-center">' +
                '<button class="btn btn-sm btn-danger me-2" onclick="confirmClearCart()">Yes, Clear All</button>' +
                '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                '</div>'
            );
        }
    });
}

function confirmClearCart() {
    toastr.clear();
    
    $.ajax({
        url: '{{ route("front.cart.clear") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                toastr.success('Cart cleared successfully');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to clear cart');
        }
    });
}

function updateCartCount() {
    $.get('{{ route("front.cart.count") }}', function(data) {
        $('.cart-count').text(data.count);
    });
}


</script>



<script>
function applyCoupon(event) {
    event.preventDefault();
    
    let code = $('#coupon-code').val().trim();
    if (!code) {
        showCouponMessage('Please enter a coupon code', 'danger');
        return;
    }

    $('#apply-coupon-btn').prop('disabled', true).text('Applying...');

    $.ajax({
        url: '{{ route("front.cart.apply-coupon") }}',
        type: 'POST',
        data: {
            code: code,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                showCouponMessage(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showCouponMessage(response.message, 'danger');
                $('#apply-coupon-btn').prop('disabled', false).text('Apply');
            }
        },
        error: function() {
            showCouponMessage('Network error. Please try again.', 'danger');
            $('#apply-coupon-btn').prop('disabled', false).text('Apply');
        }
    });
}

function removeCoupon() {
    toastr.warning('', 'Remove applied coupon?', {
        timeOut: 0,
        extendedTimeOut: 0,
        closeButton: true,
        tapToDismiss: false,
        positionClass: 'toast-top-center',
        onShown: function() {
            $(this).find('.toast-close-button').after(
                '<div class="mt-2 text-center">' +
                '<button class="btn btn-sm btn-danger me-2" onclick="confirmRemoveCoupon()">Yes, Remove</button>' +
                '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                '</div>'
            );
        }
    });
}

function confirmRemoveCoupon() {
    toastr.clear();
    
    $.ajax({
        url: '{{ route("front.cart.remove-coupon") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                toastr.success('Coupon removed');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to remove coupon');
        }
    });
}

function showCouponMessage(message, type) {
    let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    $('#coupon-message').html(
        '<div class="alert ' + alertClass + ' py-1 px-2 mb-0">' + message + '</div>'
    );
    
    if (type === 'success') {
        setTimeout(function() {
            $('#coupon-message').empty();
        }, 3000);
    }
}



function validateAppliedCoupon() {
    $.ajax({
        url: '{{ route("front.cart.validate-coupon") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '0') {
                // Coupon no longer valid, remove it
                removeCoupon();
                showCouponMessage(response.message, 'warning');
            }
        }
    });
}
</script>
@endsection