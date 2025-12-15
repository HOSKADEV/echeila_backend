<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
  public function index()
  {
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('dashboard.settings.index')
      ->with('settings', $settings);
  }

  public function store(Request $request)
  {
    foreach ($request->except('__token') as $key => $value) {
      Setting::updateOrInsert(['key' => $key], ['value' => $value]);
    }

    return redirect()->route('settings.index')->with('message', 'Settings updated successefully');
  }
}
