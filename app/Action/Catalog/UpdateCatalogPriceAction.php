<?php

namespace App\Action\Catalog;

use App\Models\CatalogPrice;
use Exception;

class UpdateCatalogPriceAction
{
    /**
     * Handle Action
     */
    public static function handle_action(array $data , CatalogPrice $price) : CatalogPrice|Exception
    {
        try {
            $price->update($data);

            return $price;
        } catch (Exception $e) {
            return $e;
        }
    }
}