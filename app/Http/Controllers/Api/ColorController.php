<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;

class ColorController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all colors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $colors = Color::all();

            return $this->successResponse(ColorResource::collection($colors));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}