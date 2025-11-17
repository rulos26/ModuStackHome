# Configuración del archivo .env para ModuStackHome

## Archivo .env de Ejemplo

Copia este contenido a tu archivo `.env` y ajusta los valores según tu entorno:

```env
APP_NAME="ModuStackHome"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE="America/Mexico_City"
APP_URL=http://localhost
ASSET_URL=

# Configuración de Idioma (Español)
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_MX

# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modustackhome
DB_USERNAME=root
DB_PASSWORD=

# Cache y Sesiones
BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Memcached (si se usa)
MEMCACHED_HOST=127.0.0.1

# Redis (si se usa)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Correo Electrónico
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@modustackhome.com"
MAIL_FROM_NAME="${APP_NAME}"

# AWS (si se usa)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Vite
VITE_APP_NAME="${APP_NAME}"
```

## Configuraciones Importantes para Español

### Idioma de la Aplicación
```env
APP_LOCALE=es              # Idioma principal: Español
APP_FALLBACK_LOCALE=es     # Idioma de respaldo: Español
APP_FAKER_LOCALE=es_MX    # Locale para datos de prueba: Español México
```

### Zona Horaria
```env
APP_TIMEZONE="America/Mexico_City"  # Zona horaria de México
```

Otras zonas horarias comunes en español:
- `America/Mexico_City` - Ciudad de México
- `America/Bogota` - Colombia
- `America/Santiago` - Chile
- `America/Buenos_Aires` - Argentina
- `America/Lima` - Perú
- `Europe/Madrid` - España

### Nombre de la Aplicación
```env
APP_NAME="ModuStackHome"
```

### URL de Assets (si está en subdirectorio)
```env
ASSET_URL=/ModuStackHome/public
```

O la URL completa:
```env
ASSET_URL=https://rulossoluciones.com/ModuStackHome/public
```

## Notas

- **APP_KEY**: Debe generarse con `php artisan key:generate` si no existe
- **DB_DATABASE**: Cambiar por el nombre de tu base de datos
- **DB_USERNAME** y **DB_PASSWORD**: Credenciales de tu base de datos
- **MAIL_***: Configurar según tu proveedor de correo
- **APP_DEBUG**: Cambiar a `false` en producción

## Generar APP_KEY

Si el archivo `.env` no tiene `APP_KEY`, ejecuta:

```bash
php artisan key:generate
```

## Limpiar Caché después de cambios

Después de modificar el `.env`, limpia la caché:

```bash
php artisan config:clear
php artisan cache:clear
```

