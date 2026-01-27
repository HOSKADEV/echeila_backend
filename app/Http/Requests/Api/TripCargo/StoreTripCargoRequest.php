<?php

namespace App\Http\Requests\Api\TripCargo;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripCargoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
            'total_fees' => 'required|numeric|min:0',
            'cargo' => 'required|array',
            'cargo.description' => 'required|string|max:1000',
            'cargo.weight' => 'required|numeric|min:0.01|max:1000',
            'cargo.images' => 'nullable|array|max:5',
            'cargo.images.*' => 'image|mimes:jpeg,png,jpg,gif|max:8192',
        ];
    }

    public function validateResolved()
    {
    }

        public function messages(): array
    {
        return [
            'trip_id.required' => __('validation.custom.trip_id.required'),
            'trip_id.exists' => __('validation.custom.trip_id.exists'),
            'total_fees.required' => __('validation.custom.total_fees.required'),
            'total_fees.min' => __('validation.custom.total_fees.min'),
            'cargo.required' => __('validation.custom.cargo.required'),
            'cargo.array' => __('validation.custom.cargo.array'),
            'cargo.description.required' => __('validation.custom.cargo.description.required'),
            'cargo.description.string' => __('validation.custom.cargo.description.string'),
            'cargo.description.max' => __('validation.custom.cargo.description.max'),
            'cargo.weight.required' => __('validation.custom.cargo.weight.required'),
            'cargo.weight.numeric' => __('validation.custom.cargo.weight.numeric'),
            'cargo.weight.min' => __('validation.custom.cargo.weight.min'),
            'cargo.weight.max' => __('validation.custom.cargo.weight.max'),
            'cargo.images.array' => __('validation.custom.cargo.images.array'),
            'cargo.images.max' => __('validation.custom.cargo.images.max'),
            'cargo.images.*.image' => __('validation.custom.cargo.images.*.image'),
            'cargo.images.*.mimes' => __('validation.custom.cargo.images.*.mimes'),
            'cargo.images.*.max' => __('validation.custom.cargo.images.*.max'),
        ];
    }
}
