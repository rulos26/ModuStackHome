@extends('adminlte::page')

@section('title', 'Prueba de Correo')

@section('content_header')
    <h1>Prueba de Envío de Correo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope"></i> Enviar Correo de Prueba
            </h3>
        </div>
        <div class="card-body">
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

            <form action="{{ route('correo.enviar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="destinatario">
                        <i class="fas fa-user"></i> Destinatario <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control @error('destinatario') is-invalid @enderror" 
                           id="destinatario" 
                           name="destinatario" 
                           value="{{ old('destinatario') }}" 
                           placeholder="ejemplo@correo.com" 
                           required>
                    @error('destinatario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Ingresa la dirección de correo electrónico del destinatario.</small>
                </div>

                <div class="form-group">
                    <label for="asunto">
                        <i class="fas fa-tag"></i> Asunto <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('asunto') is-invalid @enderror" 
                           id="asunto" 
                           name="asunto" 
                           value="{{ old('asunto') }}" 
                           placeholder="Asunto del correo" 
                           required>
                    @error('asunto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mensaje">
                        <i class="fas fa-comment"></i> Mensaje <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('mensaje') is-invalid @enderror" 
                              id="mensaje" 
                              name="mensaje" 
                              rows="6" 
                              placeholder="Escribe tu mensaje aquí..." 
                              required>{{ old('mensaje') }}</textarea>
                    @error('mensaje')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Correo
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> Este módulo solo está disponible para usuarios con rol <strong>root</strong>.
            </small>
        </div>
    </div>

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Información de Configuración
            </h3>
        </div>
        <div class="card-body">
            <p><strong>Servidor SMTP:</strong> {{ config('mail.mailers.smtp.host') }}</p>
            <p><strong>Puerto:</strong> {{ config('mail.mailers.smtp.port') }}</p>
            <p><strong>Encriptación:</strong> {{ config('mail.mailers.smtp.encryption') ?? 'No configurada' }}</p>
            <p><strong>Remitente:</strong> {{ config('mail.from.address') }}</p>
            <p><strong>Nombre del Remitente:</strong> {{ config('mail.from.name') }}</p>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header h3 {
            margin: 0;
        }
    </style>
@stop

