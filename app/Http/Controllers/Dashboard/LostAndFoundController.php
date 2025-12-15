<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Datatables\LostAndFoundDatatable;
use App\Models\LostAndFound;
use App\Models\Passenger;
use App\Constants\LostAndFoundStatus;
use App\Support\Enum\Permissions;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LostAndFoundController extends Controller
{
    use ImageUpload;

    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_INDEX)) {
            return redirect()->route('unauthorized');
        }
        
        $lostAndFounds = new LostAndFoundDatatable;
        if ($request->wantsJson()) {
            return $lostAndFounds->datatables($request);
        }

        return view('dashboard.lost-and-found.list')->with([
            'columns' => $lostAndFounds::columns(),
            'statuses' => LostAndFoundStatus::all2(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_INDEX)) {
            return redirect()->route('unauthorized');
        }
        
        return view('dashboard.lost-and-found.create')->with([
            'passengers' => Passenger::all(),
            'statuses' => LostAndFoundStatus::all2(),
        ]);
    }

    public function show($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_SHOW)) {
            return redirect()->route('unauthorized');
        }
        
        $lostAndFound = LostAndFound::with(['passenger.user', 'passenger.lostAndFounds'])->findOrFail($id);
        
        return view('dashboard.lost-and-found.show')->with([
            'lostAndFound' => $lostAndFound,
        ]);
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_UPDATE)) {
            return redirect()->route('unauthorized');
        }
        
        return view('dashboard.lost-and-found.edit')->with([
            'lostAndFound' => LostAndFound::findOrFail($id),
            'passengers' => Passenger::all(),
            'statuses' => LostAndFoundStatus::all2(),
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_INDEX)) {
            return redirect()->route('unauthorized');
        }
        
        $data = $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
            'description' => 'required|string',
            'status' => 'required|in:' . implode(',', LostAndFoundStatus::all()),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
        ]);

        try {
            DB::beginTransaction();

            $lostAndFound = LostAndFound::create($data);

            if ($request->hasFile('image')) {
                $this->uploadImageFromRequest($lostAndFound, $request, 'image');
            }

            DB::commit();

            return redirect()->route('lost-and-founds.index')
                ->with('success', __('app.created_successfully', ['name' => __('app.lost_and_found')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_UPDATE)) {
            return redirect()->route('unauthorized');
        }
        
        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'required|in:' . implode(',', LostAndFoundStatus::all()),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
        ]);

        try {
            DB::beginTransaction();

            $lostAndFound = LostAndFound::findOrFail($id);
            $lostAndFound->update($data);

            if ($request->hasFile('image')) {
                $lostAndFound->clearMediaCollection(LostAndFound::IMAGE);
                $this->uploadImageFromRequest($lostAndFound, $request, 'image');
            }

            DB::commit();

            return redirect()->route('lost-and-founds.index')
                ->with('success', __('app.updated_successfully', ['name' => __('app.lost_and_found')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_CHANGE_STATUS)) {
            return redirect()->route('unauthorized');
        }
        
        $data = $request->validate([
            'id' => 'required|exists:lost_and_founds,id',
            'status' => 'required|in:' . implode(',', LostAndFoundStatus::all()),
        ]);

        try {
            $lostAndFound = LostAndFound::findOrFail($data['id']);
            $lostAndFound->update(['status' => $data['status']]);

            return redirect()->route('lost-and-founds.index')
                ->with('success', __('app.status_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::LOST_AND_FOUND_DELETE)) {
            return redirect()->route('unauthorized');
        }
        
        try {
            $lostAndFound = LostAndFound::findOrFail($id);
            $lostAndFound->delete();

            return redirect()->route('lost-and-founds.index')
                ->with('success', __('app.deleted_successfully', ['name' => __('app.lost_and_found')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}