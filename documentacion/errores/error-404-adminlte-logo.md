# Error: 404 AdminLTELogo.png en subdirectorio

## Descripción del Error

**Mensaje de error:**
```
GET https://rulossoluciones.com/ModuStackHome/vendor/adminlte/dist/img/AdminLTELogo.png 404 (Not Found)
```

**Fecha del error:** 2024

**Contexto:** Aplicación Laravel con AdminLTE 3 instalada en un subdirectorio (`ModuStackHome`) en el servidor.

---

## Causa del Error

El error ocurre porque:

1. **Aplicación en subdirectorio:** La aplicación está instalada en `https://rulossoluciones.com/ModuStackHome/` en lugar de la raíz del dominio.

2. **Función `asset()` no maneja subdirectorio:** Cuando Laravel genera URLs con `asset()`, puede no incluir correctamente el prefijo del subdirectorio si la configuración no está correcta.

3. **Ruta en configuración:** La ruta en `config/adminlte.php` está configurada como `vendor/adminlte/dist/img/AdminLTELogo.png` sin el prefijo del subdirectorio.

---

## Solución

### Opción 1: Usar ruta absoluta con barra inicial (Recomendado)

Modificar `config/adminlte.php` para usar rutas que empiecen con `/`:

```php
'logo_img' => '/vendor/adminlte/dist/img/AdminLTELogo.png',
```

**Nota:** Esto funcionará si el servidor web está configurado para servir Laravel desde la raíz. Si está en un subdirectorio, necesitarás la Opción 2.

### Opción 2: Configurar ASSET_URL en .env (Para subdirectorios)

Si la aplicación está en un subdirectorio, agregar en el archivo `.env`:

```env
ASSET_URL=/ModuStackHome
```

O la URL completa:

```env
ASSET_URL=https://rulossoluciones.com/ModuStackHome
```

Luego limpiar la caché:

```bash
php artisan config:clear
php artisan cache:clear
```

### Opción 3: Usar URL de CDN para el logo

Si prefieres no depender de archivos locales, puedes usar una URL de CDN:

```php
'logo_img' => 'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png',
```

O cualquier otra URL de imagen que desees usar.

### Opción 4: Verificar configuración del servidor web

Si usas Apache, verificar que el archivo `.htaccess` en `public/` esté configurado correctamente para manejar el subdirectorio.

---

## Archivos Modificados

1. **`config/adminlte.php`** - Rutas del logo actualizadas:
   - `logo_img` → `/vendor/adminlte/dist/img/AdminLTELogo.png`
   - `auth_logo.img.path` → `/vendor/adminlte/dist/img/AdminLTELogo.png`
   - `preloader.img.path` → `/vendor/adminlte/dist/img/AdminLTELogo.png`

---

## Verificación

1. **Limpiar caché de configuración:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. **Verificar que el archivo existe:**
   ```bash
   ls public/vendor/adminlte/dist/img/AdminLTELogo.png
   ```

3. **Verificar en el navegador:**
   - Abrir las herramientas de desarrollador (F12)
   - Ir a la pestaña "Network"
   - Recargar la página
   - Verificar que la imagen se carga correctamente

4. **Verificar la URL generada:**
   - La URL debería ser: `https://rulossoluciones.com/ModuStackHome/vendor/adminlte/dist/img/AdminLTELogo.png`
   - O si usas ASSET_URL: `https://rulossoluciones.com/ModuStackHome/vendor/adminlte/dist/img/AdminLTELogo.png`

---

## Configuración Actual

**Archivo:** `config/adminlte.php`

```php
'logo_img' => '/vendor/adminlte/dist/img/AdminLTELogo.png',

'auth_logo' => [
    'enabled' => false,
    'img' => [
        'path' => '/vendor/adminlte/dist/img/AdminLTELogo.png',
        // ...
    ],
],

'preloader' => [
    'enabled' => true,
    'mode' => 'fullscreen',
    'img' => [
        'path' => '/vendor/adminlte/dist/img/AdminLTELogo.png',
        // ...
    ],
],
```

---

## Notas Importantes

- **Rutas relativas vs absolutas:** 
  - Sin `/` inicial: `vendor/...` → Laravel usa `asset()` que puede agregar el prefijo del subdirectorio
  - Con `/` inicial: `/vendor/...` → Ruta absoluta desde la raíz del dominio

- **Subdirectorios:** Si la aplicación está en un subdirectorio, es mejor usar `ASSET_URL` en `.env` o configurar el servidor web correctamente.

- **Archivo existe:** El archivo `public/vendor/adminlte/dist/img/AdminLTELogo.png` existe, el problema es solo la generación de la URL.

---

## Referencias

- [Laravel Asset URL Configuration](https://laravel.com/docs/asset-compilation#url-processing)
- [AdminLTE Configuration](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration)
- [Laravel Subdirectory Installation](https://laravel.com/docs/deployment#server-configuration)

---

## Solución Aplicada

Se actualizaron las rutas en `config/adminlte.php` para usar rutas absolutas con `/` inicial:

- `logo_img`: `/vendor/adminlte/dist/img/AdminLTELogo.png`
- `auth_logo.img.path`: `/vendor/adminlte/dist/img/AdminLTELogo.png`
- `preloader.img.path`: `/vendor/adminlte/dist/img/AdminLTELogo.png`

Si el problema persiste, usar la **Opción 2** (configurar `ASSET_URL` en `.env`).

