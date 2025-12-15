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

    public function messages(): array
    {
        return [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.exists' => 'The selected trip does not exist.',
            'total_fees.required' => 'Total fees is required.',
            'total_fees.min' => 'Total fees must be at least 0.',
            'cargo.required' => 'Cargo information is required.',
            'cargo.array' => 'Cargo must be an object.',
            'cargo.description.required' => 'Cargo description is required.',
            'cargo.description.string' => 'Cargo description must be a string.',
            'cargo.description.max' => 'Cargo description cannot exceed 1000 characters.',
            'cargo.weight.required' => 'Cargo weight is required.',
            'cargo.weight.numeric' => 'Cargo weight must be a number.',
            'cargo.weight.min' => 'Cargo weight must be at least 0.01 kg.',
            'cargo.weight.max' => 'Cargo weight cannot exceed 1000 kg.',
            'cargo.images.array' => 'Cargo images must be an array.',
            'cargo.images.max' => 'Cannot upload more than 5 images.',
            'cargo.images.*.image' => 'Each file must be an image.',
            'cargo.images.*.mimes' => 'Images must be jpeg, png, jpg, or gif format.',
            'cargo.images.*.max' => 'Each image cannot exceed 2MB.',
        ];
    }

    public function validateResolved()
    {
    }
}