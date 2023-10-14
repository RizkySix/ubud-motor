<?php

namespace App\Http\Controllers\Catalog;

use App\Action\Catalog\DeleteCatalogAction;
use App\Action\Catalog\DeleteSingleCatalogImageAction;
use App\Action\Catalog\StoreCatalogAction;
use App\Action\Catalog\ThrowAllCatalogAction;
use App\Action\Catalog\UpdateCatalogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\DeleteSingleCatalogImageRequest;
use App\Http\Requests\Catalog\StoreCatalogRequest;
use App\Http\Requests\Catalog\UpdateCatalogRequest;
use App\Http\Resources\CatalogResource;
use App\Models\CatalogMotor;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    use HasCustomResponse;
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $response = ThrowAllCatalogAction::handle_action();

        return $this->custom_response($response , CatalogResource::collection($response) , 200 , 422 , 'Failed to fetch catalogs');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCatalogRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['reorder'] = $request->reorder ? true : false;

        $response = StoreCatalogAction::handle_action($validatedData);

        return $this->custom_response($response , CatalogResource::make($response) , 201 , 422 , 'Catalog failed to create');
    }

    /**
     * Display the specified resource.
     */
    public function show(CatalogMotor $catalog) : JsonResponse
    {
        return $this->custom_response($catalog , CatalogResource::make($catalog->load(['price'])) , 200 , 422 , 'Cant get this catalog model');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalogRequest $request, CatalogMotor $catalog) : JsonResponse
    {
       $response = UpdateCatalogAction::handle_action($request , $catalog);

      return $this->custom_response($response , CatalogResource::make($response) , 200, 422 , 'Failed to update catalog');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogMotor $catalog) : JsonResponse
    {
        $response = DeleteCatalogAction::handle_action($catalog);

        return $this->custom_response($response , 'Success delete catalog' , 200 , 422 , 'Failed delete catalog');
    }

    /**
     * Destroy specifiec catalog image
     */
    public function delete_catalog_image(DeleteSingleCatalogImageRequest $request , CatalogMotor $catalog)
    {
        $validatedData = $request->validated();

        $response = DeleteSingleCatalogImageAction::handle_action($validatedData['catalog_position'] , $catalog);

        return $this->custom_response($response , CatalogResource::make($response) , 200, 422 , 'Failed to delete catalog image');
    }
}
