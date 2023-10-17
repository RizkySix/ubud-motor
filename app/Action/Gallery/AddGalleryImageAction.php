<?php

namespace App\Action\Gallery;

use App\Http\Requests\Gallery\GalleryRequest;
use App\Models\Gallery;
use Exception;

class AddGalleryImageAction
{
    /**
     * Handle action
     */
    public static function handle_action(GalleryRequest $request) : bool|Exception
    {
        try {
            
            if($request->file('gallery_image')){
                $file = $request->file('gallery_image');
                $file = $file->store('Gallery');

                Gallery::create(['gallery_image' => $file]);

                return true;
            }

            return false;

        } catch (Exception $e) {
            return $e;
        }
    }
}