<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('tenant');

        if (!$slug) {
            return redirect('/');
        }

        $tenant = Tenant::where('slug', $slug)
                    ->where('active', true)
                    ->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Almacenar el tenant en el contenedor de servicios
        app()->instance('tenant', $tenant);

        // Configurar la conexión a la base de datos del tenant
        $tenant->configureTenantConnection();

        return $next($request);
    }
}
