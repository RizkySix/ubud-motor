<?php

namespace App\Action\Authentication;

use App\Mail\OtpMailer;
use App\Models\User;
use App\Trait\HasOtp;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterAction
{
    use HasOtp;
    /**
     * Handle Action
     */
    public static function handle_action(array $data) : User|Exception
    {
        try {

            //daftarkan User
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            //buat token sanctum
            $token = $user->createToken('ubud-motor' , ['admin'])->plainTextToken;

            $payloadMailer = HasOtp::set_payload($user);

            //panggil Mailer
            Mail::to($user->email)->send(new OtpMailer($payloadMailer));

            $user['token'] = $token;
            return $user;

        } catch (Exception $e) {
            return $e;
        }
    }
}