@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>
        <i class="fas fa-user-edit"></i> Editar Usuario
    </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Datos del Usuario</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $usuario->name) }}" 
                           placeholder="Ingrese el nombre completo" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correo Electrónico <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $usuario->email) }}" 
                           placeholder="usuario@ejemplo.com" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Nueva Contraseña
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Dejar en blanco para mantener la actual">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Dejar en blanco si no deseas cambiar la contraseña. Mínimo 8 caracteres si se cambia.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i> Confirmar Nueva Contraseña
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Repite la nueva contraseña">
                </div>

                @if (auth()->user()->hasAnyRole(['root', 'admin']))
                    <div class="form-group">
                        <label for="role">
                            <i class="fas fa-user-tag"></i> Rol
                        </label>
                        <select class="form-control @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role">
                            <option value="">Seleccione un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" 
                                        {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                    {{ ucfirst($rol->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Solo root y admin pueden cambiar roles.</small>
                    </div>
                @endif

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

