<?php

namespace App\Action\Authentication;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : Customer|Exception
    {
        try {
            
            $data['password'] = Hash::make($data['password']);
            $customer = Customer::create($data);

            //buat token sanctum
            $token = $customer->createToken('ubud-motor' , ['customer'])->plainTextToken;

            $customer['token'] = $token;
            return $customer;

        } catch (Exception $e) {
            return $e;
        }
    }
}