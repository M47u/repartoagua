# ========================================
# Script POST-DEPLOYMENT
# ========================================
# Ejecuta estos comandos EN EL SERVIDOR después de subir archivos

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Post-Deployment en Servidor" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "IMPORTANTE: Este script es para REFERENCIA." -ForegroundColor Yellow
Write-Host "Ejecuta estos comandos manualmente vía SSH o Terminal de Hostinger" -ForegroundColor Yellow
Write-Host ""

$commands = @"
# 1. Navegar a la carpeta del proyecto
cd public_html/sistemaagua

# 2. Generar APP_KEY (si no lo hiciste)
php artisan key:generate

# 3. Instalar dependencias de Composer
composer install --optimize-autoloader --no-dev

# 4. Configurar permisos
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 5. Ejecutar migraciones
php artisan migrate --force

# 6. Ejecutar seeders (OPCIONAL - solo si es primera vez)
# php artisan db:seed --force

# 7. Generar cachés de optimización
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Verificar configuración
php artisan config:show app
php artisan route:list

# 9. Limpiar logs antiguos (opcional)
# rm -f storage/logs/*.log

# 10. Verificar que todo funcione
php artisan about
"@

Write-Host $commands -ForegroundColor White
Write-Host ""
Write-Host "=====================================" -ForegroundColor Green
Write-Host "Copia y ejecuta estos comandos en el servidor" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""
