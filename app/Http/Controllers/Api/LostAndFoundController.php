<?php

namespace App\Http\Controllers\Api;

use App\Constants\LostAndFoundStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LostAndFound\StoreLostAndFoundRequest;
use App\Http\Requests\Api\LostAndFound\UpdateLostAndFoundRequest;
use App\Http\Resources\LostAndFoundResource;
use App\Models\Driver;
use App\Models\LostAndFound;
use App\Models\Passenger;
use App\Models\User;
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
            $user = auth()->user();

            $items = LostAndFound::query()
                ->with('finder')
                ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
                ->when(!$request->filled('status'), fn($q) => $q->where('status', LostAndFoundStatus::FOUND))
                ->when($request->boolean('mine'), function ($q) use ($user) {
                    // Return only the current user's records
                    $q->where(function ($sub) use ($user) {
                        if ($user->passenger) {
                            $sub->orWhere(fn($s) => $s->where('finder_type', Passenger::class)->where('finder_id', $user->passenger->id));
                        }
                        if ($user->driver) {
                            $sub->orWhere(fn($s) => $s->where('finder_type', Driver::class)->where('finder_id', $user->driver->id));
                        }
                    });
                }, function ($q) use ($user) {
                    // Return records NOT belonging to the current user
                    $q->where(function ($sub) use ($user) {
                        if ($user->passenger) {
                            $sub->where(fn($s) => $s->where('finder_type', '!=', Passenger::class)->orWhere('finder_id', '!=', $user->passenger->id));
                        }
                        if ($user->driver) {
                            $sub->where(fn($s) => $s->where('finder_type', '!=', Driver::class)->orWhere('finder_id', '!=', $user->driver->id));
                        }
                    });
                })
                ->when($request->filled('period'), function ($q) use ($request) {
                    match ($request->period) {
                        'today'      => $q->whereDate('created_at', today()),
                        'last_week'  => $q->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]),
                        'last_month' => $q->whereBetween('created_at', [now()->subMonth()->startOfDay(), now()->endOfDay()]),
                        default      => null,
                    };
                })
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
            $user  = auth()->user();
            $finder = $this->resolveFinderFromUser($user, $validated['finder_type']);

            $item = $finder->lostAndFounds()->create([
                'description' => $validated['description'],
            ]);

            $this->uploadImageFromRequest($item, $request);

            return $this->successResponse(new LostAndFoundResource($item->load('finder')));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateLostAndFoundRequest $request, LostAndFound $lostAndFound)
    {
        try {
            $user = auth()->user();

            if (! $this->userOwnsFinder($user, $lostAndFound)) {
                throw new Exception('Unauthorized', 403);
            }

            $validated = $this->validateRequest($request);

            $lostAndFound->update([
                'description' => $validated['description'] ?? $lostAndFound->description,
                'status'      => $validated['status'] ?? $lostAndFound->status,
            ]);

            if ($request->hasFile('image')) {
                $this->uploadImageFromRequest($lostAndFound, $request);
            }

            return $this->successResponse(new LostAndFoundResource($lostAndFound->fresh()->load('finder')));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(LostAndFound $lostAndFound)
    {
        try {
            $user = auth()->user();

            if (! $this->userOwnsFinder($user, $lostAndFound)) {
                throw new Exception('Unauthorized', 403);
            }

            $lostAndFound->delete();

            return $this->successResponse(null, 'Lost and found item deleted successfully');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function resolveFinderClass(string $type): string
    {
        return match (strtolower($type)) {
            'driver'    => Driver::class,
            'passenger' => Passenger::class,
            default     => throw new Exception("Invalid finder type: {$type}", 422),
        };
    }

    private function resolveFinderFromUser(User $user, string $type): Passenger|Driver
    {
        return match (strtolower($type)) {
            'passenger' => $user->passenger ?? throw new Exception('No passenger profile found for this user.', 422),
            'driver'    => $user->driver    ?? throw new Exception('No driver profile found for this user.', 422),
            default     => throw new Exception("Invalid finder type: {$type}", 422),
        };
    }

    private function userOwnsFinder(User $user, LostAndFound $lostAndFound): bool
    {
        if ($lostAndFound->finder_type === Passenger::class) {
            return $user->passenger && $user->passenger->id === $lostAndFound->finder_id;
        }

        if ($lostAndFound->finder_type === Driver::class) {
            return $user->driver && $user->driver->id === $lostAndFound->finder_id;
        }

        return false;
    }
}
