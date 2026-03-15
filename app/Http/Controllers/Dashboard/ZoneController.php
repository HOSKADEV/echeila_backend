<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\ZoneDatatable;
use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function __construct(private FirestoreService $firestore) {}

    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_INDEX)) {
            return redirect()->route('unauthorized');
        }

        $zones = new ZoneDatatable;

        if ($request->wantsJson()) {
            return $zones->datatables($request);
        }

        return view('dashboard.zone.list')->with([
            'columns' => $zones::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_CREATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.zone.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'zoneId'   => 'required|string|max:100|regex:/^[a-z0-9_]+$/',
            'name'     => 'required|string|max:255',
            'type'     => 'required|string|in:circle,polygon',
            'radiusKm' => 'nullable|required_if:type,circle|numeric|min:0',
            'lat'      => 'nullable|required_if:type,circle|numeric|between:-90,90',
            'lng'      => 'nullable|required_if:type,circle|numeric|between:-180,180',
            'points_json' => 'nullable|required_if:type,polygon|string',
            'isActive' => 'nullable|boolean',
        ]);

        if ($data['type'] === 'polygon') {
            $decoded = json_decode($data['points_json'] ?? '[]', true);

            if (!is_array($decoded) || count($decoded) < 3) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['points_json' => __('zone.polygon_points_min')]);
            }

            $validator = Validator::make(['corners' => $decoded], [
                'corners' => 'required|array|min:3',
                'corners.*.lat' => 'required|numeric|between:-90,90',
                'corners.*.lng' => 'required|numeric|between:-180,180',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
        }

        try {
            $payload = [
                'zoneId'    => $data['zoneId'],
                'name'      => $data['name'],
                'type'      => $data['type'],
                'isActive'  => isset($data['isActive']) && $data['isActive'],
                'createdAt' => now()->toDateTimeString(),
            ];

            if ($data['type'] === 'circle') {
                $payload['radiusKm'] = (float) $data['radiusKm'];
                $payload['center'] = ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']];
                $payload['corners'] = [];
            } else {
                $payload['radiusKm'] = null;
                $payload['center'] = null;
                $payload['corners'] = json_decode($data['points_json'], true);
            }

            $this->firestore->create('zones', $payload, $data['zoneId']);

            return redirect()->route('zones.index')
                ->with('success', __('app.created_successfully', ['name' => __('zone.zone')]));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $zone = $this->firestore->get('zones', $id);

        if (! $zone) {
            abort(404);
        }

        return view('dashboard.zone.edit', compact('zone'));
    }

    public function update(Request $request, string $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|string|in:circle,polygon',
            'radiusKm' => 'nullable|required_if:type,circle|numeric|min:0',
            'lat'      => 'nullable|required_if:type,circle|numeric|between:-90,90',
            'lng'      => 'nullable|required_if:type,circle|numeric|between:-180,180',
            'points_json' => 'nullable|required_if:type,polygon|string',
            'isActive' => 'nullable|boolean',
        ]);

        if ($data['type'] === 'polygon') {
            $decoded = json_decode($data['points_json'] ?? '[]', true);

            if (!is_array($decoded) || count($decoded) < 3) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['points_json' => __('zone.polygon_points_min')]);
            }

            $validator = Validator::make(['corners' => $decoded], [
                'corners' => 'required|array|min:3',
                'corners.*.lat' => 'required|numeric|between:-90,90',
                'corners.*.lng' => 'required|numeric|between:-180,180',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
        }

        try {
            $payload = [
                'name'      => $data['name'],
                'type'      => $data['type'],
                'isActive'  => isset($data['isActive']) && $data['isActive'],
            ];

            if ($data['type'] === 'circle') {
                $payload['radiusKm'] = (float) $data['radiusKm'];
                $payload['center'] = ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']];
                $payload['corners'] = [];
            } else {
                $payload['radiusKm'] = null;
                $payload['center'] = null;
                $payload['corners'] = json_decode($data['points_json'], true);
            }

            $this->firestore->update('zones', $id, $payload);

            return redirect()->route('zones.index')
                ->with('success', __('app.updated_successfully', ['name' => __('zone.zone')]));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ZONE_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $this->firestore->delete('zones', $id);

            return redirect()->route('zones.index')
                ->with('success', __('app.deleted_successfully', ['name' => __('zone.zone')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
