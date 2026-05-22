<?php

namespace App\Exports;

use App\Models\DepartmentHospital;
use App\Models\Hospital;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;


class HospitalsExport
{
    public function export($filters)
{
    $type = TYPE_HOSPITAL;
    $status = null;

    if (array_key_exists('hospital_status', $filters)) {
        $status = $filters['hospital_status'];
    }

    if (array_key_exists('clinic_status', $filters)) {
        $type = TYPE_CLINIC;
        $status = $filters['clinic_status'];
    }

    $query = Hospital::where('hospitals.type', $type)
        ->whereNull('hospitals.deleted_at');

    $fromDate = '';
    if (isset($filters['booking_from'])) {
        $fromDate = Carbon::parse($filters['booking_from'])->format('Y-m-d');
    }

    $toDate = '';
    if (isset($filters['booking_to'])) {
        $toDate = Carbon::parse($filters['booking_to'])->format('Y-m-d');
    }

    $emirateId = $filters['emirate_id'] ?? null;

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
        $query->whereHas('user', function ($q) use ($status) {
            $q->where('active', $status);
        });
    }

    $hospitals = $query->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /*
    |--------------------------------------------------------------------------
    | Header Row (Website & Arabic Name Removed)
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue('A1', 'SL#');
    $sheet->setCellValue('B1', 'Hospital Name');
    $sheet->setCellValue('C1', 'Country');
    $sheet->setCellValue('D1', 'Emirates/ City/ Province');
    $sheet->setCellValue('E1', 'Area');
    $sheet->setCellValue('F1', 'Address Of Organization');
    $sheet->setCellValue('G1', 'Location');
    $sheet->setCellValue('H1', 'Hospital Main Number');
    $sheet->setCellValue('I1', 'Email Address');
    $sheet->setCellValue('J1', 'Direct Call for Appointment Number');
    $sheet->setCellValue('K1', 'Hospital Profile');
    $sheet->setCellValue('L1', 'Hospital Profile (ar)');
    $sheet->setCellValue('M1', 'Status');
    $sheet->setCellValue('N1', 'Registration Date');
    $sheet->setCellValue('O1', 'Departments');

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    $columnWidths = [
        'A' => 5,
        'B' => 30,
        'C' => 20,
        'D' => 30,
        'E' => 20,
        'F' => 40,
        'G' => 30,
        'H' => 20,
        'I' => 30,
        'J' => 30,
        'K' => 40,
        'L' => 40,
        'M' => 15,
        'N' => 15,
        'O' => 50,
    ];

    foreach ($columnWidths as $column => $width) {
        $sheet->getColumnDimension($column)->setWidth($width);
    }

    /*
    |--------------------------------------------------------------------------
    | Header Style
    |--------------------------------------------------------------------------
    */

    $headerStyle = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E6B8B7'],
        ],
        'font' => [
            'bold' => true,
            'color' => ['rgb' => '000000'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ];

    $sheet->getStyle('A1:O1')->applyFromArray($headerStyle);

    /*
    |--------------------------------------------------------------------------
    | Data Rows
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($hospitals as $key => $hospital) {

        $departments = DepartmentHospital::where('department_hospital.hospital_id', $hospital->id)
            ->leftJoin('departments', 'departments.id', '=', 'department_hospital.department_id')
            ->select('departments.title')
            ->get();

        $hospital_departments = null;
        if ($departments->count()) {
            $hospital_departments = implode(', ', $departments->pluck('title')->toArray());
        }

        $sheet->setCellValue('A' . $row, ($key + 1));
        $sheet->setCellValue('B' . $row, $hospital->name_en);
        $sheet->setCellValue('C' . $row, $hospital->country->name ?? null);
        $sheet->setCellValue('D' . $row, $hospital->emirate->name_en ?? null);
        $sheet->setCellValue('E' . $row, $hospital->area->name_en ?? null);
        $sheet->setCellValue('F' . $row, $hospital->address);
        $sheet->setCellValue('G' . $row, $hospital->location[0]->location ?? null);
        $sheet->setCellValue('H' . $row,
            ($hospital->user->dial_code ? '+(' . $hospital->user->dial_code . ') ' : '') .
            $hospital->user->phone
        );
        $sheet->setCellValue('I' . $row, $hospital->user->email);
        $sheet->setCellValue('J' . $row,
            ($hospital->appointment_dial_code ? '+(' . $hospital->appointment_dial_code . ') ' : '') .
            $hospital->appointment_phone
        );
        $sheet->setCellValue('K' . $row, strip_tags($hospital->profile_description));
        $sheet->setCellValue('L' . $row, strip_tags($hospital->profile_description_ar));
        $sheet->setCellValue('M' . $row, $hospital->user->active == 1 ? 'Active' : 'Inactive');

        $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $hospital->created_at);
        $sheet->setCellValue('N' . $row, $createdAt->format('d-m-Y'));

        $sheet->setCellValue('O' . $row, $hospital_departments);

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Wrap & Vertical Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:O' . ($row - 1))
        ->getAlignment()
        ->setWrapText(true);

    $sheet->getStyle('A1:O' . ($row - 1))
        ->getAlignment()
        ->setVertical(Alignment::VERTICAL_CENTER);

    /*
    |--------------------------------------------------------------------------
    | Save File
    |--------------------------------------------------------------------------
    */

    $writer = new Xlsx($spreadsheet);
    $fileName = 'hospitals.xlsx';
    $writer->save($fileName);

    return $fileName;
}
}
