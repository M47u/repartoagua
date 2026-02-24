# ⚡ RESUMEN RÁPIDO - .env para Producción

## ✅ Archivo Listo: `.env.production`

Este archivo contiene **TODO configurado** para tu servidor Hostinger.

---

## 🎯 Lo que YA está configurado (NO cambiar):

```env
APP_NAME="Reparto Agua"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:7YdZY6sQWWlXbBQ/bZ1ucx/VfCclQZ14n5Is0fT/Jo0=  ← YA GENERADA
APP_URL=https://pyfsasoftware.com.ar/sistemaagua
SESSION_PATH=/sistemaagua
SESSION_DOMAIN=.pyfsasoftware.com.ar
DB_HOST=localhost
```

---

## ⚠️ Lo que DEBES cambiar en Hostinger:

### 1. Obtén tus credenciales de BD
Ve a **hPanel → Bases de datos → MySQL** y anota:

| Campo | Dónde encontrarlo |
|-------|-------------------|
| Nombre de BD | Al crear la base de datos |
| Usuario | Al crear el usuario de BD |
| Contraseña | La que generaste |

### 2. Modifica solo estas 3 líneas:

```env
DB_DATABASE=u123456789_reparto    # ← Poner tu nombre de BD
DB_USERNAME=u123456789_user       # ← Poner tu usuario
DB_PASSWORD=TU_PASSWORD_AQUI      # ← Poner tu contraseña
```

---

## 📝 Pasos en Hostinger (Método más fácil):

### Opción A: Via Administrador de Archivos

1. **Abre** `.env.production` con Notepad/VS Code
2. **Copia todo** (Ctrl+A, Ctrl+C)
3. **Ve a** hPanel → Administrador de Archivos
4. **Navega a** `public_html/sistemaagua/`
5. **Click en** "Nuevo archivo"
6. **Nombre:** `.env`
7. **Click en** Editar
8. **Pega** el contenido completo
9. **Modifica** las 3 líneas de BD (ver arriba)
10. **Guarda** el archivo

### Opción B: Via SSH (si tienes acceso)

```bash
cd public_html/sistemaagua

# Subir .env.production primero al servidor, luego:
cp .env.production .env

# Editar con nano
nano .env

# Modificar DB_DATABASE, DB_USERNAME, DB_PASSWORD
# CTRL+O para guardar, CTRL+X para salir

# Cachear configuración
php artisan config:cache
```

---

## ✅ Verificar que funciona:

```bash
cd public_html/sistemaagua

# Probar conexión a BD
php artisan tinker
>>> DB::connection()->getPdo();
# Si no da error, está OK
>>> exit

# Cachear
php artisan config:cache
```

---

## 🚫 ERRORES COMUNES:

### "Access denied for user"
**Causa:** Usuario/contraseña incorrectos  
**Solución:** Revisar credenciales en hPanel

### "Unknown database"
**Causa:** Nombre de BD incorrecto  
**Solución:** Verificar nombre exacto en hPanel

### "Session store not set"
**Causa:** SESSION_PATH incorrecto  
**Solución:** NO cambiar `SESSION_PATH=/sistemaagua`

---

## 📚 Más Información:

- **Guía completa:** [ENV_PRODUCTION_GUIDE.md](ENV_PRODUCTION_GUIDE.md)
- **Deployment completo:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- **Problemas:** [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 💡 Recordatorios Importantes:

- ✅ **APP_KEY** ya está generada - NO ejecutar `php artisan key:generate`
- ✅ **DB_HOST** debe ser `localhost` en Hostinger
- ✅ **SESSION_PATH** debe ser `/sistemaagua` (para subcarpeta)
- ⚠️ **APP_DEBUG** debe estar en `false` SIEMPRE en producción
- ⚠️ Solo modificar credenciales de BD, nada más

---

**¿Listo?** Una vez configurado el .env, continúa con las migraciones:
```bash
php artisan migrate --force
```
