<?php

namespace App\Datatables;

use App\Models\VehicleModel;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VehicleModelDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'name',
            'brand',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('vehicle-models.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::VEHICLE_MODEL_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::VEHICLE_MODEL_DELETE))
                        ->makeLabelledIcons();
                })
                ->addColumn('name', function ($model) {
                    return $this->bold($model->translate('name', app()->getLocale()));
                })
                ->addColumn('brand', function ($model) {
                    return $this->bold($model->brand->translate('name', app()->getLocale()));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = VehicleModel::query()->with('brand');

        if($request->brand_filter){
            $query->where('brand_id', $request->brand_filter);
        }
        return $query->get();
    }
}
