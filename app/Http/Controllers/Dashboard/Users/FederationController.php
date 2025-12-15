<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Models\Trip;
use App\Models\User;
use App\Models\Driver;
use App\Models\Federation;
use App\Models\TripReview;
use App\Constants\UserType;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Constants\UserStatus;
use App\Support\Enum\Permissions;
use App\Http\Controllers\Controller;
use App\Datatables\FederationDatatable;

class FederationController extends Controller
{

  use ImageUpload;
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::FEDERATION_INDEX)) {
      return redirect()->route('unauthorized');
    }

    // Calculate statistics
    $stats = [
      'total' => User::federations()->count(),
      'active' => User::federations()->where('status', 'active')->count(),
      'banned' => User::federations()->where('status', 'banned')->count(),
      'new' => User::federations()->where('created_at', '>=', now()->subDays(7))->count(),
    ];

    $federations = new FederationDatatable();
    if ($request->wantsJson()) {
      return $federations->datatables($request);
    }
    return view("dashboard.federation.list")->with([
      "columns" => $federations::columns(),
      "stats" => $stats,
    ]);
  }

  public function show($id)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::FEDERATION_SHOW)) {
      return redirect()->route('unauthorized');
    }
    
    // Find the federation
    $federation = Federation::with(['user', 'drivers.user'])->where('user_id', $id)->firstOrFail();
    
    // Get paginated drivers
    $drivers = $federation->drivers()->with('user')->paginate(10);

    $availableDrivers = Driver::whereNull('federation_id')->with('user')->get();
    
    // Calculate statistics
    $driverIds = $federation->drivers()->pluck('id');
    
    $stats = [
      'total_drivers' => $federation->drivers()->count(),
      'active_drivers' => $federation->drivers()->whereHas('user', function($query) {
        $query->where('status', UserStatus::ACTIVE);
      })->count(),
      'banned_drivers' => $federation->drivers()->whereHas('user', function($query) {
        $query->where('status', UserStatus::BANNED);
      })->count(),
      'total_trips' => Trip::whereIn('driver_id', $driverIds)->count(),
      'avg_rating' => TripReview::whereHas('trip', function($query) use ($driverIds) {
        $query->whereIn('driver_id', $driverIds);
      })->avg('rating') ?? 0,
    ];
    
    return view('dashboard.federation.show', compact('federation', 'drivers', 'availableDrivers', 'stats'));
  }

    public function create()
    {
      if (!auth()->user()->hasPermissionTo(Permissions::FEDERATION_CREATE)) {
        return redirect()->route('unauthorized');
      }
      $users = User::passengers()->whereDoesntHave('federation')->get();
      return view('dashboard.federation.create', compact('users'));
    }

    public function store(Request $request)
    {
      if (!auth()->user()->hasPermissionTo(Permissions::FEDERATION_CREATE)) {
        return redirect()->route('unauthorized');
      }
      $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'creation_date' => 'required|date',
        'image' => 'nullable|image|max:2048',
      ]);

      $federation = Federation::create([
        'user_id' => $validated['user_id'],
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'creation_date' => $validated['creation_date'],
      ]);

      if ($request->hasFile('image')) {
        $this->uploadImageFromRequest($federation, $request, 'image', Federation::IMAGE);
      }

      return redirect()->route('federations.index')->with('success', 'Federation created successfully.');
    }
}
