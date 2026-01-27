<?php

namespace App\Http\Requests\Api\Driver;

use Illuminate\Foundation\Http\FormRequest;

class DriverIncomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => 'required|in:day,week,month,year'
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'period.required' => __('validation.custom.period.required'),
            'period.in' => __('validation.custom.period.in'),
        ];
    }
}
