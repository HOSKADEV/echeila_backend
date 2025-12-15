<?php

namespace App\Http\Requests\Api\Driver;

use App\Constants\CardType;
use App\Constants\TripType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Driver fields
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'email' => 'nullable|email',
            'image' => 'nullable|image|max:8192',

            // Vehicle fields
            'vehicle.model_id' => 'required|exists:models,id',
            'vehicle.color_id' => 'required|exists:colors,id',
            'vehicle.production_year' => 'required|integer|min:1900|max:' . date('Y'),
            'vehicle.plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'vehicle.image' => 'nullable|image|max:8192',
            'vehicle.permit' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:8192',

            // Services (array of trip types)
            'services' => 'required|array|min:1',
            'services.*' => ['required', 'string', Rule::in(TripType::all())],

            // Cards
            'cards.national_id.number' => 'required|string|max:255|unique:cards,number',
            'cards.national_id.expiration_date' => 'required|date|after:today',
            'cards.national_id.front_image' => 'required|image|max:8192',
            'cards.national_id.back_image' => 'required|image|max:8192',

            'cards.driving_license.number' => 'required|string|max:255|unique:cards,number',
            'cards.driving_license.expiration_date' => 'required|date|after:today',
            'cards.driving_license.front_image' => 'required|image|max:8192',
            'cards.driving_license.back_image' => 'required|image|max:8192',
        ];
    }

    public function validateResolved()
    {
    }
}