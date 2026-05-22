<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Appointment Cancelled</title>
</head>

<body style="margin:0;background:#1baeff;font-family:Arial,sans-serif;">

<div style="padding:20px;background:#1baeff;">

    <table width="600" align="center" cellpadding="0" cellspacing="0"
           style="background:#fff;border-radius:10px;overflow:hidden;">

        <!-- HEADER -->
        <tr>
            <td style="background:#e6f3ff;padding:20px;">

                <img
                    src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}"
                    style="max-width:180px;"
                >

            </td>
        </tr>

        <!-- BODY -->
        <tr>
            <td style="padding:30px;">

                <h2 style="color:#dc3545;margin-top:0;">
                    Appointment Cancelled
                </h2>

                <p style="font-size:15px;line-height:24px;">
                    Dear <strong>{{ $patient_name }}</strong>,
                </p>

                <p style="font-size:15px;line-height:24px;">
                    We regret to inform you that your appointment
                    <strong>({{ $order->booking_id }})</strong>
                    has been automatically cancelled because payment
                    was not completed within the required time.
                </p>

                <!-- DETAILS -->
                <div style="
                    background:#f8f9fa;
                    padding:20px;
                    border-radius:8px;
                    margin:25px 0;
                ">

                    <h4 style="margin-top:0;color:#1baeff;">
                        Appointment Details
                    </h4>

                    <table width="100%">

                        <tr>
                            <td style="padding:8px 0;color:#666;">
                                Booking ID:
                            </td>

                            <td style="padding:8px 0;font-weight:bold;">
                                {{ $order->booking_id }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0;color:#666;">
                                Doctor:
                            </td>

                            <td style="padding:8px 0;font-weight:bold;">
                                Dr. {{ $order->doctor->user->name ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0;color:#666;">
                                Hospital:
                            </td>

                            <td style="padding:8px 0;font-weight:bold;">
                                {{ $order->hospital->name_en ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0;color:#666;">
                                Appointment Date:
                            </td>

                            <td style="padding:8px 0;font-weight:bold;">
                                {{ date('d-m-Y', strtotime($order->booking_date)) }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0;color:#666;">
                                Appointment Time:
                            </td>

                            <td style="padding:8px 0;font-weight:bold;">
                                {{ $order->booking_time_slot }}
                            </td>
                        </tr>

                    </table>

                </div>

                <p style="font-size:15px;line-height:24px;">
                    If you would like to book a new appointment,
                    please visit the website or mobile app and schedule again.
                </p>

                <p style="font-size:15px;line-height:24px;">
                    For any assistance, feel free to contact our support team.
                </p>

                <p style="margin-top:30px;">
                    Best regards,<br>
                    <strong style="color:#1baeff;">
                        {{ env('APP_NAME') }} Team
                    </strong>
                </p>

            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="
                background:#e6f3ff;
                text-align:center;
                padding:20px;
                font-size:12px;
                color:#666;
            ">

                © {{ date('Y') }} {{ env('APP_NAME') }}.
                All Rights Reserved.

                <br><br>

                This is an automated email.

            </td>
        </tr>

    </table>

</div>

</body>
</html>