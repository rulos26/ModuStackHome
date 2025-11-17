# Módulo de Prueba de Correo

## Descripción

Este módulo permite probar la funcionalidad de envío de correos electrónicos desde la aplicación ModuStackHome. Está diseñado específicamente para usuarios con el rol `root` y proporciona una interfaz simple para enviar correos de prueba y verificar la configuración SMTP.

## Características

- **Interfaz de usuario**: Formulario intuitivo con validación de campos
- **Restricción de acceso**: Solo usuarios con rol `root` pueden acceder
- **Información de configuración**: Muestra la configuración SMTP actual
- **Manejo de errores**: Mensajes claros de éxito y error
- **Validación**: Validación completa de campos requeridos

## Estructura del Módulo

### Controlador

**Archivo**: `app/Http/Controllers/CorreoController.php`

El controlador contiene dos métodos principales:

1. **`index()`**: Muestra el formulario de prueba de correo
2. **`enviar(Request $request)`**: Procesa el envío del correo

#### Características del Controlador:

- Middleware de autenticación (`auth`)
- Middleware de rol (`role:root`)
- Validación de campos (destinatario, asunto, mensaje)
- Manejo de excepciones con mensajes de error claros

### Vista

**Archivo**: `resources/views/correo/prueba.blade.php`

La vista utiliza la plantilla AdminLTE (`@extends('adminlte::page')`) y contiene:

- Formulario con campos:
  - **Destinatario**: Campo de email con validación
  - **Asunto**: Campo de texto para el asunto del correo
  - **Mensaje**: Área de texto para el contenido del correo
- Sección de información de configuración SMTP
- Mensajes de éxito y error con alertas de Bootstrap

### Rutas

**Archivo**: `routes/web.php`

```php
Route::middleware(['auth', 'role:root'])->prefix('correo')->name('correo.')->group(function () {
    Route::get('/prueba', [App\Http\Controllers\CorreoController::class, 'index'])->name('prueba');
    Route::post('/enviar', [App\Http\Controllers\CorreoController::class, 'enviar'])->name('enviar');
});
```

**URLs del módulo**:
- `GET /correo/prueba` - Muestra el formulario
- `POST /correo/enviar` - Procesa el envío

### Menú

**Archivo**: `config/adminlte.php`

El módulo aparece en el menú lateral solo para usuarios con rol `root`:

```php
[
    'text' => 'Prueba de Correo',
    'url' => 'correo/prueba',
    'icon' => 'fas fa-fw fa-envelope',
    'can' => 'role:root',
],
```

## Configuración Requerida

### 1. Middleware de Roles

El middleware de roles debe estar registrado en `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
})
```

### 2. Configuración de Correo

La configuración de correo debe estar en `config/mail.php` y `.env`:

**`.env`**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rulos26@gmail.com
MAIL_PASSWORD=imltkpfnvehflplt
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="rulos26@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Ver documentación completa en: `documentacion/configuracion/configuracion-correo-gmail.md`

## Uso del Módulo

### Acceso al Módulo

1. Iniciar sesión con un usuario que tenga el rol `root`
2. En el menú lateral, hacer clic en "Prueba de Correo"
3. Se mostrará el formulario de envío

### Enviar un Correo de Prueba

1. Ingresar el correo del destinatario en el campo "Destinatario"
2. Escribir un asunto en el campo "Asunto"
3. Escribir el mensaje en el campo "Mensaje"
4. Hacer clic en "Enviar Correo"
5. Se mostrará un mensaje de éxito o error según el resultado

### Información de Configuración

En la parte inferior del formulario se muestra:
- Servidor SMTP
- Puerto
- Encriptación
- Remitente
- Nombre del remitente

## Validaciones

El módulo valida:

- **Destinatario**: Debe ser un email válido y es obligatorio
- **Asunto**: Es obligatorio y máximo 255 caracteres
- **Mensaje**: Es obligatorio

## Manejo de Errores

El módulo maneja errores de las siguientes formas:

1. **Errores de validación**: Se muestran en la parte superior del formulario con un listado de errores
2. **Errores de envío**: Se capturan las excepciones y se muestra un mensaje descriptivo
3. **Mensajes de éxito**: Se muestra un mensaje verde cuando el correo se envía correctamente

## Seguridad

- Solo usuarios autenticados pueden acceder
- Solo usuarios con rol `root` pueden ver y usar el módulo
- El middleware `role:root` protege todas las rutas del módulo
- El menú solo muestra el enlace para usuarios con el rol adecuado

## Extensión de la Plantilla

La vista utiliza `@extends('adminlte::page')` para heredar de la plantilla AdminLTE, lo que proporciona:

- Diseño consistente con el resto de la aplicación
- Navegación y menú lateral
- Estilos y componentes de Bootstrap/AdminLTE
- Responsive design automático

## Próximas Mejoras

Posibles mejoras futuras:

- Soporte para HTML en el cuerpo del correo
- Plantillas de correo predefinidas
- Historial de correos enviados
- Adjuntos de archivos
- Múltiples destinatarios
- Copia (CC) y copia oculta (BCC)

## Referencias

- Documentación de configuración de correo: `documentacion/configuracion/configuracion-correo-gmail.md`
- Documentación de creación de módulos: `documentacion/mejoras/crear-modulo-nuevo.md`

