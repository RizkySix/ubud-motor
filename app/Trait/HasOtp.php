<?php

namespace App\Trait;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait HasOtp
{
    /**
     * Generate 6 digits OTP code
     */
    public static function generate_otp(int $userId) : int
    {
        $otpCode = mt_rand(123123 , 999149);
        
        //masukan kedalam database dan hapus dulu seluruh record untuk user yang sama
        DB::table('otp_codes')->where('user_id' , $userId)->delete();
        DB::table('otp_codes')->insert(['user_id' => $userId , 'otp_code' => $otpCode]);

        return $otpCode;
    }

    /**
     * Set payload for OTP mailer
     */
    public static function set_payload(User $user) : array
    {
        $otpCode = self::generate_otp($user->id);

        $payloadMailer = [
            'otpCode' => $otpCode,
            'full_name' => $user->full_name
        ];

        return $payloadMailer;
    }
}