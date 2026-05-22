<?php
// app/Http/Controllers/Admin/ActivityLogController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class ActivityLogController extends Controller
{
    public function index(Request $request)
{
    $logs = ActivityLog::with('user');

    // ✅ Role mapping
    $roles = [
        6 => 'Doctor',
        5 => 'Hospital',
        8 => 'Clinic',
        4 => 'Service Center',
        3 => 'Agent',
    ];

    $page_heading = "Activity Logs";

    if ($request->user_type) {
        $logs = $logs->where('user_type', $request->user_type);

        // ✅ Set dynamic heading
        if (isset($roles[$request->user_type])) {
            $page_heading = $roles[$request->user_type] . " Activity Logs";
        }
    }

    $logs = $logs->latest()->paginate(20);

    return view('admin.activity_logs.index', compact('logs', 'page_heading'));
}

public function exportLogs(Request $request)
{
    $query = ActivityLog::with('user');

    // ✅ Filter by user_type
    if ($request->user_type) {
        $query->where('user_type', $request->user_type);
    }

    $logs = $query->latest()->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ✅ Headers
    $sheet->setCellValue('A1', 'SL#');
    $sheet->setCellValue('B1', 'User');
    $sheet->setCellValue('C1', 'Role');
    $sheet->setCellValue('D1', 'Action');
    $sheet->setCellValue('E1', 'Description');
    $sheet->setCellValue('F1', 'Details');
    $sheet->setCellValue('G1', 'Date & Time');

    // ✅ Styling
    $sheet->getStyle('A1:G1')->applyFromArray([
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E6B8B7'],
        ],
        'font' => [
            'bold' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ]);

    // ✅ Column Widths
    foreach (range('A','G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ✅ Data
    $row = 2;

    foreach ($logs as $key => $log) {

        // Format meta
        $metaText = 'N/A';
        if ($log->meta) {
            $metaArr = [];
            foreach ($log->meta as $k => $v) {
                $metaArr[] = $k . ': ' . $v;
            }
            $metaText = implode(", ", $metaArr);
        }

        $sheet->setCellValue('A' . $row, $key + 1);
        $sheet->setCellValue('B' . $row, $log->user->name ?? 'N/A');
        $sheet->setCellValue('C' . $row, $log->user->role_name ?? 'N/A');
        $sheet->setCellValue('D' . $row, str_replace('_', ' ', $log->action));
        $sheet->setCellValue('E' . $row, $log->description);
        $sheet->setCellValue('F' . $row, $metaText);
        $sheet->setCellValue('G' . $row, $log->created_at->format('d-M-Y h:i A'));

        $row++;
    }

    // ✅ Wrap text
    $sheet->getStyle('A1:G' . ($row - 1))
        ->getAlignment()
        ->setWrapText(true);

    $fileName = 'activity_logs.xlsx';

    $writer = new Xlsx($spreadsheet);
    $writer->save($fileName);

    return response()->download($fileName)->deleteFileAfterSend(true);
}
}