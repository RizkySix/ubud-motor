<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowAllCatalogAction
{
    /**
     * Handle Action
     */
    public static function handle_action() : Collection|Exception
    {
        try {
            
            //get all catalog
            $getCatalogs = CatalogMotor::with(['price'])->select('id', 'motor_name' , 'first_catalog' , 'second_catalog' , 'third_catalog' , 'created_at' , 'charge')->latest()->get();

            return $getCatalogs;
        } catch (Exception $e) {
            return $e;
        }
    }
}