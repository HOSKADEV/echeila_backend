<?php

namespace App\Datatables;

use App\Services\FirestoreService;
use App\Traits\DataTableActionsTrait;
use App\Traits\FirebaseTrait;
use Exception;
use GPBMetadata\Google\Firestore\V1\Firestore;
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
            'radiusKm',
            'center',
            'createdAt',
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
                    return ucfirst($zone['type'] ?? 'N/A');
                })
                ->addColumn('isActive', function ($zone) {
                    $isActive = $zone['isActive'] ?? false;
                    $badgeClass = $isActive ? 'bg-label-success' : 'bg-label-danger';
                    $text = $isActive ? __('zone.active') : __('zone.inactive');
                    return '<span class="badge ' . $badgeClass . '">' . $text . '</span>';
                })
                ->addColumn('radiusKm', function ($zone) {
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
                })
                ->addColumn('createdAt', function ($zone) {
                    if (isset($zone['createdAt'])) {
                        return date('Y-m-d H:i', strtotime($zone['createdAt']));
                    }
                    return 'N/A';
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $firstore = new FirestoreService();

        $filters = [];
        if ($request->has('type')) {
            $filters[] = ['field' => 'type', 'operator' => '==', 'value' => $request->input('type')];
        }
        if ($request->has('isActive')) {
            $filters[] = ['field' => 'isActive', 'operator' => '==', 'value' => boolval($request->input('isActive'))];
        }
        $data = $firstore->get('zones', filters: $filters);
        return collect($data);
    }
}
