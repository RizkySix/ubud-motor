<?php

namespace App\Action\Catalog;

use App\Http\Requests\Catalog\UpdateCatalogRequest;
use App\Models\CatalogMotor;
use Exception;
use Illuminate\Support\Facades\Storage;

class UpdateCatalogAction
{
    private static $file;
    /**
     * Handle action
     */
    public static function handle_action(UpdateCatalogRequest $request,  CatalogMotor $catalog) : CatalogMotor|Exception
    {
        try {
            $validatedData = $request->validated();

            if($request->file('first_catalog')){
                self::$file = $request->file('first_catalog')->store('Catalog/' . $validatedData['motor_name']);
                $validatedData['first_catalog'] = self::$file;
                Storage::delete($catalog->first_catalog);
            }
            
            if($request->file('second_catalog')){
                self::$file = $request->file('second_catalog')->store('Catalog/' . $validatedData['motor_name']);
                $validatedData['second_catalog'] = self::$file;
                Storage::delete($catalog->second_catalog);
            }

            if($request->file('third_catalog')){
                self::$file = $request->file('third_catalog')->store('Catalog/' . $validatedData['motor_name']);
                $validatedData['third_catalog'] = self::$file;
                Storage::delete($catalog->third_catalog);
            }

            $catalog->update($validatedData);

            return $catalog->load(['price']);
        } catch (Exception $e) {
            return $e;
        }
    }
}