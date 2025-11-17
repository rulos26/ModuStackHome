@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content_header')
    <h1>Panel de Control</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bienvenido</h3>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <p class="lead">{{ __('¡Has iniciado sesión!') }}</p>
        </div>
    </div>
@stop
