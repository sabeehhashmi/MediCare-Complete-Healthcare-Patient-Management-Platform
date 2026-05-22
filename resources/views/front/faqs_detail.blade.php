@extends('front.template.layout')

@section('title', 'Faqs')

@section('styles')

@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="notification-list">
                    <div class="checkout-form-title d-flex justify-content-between align-items-center">
                        <h4>Faq Detail</h4>
                       
                    </div>

                    <div id="empty-state" style="display: none;" class="text-center p-5">
                        <i class="bx bx-bell-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="mt-2 text-muted">No Faq Detail found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="module">
</script>
@endsection