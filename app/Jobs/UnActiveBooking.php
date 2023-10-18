<?php

namespace App\Jobs;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnActiveBooking implements ShouldQueue
{
    public $tries = 3;
    public $backoff = 1;
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
        $bookings = Booking::select('uuid')->with(['booking_detail'])->where('is_active' , true)->get();
        $data = [];
        foreach($bookings as &$booking){
            
            $expiredBookingDetails = $booking->booking_detail->every(function ($detail) {
                return Carbon::parse($detail->return_date) < now();
            });
        
            if($expiredBookingDetails){
                $data[] = $booking->uuid;
            }
        }

        Booking::whereIn('uuid' , $data)->update(['is_active' => false]);


    }
}
