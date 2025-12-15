<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatables\SeatPriceDatatable;
use App\Http\Controllers\Controller;
use App\Models\SeatPrice;
use App\Models\Wilaya;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatPriceController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $seatPrices = new SeatPriceDatatable;
        if ($request->wantsJson()) {
            return $seatPrices->datatables($request);
        }

        return view('dashboard.seat-price.list')->with([
            'columns' => $seatPrices::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $wilayas = Wilaya::all();

        return view('dashboard.seat-price.create')->with(['wilayas' => $wilayas]);
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $seatPrice = SeatPrice::findOrFail($id);
        $wilayas = Wilaya::all();

        return view('dashboard.seat-price.edit')->with([
            'seatPrice' => $seatPrice,
            'wilayas' => $wilayas,
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_CREATE)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'starting_wilaya_id' => 'required|exists:wilayas,id',
            'arrival_wilaya_id' => 'required|exists:wilayas,id|different:starting_wilaya_id',
            'default_seat_price' => 'required|numeric|min:0|max:999999.99',
        ]);

        try {
            DB::beginTransaction();

            SeatPrice::create($data);

            DB::commit();

            return redirect()->route('seat-prices.index')->with('success', __('app.created_successfully', ['name' => __('app.seat_price')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'starting_wilaya_id' => 'required|exists:wilayas,id',
            'arrival_wilaya_id' => 'required|exists:wilayas,id|different:starting_wilaya_id',
            'default_seat_price' => 'required|numeric|min:0|max:999999.99',
        ]);

        try {
            DB::beginTransaction();

            $seatPrice = SeatPrice::findOrFail($id);
            $seatPrice->update($data);

            DB::commit();

            return redirect()->route('seat-prices.index')->with('success', __('app.updated_successfully', ['name' => __('app.seat_price')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::SEAT_PRICE_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $seatPrice = SeatPrice::findOrFail($id);
            $seatPrice->delete();

            return redirect()->route('seat-prices.index')->with('success', __('app.deleted_successfully', ['name' => __('app.seat_price')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
