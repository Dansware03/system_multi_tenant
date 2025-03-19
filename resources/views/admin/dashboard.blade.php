@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <h4>Bienvenido al Panel de Administración</h4>
                    <p>Selecciona una opción del menú para comenzar a gestionar tu sistema.</p>

                    <div class="row mt-4">
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Usuarios</h5>
                                            <h3 class="card-text">{{ App\Models\User::count() }}</h3>
                                        </div>
                                        <i class="fas fa-users fa-3x"></i>
                                    </div>
                                    <a href="{{ route('users.index') }}" class="btn btn-light mt-3">Gestionar Usuarios</a>
                                </div>
                            </div>
                        </div>

                        <!-- Puedes agregar más tarjetas con estadísticas aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
