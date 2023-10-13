<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeleteSingleCatalogImageAction
{
    /**
     * Handle action
     */
    public static function handle_action(string $position , CatalogMotor $catalog) : CatalogMotor|Exception
    {
        try {
            Storage::delete($catalog->{$position});
            $catalog->update([$position => null]);

            return $catalog;
        } catch (Exception $e) {
            return $e;
        }
    }
}