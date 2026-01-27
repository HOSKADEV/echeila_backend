<?php

namespace App\Http\Controllers\Dashboard\Documentation;

use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Support\Enum\Permissions;
use App\Constants\DocumentationKey;
use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_DOCUMENTATIONS)) {
      return redirect()->route('unauthorized');
    }

    $locales = ['en', 'fr', 'ar', 'es'];
    $requestedLocale = $request->query('locale');
    $selectedLocale = in_array($requestedLocale, $locales, true) ? $requestedLocale : app()->getLocale();

    $documentations = Documentation::query()->get()->keyBy('key');

    return view('dashboard.documentation.index', compact('documentations', 'locales', 'selectedLocale'));
  }

  public function store(Request $request)
  {

    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_DOCUMENTATIONS)) {
      return redirect()->route('unauthorized');
    }

    $locales = ['en', 'fr', 'ar', 'es'];

    $request->validate([
      'locale' => 'required|in:'.implode(',', $locales),
      'documentations' => 'required|array',
      'documentations.*.key' => 'required|in:'. implode(',', array_keys(DocumentationKey::all())),
      'documentations.*.value' => 'required|string'
    ]);

    try {
      $locale = $request->input('locale');
      $defaultLocale = config('app.locale', 'en');

      $items = $request->input('documentations', []);
      $keys = collect($items)->pluck('key')->filter()->values()->all();
      $models = Documentation::query()->whereIn('key', $keys)->get()->keyBy('key');

      foreach ($items as $documentation) {
        $key = $documentation['key'];
        $value = $documentation['value'];

        $model = $models->get($key) ?? Documentation::query()->create(['key' => $key]);

        $rawValue = $model->getRawOriginal('value');
        if (empty($model->getTranslations('value')) && is_string($rawValue) && $rawValue !== '') {
          json_decode($rawValue, true);
          if (json_last_error() !== JSON_ERROR_NONE) {
            $model->setTranslation('value', $defaultLocale, $rawValue);
          }
        }

        $model->setTranslation('value', $locale, $value);
        $model->save();
      }

      return redirect()->route('documentations.index', ['locale' => $locale])->with('success', __('app.updated_successfully', ['name' => __('app.documentations')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

  }
}
