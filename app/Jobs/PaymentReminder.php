<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Trait\HasCustomResponse;
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
                "Terimakasih telah membuat pesanan di *Motor Ubud*. Segera ke toko untuk melakukan pembayaran dan mengambil kendaraan anda.\n\n" .
                "Motor Type: *" . $booking->motor_name . ' (' . $booking->total_unit . ')' . "*\n" .
                "Package: *" . $booking->package . "*\n" .
                "Amount: *Rp. " . $booking->amount . "*\n" .
                "Expired payment on: *" . $booking->expired_payment . "*\n" .
                "Terima kasih telah memilih *Motor Ubud*!\n\n" .
                "Salam,\n" .
                "Tim Layanan Pelanggan Motor Ubud";

        
    }


    /**
     * Payload for email notification
     */
    private function email_payload(Booking $booking) : void
    {
        $this->data = [
            'message' => $this->message
        ];
    }
}
