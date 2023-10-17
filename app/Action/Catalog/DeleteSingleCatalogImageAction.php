<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use App\Trait\HasCustomResponse;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeleteSingleCatalogImageAction
{
    use HasCustomResponse;
    /**
     * Handle action
     */
    public static function handle_action(string $position , CatalogMotor $catalog) : CatalogMotor|Exception
    {
        try {
            Storage::delete(HasCustomResponse::get_base_path($catalog->{$position}));
            $catalog->update([$position => null]);

            return $catalog;
        } catch (Exception $e) {
            return $e;
        }
    }
}