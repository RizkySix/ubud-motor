<?php

namespace App\Http\Controllers\Gallery;

use App\Action\Gallery\AddGalleryImageAction;
use App\Action\Gallery\DeleteGalleryImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\GalleryRequest;
use App\Models\Gallery;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use HasCustomResponse;
    /**
     * Add gallery image
     */
    public function add_gallery_image(GalleryRequest $request) : JsonResponse
    {
        $response = AddGalleryImageAction::handle_action($request);

        return $this->custom_response($response , 'New gallery image added' , 201 , 422 , 'Failed adding new gallery image');
    }

    /**
     * Destroy gallery image
     */
    public function delete_gallery_image(Gallery $gallery) : JsonResponse
    {
        $response = DeleteGalleryImageAction::handle_action($gallery);

        return $this->custom_response($response , 'Success delete gallery image' , 201 , 422 , 'Failed delete gallery image');
    }
}
