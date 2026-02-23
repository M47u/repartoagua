# Módulos de Usuarios y Vehículos

## 📋 Descripción General

Sistema completo de gestión de usuarios y vehículos para RepartoAgua, incluyendo roles diferenciados, asignación de vehículos a choferes, y control de mantenimiento.

---

## 👥 Módulo de Usuarios

### Roles Disponibles

1. **Administrador** - Control total del sistema
2. **Gerente** - Gestión de operaciones y reportes
3. **Administrativo** - Gestión de clientes, pagos y repartos
4. **Chofer** - Manejo de vehículos asignados
5. **Repartidor** - Entrega de productos a clientes

### Características

- ✅ CRUD completo de usuarios
- ✅ Asignación de vehículos a choferes
- ✅ Gestión de perfiles con datos personales
- ✅ Control de estado activo/inactivo
- ✅ Historial de repartos y pagos por usuario
- ✅ Validación de roles y permisos

### Campos del Usuario

| Campo | Tipo | Descripción |
|-------|------|-------------|
| name | string | Nombre del usuario |
| apellido | string | Apellido del usuario |
| email | string | Correo electrónico (único) |
| password | string | Contraseña encriptada |
| role | enum | Rol del usuario |
| telefono | string | Número de contacto |
| dni | string | Documento de identidad (único) |
| direccion | string | Dirección completa |
| ciudad | string | Ciudad de residencia |
| fecha_ingreso | date | Fecha de ingreso a la empresa |
| fecha_nacimiento | date | Fecha de nacimiento |
| observaciones | text | Notas adicionales |
| activo | boolean | Estado del usuario |

### Permisos por Rol

| Acción | Admin | Gerente | Admin. | Chofer | Repartidor |
|--------|-------|---------|--------|--------|------------|
| Ver usuarios | ✅ | ✅ | ❌ | ❌ | ❌ |
| Crear usuarios | ✅ | ✅ | ❌ | ❌ | ❌ |
| Editar usuarios | ✅ | ✅ | ❌ | ❌ | ❌ |
| Eliminar usuarios | ✅ | ❌ | ❌ | ❌ | ❌ |
| Ver propio perfil | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 🚗 Módulo de Vehículos

### Características

- ✅ CRUD completo de vehículos
- ✅ Asignación múltiple de choferes por vehículo
- ✅ Control de estados (disponible, en uso, mantenimiento, fuera de servicio)
- ✅ Registro de mantenimientos
- ✅ Alertas de mantenimiento próximo
- ✅ Historial de asignaciones de choferes
- ✅ Control de capacidad y especificaciones técnicas

### Campos del Vehículo

| Campo | Tipo | Descripción |
|-------|------|-------------|
| placa | string | Placa del vehículo (única) |
| marca | string | Marca del vehículo |
| modelo | string | Modelo del vehículo |
| año | integer | Año de fabricación |
| color | string | Color del vehículo |
| tipo | enum | Tipo: camion, camioneta, auto, moto |
| capacidad_carga | integer | Capacidad en kg |
| capacidad_bidones | integer | Cantidad de bidones |
| numero_motor | string | Número de motor |
| numero_chasis | string | Número de chasis |
| fecha_compra | date | Fecha de adquisición |
| fecha_ultimo_mantenimiento | date | Último mantenimiento realizado |
| fecha_proximo_mantenimiento | date | Próximo mantenimiento programado |
| kilometraje | decimal | Kilometraje actual |
| estado | enum | Estado del vehículo |
| observaciones | text | Notas adicionales |
| activo | boolean | Estado activo/inactivo |

### Estados del Vehículo

- **Disponible**: Listo para ser asignado
- **En Uso**: Actualmente asignado a un chofer
- **Mantenimiento**: En proceso de reparación
- **Fuera de Servicio**: No operativo

### Permisos por Rol

| Acción | Admin | Gerente | Admin. | Chofer |
|--------|-------|---------|--------|--------|
| Ver todos los vehículos | ✅ | ✅ | ✅ | ❌ |
| Ver vehículos asignados | ✅ | ✅ | ✅ | ✅ |
| Crear vehículos | ✅ | ✅ | ❌ | ❌ |
| Editar vehículos | ✅ | ✅ | ✅ | ❌ |
| Eliminar vehículos | ✅ | ✅ | ❌ | ❌ |
| Registrar mantenimiento | ✅ | ✅ | ✅ | ❌ |

---

## 🔗 Relación Chofer-Vehículo

### Tabla Pivot: `chofer_vehiculo`

Esta tabla gestiona la relación muchos a muchos entre usuarios (choferes) y vehículos.

| Campo | Descripción |
|-------|-------------|
| user_id | ID del chofer |
| vehiculo_id | ID del vehículo |
| fecha_asignacion | Fecha de inicio de asignación |
| fecha_desasignacion | Fecha de fin de asignación |
| asignacion_activa | Estado actual de la asignación |
| observaciones | Notas sobre la asignación |

### Funcionalidades

- ✅ Un chofer puede tener múltiples vehículos asignados
- ✅ Un vehículo puede ser asignado a múltiples choferes
- ✅ Historial completo de asignaciones
- ✅ Solo una asignación activa por combinación chofer-vehículo
- ✅ Desasignación automática al cambiar estado

---

## 📊 Rutas y Endpoints

### Usuarios
```
GET    /usuarios              - Lista de usuarios
GET    /usuarios/create       - Formulario nuevo usuario
POST   /usuarios              - Crear usuario
GET    /usuarios/{id}         - Ver usuario
GET    /usuarios/{id}/edit    - Editar usuario
PUT    /usuarios/{id}         - Actualizar usuario
DELETE /usuarios/{id}         - Eliminar usuario
PATCH  /usuarios/{id}/toggle-estado - Cambiar estado
```

### Vehículos
```
GET    /vehiculos                     - Lista de vehículos
GET    /vehiculos/create              - Formulario nuevo vehículo
POST   /vehiculos                     - Crear vehículo
GET    /vehiculos/{id}                - Ver vehículo
GET    /vehiculos/{id}/edit           - Editar vehículo
PUT    /vehiculos/{id}                - Actualizar vehículo
DELETE /vehiculos/{id}                - Eliminar vehículo
PATCH  /vehiculos/{id}/toggle-estado  - Cambiar estado
POST   /vehiculos/{id}/mantenimiento  - Registrar mantenimiento
```

---

## 🗄️ Migraciones

Para implementar estos módulos, ejecuta:

```bash
# Migrar base de datos
php artisan migrate

# Poblar con datos de ejemplo
php artisan db:seed --class=UsuarioSeeder
php artisan db:seed --class=VehiculoSeeder

# O ejecutar todos los seeders
php artisan db:seed
```

---

## 📝 Datos de Prueba (Seeders)

### Usuarios creados:
- **Admin**: admin@repartoagua.com / password
- **Gerente**: gerente@repartoagua.com / password
- **Administrativo**: admin1@repartoagua.com / password
- **Choferes**: chofer1@repartoagua.com, chofer2@repartoagua.com, chofer3@repartoagua.com / password
- **Repartidores**: repartidor1@repartoagua.com, repartidor2@repartoagua.com, repartidor3@repartoagua.com / password

### Vehículos creados:
- 5 vehículos de ejemplo con diferentes tipos y estados
- 3 vehículos asignados automáticamente a choferes

---

## 🔧 Uso en el Código

### Verificar rol de usuario
```php
$user = auth()->user();

if ($user->isAdministrador()) {
    // Código para administrador
}

if ($user->isChofer()) {
    // Código para chofer
}

if ($user->isGerente()) {
    // Código para gerente
}
```

### Obtener vehículos de un chofer
```php
$chofer = User::find($id);
$vehiculosActivos = $chofer->vehiculosActivos;
$todosLosVehiculos = $chofer->vehiculos;
```

### Obtener choferes de un vehículo
```php
$vehiculo = Vehiculo::find($id);
$choferesActivos = $vehiculo->choferesActivos;
$todosLosChoferes = $vehiculo->choferes;
```

### Asignar vehículo a chofer
```php
$chofer->vehiculos()->attach($vehiculoId, [
    'fecha_asignacion' => now(),
    'asignacion_activa' => true,
]);
```

### Desasignar vehículo
```php
$chofer->vehiculos()->updateExistingPivot($vehiculoId, [
    'asignacion_activa' => false,
    'fecha_desasignacion' => now(),
]);
```

### Verificar si vehículo necesita mantenimiento
```php
if ($vehiculo->necesitaMantenimiento()) {
    // Alertar sobre mantenimiento próximo
}
```

---

## 🎯 Próximas Mejoras Sugeridas

1. **Sistema de notificaciones** para mantenimientos
2. **Reportes de uso** de vehículos por chofer
3. **Sistema de multas o incidentes** por vehículo
4. **Integración con GPS** para tracking en tiempo real
5. **App móvil** para choferes
6. **Sistema de check-in/check-out** de vehículos
7. **Registro de combustible** y gastos por vehículo
8. **Sistema de reservas** de vehículos

---

## ⚠️ Consideraciones Importantes

1. Al eliminar un usuario chofer, se desactivan automáticamente sus asignaciones de vehículos
2. Al eliminar un vehículo, se desactivan automáticamente las asignaciones de choferes
3. Al cambiar el rol de un usuario de chofer a otro rol, se desactivan sus vehículos
4. Los usuarios solo pueden tener UN rol a la vez
5. Las validaciones de permisos se manejan mediante Policies
6. Todas las fechas de desasignación se registran automáticamente

---

## 💡 Ejemplos de Uso

### Crear un nuevo chofer y asignarle vehículos
```php
$chofer = User::create([
    'name' => 'Carlos',
    'apellido' => 'Rodríguez',
    'email' => 'carlos@example.com',
    'password' => Hash::make('password'),
    'role' => 'chofer',
    // ... otros campos
]);

$chofer->vehiculos()->attach([1, 2], [
    'fecha_asignacion' => now(),
    'asignacion_activa' => true,
]);
```

### Filtrar usuarios por rol
```php
$choferes = User::role('chofer')->activos()->get();
$administradores = User::where('role', 'administrador')->get();
```

### Filtrar vehículos disponibles
```php
$disponibles = Vehiculo::disponibles()->get();
$enMantenimiento = Vehiculo::enMantenimiento()->get();
```
