<?php

namespace App\Exports;

use App\Models\Hospital;
use App\Models\HospitalDepartmentModel;
use App\Models\Qualifications;
use App\Models\SpecialIntrests;
use App\Models\Specialty;
use App\Models\Languages;
use App\Models\CountryOfOrigin;
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

class DoctorExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithEvents, WithColumnWidths
{
    private $hospitals = [];
    private $departmentsByHospital = [];
    private $qualifications = [];
    private $special_intests = [];
    private $speciality = [];
    private $country_of_origins = [];
    private $languages = [];
    private $is_blank;

    public function __construct($is_blank = 1)
    {
        $hospital_list = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
            ->where(['users.deleted' => 0])
            ->select(['hospitals.id', 'hospitals.name_en'])
            ->orderBy('name_en', 'asc')
            ->get();

        foreach ($hospital_list as $hospital) {
            $this->hospitals[$hospital->id] = $hospital->name_en."  >>".$hospital->id;
            $this->departmentsByHospital[$hospital->id] = []; // Initialize empty array for each hospital
        }

        $department_list = HospitalDepartmentModel::join('departments', 'departments.id', '=', 'department_hospital.department_id')
            ->select(['hospital_id', 'departments.title','department_id'])
            ->orderBy('title', 'asc')
            ->get();

        foreach ($department_list as $dp) {
            $this->departmentsByHospital[$dp->hospital_id][] = $dp->title."  >>".$dp->department_id;
        }

        $qualification_list = Qualifications::where(['status'=>1])->select(['title','id'])->orderBy('title','asc')->get();
        foreach($qualification_list as $q){
            $this->qualifications[] = $q->title."  >>".$q->id;
        }

        $speciality_list = Specialty::where(['active'=>1])->select(['id','name_en'])->orderBy('name_en','asc')->get();
        foreach($speciality_list as $sp){
            $this->speciality[]=$sp->name_en."  >>".$sp->id;
        }

        $special_intrest_list = SpecialIntrests::where(['status'=>1])->select(['title','id'])->orderBy('title','asc')->get();
        foreach($special_intrest_list as $s){
            $this->special_intests[]=$s->title."  >>".$s->id;
        }

        $country_list = CountryOfOrigin::where(['status'=>1])->select(['id','name'])->orderBy('name','asc')->get();
        foreach($country_list as $c){
            $this->country_of_origins[] = $c->name."  >>".$c->id;
        }

        $language_list = Languages::where(['status'=>1])->select(['id','title'])->orderBy('title','asc')->get();
        foreach($language_list as $c){
            $this->languages[] = $c->title."  >>".$c->id;
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
            'Department',
            'First Name',
            'Last Name',
            'Qualification',
            'Speciality',
            'Special Intrests',
            'Year of Experience',
            'Country of Origin',
            'Language Spoken',
            'Gender',
            'Clinic DialCode',
            'Clinic Number',
            'Email',
            'Password',
            'DHA License No',
            'MOH License No',
            'DOH License No',
            'DHCC License No',
            'Profle',
            'Direct Contact Number Dialcode',
            'Direct Contact Number For Appoitment',
            'Photo File Name'
        ];
    }

    public function map($item): array
    {
        return [
            '', // Hospital dropdown
            ''  // Department dropdown
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
            'Y' => 40
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $event->sheet->getParent();

                // Create hidden sheet for data validation
                $hiddenSheet = $spreadsheet->createSheet();
                $hiddenSheet->setTitle('ValidationLists');

                $qualificationhiddenSheet = $spreadsheet->createSheet();
                $qualificationhiddenSheet->setTitle('Qualifications');

                $specialityhiddenSheet = $spreadsheet->createSheet();
                $specialityhiddenSheet->setTitle('Speciality');

                $specialIntresthiddenSheet = $spreadsheet->createSheet();
                $specialIntresthiddenSheet->setTitle('SpecialIntrests');

                $countryoforiginthiddenSheet = $spreadsheet->createSheet();
                $countryoforiginthiddenSheet->setTitle('CountryOfOrigin');

                $languagehiddenSheet = $spreadsheet->createSheet();
                $languagehiddenSheet->setTitle('Languages');

                // Insert hospitals and departments into the hidden sheet
                $row = 1;
                foreach ($this->hospitals as $hospitalId => $hospitalName) {
                    $hiddenSheet->setCellValue('A' . $row, $hospitalName);
                    $departments = $this->departmentsByHospital[$hospitalId] ?? [];
                    $col = 1;
                    foreach ($departments as $department) {
                        $hiddenSheet->setCellValueByColumnAndRow($col + 1, $row, $department);
                        $col++;
                    }
                    $row++;
                }

                // Define named ranges for each hospital's departments
                $row = 1;
                foreach ($this->hospitals as $hospitalId => $hospitalName) {
                    if (!empty($this->departmentsByHospital[$hospitalId])) {
                        $departmentRange = 'ValidationLists!$B$' . $row . ':$' . chr(ord('B') + count($this->departmentsByHospital[$hospitalId]) - 1) . '$' . $row;
                        $spreadsheet->addNamedRange(
                            new \PhpOffice\PhpSpreadsheet\NamedRange(
                                str_replace(' ', '_', $hospitalName),
                                $hiddenSheet,
                                $departmentRange
                            )
                        );
                    }
                    $row++;
                }

                // Define named range for hospitals
                $hospitalRange = 'ValidationLists!$A$1:$A$' . count($this->hospitals);
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        'Hospitals',
                        $hiddenSheet,
                        $hospitalRange
                    )
                );

                // Apply data validation to hospital column (A)
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'A' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1('=Hospitals');
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }

                // Apply data validation to department column (B)
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'B' . $row;
                    $validation = $sheet->getCell($cell)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid input');
                    $validation->setError('This value is not in the list.');
                    $validation->setFormula1('=INDIRECT(SUBSTITUTE(A' . $row . ', " ", "_"))');
                    $sheet->getCell($cell)->setDataValidation(clone $validation);
                }

                $row = 1;

                // Apply data validation to department column (E)
                foreach ($this->qualifications as $q) {
                    $qualificationhiddenSheet->setCellValue('A' . $row, $q);
                    $row++;
                }

                $namedRange = 'QualificationList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $qualificationhiddenSheet,
                        '$A$1:$A$' . count($this->qualifications)
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

                $row = 1;

                // Apply data validation to department column (F)
                foreach ($this->speciality as $q) {
                    $specialityhiddenSheet->setCellValue('A' . $row, $q);
                    $row++;
                }

                $namedRange = 'SpecialityList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $specialityhiddenSheet,
                        '$A$1:$A$' . count($this->speciality)
                    )
                );

                // Apply validation to the entire column F
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'F' . $row;
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

                $row = 1;

                // Apply data validation to department column (G)
                foreach ($this->special_intests as $q) {
                    $specialIntresthiddenSheet->setCellValue('A' . $row, $q);
                    $row++;
                }

                $namedRange = 'SpecislIntrestList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $specialIntresthiddenSheet,
                        '$A$1:$A$' . count($this->special_intests)
                    )
                );

                // Apply validation to the entire column G
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'G' . $row;
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


                $row = 1;

                // Apply data validation to department column (I)
                foreach ($this->country_of_origins as $q) {
                    $countryoforiginthiddenSheet->setCellValue('A' . $row, $q);
                    $row++;
                }

                $namedRange = 'CountryOfOriginList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $countryoforiginthiddenSheet,
                        '$A$1:$A$' . count($this->country_of_origins)
                    )
                );

                // Apply validation to the entire column I
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'I' . $row;
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


                $row = 1;

                // Apply data validation to department column (J)
                foreach ($this->languages as $q) {
                    $languagehiddenSheet->setCellValue('A' . $row, $q);
                    $row++;
                }

                $namedRange = 'LanguageList';
                $spreadsheet = $event->sheet->getParent();
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        $namedRange,
                        $languagehiddenSheet,
                        '$A$1:$A$' . count($this->languages)
                    )
                );

                // Apply validation to the entire column J
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = 'J' . $row;
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

                $typeOptions = ['Male', 'Female','Others'];

                // Define cell range for Status column
                $startRow = 2; // Assuming data starts from row 2
                $endRow = 1000; // Adjust as per your needs
                $statusColumn = 'K'; // Column for 'Status'

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
            },
        ];
    }
}
