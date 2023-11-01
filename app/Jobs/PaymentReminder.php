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

class PaymentReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasCustomResponse;

    private $message, $data = [];
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
        $unPaidBookings = Booking::select('total_unit' , 'full_name' , 'email' , 'whatsapp_number' , 'motor_name' , 'amount' , 'package' , 'expired_payment')
                                ->where('is_confirmed' , false)
                                ->where('is_notified' , false)
                                ->where('expired_payment' , '>' , now())
                                ->where('expired_payment' , '<=' , now()->addHours(3));
       
        $bookings = $unPaidBookings->get();

        $unPaidBookings->update(['is_notified' => true]);

        foreach($bookings as $booking){
            $this->set_message($booking);
            $this->email_payload($booking);

            $this->whatsapp_notification($booking->whatsapp_number , $this->message);
        
            $this->email_notification($booking->email , $this->data , 'payment');

        }

    }

    /**
     * Set message
     */
    private function set_message(Booking $booking) : void
    {
        $this->message = "Halo *" . $booking->full_name . "*,\n\n" .
                "Thank you for placing your order with *Lavista Rental Bike*. Please visit our store to complete the payment , pick up your bike promptly and start your advanture with us ❤️.\n\n" .
                "Motor Type: *" . $booking->motor_name . ' (' . $booking->total_unit . ')' . "*\n" .
                "Package: *" . $booking->package . "*\n" .
                "Amount: *Rp. " . number_format($booking->amount , 0 , '.' , '.') . "*\n" .
                "Payment due by: *" . Carbon::parse($booking->expired_payment)->format('Y M d H:i') . "*\n" .
                "We appreciate your choice of *Lavista Rental Bike*!\n\n" .
                "Best regards,\n" .
                "Lavista Rental Bike Service Team";

        
    }


    /**
     * Payload for email notification
     */
    private function email_payload(Booking $booking) : void
    {
        $this->data = [
            'full_name' => $booking->full_name,
            'motor_name' => $booking->motor_name,
            'package' => $booking->package,
            'total_unit' => $booking->total_unit,
            'amount' => number_format($booking->amount , 0 , '.' , '.'),
            'expired_payment' => Carbon::parse($booking->expired_payment)->format('Y M d H:i')
            
        ];
    }
}
