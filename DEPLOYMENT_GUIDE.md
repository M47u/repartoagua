# Guía de Despliegue - Laravel en Hostinger (Subcarpeta)

## 🎯 Objetivo
Desplegar la aplicación RepartoAgua en: `https://pyfsasoftware.com.ar/sistemaagua`

---

## 📋 PARTE 1: PREPARACIÓN LOCAL

### Paso 1: Configurar el APP_URL para subcarpeta

Edita el archivo `.env`:
```env
APP_URL=https://pyfsasoftware.com.ar/sistemaagua
```

### Paso 2: Actualizar rutas en el AppServiceProvider

Edita `app/Providers/AppServiceProvider.php` y agrega en el método `boot()`:

```php
public function boot(): void
{
    // Forzar HTTPS en producción
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
```

Asegúrate de importar:
```php
use Illuminate\Support\Facades\URL;
```

### Paso 3: Crear archivo .htaccess para la subcarpeta

Crea un archivo `.htaccess` en la raíz del proyecto (no en public):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Paso 4: Modificar public/.htaccess

Edita `public/.htaccess` y reemplaza su contenido con:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    
    # Establecer la base para subcarpeta
    RewriteBase /sistemaagua/
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Paso 5: Optimizar para producción

Ejecuta estos comandos en tu terminal local:

```bash
# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimizar autoload de Composer
composer install --optimize-autoloader --no-dev

# Cachear configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Paso 6: Preparar archivos para subir

**IMPORTANTE:** NO subas estos archivos/carpetas:
- ❌ `/vendor` (se generará en el servidor)
- ❌ `/node_modules`
- ❌ `.env` (créalo directamente en el servidor)
- ❌ `/storage/*.log`

---

## 📤 PARTE 2: SUBIR ARCHIVOS A HOSTINGER

### Paso 7: Conectar por FTP/SFTP

1. Accede a tu panel de Hostinger (hPanel)
2. Ve a **Archivos → Administrador de archivos** o usa un cliente FTP (FileZilla)
3. Credenciales FTP:
   - **Host:** ftp.pyfsasoftware.com.ar
   - **Usuario:** (tu usuario de hosting)
   - **Puerto:** 21 (o 22 para SFTP)

### Paso 8: Crear estructura de carpetas

En el servidor, crea esta estructura:
```
public_html/
├── sistemaagua/          ← Aquí va TODO tu proyecto Laravel
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/           ← Esta será la carpeta pública
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── .htaccess         ← Archivo raíz que creamos
│   └── artisan
```

### Paso 9: Subir archivos

Sube TODOS los archivos del proyecto a `public_html/sistemaagua/` EXCEPTO:
- vendor/
- node_modules/
- .env
- storage/logs/*.log

---

## 🔧 PARTE 3: CONFIGURACIÓN EN EL SERVIDOR

### Paso 10: Crear archivo .env en el servidor

Crea `public_html/sistemaagua/.env` con:

```env
APP_NAME="Reparto Agua"
APP_ENV=production
APP_KEY=base64:TU_APP_KEY_AQUI
APP_DEBUG=false
APP_TIMEZONE=America/Argentina/Buenos_Aires
APP_URL=https://pyfsasoftware.com.ar/sistemaagua

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_AR

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

# Base de datos - Obtén estos datos de Hostinger
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_base_datos
DB_PASSWORD=contraseña_base_datos

# Sesiones y caché
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/sistemaagua
SESSION_DOMAIN=.pyfsasoftware.com.ar

CACHE_STORE=database
CACHE_PREFIX=reparto_

# Broadcasting, Queue, etc
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

# Mail (opcional)
MAIL_MAILER=log
```

### Paso 11: Generar APP_KEY

Conéctate por SSH (si Hostinger lo permite) o usa el Terminal de hPanel:

```bash
cd public_html/sistemaagua
php artisan key:generate
```

Si no tienes acceso SSH, genera la key localmente y cópiala al .env del servidor:
```bash
php artisan key:generate --show
```

### Paso 12: Configurar permisos de carpetas

En el servidor, ajusta permisos (vía FTP o File Manager):

```
sistemaagua/storage/           → 755
sistemaagua/storage/framework/ → 755
sistemaagua/storage/logs/      → 755
sistemaagua/bootstrap/cache/   → 755
```

Todos los archivos dentro de estas carpetas: **644**

### Paso 13: Instalar dependencias de Composer

Por SSH o Terminal de hPanel:

```bash
cd public_html/sistemaagua
composer install --optimize-autoloader --no-dev
```

**Si NO tienes acceso a Composer en el servidor:**
1. Sube la carpeta `/vendor` desde tu local (después de ejecutar el `composer install`)
2. Asegúrate de que sea la versión con `--no-dev`

### Paso 14: Configurar base de datos

1. En hPanel de Hostinger, ve a **Bases de datos → MySQL**
2. Crea una nueva base de datos:
   - Nombre: `u123456789_reparto` (ejemplo)
   - Usuario: crea uno nuevo
   - Contraseña: genera una segura
3. Actualiza estos datos en el `.env` del servidor

### Paso 15: Ejecutar migraciones

Por SSH/Terminal:

```bash
cd public_html/sistemaagua
php artisan migrate --force
```

Si quieres datos de prueba (opcional):
```bash
php artisan db:seed --force
```

### Paso 16: Cachear configuración

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔐 PARTE 4: SEGURIDAD ADICIONAL

### Paso 17: Proteger carpetas sensibles

Crea archivos `.htaccess` en estas carpetas del servidor:

**`sistemaagua/.htaccess`** (si no existe):
```apache
# Denegar acceso directo a archivos
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

**`sistemaagua/storage/.htaccess`**:
```apache
Options -Indexes
Deny from all
```

**`sistemaagua/bootstrap/cache/.htaccess`**:
```apache
Options -Indexes
Deny from all
```

### Paso 18: Configurar SSL (HTTPS)

En Hostinger:
1. Ve a **Seguridad → SSL**
2. Activa el SSL gratuito para `pyfsasoftware.com.ar`
3. Forzar HTTPS (viene activado por el AppServiceProvider)

---

## ✅ PARTE 5: VERIFICACIÓN

### Paso 19: Probar la aplicación

Accede a: `https://pyfsasoftware.com.ar/sistemaagua`

**Checklist de pruebas:**
- [ ] La página principal carga correctamente
- [ ] Los estilos CSS se aplican (verifica en DevTools)
- [ ] Puedes hacer login
- [ ] Las rutas funcionan sin errores 404
- [ ] Las imágenes cargan
- [ ] Los formularios funcionan
- [ ] La base de datos se conecta

### Paso 20: Verificar logs de errores

Si algo falla, revisa:
```
sistemaagua/storage/logs/laravel.log
```

---

## 🚨 SOLUCIÓN DE PROBLEMAS COMUNES

### Error: "404 Not Found" en rutas

**Solución:** Verifica que el `.htaccess` en `public/` tenga `RewriteBase /sistemaagua/`

### Error: "500 Internal Server Error"

**Causas comunes:**
1. Permisos incorrectos en `storage/` y `bootstrap/cache/`
2. APP_KEY no generada en el `.env`
3. Archivos de caché corruptos

**Solución:**
```bash
php artisan cache:clear
php artisan config:clear
chmod -R 755 storage bootstrap/cache
```

### Los estilos CSS no cargan

**Solución:** Verifica que el `APP_URL` en `.env` sea correcto:
```env
APP_URL=https://pyfsasoftware.com.ar/sistemaagua
```

Luego:
```bash
php artisan config:cache
```

### Error de conexión a base de datos

**Solución:** Verifica en `.env`:
- `DB_HOST` debe ser `localhost` (en Hostinger)
- Credenciales correctas
- Base de datos existe y usuario tiene permisos

### Las sesiones no funcionan

**Solución:** Verifica en `.env`:
```env
SESSION_DRIVER=database
SESSION_PATH=/sistemaagua
SESSION_DOMAIN=.pyfsasoftware.com.ar
```

Ejecuta la migración de sesiones si no existe:
```bash
php artisan session:table
php artisan migrate --force
```

---

## 📝 NOTAS IMPORTANTES

### Vite Assets (CSS/JS compilados)

Si usas Vite, DEBES compilar los assets localmente antes de subir:

```bash
# Local
npm install
npm run build
```

Luego sube la carpeta `public/build/` al servidor.

### Actualizaciones futuras

Cuando hagas cambios:

```bash
# Local
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache

# Sube archivos modificados
# En servidor:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

### Comandos artisan útiles en producción

```bash
# Limpiar todo
php artisan optimize:clear

# Optimizar todo
php artisan optimize

# Ver configuración
php artisan config:show

# Ver rutas
php artisan route:list
```

---

## 🎉 ¡LISTO!

Tu aplicación debería estar funcionando en:
**https://pyfsasoftware.com.ar/sistemaagua**

Si encuentras problemas, revisa los logs en `storage/logs/laravel.log`
