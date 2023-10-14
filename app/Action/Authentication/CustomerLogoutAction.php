<?php

namespace App\Action\Authentication;

use Exception;

class CustomerLogoutAction
{
    /**
     * Handle Action
     */
    public static function handle_action() : bool|Exception
    {
        try {
            $customer = auth()->user();
            $customer->currentAccessToken()->delete();

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}