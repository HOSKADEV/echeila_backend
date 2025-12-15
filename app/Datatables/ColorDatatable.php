<?php

namespace App\Datatables;

use App\Models\Color;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ColorDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'name',
            'code',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('colors.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::COLOR_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::COLOR_DELETE))
                        ->makeLabelledIcons();
                })
                ->addColumn('name', function ($model) {
                    return $this->bold($model->translate('name', app()->getLocale()));
                })
                ->addColumn('code', function ($model) {
                    return '<span class="badge" style="background-color: ' . $model->code . '">' . $model->code . '</span>';
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        return Color::query()->get();
    }
}
