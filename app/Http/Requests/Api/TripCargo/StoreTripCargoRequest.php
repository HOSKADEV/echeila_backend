<?php

namespace App\Http\Requests\Api\TripCargo;

use Closure;
use App\Constants\PaymentMethod;
use Illuminate\Http\UploadedFile;
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
            'payment_method' => 'required|in:' . implode(',', PaymentMethod::all()),
            'total_fees' => 'required|numeric|min:0',
            'cargo' => 'required|array',
            'cargo.description' => 'required|string|max:1000',
            'cargo.weight' => 'required|numeric|min:0.01|max:1000',
            'cargo.images' => 'nullable|array',
            'cargo.images.*' => ['bail', $this->cargoImageRule()],
        ];
    }

    protected function cargoImageRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if ($value instanceof UploadedFile) {
                if (!$value->isValid()) {
                    $fail(__('validation.custom.cargo.images.*.file_or_url'));
                    return;
                }

                $extension = strtolower($value->getClientOriginalExtension());
                if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'], true)) {
                    $fail(__('validation.custom.cargo.images.*.mimes'));
                    return;
                }

                if (($value->getSize() ?? 0) > 8192 * 1024) {
                    $fail(__('validation.custom.cargo.images.*.max'));
                }

                return;
            }

            if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                return;
            }

            $fail(__('validation.custom.cargo.images.*.file_or_url'));
        };
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
            'cargo.images.*.file_or_url' => __('validation.custom.cargo.images.*.file_or_url'),
            'cargo.images.*.mimes' => __('validation.custom.cargo.images.*.mimes'),
            'cargo.images.*.max' => __('validation.custom.cargo.images.*.max'),
            'payment_method.required' => __('validation.custom.payment_method.required'),
            'payment_method.in' => __('validation.custom.payment_method.in'),
        ];
    }
}
