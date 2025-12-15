<?php

namespace App\Http\Controllers\Dashboard;

use Auth;
use Session;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  public function index()
  {
    return view('dashboard.profile.index')->with([
      'user' => Admin::where('id', auth()->id())->first(),
    ]);
  }

  public function store(Request $request)
  {
    try {
      $user = Admin::where('id', auth()->id())->first();
      $data = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:users,phone,' . $user->id,
        //'birthdate' => 'nullable|date|before:today',
        //'full_address' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
      ]);

      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), '/uploads/users/avatars');
      }
      $user->update($data);
      return redirect()->route('profile.index')->with('success', __('app.updated_successfully', ['name' => __('app.profile')]));
    }catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function updatePassword(Request $request)
  {
    try {
      $user = Admin::where('id', auth()->id())->first();
      $data = $request->validate([
        'old_password' => 'required|string|min:8',
        'password' => 'required|string|min:8|confirmed',
      ]);

      if (!Hash::check($data['old_password'], $user->password)) {
        return redirect()->back()->with('error', __('app.old_password_incorrect'));
      }

      $user->update([
        'password' => bcrypt($data['password']),
      ]);

      auth()->login($user);
      auth()->logoutOtherDevices($data['password']);

      return redirect()->route('logout')->with('success', __('app.updated_successfully', ['name' => __('app.password')]));
    }catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
