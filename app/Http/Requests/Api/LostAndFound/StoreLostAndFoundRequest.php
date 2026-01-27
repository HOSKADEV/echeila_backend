<?php

namespace App\Http\Requests\Api\LostAndFound;

use Illuminate\Foundation\Http\FormRequest;

class StoreLostAndFoundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'description.required' => __('validation.custom.description.required'),
            'description.string' => __('validation.custom.description.string'),
            'image.required' => __('validation.custom.image.required'),
            'image.image' => __('validation.custom.image.image'),
            'image.mimes' => __('validation.custom.image.mimes'),
            'image.max' => __('validation.custom.image.max'),
        ];
    }
}
