<?php

namespace App\Action\Gallery;

use App\Models\Gallery;
use App\Trait\HasCustomResponse;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeleteGalleryImageAction
{
    use HasCustomResponse;
    /**
     * Handle action
     */
    public static function handle_action(Gallery $gallery) : bool|Exception
    {
        try {
            
            if($gallery->gallery_image){
                Storage::delete(HasCustomResponse::get_base_path($gallery->gallery_image));

                $gallery->delete();

                return true;
            }

            return false;

        } catch (Exception $e) {
            return $e;
        }
    }
}