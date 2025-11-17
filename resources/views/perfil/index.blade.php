@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <h1>
        <i class="fas fa-user"></i> Mi Perfil
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
            <h3 class="card-title">Datos del Perfil</h3>
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

            <form action="{{ route('perfil.update') }}" method="POST" id="perfilForm" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="image">
                        <i class="fas fa-image"></i> Foto de Perfil
                    </label>
                    <div class="mb-2">
                        @if ($usuario->image)
                            <img src="{{ asset($usuario->image) }}" 
                                 alt="Foto de perfil" 
                                 class="img-thumbnail" 
                                 style="max-width: 150px; max-height: 150px;">
                        @else
                            <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" 
                                 alt="Foto por defecto" 
                                 class="img-thumbnail" 
                                 style="max-width: 150px; max-height: 150px;">
                        @endif
                    </div>
                    <input type="file" 
                           class="form-control-file @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Formatos permitidos: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB.</small>
                </div>

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
                           minlength="3"
                           maxlength="255"
                           pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="nameError"></div>
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
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Nueva Contraseña
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Dejar en blanco para mantener la actual"
                               minlength="8">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: 0;">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Dejar en blanco si no deseas cambiar la contraseña. Mínimo 8 caracteres si se cambia.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i> Confirmar Nueva Contraseña
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Repite la nueva contraseña"
                               minlength="8">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation" style="border-left: 0;">
                                <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Toggle para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Toggle para mostrar/ocultar confirmación de contraseña
        document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const toggleIcon = document.getElementById('togglePasswordConfirmationIcon');
            
            if (passwordConfirmationInput.type === 'password') {
                passwordConfirmationInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordConfirmationInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Validación en tiempo real
        const form = document.getElementById('perfilForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');

        // Validar nombre
        nameInput.addEventListener('input', function() {
            const name = this.value.trim();
            const nameError = document.getElementById('nameError');
            
            if (name.length < 3) {
                this.setCustomValidity('El nombre debe tener al menos 3 caracteres.');
                this.classList.add('is-invalid');
                nameError.textContent = 'El nombre debe tener al menos 3 caracteres.';
            } else if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(name)) {
                this.setCustomValidity('El nombre solo puede contener letras y espacios.');
                this.classList.add('is-invalid');
                nameError.textContent = 'El nombre solo puede contener letras y espacios.';
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                nameError.textContent = '';
            }
        });

        // Validar email
        emailInput.addEventListener('input', function() {
            const email = this.value.trim();
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                this.setCustomValidity('Por favor ingresa un correo electrónico válido.');
                this.classList.add('is-invalid');
                emailError.textContent = 'Por favor ingresa un correo electrónico válido.';
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                emailError.textContent = '';
            }
        });

        // Validar contraseña (solo si se ingresa)
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0 && password.length < 8) {
                this.setCustomValidity('La contraseña debe tener al menos 8 caracteres.');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
            
            // Validar confirmación si ya tiene valor
            if (passwordConfirmationInput.value.length > 0) {
                passwordConfirmationInput.dispatchEvent(new Event('input'));
            }
        });

        // Validar confirmación de contraseña (solo si se ingresa)
        passwordConfirmationInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmation = this.value;
            
            if (confirmation.length > 0 && password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden.');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });

        // Validación al enviar el formulario
        form.addEventListener('submit', function(event) {
            // Validar contraseña solo si se ingresó
            if (passwordInput.value.length > 0) {
                if (passwordInput.value.length < 8) {
                    event.preventDefault();
                    event.stopPropagation();
                    passwordInput.classList.add('is-invalid');
                }
                
                if (passwordInput.value !== passwordConfirmationInput.value) {
                    event.preventDefault();
                    event.stopPropagation();
                    passwordConfirmationInput.classList.add('is-invalid');
                }
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    </script>
@stop

