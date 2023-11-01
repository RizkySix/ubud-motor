<?php

namespace App\Http\Controllers\Authentication;

use App\Action\Authentication\ConfirmResetPasswordAction;
use App\Action\Authentication\CustomerLoginAction;
use App\Action\Authentication\CustomerLogoutAction;
use App\Action\Authentication\CustomerRegisterAction;
use App\Action\Authentication\LoginAction;
use App\Action\Authentication\LogoutAction;
use App\Action\Authentication\OtpSendAction;
use App\Action\Authentication\RegisterAction;
use App\Action\Authentication\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AdminLoginRequest;
use App\Http\Requests\Authentication\CustomerLoginRequest;
use App\Http\Requests\Authentication\CustomerRegisterRequest;
use App\Http\Requests\Authentication\OtpRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Mail\OtpMailer;
use App\Models\Customer;
use App\Models\User;
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

    /**
     * Handle forget password
     */
    public function reset_password(ResetPasswordRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = ResetPasswordAction::handle_action($validatedData['email']);

        return $this->custom_response($response , 'Reset password mail sent' , 200 , 422 , 'Fail send reset password mail');
    }

    /**
     * Handle send verification reset password
     */
    public function verify_reset_password(string $email) : JsonResponse
    {
        $response = ConfirmResetPasswordAction::handle_action($email);

        return $this->custom_response($response , 'Password reset success' , 200 , 422 , 'Fail reset password');
    }


    /**
     * Login admin
     */
    public function login(AdminLoginRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = LoginAction::handle_action($validatedData);

        return $this->custom_response($response , UserResource::make($response) , 200 , 404, 'Invalid Admin credentials');
    }


    /**
     * Logout Admin
     */
    public function logout() : JsonResponse
    {
        $response = LogoutAction::handle_action();

        return $this->custom_response($response , 'Admin logout success' , 200 , 422 ,'Admin failed logout');
    }



    //method for customers just simple authentication
    /**
     * Handle register customer
     */
    public function register_customer(CustomerRegisterRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = CustomerRegisterAction::handle_action($validatedData);

        return $this->custom_response($response , CustomerResource::make($response), 201 , 422 , 'Failed to register folks');
    }

    /**
     * Handle login customer
     */
    public function login_customer(CustomerLoginRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = CustomerLoginAction::handle_action($validatedData);

        return $this->custom_response($response , CustomerResource::make($response) , 200 , 404, 'Invalid Customer credentials');
    }

    /**
     * Handle logout customer
     */
    public function logout_customer() : JsonResponse
    {
        $response = CustomerLogoutAction::handle_action();

        return $this->custom_response($response , 'Customer logout success' , 200 , 422 ,'Customer failed logout');
    }


    /**
     * Handle get data user
     */
    public function get_data_user() : JsonResponse
    {
        $data = null;
        $user = auth()->user();

        if($user instanceof Customer){
            $data = CustomerResource::make($user);
        }elseif($user instanceof User){
            $data = UserResource::make($user);
        }

        return $this->custom_response(true , $data , 200 , 422 ,'Invalid user type');
    }

}
