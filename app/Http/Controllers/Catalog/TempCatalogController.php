<?php

namespace App\Http\Controllers\Catalog;

use App\Action\Catalog\DeleteTemporaryCatalogAction;
use App\Action\Catalog\ReorderTemporaryCatalogAction;
use App\Action\Catalog\UploadTemporaryCatalogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\DeleteTemporaryCatalogRequest;
use App\Http\Requests\Catalog\ReorderTemporaryCatalogRequest;
use App\Http\Requests\Catalog\TemporaryUploadCatalogRequest;
use App\Trait\HasCustomResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TempCatalogController extends Controller
{
    use HasCustomResponse;
    /**
     * Upload temporary catalog motor image
     */
    public function upload_temporary_catalog(TemporaryUploadCatalogRequest $request) : JsonResponse
    {
       $request->validated();
       $response = UploadTemporaryCatalogAction::handle_action($request);

       return $this->custom_response($response , $response , 201 , 422 , 'Something wrong with the path image');
    }

     /**
     * Delete temporary catalog motor image
     */
    public function delete_temporary_catalog(DeleteTemporaryCatalogRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = DeleteTemporaryCatalogAction::handle_action($validatedData['temp_path']);

        return $this->custom_response($response , 'Temp catalog deleted' , 200 , 422 , 'Temp path not found');

    }


    /**
     * Add catalog image position after all image uploaded
     */
    public function reorder_temporary_catalog(ReorderTemporaryCatalogRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = ReorderTemporaryCatalogAction::handle_action($validatedData['temp_path']);

        return $this->custom_response($response , 'Success reorder temp catalog' , 200 , 422 , 'Failed reorder temp catalog');
    }
}
