@extends('front.template.layout')

@section('title', 'Checkout - MedNero')

@section('content')
<!-- Checkout Page Start-->
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form id="checkout-form" method="POST" action="{{ route('front.checkout.process') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-lg-4 gy-5">
                <div class="col-lg-7">
                    <div class="checkout-form-wrapper">
                        @if($addresses->isEmpty())
                        <div class="alert alert-info mb-4">
                            <h5>No Address Found</h5>
                            <p>Please add an address to continue with checkout.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                Add New Address
                            </button>
                        </div>
                        @else
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5>Select Delivery Address</h5>
                            <button type="button" class="primary-btn1 btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                <span>Add New Address</span>
                            </button>
                        </div>

                        <div class="time-slots address-area-slots mb-40">
                            <ul>
                                @foreach($addresses as $address)
                                <li class="{{ $address->is_default ? 'active' : '' }}">
                                    <label class="checkbox-container">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" 
                                            {{ $address->is_default ? 'checked' : '' }} required>
                                        <span class="checkmark"></span>
                                        <span class="label-text text-start d-block">
                                            <span class="small-label">{{ ucfirst($address->address_type) }}</span>
                                            <span class="small-label lh-base">
                                                <b>{{ $address->name }}, </b> <br>
                                                {{ $address->full_address }}
                                                <span>{{ $address->mobile_number }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($prescription_required)
                        <div class="checkout-form-title mt-4">
                            <h5>Prescription Upload (Required for some items)</h5>
                        </div>
                        <div class="checkout-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-inner two mb-25">
                                        <label>Upload Prescription*</label>
                                        <input type="file" name="prescription" accept=".pdf,.jpg,.jpeg,.png" 
                                            class="form-control" {{ $prescription_required ? 'required' : '' }}>
                                        <small class="text-muted">Max file size: 5MB (PDF, JPG, PNG)</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-inner two mb-25">
                                        <label>Short Notes (Optional)</label>
                                        <textarea name="notes" placeholder="Write any notes about your order..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-form-wrapper">
                        <div class="checkout-form-title">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="order-sum-area">
                            <div class="cart-menu">
                                <div class="cart-body">
                                    <ul>
                                        @foreach($cart_items as $item)
                                        <li class="single-item">
                                            <div class="item-area">
                                                <div class="main-item">
                                                    <div class="item-img">
                                                        <img src="{{ $item->medicine->image_url ?? asset('assets/img/product-img1.jpg') }}" 
                                                            alt="{{ $item->medicine->title_en }}">
                                                    </div>
                                                    <div class="content-and-quantity">
                                                        <div class="content">
                                                            <span>{{ $item->quantity }} x <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($item->price, 2) }}</span>
                                                            <h6>{{ $item->medicine->title_en }}</h6>
                                                            @if($item->medicine->prescription_required)
                                                            <span class="badge bg-warning text-dark">Rx Required</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="cart-footer">
                                    <div class="pricing-area mb-40">
                                        <ul>
                                            <li>
                                                <strong>Sub Total</strong>
                                                <strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($subtotal, 2) }}</strong>
                                            </li>
                                             @if($couponDiscount > 0 && $appliedCoupon)
                                                <li>
                                                    <strong>Coupon Discount ({{ $appliedCoupon->code }})</strong>
                                                    <strong class="text-success">- <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($couponDiscount, 2) }}</strong>
                                                </li>
                                            @endif
                                            <li>
                                                Shipping
                                                <div class="order-info">
                                                    <p>Shipping Fee*</p>
                                                    <span><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($shipping_fee, 2) }}</span>
                                                </div>
                                            </li>
                                            <li>
                                                <strong>Total</strong>
                                                <strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($total, 2) }}</strong>
                                            </li>
                                             @if($couponDiscount > 0)
                                            <!-- <li class="text-muted small">
                                                <span>Original Total:</span>
                                                <span class="text-decoration-line-through"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($subtotal + $shipping_fee, 2) }}</span>
                                            </li> -->
                                            @endif
                                        </ul>
                                    </div>

                                    <button type="submit" class="primary-btn1 w-100" {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                                        <span>
                                            Proceed to Payment
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                            </svg>
                                        </span>
                                        <span>
                                            Proceed to Payment
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plot/Office No*</label>
                            <input type="text" name="plot_office_no" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Building Name*</label>
                            <input type="text" name="building_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cities*</label>
                            <select name="emirates" class="form-select" required>
                                <option value="">Select City</option>
                                <option value="Abu Dhabi">Abu Dhabi</option>
                                <option value="Dubai">Dubai</option>
                                <option value="Sharjah">Sharjah</option>
                                <option value="Ajman">Ajman</option>
                                <option value="Umm Al Quwain">Umm Al Quwain</option>
                                <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                <option value="Fujairah">Fujairah</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Locality*</label>
                            <input type="text" name="locality" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name*</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number*</label>
                            <input type="text" name="mobile_number" class="form-control" required maxlength="15" oninput="this.value = this.value.replace(/\D/g, '')">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Location Name (Optional)</label>
                            <input type="text" name="location_name" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="address_type" value="home" checked>
                                    <label class="form-check-label">Home</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="address_type" value="office">
                                    <label class="form-check-label">Office</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="address_type" value="work">
                                    <label class="form-check-label">Work</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="defaultAddress">
                                <label class="form-check-label" for="defaultAddress">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('#addressForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("front.addresses.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status == '1') {
                    $('#addAddressModal').modal('hide');
                      toastr.success('Address added successfully');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                } else {
                     toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    toastr.error(errorMessages);
                } else {
                    toastr.error('Error saving address');
                }
            }
        });
    });
});
</script>
@endsection
@endsection