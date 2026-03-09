<?php

namespace App\Http\Requests\Api\LostAndFound;

use App\Constants\LostAndFoundStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLostAndFoundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'sometimes|string',
            'status'      => ['sometimes', Rule::in(LostAndFoundStatus::all())],
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'description.string' => __('validation.custom.description.string'),
            'status.in'          => __('validation.custom.status.in'),
            'image.image'        => __('validation.custom.image.image'),
            'image.mimes'        => __('validation.custom.image.mimes'),
            'image.max'          => __('validation.custom.image.max'),
        ];
    }
}
