<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background-color: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo h1 {
            margin: 0;
            color: #2563eb;
            font-size: 28px;
        }
        .logo p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .invoice-title p {
            margin: 5px 0 0;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #10b981;
            color: white;
        }
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        .status-cancelled {
            background-color: #ef4444;
            color: white;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #2563eb;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            color: #666;
        }
        .info-box strong {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            color: #666;
        }
        table td strong {
            color: #333;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
            margin-bottom: 0;
        }
        .totals table td {
            padding: 8px 12px;
            border: none;
        }
        .totals table tr:last-child td {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            border-top: 2px solid #dee2e6;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
            color: #999;
            font-size: 11px;
        }
        .footer p {
            margin: 5px 0;
        }
        .note {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            color: #666;
        }
        .note h4 {
            margin: 0 0 10px;
            color: #333;
            font-size: 13px;
        }
        .note p {
            margin: 0;
            font-style: italic;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { box-shadow: none; border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header -->
        <div class="header">
             <div class="logo">
                <img src="{{ asset('assets/img/logo-mednero.png') }}" alt="MedNero" style="height: 60px;">
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>Invoice No:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Status -->
        <div style="margin-bottom: 20px;">
            @php
                $paymentStatusClass = $order->payment_status == 1 ? 'status-paid' : ($order->payment_status == 6 ? 'status-cancelled' : 'status-pending');
                $paymentStatusText = $order->payment_status == 1 ? 'PAID' : ($order->payment_status == 6 ? 'CANCELLED' : 'PENDING');
            @endphp
            <span class="status-badge {{ $paymentStatusClass }}">{{ $paymentStatusText }}</span>
            
            @if($order->order_status == 5)
                <span class="status-badge status-paid" style="margin-left: 10px;">DELIVERED</span>
            @endif
        </div>

        <!-- Information Sections -->
        <div class="info-section">
            <div class="info-box">
                <h3>Bill To:</h3>
                <p><strong>{{ $order->address->name ?? $order->user->name }}</strong></p>
                @if($order->address)
                    <p>{{ $order->address->plot_office_no }}, {{ $order->address->building_name }}</p>
                    <p>{{ $order->address->locality }}, {{ $order->address->emirates }}</p>
                    <p>Phone: {{ $order->address->mobile_number }}</p>
                @else
                    <p>Email: {{ $order->user->email }}</p>
                    <p>Phone: {{ $order->user->mobile }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>Order Information:</h3>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                @if($order->payment_intent_id)
                    <p><strong>Transaction ID:</strong> {{ substr($order->payment_intent_id, -12) }}</p>
                @endif
                <p><strong>Order Status:</strong> {{ $order->status_text }}</p>
            </div>
        </div>

        <!-- Order Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Product</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->medicine_name }}</strong>
                        @if($item->sku)
                            <br><small style="color: #999;">SKU: {{ $item->sku }}</small>
                        @endif
                        @if($item->prescription_required)
                            <br><small style="color: #f59e0b;">Prescription Required</small>
                        @endif
                    </td>
                    <td class="text-right"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right"><strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($item->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->coupon_discount > 0 && $order->coupon_data)
                <tr style="color: #10b981;">
                    <td>
                        <strong>Coupon Discount</strong>
                        @if($order->coupon_data['code'] ?? false)
                            <br><small style="color: #999;">{{ $order->coupon_data['code'] }}</small>
                        @endif
                    </td>
                    <td class="text-right"><strong>-<img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->coupon_discount, 2) }}</strong></td>
                </tr>
                @endif
                <tr>
                    <td>Shipping Fee:</td>
                    <td class="text-right"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
                  @if($order->coupon_discount > 0)
                <!-- <tr style="color: #999;">
                    <td><small>Original Total:</small></td>
                    <td class="text-right"><small><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->subtotal + $order->shipping_fee, 2) }}</small></td>
                </tr> -->
                @endif
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($order->total, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Amount in Words -->
       

        <!-- Prescription Note -->
        @if($order->prescription_path)
        <div class="note">
            <h4>Prescription Note:</h4>
            <p>A prescription was uploaded with this order. Please ensure you have the original prescription ready for verification upon delivery.</p>
        </div>
        @endif

        <!-- Order Notes -->
        @if($order->notes)
        <div class="note">
            <h4>Order Notes:</h4>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Pharmacy</strong> - Your Trusted Health Partner</p>
            <p>123 Health Street, Medical District, Dubai, United Arab Emirates</p>
            <p>Phone: +971 4 123 4567 | Email: info@pharmacy.com | Web: www.pharmacy.com</p>
            <p style="margin-top: 10px;">This is a computer generated invoice. No signature is required.</p>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>
</body>
</html>