<?php
namespace App\Exports;

use App\Models\PointHistory;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PointHistoryExport
{
    public function export($filters)
    {

        $user = auth()->user();
        $query = PointHistory::where('user_id',$user->id)->with([
            'appointment.doctor.user',
            'appointment.hospital',
            'appointment.member',
            'user'
        ]);


       
        // USER FILTER
        if ($filters['user_id'] ?? null) {
            $query->where('user_id', $filters['user_id']);
        }

        // DATE FILTERS
        if ($filters['booking_from'] ?? null) {

            $from = Carbon::createFromFormat(
                'd-m-Y',
                $filters['booking_from']
            )->format('Y-m-d');

            $query->whereDate('created_at', '>=', $from);
        }

        if ($filters['booking_to'] ?? null) {

            $to = Carbon::createFromFormat(
                'd-m-Y',
                $filters['booking_to']
            )->format('Y-m-d');

            $query->whereDate('created_at', '<=', $to);
        }

        // BOOKING ID FILTER
        if ($filters['booking_id'] ?? null) {

            $bookingId = strtolower(trim($filters['booking_id']));

            $query->whereHas('appointment', function ($q) use ($bookingId) {

                $q->whereRaw(
                    'LOWER(booking_id) LIKE ?',
                    ['%' . $bookingId . '%']
                );
            });
        }

        $data = $query->latest()->get();

        // ================= EXCEL =================

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A1' => 'SL#',
            'B1' => 'Booking ID',
            'C1' => 'Points',
            'D1' => 'Type',
            'E1' => 'Booking Status',
            'F1' => 'Patient',
            'G1' => 'Clinic',
            'H1' => 'Booking Date',
            'I1' => 'Booking Time',
            'J1' => 'Consultation Fee',
            'K1' => 'Created At',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // HEADER STYLE
        $sheet->getStyle('A1:K1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6B8B7'],
            ],
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
        ]);

        // DATA
        $row = 2;

        foreach ($data as $key => $point) {

            $appointment = $point->appointment;

            $patient =
                $appointment->patient_member_name
                ?? $appointment->user->name
                ?? 'N/A';
           if ($appointment) {

    $patient =
        $appointment->patient_member_name
        ?? ($appointment->user->name ?? 'N/A');

    $doctor =
        $appointment->doctor->user->name ?? 'N/A';

    $clinic =
        $appointment->hospital->name_en ?? 'N/A';

    $sheet->setCellValue("A$row", $key + 1);
    $sheet->setCellValue("B$row", $appointment->booking_id ?? 'N/A');
    $sheet->setCellValue("C$row", $point->points);
    $sheet->setCellValue("D$row", $point->type ?? 'N/A');
    $sheet->setCellValue("E$row", $appointment->booking_status ?? 'N/A');
    $sheet->setCellValue("F$row", $patient);
    $sheet->setCellValue("G$row", $clinic);
    $sheet->setCellValue("H$row", $appointment->booking_date ?? 'N/A');
    $sheet->setCellValue(
        "I$row",
        $appointment->booking_time_slot
            ? Carbon::parse($appointment->booking_time_slot)->format('h:i A')
            : 'N/A'
    );
    $sheet->setCellValue(
    "J$row",
    $appointment->consultation_fee ?? '0'
);

$sheet->setCellValue(
    "K$row",
    Carbon::parse($point->created_at)->format('d-m-Y h:i A')
);

    $row++;
}
        }

       $sheet->getStyle("A1:K" . ($row - 1))
            ->getAlignment()
            ->setWrapText(true);

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'points-history.xlsx';

        $writer->save($fileName);

        return $fileName;
    }

    public function exportPdf($filters)
    {
        $user = auth()->user();
        $query = PointHistory::where('user_id',$user->id)->with([
            'appointment.doctor.user',
            'appointment.hospital',
            'appointment.member',
            'user'
        ]);

        if ($filters['user_id'] ?? null) {
            $query->where('user_id', $filters['user_id']);
        }

        if ($filters['booking_from'] ?? null) {

            $from = Carbon::createFromFormat(
                'd-m-Y',
                $filters['booking_from']
            )->format('Y-m-d');

            $query->whereDate('created_at', '>=', $from);
        }

        if ($filters['booking_to'] ?? null) {

            $to = Carbon::createFromFormat(
                'd-m-Y',
                $filters['booking_to']
            )->format('Y-m-d');

            $query->whereDate('created_at', '<=', $to);
        }

        if ($filters['booking_id'] ?? null) {

            $bookingId = strtolower(trim($filters['booking_id']));

            $query->whereHas('appointment', function ($q) use ($bookingId) {

                $q->whereRaw(
                    'LOWER(booking_id) LIKE ?',
                    ['%' . $bookingId . '%']
                );
            });
        }

        return $query->latest()->get();
    }
}