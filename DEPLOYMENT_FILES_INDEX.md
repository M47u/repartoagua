# 📦 Archivos de Deployment Creados

Se han creado los siguientes archivos para facilitar el deployment en Hostinger:

---

## 📚 Documentación

### 1. **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)**
📖 Guía COMPLETA paso a paso con todos los detalles

**Contiene:**
- Preparación local (5 pasos)
- Subida de archivos (3 pasos)
- Configuración en servidor (10 pasos)
- Seguridad adicional (2 pasos)
- Verificación (2 pasos)
- Solución de problemas comunes

**Úsalo cuando:** Sea tu primera vez desplegando o necesites entender el proceso completo

---

### 2. **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)**
✅ Checklist interactivo para marcar tareas

**Contiene:**
- Antes de subir (5 checks)
- Subir archivos (10 checks)
- Base de datos (4 checks)
- Configuración servidor (8 checks)
- Comandos (7 checks)
- Seguridad (4 checks)
- Pruebas (10 checks)
- Si algo falla (10 checks)

**Úsalo cuando:** Estés ejecutando el deployment y necesites una lista de verificación

---

### 3. **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)**
🔧 Solución de problemas específicos

**Contiene:**
- Error 500 (3 causas)
- Error 404 (2 causas)
- CSS no carga (3 causas)
- Error de BD (3 tipos)
- Problemas de sesión (2 casos)
- Error de migraciones (2 casos)
- Error 403 (1 causa)
- Problemas de email (2 casos)
- Comandos de limpieza
- Comandos de diagnóstico
- Modo debug

**Úsalo cuando:** Algo falle después del deployment

---

### 4. **[README.md](README.md)** (Actualizado)
📘 Información general del proyecto

**Nuevo contenido:**
- Descripción del sistema
- Características y módulos
- Sección de deployment
- Instalación local
- Estructura del proyecto
- Roles de usuario
- Tecnologías

---

### 5. **[ENV_PRODUCTION_GUIDE.md](ENV_PRODUCTION_GUIDE.md)** ⭐ NUEVO
🔧 Guía completa de configuración del .env en producción

**Contiene:**
- Cómo obtener credenciales de BD en Hostinger
- Configuración de email SMTP
- Pasos detallados para crear .env en servidor
- Configuraciones críticas explicadas
- Qué modificar y qué NO modificar
- Solución de problemas con .env
- Checklist de verificación

**Úsalo cuando:** Necesites configurar el archivo .env en Hostinger

---

## 🔧 Archivos de Configuración

### 6. **[.htaccess](.htaccess)** (Raíz del proyecto)
Redirige todas las peticiones a `public/`

```apache
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

---

### 6. **[public/.htaccess.production](public/.htaccess.production)**
`.htaccess` configurado para SUBCARPETA

**Diferencia clave:**
```apache
RewriteBase /sistemaagua/
```

**Instrucción:** Renombrar a `.htaccess` al subir al servidor

---

### 7. **[.env.production](.env.production)** ⭐ NUEVO
Archivo .env COMPLETO y listo para producción

**Ya incluye:**
- APP_KEY generada (no cambiar)
- APP_ENV=production, APP_DEBUG=false
- APP_URL configurada para subcarpeta
- SESSION_PATH y SESSION_DOMAIN correctos
- Locales en español (es)
- Configuración de BD lista (solo completar credenciales)
- Configuración SMTP de Hostinger
- LOG_LEVEL=error para producción

**⚠️ Solo debes modificar:**
- DB_DATABASE, DB_USERNAME, DB_PASSWORD
- MAIL_USERNAME, MAIL_PASSWORD (si usas email)

**Instrucción:** Copiar contenido completo al .env en el servidor

📖 Guía completa: [ENV_PRODUCTION_GUIDE.md](ENV_PRODUCTION_GUIDE.md)

---

### 8. **[.env.production.example](.env.production.example)**
Plantilla alternativa de configuración para producción

**Incluye:**
- Configuración de APP para producción
- Credenciales de BD (para rellenar)
- Configuración de sesiones para subcarpeta
- Configuración de email SMTP Hostinger
- Comentarios explicativos

**Nota:** Usar **.env.production** es más fácil (ya tiene APP_KEY)

---

### 9. **[storage/.htaccess](storage/.htaccess)**
Protege la carpeta storage

```apache
Options -Indexes
Deny from all
```

---

### 10. **[bootstrap/cache/.htaccess](bootstrap/cache/.htaccess)**
Protege la carpeta de caché

```apache
Options -Indexes
Deny from all
```

---

## 🚀 Scripts de Automatización

### 11. **[deploy-prepare.ps1](deploy-prepare.ps1)**
Script PowerShell para PREPARACIÓN LOCAL

**Ejecuta automáticamente:**
1. Limpieza de cachés
2. Instalación de dependencias de producción
3. Compilación de assets (Vite)
4. Generación de cachés optimizados
5. Configuración de .htaccess para subcarpeta
6. Verificación de estructura
7. Generación de lista de exclusión
8. Instrucciones finales

**Uso:**
```powershell
.\deploy-prepare.ps1
```

---

### 12. **[deploy-server-commands.ps1](deploy-server-commands.ps1)**
Referencia de comandos para EJECUTAR EN EL SERVIDOR

**Contiene:**
- composer install (NO key:generate, ya está en .env.production)
- configuración de permisos
- migraciones
- seeders (opcional)
- caché de optimización
- comandos de verificación

**Uso:** Copiar y ejecutar manualmente vía SSH

---

## 📋 Resumen de Uso

### Primera vez desplegando:
```
1. Lee: DEPLOYMENT_GUIDE.md (completo)
2. Ejecuta: deploy-prepare.ps1
3. Lee: ENV_PRODUCTION_GUIDE.md (configuración .env)
4. Sube archivos según el checklist
5. Copia .env.production al servidor como .env
6. Modifica credenciales de BD en el .env
7. Sigue: DEPLOYMENT_CHECKLIST.md (marca cada paso)
8. Si hay errores: TROUBLESHOOTING.md
```

### Ya conoces el proceso:
```
1. Ejecuta: deploy-prepare.ps1
2. Sube archivos
3. Copia .env.production → .env (modifica credenciales)
4. Usa: DEPLOYMENT_CHECKLIST.md (rápido)
5. Si hay problemas: TROUBLESHOOTING.md
```

### Solo actualizar archivos:
```
1. Cambios en código → Sube archivos modificados
2. Cambios en BD → php artisan migrate --force
3. Cambios en config → php artisan config:cache
4. Cambios en rutas → php artisan route:cache
5. Cambios en vistas → php artisan view:cache
```

---

## 🎯 Ruta de Deployment

**URL Final:** `https://pyfsasoftware.com.ar/sistemaagua`

**Ubicación en Servidor:** `public_html/sistemaagua/`

---

## ⚠️ IMPORTANTE - Archivos a NO Subir

❌ `/vendor` (se genera con composer)
❌ `/node_modules`
❌ `/.env` (créalo en el servidor)
❌ `/storage/logs/*.log`
❌ `/.git`
❌ `/tests`

---

## ✅ IMPORTANTE - Archivos a SÍ Subir

✅ `/public/build/` (assets compilados)
✅ `/.htaccess` (raíz)
✅ `/public/.htaccess` (renombrado desde .htaccess.production)
✅ `/storage/.htaccess`
✅ `/bootstrap/cache/.htaccess`
✅ Todo el resto del proyecto

---

## 💡 Tips Finales

1. **Siempre haz backup** de la BD antes de desplegar
2. **Usa el checklist** para no olvidar pasos
3. **Revisa los logs** si algo falla: `storage/logs/laravel.log`
4. **Modo debug OFF** en producción: `APP_DEBUG=false`
5. **HTTPS siempre activo** con el SSL de Hostinger

---

## 📞 Soporte

- **Problemas técnicos:** Ver [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
- **Proceso de deployment:** Ver [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- **Soporte Hostinger:** Live Chat 24/7 en hPanel

---

**¡Todo listo para desplegar! 🚀**
