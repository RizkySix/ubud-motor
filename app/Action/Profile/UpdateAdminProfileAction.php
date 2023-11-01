<?php

namespace App\Action\Profile;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UpdateAdminProfileAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : User|Exception
    {
        try {
            
            $user = auth()->user();
            if(isset($data['password'])){
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return $user;
            

        } catch (Exception $e ) {
            return $e;
        }
    }
}