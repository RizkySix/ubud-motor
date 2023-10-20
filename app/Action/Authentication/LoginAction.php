<?php

namespace App\Action\Authentication;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    /**
     * Handle Action
     */
    public static function handle_action(array $data) : bool|User|Exception
    {
        try {
            
            if(Auth::guard()->attempt(['email' => $data['email'] , 'password' => $data['password']])){
                $admin = auth()->user();
                
                $token = $admin->createToken('ubud-motor' , ['admin'])->plainTextToken;
                
                $admin['token'] = $token;

                return $admin;
            }

            return false;

        } catch (Exception $e) {
            return $e;
        }
    }
}