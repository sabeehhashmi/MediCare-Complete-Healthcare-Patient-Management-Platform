```html
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
</head>

<body style="margin:0; color:#333; background:#1baeff;">

<div style="background-color:#1baeff; padding:20px 0; width:100%;">

    <table border="0"
           cellpadding="0"
           cellspacing="0"
           width="100%"
           style="background-color:#1baeff;">

        <tbody>
        <tr>
            <td align="center" valign="top">

                <table border="0"
                       cellpadding="0"
                       cellspacing="0"
                       width="600"
                       style="background:#1baeff; border-radius:10px; overflow:hidden;">

                    <tbody>

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#e6f3ff;">

                            <div style="padding:15px 20px; background:#e6f3ff;">

                                <table width="100%"
                                       style="font-family:Arial,sans-serif; font-size:14px;">

                                    <tbody>
                                    <tr>
                                        <td>

                                            <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}"
                                                 alt="{{ env('APP_NAME') }}"
                                                 style="max-width:190px;">

                                        </td>
                                    </tr>
                                    </tbody>

                                </table>

                            </div>

                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td align="center"
                            valign="top"
                            style="background:#fff;">

                            <table border="0"
                                   cellpadding="0"
                                   cellspacing="0"
                                   width="600"
                                   style="background:#fff;">

                                <tbody>
                                <tr>

                                    <td valign="top"
                                        style="background-color:#fff; padding:0;">

                                        <table border="0"
                                               cellpadding="20"
                                               cellspacing="0"
                                               width="100%"
                                               style="font-family:Arial,sans-serif;">

                                            <tbody>
                                            <tr>

                                                <td valign="top"
                                                    style="padding-bottom:0px;">

                                                    <div style="color:#333;
                                                                font-size:14px;
                                                                line-height:150%;
                                                                text-align:left;">

                                                        <!-- TITLE -->

                                                        <h3 style="font-size:22px;
                                                                   color:#dc3545;
                                                                   margin-top:0;">

                                                            @if($order->is_urgent)
                                                                URGENT: Payment Reminder
                                                            @else
                                                                Appointment Payment Reminder
                                                            @endif

                                                        </h3>

                                                        <!-- GREETING -->

                                                        <p style="font-size:15px;
                                                                  line-height:26px;">

                                                            Dear
                                                            <strong>
                                                                {{ $patient_name }}
                                                            </strong>,
                                                        </p>

                                                        <!-- MESSAGE -->

                                                        <p style="font-size:15px;
                                                                  line-height:26px;">

                                                            This is a reminder that payment for your appointment
                                                            is still pending.

                                                        </p>

                                                        <p style="font-size:15px;
                                                                  line-height:26px;">

                                                            We previously shared the payment link when your appointment
                                                            was created. To confirm your booking and avoid cancellation,
                                                            please complete your payment before the appointment time.

                                                        </p>

                                                        <!-- APPOINTMENT DETAILS -->

                                                        <div style="background:#f8f9fa;
                                                                    padding:20px;
                                                                    border-radius:8px;
                                                                    margin:25px 0;
                                                                    border-left:4px solid #1baeff;">

                                                            <h4 style="margin-top:0;
                                                                       margin-bottom:15px;
                                                                       color:#1baeff;">

                                                                Appointment Details

                                                            </h4>

                                                            <table width="100%"
                                                                   style="font-size:14px;
                                                                          line-height:24px;">

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;
                                                                               width:40%;">
                                                                        Booking ID:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;">
                                                                        {{ $order->booking_id }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Doctor:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;">

                                                                        Dr.
                                                                        {{ $order->doctor->user->name ?? 'N/A' }}

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Hospital / Clinic:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;">

                                                                        {{ $order->hospital->name_en ?? 'N/A' }}

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Date:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;">

                                                                        {{ date('d-m-Y', strtotime($order->booking_date)) }}

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Time:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;">

                                                                        {{ $order->booking_time_slot }}

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Consultation Fee:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;
                                                                               color:#1baeff;">

                                                                        {{ $order->formatted_consultation_fee ?? 'N/A' }}

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Payment Status:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;
                                                                               color:#dc3545;">

                                                                        PENDING

                                                                    </td>
                                                                </tr>

                                                                @if($order->is_urgent)

                                                                <tr>
                                                                    <td style="padding:6px 0;
                                                                               color:#666;">
                                                                        Priority:
                                                                    </td>

                                                                    <td style="padding:6px 0;
                                                                               font-weight:bold;
                                                                               color:#dc3545;">

                                                                        URGENT

                                                                    </td>
                                                                </tr>

                                                                @endif

                                                            </table>

                                                        </div>

                                                        <!-- PAYMENT BUTTON -->

                                                        <div style="margin:30px 0;
                                                                    text-align:center;">

                                                            <a href="{{ $payment_url }}"
                                                               style="display:inline-block;
                                                                      background:#1baeff;
                                                                      color:#fff;
                                                                      padding:14px 35px;
                                                                      text-decoration:none;
                                                                      border-radius:5px;
                                                                      font-weight:bold;
                                                                      font-size:15px;">

                                                                Complete Payment

                                                            </a>

                                                        </div>

                                                        <!-- FALLBACK LINK -->

                                                        <p style="font-size:14px;
                                                                  line-height:24px;">

                                                            If the button above does not work,
                                                            copy and paste this link into your browser:

                                                        </p>

                                                        <p style="word-break:break-all;">

                                                            <a href="{{ $payment_url }}"
                                                               style="color:#1baeff;">

                                                                {{ $payment_url }}

                                                            </a>

                                                        </p>

                                                        <!-- WARNING BOX -->

                                                        <div style="background:#fff3cd;
                                                                    border-left:4px solid #ffc107;
                                                                    padding:15px;
                                                                    border-radius:6px;
                                                                    margin-top:25px;">

                                                            <strong style="color:#856404;">
                                                                Important:
                                                            </strong>

                                                            <p style="margin:10px 0 0 0;
                                                                      color:#856404;
                                                                      line-height:24px;">

                                                                Your appointment may remain unconfirmed
                                                                or automatically cancelled if payment
                                                                is not completed before the appointment time.

                                                            </p>

                                                        </div>

                                                        <!-- SUPPORT -->

                                                        @php
                                                            $settings = \App\Models\SettingsModel::first();
                                                        @endphp

                                                        <p style="margin-top:30px;
                                                                  font-size:14px;
                                                                  line-height:24px;">

                                                            If you need assistance,
                                                            please contact our support team at

                                                            <a href="mailto:{{ $settings->support_email ?? 'support@mednero.com' }}"
                                                               style="color:#1baeff;
                                                                      text-decoration:none;">

                                                                {{ $settings->support_email ?? 'support@mednero.com' }}

                                                            </a>

                                                            or call

                                                            <strong style="color:#1baeff;">

                                                                {{ $settings->support_phone ?? '+971 XXX XXX XXX' }}

                                                            </strong>

                                                        </p>

                                                        <!-- FOOTER TEXT -->

                                                        <p style="margin-top:30px;
                                                                  font-size:15px;
                                                                  line-height:26px;">

                                                            Thank you for choosing
                                                            {{ env('APP_NAME') }}.

                                                        </p>

                                                        <p style="margin-top:25px;
                                                                  font-size:15px;
                                                                  line-height:26px;">

                                                            Regards,<br>

                                                            <strong style="color:#1baeff;">

                                                                {{ env('APP_NAME') }} Team

                                                            </strong>

                                                        </p>

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

                    <!-- FOOTER -->

                    <tr>
                        <td style="background:#e6f3ff;">

                            <div style="padding:20px;
                                        background:#e6f3ff;">

                                <table width="100%"
                                       style="font-family:Arial,sans-serif;
                                              font-size:14px;">

                                    <tbody>
                                    <tr>

                                        <td align="center">

                                            <p style="color:#666;
                                                      padding-top:20px;
                                                      font-size:12px;
                                                      margin-top:0;">

                                                © {{ date('Y') }}
                                                {{ env('APP_NAME') }}.
                                                All Rights Reserved.

                                            </p>

                                            <p style="color:#999;
                                                      font-size:11px;
                                                      margin-top:5px;">

                                                This is an automated message,
                                                please do not reply to this email.

                                            </p>

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

</body>
</html>
```
