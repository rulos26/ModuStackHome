@extends('adminlte::page')

@section('title', 'Ver Usuario')

@section('content_header')
    <h1>
        <i class="fas fa-user"></i> Detalles del Usuario
    </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Usuario</h3>
            <div class="card-tools">
                @can('update', $usuario)
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endcan
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $usuario->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $usuario->name }}</td>
                        </tr>
                        <tr>
                            <th>Correo Electrónico:</th>
                            <td>{{ $usuario->email }}</td>
                        </tr>
                        <tr>
                            <th>Rol(es):</th>
                            <td>
                                @foreach ($usuario->roles as $rol)
                                    <span class="badge badge-{{ $rol->name === 'root' ? 'danger' : ($rol->name === 'admin' ? 'warning' : 'info') }}">
                                        {{ ucfirst($rol->name) }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Registro:</th>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $usuario->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @can('delete', $usuario)
            <div class="card-footer">
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Usuario
                    </button>
                </form>
            </div>
        @endcan
    </div>
@stop

