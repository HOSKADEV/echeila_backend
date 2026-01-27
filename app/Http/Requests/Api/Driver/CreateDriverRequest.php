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

    public function messages()
    {
        return [
            'first_name.required' => __('validation.custom.first_name.required'),
            'first_name.string' => __('validation.custom.first_name.string'),
            'first_name.max' => __('validation.custom.first_name.max'),

            'last_name.required' => __('validation.custom.last_name.required'),
            'last_name.string' => __('validation.custom.last_name.string'),
            'last_name.max' => __('validation.custom.last_name.max'),

            'birth_date.required' => __('validation.custom.birth_date.required'),
            'birth_date.date' => __('validation.custom.birth_date.date'),
            'birth_date.before' => __('validation.custom.birth_date.before'),

            'email.email' => __('validation.custom.email.email'),

            'image.image' => __('validation.custom.image.image'),
            'image.max' => __('validation.custom.image.max'),

            'vehicle.model_id.required' => __('validation.custom.vehicle.model_id.required'),
            'vehicle.model_id.exists' => __('validation.custom.vehicle.model_id.exists'),

            'vehicle.color_id.required' => __('validation.custom.vehicle.color_id.required'),
            'vehicle.color_id.exists' => __('validation.custom.vehicle.color_id.exists'),

            'vehicle.production_year.required' => __('validation.custom.vehicle.production_year.required'),
            'vehicle.production_year.integer' => __('validation.custom.vehicle.production_year.integer'),
            'vehicle.production_year.min' => __('validation.custom.vehicle.production_year.min'),
            'vehicle.production_year.max' => __('validation.custom.vehicle.production_year.max'),

            'vehicle.plate_number.required' => __('validation.custom.vehicle.plate_number.required'),
            'vehicle.plate_number.string' => __('validation.custom.vehicle.plate_number.string'),
            'vehicle.plate_number.max' => __('validation.custom.vehicle.plate_number.max'),
            'vehicle.plate_number.unique' => __('validation.custom.vehicle.plate_number.unique'),

            'vehicle.image.image' => __('validation.custom.vehicle.image.image'),
            'vehicle.image.max' => __('validation.custom.vehicle.image.max'),

            'vehicle.permit.file' => __('validation.custom.vehicle.permit.file'),
            'vehicle.permit.mimes' => __('validation.custom.vehicle.permit.mimes'),
            'vehicle.permit.max' => __('validation.custom.vehicle.permit.max'),

            'services.required' => __('validation.custom.services.required'),
            'services.array' => __('validation.custom.services.array'),
            'services.min' => __('validation.custom.services.min'),
            'services.*.required' => __('validation.custom.services.*.required'),
            'services.*.string' => __('validation.custom.services.*.string'),
            'services.*.in' => __('validation.custom.services.*.in'),
        ];
    }
}
