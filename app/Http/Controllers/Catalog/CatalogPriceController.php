<?php

namespace App\Http\Controllers\Catalog;

use App\Action\Catalog\AddMoreCatalogPriceAction;
use App\Action\Catalog\DeleteCatalogPrice;
use App\Action\Catalog\UpdateCatalogPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\PriceListRequest;
use App\Http\Requests\Catalog\UpdatePriceCatalogRequest;
use App\Http\Resources\CatalogPriceResource;
use App\Models\CatalogPrice;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogPriceController extends Controller
{
    use HasCustomResponse;
    /**
     * Adding more price lists
     */
    public function add_prices(PriceListRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        
        $response = AddMoreCatalogPriceAction::handle_action($validatedData);

        return $this->custom_response($response , 'New price lists added' , 201 , 422 , 'Failed adding new prices');
    }

    /**
     * Updating price lists
     */
    public function update_prices(UpdatePriceCatalogRequest $request , CatalogPrice $price) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = UpdateCatalogPriceAction::handle_action($validatedData , $price);

        return $this->custom_response($response , CatalogPriceResource::make($response) , 200 , 422 , 'Failed update catalog price');
    }


    /**
     * Deleting price lists
     */
    public function delete_prices(CatalogPrice $price) : JsonResponse
    {
        $response = DeleteCatalogPrice::handle_action($price);

        return $this->custom_response($response , 'Success delete price list' , 200 , 422 ,'Failed delete price list');
    }
}
