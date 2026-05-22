<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingType;

class BookingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'New Consultation'],
            ['name' => 'Follow-up Consultation'],
            ['name' => 'Second Opinion'],
            ['name' => 'Online Consultation'],
            ['name' => 'Emergency Consultation'],
        ];

        foreach ($types as $type) {
            BookingType::create($type);
        }
    }
}
