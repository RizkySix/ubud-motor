<?php

namespace App\Jobs;

use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ChargeMotor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bookingDetails = BookingDetail::with(['booking:is_active,uuid'])
                                    ->select('id' , 'charge' , 'booking_uuid' , 'return_date')
                                    ->where('is_done' , false)
                                    ->where('today_charge' , '<' , now())
                                    ->where('return_date' , '<' , now()->addHours(3))
                                    ->get();

    
        foreach($bookingDetails as $detail){
            $passed = Carbon::parse($detail->return_date)->diffInDays(now());
               $passed = $passed == 0 ? 1 : $passed;

               $totalCharge = $detail->charge * $passed;
    
               $detail->update([
                    'total_charge' => $totalCharge,
                    'passed_days' => $passed,
                    'today_charge' => now()->addDays(1)
               ]);
        }

    }
}
