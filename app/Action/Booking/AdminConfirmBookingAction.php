<?php

namespace App\Action\Booking;

use App\Mail\MailConfirmBooking;
use App\Models\Booking;
use Exception;
use Illuminate\Support\Facades\Mail;

class AdminConfirmBookingAction
{
    /**
     * Handle action
     */
    public static function handle_action(Booking $booking) : bool|Exception
    {
        try {
            $booking->update(['is_confirmed' => true]);
            
            Mail::to($booking->email)->send(new MailConfirmBooking($booking));
            return true;
        } catch (Exception $e) {
           return $e;
        }
    }
}