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

    return $this->successResponse($model->value ?? " ");
  }
}
