<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtensionReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasCustomResponse;

    private $message, $data = [] , $returnDate;
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
        $bookings = Booking::with(['booking_detail'])->select('uuid', 'total_unit' , 'full_name' , 'email' , 'whatsapp_number' , 'motor_name' , 'amount' , 'package' , 'expired_payment')
                                ->where('is_confirmed' , true)
                                ->where('is_active' , true)
                               ->get();

         foreach($bookings as $booking){

                $bookingDetail = $booking->booking_detail->some(function ($detail) {
                    $this->returnDate = $detail->return_date;
                    $returnDate = Carbon::parse($detail->return_date);
                    return $returnDate > now() && $returnDate->diffInDays(now()) == 1;
                });
            
                if($bookingDetail){
                    $this->set_message($booking);
                    $this->email_payload($booking);

                    $this->whatsapp_notification($booking->whatsapp_number , $this->message);
                    
                    $this->email_notification($booking->email , $this->data , 'extension');
                }
         }                      
    }

    /**
     * Set message
     */
    private function set_message(Booking $booking) : void
    {
        $this->message = "Hello *" . $booking->full_name . "*,\n\n" .
                "Thank you for making an order at *Lavista Rental Bike*. Your rental time will end soon, come on, hurry up and extend it and pay at our store.\n\n" .
                "You can make renewal on website http://localhost:5173/ \n\n" .
                "Motor Type: *" . $booking->motor_name . ' (' . $booking->total_unit . ')' . "*\n" .
                "Package: *" . $booking->package . "*\n" .
                "Rental set to expire on: *" . Carbon::parse($this->returnDate)->format('Y M d H:i') . "*\n" .
                "Thank you for choosing *Lavista Rental Bike*!\n\n" .
                "Regards,\n" .
                "Lavista Rental Bike Customer Service Team";
        
    }


    /**
     * Payload for email notification
     */
    private function email_payload(Booking $booking) : void
    {
        $this->data = [
            'full_name' => $booking->full_name,
            'motor_name' => $booking->motor_name,
            'total_unit' => $booking->total_unit,
            'package' => $booking->package,
            'return_date' => Carbon::parse($this->returnDate)->format('Y M d H:i'),
        ];
    }
}
