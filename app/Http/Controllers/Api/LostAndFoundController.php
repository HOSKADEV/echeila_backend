<?php

namespace App\Http\Controllers\Api;

use App\Traits\ImageUpload;
use Exception;
use App\Models\LostAndFound;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\LostAndFoundResource;

class LostAndFoundController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    public function index()
    {
        try {
            $items = LostAndFound::latest()->paginate(10);
            return $this->successResponse(LostAndFoundResource::collection($items));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request, [
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            ]);

        try {

            $user = auth()->user();

            $passenger = $user->passenger;

            if (!$passenger) {
                throw new Exception('Passenger profile not found', 404);
            }
            
            $item = $passenger->lostAndFounds()->create([
                'description' => $validated['description'],
            ]);

            $this->uploadImageFromRequest($item, $request);

            return $this->successResponse(new LostAndFoundResource($item));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}