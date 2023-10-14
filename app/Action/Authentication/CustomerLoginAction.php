<?php

namespace App\Action\Authentication;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;

class CustomerLoginAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : bool|Customer|Exception
    {
        try {
            
            if(Auth::guard('customers')->attempt(['username' => $data['username'] , 'password' => $data['password']])){
                $customer = auth('customers')->user();
                
                $token = $customer->createToken('ubud-motor' , ['customer'])->plainTextToken;
                
                $customer['token'] = $token;

                return $customer;
            }

            return false;
        } catch (Exception $e) {
            return $e;
        }
    }
}