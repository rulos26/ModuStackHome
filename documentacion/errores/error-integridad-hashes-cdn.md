# Error: Failed to find a valid digest in the 'integrity' attribute

## Descripción del Error

**Mensajes de error:**
```
Failed to find a valid digest in the 'integrity' attribute for resource 
'https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css' 
with computed SHA-512 integrity 'jN4O0AUkRmE6Jwc8la2I5iBmS+tCDcfUd1eq8nrZIBnDKTmCp5YxxNN1/aetnAH32qT+dDbk1aGhhoaw5cJNlw=='. 
The resource has been blocked.

Failed to find a valid digest in the 'integrity' attribute for resource 
'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js' 
with computed SHA-512 integrity 'igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA=='. 
The resource has been blocked.

Failed to find a valid digest in the 'integrity' attribute for resource 
'https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js' 
with computed SHA-512 integrity '3Ofi0j25Ar6Hyqk2sdvfuoVCvvN6nE6Dh/eoMc8RQ/bnCvO8wpE+M5KyJz6T08T7pl/x82I/3Y5Amz9n3T9Esw=='. 
The resource has been blocked.
```

**Fecha del error:** 2024

**Contexto:** Error al usar CDN con atributos de integridad (SRI - Subresource Integrity) incorrectos.

---

## Causa del Error

El error ocurre cuando:

1. **Hashes de integridad incorrectos:** Los valores del atributo `integrity` no coinciden con el hash SHA-512 real del archivo servido por el CDN.

2. **Versiones diferentes:** El hash puede corresponder a una versión diferente del archivo o a un archivo modificado.

3. **CDN modifica archivos:** Algunos CDN pueden aplicar optimizaciones (minificación, compresión) que alteran el contenido y por tanto el hash.

4. **Hashes copiados incorrectamente:** Los hashes pueden haber sido copiados de una fuente incorrecta o desactualizada.

---

## Solución

### Paso 1: Obtener los hashes correctos

Los hashes correctos se pueden obtener de:

1. **CDNJS directamente:** Visitar la página del recurso en cdnjs.cloudflare.com y copiar el hash de integridad que proporcionan.

2. **Herramientas online:**
   - [srihash.org](https://srihash.org/) - Genera hashes SRI
   - [Subresource Integrity Hash Generator](https://www.srihash.org/)

3. **Desde la consola del navegador:** El navegador muestra el hash calculado en el mensaje de error.

### Paso 2: Actualizar los hashes en los archivos

**Archivo:** `resources/views/vendor/adminlte/master.blade.php`

**Hashes corregidos:**

#### OverlayScrollbars CSS (1.13.1)
```blade
<!-- Antes (incorrecto) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css" integrity="sha512-jN4O0u6mG3eG1tpPG2A58owdCOBDxXvx5CM6yQ9s7f8v+QSBk+O3H9rK0Y3BQv4Z8c7N8c1A3qF8q8X5k8V8vw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Después (correcto) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css" integrity="sha512-jN4O0AUkRmE6Jwc8la2I5iBmS+tCDcfUd1eq8nrZIBnDKTmCp5YxxNN1/aetnAH32qT+dDbk1aGhhoaw5cJNlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
```

#### Bootstrap 4.6.2 JS
```blade
<!-- Antes (incorrecto) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-7KkTNrhi3ZJwPGsjm9VO6pXicT8Xwv9eTqH1eY0ehlfOasx55sxW7Y2QSH9gP2hF1z+y2R7gGbP8vK9Ctsq/Og==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Después (correcto) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
```

#### OverlayScrollbars JS (1.13.1)
```blade
<!-- Antes (incorrecto) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js" integrity="sha512-jN4O0u6mG3eG1tpPG2A58owdCOBDxXvx5CM6yQ9s7f8v+QSBk+O3H9rK0Y3BQv4Z8c7N8c1A3qF8q8X5k8V8vw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Después (correcto) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js" integrity="sha512-3Ofi0j25Ar6Hyqk2sdvfuoVCvvN6nE6Dh/eoMc8RQ/bnCvO8wpE+M5KyJz6T08T7pl/x82I/3Y5Amz9n3T9Esw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
```

### Paso 3: Verificar que los recursos cargan correctamente

1. Limpiar caché del navegador (Ctrl+Shift+Delete)
2. Recargar la página (Ctrl+F5)
3. Abrir las herramientas de desarrollador (F12)
4. Verificar en la pestaña "Network" que los recursos se cargan sin errores
5. Revisar la consola para asegurarse de que no hay más errores de integridad

---

## Error Adicional: AdminLTELogo.png 404

**Mensaje de error:**
```
AdminLTELogo.png:1 Failed to load resource: the server responded with a status of 404 ()
```

**Causa:** El archivo `AdminLTELogo.png` no se encuentra en la ruta esperada `public/vendor/adminlte/dist/img/AdminLTELogo.png`.

**Solución:**

1. **Verificar que el archivo existe:**
   ```bash
   ls public/vendor/adminlte/dist/img/AdminLTELogo.png
   ```

2. **Si el archivo no existe, copiarlo desde el paquete:**
   ```bash
   php artisan vendor:publish --tag=adminlte_assets
   ```

3. **O usar una imagen desde CDN:**
   Modificar `config/adminlte.php`:
   ```php
   'logo_img' => 'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png',
   ```

4. **O usar una imagen local personalizada:**
   Colocar la imagen en `public/img/logo.png` y actualizar:
   ```php
   'logo_img' => 'img/logo.png',
   ```

---

## Hashes Correctos Utilizados

| Recurso | Versión | Hash SHA-512 (Base64) |
|---------|---------|----------------------|
| OverlayScrollbars CSS | 1.13.1 | `sha512-jN4O0AUkRmE6Jwc8la2I5iBmS+tCDcfUd1eq8nrZIBnDKTmCp5YxxNN1/aetnAH32qT+dDbk1aGhhoaw5cJNlw==` |
| Bootstrap 4.6.2 JS | 4.6.2 | `sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==` |
| OverlayScrollbars JS | 1.13.1 | `sha512-3Ofi0j25Ar6Hyqk2sdvfuoVCvvN6nE6Dh/eoMc8RQ/bnCvO8wpE+M5KyJz6T08T7pl/x82I/3Y5Amz9n3T9Esw==` |

---

## Archivos Modificados

- `resources/views/vendor/adminlte/master.blade.php` - Hashes de integridad corregidos

---

## Prevención de Errores Futuros

1. **Siempre obtener hashes desde la fuente oficial:**
   - Visitar cdnjs.cloudflare.com y copiar el hash directamente
   - Usar herramientas como srihash.org para generar hashes

2. **Verificar hashes antes de implementar:**
   - Probar en un entorno de desarrollo primero
   - Revisar la consola del navegador para errores

3. **Documentar los hashes utilizados:**
   - Mantener un registro de los hashes correctos
   - Actualizar la documentación cuando se cambien versiones

4. **Alternativa: Omitir integrity si causa problemas:**
   Si los hashes causan problemas persistentes, se puede omitir el atributo `integrity` (aunque esto reduce la seguridad):
   ```blade
   <!-- Sin integrity (menos seguro pero funcional) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css" crossorigin="anonymous">
   ```

---

## Referencias

- [MDN: Subresource Integrity](https://developer.mozilla.org/en-US/docs/Web/Security/Subresource_Integrity)
- [CDNJS - Cloudflare CDN](https://cdnjs.com/)
- [SRI Hash Generator](https://srihash.org/)
- [W3C Subresource Integrity](https://www.w3.org/TR/SRI/)

---

## Notas

- Los hashes de integridad (SRI) son importantes para la seguridad, ya que previenen que recursos maliciosos se inyecten si el CDN es comprometido.
- Si un CDN modifica los archivos (minificación, compresión), los hashes pueden cambiar.
- Siempre usar hashes de la fuente oficial del CDN para garantizar compatibilidad.

