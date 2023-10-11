<?php

namespace Tests\Feature\Authentication;

use App\Mail\OtpMailer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Trait\HasTest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase , HasTest;
    private $user , $otpCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->register();
        $this->user = User::first();
    }

    /**
     * @group authentication-test
     */
    public function test_admin_can_resend_otp_code_and_should_destroy_old_otp_code(): void
    {
        Mail::fake();

        Mail::assertNothingQueued();

        //pastikan code otp sudah ada saat ini
        $this->assertDatabaseCount('otp_codes' , 1);

        $this->otpCode = DB::table('otp_codes')->select('otp_code' , 'user_id')->first();
        $this->assertDatabaseHas('otp_codes' , [
           ...collect($this->otpCode)
        ]);    

        //coba hit endpoint resend otp
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN . '/otp/resend');
        $response->assertStatus(200);

        //pastikan jumlah otp_codes masih 1
        $this->assertDatabaseCount('otp_codes' , 1);

        //pastikan kode otp yang lama sdah terhapus
        $this->assertDatabaseMissing('otp_codes' , [
            ...collect($this->otpCode)
        ]);

        Mail::assertQueued(OtpMailer::class , function($email)  {
            return $email->hasTo($this->user->email);
        });
        
        Mail::assertNothingSent();
        Mail::assertQueuedCount(1);

    }

    /**
     * @group authentication-test
     */
    public function test_resend_otp_endpoint_hit_should_once_per_minute() : void
    {
        $this->test_admin_can_resend_otp_code_and_should_destroy_old_otp_code();
        
        //coba hit endpoint resend otp lagi
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN . '/otp/resend');
        $response->assertStatus(429);
    }

    /**
     * @group authentication-test
     */
    public function test_send_otp_with_valid_otp_code_should_success_and_throw_valid_response() : void
    {
        $this->assertDatabaseCount('otp_codes' , 1);

        $this->otpCode = DB::table('otp_codes')->select('otp_code')->first();

        //hit endpoint send
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN  . '/otp/send' , [
            ...collect($this->otpCode)
        ]); 
        $response->assertStatus(200);
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

        $response->assertJson([
            "status" => true,
            "data" => [
                "email_verified_at" => now(),
            ]
        ]);

        //pastikan otp code sudah terhapus
        $this->assertDatabaseEmpty('otp_codes');
    }


    /**
     * @group authentication-test
     */
    public function test_expired_otp_code_should_invalid() : void
    {
        //travel ke waktu 2 jam kedepan
        Carbon::setTestNow(now()->addHours(2));

        $this->assertDatabaseCount('otp_codes' , 1);
        $this->otpCode = DB::table('otp_codes')->select('otp_code')->first();

        //hit endpoint send
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN  . '/otp/send' , [
            ...collect($this->otpCode)
        ]); 
        $response->assertStatus(422);

        $this->user->refresh();

        //pastikan email verified at admin masih null
        $this->assertEquals(null , $this->user->email_verified_at);
    }

    /**
     * @group authentication-test
     */
    public function test_verified_email_admin_cant_access_resend_or_send_otp() : void
    {
        $this->test_send_otp_with_valid_otp_code_should_success_and_throw_valid_response();

        //coba hit endpoint resend otp
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN . '/otp/resend');
        $response->assertStatus(409);

        //coba htt endpoint send otp
        $response = $this->actingAs($this->user)->postJson(RouteServiceProvider::DOMAIN  . '/otp/send' , [
            ...collect($this->otpCode)
        ]); 
        $response->assertStatus(409);
    }


    /**
     * Testing register admin
     */
    private function register() : void
    {
        $response = $this->postJson(RouteServiceProvider::DOMAIN . '/admin/register' , $this->set_payload_admin());
    }
}
