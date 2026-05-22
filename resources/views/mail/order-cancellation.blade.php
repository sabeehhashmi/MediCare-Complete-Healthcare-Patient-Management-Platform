<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Cancellation - {{env('APP_NAME')}}</title>
</head>

<body style="margin: 0; color: #333; background: #1baeff;">

    <div marginwidth="0" marginheight="0">
        <div marginwidth="0" marginheight="0" id="" dir="ltr" style="background-color: #1baeff; margin:0;padding:20px 0 20px 0;width:100%;">

            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #1baeff;">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#1baeff;border-radius:10px!important;overflow: hidden;">
                                <tbody>
                                    <!-- Header -->
                                    <tr>
                                        <td style="background: #e6f3ff;">
                                            <div style="padding: 15px 20px; background:#e6f3ff;">
                                                <table style="background:#e6f3ff; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}" alt="{{env('APP_NAME')}}" style="max-width: 190px;">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Body -->
                                    <tr>
                                        <td align="center" valign="top" style="background: #fff;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="600" style="background: #fff;">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top" style="background-color: #fff; padding:0;">
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%" style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" style="padding-bottom: 0px;">
                                                                            <div style="color:#333;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left;">
                                                                                
                                                                                <!-- Cancellation Icon -->
                                                                                <div style="text-align: center; margin-bottom: 20px;">
                                                                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <circle cx="12" cy="12" r="12" fill="#dc3545" opacity="0.2"/>
                                                                                        <circle cx="12" cy="12" r="10" fill="#dc3545" opacity="0.4"/>
                                                                                        <path d="M8 8L16 16M16 8L8 16" stroke="#dc3545" stroke-width="2" stroke-linecap="round"/>
                                                                                    </svg>
                                                                                </div>
                                                                                
                                                                                <h4 style="font-weight: 600; font-size: 20px; color: #dc3545; margin-top: 0; text-align: center;">Order Cancelled</h4>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333; text-align: center;">
                                                                                    Dear <strong>{{$user->name}}</strong>,
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333; text-align: center;">
                                                                                    Your order has been successfully cancelled as per your request.
                                                                                </p>
                                                                                
                                                                                <!-- Order Details Box -->
                                                                                <div style="margin: 30px 0; background: #e6f3ff; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;">
                                                                                    <h4 style="margin-top: 0; margin-bottom: 15px; color: #dc3545; font-size: 16px;">Cancelled Order Details:</h4>
                                                                                    
                                                                                    <table style="width: 100%; font-size: 14px; line-height: 24px;">
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666; width: 40%;">Order Number:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 600;">{{$order->order_number}}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Order Date:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Cancelled On:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">{{ \Carbon\Carbon::parse($order->cancelled_at ?? now())->format('d M Y, h:i A') }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Payment Method:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">{{ ucfirst($order->payment_method) }}</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                
                                                                                <!-- Cancellation Reason -->
                                                                                @if($order->cancellation_reason)
                                                                                <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                                                                    <h4 style="color: #dc3545; font-size: 16px; margin: 0 0 10px;">Cancellation Reason:</h4>
                                                                                    <p style="margin: 0; color: #333; font-style: italic;">"{{$order->cancellation_reason}}"</p>
                                                                                </div>
                                                                                @endif
                                                                                
                                                                                <!-- Order Items -->
                                                                                <h4 style="color: #333; font-size: 16px; margin: 20px 0 10px;">Cancelled Items:</h4>
                                                                                
                                                                                <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                                                                                    <thead>
                                                                                        <tr style="background: #f8f9fa;">
                                                                                            <th style="padding: 10px; text-align: left; color: #666;">Item</th>
                                                                                            <th style="padding: 10px; text-align: center; color: #666;">Qty</th>
                                                                                            <th style="padding: 10px; text-align: right; color: #666;">Price</th>
                                                                                            <th style="padding: 10px; text-align: right; color: #666;">Total</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($order->items as $item)
                                                                                        <tr style="border-bottom: 1px solid #e6f3ff;">
                                                                                            <td style="padding: 10px; text-align: left;">{{$item->medicine_name}}</td>
                                                                                            <td style="padding: 10px; text-align: center;">{{$item->quantity}}</td>
                                                                                            <td style="padding: 10px; text-align: right;">AED {{number_format($item->price, 2)}}</td>
                                                                                            <td style="padding: 10px; text-align: right;">AED {{number_format($item->total, 2)}}</td>
                                                                                        </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                                
                                                                                <!-- Refund Information -->
                                                                                @if($order->payment_status == 1)
                                                                                <div style="margin: 30px 0; padding: 15px; background: #e8f4fd; border-radius: 8px;">
                                                                                    <h4 style="color: #1baeff; font-size: 16px; margin: 0 0 10px;">Refund Information:</h4>
                                                                                    <p style="margin: 0; color: #333;">
                                                                                        Your payment of <strong>AED {{number_format($order->total, 2)}}</strong> will be refunded to your original payment method within 5-7 business days.
                                                                                    </p>
                                                                                </div>
                                                                                @endif
                                                                                
                                                                                <!-- Order Summary -->
                                                                                <div style="margin-top: 20px; border-top: 2px solid #e6f3ff; padding-top: 15px;">
                                                                                    <table style="width: 100%; font-size: 14px;">
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Subtotal:</td>
                                                                                            <td style="padding: 5px 0; text-align: right;">AED {{number_format($order->subtotal, 2)}}</td>
                                                                                        </tr>
                                                                                        
                                                                                        @if($order->coupon_discount > 0)
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #28a745;">
                                                                                                Coupon Discount ({{$order->coupon_data['code'] ?? 'Discount'}}):
                                                                                            </td>
                                                                                            <td style="padding: 5px 0; text-align: right; color: #28a745;">
                                                                                                -AED {{number_format($order->coupon_discount, 2)}}
                                                                                            </td>
                                                                                        </tr>
                                                                                        @endif
                                                                                        
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Shipping Fee:</td>
                                                                                            <td style="padding: 5px 0; text-align: right;">AED {{number_format($order->shipping_fee, 2)}}</td>
                                                                                        </tr>
                                                                                        
                                                                                        <tr style="font-weight: bold;">
                                                                                            <td style="padding: 10px 0 5px; color: #333; font-size: 16px;">Total Refund Amount:</td>
                                                                                            <td style="padding: 10px 0 5px; text-align: right; color: #dc3545; font-size: 18px;">AED {{number_format($order->total, 2)}}</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                
                                                                                <p style="margin:30px 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: center;">
                                                                                    If you have any questions about your cancellation or refund, please contact our support team.
                                                                                </p>
                                                                                
                                                                                <p style="margin:30px 0 16px; font-size: 16px; line-height: 26px; color: #333; text-align: center;">
                                                                                    We hope to serve you again soon!
                                                                                </p>
                                                                                
                                                                                <p style="margin:30px 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: center;">
                                                                                    Best regards,<br>
                                                                                    <strong style="color: #1baeff;">{{env('APP_NAME')}} Team</strong>
                                                                                </p>
                                                                                
                                                                                <!-- Support Contact -->
                                                                                @php
                                                                                    $settings = \App\Models\SettingsModel::first();
                                                                                @endphp

                                                                                <!-- Support Contact -->
                                                                                <div style="margin: 30px 0; text-align: center;">
                                                                                    <p style="margin: 0; color: #666; font-size: 13px;">
                                                                                        Need help? Contact us at 
                                                                                        <a href="mailto:{{ $settings->support_email ?? env('SUPPORT_EMAIL', 'support@mednero.com') }}" 
                                                                                        style="color: #1baeff; text-decoration: none;">
                                                                                        {{ $settings->support_email ?? env('SUPPORT_EMAIL', 'support@mednero.com') }}
                                                                                        </a>
                                                                                        <br>or call <strong style="color: #1baeff;">{{ $settings->support_phone ?? env('SUPPORT_PHONE', '+971 XXX XXX XXX') }}</strong>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <!-- Footer -->
                                    <tr>
                                        <td style="background: #e6f3ff;">
                                            <div style="padding: 20px; background: #e6f3ff;">
                                                <table style="background: #e6f3ff; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 100%;" colspan="2">
                                                                <table style="font-size: 14px; width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="2" valign="middle" style="padding:0;border:0;color:#666;font-family:Arial;font-size:12px;line-height:125%;text-align:center; background: #e6f3ff;">
                                                                                <p style="color: #666; padding-top: 20px; font-size: 12px; margin-top: 0;">
                                                                                    © {{date('Y')}} {{env('APP_NAME')}}. All Rights Reserved.
                                                                                </p>
                                                                                <p style="color: #999; font-size: 11px; margin-top: 5px;">
                                                                                    This is an automated message, please do not reply to this email.
                                                                                </p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>