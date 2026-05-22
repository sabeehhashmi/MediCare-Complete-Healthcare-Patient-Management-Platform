<?php

namespace App\Exports;

use App\Models\Hospital;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\Area;
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

        $area_list = Area::where(['active'=>1])->get();
        foreach($area_list as $ar){
            $this->areas[] = $ar->name_en."   >>".$ar->id;
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
            'Emirate',
            'Area'
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
            'A' => 30,
            'B' => 30,
            'C' => 20,
            'D' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
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

                
            },
        ];
    }
}
