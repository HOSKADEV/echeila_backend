<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\BrandDatatable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $brands = new BrandDatatable;
        if ($request->wantsJson()) {
            return $brands->datatables($request);
        }

        return view('dashboard.brand.list')->with([
            'columns' => $brands::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_CREATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.brand.create');
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.brand.edit')->with(['brand' => Brand::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate($nameValidation);

        try {
            DB::beginTransaction();

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            Brand::create([
                'name' => $name,
            ]);

            DB::commit();

            return redirect()->route('brands.index')->with('success', __('app.created_successfully', ['name' => __('app.brand')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate($nameValidation);

        try {
            DB::beginTransaction();

            $brand = Brand::findOrFail($id);

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            $brand->update([
                'name' => $name,
            ]);

            DB::commit();

            return redirect()->route('brands.index')->with('success', __('app.updated_successfully', ['name' => __('app.brand')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::BRAND_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            return redirect()->route('brands.index')->with('success', __('app.deleted_successfully', ['name' => __('app.brand')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
