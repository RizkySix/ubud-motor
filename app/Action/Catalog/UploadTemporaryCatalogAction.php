<?php

namespace App\Action\Catalog;

use App\Http\Requests\Catalog\TemporaryUploadCatalogRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class UploadTemporaryCatalogAction
{
    /**
     * Handle Action
     */
    public static function handle_action(TemporaryUploadCatalogRequest $request) : bool|string|Exception
    {
        try {
            
            if($request->file('temp_path')){
                $file = $request->file('temp_path');
                $tempPath = $file->store('Catalog/Temp');

                DB::table('temp_catalogs')->insert(['temp_path' => $tempPath]);

                return $tempPath;
            }

            return false;
            
        } catch (Exception $e) {
            return $e;
        }
    }
}