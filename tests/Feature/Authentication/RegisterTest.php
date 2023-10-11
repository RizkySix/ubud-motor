<?php

namespace Tests\Feature\Authenticaton;

use App\Mail\OtpMailer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Trait\HasTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, HasTest;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@gmail.com'
        ]);

    } 

    /**
     *@group authentication-test
     */
    public function test_validation_register_invalid(): void
    {
        $this->assertDatabaseCount('users' , 1);

       $payload =  $this->set_payload_admin($this->user->email);
       $response = $this->postJson(RouteServiceProvider::DOMAIN . '/admin/register' , $payload);
       $response->assertStatus(400);
       $response->assertJsonStructure([
        'validation_errors' => [
            'email',
        ]
       ]);

        $this->assertDatabaseMissing('users' , [
            'full_name' => $payload['full_name'],
        ]);

        //pastikan jumlah users saat ini masih satu
        $this->assertDatabaseCount('users' , 1);

    }

     /**
     *@group authentication-test
     */
    public function test_token_should_return_succed_registration_and_email_verified_at_must_be_null() : void
    {
        $payload =  $this->set_payload_admin();
        $response = $this->postJson(RouteServiceProvider::DOMAIN . '/admin/register' , $payload);
        $response->assertStatus(201);

        $this->assertDatabaseCount('users' , 2);
        $this->assertDatabaseHas('users' , [
            'full_name' => $payload['full_name'],
            'email' => $payload['email'],
            'email_verified_at' => null
        ]);

        $response->assertJsonStructure([
            "status",
            "data" => [
                "full_name",
                "email",
                "phone_number",
                "email_verified_at",
                "token"
            ]
        ]);
       
        $this->assertDatabaseCount('personal_access_tokens' , 1);
    }

     /**
     *@group authentication-test
     */
    public function test_email_otp_should_queued() : void
    {
        Mail::fake();

        Mail::assertNothingQueued();
        $payload =  $this->set_payload_admin();
        $response = $this->postJson(RouteServiceProvider::DOMAIN . '/admin/register' , $payload);
        $response->assertStatus(201);

       
        Mail::assertQueued(OtpMailer::class , function($email) use($payload) {
            return $email->hasTo($payload['email']);
        });
        
        Mail::assertNothingSent();
    }

}
