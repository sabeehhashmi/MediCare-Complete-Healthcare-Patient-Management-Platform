{{-- resources/views/front/invoices/index.blade.php --}}
@extends('front.template.layout')

@section('title', 'My Invoices')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/flatpickr.min.css')}}">
<style>
    .invoice-card {
        background: #fff;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
    }
    .invoice-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .filter-section {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    .status-paid {
        background: #d4edda;
        color: #155724;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .invoice-number {
        font-family: monospace;
        font-size: 14px;
        font-weight: 600;
        color: #1baeff;
    }
</style>
@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            
            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="checkout-form-wrapper">
                    <div class="checkout-form-title">
                        <h4>My Invoices</h4>
                    </div>

                    {{-- Filter Section --}}
                    <div class="filter-section">
                        <form method="GET" action="{{ route('front.invoices.index') }}" id="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">From Date</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               name="from_date" 
                                               class="form-control flatpicker-input" 
                                               placeholder="From Date" 
                                               value="{{ request('from_date') }}">
                                        <i class="bx bx-calendar position-absolute top-50 translate-middle" style="right: 12px;"></i>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">To Date</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               name="to_date" 
                                               class="form-control flatpicker-input" 
                                               placeholder="To Date" 
                                               value="{{ request('to_date') }}">
                                        <i class="bx bx-calendar position-absolute top-50 translate-middle" style="right: 12px;"></i>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Invoice Number</label>
                                    <input type="text" 
                                           name="invoice_number" 
                                           class="form-control" 
                                           placeholder="Search by Invoice ID" 
                                           value="{{ request('invoice_number') }}">
                                </div>
                                
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn" style="background: #1baeff; color: white; border-radius: 8px;">
                                            <i class="bx bx-search"></i> Search
                                        </button>
                                        <a href="{{ route('front.invoices.index') }}" class="btn btn-secondary" style="border-radius: 8px;">
                                            <i class="bx bx-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Invoices List --}}
                    @if($invoices->count())
                        <div class="row">
                            @foreach($invoices as $invoice)
                                <div class="col-12 mb-4">
                                    <div class="invoice-card p-4">
                                        <div class="row align-items-center">
                                            <div class="col-lg-3 mb-3 mb-lg-0">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        <div style="background: linear-gradient(135deg, #1baeff 0%, #1baeff 100%); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bx bx-receipt" style="font-size: 24px; color: white;"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 invoice-number">{{ $invoice->booking_id }}</h6>
                                                        <span class="status-paid">{{ ucfirst($invoice->payment_status) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-3 mb-3 mb-lg-0">
                                                <div>
                                                    <small class="text-muted d-block">Date</small>
                                                    <strong>{{ \Carbon\Carbon::parse($invoice->booking_date)->format('d M, Y') }}</strong>
                                                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($invoice->booking_time_slot)->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2 mb-3 mb-lg-0">
                                                <div>
                                                    <small class="text-muted d-block">Doctor</small>
                                                    <strong>Dr. {{ $invoice->doctor_name }}</strong>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2 mb-3 mb-lg-0">
                                                <div>
                                                    <small class="text-muted d-block">Amount</small>
                                                    <strong style="color: #1baeff; font-size: 18px;">AED {{ number_format($invoice->consultation_fee, 2) }}</strong>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('front.invoices.show', $invoice->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" style="border-radius: 8px;">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('front.invoices.download', $invoice->id) }}" 
                                                       class="btn" style="background: #1baeff; color: white; border-radius: 8px;">
                                                        <i class="bx bxs-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                {{ $invoices->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-receipt" style="font-size: 64px; color: #cbd5e0;"></i>
                            <h5 class="mt-3 text-muted">No invoices found</h5>
                            <p class="text-muted">You don't have any paid appointments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('admin-assets/assets/js/flatpickr.min.js')}}"></script>
<script>
    flatpickr(".flatpicker-input", {
        dateFormat: "d-m-Y",
        allowInput: true
    });
</script>
@endsection