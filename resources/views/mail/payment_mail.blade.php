

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
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
                            <tr>
                                <td style="background: #e6f3ff;">
                                    <div style="padding: 15px 20px; background:#e6f3ff; padding-bottom: 15px;">
                                        <table style="background:#e6f3ff; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}" alt="" style="max-width: 190px; margin-bottom: 0px;">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    
                                    </div>
                                </td>
                            </tr>
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
                                                                    <div style="color:#333;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left;margin-top: 0px">
                                                                        
                                                                        <h4 style="font-weight: 600; font-size: 18px; color: #1baeff;margin-top: 0;">
                                                                            @if($appointment->is_urgent)
                                                                                 URGENT: Appointment Payment Required
                                                                            @else
                                                                                Appointment Payment Required
                                                                            @endif
                                                                        </h4>
                                                                        
                                                                        <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: left;">
                                                                            Hi {{$user->name}},
                                                                        </p>
                                                                        
                                                                        <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: left;">
                                                                            Thank you for choosing Mednero. An appointment has been created for you. 
                                                                            Please complete the payment to confirm your appointment.
                                                                        </p>
                                                                        
                                                                        <!-- Appointment Details Box -->
                                                                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                                                            <table style="width: 100%; font-size: 14px;">
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Booking ID:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold;">{{$appointment->booking_id}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Doctor:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold;">Dr. {{$appointment->doctor->user->name ?? ''}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Date:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold;">{{ date('d-m-Y', strtotime($appointment->booking_date)) }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Time:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold;">{{ $appointment->booking_time_slot }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Consultation Fee:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold; color: #1baeff;">{{ $appointment->formatted_consultation_fee }}</td>
                                                                                </tr>
                                                                                @if($appointment->is_urgent)
                                                                                <tr>
                                                                                    <td style="padding: 8px 0; color: #666;">Priority:</td>
                                                                                    <td style="padding: 8px 0; font-weight: bold; color: #dc3545;">URGENT</td>
                                                                                </tr>
                                                                                @endif
                                                                            </table>
                                                                        </div>
                                                                        
                                                                        <!-- Payment Button -->
                                                                        <div style="margin: 25px 0; text-align: center;">
                                                                            <a href="{{ $payment_url }}" style="display: inline-block; background: #1baeff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                                                                                Pay Now
                                                                            </a>
                                                                        </div>
                                                                        
                                                                        <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: left;">
                                                                            Or copy and paste this link: <br>
                                                                            <a href="{{ $payment_url }}" style="color: #1baeff; word-break: break-all;">{{ $payment_url }}</a>
                                                                        </p>
                                                                        
                                                                        <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: left;">
                                                                            <strong>Note:</strong> Your appointment will be confirmed only after successful payment.
                                                                        </p>
                                                                        
                                                                        <p style="margin:30px 0 16px; font-size: 14px; line-height: 26px; color: #333; text-align: left;">
                                                                            Thank you,<br>
                                                                            <strong style="color: #1baeff;">Mednero Team</strong>
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
                                                                    <td colspan="2" valign="middle"
                                                                        style="padding:0;border:0;color:#666;font-family:Arial;font-size:12px;line-height:125%;text-align:center; background: #e6f3ff;">
                                                                        <p style="color: #666; padding-top: 20px; font-style: 14px; margin-top: 0px">
                                                                            © {{date('Y')}} Mednero. All Rights Reserved.
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