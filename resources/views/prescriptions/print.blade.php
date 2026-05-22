<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription #{{$appointment->booking_id}}</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .ar {
            direction: rtl;
            text-align: right;
        }

        .bn {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .prescription {
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .clinic-name {
            font-size: 22px;
            font-weight: bold;
        }

        .clinic-details {
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .doctor-info td,
        .patient-info td {
            padding: 5px;
            vertical-align: top;
        }

        .medicine-table th,
        .medicine-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .medicine-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }

        .small-text {
            font-size: 12px;
            color: #555;
        }

        .logo {
            max-height: 55px;
        }

        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="{{ $prescription->language == 'ar' ? 'ar' : ($prescription->language == 'bn' ? 'bn' : '') }}">

<div class="prescription">

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td width="80%">
                    <div class="clinic-name">
                        @if($prescription->language == 'ar')
                            {{ $appointment->hospital->name_ar ?? $appointment->hospital->name_en }}
                        @elseif($prescription->language == 'bn')
                            {{ $appointment->hospital->name_bn ?? $appointment->hospital->name_en }}
                        @else
                            {{ $appointment->hospital->name_en }}
                        @endif
                    </div>
                    <div class="clinic-details">
                        @if($prescription->language == 'ar')
                            {{ $appointment->hospital->address_ar ?? $appointment->hospital->address }}
                        @elseif($prescription->language == 'bn')
                            {{ $appointment->hospital->address_bn ?? $appointment->hospital->address }}
                        @else
                            {{ $appointment->hospital->address }}
                        @endif
                        <br>
                        @if($prescription->language == 'ar')
                            هاتف:
                        @elseif($prescription->language == 'bn')
                            ফোন:
                        @else
                            Phone:
                        @endif
                        {{ $appointment->hospital->user->dial_code }} {{ $appointment->hospital->user->phone }} |
                        @if($prescription->language == 'ar')
                            بريد إلكتروني:
                        @elseif($prescription->language == 'bn')
                            ইমেইল:
                        @else
                            Email:
                        @endif
                        {{ $appointment->hospital->user->email }}
                    </div>
                </td>
                <td width="20%" align="right">
                    <img src="https://dxbitprojects.com/p1/public/hospital/assets/images/logo-mednero.png" class="logo">
                </td>
            </tr>
        </table>
    </div>

    @php
        $formattedDate = \Carbon\Carbon::parse($appointment->booking_date)->format('d F Y');

if ($prescription->language == 'bn') {
    $formattedDate = \Carbon\Carbon::parse($appointment->booking_date)
        ->locale('bn')
        ->translatedFormat('d F Y');
}
    @endphp

    <!-- Doctor Info -->
    <table class="doctor-info">
        <tr>
            <td>
                @if($prescription->language == 'ar')
                    <strong>طبيب:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>ডাক্তার:</strong>
                @else
                    <strong>Doctor:</strong>
                @endif
                {{$appointment->doctor->user->name ?? ''}} <br>
                
                @if($prescription->language == 'ar')
                    <strong>المؤهل:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>যোগ্যতা:</strong>
                @else
                    <strong>Qualification:</strong>
                @endif
                {{ $appointment->doctor->qualifications ? $appointment->doctor->qualifications->pluck('title')->implode(', ') : '' }}
            </td>
            <td align="right">
                @if($prescription->language == 'ar')
                    <strong>تاريخ:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>তারিখ:</strong>
                @else
                    <strong>Date:</strong>
                @endif
                {{$formattedDate}} <br>
                
                @if($prescription->language == 'ar')
                    <strong>رقم الوصفة:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>প্রেসক্রিপশন আইডি:</strong>
                @else
                    <strong>Prescription ID:</strong>
                @endif
                {{$appointment->booking_id}}
            </td>
        </tr>
    </table>

    <!-- Patient Info -->
    <table class="patient-info">
        <tr>
            <td>
                @if($prescription->language == 'ar')
                    <strong>اسم المريض:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>রোগীর নাম:</strong>
                @else
                    <strong>Patient Name:</strong>
                @endif
                {{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}
            </td>
            <td>
                @if($prescription->language == 'ar')
                    <strong>تاريخ الميلاد:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>জন্ম তারিখ:</strong>
                @else
                    <strong>DOB:</strong>
                @endif
                {{$appointment->user->dob ?? ''}}
            </td>
            <td>
                @if($prescription->language == 'ar')
                    <strong>الجنس:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>লিঙ্গ:</strong>
                @else
                    <strong>Gender:</strong>
                @endif
                {{ GENDERS[($appointment->user->gender ?? null)] ?? 'N/A'}}
            </td>
        </tr>
        <tr>
            <td>
                @if($prescription->language == 'ar')
                    <strong>هاتف:</strong>
                @elseif($prescription->language == 'bn')
                    <strong>ফোন:</strong>
                @else
                    <strong>Phone:</strong>
                @endif
                {{$appointment->doctor->user->phone ? ('+'.$appointment->doctor->user->dial_code.' '.$appointment->doctor->user->phone) : 'N/A'}}
            </td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br>

    <!-- Medicine Table Headers -->
    @php
        $headers = [
            'medicine' => ['en' => 'Medicine', 'ar' => 'الدواء', 'bn' => 'ঔষধ'],
            'dosage' => ['en' => 'Dosage', 'ar' => 'الجرعة', 'bn' => 'ডোজ'],
            'direction' => ['en' => 'Direction', 'ar' => 'الاتجاه', 'bn' => 'নির্দেশনা'],
            'frequency' => ['en' => 'Frequency', 'ar' => 'التكرار', 'bn' => 'ফ্রিকোয়েন্সি'],
            'duration' => ['en' => 'Duration', 'ar' => 'المدة', 'bn' => 'মেয়াদ'],
            'quantity' => ['en' => 'Quantity', 'ar' => 'الكمية', 'bn' => 'পরিমাণ'],
            'instructions' => ['en' => 'Instructions', 'ar' => 'تعليمات', 'bn' => 'নির্দেশাবলী']
        ];
        $lang = $prescription->language;
    @endphp

    <table class="medicine-table">
        <thead>
            <tr>
                <th>{{ $headers['medicine'][$lang] ?? $headers['medicine']['en'] }}</th>
                <th>{{ $headers['dosage'][$lang] ?? $headers['dosage']['en'] }}</th>
                <th>{{ $headers['direction'][$lang] ?? $headers['direction']['en'] }}</th>
                <th>{{ $headers['frequency'][$lang] ?? $headers['frequency']['en'] }}</th>
                <th>{{ $headers['duration'][$lang] ?? $headers['duration']['en'] }}</th>
                <th>{{ $headers['quantity'][$lang] ?? $headers['quantity']['en'] }}</th>
                <th>{{ $headers['instructions'][$lang] ?? $headers['instructions']['en'] }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->details as $detail)
            <tr>
                <td>
                    @if($prescription->language == 'ar')
                        {{ $detail->medicine->title_ar ?? $detail->medicine->title_en ?? '' }}
                    @elseif($prescription->language == 'bn')
                        {{ $detail->medicine->title_bn ?? $detail->medicine->title_en ?? '' }}
                    @else
                        {{ $detail->medicine->title_en ?? '' }}
                    @endif
                </td>
                <td>
                    @if($prescription->language == 'ar')
                        {{ $detail->dosage_value ?? '' }}
                        {{ $detail->dosage->title_ar ?? $detail->dosage->title ?? '' }}
                    @elseif($prescription->language == 'bn')
                        {{ $detail->dosage_value ?? '' }}
                        {{ $detail->dosage->title_ban ?? $detail->dosage->title ?? '' }}
                    @else
                        {{ $detail->dosage_value ?? '' }}
                        {{ $detail->dosage->title ?? '' }}
                    @endif
                </td>
                <td>
                    @if($prescription->language == 'ar')
                        {{ $detail->direction->title_ar ?? $detail->direction->title ?? '' }}
                    @elseif($prescription->language == 'bn')
                        {{ $detail->direction->title_ban ?? $detail->direction->title ?? '' }}
                    @else
                        {{ $detail->direction->title ?? '' }}
                    @endif
                </td>
                <td>
                    @if($prescription->language == 'ar')
                        {{ $detail->frequency->title_ar ?? $detail->frequency->title ?? '' }}
                    @elseif($prescription->language == 'bn')
                        {{ $detail->frequency->title_ban ?? $detail->frequency->title ?? '' }}
                    @else
                        {{ $detail->frequency->title ?? '' }}
                    @endif
                </td>
                <td>
                    @if($prescription->language == 'ar')
                        {{ $detail->duration->title_ar ?? $detail->duration->title ?? '' }}
                    @elseif($prescription->language == 'bn')
                        {{ $detail->duration->title_ban ?? $detail->duration->title ?? '' }}
                    @else
                        {{ $detail->duration->title ?? '' }}
                    @endif
                </td>
                <td>{{ $detail->quantity ?? '' }}</td>
                <td>{{ $detail->instructions ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="small-text">
            @if($prescription->language == 'ar')
                هذه وصفة طبية مولدة رقمياً
            @elseif($prescription->language == 'bn')
                এটি একটি ডিজিটাল প্রেসক্রিপশন
            @else
                This is a digitally generated prescription.
            @endif
        </div>

        <div class="signature">
            <strong>{{$appointment->doctor->user->name ?? ''}}</strong>
        </div>
         <div class="signature">
            <img src="{{$appointment->doctor->user_signature ?? ''}}" width="200px">
        </div>
    </div>

</div>

<script>
window.onload = function() {
    window.print();
    window.onafterprint = function() {
        window.close();
    };
};
</script>

</body>
</html>