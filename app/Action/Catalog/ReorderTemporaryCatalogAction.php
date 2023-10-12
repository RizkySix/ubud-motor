<?php

namespace App\Action\Catalog;

use Exception;
use Illuminate\Support\Facades\DB;

class ReorderTemporaryCatalogAction
{
    /**
     * Handle Action
     */
    public static function handle_action(array $tempPath) : bool|Exception
    {
       try {
        
            $newOrder = [];

            foreach($tempPath as $key => $path){
                $newOrder[] = [
                    'temp_path' => $path,
                    'position' => $key
                ];
            }

            //hapus dan remake temp catalog image
            DB::table('temp_catalogs')->whereIn('temp_path' , $tempPath)->delete();
            DB::table('temp_catalogs')->insert($newOrder);

            return true;
       } catch (Exception $e) {
            return $e;
       }
    }
}