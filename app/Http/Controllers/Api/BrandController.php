<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all brands
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $brands = Brand::all();

            return $this->successResponse(BrandResource::collection($brands));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}