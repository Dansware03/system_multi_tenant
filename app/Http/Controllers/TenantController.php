<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function showRegistrationForm()
    {
        return view('tenant.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:tenants',
            'email' => 'required|string|email|max:255|unique:tenants',
        ]);
        
        $tenant = Tenant::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'email' => $request->email,
            'database_name' => Str::slug($request->slug) . '_' . Str::random(5),
            'active' => true,
        ]);
        
        // Crear la base de datos para el tenant
        $tenant->createDatabase();
        
        return redirect()->route('tenant.login', ['tenant' => $tenant->slug])
            ->with('success', 'Tu cuenta ha sido creada. Ahora puedes iniciar sesión.');
    }
}
