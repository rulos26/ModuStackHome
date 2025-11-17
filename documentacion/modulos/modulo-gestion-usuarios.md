# Módulo de Gestión de Usuarios

## Descripción

Este módulo proporciona una interfaz completa para la gestión de usuarios (CRUD) con control de acceso basado en roles. Permite a los administradores crear, ver, editar y eliminar usuarios, mientras que los clientes solo pueden editar su propio perfil.

## Características

- **CRUD Completo**: Crear, leer, actualizar y eliminar usuarios
- **Control de Acceso por Roles**: Diferentes permisos según el rol del usuario
- **Asignación Automática de Rol**: Todos los nuevos usuarios se crean con rol "cliente"
- **Interfaz Intuitiva**: Diseño limpio usando AdminLTE
- **Validación Completa**: Validación de formularios con mensajes en español
- **Políticas de Autorización**: Uso de Laravel Policies para control de acceso

## Reglas de Autorización

### Ver Lista de Usuarios (`viewAny`)
- ✅ **Root**: Puede ver todos los usuarios
- ✅ **Admin**: Puede ver todos los usuarios
- ❌ **Cliente**: No puede ver la lista de usuarios

### Ver Usuario (`view`)
- ✅ **Root**: Puede ver cualquier usuario
- ✅ **Admin**: Puede ver cualquier usuario
- ✅ **Cliente**: Solo puede ver su propio perfil

### Crear Usuario (`create`)
- ✅ **Root**: Puede crear usuarios
- ✅ **Admin**: Puede crear usuarios
- ❌ **Cliente**: No puede crear usuarios

### Editar Usuario (`update`)
- ✅ **Root**: Puede editar cualquier usuario
- ✅ **Admin**: Puede editar cualquier usuario
- ✅ **Cliente**: Solo puede editar su propio perfil
- ❌ **Nadie**: No puede editar a sí mismo si es root (para eliminar)

### Eliminar Usuario (`delete`)
- ✅ **Root**: Puede eliminar cualquier usuario (excepto a sí mismo)
- ❌ **Admin**: No puede eliminar usuarios
- ❌ **Cliente**: No puede eliminar usuarios

## Estructura del Módulo

### Policy

**Archivo**: `app/Policies/UserPolicy.php`

La Policy controla todos los permisos de acceso según las reglas definidas arriba.

### Controlador

**Archivo**: `app/Http/Controllers/UsuarioController.php`

El controlador contiene los siguientes métodos:

- **`index()`**: Lista todos los usuarios (paginado)
- **`create()`**: Muestra el formulario de creación
- **`store()`**: Guarda un nuevo usuario con rol "cliente" por defecto
- **`show()`**: Muestra los detalles de un usuario
- **`edit()`**: Muestra el formulario de edición
- **`update()`**: Actualiza un usuario existente
- **`destroy()`**: Elimina un usuario

#### Características del Controlador:

- Middleware de autenticación en todas las rutas
- Autorización usando `$this->authorize()` en cada método
- Validación de formularios con mensajes en español
- Asignación automática de rol "cliente" al crear usuarios
- Solo root y admin pueden cambiar roles al editar

### Vistas

**Ubicación**: `resources/views/usuarios/`

#### `index.blade.php`
- Lista de usuarios en tabla
- Paginación
- Botones de acción según permisos
- Badges de colores para roles (root=rojo, admin=amarillo, cliente=azul)

#### `create.blade.php`
- Formulario de creación
- Campos: nombre, email, contraseña, confirmar contraseña
- Nota informativa sobre asignación automática de rol cliente

#### `edit.blade.php`
- Formulario de edición
- Campos: nombre, email, contraseña (opcional), confirmar contraseña
- Selector de rol (solo visible para root y admin)
- Validación condicional de contraseña

#### `show.blade.php`
- Vista detallada del usuario
- Información completa: ID, nombre, email, roles, fechas
- Botones de acción según permisos

### Rutas

**Archivo**: `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class)->parameters([
        'usuarios' => 'usuario'
    ]);
});
```

**URLs del módulo**:
- `GET /usuarios` - Lista de usuarios
- `GET /usuarios/create` - Formulario de creación
- `POST /usuarios` - Guardar nuevo usuario
- `GET /usuarios/{usuario}` - Ver detalles
- `GET /usuarios/{usuario}/edit` - Formulario de edición
- `PUT /usuarios/{usuario}` - Actualizar usuario
- `DELETE /usuarios/{usuario}` - Eliminar usuario

### Menú

**Archivo**: `config/adminlte.php`

El módulo aparece en el menú lateral solo para usuarios con permisos:

```php
[
    'text' => 'Usuarios',
    'url' => 'usuarios',
    'icon' => 'fas fa-fw fa-users',
    'can' => 'viewAny,App\Models\User',
],
```

## Configuración Requerida

### 1. Policy Registrada

La Policy debe estar registrada en `app/Providers/AppServiceProvider.php`:

```php
protected $policies = [
    User::class => UserPolicy::class,
];
```

### 2. Modelo User con HasRoles

El modelo `User` debe usar el trait `HasRoles` de Spatie Permission:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    // ...
}
```

### 3. Roles Creados

Los roles deben existir en la base de datos:
- `root`
- `admin`
- `cliente`

Ver: `database/seeders/RolesAndUsersSeeder.php`

## Uso del Módulo

### Para Root y Admin

1. **Ver Lista de Usuarios**:
   - Acceder a "Usuarios" en el menú lateral
   - Ver tabla con todos los usuarios

2. **Crear Usuario**:
   - Clic en "Nuevo Usuario"
   - Completar formulario
   - El usuario se crea automáticamente con rol "cliente"

3. **Editar Usuario**:
   - Clic en botón "Editar" (ícono de lápiz)
   - Modificar datos
   - Cambiar rol si es necesario (solo root y admin)

4. **Eliminar Usuario** (solo root):
   - Clic en botón "Eliminar" (ícono de basura)
   - Confirmar eliminación

### Para Cliente

1. **Editar Perfil Propio**:
   - Acceder a su perfil (si tiene acceso)
   - Clic en "Editar"
   - Modificar solo sus propios datos
   - No puede cambiar su rol

## Validaciones

### Crear Usuario

- **Nombre**: Obligatorio, máximo 255 caracteres
- **Email**: Obligatorio, formato válido, único en la base de datos
- **Contraseña**: Obligatoria, mínimo 8 caracteres
- **Confirmar Contraseña**: Debe coincidir con la contraseña

### Editar Usuario

- **Nombre**: Obligatorio, máximo 255 caracteres
- **Email**: Obligatorio, formato válido, único (excepto el usuario actual)
- **Contraseña**: Opcional, mínimo 8 caracteres si se proporciona
- **Confirmar Contraseña**: Debe coincidir si se proporciona contraseña

## Seguridad

- **Autenticación**: Todas las rutas requieren autenticación
- **Autorización**: Cada acción verifica permisos mediante Policies
- **Protección CSRF**: Todos los formularios incluyen tokens CSRF
- **Validación de Datos**: Validación en servidor y cliente
- **Hash de Contraseñas**: Las contraseñas se hashean automáticamente
- **Prevención de Auto-eliminación**: Root no puede eliminarse a sí mismo

## Flujo de Trabajo

### Crear Usuario

1. Usuario (root/admin) accede a "Usuarios" → "Nuevo Usuario"
2. Completa el formulario
3. Al guardar, el sistema:
   - Valida los datos
   - Crea el usuario
   - Asigna automáticamente el rol "cliente"
   - Redirige a la lista con mensaje de éxito

### Editar Usuario

1. Usuario accede a la lista de usuarios
2. Clic en "Editar" del usuario deseado
3. Modifica los datos necesarios
4. Si es root/admin, puede cambiar el rol
5. Guarda los cambios
6. Redirige a la lista con mensaje de éxito

### Eliminar Usuario (solo root)

1. Root accede a la lista de usuarios
2. Clic en "Eliminar" del usuario deseado
3. Confirma la eliminación
4. El usuario es eliminado permanentemente
5. Redirige a la lista con mensaje de éxito

## Mensajes al Usuario

### Mensajes de Éxito

- "Usuario creado exitosamente con rol cliente."
- "Usuario actualizado exitosamente."
- "Usuario eliminado exitosamente."

### Mensajes de Error

- "No puedes eliminar tu propia cuenta."
- "Error al crear el usuario: [mensaje]"
- "Error al actualizar el usuario: [mensaje]"
- "Error al eliminar el usuario: [mensaje]"

### Validaciones

Todos los mensajes de validación están en español y son claros y descriptivos.

## Extensión de la Plantilla

Todas las vistas utilizan `@extends('adminlte::page')` para mantener consistencia con el resto de la aplicación.

## Mejoras Futuras

Posibles mejoras para el módulo:

- Búsqueda y filtrado de usuarios
- Exportación a Excel/PDF
- Historial de cambios
- Verificación de email
- Restablecimiento de contraseña desde el módulo
- Activar/desactivar usuarios en lugar de eliminar
- Asignación de múltiples roles
- Permisos granulares por acción

## Referencias

- Documentación de Laravel Policies: https://laravel.com/docs/authorization#creating-policies
- Documentación de Spatie Permission: https://spatie.be/docs/laravel-permission
- Documentación de creación de módulos: `documentacion/mejoras/crear-modulo-nuevo.md`

