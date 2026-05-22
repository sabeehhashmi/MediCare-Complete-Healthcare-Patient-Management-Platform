<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{env('APP_NAME')}}</title>
</head>

<body style="margin:0; background:#1baeff;">

<div style="padding:20px; background:#1baeff;">
    <table width="600" align="center" style="background:#fff; border-radius:10px; overflow:hidden;">

        <!-- Header -->
        <tr>
            <td style="background:#e6f3ff; padding:15px;">
                <img src="{{ URL::asset('hospital/assets/images/logo-mednero.png') }}" style="max-width:180px;">
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:20px; font-family:Arial;">

                <h3 style="color:#1baeff;">
                    New Appointment Booked
                </h3>

                <p>Dear Dr. {{$appointment->doctor->user->name ?? ''}},</p>

                <p>A new appointment has been scheduled.</p>

                <div style="background:#f8f9fa; padding:15px; border-radius:8px;">
                    <p><strong>Booking ID:</strong> {{$appointment->booking_id}}</p>
                    <p><strong>Patient:</strong> {{$appointment->user->name}}</p>
                    <p><strong>Date:</strong> {{ date('d-m-Y', strtotime($appointment->booking_date)) }}</p>
                    <p><strong>Time:</strong> {{$appointment->booking_time_slot}}</p>
                    <p><strong>Fee:</strong> {{$appointment->formatted_consultation_fee}}</p>

                    @if($appointment->is_urgent)
                        <p style="color:red;"><strong>URGENT APPOINTMENT</strong></p>
                    @endif
                </div>

                <p style="margin-top:20px;">
                    Please check your dashboard for details.
                </p>

                <p>Regards,<br><strong>Mednero Team</strong></p>

            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background:#e6f3ff; text-align:center; padding:15px; font-size:12px;">
                © {{date('Y')}} Mednero
            </td>
        </tr>

    </table>
</div>

</body>
</html>