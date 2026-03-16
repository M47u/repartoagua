-- phpMyAdmin SQL Dump
-- Estructura limpia de la base de datos RepartoAgua
-- Generado: 2026-03-11
-- Versión del servidor: 8.0.42
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aguasdellitoral`
--
USE `u251673992_aguasdellitora`;
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
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- (Se incluyen los registros para que Laravel no intente re-ejecutar las migraciones)
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Datos para la tabla `migrations`
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
-- Datos para la tabla `users`
-- Contraseña de todos los usuarios: password123
-- (hashes bcrypt generados con cost=12)
--
-- | Email                          | Rol            | Contraseña  |
-- |--------------------------------|----------------|-------------|
-- | admin@repartoagua.com          | administrador  | password123 |
-- | gerente@repartoagua.com        | gerente        | password123 |
-- | admin1@repartoagua.com         | administrativo | password123 |
-- | chofer1@repartoagua.com        | chofer         | password123 |
-- | chofer2@repartoagua.com        | chofer         | password123 |
-- | chofer3@repartoagua.com        | chofer         | password123 |
-- | repartidor1@repartoagua.com    | repartidor     | password123 |
-- | repartidor2@repartoagua.com    | repartidor     | password123 |
-- | repartidor3@repartoagua.com    | repartidor     | password123 |
--

INSERT INTO `users` (`id`, `name`, `apellido`, `email`, `telefono`, `dni`, `direccion`, `ciudad`, `fecha_ingreso`, `fecha_nacimiento`, `observaciones`, `activo`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Sistema', 'admin@repartoagua.com', '555-0001', '12345678', 'Calle Principal 123', 'Ciudad Principal', '2023-02-24', NULL, NULL, 1, 'administrador', NULL, '$2y$12$LeVuYHqwh6nXm0b0V1dRHeqj7VOGj6NEL4nADvdA4C4ZtsjP/vx22', NULL, '2026-02-25 00:59:42', '2026-02-25 00:59:42'),
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
-- Índices para tablas
--

ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `chofer_vehiculo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chofer_vehiculo_unique` (`user_id`,`vehiculo_id`,`asignacion_activa`),
  ADD KEY `chofer_vehiculo_vehiculo_id_foreign` (`vehiculo_id`);

ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_email_unique` (`email`);

ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `movimientos_cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimientos_cuenta_cliente_id_foreign` (`cliente_id`);

ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `pagos_registrado_por_foreign` (`registrado_por`);

ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `repartos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repartos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `repartos_repartidor_id_foreign` (`repartidor_id`),
  ADD KEY `repartos_producto_id_foreign` (`producto_id`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_dni_unique` (`dni`);

ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehiculos_placa_unique` (`placa`);

--
-- AUTO_INCREMENT de las tablas
--

ALTER TABLE `chofer_vehiculo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `movimientos_cuenta`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `pagos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `repartos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `vehiculos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones (foreign keys)
--

ALTER TABLE `chofer_vehiculo`
  ADD CONSTRAINT `chofer_vehiculo_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `chofer_vehiculo_vehiculo_id_foreign` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`) ON DELETE RESTRICT;

ALTER TABLE `movimientos_cuenta`
  ADD CONSTRAINT `movimientos_cuenta_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT;

ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pagos_registrado_por_foreign` FOREIGN KEY (`registrado_por`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

ALTER TABLE `repartos`
  ADD CONSTRAINT `repartos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `repartos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `repartos_repartidor_id_foreign` FOREIGN KEY (`repartidor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
