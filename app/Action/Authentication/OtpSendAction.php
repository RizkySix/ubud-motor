<?php   

namespace App\Action\Authentication;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class OtpSendAction
{
    /**
     * Handle action
     */
    public static function handle_action(int $otpCode) : bool|User|Exception
    {
       try {
            $user = auth()->user();

            //compare otp yang dkirim
            $getOtpCode = DB::table('otp_codes')->where('otp_code' , $otpCode)
                                                ->where('expired_time' , '>' , now());

            if($getOtpCode->first()){
                $user->email_verified_at = now();
                $user->save();

                $getOtpCode->delete();
                
                return $user;
            }

            return false;
       } catch (Exception $e) {
            return $e;
       }
    }
}