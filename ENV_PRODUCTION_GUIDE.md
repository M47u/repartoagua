# 📝 Guía: Configurar .env en Producción

## 🎯 Archivo Creado

Se ha generado **`.env.production`** listo para subir a Hostinger.

---

## 📋 PASOS PARA USAR EN HOSTINGER

### 1️⃣ Obtener Credenciales de Base de Datos

En hPanel de Hostinger:
1. Ve a **Bases de datos** → **MySQL**
2. Crea una nueva base de datos (o usa una existente)
3. Anota estos datos:
   - **Nombre de BD:** (ej: u123456789_reparto)
   - **Usuario:** (ej: u123456789_user)
   - **Contraseña:** (la que generaste)
   - **Host:** localhost (siempre en Hostinger)

### 2️⃣ Configurar Email SMTP (Opcional)

Si quieres que la aplicación envíe emails:

1. En hPanel → **Emails** → **Cuentas de email**
2. Crea una cuenta: `noreply@pyfsasoftware.com.ar`
3. Anota la contraseña

**Si NO necesitas emails ahora:**
- Comenta las líneas de SMTP en el .env
- Descomenta la línea `MAIL_MAILER=log`

### 3️⃣ Subir y Editar el .env

**Opción A: Vía Administrador de Archivos (hPanel)**

1. Abre el archivo `.env.production` en tu PC
2. **Copia todo el contenido**
3. Ve a hPanel → **Administrador de Archivos**
4. Navega a `public_html/sistemaagua/`
5. Click en **Nuevo archivo** → Nombre: `.env`
6. Edita el archivo y **pega el contenido**
7. **Actualiza estos valores:**

```env
# Líneas a modificar:
DB_DATABASE=u123456789_reparto          # ← Tu nombre de BD real
DB_USERNAME=u123456789_user              # ← Tu usuario de BD real
DB_PASSWORD=TU_PASSWORD_AQUI             # ← Tu contraseña de BD real

MAIL_USERNAME=noreply@pyfsasoftware.com.ar  # ← Tu email
MAIL_PASSWORD=TU_PASSWORD_EMAIL              # ← Contraseña del email
```

8. Guarda el archivo

**Opción B: Vía SSH/Terminal**

```bash
cd public_html/sistemaagua

# Crear el .env desde la plantilla local
# (previamente sube .env.production al servidor)
cp .env.production .env

# Editar con nano
nano .env

# Modificar las líneas de credenciales y guardar
# CTRL+O para guardar, CTRL+X para salir
```

### 4️⃣ Verificar Configuración

Después de guardar el .env, ejecuta:

```bash
cd public_html/sistemaagua

# Ver la configuración (sin mostrar contraseñas)
php artisan config:show app
php artisan config:show database

# Si todo está OK, cachear
php artisan config:cache
```

---

## ⚙️ CONFIGURACIONES CRÍTICAS

### ✅ Ya Configuradas Correctamente

Las siguientes configuraciones YA están listas en `.env.production`:

| Variable | Valor | Motivo |
|----------|-------|--------|
| `APP_ENV` | production | Entorno de producción |
| `APP_DEBUG` | false | Ocultar errores detallados |
| `APP_URL` | https://pyfsasoftware.com.ar/sistemaagua | URL correcta |
| `SESSION_PATH` | /sistemaagua | Para subcarpeta |
| `SESSION_DOMAIN` | .pyfsasoftware.com.ar | Dominio correcto |
| `DB_HOST` | localhost | Host de Hostinger |
| `LOG_LEVEL` | error | Solo errores graves |
| `CACHE_PREFIX` | reparto_ | Evita conflictos |
| `APP_LOCALE` | es | Español |

### ⚠️ Debes Modificar

| Variable | ¿Qué poner? |
|----------|-------------|
| `DB_DATABASE` | Nombre de tu BD en Hostinger |
| `DB_USERNAME` | Usuario de tu BD en Hostinger |
| `DB_PASSWORD` | Contraseña de tu BD en Hostinger |
| `MAIL_USERNAME` | Tu email (si usas SMTP) |
| `MAIL_PASSWORD` | Contraseña del email (si usas SMTP) |

### 🔐 NO Modificar

| Variable | ⚠️ No Cambiar |
|----------|---------------|
| `APP_KEY` | Ya está generada - mantener igual |
| `SESSION_PATH` | Debe ser /sistemaagua |
| `DB_HOST` | Debe ser localhost en Hostinger |

---

## 🧪 PROBAR LA CONFIGURACIÓN

Después de configurar el .env:

```bash
cd public_html/sistemaagua

# Probar conexión a BD
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Si no da error, la BD está OK

# Cachear configuración
php artisan config:cache
```

---

## 🚨 SOLUCIÓN DE PROBLEMAS

### Error: "Access denied for user"

**Causa:** Credenciales de BD incorrectas

**Solución:**
1. Verifica en hPanel → Bases de datos
2. Confirma usuario y contraseña
3. Asegúrate que el usuario tenga permisos sobre la BD
4. Edita el .env con los datos correctos
5. Ejecuta: `php artisan config:clear`

### Error: "Unknown database"

**Causa:** El nombre de la BD no existe

**Solución:**
1. Ve a hPanel → Bases de datos → MySQL
2. Crea la base de datos
3. Actualiza `DB_DATABASE` en el .env
4. Ejecuta: `php artisan config:clear`

### Las sesiones no funcionan

**Causa:** SESSION_PATH incorrecto

**Solución:**
```env
# En .env debe estar exactamente:
SESSION_PATH=/sistemaagua
SESSION_DOMAIN=.pyfsasoftware.com.ar
```

Luego:
```bash
php artisan config:clear
php artisan config:cache
```

### Los emails no se envían

**Opción 1: Configurar SMTP correctamente**
1. Verifica que la cuenta de email existe en hPanel
2. Confirma la contraseña
3. Verifica que el puerto sea 465 y encryption sea ssl

**Opción 2: Desactivar temporalmente**
```env
MAIL_MAILER=log
```

---

## 📋 CHECKLIST DE VERIFICACIÓN

Antes de continuar, verifica:

- [ ] Archivo `.env` creado en `public_html/sistemaagua/`
- [ ] `DB_DATABASE` actualizado con tu BD real
- [ ] `DB_USERNAME` actualizado con tu usuario real
- [ ] `DB_PASSWORD` actualizado con tu contraseña real
- [ ] `APP_KEY` copiado (no cambiar)
- [ ] `SESSION_PATH=/sistemaagua` (no cambiar)
- [ ] `DB_HOST=localhost` (no cambiar)
- [ ] `APP_DEBUG=false` (no cambiar)
- [ ] Ejecutado: `php artisan config:cache`

---

## 📞 SIGUIENTE PASO

Una vez configurado el .env correctamente:

```bash
# Ejecutar migraciones para crear tablas
php artisan migrate --force

# Verificar que todo funciona
php artisan about
```

Luego accede a: **https://pyfsasoftware.com.ar/sistemaagua**

---

**Consulta también:**
- [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) - Guía completa
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Solución de problemas
- [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) - Checklist paso a paso
