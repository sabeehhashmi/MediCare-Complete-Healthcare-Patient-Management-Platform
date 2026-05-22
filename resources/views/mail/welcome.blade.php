<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
</head>

<body style="margin: 0; color: #fff; background: #1baeff;">

    <div marginwidth="0" marginheight="0">
        <div marginwidth="0" marginheight="0" id="" dir="ltr" style="background-color: #1baeff;  margin:0;padding:20px 0 20px 0;width:100%; margin: 0;">

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
                                                                <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}" alt="" style="max-width: 190px; margin-bottom: 0px; ">
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
                                                        <td valign="top" style="background-color: #fff;   padding:0;">
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%" style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" style="padding-bottom: 0px;">
                                                                            <div  style="color:#000;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left;margin-top: 0px">
                                                                                <h2>Welcome {{ $user->name }}!</h2>
                                                                                <p>Thank you for registering with MedNero. We're excited to have you on board!</p>
                                                                                
                                                                                <h3>Your Account Details:</h3>
                                                                                <ul>
                                                                                    <li>Name: {{ $user->name }}</li>
                                                                                    <li>Email: {{ $user->email }}</li>
                                                                                    <li>Phone: +{{ $user->dial_code }} {{ $user->phone }}</li>
                                                                                </ul>
                                                                                
                                                                                <p>You can now book appointments with doctors, manage your family members' health, and much more.</p>
                                                                                
                                                                                <p>
                                                                                    <a href="{{ route('front.bookings') }}" style="background-color: #1baeff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                                                                                        Go to Your Dashboard
                                                                                    </a>
                                                                                </p>
                                                                                
                                                                                <p>If you have any questions, feel free to contact our support team.</p>
                                                                                
                                                                                <p>Best regards,<br>MedNero Team</p>
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
                                                                                style="padding:0;border:0;color:#000;font-family:Arial;font-size:12px;line-height:125%;text-align:center; background: #e6f3ff;">
                                                                                <p style="color: #000; padding-top: 20px; font-style: 14px; margin-top: 0px">
                                                                                    © {{ date('Y') }} Mednero. All Rights Reserved.</p>
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
