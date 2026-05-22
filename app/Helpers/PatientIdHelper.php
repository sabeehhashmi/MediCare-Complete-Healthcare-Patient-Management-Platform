<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PatientIdHelper
{
    /**
     * Generate a unique random Patient ID
     * Format: MED + 6-digit random number
     * Example: MED123456, MED876543, MED000001
     */
    public static function generatePatientId()
    {
        $prefix = 'MED';
        $maxAttempts = 10;
        
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Generate 6-digit random number (100000 to 999999)
            $randomNumber = mt_rand(100000, 999999);
            $patientId = $prefix . $randomNumber;
            
            // Check if this patient ID already exists
            $exists = User::where('role', USER_ROLE)
                ->where('deleted', 0)
                ->where('patient_id', $patientId)
                ->exists();
            
            if (!$exists) {
                return $patientId;
            }
        }
        
        // Fallback: Use timestamp last 6 digits if random fails
        $timestamp = time();
        $last6Digits = substr($timestamp, -6);
        return $prefix . $last6Digits;
    }
    
    public static function generatePatientIdByDate($createdAt)
    {
        // Use the same random logic
        return self::generatePatientId();
    }
}