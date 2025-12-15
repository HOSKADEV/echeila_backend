<?php

namespace App\Http\Controllers\Api;

use App\Traits\ImageUpload;
use Exception;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PassengerResource;
use App\Http\Requests\Api\Passenger\UpdatePassengerRequest;

class PassengerController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    /**
     * Update the authenticated user's passenger profile
     *
     * @param UpdatePassengerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePassengerRequest $request)
    {

        $validated = $this->validateRequest($request);

        try {
            $user = auth()->user();
            $passenger = $user->passenger;

            if (!$passenger) {
                throw new Exception('Passenger profile not found', 404);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $passenger->clearMediaCollection(Passenger::IMAGE);
                $this->uploadImageFromRequest($passenger, $request);
            }

            $passenger->update($validated);
            $passenger->refresh();

            return $this->successResponse(new PassengerResource($passenger));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}