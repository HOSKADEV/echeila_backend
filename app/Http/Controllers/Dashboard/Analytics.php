<?php

namespace App\Http\Controllers\Dashboard;

class Analytics extends Controller
{
  public function index()
  {
    return view('dashboard.dashboard-analytics');
  }
}
