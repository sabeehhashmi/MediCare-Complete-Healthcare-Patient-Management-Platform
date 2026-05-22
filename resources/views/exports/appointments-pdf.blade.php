<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        th { background: #E6B8B7; }
        pre { white-space: pre-wrap; }
    </style>
</head>
<body>

<h3>Appointments Export</h3>

<table>
    <thead>
        <tr>
            <th>SL</th>
            <th>Booking ID</th>
            <th>Date</th>
            <th>Hospital</th>
            <th>Doctor</th>
            <th>Patient</th>
            <th>Status</th>
            <th>Prescription</th>
            <th>Symptoms</th>
            <th>Lab</th>
            <th>Xray</th>
        </tr>
    </thead>

    <tbody>
    @foreach($data as $key => $item)

        @php
            $patient = ($item->member->full_name ?? $item->user->first_name.' '.$item->user->last_name)
                        . ' | ' . ($item->user->phone ?? '');

            $prescriptionText = '';

            if($item->prescription) {
                foreach($item->prescription->details as $d) {
                    $prescriptionText .=
                        ($d->medicine?->title_en ?? '') . ' (' . ($d->quantity ?? '') . ') | ' .
                        ($d->dosage_value ?? '') . ' ' . ($d->dosage?->title ?? '') . ' | ' .
                        ($d->direction?->title ?? '') . ' | ' .
                        ($d->duration?->title ?? '') . "\n";
                }
            }
        @endphp

        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $item->booking_id }}</td>
            <td>{{ $item->booking_date }}</td>
            <td>{{ $item->hospital->name_en ?? '' }}</td>
            <td>{{ $item->doctor->user->name ?? '' }}</td>
            <td>{{ $patient }}</td>
            <td>{{ strtoupper($item->booking_status) }}</td>
            <td><pre>{{ $prescriptionText }}</pre></td>
            <td>{{ $item->clinical_assessment->symptoms ?? '' }}</td>
            <td>
                @foreach($item->labReports as $lab)
                    {{ $lab->docment }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item->xrayReports as $xray)
                    {{ $xray->docment }}<br>
                @endforeach
            </td>
        </tr>

    @endforeach
    </tbody>
</table>

</body>
</html>