<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleModelResource;

class VehicleModelController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all vehicle models with optional brand filter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = VehicleModel::with('brand');

            // Filter by brand_id if provided
            if ($request->filled('brand_id') && $request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }

            $models = $query->get();

            return $this->successResponse(VehicleModelResource::collection($models));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}