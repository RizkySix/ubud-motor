<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeleteCatalogAction
{
    /**
     * Handle Action
     */
    public static function handle_action(CatalogMotor $catalog) : bool|Exception
    {
        try {
            if($catalog->first_catalog){
                Storage::delete($catalog->first_catalog);
            }
            
            if($catalog->second_catalog){
                Storage::delete($catalog->second_catalog);
            }

            if($catalog->third_catalog){
                Storage::delete($catalog->third_catalog);
            }

            $catalog->price()->delete();
            $catalog->delete();

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}