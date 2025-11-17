# Errores Corregidos: Módulo de Usuarios - Perfil, Roles e Imágenes

## Descripción de los Errores

Se identificaron y corrigieron 3 errores importantes en el módulo de gestión de usuarios:

1. **Selector de roles visible para admin**: El selector de roles aparecía para usuarios con rol `admin`, cuando solo `root` debería poder cambiar roles.
2. **Falta de opción de perfil**: No existía una opción en el menú para que los clientes pudieran editar su propia información de perfil.
3. **Falta de funcionalidad de foto de perfil**: No había opción para que los usuarios subieran una foto de perfil, aunque AdminLTE tiene esta funcionalidad integrada.

## Error 1: Selector de Roles Visible para Admin

### Problema

En la vista `edit.blade.php`, el selector de roles aparecía para usuarios con rol `admin`, pero según las políticas de seguridad, solo el rol `root` debería poder cambiar roles de otros usuarios.

### Solución

**Archivo**: `resources/views/usuarios/edit.blade.php`

**Antes:**
```blade
@if (auth()->user()->hasAnyRole(['root', 'admin']))
    <div class="form-group">
        <label for="role">Rol</label>
        <!-- Selector de roles -->
    </div>
@endif
```

**Después:**
```blade
@if (auth()->user()->hasRole('root'))
    <div class="form-group">
        <label for="role">Rol</label>
        <!-- Selector de roles -->
        <small class="form-text text-muted">Solo root puede cambiar roles.</small>
    </div>
@endif
```

**Archivo**: `app/Http/Controllers/UsuarioController.php`

**Antes:**
```php
// Solo root y admin pueden cambiar roles
if (auth()->user()->hasAnyRole(['root', 'admin']) && $request->filled('role')) {
    $nuevoRol = Role::find($request->role);
    if ($nuevoRol) {
        $usuario->syncRoles([$nuevoRol->name]);
    }
}
```

**Después:**
```php
// Solo root puede cambiar roles (no admin)
if (auth()->user()->hasRole('root') && $request->filled('role')) {
    $nuevoRol = Role::find($request->role);
    if ($nuevoRol) {
        $usuario->syncRoles([$nuevoRol->name]);
    }
}
```

### Resultado

- Solo usuarios con rol `root` pueden ver y cambiar roles
- Los usuarios con rol `admin` ya no pueden modificar roles
- Mayor seguridad y control de acceso

## Error 2: Falta de Opción de Perfil

### Problema

Los usuarios, especialmente los clientes, no tenían una forma fácil de acceder a su perfil para editar su propia información. No había una opción en el menú lateral ni en el menú de usuario.

### Solución

#### 1. Crear Controlador de Perfil

**Archivo**: `app/Http/Controllers/PerfilController.php`

Se creó un controlador dedicado para manejar el perfil del usuario autenticado:

```php
class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = auth()->user();
        return view('perfil.index', compact('usuario'));
    }

    public function update(Request $request)
    {
        // Validación y actualización del perfil
    }
}
```

#### 2. Crear Vista de Perfil

**Archivo**: `resources/views/perfil/index.blade.php`

Se creó una vista completa para editar el perfil con:
- Campo para subir foto de perfil
- Campos para editar nombre y email
- Campos opcionales para cambiar contraseña
- Validación en tiempo real
- Toggle para mostrar/ocultar contraseña

#### 3. Agregar Rutas

**Archivo**: `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    // Ruta de perfil (accesible para todos los usuarios autenticados)
    Route::get('/perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
});
```

#### 4. Agregar al Menú

**Archivo**: `config/adminlte.php`

```php
'menu' => [
    // ...
    [
        'text' => 'Mi Perfil',
        'url' => 'perfil',
        'icon' => 'fas fa-fw fa-user',
    ],
    // ...
],
```

#### 5. Configurar AdminLTE para Mostrar Perfil en Menú de Usuario

**Archivo**: `config/adminlte.php`

```php
'usermenu_profile_url' => true,
'profile_url' => 'perfil',
```

### Resultado

- Todos los usuarios pueden acceder a "Mi Perfil" desde el menú lateral
- Los usuarios pueden editar su propia información sin necesidad de permisos especiales
- El menú de usuario (dropdown) también muestra un enlace al perfil
- Mejor experiencia de usuario

## Error 3: Falta de Funcionalidad de Foto de Perfil

### Problema

Aunque AdminLTE tiene soporte nativo para mostrar fotos de perfil en el menú de usuario, no se había implementado la funcionalidad para que los usuarios pudieran subir sus propias fotos.

### Solución

#### 1. Crear Migración para Campo de Imagen

**Archivo**: `database/migrations/2025_11_17_154221_add_image_to_users_table.php`

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('image')->nullable()->after('email');
    });
}
```

#### 2. Actualizar Modelo User

**Archivo**: `app/Models/User.php`

**Agregar campo a fillable:**
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'image', // Nuevo campo
];
```

**Agregar métodos para AdminLTE:**
```php
/**
 * Get the user's image URL for AdminLTE.
 */
public function adminlte_image(): string
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }
    // Imagen por defecto si no tiene foto
    return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
}

/**
 * Get the user's description for AdminLTE.
 */
public function adminlte_desc(): string
{
    return $this->email;
}

/**
 * Get the user's profile URL for AdminLTE.
 */
public function adminlte_profile_url(): string
{
    return 'perfil';
}
```

#### 3. Configurar AdminLTE

**Archivo**: `config/adminlte.php`

```php
'usermenu_image' => true,  // Habilitar imagen en menú de usuario
'usermenu_desc' => true,   // Habilitar descripción en menú de usuario
'usermenu_profile_url' => true,  // Habilitar URL de perfil
```

#### 4. Agregar Campo de Imagen en Vistas

**Archivos**: 
- `resources/views/usuarios/create.blade.php`
- `resources/views/usuarios/edit.blade.php`
- `resources/views/perfil/index.blade.php`

```blade
<div class="form-group">
    <label for="image">
        <i class="fas fa-image"></i> Foto de Perfil
    </label>
    <div class="mb-2">
        @if ($usuario->image)
            <img src="{{ asset('storage/' . $usuario->image) }}" 
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
```

#### 5. Actualizar Controladores para Manejar Imágenes

**Archivos**: 
- `app/Http/Controllers/UsuarioController.php`
- `app/Http/Controllers/PerfilController.php`

**Validación:**
```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
```

**Guardar imagen:**
```php
if ($request->hasFile('image')) {
    // Eliminar imagen anterior si existe
    if ($usuario->image && Storage::disk('public')->exists($usuario->image)) {
        Storage::disk('public')->delete($usuario->image);
    }

    // Guardar nueva imagen
    $imagePath = $request->file('image')->store('users', 'public');
    $usuario->image = $imagePath;
}
```

#### 6. Crear Enlace Simbólico de Storage

```bash
php artisan storage:link
```

Este comando crea un enlace simbólico desde `public/storage` a `storage/app/public`, permitiendo que las imágenes sean accesibles públicamente.

### Resultado

- Los usuarios pueden subir su foto de perfil
- La foto se muestra en el menú de usuario (dropdown) en la barra superior
- La foto se muestra en las vistas de creación, edición y perfil
- Las imágenes se almacenan en `storage/app/public/users/`
- Se elimina la imagen anterior cuando se sube una nueva
- Validación de formato y tamaño de imagen

## Archivos Modificados

### Nuevos Archivos Creados

1. `database/migrations/2025_11_17_154221_add_image_to_users_table.php` - Migración para campo image
2. `app/Http/Controllers/PerfilController.php` - Controlador de perfil
3. `resources/views/perfil/index.blade.php` - Vista de perfil
4. `documentacion/errores/errores-modulo-usuarios-perfil-imagen.md` - Esta documentación

### Archivos Modificados

1. `app/Models/User.php` - Agregado campo image y métodos AdminLTE
2. `app/Http/Controllers/UsuarioController.php` - Manejo de imágenes y corrección de roles
3. `resources/views/usuarios/create.blade.php` - Campo de imagen
4. `resources/views/usuarios/edit.blade.php` - Campo de imagen y corrección de selector de roles
5. `resources/views/usuarios/show.blade.php` - Mostrar foto de perfil
6. `routes/web.php` - Rutas de perfil
7. `config/adminlte.php` - Configuración de imagen y perfil

## Configuración Requerida

### 1. Ejecutar Migración

```bash
php artisan migrate
```

### 2. Crear Enlace Simbólico

```bash
php artisan storage:link
```

### 3. Permisos de Carpeta

Asegurarse de que la carpeta `storage/app/public/users/` tenga permisos de escritura:

```bash
chmod -R 775 storage/app/public
```

## Validaciones Implementadas

### Imagen de Perfil

- **Tipo**: Debe ser una imagen (jpeg, png, jpg, gif)
- **Tamaño máximo**: 2MB
- **Formatos permitidos**: JPEG, PNG, JPG, GIF
- **Opcional**: El campo es opcional, se puede crear/editar usuario sin imagen

## Funcionalidades Agregadas

### 1. Subida de Imagen

- Campo de archivo en formularios de creación, edición y perfil
- Vista previa de la imagen actual
- Eliminación automática de imagen anterior al subir nueva
- Almacenamiento en `storage/app/public/users/`

### 2. Visualización de Imagen

- Imagen en menú de usuario (dropdown superior)
- Imagen en vista de detalles de usuario
- Imagen en formularios de edición
- Imagen por defecto si no tiene foto

### 3. Perfil de Usuario

- Ruta dedicada `/perfil`
- Vista completa para editar perfil propio
- Acceso desde menú lateral "Mi Perfil"
- Acceso desde menú de usuario (dropdown)

## Referencias

- Documentación de AdminLTE: https://github.com/jeroennoten/Laravel-AdminLTE
- Documentación de Laravel Storage: https://laravel.com/docs/filesystem
- Documentación de Laravel File Uploads: https://laravel.com/docs/requests#files

## Notas Adicionales

- Las imágenes se almacenan en `storage/app/public/users/`
- El enlace simbólico permite acceso público a las imágenes
- Se recomienda implementar redimensionamiento de imágenes en el futuro
- La imagen por defecto es `vendor/adminlte/dist/img/user2-160x160.jpg`
- Solo el rol `root` puede cambiar roles de otros usuarios
- Todos los usuarios pueden editar su propio perfil

