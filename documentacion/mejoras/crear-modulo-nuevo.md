# Guía: Crear un Módulo Nuevo en ModuStackHome

Esta guía explica paso a paso cómo crear un nuevo módulo en la aplicación ModuStackHome, siguiendo las mejores prácticas y la estructura establecida.

## Estructura de un Módulo

Un módulo completo en ModuStackHome debe incluir:

1. **Controlador** (`app/Http/Controllers/`)
2. **Vista(s)** (`resources/views/`)
3. **Rutas** (`routes/web.php`)
4. **Menú** (opcional, en `config/adminlte.php`)
5. **Middleware** (si requiere protección por roles/permisos)

## Paso 1: Crear el Controlador

### Ubicación
`app/Http/Controllers/[NombreModulo]Controller.php`

### Ejemplo: Módulo de Correo

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CorreoController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:root'); // Opcional: restricción por rol
    }

    /**
     * Mostrar el formulario
     */
    public function index()
    {
        return view('correo.prueba');
    }

    /**
     * Procesar el formulario
     */
    public function enviar(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'campo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Lógica del módulo
        try {
            // Tu código aquí
            
            return redirect()->back()
                ->with('success', 'Operación exitosa');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }
}
```

### Características Importantes:

- **Namespace**: `App\Http\Controllers`
- **Middleware**: Agregar `auth` para usuarios autenticados
- **Middleware de roles**: Usar `role:nombre_rol` para restricción por rol
- **Validación**: Usar `Validator` de Laravel
- **Manejo de errores**: Usar try-catch con mensajes claros

## Paso 2: Crear la Vista

### Ubicación
`resources/views/[nombre-modulo]/[nombre-vista].blade.php`

### Estructura Básica con AdminLTE

```blade
@extends('adminlte::page')

@section('title', 'Título del Módulo')

@section('content_header')
    <h1>Título del Módulo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-icono"></i> Subtítulo
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
                    <i class="fas fa-exclamation-triangle"></i> <strong>Errores:</strong>
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

            <!-- Contenido del módulo aquí -->
            
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estilos personalizados si es necesario */
    </style>
@stop

@section('js')
    <script>
        // JavaScript personalizado si es necesario
    </script>
@stop
```

### Puntos Clave:

- **Extensión**: Siempre usar `@extends('adminlte::page')` para mantener consistencia
- **Secciones**: Usar `@section('content_header')` y `@section('content')`
- **Mensajes**: Incluir manejo de mensajes de éxito y error
- **Iconos**: Usar Font Awesome (ya incluido en AdminLTE)
- **Alertas**: Usar clases de Bootstrap para alertas

## Paso 3: Definir las Rutas

### Ubicación
`routes/web.php`

### Ejemplo de Rutas Protegidas por Rol

```php
// Rutas del módulo (solo para rol root)
Route::middleware(['auth', 'role:root'])->prefix('nombre-modulo')->name('nombre-modulo.')->group(function () {
    Route::get('/ruta', [App\Http\Controllers\NombreController::class, 'metodo'])->name('ruta');
    Route::post('/procesar', [App\Http\Controllers\NombreController::class, 'procesar'])->name('procesar');
});
```

### Opciones de Middleware:

- **Solo autenticación**: `Route::middleware(['auth'])`
- **Por rol**: `Route::middleware(['auth', 'role:nombre_rol'])`
- **Por permiso**: `Route::middleware(['auth', 'permission:nombre_permiso'])`
- **Rol o permiso**: `Route::middleware(['auth', 'role_or_permission:rol|permiso'])`

### Convenciones de Nombres:

- **Prefijo**: Nombre del módulo en singular (ej: `correo`, `usuario`)
- **Nombre de ruta**: Usar punto para agrupar (ej: `correo.prueba`, `correo.enviar`)
- **URLs**: Usar guiones (ej: `/correo/prueba`, `/correo/enviar`)

## Paso 4: Agregar al Menú (Opcional)

### Ubicación
`config/adminlte.php` - Sección `'menu'`

### Ejemplo

```php
'menu' => [
    // ... otros elementos del menú ...
    
    [
        'text' => 'Nombre del Módulo',
        'url' => 'nombre-modulo/ruta',
        'icon' => 'fas fa-fw fa-icono',
        'can' => 'role:root', // Opcional: restricción por rol
    ],
],
```

### Opciones de Restricción en el Menú:

- **Por rol**: `'can' => 'role:nombre_rol'`
- **Por permiso**: `'can' => 'permission:nombre_permiso'`
- **Sin restricción**: Omitir la clave `'can'`

### Iconos Disponibles:

Usar Font Awesome. Ejemplos:
- `fas fa-fw fa-home` - Inicio
- `fas fa-fw fa-envelope` - Correo
- `fas fa-fw fa-user` - Usuario
- `fas fa-fw fa-cog` - Configuración
- `fas fa-fw fa-list` - Lista

Ver más iconos en: https://fontawesome.com/icons

## Paso 5: Registrar Middleware (Si es Necesario)

### Ubicación
`bootstrap/app.php`

Si necesitas usar middleware de roles/permisos, asegúrate de que estén registrados:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
})
```

## Estructura de Carpetas Recomendada

```
app/
├── Http/
│   └── Controllers/
│       └── [NombreModulo]Controller.php

resources/
└── views/
    └── [nombre-modulo]/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php

routes/
└── web.php (agregar rutas aquí)

config/
└── adminlte.php (agregar menú aquí)
```

## Buenas Prácticas

### 1. Nomenclatura

- **Controladores**: PascalCase con sufijo `Controller` (ej: `CorreoController`)
- **Vistas**: snake_case (ej: `prueba.blade.php`)
- **Rutas**: kebab-case (ej: `/correo/prueba`)
- **Nombres de ruta**: dot notation (ej: `correo.prueba`)

### 2. Validación

- Siempre validar datos de entrada
- Usar mensajes de error en español
- Mostrar errores de forma clara al usuario

### 3. Seguridad

- Proteger rutas con middleware apropiado
- Validar permisos en el controlador
- Usar CSRF tokens en formularios (automático con `@csrf`)

### 4. Manejo de Errores

- Usar try-catch para operaciones que pueden fallar
- Proporcionar mensajes de error descriptivos
- Registrar errores cuando sea necesario

### 5. Mensajes al Usuario

- Usar mensajes de éxito/error consistentes
- Mostrar mensajes en español
- Usar iconos para mejorar la UX

## Ejemplo Completo: Módulo de Prueba de Correo

Ver el módulo completo de ejemplo en:
- Controlador: `app/Http/Controllers/CorreoController.php`
- Vista: `resources/views/correo/prueba.blade.php`
- Rutas: `routes/web.php` (líneas 18-21)
- Menú: `config/adminlte.php` (líneas 318-323)
- Documentación: `documentacion/modulos/modulo-prueba-correo.md`

## Verificación

Después de crear el módulo, verifica:

1. ✅ El controlador existe y tiene los métodos necesarios
2. ✅ Las vistas existen y extienden `adminlte::page`
3. ✅ Las rutas están definidas y protegidas correctamente
4. ✅ El menú muestra el módulo (si aplica)
5. ✅ Los permisos/roles funcionan correctamente
6. ✅ Los mensajes de éxito/error se muestran correctamente
7. ✅ La validación funciona como se espera

## Comandos Útiles

```bash
# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de rutas
php artisan route:clear

# Limpiar caché de vistas
php artisan view:clear

# Ver todas las rutas
php artisan route:list

# Ver rutas de un módulo específico
php artisan route:list --name=nombre-modulo
```

## Referencias

- Documentación de Laravel: https://laravel.com/docs
- Documentación de AdminLTE: https://adminlte.io/docs/3.2
- Documentación de Spatie Permission: https://spatie.be/docs/laravel-permission
- Módulo de ejemplo: `documentacion/modulos/modulo-prueba-correo.md`

