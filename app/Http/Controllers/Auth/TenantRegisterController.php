<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TenantRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $tenant = app('tenant');
        return view('tenant.auth.register', compact('tenant'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenant.users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = TenantUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Configura auth para usar la guardia 'tenant'
        Auth::guard('tenant')->login($user);

        return redirect()->route('tenant.dashboard', ['tenant' => app('tenant')->slug]);
    }
}
