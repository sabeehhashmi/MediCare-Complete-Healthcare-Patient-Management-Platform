<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;

class PatientsExport
{
    public function export($filters)
    {
        $query = User::query()
            ->where('role', USER_ROLE)->where('deleted', 0);

        $fromDate = $filters['from_date'] ?? null;
        $patient_id = $filters['patient_id'] ?? null;
        $toDate = $filters['to_date'] ?? null;
        $gender = $filters['gender'] ?? null;
        $status = isset($filters['active']) ? $filters['active'] : null;

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($patient_id) {
            $query->whereDate('created_at',  $patient_id);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($status !== null) {
            $query->where('active', $status);
        }

        $patients = $query->get()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'SL#');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Phone');
        $sheet->setCellValue('E1', 'Whatsapp Phone');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'Registration Date');
        $sheet->setCellValue('H1', 'Age');
        $sheet->setCellValue('I1', 'Status');

        // Set column widths
        $columnWidths = [
            'A' => 5,
            'B' => 30,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 20,
            'H' => 10,
            'I' => 15,
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        $headerStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6B8B7'], // Yellow background
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'], // Black text color
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black border color
                ],
            ],
        ];

        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($patients as $key => $patient) {
            if ($patient->gender == '1') {
                $patient->gender = 'Male';
            } else if ($patient->gender == '2') {
                $patient->gender = 'Female';
            } else {
                $patient->gender = 'Other';
            }

            $dob = Carbon::parse($patient->dob);
            $age = (int) $dob->diffInYears(Carbon::today());
            $sheet->setCellValue('A' . $row, ($key + 1));
            $sheet->setCellValue('B' . $row, $patient->name);
            $sheet->setCellValue('C' . $row, $patient->email);
            $sheet->setCellValue('D' . $row, '(+' . $patient->dial_code . ')' . $patient->phone);
            $sheet->setCellValue('E' . $row, '+(' . $patient->whatsap_dial_code . ')' . $patient->whatsap_phone);
            $sheet->setCellValue('F' . $row, $patient->gender);
            $sheet->setCellValue('G' . $row, $dob->format('d/M/Y'));
            $sheet->setCellValue('H' . $row, $age);
            $sheet->setCellValue('I' . $row, $patient->active == 1 ? 'Active' : 'Disable');
            $row++;
        }

        $sheet->getStyle('A1:I' . ($row - 1))
            ->getAlignment()
            ->setWrapText(true);

        $sheet->getStyle('A1:I' . ($row - 1))
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'patients.xlsx';
        $writer->save($fileName);

        return $fileName;
    }
}
