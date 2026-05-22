<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DoctorImport implements ToModel, WithHeadingRow
{
    private $validRecords = [];
    private $unid;
    public function model(array $row)
    {
        //printr($row); exit;
        // Skip empty rows
        if (empty($row['first_name']) && empty($row['email']) && empty($row['password']) && empty($row['qualification']) && empty($row['speciality']) && empty($row['special_intrests']) ) {
            return null;
        }
        if(!empty($row['first_name'])){
            $this->unid = time().uniqid();
        
        
            //printr($row); 
            $hospital_id = $row['hospital_name']??0;

            $cat = explode(">>",$row['department']);
            $department_id = $cat[1]??0;

            $country_of_origin = explode(">>",$row['country_of_origin']);
            $country_of_origin_id = $country_of_origin[1]??0;

            $this->validRecords[$this->unid] = [
                'hospital_id'=>$hospital_id,
                'department' => $department_id,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'qualification'=> [],
                'speciality'=>[],
                'special_intrests'=>[],
                'year_of_experience' => $row['year_of_experience'],
                'country_of_origin' => $country_of_origin_id,
                'language_spoken'=>[],
                'gender' => $row['gender'],
                'clinic_dialcode' => $row['clinic_dial_code']??'',
                'clinic_number' => $row['clinic_direct_number_to_book_an_appointment']??'',
                'email' => $row['email'],
                'password' => $row['password'],
                
                'profle' => $row['doctor_profile'],
                'direct_dial_code' => $row['doctor_dial_code'],
                'direct_contact_number_for_appoitment' => $row['doctor_direct_number_to_book_an_appointment'],
                'photo_file_name' => $row['doctor_image_file_name']
            ];
            if($row['qualification'] != ''){
                $dp = explode(">>",$row['qualification']);
                $qualification_id = $dp[1]??0;
                $this->validRecords[$this->unid]['qualification'][] = $qualification_id;
            }
            if($row['speciality'] != ''){
                $dp = explode(">>",$row['speciality']);
                $speciality_id = $dp[1]??0;
                $this->validRecords[$this->unid]['speciality'][] = $speciality_id;
            }
            if($row['special_intrests'] != ''){
                $dp = explode(">>",$row['special_intrests']);
                $special_intrests_id = $dp[1]??0;
                $this->validRecords[$this->unid]['special_intrests'][] = $special_intrests_id;
            }
            if($row['language_spoken'] != ''){
                $dp = explode(">>",$row['language_spoken']);
                $language_spoken_id = $dp[1]??0;
                $this->validRecords[$this->unid]['language_spoken'][] = $language_spoken_id;
            }
            
        }else{
                if($row['qualification'] != ''){
                    $dp = explode(">>",$row['qualification']);
                    $qualification_id = $dp[1]??0;
                    $this->validRecords[$this->unid]['qualification'][] = $qualification_id;
                }
                if($row['speciality'] != ''){
                    $dp = explode(">>",$row['speciality']);
                    $speciality_id = $dp[1]??0;
                    $this->validRecords[$this->unid]['speciality'][] = $speciality_id;
                }
                if($row['special_intrests'] != ''){
                    $dp = explode(">>",$row['special_intrests']);
                    $special_intrests_id = $dp[1]??0;
                    $this->validRecords[$this->unid]['special_intrests'][] = $special_intrests_id;
                }
                if($row['language_spoken'] != ''){
                    $dp = explode(">>",$row['language_spoken']);
                    $language_spoken_id = $dp[1]??0;
                    $this->validRecords[$this->unid]['language_spoken'][] = $language_spoken_id;
                }
            
        }
       

        return null;
        
    }
    public function collection(Collection $collection)
    {
        //
    }
    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        return date("Y-m-d",strtotime($value));
    }
    // Method to get valid records
    public function getValidRecords()
    {
        return $this->validRecords;
    }
}
