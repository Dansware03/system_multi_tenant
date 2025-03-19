<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        return view('tenant.dashboard', compact('tenant'));
    }
}
