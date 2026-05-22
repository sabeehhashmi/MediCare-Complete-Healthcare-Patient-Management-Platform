<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: dejavusans;
            font-size: 11px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid #000;
            padding:6px;
            vertical-align: top;
            text-align:left;
        }

        th{
            background:#f2f2f2;
            font-weight:bold;
        }

        h2{
            margin-bottom:15px;
        }

    </style>
</head>
<body>

    <h2>Points History Report</h2>

    <table>

        <thead>
            <tr>
                <th>SL#</th>
                <th>Booking ID</th>
                <th>Points</th>
                <th>Type</th>
                <th>Status</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Clinic</th>
                <th>Consultation Fee</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>

            @forelse($data as $key => $point)

                @php

                    $appointment = $point->appointment;

                    $patient =
                        $appointment->patient_member_name
                        ?? ($appointment->user->name ?? 'N/A');

                    $doctor =
                        $appointment->doctor->user->name ?? 'N/A';

                    $clinic =
                        $appointment->hospital->name_en ?? 'N/A';

                @endphp

                @if($appointment)

                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td>
                        {{ $appointment->booking_id ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $point->points }}
                    </td>

                    <td>
                        {{ ucfirst($point->type) }}
                    </td>

                    <td>
                        {{ $appointment->booking_status ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $patient }}
                    </td>

                    <td>
                        {{ $doctor }}
                    </td>

                    <td>
                        {{ $clinic }}
                    </td>

                    <td>
                        AED {{ $appointment->consultation_fee ?? '0' }}
                    </td>

                    <td>
                        {{ $appointment->booking_date ?? 'N/A' }}
                    </td>

                    <td>
                        {{
                            $appointment->booking_time_slot
                            ? \Carbon\Carbon::parse($appointment->booking_time_slot)->format('h:i A')
                            : 'N/A'
                        }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($point->created_at)->format('d-m-Y h:i A') }}
                    </td>

                </tr>

                @endif

            @empty

                <tr>
                    <td colspan="12" align="center">
                        No records found
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>