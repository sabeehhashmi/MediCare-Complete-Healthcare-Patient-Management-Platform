@extends('front.template.layout')

@section('title', 'Order Success - MedNero')

@section('content')
<div class="checkout-page pt-100 mb-100">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 600px;">

            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="220" height="220" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <g>
                    <path fill="#70CCFF" d="M512 256c0 141.387-114.613 256-256 256S0 397.387 0 256 114.613 0 256 0s256 114.613 256 256zm0 0" opacity="1" data-original="#70CCFF"></path>
                    <path fill="#1baeff" d="M237.54 428.992c21.124-20.02 30.491-47.676 30.491-47.676l-34.047-28.906s-41.836-80.37-41.578-80.168C99 163.622 169.516 16.352 170.312 14.692 71.079 49.941 0 144.675 0 256c0 131.762 99.566 240.277 227.555 254.438-16.676-25.258-12.875-59.778 9.984-81.446zm0 0" opacity="1" data-original="#1baeff"></path>
                    <path fill="#ffffff" d="M240.352 393.605a37.147 37.147 0 0 1-22.754-7.765l-120.68-93.09c-16.305-12.574-19.328-35.988-6.75-52.293 12.574-16.3 35.988-19.324 52.293-6.75l93.367 72.027 128.82-142.57c13.805-15.277 37.38-16.473 52.66-2.664 15.278 13.8 16.473 37.379 2.665 52.656L268.03 381.316c-7.328 8.11-17.469 12.29-27.68 12.29zm0 0" opacity="1" data-original="#ffffff"></path>
                </g>
            </svg>

            <div class="checkout-form-title mt-30">
                <h3>Thank You!</h3>
            </div>
            <p>Your Order Has Been Received Successfully.</p>

            <p>MedNero team will contact you soon for confirmation.</p>

            <h5>Order No : <span class="text-primary">{{ $order->order_number }}</span> </h5>
            
            <div class="order-details mt-4 p-3 bg-light rounded">
                <p><strong>Total Amount:</strong> <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($order->total, 2) }}</p>
                <p><strong>Order Status:</strong> 
                    <span class="badge bg-success">{{ $order->status_text }}</span>
                </p>
                <p><strong>Payment Status:</strong> 
                    <span class="badge bg-info">{{ $order->payment_status_text }}</span>
                </p>
            </div>
        
            <a href="{{ route('front.index') }}" class="primary-btn1 btn-outline mt-30">
                <span>
                    Back To Home
                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                    </svg>
                </span>
                  <span>
                    Back To Home
                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</div>
@endsection