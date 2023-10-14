<?php

namespace App\Action\Catalog;

use App\Models\CatalogPrice;
use Exception;

class DeleteCatalogPrice
{
    /**
     * Handle action
     */
    public static function handle_action(CatalogPrice $price) : bool|Exception
    {
        try {
            $price->delete();

            return true;
        } catch (Exception $e) {
           return $e;
        }
    }
}