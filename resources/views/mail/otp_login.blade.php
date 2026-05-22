<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
</head>

<body style="margin: 0; background: #1baeff;">

<div style="padding:20px 0; background:#1baeff; width:100%;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#1baeff;">
<tr>
<td align="center">

<table width="600" style="background:#ffffff; border-radius:10px; overflow:hidden;">

    <!-- HEADER -->
    <tr>
        <td style="background:#e6f3ff; padding:20px;">
            <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}" 
                 style="max-width:180px;">
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding:30px; font-family: Arial, sans-serif; color:#000;">

            <h3 style="margin-top:0;">Login Verification</h3>

            <p style="font-size:14px; line-height:24px;">
                You are trying to login to your account. Please use the OTP below to complete your login.
            </p>

            <!-- OTP BOX -->
            <div style="text-align:center; margin:30px 0;">
                <span style="
                    display:inline-block;
                    font-size:28px;
                    letter-spacing:10px;
                    font-weight:bold;
                    color:#1baeff;
                    background:#f2f8ff;
                    padding:15px 25px;
                    border-radius:8px;
                ">
                    {{ $otp }}
                </span>
            </div>


            <p style="font-size:14px; color:#555;">
                If you did not attempt to login, please ignore this email.
            </p>

            <p style="margin-top:30px;">
                Regards,<br>
                <b>{{ env('APP_NAME') }} Team</b>
            </p>

        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="background:#e6f3ff; text-align:center; padding:15px;">
            <p style="margin:0; font-size:12px; color:#000;">
                © {{ date('Y') }} {{ env('APP_NAME') }}. All Rights Reserved.
            </p>
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</div>

</body>
</html>