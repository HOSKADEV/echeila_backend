<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use App\Support\Enum\Permissions;
use App\Datatables\AdminActionDatatable;
use App\Http\Controllers\Controller;

class AdminActionController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(Permissions::ADMIN_ACTION_INDEX)) {
            return redirect()->route('unauthorized');
        }

        // Calculate statistics
        $stats = [
            'total' => AdminAction::count(),
            'today' => AdminAction::whereDate('created_at', today())->count(),
            'this_week' => AdminAction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AdminAction::whereMonth('created_at', now()->month)->count(),
        ];

        // Get all admins for filter
        $admins = Admin::orderBy('firstname')->get();

        $adminActions = new AdminActionDatatable();
        if ($request->wantsJson()) {
            return $adminActions->datatables($request);
        }
        
        return view("dashboard.admin-action.list")->with([
            "columns" => $adminActions::columns(),
            "stats" => $stats,
            "admins" => $admins,
        ]);
    }
}
