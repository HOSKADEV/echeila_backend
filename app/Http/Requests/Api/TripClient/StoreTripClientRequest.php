<?php

namespace App\Http\Requests\Api\TripClient;

use App\Constants\TripType;
use App\Models\Trip;
use App\Models\InternationalTripDetail;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
            'number_of_seats' => 'required|integer|min:1|max:50',
            'note' => 'nullable|string|max:1000',
            'fullname' => 'nullable|required_with:phone|string|max:255',
            'phone' => 'nullable|required_with:fullname|string|regex:/^\+\d{1,3}\d{9}$/',
        ];
    }

    public function validateResolved()
    {
    }
}