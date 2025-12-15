<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Models\User;
use App\Models\Passenger;
use App\Constants\UserType;
use Illuminate\Http\Request;
use App\Support\Enum\Permissions;
use App\Http\Controllers\Controller;
use App\Datatables\PassengerDatatable;

class PassengerController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::PASSENGER_INDEX)) {
      return redirect()->route('unauthorized');
    }

    // Calculate statistics
    $stats = [
      'total' => User::passengers()->count(),
      'active' => User::passengers()->where('status', 'active')->count(),
      'banned' => User::passengers()->where('status', 'banned')->count(),
      'new' => User::passengers()->where('created_at', '>=', now()->subDays(7))->count(),
    ];

    $passengers = new PassengerDatatable();
    if ($request->wantsJson()) {
      return $passengers->datatables($request);
    }
    return view("dashboard.passenger.list")->with([
      "columns" => $passengers::columns(),
      "stats" => $stats,
    ]);
  }

  public function show($id)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::PASSENGER_SHOW)) {
      return redirect()->route('unauthorized');
    }

    $passenger = Passenger::with(['user.wallet.transactions', 'tripClients', 'reviewsReceived', 'reviewsGiven', 'cargos', 'lostAndFounds'])->where('user_id', $id)->first();

$stats = [
    'trips_count' => $passenger->tripClients()->count(),
    'reviews_count' => $passenger->reviewsReceived()->count(),
    'avg_rating' => $passenger->reviewsReceived()->avg('rating') ?? 0,
    'cargos_count' => $passenger->cargos()->count(),
    'lost_and_founds_count' => $passenger->lostAndFounds()->count(),
    'total_spent' => $passenger->tripClients()->sum('total_fees'),
];

$transactions = $passenger->user->wallet->transactions()->paginate(15);
$recentTrips = $passenger->trips()->with('driver')->latest()->paginate(10);
$reviews = $passenger->reviewsReceived()->with(['reviewer'])->paginate(5);

return view('dashboard.passenger.show', compact('passenger', 'stats', 'transactions', 'recentTrips', 'reviews'));
  }

}
