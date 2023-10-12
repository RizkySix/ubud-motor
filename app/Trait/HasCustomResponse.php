<?php

namespace App\Trait;

use Exception;
use Illuminate\Http\JsonResponse;

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
}