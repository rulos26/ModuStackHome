@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1>
        <i class="fas fa-users"></i> Gestión de Usuarios
    </h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> Lista de Usuarios
            </h3>
            @can('create', App\Models\User::class)
                <div class="card-tools">
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @foreach ($usuario->roles as $rol)
                                    <span class="badge badge-{{ $rol->name === 'root' ? 'danger' : ($rol->name === 'admin' ? 'warning' : 'info') }}">
                                        {{ ucfirst($rol->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @can('view', $usuario)
                                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $usuario)
                                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $usuario)
                                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <p class="text-muted">No hay usuarios registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($usuarios->hasPages())
            <div class="card-footer">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin-right: 2px;
        }
    </style>
@stop

