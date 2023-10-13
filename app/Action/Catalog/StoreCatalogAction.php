<?php

namespace App\Action\Catalog;

use App\Models\CatalogMotor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreCatalogAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : CatalogMotor|Exception
    {
        try {
            $newCatalog = null;
            DB::transaction(function () use ($data , &$newCatalog) {
                $payloadCatalog = [];
            
                //Buatkan array newpath
                $newPath = [];
                foreach ($data['path_catalog'] as $position => $path) {
                    $newPath[$path] = $position;
                }
            
                //Reorder data
                $data['reorder'] ? asort($newPath) : null;
            
                //Pindahkan path catalog dari temp ke newpath
                $getTempCatalog = DB::table('temp_catalogs')->whereIn('temp_path', array_keys($newPath));
            
                $tempCatalog = $getTempCatalog->orderBy('position', 'ASC')->get();
            
                $count = 1;
                foreach ($tempCatalog as $temp) {
                    $fileName = explode('/', $temp->temp_path);
                    $fileName = end($fileName);
                    $oriPath = 'Catalog/' . $data['motor_name'] . '/' . $fileName;
            
                    Storage::move($temp->temp_path, $oriPath);
            
                    if ($count === 1) {
                        $payloadCatalog['first_catalog'] = $oriPath;
                    } elseif ($count === 2) {
                        $payloadCatalog['second_catalog'] = $oriPath;
                    } elseif ($count === 3) {
                        $payloadCatalog['third_catalog'] = $oriPath;
                    }
            
                    $count++;
                }
            
                $getTempCatalog->delete();
                $payloadCatalog['motor_name'] = $data['motor_name'];
            
                $newCatalog = CatalogMotor::create($payloadCatalog);
            
                //Tambahkan package pricingnya
                $newCatalog->price()->create([
                    'package' => $data['package'],
                    'duration' => $data['duration'],
                    'price' => $data['price'],
                ]);
            });
            
            return $newCatalog;

        } catch (Exception $e) {
            return $e;
        }
    }
}