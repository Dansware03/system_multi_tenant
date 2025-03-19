<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'database_name',
        'active'
    ];

    /**
     * Crea la base de datos para el tenant
     */
    public function createDatabase()
    {
        $databasePath = storage_path('app/tenants/' . $this->database_name . '.sqlite');
        
        // Crear archivo SQLite
        if (!File::exists($databasePath)) {
            File::put($databasePath, '');
        }
        
        // Configurar conexión
        config([
            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'url' => null,
                'database' => $databasePath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]
        ]);

        // Ejecutar migraciones en la base de datos del tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    /**
     * Configura la conexión a la base de datos del tenant
     */
    public function configureTenantConnection()
    {
        $databasePath = storage_path('app/tenants/' . $this->database_name . '.sqlite');
        
        // Configurar conexión
        config([
            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'url' => null,
                'database' => $databasePath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]
        ]);

        // Reconectar con la nueva configuración
        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
