<?php

namespace App\Http\Requests\Api\TripReview;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validateResolved()
    {
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
            'reviewer_type' => 'required|in:driver,passenger',
            'reviewee_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'trip_id.required' => __('validation.custom.trip_id.required'),
            'trip_id.exists' => __('validation.custom.trip_id.exists'),
            'reviewer_type.required' => __('validation.custom.reviewer_type.required'),
            'reviewer_type.in' => __('validation.custom.reviewer_type.in'),
            'reviewee_id.required' => __('validation.custom.reviewee_id.required'),
            'reviewee_id.integer' => __('validation.custom.reviewee_id.integer'),
            'rating.required' => __('validation.custom.rating.required'),
            'rating.integer' => __('validation.custom.rating.integer'),
            'rating.min' => __('validation.custom.rating.min'),
            'rating.max' => __('validation.custom.rating.max'),
            'comment.string' => __('validation.custom.comment.string'),
        ];
    }
}
