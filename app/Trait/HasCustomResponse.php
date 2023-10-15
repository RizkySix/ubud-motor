<?php

namespace App\Trait;

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
}