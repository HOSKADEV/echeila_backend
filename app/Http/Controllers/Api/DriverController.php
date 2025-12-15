<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Card;
use App\Models\Driver;
use App\Models\Service;
use App\Models\Vehicle;
use App\Constants\CardType;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Constants\DriverStatus;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Constants\NotificationMessages;
use App\Notifications\NewMessageNotification;
use App\Http\Requests\Api\Driver\CreateDriverRequest;
use App\Http\Requests\Api\Driver\UpdateDriverRequest;

class DriverController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    /**
     * Create a new driver with vehicle, services, and cards
     */
    public function store(CreateDriverRequest $request): JsonResponse
    {

        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();

            // Create driver
            $driver = Driver::create([
                'user_id' => auth()->id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'email' => $request->email,
            ]);

            // Handle driver image
            if ($request->hasFile('image')) {
                $this->uploadImageFromRequest($driver, $request);
            }

            // Create vehicle
            $vehicle = Vehicle::create([
                'driver_id' => $driver->id,
                'model_id' => $request->input('vehicle.model_id'),
                'color_id' => $request->input('vehicle.color_id'),
                'production_year' => $request->input('vehicle.production_year'),
                'plate_number' => $request->input('vehicle.plate_number'),
            ]);

            // Handle vehicle images
            if ($request->hasFile('vehicle.image')) {
                $this->uploadImageFromRequest($vehicle, $request, 'vehicle.image', Vehicle::IMAGE);
            }

            if ($request->hasFile('vehicle.permit')) {
                $this->uploadImageFromRequest($vehicle, $request, 'vehicle.permit', Vehicle::PERMIT);
            }

            // Create services
            foreach ($request->services as $tripType) {
                Service::create([
                    'driver_id' => $driver->id,
                    'trip_type' => $tripType,
                ]);
            }

            // Create National ID card
            $nationalIdCard = Card::create([
                'driver_id' => $driver->id,
                'type' => CardType::NATIONAL_ID,
                'number' => $request->input('cards.national_id.number'),
                'expiration_date' => $request->input('cards.national_id.expiration_date'),
            ]);

            // Handle National ID images
            if ($request->hasFile('cards.national_id.front_image')) {
                $this->uploadImageFromRequest($nationalIdCard, $request, 'cards.national_id.front_image', Card::FRONT_IMAGE);
            }

            if ($request->hasFile('cards.national_id.back_image')) {
                $this->uploadImageFromRequest($nationalIdCard, $request, 'cards.national_id.back_image', Card::BACK_IMAGE);
            }

            // Create Driving License card
            $drivingLicenseCard = Card::create([
                'driver_id' => $driver->id,
                'type' => CardType::DRIVING_LICENSE,
                'number' => $request->input('cards.driving_license.number'),
                'expiration_date' => $request->input('cards.driving_license.expiration_date'),
            ]);

            // Handle Driving License images
            if ($request->hasFile('cards.driving_license.front_image')) {
                $this->uploadImageFromRequest($drivingLicenseCard, $request, 'cards.driving_license.front_image', Card::FRONT_IMAGE);
            }

            if ($request->hasFile('cards.driving_license.back_image')) {
                $this->uploadImageFromRequest($drivingLicenseCard, $request, 'cards.driving_license.back_image', Card::BACK_IMAGE);
            }

            // Send notification
            $driver->user->notify(new NewMessageNotification(
                NotificationMessages::DRIVER_PENDING,
                ['status' => DriverStatus::PENDING]
            ));

            DB::commit();

            // Load relationships for response
            $driver->load(['subscription', 'federation', 'vehicle.model.brand', 'vehicle.color', 'services', 'cards']);

            return $this->successResponse(new DriverResource($driver));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update an existing driver
     */
    public function update(UpdateDriverRequest $request): JsonResponse
    {

        $this->validateRequest($request);

        try {

            $user = auth()->user();
            $driver = $user->driver;

            if (!$driver) {
                throw new Exception('Driver profile not found', 404);
            }

            $driver->update($request->validated());

            // Handle image upload
            if ($request->hasFile('image')) {
                $driver->clearMediaCollection(Driver::IMAGE);
                $this->uploadImageFromRequest($driver, $request, 'image', Driver::IMAGE);
            }

            // Load relationships for response
            $driver->load(['subscription', 'federation', 'vehicle.model.brand', 'vehicle.color', 'services', 'cards']);

            return $this->successResponse(new DriverResource($driver));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function income(Request $request){
        $this->validateRequest($request, [
            'period' => 'required|in:day,week,month,year'
        ]);

        try {

            $user = auth()->user();
            $driver = $user->driver;

            if (!$driver) {
                throw new Exception('Driver not found',404);
            }

            $income = $driver->income($request->period) ?? 0;
            return $this->successResponse(['income' => $income]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get driver statistics
     */
    public function stats(Request $request)
    {
        try {
            $user = auth()->user();
            $driver = $user->driver;

            if (!$driver) {
                throw new Exception('Driver not found', 404);
            }

            // Get total trips count
            $tripsCount = $driver->trips()->count();

            // Get reviews received count
            $reviewsReceivedCount = $driver->reviewsReceived()->count();

            // Get average rating
            $avgRating = $driver->reviewsReceived()->avg('rating') ?? 0;

            return $this->successResponse([
                'trips_count' => $tripsCount,
                'reviews_received_count' => $reviewsReceivedCount,
                'avg_rating' => round($avgRating, 2),
            ]);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}