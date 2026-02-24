# ✅ CHECKLIST DE DEPLOYMENT - Hostinger (Subcarpeta)

## 📋 ANTES DE SUBIR (EN TU PC LOCAL)

- [ ] Ejecutar `.\deploy-prepare.ps1` (preparación automática)
- [ ] Verificar que existe `public/build/` con assets compilados
- [ ] Verificar que `public/.htaccess` tiene `RewriteBase /sistemaagua/`
- [ ] Anotar tu APP_KEY actual (desde `.env` local) por seguridad
- [ ] Hacer backup de la base de datos local

## 📤 SUBIR ARCHIVOS A HOSTINGER

### Conectar al servidor:
- [ ] Acceder a hPanel de Hostinger
- [ ] Ir a **Administrador de Archivos** o usar FileZilla

### Crear estructura:
- [ ] Crear carpeta `public_html/sistemaagua/`
- [ ] Verificar que la ruta completa es correcta

### Subir archivos (EXCLUIR):
- [ ] ❌ NO subir `/vendor`
- [ ] ❌ NO subir `/node_modules`  
- [ ] ❌ NO subir `/.env`
- [ ] ❌ NO subir `/storage/logs/*.log`
- [ ] ❌ NO subir `/.git`
- [ ] ❌ NO subir `/tests`

### Subir archivos (SÍ INCLUIR):
- [ ] ✅ Raíz del proyecto (except exclusiones)
- [ ] ✅ `/public/build/` (assets de Vite)
- [ ] ✅ `/.htaccess` (raíz)
- [ ] ✅ `/public/.htaccess` (con RewriteBase)
- [ ] ✅ `/storage/.htaccess`
- [ ] ✅ `/bootstrap/cache/.htaccess`

## 🗄️ CONFIGURAR BASE DE DATOS

- [ ] En hPanel > **Bases de datos** > **MySQL**
- [ ] Crear nueva base de datos
- [ ] Anotar: Nombre, Usuario, Contraseña, Host (localhost)
- [ ] Verificar que el usuario tiene todos los permisos

## 🔧 CONFIGURAR EN EL SERVIDOR

### Crear archivo .env:
- [ ] Ir a `sistemaagua/` en el File Manager
- [ ] Crear archivo `.env` (usar `.env.production.example` como base)
- [ ] Actualizar credenciales de base de datos
- [ ] Poner `APP_ENV=production`
- [ ] Poner `APP_DEBUG=false`
- [ ] Poner `APP_URL=https://pyfsasoftware.com.ar/sistemaagua`

### Permisos de carpetas:
- [ ] `storage/` → **755**
- [ ] `storage/framework/` → **755**
- [ ] `storage/logs/` → **755**
- [ ] `bootstrap/cache/` → **755**

## 💻 COMANDOS EN EL SERVIDOR

### Vía SSH o Terminal de hPanel:

```bash
cd public_html/sistemaagua
```

- [ ] `php artisan key:generate` (genera APP_KEY)
- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] `php artisan migrate --force`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`

### Verificar:
- [ ] `php artisan about` (ver estado general)
- [ ] `php artisan config:show app` (ver configuración)

## 🔐 SEGURIDAD

- [ ] Activar SSL en hPanel (**Seguridad** > **SSL**)
- [ ] Forzar HTTPS (ya configurado en AppServiceProvider)
- [ ] Verificar que `.env` no sea accesible públicamente
- [ ] Verificar que `storage/` no sea accesible

## ✅ PRUEBAS FUNCIONALES

- [ ] Acceder a `https://pyfsasoftware.com.ar/sistemaagua`
- [ ] Verificar que carga la página principal
- [ ] Verificar que los estilos CSS se aplican
- [ ] Intentar hacer login
- [ ] Crear un usuario de prueba
- [ ] Verificar que las rutas funcionan
- [ ] Probar crear un cliente
- [ ] Probar crear un vehículo
- [ ] Probar crear un pago
- [ ] Verificar que se crean movimientos de cuenta

## 🚨 SI ALGO FALLA

### Revisar logs:
- [ ] Ver `storage/logs/laravel.log`
- [ ] Activar temporalmente `APP_DEBUG=true` (SOLO para debuggear)

### Errores comunes:

**500 Internal Server Error:**
- [ ] Verificar permisos de `storage/` y `bootstrap/cache/`
- [ ] Verificar que APP_KEY esté generada
- [ ] Ejecutar `php artisan cache:clear`

**404 Not Found en rutas:**
- [ ] Verificar `RewriteBase /sistemaagua/` en `public/.htaccess`
- [ ] Ejecutar `php artisan route:cache`

**Estilos CSS no cargan:**
- [ ] Verificar APP_URL en `.env`
- [ ] Verificar que existe `public/build/`
- [ ] Ejecutar `php artisan config:cache`

**Error de base de datos:**
- [ ] Verificar credenciales en `.env`
- [ ] Verificar que DB_HOST=localhost
- [ ] Verificar que la base de datos existe

## 📊 POST-DEPLOYMENT

### Primera vez:
- [ ] Crear usuario administrador: `php artisan db:seed --class=UserSeeder --force`
- [ ] O crear manualmente vía Tinker:
  ```bash
  php artisan tinker
  User::create(['name'=>'Admin','email'=>'admin@mail.com','password'=>bcrypt('password'),'role'=>'administrador'])
  ```

### Actualizaciones futuras:
- [ ] Hacer backup de BD antes de actualizar
- [ ] Subir solo archivos modificados
- [ ] Ejecutar `php artisan migrate --force` si hay nuevas migraciones
- [ ] Ejecutar `php artisan config:cache`
- [ ] Ejecutar `php artisan view:cache`
- [ ] Ejecutar `php artisan route:cache`

## 🎉 ¡DEPLOYMENT EXITOSO!

Si completaste todos los pasos, tu aplicación debería estar funcionando en:

**🌐 https://pyfsasoftware.com.ar/sistemaagua**

Para soporte, consulta: **DEPLOYMENT_GUIDE.md**
