<?php

namespace App\Http\Controllers\Authentication;

use App\Action\Authentication\OtpSendAction;
use App\Action\Authentication\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\OtpRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\OtpMailer;
use App\Trait\HasCustomResponse;
use App\Trait\HasOtp;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    use HasOtp , HasCustomResponse;
    /**
     * Handle register admin
     */
    public function register(RegisterRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = RegisterAction::handle_action($validatedData);
        
        return $this->custom_response($response , UserResource::make($response) , 201, 422, 'Register Failed');
    }


    /**
     * Handle resend otp code
     */
    public function resend_otp() : JsonResponse
    {
        try {
            $user = auth()->user();
            //generate Otp
            $generateOtp = HasOtp::set_payload($user);

            //panggil mailer
            Mail::to($user->email)->send(new OtpMailer($generateOtp));

            return response()->json([
                'status' => true,
                'data' => 'New otp code sent'
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ] , 500);
        }   
    }

     /**
     * Handle resend otp code
     */
    public function send_otp(OtpRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = OtpSendAction::handle_action($validatedData['otp_code']);

        return $this->custom_response($response , UserResource::make($response) , 200, 422 , 'Otp code not valid');

        
    }
}
