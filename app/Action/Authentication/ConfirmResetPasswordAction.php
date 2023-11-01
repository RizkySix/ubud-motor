<?php

namespace App\Action\Authentication;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ConfirmResetPasswordAction
{
    /**
     * Handle action
     */
    public static function handle_action(string $email) : bool|Exception
    {
        try {
            $cache = Cache::get('reset-password-' . $email);
            
            if($cache){
                User::where('email' , $email)->update(['password' => Hash::make($cache)]);
                Cache::forget('reset-password-' . $email);
                return true;
            }

            return false;
        } catch (Exception $e) {
            return $e;
        }
    }
}