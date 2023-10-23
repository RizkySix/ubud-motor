<?php

namespace App\Action\Gallery;

use App\Http\Requests\Gallery\TempGalleryRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class TempGalleryImageAction
{
    /**
     * Handle action
     */
    public static function handle_action(TempGalleryRequest $request) : bool|string|Exception
    {
        try {
            
            if($request->file('temp_path')){
                $file = $request->file('temp_path');
                $tempPath = $file->store('Gallery/Temp');

                DB::table('temp_galleries')->insert(['temp_path' => $tempPath]);

                return $tempPath;
            }

            return false;

        } catch (Exception $e) {
            return $e;
        }

    }
}