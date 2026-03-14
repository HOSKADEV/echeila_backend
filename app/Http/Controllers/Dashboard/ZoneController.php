<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\ZoneDatatable;
use App\Http\Controllers\Controller;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
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
}
