<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
</head>

<body style="margin: 0; color: #fff; background: #89124B;">

    <div marginwidth="0" marginheight="0">
    <div marginwidth="0" marginheight="0" id="" dir="ltr" style="background-color: #89124B;  margin:0;padding:20px 0 20px 0;width:100%; margin: 0;">

    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #89124B;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#89124B;border-radius:10px!important;overflow: hidden;">
                        <tbody>
                            <tr>
                                <td style="background: #FFDEEE;">
                                    <div style="padding: 15px 20px; background:#FFDEEE; padding-bottom: 15px;">
                                        <table style="background:#FFDEEE; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        
                                                        <img src="{{ URL::asset('mydrlogo.png') }}" alt="" style="max-width: 190px; margin-bottom: 0px; ">

                                                        <!-- <h2 style="color: #000; font-size: 30px;line-height: 100%; margin-bottom: 0;">Pending Appointment </h2> -->
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
                                                            <h4 style="font-weight: 600; font-size: 18px; color: #000;margin-top: 0;">{{$title}}</h4>
                                                            <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #000; text-align: left;">
                                                                Dr. {{$order->doctor->user->name}} has received a pending appointment request from {{$patient_name}} for {{date('d/m/Y',strtotime($order->booking_date))}} at {{date('H:i a',strtotime($order->booking_time_slot))}}.
                                                            </p>
                                                            <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #000; text-align: left;">
                                                                To confirm or Reschedule the appointment, please visit the online portal <a href="#!" target="_blank" style="color: #89124B">[Link to online portal - View appointment details]</a>.
                                                            </p>
                                                            <p style="margin:0 0 16px; font-size: 14px; line-height: 26px; color: #000; text-align: left;">
                                                                Best regards,
                                                                <br>
                                                                <b>Mednero Team</b>
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
                            <td style="background: #FFDEEE;">
                                <div style="padding: 20px; background: #FFDEEE;">
                                    <table style="background: #FFDEEE; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width: 100%;">
                                        <tbody>

                                            <tr>
                                                <td style="width: 100%;" colspan="2">
                                                    <table style="font-size: 14px; width: 100%;">
                                                        <tbody>


                                                            <tr>
                                                                <td colspan="2" valign="middle"
                                                                    style="padding:0;border:0;color:#000;font-family:Arial;font-size:12px;line-height:125%;text-align:center; background: #FFDEEE;">
                                                                    <p style="color: #000; padding-top: 20px; font-style: 14px; margin-top: 0px">
                                                                        © {{date('Y')}} Mednero. All Rights Reserved.</p>
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
