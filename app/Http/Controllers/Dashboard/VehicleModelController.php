<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\VehicleModelDatatable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $vehicleModels = new VehicleModelDatatable;
        if ($request->wantsJson()) {
            return $vehicleModels->datatables($request);
        }

        return view('dashboard.vehicle-model.list')->with([
            'columns' => $vehicleModels::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $brands = Brand::all();

        return view('dashboard.vehicle-model.create')->with(['brands' => $brands]);
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $vehicleModel = VehicleModel::findOrFail($id);
        $brands = Brand::all();

        return view('dashboard.vehicle-model.edit')->with([
            'vehicleModel' => $vehicleModel,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'brand_id' => 'required|exists:brands,id',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            VehicleModel::create([
                'brand_id' => $data['brand_id'],
                'name' => $name,
            ]);

            DB::commit();

            return redirect()->route('vehicle-models.index')->with('success', __('app.created_successfully', ['name' => __('app.vehicle_model')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $locales = config('app.available_locales', ['ar', 'en', 'fr']);
        $nameValidation = [];

        foreach ($locales as $locale) {
            $nameValidation["name.{$locale}"] = 'required|string|max:255';
        }

        $data = $request->validate(array_merge([
            'brand_id' => 'required|exists:brands,id',
        ], $nameValidation));

        try {
            DB::beginTransaction();

            $vehicleModel = VehicleModel::findOrFail($id);

            $name = [];
            foreach ($locales as $locale) {
                $name[$locale] = $request->input("name.{$locale}");
            }

            $vehicleModel->update([
                'brand_id' => $data['brand_id'],
                'name' => $name,
            ]);

            DB::commit();

            return redirect()->route('vehicle-models.index')->with('success', __('app.updated_successfully', ['name' => __('app.vehicle_model')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::VEHICLE_MODEL_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $vehicleModel = VehicleModel::findOrFail($id);
            $vehicleModel->delete();

            return redirect()->route('vehicle-models.index')->with('success', __('app.deleted_successfully', ['name' => __('app.vehicle_model')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
