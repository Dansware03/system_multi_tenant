@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                    <div class="card-body">
                        <h1>Laravel Multi-Tenant</h1>
                        <p class="lead">Aplicación multi-tenant basada en slugs</p>

                        <div class="mt-4">
                            <a href="{{ route('tenant.register') }}" class="btn btn-primary">Registrar mi empresa</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection