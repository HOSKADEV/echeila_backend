<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\WilayaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Wilaya;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayaController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $wilayas = new WilayaDatatable;
        if ($request->wantsJson()) {
            return $wilayas->datatables($request);
        }

        return view('dashboard.wilaya.list')->with([
            'columns' => $wilayas::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_CREATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.wilaya.create');
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.wilaya.edit')->with(['wilaya' => Wilaya::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            Wilaya::create([
                'name' => $name,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            DB::commit();

            return redirect()->route('wilayas.index')->with('success', __('app.created_successfully', ['name' => __('app.wilaya')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $wilaya = Wilaya::findOrFail($id);

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            $wilaya->update([
                'name' => $name,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            DB::commit();

            return redirect()->route('wilayas.index')->with('success', __('app.updated_successfully', ['name' => __('app.wilaya')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::WILAYA_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $wilaya = Wilaya::findOrFail($id);
            $wilaya->delete();

            return redirect()->route('wilayas.index')->with('success', __('app.deleted_successfully', ['name' => __('app.wilaya')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
