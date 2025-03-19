@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Detalles del Usuario') }}</h5>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="avatar-circle mx-auto">
                    <span class="avatar-text">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="mt-3">
                    @if($user->role == 'root')
                        <span class="badge bg-danger">Root</span>
                    @elseif($user->role == 'admin')
                        <span class="badge bg-warning">Admin</span>
                    @else
                        <span class="badge bg-info">Usuario</span>
                    @endif
                </div>
            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">ID:</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Nombre:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Correo Electrónico:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Rol:</th>
                                <td>{{ ucfirst($user->role) }}</td>
                            </tr>
                            <tr>
                                <th>Fecha de Registro:</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Última Actualización:</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($user->role != 'root' && $user->id != Auth::id())
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-userid="{{ $user->id }}"
                            data-username="{{ $user->name }}">
                        <i class="fas fa-trash"></i> Eliminar Usuario
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar al usuario <strong>{{ $user->name }}</strong>?
                <br>Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #007bff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .avatar-text {
        color: white;
        font-size: 40px;
        font-weight: bold;
    }
</style>
@endsection
