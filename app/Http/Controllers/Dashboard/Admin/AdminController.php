<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Datatables\AdminDatatable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Support\Enum\Permissions;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use ImageUpload;

    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_INDEX)) {
            return redirect()->route('unauthorized');
        }
        $admins = new AdminDatatable;
        if ($request->wantsJson()) {
            return $admins->datatables($request);
        }

        return view('dashboard.admin.list')->with([
            'columns' => $admins::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_CREATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.admin.create');
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_UPDATE)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.admin.edit')->with(['admin' => Admin::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_CREATE)) {
            return redirect()->route('unauthorized');
        }
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            // 'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:admins,phone',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            $data['password'] = Hash::make($data['password']);
            $admin = Admin::create($data);

            if ($request->role) {
                $admin->assignRole($request->role);
            }

            if ($request->hasFile('avatar')) {
                $this->uploadImageFromRequest($admin, $request, 'avatar');
            }

            DB::commit();

            return redirect()->route('admins.index')->with('success', __('app.created_successfully', ['name' => __('app.admin')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_UPDATE)) {
            return redirect()->route('unauthorized');
        }
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$id,
            'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        try {
            DB::beginTransaction();
            $admin = Admin::findOrFail($id);
            if ($request->hasFile('avatar')) {
                $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/admins/avatars');
            }
            $admin->update($data);

            if ($request->role) {
                $admin->syncRoles([$request->role]);
            }

            if ($request->hasFile('avatar')) {
                $admin->clearMediaCollection('image');
                $this->uploadImageFromRequest($admin, $request, 'avatar');
            }

            DB::commit();

            return redirect()->route('admins.index')->with('success', __('app.updated_successfully', ['name' => __('app.admin')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::ADMIN_DELETE)) {
            return redirect()->route('unauthorized');
        }

        try {
            $admin = Admin::findOrFail($id);
            $admin->syncRoles([]);
            $admin->delete();

            return redirect()->route('admins.index')->with('success', __('app.deleted_successfully', ['name' => __('app.admin')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
