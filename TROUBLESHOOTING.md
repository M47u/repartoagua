# 🔧 Troubleshooting - Problemas Comunes en Hostinger

## 🚨 Error 500 - Internal Server Error

### Causa 1: Permisos incorrectos
**Síntoma:** Error 500 al acceder al sitio

**Solución:**
```bash
cd public_html/sistemaagua
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

### Causa 2: APP_KEY no generada
**Síntoma:** "No application encryption key has been specified"

**Solución:**
```bash
php artisan key:generate
```

### Causa 3: Caché corrupto
**Síntoma:** Errores aleatorios después de subir archivos

**Solución:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
# Volver a cachear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔍 Error 404 - Not Found en Rutas

### Causa 1: RewriteBase incorrecto
**Síntoma:** Dashboard funciona pero rutas internas dan 404

**Solución:**
Editar `public/.htaccess` y verificar:
```apache
RewriteBase /sistemaagua/
```

### Causa 2: mod_rewrite no activo
**Síntoma:** Todas las rutas excepto la principal dan 404

**Solución:**
Contactar soporte de Hostinger para activar `mod_rewrite` (normalmente está activo)

---

## 🎨 Estilos CSS no Cargan

### Causa 1: APP_URL incorrecto
**Síntoma:** La página se ve sin estilos

**Solución:**
Verificar en `.env`:
```env
APP_URL=https://pyfsasoftware.com.ar/sistemaagua
```

Luego:
```bash
php artisan config:clear
php artisan config:cache
```

### Causa 2: Assets no compilados
**Síntoma:** Error 404 en archivos CSS/JS

**Solución:**
En local, antes de subir:
```bash
npm run build
```

Asegúrate de subir la carpeta `public/build/` al servidor.

### Causa 3: Ruta de Vite incorrecta
**Síntoma:** Error "Vite manifest not found"

**Solución:**
```bash
# Borrar caché de Vite
rm -rf public/build
# En local, recompilar
npm run build
# Subir de nuevo public/build/
```

---

## 🗄️ Error de Conexión a Base de Datos

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Síntoma:** No puede conectar a MySQL

**Solución:**
Verificar en `.env`:
```env
DB_HOST=localhost  # En Hostinger SIEMPRE es localhost
DB_PORT=3306
DB_DATABASE=nombre_correcto_bd
DB_USERNAME=usuario_correcto
DB_PASSWORD=password_correcto
```

### Error: "Access denied for user"
**Síntoma:** Usuario o contraseña incorrectos

**Solución:**
1. Ir a hPanel > Bases de datos
2. Verificar usuario y contraseña
3. Asegurarse de que el usuario tenga permisos sobre la BD
4. Recrear usuario si es necesario

### Error: "Unknown database"
**Síntoma:** La base de datos no existe

**Solución:**
1. Ir a hPanel > Bases de datos
2. Crear la base de datos
3. Actualizar nombre en `.env`

---

## 🔐 Problemas con Sesiones

### Error: "Session store not set on request"
**Síntoma:** No puede mantener sesión de login

**Solución:**
En `.env`:
```env
SESSION_DRIVER=database
SESSION_PATH=/sistemaagua
SESSION_DOMAIN=.pyfsasoftware.com.ar
```

Verificar tabla de sesiones:
```bash
php artisan session:table
php artisan migrate --force
```

### Las sesiones se pierden constantemente
**Síntoma:** Te desloguea automáticamente

**Solución:**
```bash
# Limpiar sesiones
php artisan cache:clear
# Verificar configuración
php artisan config:show session
```

---

## 📝 Error de Migraciones

### Error: "Class not found" al migrar
**Síntoma:** Migraciones fallan por clases no encontradas

**Solución:**
```bash
composer dump-autoload
php artisan migrate --force
```

### Error: "Table already exists"
**Síntoma:** Intentas migrar y las tablas ya existen

**Solución - CUIDADO (borra datos):**
```bash
php artisan migrate:fresh --force
```

**Solución segura:**
```bash
# Ver estado
php artisan migrate:status
# Migrar solo pendientes
php artisan migrate --force
```

---

## 🚫 Error: "403 Forbidden"

### Causa: Permisos de directorios
**Síntoma:** Error 403 al acceder a archivos/carpetas

**Solución:**
```bash
# Carpetas
chmod 755 public_html/sistemaagua
chmod 755 public_html/sistemaagua/public
# Archivos
find public_html/sistemaagua -type f -exec chmod 644 {} \;
```

---

## 📧 Problemas con Email

### Error: "Connection could not be established"
**Síntoma:** No puede enviar emails

**Solución - Hostinger SMTP:**
En `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=tu-email@pyfsasoftware.com.ar
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@pyfsasoftware.com.ar
```

### Desactivar email temporalmente
```env
MAIL_MAILER=log
```

---

## 🧹 Comandos de Limpieza General

### Limpiar TODO
```bash
php artisan optimize:clear
```
Equivale a ejecutar:
- cache:clear
- config:clear
- route:clear
- view:clear
- compiled:clear

### Optimizar TODO
```bash
php artisan optimize
```
Equivale a cachear:
- config:cache
- route:cache
- view:cache

---

## 🔍 Comandos de Diagnóstico

### Ver información general
```bash
php artisan about
```

### Ver configuración específica
```bash
php artisan config:show app
php artisan config:show database
php artisan config:show session
```

### Ver todas las rutas
```bash
php artisan route:list
```

### Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log
```

### Verificar conexión a BD
```bash
php artisan tinker
DB::connection()->getPdo();
```

---

## 🛠️ Comandos de Mantenimiento

### Poner en modo mantenimiento
```bash
php artisan down --secret="codigo-secreto"
# Acceder con: /codigo-secreto
```

### Quitar modo mantenimiento
```bash
php artisan up
```

### Limpiar logs antiguos
```bash
rm storage/logs/*.log
```

### Limpiar sesiones antiguas
```bash
php artisan session:gc
```

---

## 📱 Verificar desde el Navegador

### DevTools - Console
Abre F12 > Console y busca errores JavaScript:
- `Mixed Content` = problema de HTTPS/HTTP
- `404` = archivos no encontrados
- `CORS` = problema de origen cruzado

### DevTools - Network
F12 > Network para ver:
- Qué archivos están dando 404
- Qué archivos CSS/JS no cargan
- Respuestas del servidor

---

## ⚡ Modo Debug (SOLO TEMPORALMENTE)

**PELIGRO: Nunca dejes esto activado en producción**

Para ver errores detallados, edita `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

**IMPORTANTE:** Una vez resuelto el problema, volver a:
```env
APP_DEBUG=false
APP_ENV=production
```

---

## 📞 Contactar Soporte de Hostinger

Si nada funciona:

1. **Live Chat**: Disponible 24/7
2. **Ticket**: Desde hPanel > Ayuda
3. **Teléfono**: Consultar en su sitio web

**Información útil para dar a soporte:**
- Ruta del proyecto: `public_html/sistemaagua`
- Error exacto del log: `storage/logs/laravel.log`
- Versión de PHP: `php -v`
- Captura de pantalla del error

---

## ✅ Checklist Rápido de Solución

Cuando algo falla, ejecuta EN ORDEN:

```bash
# 1. Limpiar todo
php artisan optimize:clear

# 2. Verificar permisos
chmod -R 755 storage bootstrap/cache

# 3. Verificar .env
cat .env | grep APP_URL
cat .env | grep DB_

# 4. Regenerar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Verificar conexión BD
php artisan tinker
DB::connection()->getPdo();

# 6. Ver logs
tail -20 storage/logs/laravel.log
```

Si con esto no se resuelve, revisa los casos específicos arriba. 👆
