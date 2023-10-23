<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use Exception;
use Illuminate\Support\Facades\DB;

class AddMoreCatalogPriceAction
{
    /**
     * Handle Action
     */
    public static function handle_action(array $data , CatalogMotor $catalog) : bool|Exception
    {
        try {
           
            foreach($data['price_lists'] as &$price){
                $price['catalog_motor_id'] = $catalog->id;
            }

            DB::table('catalog_prices')->insert($data['price_lists']);

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}