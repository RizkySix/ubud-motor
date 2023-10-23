<?php

namespace App\Action\Gallery;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AddGalleryImageAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : bool|Exception
    {
        try {
            
            $newPath = $data['gallery_image'];
            rsort($newPath);
            $movingPath = [];
            $galleries = [];

            foreach ($newPath as $temp) {
                $fileName = explode('/', $temp);
                $fileName = end($fileName);
                $oriPath = 'Gallery/' . $fileName;
        
                $movingPath[$temp] = $oriPath;
                $galleries[] = [
                    'gallery_image' => $oriPath
                ];
              
            }

            //hapus gambar dari temp galleries
            DB::table('temp_galleries')->whereIn('temp_path', $newPath)->delete();
            DB::table('galleries')->insert($galleries);

            //pindahkan seluruh galleries dari temp ke oripath
            foreach($movingPath as $oldPath => $newPath){
                Storage::move($oldPath, $newPath);
            }

            return true;

        } catch (Exception $e) {
            return $e;
        }
    }
}