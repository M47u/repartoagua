-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2026 a las 14:23:23
-- Versión del servidor: 8.0.42
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `repartoagua`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chofer_vehiculo`
--

CREATE TABLE `chofer_vehiculo` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `vehiculo_id` bigint UNSIGNED NOT NULL,
  `fecha_asignacion` date DEFAULT NULL,
  `fecha_desasignacion` date DEFAULT NULL,
  `asignacion_activa` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `chofer_vehiculo`
--

INSERT INTO `chofer_vehiculo` (`id`, `user_id`, `vehiculo_id`, `fecha_asignacion`, `fecha_desasignacion`, `asignacion_activa`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2025-11-24', NULL, 1, NULL, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(2, 5, 2, '2025-11-24', NULL, 1, NULL, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(3, 6, 3, '2025-11-24', NULL, 1, NULL, '2026-02-25 00:59:44', '2026-02-25 00:59:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colonia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cliente` enum('hogar','comercio','empresa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hogar',
  `precio_por_bidon` decimal(8,2) DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `direccion`, `colonia`, `ciudad`, `latitude`, `longitude`, `telefono`, `email`, `tipo_cliente`, `precio_por_bidon`, `observaciones`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Juan', 'García', 'Junín 384', 'Formosa', 'Formosa', -26.17611780, -58.17120690, '5512345678', NULL, 'hogar', 40.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 22:17:46'),
(2, 'María', 'López', 'Salta 1234', 'Formosa', 'Formosa', -26.19182100, -58.17617560, '5523456789', NULL, 'hogar', 38.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 22:18:12'),
(3, 'Tacos El Buen Sabor', 'Aveiro', 'Maipu 384', 'Formosa', 'Formosa', -26.17914190, -58.16997290, '5534567890', 'aveiromatias@gmail.com', 'comercio', 35.00, 'Solicitan entrega temprano', 1, '2026-02-25 00:59:44', '2026-02-25 23:15:50'),
(4, 'Restaurante La Hacienda', 'Aveiro', 'Corrientes 2254', 'Formosa', 'Formosa', -26.18408531, -58.19223046, '5545678901', 'aveiromatias1@gmail.com', 'comercio', 36.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 23:20:02'),
(5, 'Corporativo TechSolutions', 'SA', 'Salta 2565', 'Formosa', 'Formosa', -26.19660200, -58.19110100, '5556789012', 'contacto@techsolutions.com', 'empresa', 6000.00, 'Factura requerida', 1, '2026-02-25 00:59:44', '2026-02-27 04:41:36'),
(6, 'Rosa', 'Flores', 'Av. Universidad 987', 'Coyoacán', 'CDMX', NULL, NULL, '5567890123', NULL, 'hogar', 40.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(7, 'Gym FitLife', '', 'Calle Madero 147', 'Narvarte', 'CDMX', NULL, NULL, '5578901234', 'info@fitlife.com', 'comercio', 37.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(8, 'Oficinas Grupo Alfa', '', 'Av. Chapultepec 258', 'Juárez', 'CDMX', NULL, NULL, '5589012345', NULL, 'empresa', 33.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(9, 'Jorge', 'Moreno', 'Calle 5 de Mayo 369', 'Tlalpan', 'CDMX', NULL, NULL, '5590123456', NULL, 'hogar', 42.00, NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(10, 'Cafetería Aroma', '', 'Av. Revolución 741', 'San Ángel', 'CDMX', NULL, NULL, '5501234567', NULL, 'comercio', 38.00, 'Entrega por la tarde', 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(11, 'Juan carlos', 'Chupapija', 'Juan José silva 145', 'Formosa', 'Formosa', -26.17714550, -58.16781400, '370412345', NULL, 'comercio', 6500.00, NULL, 1, '2026-02-26 00:49:50', '2026-02-26 00:50:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_19_135845_create_clientes_table', 1),
(5, '2026_01_19_135857_create_productos_table', 1),
(6, '2026_01_19_135859_create_repartos_table', 1),
(7, '2026_01_19_165736_add_role_to_users_table', 1),
(8, '2026_01_19_165938_create_movimientos_cuenta_table', 1),
(9, '2026_01_19_165938_create_pagos_table', 1),
(10, '2026_01_24_202921_add_missing_columns_to_clientes_table', 1),
(11, '2026_02_23_000001_update_users_table_add_user_fields', 1),
(12, '2026_02_23_000002_create_vehiculos_table', 1),
(13, '2026_02_23_000003_create_chofer_vehiculo_table', 1),
(14, '2026_02_24_200926_make_precio_por_bidon_nullable_in_clientes_table', 1),
(15, '2026_02_24_205332_add_geolocation_to_clientes_table', 1),
(16, '2026_02_25_172133_add_fecha_programada_to_repartos_table', 2),
(17, '2026_02_25_172333_add_missing_columns_to_repartos_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_cuenta`
--

CREATE TABLE `movimientos_cuenta` (
  `id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `tipo` enum('debito','credito') COLLATE utf8mb4_unicode_ci NOT NULL,
  `origen` enum('reparto','pago') COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `referencia_tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia_id` bigint UNSIGNED NOT NULL,
  `saldo_anterior` decimal(10,2) NOT NULL,
  `saldo_nuevo` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos_cuenta`
--

INSERT INTO `movimientos_cuenta` (`id`, `cliente_id`, `tipo`, `origen`, `monto`, `fecha`, `referencia_tipo`, `referencia_id`, `saldo_anterior`, `saldo_nuevo`, `created_at`, `updated_at`) VALUES
(1, 1, 'debito', 'reparto', 80.00, '2026-02-25', 'App\\Models\\Reparto', 1, 0.00, 80.00, '2026-02-25 23:50:56', '2026-02-25 23:50:56'),
(2, 2, 'debito', 'reparto', 152.00, '2026-02-25', 'App\\Models\\Reparto', 2, 0.00, 152.00, '2026-02-25 23:51:20', '2026-02-25 23:51:20'),
(3, 4, 'debito', 'reparto', 180.00, '2026-02-25', 'App\\Models\\Reparto', 3, 0.00, 180.00, '2026-02-25 23:51:38', '2026-02-25 23:51:38'),
(4, 11, 'debito', 'reparto', 13000.00, '2026-02-25', 'App\\Models\\Reparto', 4, 0.00, 13000.00, '2026-02-26 00:51:01', '2026-02-26 00:51:01'),
(5, 11, 'credito', 'pago', 13000.00, '2026-02-26', 'App\\Models\\Pago', 1, 13000.00, 0.00, '2026-02-26 16:38:36', '2026-02-26 16:38:36'),
(6, 4, 'credito', 'pago', 180.00, '2026-02-26', 'App\\Models\\Pago', 2, 180.00, 0.00, '2026-02-26 16:43:59', '2026-02-26 16:43:59'),
(7, 5, 'debito', 'reparto', 24000.00, '2026-02-27', 'App\\Models\\Reparto', 5, 0.00, 24000.00, '2026-02-27 04:42:10', '2026-02-27 04:42:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','transferencia','cuenta_corriente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `registrado_por` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `fecha`, `fecha_pago`, `cliente_id`, `monto`, `metodo_pago`, `referencia`, `notas`, `observaciones`, `registrado_por`, `created_at`, `updated_at`) VALUES
(1, '2026-02-26', '2026-02-26 16:38:36', 11, 13000.00, 'efectivo', 'Reparto #4', NULL, NULL, 7, '2026-02-26 16:38:36', '2026-02-26 16:38:36'),
(2, '2026-02-26', '2026-02-26 16:43:59', 4, 180.00, 'efectivo', 'Reparto #3', NULL, NULL, 7, '2026-02-26 16:43:59', '2026-02-26 16:43:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio_base` decimal(8,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio_base`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Bidón 20L', 'Bidón de agua purificada de 20 litros', 6500.00, 1, '2026-02-25 00:59:44', '2026-02-26 17:22:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repartos`
--

CREATE TABLE `repartos` (
  `id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `fecha_programada` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `repartidor_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(8,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','entregado','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `entregado_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `repartos`
--

INSERT INTO `repartos` (`id`, `fecha`, `fecha_programada`, `fecha_entrega`, `cliente_id`, `repartidor_id`, `producto_id`, `cantidad`, `precio_unitario`, `total`, `estado`, `notas`, `observaciones`, `entregado_at`, `created_at`, `updated_at`) VALUES
(1, '2026-02-25', '2026-02-26', NULL, 1, 7, 1, 2, 40.00, 80.00, 'pendiente', NULL, NULL, NULL, '2026-02-25 23:50:56', '2026-02-26 23:09:36'),
(2, '2026-02-25', '2026-02-26', NULL, 2, 7, 1, 4, 38.00, 152.00, 'pendiente', NULL, NULL, NULL, '2026-02-25 23:51:20', '2026-02-26 23:09:28'),
(3, '2026-02-25', '2026-02-26', '2026-02-26', 4, 7, 1, 5, 36.00, 180.00, 'pendiente', NULL, NULL, '2026-02-26 16:28:39', '2026-02-25 23:51:38', '2026-02-27 04:39:02'),
(4, '2026-02-25', '2026-02-26', '2026-02-26', 11, 7, 1, 2, 6500.00, 13000.00, 'pendiente', NULL, NULL, '2026-02-26 16:06:55', '2026-02-26 00:51:00', '2026-02-27 04:37:33'),
(5, '2026-02-27', '2026-02-27', NULL, 5, 7, 1, 4, 6000.00, 24000.00, 'pendiente', NULL, NULL, NULL, '2026-02-27 04:42:10', '2026-02-27 04:42:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3m3G0h6qmoH4B4laCd5bpvXrvMhAIAPAEAT78eiQ', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEVIR1VYUWtKT1dDQUZXeVkybUloRmxiQXZxOHR6YkpDMkc1Wm5zeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1772156755),
('jJqD1J4pUyZTrZ4ZmNDf96Ayuwsvh4CjJIlylbhz', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUNyblFjTzlsYWtoWVV5QndkVWNwUFJ1QlNBaXh5dUdza0g3SmV4MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772198578);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('administrador','administrativo','repartidor','chofer','gerente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'repartidor',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `apellido`, `email`, `telefono`, `dni`, `direccion`, `ciudad`, `fecha_ingreso`, `fecha_nacimiento`, `observaciones`, `activo`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Sistema', 'admin@repartoagua.com', '555-0001', '12345678', 'Calle Principal 123', 'Ciudad Principal', '2023-02-24', NULL, NULL, 1, 'administrador', NULL, '$2y$12$LeVuYHqwh6nXm0b0V1dRHeqj7VOGj6NEL4nADvdA4C4ZtsjP/vx22', 'Hyd045E93DgUF9VmPUwkBvNA9UwY2SBc8UJGKl4ouN0AFsVTkvuvoColr2BF', '2026-02-25 00:59:42', '2026-02-25 00:59:42'),
(2, 'Juan', 'Pérez', 'gerente@repartoagua.com', '555-0002', '23456789', 'Avenida Central 456', 'Ciudad Principal', '2024-02-24', '1991-02-24', NULL, 1, 'gerente', NULL, '$2y$12$npWFXPR1sKMaRTdGtgeYv.rffSNy39UVAJUYkpD.Ymt0OWYXDy5sy', NULL, '2026-02-25 00:59:42', '2026-02-25 00:59:42'),
(3, 'María', 'González', 'admin1@repartoagua.com', '555-0003', '34567890', 'Calle Comercio 789', 'Ciudad Principal', '2025-02-24', '1998-02-24', NULL, 1, 'administrativo', NULL, '$2y$12$aL9ZGNfjkXH1WchvHX7T4eqU47PN4P/dK2zcNUqELst7xOCNOJv4y', NULL, '2026-02-25 00:59:42', '2026-02-25 00:59:42'),
(4, 'Carlos', 'Rodríguez', 'chofer1@repartoagua.com', '555-1001', '45678901', 'Zona Repartos', 'Ciudad Principal', '2024-11-24', '1983-02-24', NULL, 1, 'chofer', NULL, '$2y$12$vplNnsqftSAnGpL7YmsH1efPmj2q5kFMcMHgAoLa2JdcAi9TzWdTe', NULL, '2026-02-25 00:59:43', '2026-02-25 00:59:43'),
(5, 'Luis', 'Martínez', 'chofer2@repartoagua.com', '555-1002', '56789012', 'Zona Repartos', 'Ciudad Principal', '2024-08-24', '1995-02-24', NULL, 1, 'chofer', NULL, '$2y$12$gfoWgVo5zQGf4s9f22mbAebljv4CUqCcO3.yJoWOYDXn2C075jIvK', NULL, '2026-02-25 00:59:43', '2026-02-25 00:59:43'),
(6, 'Roberto', 'Sánchez', 'chofer3@repartoagua.com', '555-1003', '67890123', 'Zona Repartos', 'Ciudad Principal', '2024-08-24', '1985-02-24', NULL, 1, 'chofer', NULL, '$2y$12$QjPvg/ZWMsavsRCgzPwNTusYiKwWrj9rvLBCm91MxLaM2Ew.nmVlG', NULL, '2026-02-25 00:59:43', '2026-02-25 00:59:43'),
(7, 'Pedro', 'López', 'repartidor1@repartoagua.com', '555-2001', '78901234', 'Zona Repartos', 'Ciudad Principal', '2025-06-24', '1986-02-24', NULL, 1, 'repartidor', NULL, '$2y$12$zrwelK.lalqWVopu2w0/0.JsedFHdwt4Gd4CPx9kVuwi6SztKWOUm', NULL, '2026-02-25 00:59:43', '2026-02-25 00:59:43'),
(8, 'José', 'García', 'repartidor2@repartoagua.com', '555-2002', '89012345', 'Zona Repartos', 'Ciudad Principal', '2025-05-24', '1989-02-24', NULL, 1, 'repartidor', NULL, '$2y$12$D8.Jwd4cMrME1rbbuQs8WO7qecGxjUmfrmKNWpX4qezQWu9AIh5ce', NULL, '2026-02-25 00:59:43', '2026-02-25 00:59:43'),
(9, 'Miguel', 'Fernández', 'repartidor3@repartoagua.com', '555-2003', '90123456', 'Zona Repartos', 'Ciudad Principal', '2025-08-24', '1994-02-24', NULL, 1, 'repartidor', NULL, '$2y$12$wMoNdHom4Y3itqTN.0MprOaR3M48dNGnkDzlGncIqckBSn.HorWMm', NULL, '2026-02-25 00:59:44', '2026-02-25 00:59:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` bigint UNSIGNED NOT NULL,
  `placa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `año` year NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('camion','camioneta','auto','moto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'camioneta',
  `capacidad_carga` int DEFAULT NULL COMMENT 'Capacidad en kg',
  `capacidad_bidones` int DEFAULT NULL COMMENT 'Cantidad de bidones',
  `numero_motor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_chasis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `fecha_ultimo_mantenimiento` date DEFAULT NULL,
  `fecha_proximo_mantenimiento` date DEFAULT NULL,
  `kilometraje` decimal(10,2) DEFAULT NULL,
  `estado` enum('disponible','en_uso','mantenimiento','fuera_servicio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disponible',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `placa`, `marca`, `modelo`, `año`, `color`, `tipo`, `capacidad_carga`, `capacidad_bidones`, `numero_motor`, `numero_chasis`, `fecha_compra`, `fecha_ultimo_mantenimiento`, `fecha_proximo_mantenimiento`, `kilometraje`, `estado`, `observaciones`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'ABC-123', 'Toyota', 'Hilux', '2022', 'Blanco', 'camioneta', 1000, 50, 'MOT-2C9415725B', 'CHA-B4BED3DE9E', '2022-02-24', '2025-11-24', '2026-05-24', 57663.00, 'en_uso', NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(2, 'DEF-456', 'Nissan', 'Frontier', '2021', 'Azul', 'camioneta', 900, 45, 'MOT-E5BCE86EC7', 'CHA-CD44C8B3A4', '2025-02-24', '2026-01-24', '2026-03-24', 35672.00, 'en_uso', NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(3, 'GHI-789', 'Mitsubishi', 'L200', '2023', 'Negro', 'camioneta', 1100, 55, 'MOT-2762325B47', 'CHA-E69BC1F3A0', '2025-02-24', '2026-01-24', '2026-03-24', 53483.00, 'en_uso', NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(4, 'JKL-012', 'Isuzu', 'NLR', '2020', 'Blanco', 'camion', 3000, 150, 'MOT-134EFDAC06', 'CHA-C71615AA65', '2022-02-24', '2025-12-24', '2026-05-24', 57272.00, 'disponible', NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44'),
(5, 'MNO-345', 'Ford', 'Ranger', '2019', 'Gris', 'camioneta', 850, 40, 'MOT-40D3EC5742', 'CHA-203A78EE76', '2024-02-24', '2025-12-24', '2026-03-24', 56578.00, 'mantenimiento', NULL, 1, '2026-02-25 00:59:44', '2026-02-25 00:59:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `chofer_vehiculo`
--
ALTER TABLE `chofer_vehiculo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chofer_vehiculo_unique` (`user_id`,`vehiculo_id`,`asignacion_activa`),
  ADD KEY `chofer_vehiculo_vehiculo_id_foreign` (`vehiculo_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_email_unique` (`email`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_cuenta`
--
ALTER TABLE `movimientos_cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimientos_cuenta_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `pagos_registrado_por_foreign` (`registrado_por`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `repartos`
--
ALTER TABLE `repartos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repartos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `repartos_repartidor_id_foreign` (`repartidor_id`),
  ADD KEY `repartos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_dni_unique` (`dni`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehiculos_placa_unique` (`placa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chofer_vehiculo`
--
ALTER TABLE `chofer_vehiculo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `movimientos_cuenta`
--
ALTER TABLE `movimientos_cuenta`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `repartos`
--
ALTER TABLE `repartos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chofer_vehiculo`
--
ALTER TABLE `chofer_vehiculo`
  ADD CONSTRAINT `chofer_vehiculo_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `chofer_vehiculo_vehiculo_id_foreign` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `movimientos_cuenta`
--
ALTER TABLE `movimientos_cuenta`
  ADD CONSTRAINT `movimientos_cuenta_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pagos_registrado_por_foreign` FOREIGN KEY (`registrado_por`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `repartos`
--
ALTER TABLE `repartos`
  ADD CONSTRAINT `repartos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `repartos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `repartos_repartidor_id_foreign` FOREIGN KEY (`repartidor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
