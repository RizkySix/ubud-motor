<?php   

namespace App\Action\Authentication;

use Exception;

class LogoutAction
{
    /**
     * Handle action
     */
    public static function handle_action() : bool|Exception
    {
        try {
            $admin = auth()->user();
            $admin->currentAccessToken()->delete();

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}