<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to {{env('APP_NAME')}}</title>
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
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333;">
                                                                                    Dear <strong>{{$user->name}}</strong>,
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333;">
                                                                                    Welcome to the {{env('APP_NAME')}} Medical Portal.
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333;">
                                                                                    Your account has been successfully created.
                                                                                </p>
                                                                                
                                                                                <!-- Registration Details Box -->
                                                                                <div style="margin: 30px 0; background: #e6f3ff; padding: 20px; border-radius: 8px; border-left: 4px solid #1baeff;">
                                                                                    <h4 style="margin-top: 0; margin-bottom: 15px; color: #1baeff; font-size: 16px;">Registration Details:</h4>
                                                                                    
                                                                                    <table style="width: 100%; font-size: 14px; line-height: 24px;">
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666; width: 40%;">Patient ID:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">{{$user->patient_id ?? $user->id}}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Registered Mobile Number:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">+{{$user->dial_code}} {{$user->phone}}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding: 5px 0; color: #666;">Registration Date:</td>
                                                                                            <td style="padding: 5px 0; color: #333; font-weight: 500;">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    You can now log in to book appointments, access prescriptions, view lab reports, and manage your medical records securely.
                                                                                </p>
                                                                                
                                                                               @php
                                                                                    use App\Models\SettingsModel;
                                                                                    $settings = SettingsModel::first();
                                                                                @endphp

                                                                                <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    If you require any assistance, please contact our support team at 
                                                                                    <a href="mailto:{{ $settings->support_email ?? env('SUPPORT_EMAIL', 'support@mednero.com') }}" 
                                                                                    style="color: #1baeff; text-decoration: none;">
                                                                                    {{ $settings->support_email ?? env('SUPPORT_EMAIL', 'support@mednero.com') }}
                                                                                    </a> 
                                                                                    or call <strong style="color: #1baeff;">{{ $settings->support_phone ?? env('SUPPORT_PHONE', '+971 XXX XXX XXX') }}</strong>.
                                                                                </p>
                                                                                
                                                                                <p style="margin:0 0 16px; font-size: 16px; line-height: 26px; color: #333;">
                                                                                    Thank you for choosing {{env('APP_NAME')}}.
                                                                                </p>
                                                                                
                                                                                <p style="margin:30px 0 16px; font-size: 14px; line-height: 26px; color: #333;">
                                                                                    Best regards,<br>
                                                                                    <strong style="color: #1baeff; font-size: 16px;">{{env('APP_NAME')}} Support Team</strong>
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