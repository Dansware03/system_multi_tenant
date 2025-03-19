<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantLoginController extends Controller
{
    public function showLoginForm()
    {
        $tenant = app('tenant');
        return view('tenant.auth.login', compact('tenant'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('tenant')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.dashboard', ['tenant' => app('tenant')->slug]));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login', ['tenant' => app('tenant')->slug]);
    }
}
