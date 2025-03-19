
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Multi-Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="antialiased">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1>Laravel Multi-Tenant</h1>
                <p class="lead">Aplicación multi-tenant basada en slugs</p>
                
                <div class="mt-4">
                    <a href="{{ route('tenant.register') }}" class="btn btn-primary">Registrar mi empresa</a>
                </div>
            </div>
            <div class="text-center">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </div>
</body>
</html>