{{-- resources/views/front/invoices/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoiceData->invoice_number }}</title>
    <style>
        @page{
            margin:0.7cm;
            size:A4;
        }
        
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }
        
        body{
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size:10px;
            line-height:1.5;
            color:#334155;
            background:#ffffff;
        }
        
        .invoice-container{
            width:100%;
        }
        
        /* Main table spacing */
        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0 16px;
        }
        
        /* ================= HEADER ================= */
        
        .invoice-header{
            background:#000;
            border-radius:16px;
            padding:28px;
            overflow: hidden;
        }
        
        .header-row{
            width:100%;
        }
        
        .company-logo{
            max-height:34px;
            filter:brightness(0) invert(1);
            margin-bottom:14px;
        }
        
        .invoice-title{
            color:#fff;
            font-size:34px;
            font-weight:800;
            letter-spacing:.5px;
        }
        
        .invoice-number{
            color:#A7C4DF;
            margin-top:8px;
            font-size:11px;
        }
        
        .status-badge{
            display:inline-block;
            background:#19c37d;
            color:#fff;
            border-radius:30px;
            padding:8px 18px;
            font-size:11px;
            font-weight:700;
        }
        
        .invoice-date{
            color:#fff;
            margin-top:14px;
            font-size:11px;
        }
        
        
        /* ================= CARDS ================= */
        
        .info-box,
        .appointment-box,
        .payment-box{
            background:#fff;
            border:1px solid #dbe3eb;
            border-radius:12px;
            padding:16px;
        }
        
        .info-box{
            margin-right:8px;
        }
        
        .appointment-box{
            margin-left:8px;
        }
        
        .box-title{
            color:#062849;
            font-size:13px;
            font-weight:800;
            margin-bottom:14px;
            text-transform:uppercase;
            padding-bottom:12px;
            border-bottom:1px solid #e8edf3;
        }
        
        
        /* ================= CONTENT ================= */
        
        .info-row{
            margin-bottom: 2px;
            display:flex;
        }
        
        .info-label{
            width:85px;
            color:#7A8797;
            font-size:10px;
        }
        
        .info-value{
            color:#334155;
            font-size:10px;
            font-weight:500;
        }
        
        .badge{
            background:#EDF6FF;
            color:#0a4b7a;
            border-radius:20px;
            padding:4px 10px;
            font-size:9px;
        }
        
        
        /* ================= PAYMENT ================= */
        
        .summary-row{
            display:flex;
            justify-content:space-between;
            padding:4px 6px;
            border-bottom:1px solid #edf2f7;
        }
        
        .summary-row:last-child{
            background:#EAF5FF;
            margin:0px;
            border-radius:0;
            border-bottom:none;
        }
        
        .total-label{
            font-size:16px;
            font-weight:800;
            color:#062849;
        }
        
        .total-amount{
            font-size:16px;
            font-weight:800;
            color:#062849;
        }
        
        
        /* ================= FOOTER ================= */
        
        .divider{
            display:none;
        }
        
        .footer{
            text-align:center;
            margin-top:25px;
            color:#9aa9ba;
            font-size:9px;
            border-top:none;
        }
        
        .mt-2{
            margin-top:6px;
        }
        
        .text-right{
            text-align:right;
        }
        
        .invoice-title{
            font-size:32px;
            font-weight:bold;
        }
        
        .box-title{
            font-size:13px;
            font-weight:bold;
        }
        
        .status-badge{
            font-size:11px;
            font-weight:bold;
        }
        
        .total-label,
        .total-amount{
            font-size:16px;
            font-weight:bold;
        }
        
        .info-label{
            font-weight:normal;
        }
        
        .info-value{
            font-weight:bold;
        }
        /* Equal height cards */
        .equal-card{
            height:195px; /* adjust as needed */
        }
        
        .info-box,
        .appointment-box{
            min-height:195px;
            vertical-align:top;
        }
        .info-box.equal-card-down ,
        .equal-card-down{
            height:150px; /* adjust as needed */
            min-height:150px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        <table style="width: 100%;padding: 20px 20px;">
            <tbody>
        
                {{-- Header --}}
                <tr class="invoice-header">
                    <td style="padding:16px;" width="50%" valign="bottom">
                        <div>
                            <img src="{{ public_path('assets/img/logo-mednero.png') }}" alt="Mednero" class="company-logo">
                            <div class="invoice-title">INVOICE</div>
                            <div class="invoice-number">{{ $invoiceData->invoice_number }}</div>
                        </div>
                    </td>
                    <td style="padding:16px;" width="50%" valign="bottom">
                        <div class="text-right">
                            <div class="status-badge">✓ PAID</div>
                            <div class="invoice-date">
                                Date: {{ \Carbon\Carbon::parse($invoiceData->invoice_date)->format('d M, Y') }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td width="50%" valign="top">
                        <!-- Bill To -->
                        <div class="info-box  equal-card">
                            <div class="box-title">📋 BILL TO</div>
                            <div class="info-row">
                                <div class="info-label">Patient:</div>
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
                    </td>
                    <td width="50%" valign="top">
                        <!-- Appointment Details -->
                        <div class="appointment-box  equal-card">
                            <div class="box-title">📅 APPOINTMENT</div>
                            <div class="info-row"   style="margin-bottom: 0px !important;">
                                <div class="info-label">Booking ID:</div>
                                <div class="info-value">{{ $invoiceData->invoice_number }}</div>
                            </div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="info-row"  style="margin-bottom: 0px !important;">
                                                <div class="info-label">Date:</div>
                                                <div class="info-value">{{ \Carbon\Carbon::parse($invoiceData->booking_date)->format('d M, Y') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="info-row"  style="margin-bottom: 0px !important;">
                                                <div class="info-label">Time:</div>
                                                <div class="info-value">{{ \Carbon\Carbon::parse($invoiceData->booking_time_slot)->format('h:i A') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="info-row"  style="margin-bottom: 0px !important;">
                                <div class="info-label">Type:</div>
                                <div class="info-value"><span class="badge">{{ $invoiceData->booking_type }}</span></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" width="100%" valign="top">
                        <!-- Service Provider -->
                        <div class="info-box">
                            <div class="box-title">🏥 SERVICE PROVIDER</div>
                            
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="info-row">
                                                <div class="info-label">Doctor:</div>
                                                <div class="info-value">Dr. {{ $invoiceData->doctor_name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="info-row">
                                                <div class="info-label">Specialty:</div>
                                                <div class="info-value">{{ $invoiceData->doctor_specialty }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="info-row">
                                                <div class="info-label">Hospital:</div>
                                                <div class="info-value">{{ $invoiceData->hospital_name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="info-row">
                                                <div class="info-label">Address:</div>
                                                <div class="info-value">{{ $invoiceData->hospital_address }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        {{-- Payment Information Row (Full Width) --}}
                        <div class="info-box equal-card-down">
                            <div class="box-title">💳 PAYMENT INFORMATION</div>
                            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                                <div class="info-row">
                                    <span class="info-label" style="color: #64748b;">Method:</span>
                                    <span class="info-value" style=" margin-left: 5px;">{{ ucfirst($invoiceData->payment_method) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label" style="color: #64748b;">Status:</span>
                                    <span class="info-value" style="color: #10b981;">PAID</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label" style="color: #64748b;">Transaction Date:</span>
                                    <span class="info-value" style="margin-left: 5px;">
                                        @if($invoiceData->payment_completed_at)
                                            {{ \Carbon\Carbon::parse($invoiceData->payment_completed_at)->format('d M, Y h:i A') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($invoiceData->invoice_date)->format('d M, Y') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <!-- Payment Summary -->
                        <div class="payment-box equal-card-down">
                            <div class="box-title">💰 PAYMENT SUMMARY</div>
                            <div class="summary-row">
                                <span>Consultation Fee</span>
                                <span>AED {{ number_format($invoiceData->subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>VAT (0%)</span>
                                <span>AED {{ number_format($invoiceData->tax, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="total-label">TOTAL</span>
                                <span class="total-amount">AED {{ number_format($invoiceData->total, 2) }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        </div>
        
        {{-- Divider --}}
        <div class="divider"></div>
        
        {{-- Footer --}}
        <div class="footer">
            <div>Thank you for choosing Mednero Healthcare</div>
            <div class="mt-2">For queries, contact support@mednero.com | © {{ date('Y') }} Mednero Healthcare</div>
        </div>
    </div>
</body>
</html>