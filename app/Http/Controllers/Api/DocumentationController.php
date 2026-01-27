<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Models\Documentation;

class DocumentationController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request, $key)
  {
    $model = Documentation::where('key', $key)->first();

    if (!$model) {
      return $this->errorResponse(
        message: 'documentation_not_found',
        statusCode: 404
      );
    }

    $locale = app()->getLocale();

    $translations = $model->getTranslations('value');
    $value = $translations[$locale] ?? '';

    $rawValue = $model->getRawOriginal('value');
    if ($value === '' && is_string($rawValue) && $rawValue !== '') {
      json_decode($rawValue, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        $defaultLocale = config('app.locale', 'en');
        if ($locale === $defaultLocale) {
          $value = $rawValue;
        }
      }
    }

    return $this->successResponse($value);
  }
}
