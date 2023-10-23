<?php

namespace App\Http\Controllers\Gallery;

use App\Action\Gallery\AddGalleryImageAction;
use App\Action\Gallery\DeleteGalleryImageAction;
use App\Action\Gallery\DeleteTempGalleryImageAction;
use App\Action\Gallery\TempGalleryImageAction;
use App\Action\Gallery\ThrowAllGalleryImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\DeleteTempGalleryRequest;
use App\Http\Requests\Gallery\GalleryRequest;
use App\Http\Requests\Gallery\TempGalleryRequest;
use App\Models\Gallery;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use HasCustomResponse;
    /**
     * Get gallery image
     */
    public function get_gallery_image()
    {
        $response = ThrowAllGalleryImageAction::handle_action();

        return $this->custom_response($response , $response , 200 , 422 , 'Failed fetch galleries');
    }

    /**
     * Add temp path gallery image
     */
    public function add_temp_gallery_image(TempGalleryRequest $request) : JsonResponse
    {
       $request->validated();
       $response = TempGalleryImageAction::handle_action($request);

       return $this->custom_response($response , $response , 201 , 422 , 'Something wrong with the path image');
    }

    /**
     * Delete temp gallery image
     */
    public function delete_temp_gallery_image(DeleteTempGalleryRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        $response = DeleteTempGalleryImageAction::handle_action($validatedData['temp_path']);

        return $this->custom_response($response , 'Temp gallery deleted' , 200 , 422 , 'Temp path not found');
    }

    /**
     * Add gallery image
     */
    public function add_gallery_image(GalleryRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        $response = AddGalleryImageAction::handle_action($validatedData);

        return $this->custom_response($response , 'New gallery images added' , 201 , 422 , 'Failed adding new gallery images');
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
