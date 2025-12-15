<?php

namespace App\Datatables;

use Exception;
use App\Models\Admin;
use App\Support\Enum\Roles;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class AdminDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'admin',
            'email',
            'role',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('admins.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::ADMIN_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::ADMIN_DELETE))
                        ->makeLabelledIcons();
                })
                ->addColumn('admin', function ($model) {
                    return $this->thumbnailTitleMeta($model->avatar_url, $model->fullname, $model->phone);
                })
                ->addColumn('email', function ($model) {
                    return $model->email;
                })
                ->addColumn('role', function ($model) {
                    $role = $model->getRoleNames()->first() ?? '-';

                    return $this->badge(Roles::get_name($role), Roles::get_color($role));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this).' Error '.$e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Admin::whereNot('id', Auth::id());

        if ($request->role_filter) {
            $query->role($request->role_filter);
        }

        return $query->get();
    }
}
