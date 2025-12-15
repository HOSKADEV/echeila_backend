<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\SeatPrice;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeatPriceResource;

class SeatPriceController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all seat prices
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $seatPrices = SeatPrice::with(['startingWilaya', 'arrivalWilaya'])->get();

            return $this->successResponse(SeatPriceResource::collection($seatPrices));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * Get a specific seat price by ID
     *
     * @param int $startingWilayaId
     * @param int $arrivalWilayaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($startingWilayaId, $arrivalWilayaId)
    {
        try {
            $seatPrice = SeatPrice::with(['startingWilaya', 'arrivalWilaya'])->where('starting_wilaya_id', $startingWilayaId)->where('arrival_wilaya_id', $arrivalWilayaId)->firstOrFail();

            return $this->successResponse(new SeatPriceResource($seatPrice));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}