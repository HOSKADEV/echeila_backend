<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\ColorDatatable;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $colors = new ColorDatatable;
        if ($request->wantsJson()) {
            return $colors->datatables($request);
        }

        return view('dashboard.color.list')->with([
            'columns' => $colors::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_CREATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.color.create');
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.color.edit')->with(['color' => Color::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'code' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            Color::create([
                'name' => $name,
                'code' => $data['code'],
            ]);

            DB::commit();

            return redirect()->route('colors.index')->with('success', __('app.created_successfully', ['name' => __('app.color')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'code' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $color = Color::findOrFail($id);

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            $color->update([
                'name' => $name,
                'code' => $data['code'],
            ]);

            DB::commit();

            return redirect()->route('colors.index')->with('success', __('app.updated_successfully', ['name' => __('app.color')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::COLOR_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $color = Color::findOrFail($id);
            $color->delete();

            return redirect()->route('colors.index')->with('success', __('app.deleted_successfully', ['name' => __('app.color')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
