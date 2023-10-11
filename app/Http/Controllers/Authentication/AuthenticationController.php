<?php

namespace App\Http\Controllers\Authentication;

use App\Action\OtpSendAction;
use App\Action\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\OtpRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\OtpMailer;
use App\Trait\HasOtp;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    use HasOtp;
    /**
     * Handle register admin
     */
    public function register(RegisterRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = RegisterAction::handle_action($validatedData);
        
        if($response instanceof Exception){
            return response()->json([
                'status' => false,
                'error' => $response->getMessage()
            ], 500);
        }else{
            return response()->json([
                'status' => true,
                'data' => UserResource::make($response)
            ], 201);
        }
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

        if($response instanceof Exception){
            return response()->json([
                'status' => false,
                'error' => $response->getMessage()
            ] , 500);
        }else{
            $status = !$response ? 422 : 200;
            return response()->json([
                'status' => $status === 200 ? true : false,
                'data' => $status === 200 ? UserResource::make($response) : 'Otp code not valid'
            ] , $status);
        }
    }
}
