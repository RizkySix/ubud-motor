<?php

namespace App\Trait;

use App\Models\CatalogPrice;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

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


}