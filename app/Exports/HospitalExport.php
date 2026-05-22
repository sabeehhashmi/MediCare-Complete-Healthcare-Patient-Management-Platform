<?php

namespace App\Exports;

use App\Models\Hospital;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\DepartmentModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Collection;

class HospitalExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithEvents, WithColumnWidths
{
    private $countries;
    private $emirates;
    private $areas;
    private $departments;
    private $is_blank;

    public function __construct($is_blank = 1)
    {
        $cat_list = CountryModel::where('active', 1)
            ->select(['name', 'id'])
            ->where(['id'=>229])
            ->get();
        foreach($cat_list as $k){
            $this->countries[] = $k->name."   >>".$k->id;
        }

        $emirate_list = Emirate::where('active', 1)
            ->select('country_id', 'name_en', 'id')
            ->get();
        
        foreach($emirate_list as $em){
            $this->emirates[] = $em->name_en."   >>".$em->id;
        }

        $area_list = Area::with(['emirate'])->where(['active'=>1])->orderBy('name_en','asc')->get();
        foreach($area_list as $ar){
            $this->areas[] = $ar->name_en." (".$ar->emirate->name_en.")  >>".$ar->id;
        }

        $department_list = DepartmentModel::where(['status'=>1])->orderBy('title','asc')->get();
        foreach($department_list as $em){
            $this->departments[] = $em->title."   >>".$em->id;
        }
        $this->is_blank = $is_blank;
    }

    public function collection()
    {
        if ($this->is_blank == 1) {
            return new Collection([]);
        }
        return Hospital::all();
    }

    public function headings(): array
    {
        return [
            'Hospital Name',
            'Hospital Name (ar)',
            'Country',
            'Emirates',
            'Area',
            'Address Of Organization',
            'Location',
            'Latitude',
            'Longitude',
            'Country Dial Code',
            'Hospital Main Number',
            'Email Address',
            'Password',
            'Website',
            'Direct Call Dialcode',
            'Direct Call Phone Number',
            'Hospital Profile',
            'Hospital Profile (ar)',
            'Tradelicence File name',
            'Upload Logo (Allowed Dim 300px X 300px) (jpg, jpeg, png)',
            'Hospital Images (Allowed 3 Photos with Dim 750px X 750px)',
            'Hospital or Clinic',
            'Department',
            'Manager Name',
            'Manager Dail Code',
            'Manager Phone',
            'Manger Email'
        ];
    }

    public function map($item): array
    {
        return [
            $item->name_en,
            $item->name_ar,
            '', // Assuming empty initially, will be filled by user
            ''  // Assuming empty initially, will be filled by user
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 40,
            'C' => 40,
            'D' => 40,
            'E' => 40,
            'F' => 40,
            'G' => 40,
            'H' => 40,
            'I' => 40,
            'J' => 40,
            'K' => 40,
            'L' => 40,
            'M' => 40,
            'N' => 40,
            'O' => 40,
            'P' => 40,
            'Q' => 40,
            'R' => 40,
            'S' => 40,
            'T' => 40,
            'U' => 40,

            'V' => 40,
            'W' => 40,
            'X' => 40,
            'Y' => 40,
            'Z' => 40,
            'AA' => 40,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_TEXT,
            'O' => NumberFormat::FORMAT_TEXT,
            'P' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
            'R' => NumberFormat::FORMAT_TEXT,
            'S' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $event->sheet->getParent();

                // Create hidden sheet for countries and states
                $hiddenSheet = $spreadsheet->createSheet();
                $hiddenSheet->setTitle('Country');

                $emiratehiddenSheet = $spreadsheet->createSheet();
                $emiratehiddenSheet->setTitle('Emirates');

                $areahiddenSheet = $spreadsheet->createSheet();
                $areahiddenSheet->setTitle('Areas');

                $departmenthiddenSheet = $spreadsheet->createSheet();
                $departmenthiddenSheet->setTitle('Departments');

                $row = 1;

                // Insert countries list into the hidden sheet
                foreach ($this->countries as $country) {
                    $hiddenSheet->setCellValue('A' . $row, $country);
                    $row++;
                }

                $row = 1;

                // Insert emirates list into the hidden sheet
                foreach ($this->emirates as $emirate) {
                    $emiratehiddenSheet->setCellValue('A' . $row, $emirate);
                    $row++;
                }

                $row = 1;

                // Insert emirates list into the hidden sheet
                foreach ($this->areas as $area) {
                    $areahiddenSheet->setCellValue('A' . $row, $area);
                    $row++;
                }

                $row = 1;

                // Insert department list into the hidden sheet
                foreach ($this->departments as $dept) {
                    $departmenthiddenSheet->setCellValue('A' . $row, $dept);
                    $row++;
                }

                $namedRange = 'CountryList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $hiddenSheet,
                        '$A$1:$A$' . count($this->countries)
                    )
                );

                // Apply validation to the entire column C
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'C' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1("=$namedRange");
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }

                $namedRange = 'EmirateList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $emiratehiddenSheet,
                        '$A$1:$A$' . count($this->emirates)
                    )
                );

                // Apply validation to the entire column D
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'D' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1("=$namedRange");
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }

                $namedRange = 'AreaList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $areahiddenSheet,
                        '$A$1:$A$' . count($this->areas)
                    )
                );

                // Apply validation to the entire column E
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'E' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1("=$namedRange");
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }

                $typeOptions = ['Hospital', 'Clinic'];

                // Define cell range for Status column
                $startRow = 2; // Assuming data starts from row 2
                $endRow = 1000; // Adjust as per your needs
                $statusColumn = 'V'; // Column for 'Status'

                // Apply dropdown validation for Status column
                for ($row = $startRow; $row <= $endRow; $row++) {
                    $cell = $statusColumn . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('Select a value from the list.');
                    $validation->setFormula1('"'.implode(',', $typeOptions).'"');
                    $sheet->getCell($cell)->setDataValidation($validation);
                }

                $namedRange = 'DepartmentList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $departmenthiddenSheet,
                        '$A$1:$A$' . count($this->departments)
                    )
                );

                // Apply validation to the entire column C
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'W' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1("=$namedRange");
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }
                
                
                // Hide column B
                $sheet->getColumnDimension('B')->setVisible(false);
                $sheet->getColumnDimension('O')->setVisible(false);
                $sheet->getColumnDimension('P')->setVisible(false);
                $sheet->getColumnDimension('R')->setVisible(false);
                $sheet->getColumnDimension('S')->setVisible(false);
                $sheet->getColumnDimension('Y')->setVisible(false);
                $sheet->getColumnDimension('Z')->setVisible(false);
                
            },
        ];
    }
}
