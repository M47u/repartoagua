# Configuración de Mapbox

## ✅ Mapbox ya está implementado

La integración con Mapbox Directions API está completa. Solo necesitas obtener tu token de acceso.

## 📋 Pasos para obtener tu Access Token

### 1. Crear cuenta en Mapbox (GRATIS)

Ve a: **https://account.mapbox.com/auth/signup/**

- Regístrate con tu email
- Confirma tu cuenta
- Es completamente gratuito (100,000 requests/mes)

### 2. Obtener tu Access Token

Una vez dentro:

1. Ve al **Dashboard**
2. En la sección **Access tokens**, encontrarás tu **Default public token**
3. Copia el token (comienza con `pk.`)

### 3. Configurar el token en tu proyecto

Abre el archivo `.env` y reemplaza:

```env
MAPBOX_ACCESS_TOKEN=your_mapbox_token_here
``` 

Por tu token real:

```env
MAPBOX_ACCESS_TOKEN=pk.eyJ1Ijoixxxxxxxxxxxxxxxxxxxxxxx
```

### 4. Reiniciar el servidor

Si tienes el servidor de Laravel corriendo, reinícialo:

```bash
# Detener con Ctrl+C
# Volver a iniciar
php artisan serve
```

## 🎯 Características implementadas

### ✅ Tráfico en tiempo real
- Usa el perfil `driving-traffic` de Mapbox
- Las rutas consideran el tráfico actual
- Tiempo estimado de llegada preciso

### ✅ Evita calles sin pavimentar
- Configurado con `exclude=unpaved`
- Solo usa calles pavimentadas/asfaltadas
- Ideal para zonas urbanas y rurales

### ✅ Rutas optimizadas
- Calcula la mejor ruta entre todos los puntos
- Considera la ubicación actual como punto de partida
- Muestra distancia real y tiempo estimado

### ✅ Mapas de alta calidad
- Tiles de Mapbox (mejor que OpenStreetMap)
- Datos actualizados constantemente
- Visualización profesional

## 📊 Límites gratuitos

- **100,000 requests/mes** GRATIS
- Con 20 repartidores y ~4 cálculos/día = ~1,760 requests/mes
- Solo usarás el **1.8%** de tu límite gratuito
- Margen enorme para crecer

## 🔧 Verificación

Una vez configurado el token, abre la aplicación y:

1. Ve a la página de **Repartos**
2. Haz clic en **🎯 CALCULAR RUTA ÓPTIMA**
3. Acepta los permisos de ubicación
4. Deberías ver:
   - ✅ Ruta dibujada en rojo
   - ✅ Distancia real por calles
   - ✅ Tiempo estimado en minutos
   - ✅ Mensaje: "Ruta óptima calculada con Mapbox"

## ⚠️ Solución de problemas

### Error: "Failed to fetch"
- Verifica que el token sea correcto
- Asegúrate de que comience con `pk.`
- Reinicia el servidor Laravel

### El mapa no se ve
- Verifica tu conexión a internet
- Abre la consola del navegador (F12)
- Busca errores relacionados con Mapbox

### No calcula la ruta
- Verifica que los clientes tengan coordenadas (latitud/longitud)
- Asegúrate de que hay repartos pendientes
- Revisa la consola del navegador

## 📞 Soporte

Si tienes problemas:
1. Revisa la consola del navegador (F12)
2. Verifica el archivo `.env`
3. Confirma que el token está activo en Mapbox

## 🎉 ¡Listo!

Una vez configurado, tendrás:
- 🚗 Rutas con tráfico en tiempo real
- 🛣️ Evita calles de tierra/ripio
- 📍 Ubicación GPS como punto de partida
- ⏱️ Tiempo estimado preciso
- 📏 Distancia real por calles
