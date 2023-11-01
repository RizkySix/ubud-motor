<?php

namespace App\Http\Controllers\Profile;

use App\Action\Profile\UpdateAdminProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\AdminProfileRequest;
use App\Http\Resources\UserResource;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    use HasCustomResponse;
    /**
     * Handle update profile
     */
    public function update_profile(AdminProfileRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        
        $response = UpdateAdminProfileAction::handle_action($validatedData);

        return $this->custom_response($response , UserResource::make($response) , 200 , 422 , 'Failed update profile');
    }
}
