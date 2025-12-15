<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get settings by keys
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {

            $settings = Setting::whereIn('key', [
                'android_version_number',
                'android_build_number',
                'android_priority',
                'android_link',
                'ios_version_number',
                'ios_build_number',
                'ios_priority',
                'ios_link',
                'contact_phone',
                'contact_email',
                'contact_facebook',
                'contact_instagram',
                'emergency_number',
                'subscription_month_price',
                'water_price_per_litre',
                'cargo_price_per_kg',
                'min_charge_amount',
                'max_withdraw_amount',
            ])->pluck('value', 'key')->toArray();

            return $this->successResponse($settings);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
