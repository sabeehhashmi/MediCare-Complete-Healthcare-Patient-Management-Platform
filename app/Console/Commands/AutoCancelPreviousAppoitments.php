<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;
class AutoCancelPreviousAppoitments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-cancel-previous-appoitments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = date('Y-m-d');

        // Get the previous date by subtracting one day
        $previousDate = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
        $list = DoctorPatientAppointment::whereDate('booking_date','<=',$previousDate)->whereNotIn('booking_status',[BOOKING_STATUS_COMPLETED,BOOKING_STATUS_CANCELLED])->get();
        //printr($list->toArray());
        if($list->count() > 0){
            
            foreach($list as $key){
                exec("php " . base_path() . "/artisan app:send-notitications-patient " . $key->id . " > /dev/null 2>&1 & ");
            }
        }
        DoctorPatientAppointment::whereDate('booking_date','<=',$previousDate)->whereNotIn('booking_status',[BOOKING_STATUS_COMPLETED,BOOKING_STATUS_CANCELLED])->update(['booking_status'=>BOOKING_STATUS_CANCELLED]);
        //
    }
}
