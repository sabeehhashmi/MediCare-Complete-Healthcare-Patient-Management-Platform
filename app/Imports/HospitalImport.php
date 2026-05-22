<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HospitalImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $validRecords = [];
    private $unid;
    public function model(array $row)
    {
        
        
        // Skip empty rows
        if (empty($row['hospital_name']) && empty($row['email']) && empty($row['password']) && empty($row['latitude']) && empty($row['longitude']) ) {
            return null;
        }
        if(!empty($row['hospital_name'])){
            $this->unid = time().uniqid();
        
        
            //printr($row); 
            $cat = explode(">>",$row['country']);
            $country_id = $cat[1]??229;

            $emirate = explode(">>",$row['emirates']);
            $emirate_id = $emirate[1]??0;

            $area = explode(">>",$row['area']);
            $area_id = $area[1]??0;
            $this->validRecords[$this->unid] = [
                'hospital_name'=>$row['hospital_name'],
                'hospital_name_ar' => $row['hospital_name_ar'],
                'country' => $country_id,
                'emirate_id' => $emirate_id,
                'area_id' => $area_id,
                'address_of_organisation' => $row['address_of_organization'],

                'location' => $row['location'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'phone_dialcode' => $row['country_dial_code'],
                'phone_number' => $row['hospital_main_number'],
                'email' => $row['email_address'],
                'password' => $row['password'],
                'website' => $row['website'],
                'direct_call_dialcode' => $row['direct_call_dialcode'],

                'direct_call_phone_number' => $row['direct_call_phone_number'],
                'hospital_profile_en' => $row['hospital_profile'],
                'hospital_profile_ar' => $row['hospital_profile_ar'],
                'tradelicence_file_name' => $row['tradelicence_file_name'],
                'logo_file_name' => $row['upload_logo_allowed_dim_300px_x_300px_jpg_jpeg_png'],
                'hospital_images_multiple_images_name_coma_seperated' => $row['hospital_images_allowed_3_photos_with_dim_750px_x_750px'],
                'hospital_or_clinic'=>$row['hospital_or_clinic']??"Hospital",
                'department' => []
                // Add other fields here according to your database columns
            ];
            if($row['hospital_or_clinic'] == 'Hospital'){
                $dp = explode(">>",$row['department']);
                $department_id = $dp[1]??0;
                $this->validRecords[$this->unid]['department'][] = [
                    'department_id' => $department_id,
                    'manager'=>$row['manager_name'],
                    'dial_code'=>$row['manager_dail_code']??'',
                    'phone'=>$row['manager_phone']??'',
                    'email'=>$row['manger_email']??''
                ];
            }
        }else{
                $dp = explode(">>",$row['department']);
                $department_id = $dp[1]??0;
                if($department_id){
                    $this->validRecords[$this->unid]['department'][] = [
                        'department_id' => $department_id,
                        'manager'=>$row['manager_name'],
                        'dial_code'=>$row['manager_dail_code']??'',
                        'phone'=>$row['manager_phone']??'',
                        'email'=>$row['manger_email']??''
                    ];
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
