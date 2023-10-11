<?php

namespace App\Trait;

trait HasTest
{
    /**
     *payload data admin
     */
    private function set_payload_admin(string $email = 'rizkytest@gmail.com') : array
    {
        $data = [
            'full_name' => 'TestName',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone_number' => '087123123123'
        ];

        return $data;
    }
}