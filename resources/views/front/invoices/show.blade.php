{{-- resources/views/front/invoices/show.blade.php --}}
@extends('front.template.layout')

@section('title', 'Invoice Details - ' . $invoiceData->invoice_number)

@section('styles')
<style>
    .invoice-wrapper {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .invoice-header {
        background: linear-gradient(135deg, #1baeff 0%, #1baeff 100%);
        padding: 30px 40px;
        color: white;
    }
    .invoice-body {
        padding: 40px;
    }
    .company-logo {
        max-height: 50px;
        filter: brightness(0) invert(1);
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-paid {
        background: #d4edda;
        color: #155724;
    }
    .info-section {
        margin-bottom: 25px;
    }
    .info-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1baeff;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }
    .info-row {
        margin-bottom: 10px;
        display: flex;
        flex-wrap: wrap;
    }
    .info-label {
        font-weight: 600;
        color: #4a5568;
        width: 140px;
        flex-shrink: 0;
    }
    .info-value {
        color: #2d3748;
        flex: 1;
    }
    .total-amount {
        font-size: 24px;
        font-weight: 700;
        color: #1baeff;
    }
    .btn-download {
        background: #1baeff;
        color: white;
        border-radius: 8px;
        padding: 10px 24px;
        transition: all 0.3s ease;
    }
    .btn-download:hover {
        background: #1baeff;
        color: white;
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
                <div class="invoice-wrapper">
                    {{-- Header --}}
                    <div class="invoice-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <img src="{{ asset('assets/img/logo-mednero.png') }}" alt="Mednero" class="company-logo mb-3">
                                <h2 class="text-white mb-2" style="font-size: 28px;">INVOICE</h2>
                                <p class="text-white-50 mb-0">#{{ $invoiceData->invoice_number }}</p>
                            </div>
                            <div class="text-end mt-3 mt-sm-0">
                                <div class="status-badge status-paid mb-2">
                                    <i class="bx bx-check-circle"></i> PAID
                                </div>
                                <p class="text-white-50 mb-0">
                                    <i class="bx bx-calendar"></i> Date: {{ \Carbon\Carbon::parse($invoiceData->invoice_date)->format('d M, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Body --}}
                    <div class="invoice-body">
                        {{-- Patient & Doctor Details --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="info-section">
                                    <div class="info-section-title">
                                        <i class="bx bx-user"></i> Bill To:
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Patient Name:</div>
                                        <div class="info-value">{{ $invoiceData->patient_member_name ?? $invoiceData->patient_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">{{ $invoiceData->patient_email }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Phone:</div>
                                        <div class="info-value">{{ $invoiceData->patient_phone }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-section">
                                    <div class="info-section-title">
                                        <i class="bx bx-building"></i> Service Provider:
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Doctor:</div>
                                        <div class="info-value">Dr. {{ $invoiceData->doctor_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Specialty:</div>
                                        <div class="info-value">{{ $invoiceData->doctor_specialty }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Hospital:</div>
                                        <div class="info-value">{{ $invoiceData->hospital_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Address:</div>
                                        <div class="info-value">{{ $invoiceData->hospital_address }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Appointment Details --}}
                        <div class="info-section">
                            <div class="info-section-title">
                                <i class="bx bx-calendar-check"></i> Appointment Details:
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Booking ID:</div>
                                        <div class="info-value">{{ $invoiceData->invoice_number }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Date:</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($invoiceData->booking_date)->format('d M, Y') }}</div>
                                    </div>
                                     <div class="info-row">
                                        <div class="info-label">Status:</div>
                                        <div class="info-value">{{ ucfirst($invoiceData->booking_status) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Time Slot:</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($invoiceData->booking_time_slot)->format('h:i A') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Booking Type:</div>
                                        <div class="info-value">{{ $invoiceData->booking_type }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                            {{-- Payment Information --}}
                                    <div class="info-section">
                                        <div class="info-section-title">
                                            <i class="bx bx-credit-card-front"></i> Payment Information:
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Payment Method:</div>
                                            <div class="info-value">{{ ucfirst($invoiceData->payment_method) }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Payment Status:</div>
                                            <div class="info-value">
                                                <span class="status-badge status-paid" style="padding: 3px 10px;">{{ strtoupper($invoiceData->payment_status) }}</span>
                                            </div>
                                        </div>
                                        @if($invoiceData->payment_completed_at)
                                        <div class="info-row">
                                            <div class="info-label">Payment Date:</div>
                                            <div class="info-value">{{ \Carbon\Carbon::parse($invoiceData->payment_completed_at)->format('d M, Y h:i A') }}</div>
                                        </div>
                                        @endif
                                    </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                {{-- Payment Summary --}}
                                <div class="info-section">
                                    <div class="info-section-title">
                                        <i class="bx bx-credit-card"></i> Payment Summary:
                                    </div>
                                    <div style="max-width: 400px; margin-left: auto;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 10px 0;">Consultation Fee:</td>
                                                <td style="padding: 10px 0; text-align: right;">AED {{ number_format($invoiceData->subtotal, 2) }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 10px 0;">Tax (VAT):</td>
                                                <td style="padding: 10px 0; text-align: right;">AED {{ number_format($invoiceData->tax, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 0 10px 0; font-weight: 700; font-size: 18px;">Total Amount:</td>
                                                <td style="padding: 15px 0 10px 0; text-align: right; font-weight: 700; font-size: 18px; color: #1baeff;">AED {{ number_format($invoiceData->total, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                </div>
                        </div>
                        
                        
                        {{-- Footer Actions --}}
                        <div class="mt-4 pt-3 text-end">
                            <a href="{{ route('front.invoices.download', $invoiceData->id) }}" class="btn btn-download">
                                <i class="bx bxs-download"></i> Download PDF
                            </a>
                            <a href="{{ route('front.invoices.index') }}" class="btn btn-outline-secondary ms-2" style="border-radius: 8px;">
                                <i class="bx bx-arrow-back"></i> Back
                            </a>
                        </div>
                        
                        {{-- Footer Note --}}
                        <div class="mt-5 pt-3 text-center text-muted small">
                            <hr>
                            <p>Thank you for choosing Mednero. For any queries, please contact our support team.</p>
                            <p>© {{ date('Y') }} Mednero Healthcare. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection