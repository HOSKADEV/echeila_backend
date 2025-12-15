<?php

namespace App\Datatables;

use App\Models\Wilaya;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WilayaDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'wilaya',
            //'latitude',
            //'longitude',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('wilayas.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::WILAYA_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::WILAYA_DELETE))
                        ->makeLabelledIcons();
                })
                ->addColumn('wilaya', function ($model) {
                    return $this->bold($model->translate('name', app()->getLocale()));
                })
                /* ->addColumn('latitude', function ($model) {
                    return $model->latitude;
                })
                ->addColumn('longitude', function ($model) {
                    return $model->longitude;
                }) */
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        return Wilaya::query()->get();
    }
}
