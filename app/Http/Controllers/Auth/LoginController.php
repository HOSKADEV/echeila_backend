<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Enum\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('auth.login', ['pageConfigs' => $pageConfigs]);
  }

  public function action(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $user = Auth::user();
      if ($user->hasAnyRole([Roles::ADMIN, Roles::SUPER_ADMIN])) {
        return redirect()->route('dashboard');
      }
      Auth::logout();
      return redirect()->route('login')->with('error', 'Access denied for this account.');
    }
    return redirect()->route('login')->with('error', 'Login details are not valid');
  }
}
