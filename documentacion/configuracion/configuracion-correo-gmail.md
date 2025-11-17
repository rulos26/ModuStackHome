# Configuración de Correo Electrónico con Gmail SMTP

## Descripción

Esta guía explica cómo configurar el envío de correos electrónicos en ModuStackHome usando Gmail como servidor SMTP.

**Fecha de configuración:** 2024

---

## Configuración en el archivo `.env`

Agrega o actualiza las siguientes líneas en tu archivo `.env`:

```env
# Configuración de correo electrónico
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rulos26@gmail.com
MAIL_PASSWORD=imltkpfnvehflplt
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="rulos26@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Explicación de cada parámetro:

- **MAIL_MAILER=smtp**: Usa el protocolo SMTP para enviar correos
- **MAIL_HOST=smtp.gmail.com**: Servidor SMTP de Gmail
- **MAIL_PORT=587**: Puerto para conexión TLS (recomendado para Gmail)
- **MAIL_USERNAME**: Tu dirección de correo de Gmail
- **MAIL_PASSWORD**: Contraseña de aplicación de Gmail (ver sección de generación)
- **MAIL_ENCRYPTION=tls**: Método de encriptación (TLS es más seguro que SSL)
- **MAIL_FROM_ADDRESS**: Dirección desde la cual se enviarán los correos
- **MAIL_FROM_NAME**: Nombre que aparecerá como remitente (usa ${APP_NAME} para usar el nombre de la app)

---

## Generar Contraseña de Aplicación en Gmail

Gmail requiere una "Contraseña de aplicación" en lugar de tu contraseña normal para aplicaciones de terceros.

### Pasos para generar una contraseña de aplicación:

1. **Activar la verificación en dos pasos** (si no la tienes activada):
   - Ve a tu cuenta de Google: https://myaccount.google.com/
   - Seguridad → Verificación en 2 pasos → Activar

2. **Generar contraseña de aplicación**:
   - Ve a: https://myaccount.google.com/apppasswords
   - O: Seguridad → Verificación en 2 pasos → Contraseñas de aplicaciones
   - Selecciona "Aplicación": Correo
   - Selecciona "Dispositivo": Otro (personalizado) → Escribe "ModuStackHome"
   - Haz clic en "Generar"
   - Copia la contraseña de 16 caracteres (sin espacios)

3. **Usar la contraseña generada**:
   - Pega la contraseña en `MAIL_PASSWORD` en tu archivo `.env`
   - **Importante:** No uses tu contraseña normal de Gmail

---

## Configuración en `config/mail.php`

El archivo `config/mail.php` ya está configurado con los valores por defecto correctos:

```php
'default' => env('MAIL_MAILER', 'smtp'),

'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST', 'smtp.gmail.com'),
        'port' => env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        // ...
    ],
],

'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'rulos26@gmail.com'),
    'name' => env('MAIL_FROM_NAME', 'ModuStackHome'),
],
```

---

## Configuración Actual

### Valores configurados:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rulos26@gmail.com
MAIL_PASSWORD=imltkpfnvehflplt
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="rulos26@gmail.com"
MAIL_FROM_NAME="ModuStackHome"
```

---

## Verificación de la Configuración

### 1. Limpiar caché de configuración

Después de modificar el `.env`, ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Probar el envío de correo

Puedes probar el envío de correo usando Tinker:

```bash
php artisan tinker
```

Luego ejecuta:

```php
Mail::raw('Este es un correo de prueba', function ($message) {
    $message->to('tu-email@ejemplo.com')
            ->subject('Prueba de Correo ModuStackHome');
});
```

### 3. Verificar logs

Si hay errores, revisa los logs en `storage/logs/laravel.log`

---

## Puertos Alternativos para Gmail

Si el puerto 587 no funciona, puedes intentar:

- **Puerto 465 con SSL:**
  ```env
  MAIL_PORT=465
  MAIL_ENCRYPTION=ssl
  ```

- **Puerto 25 (menos recomendado):**
  ```env
  MAIL_PORT=25
  MAIL_ENCRYPTION=tls
  ```

---

## Solución de Problemas Comunes

### Error: "Connection could not be established"

**Causa:** Gmail bloquea conexiones no seguras.

**Solución:**
1. Verifica que `MAIL_ENCRYPTION=tls` esté configurado
2. Asegúrate de usar una contraseña de aplicación, no tu contraseña normal
3. Verifica que la verificación en 2 pasos esté activada

### Error: "Authentication failed"

**Causa:** Credenciales incorrectas o contraseña de aplicación inválida.

**Solución:**
1. Genera una nueva contraseña de aplicación
2. Verifica que `MAIL_USERNAME` sea tu correo completo
3. Asegúrate de que no haya espacios en `MAIL_PASSWORD`

### Error: "SMTP connect() failed"

**Causa:** Problemas de conexión o firewall.

**Solución:**
1. Verifica tu conexión a internet
2. Prueba con otro puerto (465 con SSL)
3. Verifica que tu firewall no bloquee el puerto 587

---

## Seguridad

### ⚠️ Importante:

1. **Nunca subas tu archivo `.env` a Git** - Ya está en `.gitignore`
2. **Usa contraseñas de aplicación** - No uses tu contraseña principal de Gmail
3. **Rota las contraseñas periódicamente** - Genera nuevas contraseñas de aplicación cada cierto tiempo
4. **En producción:** Considera usar servicios profesionales como Mailgun, SendGrid, o AWS SES

---

## Alternativas a Gmail SMTP

Si Gmail no es adecuado para tu caso, considera:

### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=tu-dominio.com
MAILGUN_SECRET=tu-secret
```

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key
```

### AWS SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=tu-key
AWS_SECRET_ACCESS_KEY=tu-secret
AWS_DEFAULT_REGION=us-east-1
```

---

## Referencias

- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Gmail SMTP Settings](https://support.google.com/mail/answer/7126229)
- [Google App Passwords](https://support.google.com/accounts/answer/185833)

---

## Notas

- La configuración actual usa Gmail SMTP con TLS
- El remitente por defecto es `rulos26@gmail.com`
- El nombre del remitente es "ModuStackHome" (configurable con `${APP_NAME}`)
- Se recomienda usar contraseñas de aplicación para mayor seguridad

