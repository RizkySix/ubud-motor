<?php

namespace App\Trait;

use App\Mail\ExtensionReminderMail;
use App\Mail\PaymentReminderMail;
use App\Models\CatalogPrice;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * All custom response made by me is here
 */
trait HasCustomResponse
{
    /**
     * Set response json
     */
    public function custom_response(mixed $response , mixed $succesResponse , int $success = 200 , int $failure = 422, string $failureMsg = 'Something Wrong') : JsonResponse
    {
        if($response instanceof Exception){
            return response()->json([
                'status' => false,
                'error' => $response->getMessage()
            ] , 500);
       }else{
            $status = !$response ? $failure : $success;
            return response()->json([
                'status' => $status === $success ? true : false,
                'data' => $status === $success ? $succesResponse : $failureMsg
            ] , $status);
       }
    }

    /**
     * Set response failed request validation
     */
    public function validation_error(Validator $validator) : HttpResponseException
    {
        throw new HttpResponseException(response([
            'validation_errors' => $validator->getMessageBag()
        ] , 400));
    }


    /**
     * Validation for daily booking
     */
    public function is_daily(string $durationSuffix) : bool
    {
        $hourFormat = ['jam' , 'Jam' , 'jams' , 'Jams' , 'hour' , 'hours' , 'Hour' , 'Hours'];

        if(array_search($durationSuffix , $hourFormat)){
            return true;
        }

        return false;
    }

    /**
     * Make intereval days for daily package
     */
    public static function daily_interval(string $rentalDate , string $returnDate) : int
    {
        return Carbon::parse($returnDate)->diffInDays($rentalDate);
    }

    /**
     * Calculate total booking amount
     * rentalDuraton -- can be 1 day or 1 month
     */
    public static function calculate_amount(float $price , int $rentalDuration , int $totalUnit) : string
    {
        $totalAmount = $price * $rentalDuration;
        $totalAmount = $totalAmount * $totalUnit;

        return CatalogPrice::amount($totalAmount);
    }

    /**
     * Base path image
     */
    public static function get_base_path(string $image) : string
    {
        return str_replace(asset('storage/') . '/' , '' , $image);
    }

    /**
     * Whatsapp notification sender
     */
    public function whatsapp_notification($phoneNumber , string $message) : void
    {
        Http::withHeaders(['Authorization' => env('FONTE_API_TOKEN' , '')])->post('https://api.fonnte.com/send', [
            'target' => $phoneNumber,
            'message' => $message,
            'delay' => '30-60',
            //'countryCode' => '13'
        ]);
    }

    /**
     * Email notification sender,
     * Type must be 'payment' or 'extension'
     */
    public function email_notification(string $email , array $data , string $type) : void
    {
        switch ($type) {
            case 'payment':
                Mail::to($email)->send(new PaymentReminderMail($data));
                break;
            case 'extension':
                Mail::to($email)->send(new ExtensionReminderMail($data));
                break;
        }
    }


}