<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}} - OTP Verification</title>
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
                                                                                <h4 style="font-weight: 600; font-size: 18px; color: #1baeff; margin-top: 0;">{{env('APP_NAME')}} Verification</h4>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    Hi {{$user->name}},
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    Thank you for using {{env('APP_NAME')}}. Your One-Time Password (OTP) for verification is:
                                                                                </p>
                                                                                
                                                                                <!-- OTP Box -->
                                                                                <div style="margin: 30px 0; text-align: center;">
                                                                                    <span style="display: inline-block; background: #e6f3ff; color: #1baeff; font-size: 36px; font-weight: bold; letter-spacing: 8px; padding: 15px 30px; border-radius: 8px; border: 2px solid #1baeff;">
                                                                                        {{$otp}}
                                                                                    </span>
                                                                                </div>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    This OTP is valid for <strong>10 minutes</strong>. Please do not share this code with anyone for security reasons.
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    If you didn't request this verification, please ignore this email or contact our support team.
                                                                                </p>
                                                                                
                                                                                <p style="margin:30px 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    Regards,<br>
                                                                                    <strong style="color: #1baeff;">{{env('APP_NAME')}} Team</strong>
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