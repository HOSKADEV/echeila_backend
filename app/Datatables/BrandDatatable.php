<?php

namespace App\Datatables;

use App\Models\Brand;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BrandDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'name',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('brands.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::BRAND_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::BRAND_DELETE))
                        ->makeLabelledIcons();
                })
                ->addColumn('name', function ($model) {
                    return $this->bold($model->translate('name', app()->getLocale()));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        return Brand::query()->get();
    }
}
