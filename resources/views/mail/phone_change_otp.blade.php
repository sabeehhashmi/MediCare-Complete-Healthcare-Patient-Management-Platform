<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }}</title>
</head>

<body style="margin:0;background:#1baeff;">

<div style="padding:20px;">

<table width="100%">
<tr>
<td align="center">

<table width="600" style="background:#fff;border-radius:10px;overflow:hidden;">

<tr>
<td style="background:#e6f3ff;padding:20px;">

<img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}"
     style="max-width:180px;">

</td>
</tr>

<tr>
<td style="padding:30px;font-family:Arial;">

<h2>Phone Number Verification</h2>

<p>
You requested to change your phone number.
Use the OTP below to verify your request.
</p>

<div style="text-align:center;margin:30px 0;">

<span style="
display:inline-block;
font-size:30px;
letter-spacing:8px;
font-weight:bold;
background:#f2f8ff;
padding:15px 30px;
border-radius:8px;
color:#1baeff;
">
{{ $otp }}
</span>

</div>

<p>
This OTP will expire in 5 minutes.
</p>

<p>
If you did not request this change,
please ignore this email.
</p>

<p>
Regards,<br>
<b>{{ env('APP_NAME') }}</b>
</p>

</td>
</tr>

<tr>
<td style="background:#e6f3ff;padding:15px;text-align:center;">

© {{ date('Y') }} {{ env('APP_NAME') }}

</td>
</tr>

</table>

</td>
</tr>
</table>

</div>

</body>
</html>