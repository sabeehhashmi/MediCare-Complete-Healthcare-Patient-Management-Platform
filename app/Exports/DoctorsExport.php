<?php

namespace App\Exports;

use App\Models\DepartmentHospital;
use App\Models\Doctor;
use App\Models\Hospital;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;


class DoctorsExport
{
    public function export($filters)
    {
        $query = Doctor::query();

        $query->leftJoin('users', 'users.id', '=', 'doctors.user_id')
            ->leftJoin('country', 'country.id', '=', 'doctors.country_id')
            ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
            ->leftJoin('department_doctors', 'department_doctors.doctor_id', '=', 'doctors.id')
            ->leftJoin('doctor_specialities', 'doctor_specialities.doctor_id', '=', 'doctors.id')
            ->leftJoin('country_of_origins', 'country_of_origins.id', '=', 'doctors.country_id')
            ->leftJoin('doctor_intrests', 'doctor_intrests.doctor_id', '=', 'doctors.id')
            ->groupBy('doctors.id', 'users.id', 'country.id', 'hospitals.id');
        //   ->leftJoin('doctor_specialities', 'doctor_specialities.doctor_id', '=', 'doctors.id');
        //   if ($request->hospital_id) {
        //       $query->where('doctors.hospital_id', $request->hospital_id);
        //       $params['hospital_id'] = $request->hospital_id;
        //   }
        // dd($request->all());
        if (!empty($filters['hospital_id'])) {
            $query->where('doctors.hospital_id', $filters['hospital_id']);
        }

        if (!empty($filters['clinic_id'])) {
            $query->where('doctors.hospital_id', $filters['clinic_id']);
        }

        if (!empty($filters['booking_from'])) {
            $date = \Carbon\Carbon::parse($filters['booking_from'])->startOfDay()->format('Y-m-d');
            $query->where('doctors.created_at', '>=', $date);
        }

        if (!empty($filters['booking_to'])) {
            $fromDate = \Carbon\Carbon::parse($filters['booking_from']);
            $date = \Carbon\Carbon::parse($filters['booking_to']);
            if ($fromDate->isSameDay($date)) {
                $date = $date->addDay()->format('Y-m-d');
            }
            $query->where('doctors.created_at', '<=', $date);
        }

        if (!empty($filters['hospital_id'])) {
            $query->where('doctors.hospital_id', $filters['hospital_id']);
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_doctors.department_id', $filters['department_id']);
        }

        if (!empty($filters['speciality_id'])) {
            $query->where('doctor_specialities.speciality_id', $filters['speciality_id']);
        }

        if (!empty($filters['special_interest_id'])) {
            $query->where('doctor_intrests.special_intrest_id', $filters['special_interest_id']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('doctors.country_id', $filters['country_id']);
        }

        if (isset($filters['clinic_status']) && $filters['clinic_status'] != "") {
            $query->where('users.active', $filters['clinic_status']);
        }

       $query->select(['doctors.*', 'users.email', 'users.first_name', 'users.last_name',
            'users.dial_code', 'users.phone', 'country.name as country_name'])
            ->orderBy('doctors.id', 'desc');

        $doctors = $query->get()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'SL#');
        $sheet->setCellValue('B1', 'Doctor Name');
        $sheet->setCellValue('C1', 'Email ID');
        $sheet->setCellValue('D1', 'Phone Number');
        $sheet->setCellValue('E1', 'Qualifications');
        $sheet->setCellValue('F1', 'Hospital');
        $sheet->setCellValue('G1', 'Department');
        $sheet->setCellValue('H1', 'Specialities');
        $sheet->setCellValue('I1', 'Special Interest');
        $sheet->setCellValue('J1', 'Status');

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
        foreach ($doctors as $key => $doctor) {
            $sheet->setCellValue('A' . $row, ($key + 1));
            $sheet->setCellValue('B' . $row, $doctor->first_name . ' ' . $doctor->last_name);
            $sheet->setCellValue('C' . $row, $doctor->email);
            $sheet->setCellValue('D' . $row, $doctor->dial_code ? ('+(' . $doctor->dial_code . ')' . $doctor->phone) : $doctor->phone);
            $sheet->setCellValue('E' . $row, $doctor->qualifications ? $doctor->qualifications->pluck('title')->implode(', ') : null);
            $sheet->setCellValue('F' . $row, $doctor->hospital->name_en ?? null);
            $sheet->setCellValue('G' . $row, $doctor->departments ? $doctor->departments->pluck('title')->implode(', ') : null);
            $sheet->setCellValue('H' . $row, $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null);
            $sheet->setCellValue('I' . $row, $doctor->interests ? $doctor->interests->pluck('title')->implode(', ') : null);
            $sheet->setCellValue('J' . $row, $doctor->user->active ? 'Active' : 'Inactive');
            $row++;
        }

        $sheet->getStyle('A1:J' . ($row - 1))
            ->getAlignment()
            ->setWrapText(true);

        $sheet->getStyle('A1:J' . ($row - 1))
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'doctors.xlsx';
        $writer->save($fileName);

        return $fileName;
    }
}
