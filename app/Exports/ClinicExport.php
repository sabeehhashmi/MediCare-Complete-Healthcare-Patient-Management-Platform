<?php

namespace App\Exports;

use App\Models\Hospital;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;


class ClinicExport
{
    public function export($filters)
    {
        $query = Hospital::whereHas('user', function ($q) {
            $q->where('deleted', 0);
        })->where('type', TYPE_CLINIC);

        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;
        $emirateId = $filters['emirate_id'] ?? null;
        $status = $filters['hospital_status'] ?? null;

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        if ($emirateId) {
            $query->where('emirate_id', $emirateId);
        }

        if ($status !== null) {
            $query->whereHas('user', function ($q) use($status){
                $q->where('active', $status);
            });
        }

        $hospitals = $query->get()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'SL#');
        $sheet->setCellValue('B1', 'Clinic Name');
        $sheet->setCellValue('C1', 'Clinic Name (ar)');
        $sheet->setCellValue('D1', 'Country');
        $sheet->setCellValue('E1', 'Emirates/ City/ Province');
        $sheet->setCellValue('F1', 'Area');
        $sheet->setCellValue('G1', 'Address Of Organization');
        $sheet->setCellValue('H1', 'Location');
        $sheet->setCellValue('I1', 'Clinic Direct Number to Book an Appointment ');
        $sheet->setCellValue('J1', 'Email Address');
        $sheet->setCellValue('K1', 'Website');
        $sheet->setCellValue('L1', 'Direct Call for Appointment Number');
        $sheet->setCellValue('M1', 'Clinic Profile');
        $sheet->setCellValue('N1', 'Clinic Profile (ar)');
        $sheet->setCellValue('O1', 'Status');
        $sheet->setCellValue('P1', 'Registration Date');

        // Set column widths
        $columnWidths = [
            'A' => 5,
            'B' => 30,
            'C' => 30,
            'D' => 20,
            'E' => 30,
            'F' => 20,
            'G' => 40,
            'H' => 30,
            'I' => 20,
            'J' => 30,
            'K' => 20,
            'L' => 30,
            'M' => 40,
            'N' => 40,
            'O' => 15,
            'P' => 15,
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

        $sheet->getStyle('A1:P1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($hospitals as $key => $hospital) {
            $sheet->setCellValue('A' . $row, ($key + 1));
            $sheet->setCellValue('B' . $row, $hospital->name_en);
            $sheet->setCellValue('C' . $row, $hospital->name_ar);
            $sheet->setCellValue('D' . $row, $hospital->country->name ?? null);
            $sheet->setCellValue('E' . $row, $hospital->emirate->name_en ?? null);
            $sheet->setCellValue('F' . $row, $hospital->area->name_en ?? null);
            $sheet->setCellValue('G' . $row, $hospital->address);
            $sheet->setCellValue('H' . $row, $hospital->location[0]->location ?? null);
            $sheet->setCellValue('I' . $row, ($hospital->user->dial_code ?  '+('.$hospital->user->dial_code . ') ' : '') . $hospital->user->phone);
            $sheet->setCellValue('J' . $row, $hospital->user->email);
            $sheet->setCellValue('K' . $row, $hospital->website);
            $sheet->setCellValue('L' . $row, ($hospital->appointment_dial_code ?  '+'.$hospital->appointment_dial_code: '') . $hospital->appointment_phone);
            $sheet->setCellValue('M' . $row, strip_tags($hospital->profile_description));
            $sheet->setCellValue('N' . $row, strip_tags($hospital->profile_description_ar));
            $sheet->setCellValue('O' . $row, $hospital->user->active == 1 ? 'Active' : 'Inactive');
            $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $hospital->created_at);
            $sheet->setCellValue('P' . $row, $createdAt->format('d-m-Y'));
            $row++;
        }

        $sheet->getStyle('A1:P' . ($row - 1))
            ->getAlignment()
            ->setWrapText(true);

        $sheet->getStyle('A1:P' . ($row - 1))
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'clinics.xlsx';
        $writer->save($fileName);

        return $fileName;
    }
}
