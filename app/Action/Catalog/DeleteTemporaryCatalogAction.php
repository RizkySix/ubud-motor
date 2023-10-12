<?php

namespace App\Action\Catalog;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteTemporaryCatalogAction
{
    /**
     * Handle Action
     */
    public static function handle_action(string $tempPath) : bool|Exception
    {
       try {
            $getTempPath = DB::table('temp_catalogs')->where('temp_path' , $tempPath);
            if($getTempPath->first()){
                Storage::delete($tempPath);
                $getTempPath->delete();

                return true;
            }

            return false;
       } catch (Exception $e) {
            return $e;
       }
    }
}