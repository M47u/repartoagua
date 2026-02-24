# ========================================
# Script de preparación para deployment
# ========================================
# Ejecuta este script ANTES de subir archivos a Hostinger

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Preparando app para producción" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Paso 1: Limpiar cachés
Write-Host "[1/8] Limpiando cachés..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
Write-Host "✓ Cachés limpiados" -ForegroundColor Green
Write-Host ""

# Paso 2: Instalar dependencias de producción
Write-Host "[2/8] Instalando dependencias de producción..." -ForegroundColor Yellow
composer install --optimize-autoloader --no-dev
Write-Host "✓ Dependencias instaladas" -ForegroundColor Green
Write-Host ""

# Paso 3: Compilar assets con Vite
Write-Host "[3/8] Compilando assets (CSS/JS)..." -ForegroundColor Yellow
npm install
npm run build
Write-Host "✓ Assets compilados" -ForegroundColor Green
Write-Host ""

# Paso 4: Generar cachés de optimización
Write-Host "[4/8] Generando cachés de optimización..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "✓ Cachés generados" -ForegroundColor Green
Write-Host ""

# Paso 5: Preparar .htaccess para producción
Write-Host "[5/8] Configurando .htaccess para subcarpeta..." -ForegroundColor Yellow
Copy-Item "public\.htaccess.production" -Destination "public\.htaccess" -Force
Write-Host "✓ .htaccess configurado" -ForegroundColor Green
Write-Host ""

# Paso 6: Verificar permisos (simulación)
Write-Host "[6/8] Verificando estructura de carpetas..." -ForegroundColor Yellow
$folders = @("storage", "storage/framework", "storage/logs", "bootstrap/cache")
foreach ($folder in $folders) {
    if (Test-Path $folder) {
        Write-Host "  ✓ $folder existe" -ForegroundColor Gray
    } else {
        Write-Host "  ✗ $folder no existe" -ForegroundColor Red
    }
}
Write-Host "✓ Verificación completa" -ForegroundColor Green
Write-Host ""

# Paso 7: Crear lista de archivos a NO subir
Write-Host "[7/8] Generando lista de exclusión..." -ForegroundColor Yellow
$excludeList = @"
====================================
 NO SUBIR ESTOS ARCHIVOS/CARPETAS:
====================================

✗ /vendor (se generará con composer en el servidor)
✗ /node_modules
✗ /.env (créalo directamente en el servidor)
✗ /storage/logs/*.log
✗ /.git
✗ /.vscode
✗ /tests
✗ /.env.example
✗ /phpunit.xml

====================================
 SÍ SUBIR ESTOS:
====================================

✓ Todo el resto del proyecto
✓ /public/build (assets compilados de Vite)
✓ /.htaccess (raíz)
✓ /public/.htaccess (configurado para subcarpeta)
✓ /storage/.htaccess
✓ /bootstrap/cache/.htaccess

"@
Write-Host $excludeList -ForegroundColor Cyan
Write-Host ""

# Paso 8: Instrucciones finales
Write-Host "[8/8] Preparación completa" -ForegroundColor Yellow
Write-Host ""
Write-Host "=====================================" -ForegroundColor Green
Write-Host "  ✓ LISTO PARA SUBIR A HOSTINGER" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""
Write-Host "PRÓXIMOS PASOS:" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Sube todos los archivos a: public_html/sistemaagua/" -ForegroundColor White
Write-Host "2. Crea el archivo .env en el servidor (usa .env.production.example)" -ForegroundColor White
Write-Host "3. Ejecuta en el servidor:" -ForegroundColor White
Write-Host "   php artisan key:generate" -ForegroundColor Gray
Write-Host "   composer install --optimize-autoloader --no-dev" -ForegroundColor Gray
Write-Host "   php artisan migrate --force" -ForegroundColor Gray
Write-Host "   php artisan config:cache" -ForegroundColor Gray
Write-Host ""
Write-Host "4. Configura permisos (755) en:" -ForegroundColor White
Write-Host "   - storage/" -ForegroundColor Gray
Write-Host "   - bootstrap/cache/" -ForegroundColor Gray
Write-Host ""
Write-Host "Consulta DEPLOYMENT_GUIDE.md para más detalles" -ForegroundColor Yellow
Write-Host ""
