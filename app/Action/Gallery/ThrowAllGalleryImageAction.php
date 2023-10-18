<?php

namespace App\Action\Gallery;

use App\Models\Gallery;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowAllGalleryImageAction
{
    /**
     * Handle action
     */
    public static function handle_action() : Collection|Exception
    {
        try {

            $galleries = Gallery::orderBy('id' , 'DESC')->get();

            return $galleries;

        } catch (Exception $e) {
            return $e;
        }
    }
}