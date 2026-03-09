<?php

namespace App\Http\Controllers\Api;

use App\Constants\LostAndFoundStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LostAndFound\StoreLostAndFoundRequest;
use App\Http\Requests\Api\LostAndFound\UpdateLostAndFoundRequest;
use App\Http\Resources\LostAndFoundResource;
use App\Models\LostAndFound;
use App\Traits\ApiResponseTrait;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Http\Request;

class LostAndFoundController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    public function index(Request $request)
    {
        try {
            $items = LostAndFound::query()
                ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
                ->when(!$request->filled('status'), fn($q) => $q->where('status', LostAndFoundStatus::FOUND))
                ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
                ->when($request->filled('search'), fn($q) => $q->where('description', 'like', '%' . $request->search . '%'))
                ->latest()
                ->paginate(10);

            return $this->successResponse(LostAndFoundResource::collection($items));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(StoreLostAndFoundRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            $user = auth()->user();

            $item = $user->lostAndFounds()->create([
                'description' => $validated['description'],
            ]);

            $this->uploadImageFromRequest($item, $request);

            return $this->successResponse(new LostAndFoundResource($item));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateLostAndFoundRequest $request, LostAndFound $lostAndFound)
    {
        try {
            $user = auth()->user();

            if ($lostAndFound->user_id !== $user->id) {
                throw new Exception('Unauthorized', 403);
            }

            $validated = $this->validateRequest($request);

            $lostAndFound->update([
                'description' => $validated['description'] ?? null,
                'status'      => $validated['status'] ?? null,
            ]);

            if ($request->hasFile('image')) {
                $this->uploadImageFromRequest($lostAndFound, $request);
            }

            return $this->successResponse(new LostAndFoundResource($lostAndFound->fresh()));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(LostAndFound $lostAndFound)
    {
        try {
            $user = auth()->user();

            if ($lostAndFound->user_id !== $user->id) {
                throw new Exception('Unauthorized', 403);
            }

            $lostAndFound->delete();

            return $this->successResponse(null, 'Lost and found item deleted successfully');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
