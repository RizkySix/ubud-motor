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
                $newPath = $data['path_catalog'];
            
                $movingPath = [];
                $count = 1;

                foreach ($newPath as $temp) {
                    $fileName = explode('/', $temp);
                    $fileName = end($fileName);
                    $oriPath = 'Catalog/' . $data['motor_name'] . '/' . $fileName;
            
                    $movingPath[$temp] = $oriPath;
                  
                    switch ($count) {
                        case 1:
                            $payloadCatalog['first_catalog'] = $oriPath;
                            break;
                        case 2:
                            $payloadCatalog['second_catalog'] = $oriPath;
                            break;
                        case 3:
                            $payloadCatalog['third_catalog'] = $oriPath;
                            break;
                    }
            
                    $count++;
                }
            
                //hapus gambar dari temp catalog
                DB::table('temp_catalogs')->whereIn('temp_path', $newPath)->delete();

                //masukan data catalog
                $payloadCatalog['motor_name'] = $data['motor_name'];
                $payloadCatalog['charge'] = $data['charge'];
            
                $newCatalog = CatalogMotor::create($payloadCatalog);
            
                //Tambahkan package pricingnya
                foreach($data['price_lists'] as &$price){
                    $price['catalog_motor_id'] = $newCatalog->id;
                }

                DB::table('catalog_prices')->insert($data['price_lists']);

                //pindahkan seluruh catalog dari temp ke oripath
                foreach($movingPath as $oldPath => $newPath){
                    Storage::move($oldPath, $newPath);
                }

                $newCatalog->load(['price']);
            });
            
            return $newCatalog;

        } catch (Exception $e) {
            return $e;
        }
    }


    


    //________________________________________________________________________________________________________________________

    /**
     * Code store catalog versi lama (tidak digunakan)
     */
    private function old_version(array $data) : CatalogMotor|Exception
    {
        try {
            $newCatalog = null;
            DB::transaction(function () use ($data , &$newCatalog) {
                $payloadCatalog = [];
            
                //Buatkan array newpath
                foreach ($data['path_catalog'] as $position => $path) {
                    $newPath[$position] = $path;
                }
            
                //Reorder data
                //$data['reorder'] ? asort($newPath) : null;
            
                $getTempCatalog = DB::table('temp_catalogs')->whereIn('temp_path', array_keys($newPath));
              
                $tempCatalog = $getTempCatalog->orderBy('position', 'ASC')->get();
                $movingPath = [];
                $count = 1;

                foreach ($tempCatalog as $temp) {
                    $fileName = explode('/', $temp->temp_path);
                    $fileName = end($fileName);
                    $oriPath = 'Catalog/' . $data['motor_name'] . '/' . $fileName;
            
                    $movingPath[$temp->temp_path] = $oriPath;
                  
                    switch ($count) {
                        case 1:
                            $payloadCatalog['first_catalog'] = $oriPath;
                            break;
                        case 2:
                            $payloadCatalog['second_catalog'] = $oriPath;
                            break;
                        case 3:
                            $payloadCatalog['third_catalog'] = $oriPath;
                            break;
                    }
            
                    $count++;
                }
            
                $getTempCatalog->delete();
                $payloadCatalog['motor_name'] = $data['motor_name'];
                $payloadCatalog['charge'] = $data['charge'];
            
                $newCatalog = CatalogMotor::create($payloadCatalog);
            
                //Tambahkan package pricingnya
                foreach($data['price_lists'] as &$price){
                    $price['catalog_motor_id'] = $newCatalog->id;
                }

                DB::table('catalog_prices')->insert($data['price_lists']);

                //pindahkan seluruh catalog dari temp ke oripath
                foreach($movingPath as $oldPath => $newPath){
                    Storage::move($oldPath, $newPath);
                }

                $newCatalog->load(['price']);
            });
            
            return $newCatalog;

        } catch (Exception $e) {
            return $e;
        }
    }
}