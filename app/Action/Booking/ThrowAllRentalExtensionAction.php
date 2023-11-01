<?php

namespace App\Action\Booking;

use App\Models\RentalExtension;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowAllRentalExtensionAction
{
    /**
     * Handle Action
     */
    public static function handle_action(string $type) : bool|Collection|Exception
    {
        try {
            
            $rentalExtensions = false;

            switch ($type) {
                case 'confirmed':
                    $rentalExtensions = RentalExtension::with(['booking_detail'])
                                        ->where('is_confirmed' , true)
                                        ->latest()->get();
                    break;
                case 'today':
                    $rentalExtensions = RentalExtension::with(['booking_detail'])
                                        ->where('is_confirmed' , false)
                                        ->wheredate('created_at' , today())
                                        ->latest()->get();
                    break;
                case 'unconfirmed':
                    $rentalExtensions = RentalExtension::with(['booking_detail'])
                                        ->where('is_confirmed' , false)
                                        ->latest()->get();
                    break;
            }
            
            return $rentalExtensions;

        } catch (Exception $e) {
            return $e;
        }
    }
}