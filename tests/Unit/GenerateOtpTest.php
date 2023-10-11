<?php

namespace Tests\Unit;

use App\Models\User;
use App\Trait\HasOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateOtpTest extends TestCase
{
    use RefreshDatabase , HasOtp;

    protected function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * @group authentication-test
     */
    public function test_otp_code_must_be_six_digits(): void
    {
        $otpCode = HasOtp::generate_otp(10);
        $this->assertEquals(6 , strlen(strval($otpCode)));
    }
    
}
