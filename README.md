# 💧 Sistema de Reparto de Agua

Sistema web completo para gestión de reparto de agua embotellada, desarrollado con Laravel 12 y Tailwind CSS.

## 📋 Características

### Módulos Principales
- **👥 Gestión de Clientes**: Alta, baja, modificación y consulta de clientes con cuenta corriente
- **👤 Gestión de Usuarios**: Usuarios con roles (Administrador, Gerente, Administrativo, Chofer, Repartidor)
- **🚚 Gestión de Vehículos**: Control de flota con estados (disponible, en uso, mantenimiento, fuera de servicio)
- **💰 Gestión de Pagos**: Registro de pagos con múltiples métodos (efectivo, transferencia, cuenta corriente)
- **📦 Gestión de Productos**: Catálogo de productos (bidones, dispensers, accesorios)
- **🚗 Gestión de Repartos**: Planificación y seguimiento de entregas
- **📊 Movimientos de Cuenta**: Sistema automático de contabilidad para clientes

### Funcionalidades Destacadas
- ✅ Sistema de autenticación y autorización basado en roles
- ✅ Contabilidad automática con Observers (Laravel)
- ✅ Interfaz responsive con Tailwind CSS
- ✅ Componentes reutilizables Blade
- ✅ Filtros y búsquedas avanzadas
- ✅ Estadísticas en tiempo real
- ✅ Auditoría de cambios (creado/actualizado por)

## 🚀 Deployment en Hostinger (Subcarpeta)

Este proyecto está listo para desplegarse en Hostinger en una subcarpeta.

### Guías de Deployment

📖 **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Guía completa paso a paso

✅ **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)** - Checklist interactivo

### Scripts de Deployment

```powershell
# Ejecutar ANTES de subir archivos (en tu PC)
.\deploy-prepare.ps1
```

Revisa `deploy-server-commands.ps1` para los comandos a ejecutar en el servidor.

### Configuración Rápida

1. **Preparar localmente:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   ```

2. **Subir archivos a:** `public_html/sistemaagua/`

3. **En el servidor:**
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan config:cache
   ```

4. **Acceder a:** `https://pyfsasoftware.com.ar/sistemaagua`

## 💻 Instalación Local

### Requisitos
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8.0+
- XAMPP (opcional)

### Pasos de Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/M47u/repartoagua.git
   cd repartoagua
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   npm install
   ```

3. **Configurar entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar base de datos** en `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=repartoagua
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

6. **Seeders (opcional - datos de prueba):**
   ```bash
   php artisan db:seed
   ```

7. **Compilar assets:**
   ```bash
   npm run dev
   ```

8. **Iniciar servidor:**
   ```bash
   php artisan serve
   ```

9. **Acceder a:** `http://127.0.0.1:8000`

## 📚 Estructura del Proyecto

```
├── app/
│   ├── Http/Controllers/     # Controladores CRUD
│   ├── Models/               # Modelos Eloquent
│   ├── Observers/            # Observers para eventos
│   ├── Policies/             # Políticas de autorización
│   └── Providers/            # Service Providers
├── database/
│   ├── migrations/           # Migraciones de BD
│   └── seeders/              # Seeders de datos
├── resources/
│   ├── views/                # Vistas Blade
│   │   ├── clientes/
│   │   ├── usuarios/
│   │   ├── vehiculos/
│   │   ├── pagos/
│   │   ├── productos/
│   │   └── repartos/
│   ├── css/                  # Estilos CSS
│   └── js/                   # JavaScript
└── routes/
    └── web.php               # Rutas web
```

## 🔐 Roles de Usuario

| Rol | Permisos |
|-----|----------|
| **Administrador** | Acceso total al sistema |
| **Gerente** | Gestión completa excepto configuración crítica |
| **Administrativo** | Gestión de clientes, pagos y productos |
| **Chofer** | Acceso a vehículos asignados y repartos |
| **Repartidor** | Acceso solo a sus propios repartos |

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Blade Templates + Alpine.js + Tailwind CSS
- **Base de Datos:** MySQL 8.0
- **Autenticación:** Laravel Breeze
- **Build Tools:** Vite

## 📄 Documentación Adicional

- **[DISEÑO_UI_UX.md](DISEÑO_UI_UX.md)** - Guía de diseño y componentes
- **[SNIPPETS.md](SNIPPETS.md)** - Snippets de código útiles

## 🤝 Contribución

Este es un proyecto privado. Para colaborar, contacta al administrador del repositorio.

## 📞 Soporte

Para problemas de deployment o bugs, consulta:
1. Los logs en `storage/logs/laravel.log`
2. La guía [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
3. El checklist [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# repartoagua" 
