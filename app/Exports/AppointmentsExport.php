<?php

namespace App\Exports;

use App\Models\DoctorPatientAppointment;
use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AppointmentsExport
{
    public function export($filters)
    {
        $query = DoctorPatientAppointment::query()
            ->with([
                'followups',
                'status_history.changed_by',
                'prescription.details.medicine',
                'prescription.details.direction',
                'prescription.details.dosage',
                'prescription.details.duration',
                'summaries',
                'labReports',
                'xrayReports',
                'clinical_assessment',
                'doctor.user',
                'hospital.user',
                'user',
                'member',
                'created_by_user'
            ]);

        // ================= FILTERS =================
        if ($filters['patient_id'] ?? null) {
            $userIds = User::where('patient_id', $filters['patient_id'])->pluck('id');

            if ($userIds->isNotEmpty()) {
                $query->whereIn('user_id', $userIds);
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($filters['doctor_id'] ?? null) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if ($filters['hospital_id'] ?? null) {
            $query->where('hospital_id', $filters['hospital_id']);
        }

        if ($filters['booking_from'] ?? null) {
            $date = Carbon::createFromFormat('d-m-Y', $filters['booking_from'])->format('Y-m-d');
            $query->whereDate('booking_date', '>=', $date);
        }

        if ($filters['booking_to'] ?? null) {
            $date = Carbon::createFromFormat('d-m-Y', $filters['booking_to'])->format('Y-m-d');
            $query->whereDate('booking_date', '<=', $date);
        }

        if ($filters['booking_status'] ?? null) {
            $query->where('booking_status', $filters['booking_status']);
        }

        if ($filters['booking_id'] ?? null) {

    $bookingId = strtolower(trim($filters['booking_id']));

    $query->whereRaw(
        'LOWER(booking_id) LIKE ?',
        ['%' . $bookingId . '%']
    );
}
            
            if ($filters['user_id'] ?? null) {
                $query->where('user_id', $filters['user_id']);
            }

        $data = $query->orderByDesc('id')->get();

        // ================= EXCEL =================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADERS
        $headers = [
            'A1' => 'SL#',
            'B1' => 'Booking Id',
            'C1' => 'Date & Time',
            'D1' => 'Previous Date',
            'E1' => 'Hospital',
            'F1' => 'Doctor',
            'G1' => 'Patient',
            'H1' => 'Cancel Reason',
            'I1' => 'Reschedule Reason',
            'J1' => 'Followups',
            'K1' => 'Status',
            'L1' => 'Processed By',
            'M1' => 'History',
            'N1' => 'Symptoms',
            'O1' => 'Present Illness',
            'P1' => 'Past History',
            'Q1' => 'Prescription',
            'R1' => 'Clinical Summary',
            'S1' => 'Lab Reports',
            'T1' => 'Xray Reports',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // STYLE
        $sheet->getStyle('A1:T1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6B8B7'],
            ],
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
        ]);

        // ================= DATA =================
        $row = 2;

        foreach ($data as $key => $item) {

            // Patient Name
            $patient = $item->member->full_name ??
                trim(($item->user->first_name ?? '') . ' ' . ($item->user->last_name ?? ''));

            $phone = ($item->user->dial_code ? '+' . $item->user->dial_code : '') . ($item->user->phone ?? '');
            $patient .= " | " . $phone;

            // Followups
            $followups = 'N/A';
            if ($item->followups->count()) {
                $tmp = [];
                foreach ($item->followups as $f) {
                    $tmp[] = Carbon::parse($f->followup_date)->format('d M Y') . ' | ' . $f->notes;
                }
                $followups = implode("\n", $tmp);
            }

            // Status History
            $history = [];
            foreach ($item->status_history as $h) {
                $history[] = $h->status . ' - ' .
                    ($h->changed_by->name ?? 'N/A') .
                    ' - ' . Carbon::parse($h->created_at)->format('d/m/Y H:i');
            }

            // Prescription
            $prescriptionText = 'N/A';
            if ($item->prescription && $item->prescription->details->count()) {
                $arr = [];

foreach ($item->prescription->details as $d) {

    $medicine  = $d->medicine->title_en ?? '';
    $qty       = $d->quantity ?? '';

    $dosageVal = $d->dosage_value ?? '';
    $dosage    = $d->dosage->title ?? 'N/A';

    $direction = $d->direction->title ?? 'N/A';
    $duration  = $d->duration->title ?? 'N/A';

    $instructions = $d->instructions ?? '';

    $arr[] = "{$medicine} ({$qty}) | {$dosageVal} {$dosage} | {$direction} | {$duration} | {$instructions}";
}
            }

            // Summaries
            $summaryText = 'N/A';
            if ($item->summaries->count()) {
                $tmp = [];
                foreach ($item->summaries as $s) {
                    $tmp[] = Carbon::parse($s->created_at)->format('d M Y') .
                        " | {$s->summary} | Follow: {$s->follow_up}";
                }
                $summaryText = implode("\n", $tmp);
            }

            // Lab Reports
            $labText = $item->labReports->count()
                ? implode("\n", $item->labReports->pluck('docment')->toArray())
                : 'N/A';

            // Xray
            $xrayText = $item->xrayReports->count()
                ? implode("\n", $item->xrayReports->pluck('docment')->toArray())
                : 'N/A';

            // Fill Excel
            $sheet->setCellValue("A$row", $key + 1);
            $sheet->setCellValue("B$row", $item->booking_id);
            $sheet->setCellValue("C$row", $item->booking_date . ' | ' . $item->booking_time_slot);
            $sheet->setCellValue("D$row", $item->previous_booking_date);
            $sheet->setCellValue("E$row", $item->hospital->name_en ?? '');
            $sheet->setCellValue("F$row", $item->doctor->user->name ?? '');
            $sheet->setCellValue("G$row", $patient);
            $sheet->setCellValue("H$row", $item->reason_cancel ?? 'N/A');
            $sheet->setCellValue("I$row", $item->reason_reschedule ?? 'N/A');
            $sheet->setCellValue("J$row", $followups);
            $sheet->setCellValue("K$row", strtoupper($item->booking_status));
            $sheet->setCellValue("L$row", $item->created_by_user->name ?? 'N/A');
            $sheet->setCellValue("M$row", implode("\n", $history));
            $sheet->setCellValue("N$row", $item->clinical_assessment->symptoms ?? 'N/A');
            $sheet->setCellValue("O$row", $item->clinical_assessment->present_illness ?? 'N/A');
            $sheet->setCellValue("P$row", $item->clinical_assessment->past_history ?? 'N/A');
            $sheet->setCellValue("Q$row", $prescriptionText);
            $sheet->setCellValue("R$row", $summaryText);
            $sheet->setCellValue("S$row", $labText);
            $sheet->setCellValue("T$row", $xrayText);

            $row++;
        }

        // Wrap text
        $sheet->getStyle("A1:T" . ($row - 1))
            ->getAlignment()
            ->setWrapText(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'appointments.xlsx';
        $writer->save($fileName);

        return $fileName;
    }

    public function exportPdf($filters)
{
    $query = DoctorPatientAppointment::query()
        ->with([
            'followups',
            'status_history.changed_by',
            'prescription.details.medicine',
            'prescription.details.direction',
            'prescription.details.dosage',
            'prescription.details.duration',
            'summaries',
            'labReports',
            'xrayReports',
            'clinical_assessment',
            'doctor.user',
            'hospital.user',
            'user',
            'member',
            'created_by_user'
        ]);

    // SAME FILTERS (reuse exactly)
    if ($filters['patient_id'] ?? null) {
        $userIds = User::where('patient_id', $filters['patient_id'])->pluck('id');

        if ($userIds->isNotEmpty()) {
            $query->whereIn('user_id', $userIds);
        } else {
            $query->whereRaw('1=0');
        }
    }

    if ($filters['doctor_id'] ?? null) {
        $query->where('doctor_id', $filters['doctor_id']);
    }
     if ($filters['booking_id'] ?? null) {

    $bookingId = strtolower(trim($filters['booking_id']));

    $query->whereRaw(
        'LOWER(booking_id) LIKE ?',
        ['%' . $bookingId . '%']
    );
}
    
    if ($filters['user_id'] ?? null) {
        $query->where('user_id', $filters['user_id']);
    }

    if ($filters['hospital_id'] ?? null) {
        $query->where('hospital_id', $filters['hospital_id']);
    }

    if ($filters['booking_from'] ?? null) {
        $date = Carbon::createFromFormat('d-m-Y', $filters['booking_from'])->format('Y-m-d');
        $query->whereDate('booking_date', '>=', $date);
    }

    if ($filters['booking_to'] ?? null) {
        $date = Carbon::createFromFormat('d-m-Y', $filters['booking_to'])->format('Y-m-d');
        $query->whereDate('booking_date', '<=', $date);
    }

    if ($filters['booking_status'] ?? null) {
        $query->where('booking_status', $filters['booking_status']);
    }

    $data = $query->orderByDesc('id')->get();

    return $data;
}
}