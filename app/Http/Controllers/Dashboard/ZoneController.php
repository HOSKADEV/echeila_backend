<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\ZoneDatatable;
use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use App\Support\Enum\Permissions;
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
            'radiusKm' => 'required|numeric|min:0',
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
            'isActive' => 'nullable|boolean',
        ]);

        try {
            $this->firestore->create('zones', [
                'zoneId'    => $data['zoneId'],
                'name'      => $data['name'],
                'type'      => $data['type'],
                'radiusKm'  => (float) $data['radiusKm'],
                'center'    => ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']],
                'isActive'  => isset($data['isActive']) && $data['isActive'],
                'createdAt' => now()->toDateTimeString(),
            ], $data['zoneId']);

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
            'radiusKm' => 'required|numeric|min:0',
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
            'isActive' => 'nullable|boolean',
        ]);

        try {
            $this->firestore->update('zones', $id, [
                'name'      => $data['name'],
                'type'      => $data['type'],
                'radiusKm'  => (float) $data['radiusKm'],
                'center'    => ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']],
                'isActive'  => isset($data['isActive']) && $data['isActive'],
            ]);

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
