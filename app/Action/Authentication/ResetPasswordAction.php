<?php

namespace App\Action\Authentication;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ResetPasswordAction
{
    /**
     * Handle Action
     */
    public static function handle_action(string $email) :bool|Exception
    {
        try {

            $user = User::select('full_name')->where('email' , $email)->first();
            Cache::remember('reset-password-' . $email , now()->addHour(1) , function() {
                return null;
            });

            if($user){
                $generatePassword = 'admin-' . Str::random(5);
                Cache::put('reset-password-' . $email , $generatePassword);
                $data = [
                    'full_name' => $user->full_name,
                    'new_password' => $generatePassword,
                    'email' => base64_encode($email)
                ];

                Mail::to($email)->send(new ResetPasswordMail($data));

                return true;    
            }

            return false;
        } catch (Exception $e ) {
            return $e;
        }
    }
}