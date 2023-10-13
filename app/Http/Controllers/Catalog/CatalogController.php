<?php

namespace App\Http\Controllers\Catalog;

use App\Action\Catalog\StoreCatalogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\StoreCatalogRequest;
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
    public function index()
    {
        //
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
    public function show(CatalogMotor $catalogMotor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatalogMotor $catalogMotor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogMotor $catalogMotor)
    {
        //
    }
}
