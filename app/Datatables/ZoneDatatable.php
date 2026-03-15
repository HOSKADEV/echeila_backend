<?php

namespace App\Datatables;

use App\Services\FirestoreService;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ZoneDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'name',
            'type',
            'isActive',
            //'radiusKm',
            //'center',
            'createdAt',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('name', function ($zone) {
                    return $this->bold($zone['name'] ?? 'N/A');
                })
                ->addColumn('type', function ($zone) {
                    return match ($zone['type'] ?? '') {
                        'circle' => $this->badge(__('zone.circle'), 'blue', 'bx bx-shape-circle'),
                        'polygon' => $this->badge(__('zone.polygon'), 'purple', 'bx bx-shape-polygon'),
                        default => $this->badge('N/A', 'secondary'),
                    };
                })
                ->addColumn('isActive', function ($zone) {
                    $isActive = $zone['isActive'] ?? false;
                    $badgeClass = $isActive ? 'bg-label-success' : 'bg-label-danger';
                    $text = $isActive ? __('zone.active') : __('zone.inactive');
                    return '<span class="badge ' . $badgeClass . '">' . $text . '</span>';
                })
                /* ->addColumn('radiusKm', function ($zone) {
                    return ($zone['radiusKm'] ?? 0) . ' km';
                })
                ->addColumn('center', function ($zone) {
                    if (isset($zone['center']['lat']) && isset($zone['center']['lng'])) {
                        $lat = $zone['center']['lat'];
                        $lng = $zone['center']['lng'];
                        $coords = $lat . ', ' . $lng;
                        $mapsUrl = 'https://www.google.com/maps?q=' . $lat . ',' . $lng;
                        return '<a href="' . $mapsUrl . '" target="_blank" class="text-primary"><i class="bx bx-map me-1"></i>' . $coords . '</a>';
                    }
                    return 'N/A';
                }) */
                ->addColumn('createdAt', function ($zone) {
                    if (isset($zone['createdAt'])) {
                        return date('Y-m-d H:i', strtotime($zone['createdAt']));
                    }
                    return 'N/A';
                })
                ->addColumn('actions', function ($zone) {
                    return $this
                        ->edit(
                            route('zones.edit', $zone['id']),
                            Auth::user()->hasPermissionTo(Permissions::ZONE_UPDATE)
                        )
                        ->delete(
                            $zone['id'],
                            Auth::user()->hasPermissionTo(Permissions::ZONE_DELETE)
                        )
                        ->makeLabelledIcons();
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $firestore = new FirestoreService();

        $filters = [];
        if (in_array($request->input('type'), ['circle', 'polygon'])) {
            $filters[] = ['field' => 'type', 'operator' => '==', 'value' => $request->input('type')];
        }
        if (in_array($request->input('isActive'), ['0', '1'])) {
            $filters[] = ['field' => 'isActive', 'operator' => '==', 'value' => boolval($request->input('isActive'))];
        }

        return collect($firestore->get('zones', filters: $filters))->sortByDesc('createdAt');
    }
}

