-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-07-2025 a las 02:01:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `talleres`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_users_table', 1),
(2, '2024_01_01_000002_create_workshops_table', 1),
(3, '2024_01_01_000003_create_workshop_enrollments_table', 1),
(4, '2024_01_01_000004_create_tasks_table', 1),
(5, '2025_06_29_031514_create_personal_access_tokens_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `due_date` datetime NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `completion_notes` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `workshop_id`, `assigned_to`, `assigned_by`, `due_date`, `status`, `priority`, `completion_notes`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'Crear página web personal', 'Desarrolla una página web personal utilizando HTML5 y CSS3. Debe incluir secciones de información personal, proyectos y contacto.', 1, 7, 1, '2025-07-09 03:16:14', 'completed', 'high', NULL, '2025-07-04 03:59:38', '2025-06-29 06:16:14', '2025-07-04 03:59:38'),
(2, 'Diseñar logo corporativo', 'Crear un logo profesional para una empresa ficticia. Incluir versiones en color y monocromático.', 2, 7, 1, '2025-07-14 03:16:14', 'completed', 'medium', NULL, '2025-07-04 03:59:46', '2025-06-29 06:16:14', '2025-07-04 03:59:46'),
(3, 'Portafolio fotográfico', 'Crear un portafolio con 20 fotografías editadas que demuestren diferentes técnicas aprendidas.', 3, 7, 1, '2025-07-19 03:16:14', 'completed', 'medium', NULL, '2025-07-04 03:59:51', '2025-06-29 06:16:14', '2025-07-04 03:59:51'),
(4, 'Programar robot seguidor de línea', 'Construir y programar un robot que pueda seguir una línea negra utilizando sensores infrarrojos.', 4, 7, 1, '2025-08-03 03:16:14', 'pending', 'high', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(5, 'Tutorial JavaScript básico', 'Completar el tutorial interactivo de JavaScript y entregar los ejercicios resueltos.', 1, 7, 1, '2025-07-04 03:16:14', 'completed', 'low', 'Excelente trabajo en los ejercicios de loops y funciones.', '2025-06-28 06:16:14', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(6, 'Ejercicios de práctica', 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.', 1, 2, 1, '2025-07-06 03:16:14', 'in_progress', 'medium', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(7, 'Ejercicios de práctica', 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.', 1, 3, 1, '2025-07-24 03:16:14', 'completed', 'high', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(8, 'Ejercicios de práctica', 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.', 1, 4, 1, '2025-07-11 03:16:14', 'pending', 'medium', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(9, 'Ejercicios de práctica', 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.', 1, 5, 1, '2025-07-22 03:16:14', 'pending', 'low', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(10, 'Ejercicios de práctica', 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.', 1, 6, 1, '2025-07-07 03:16:14', 'completed', 'high', NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `student_id` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `student_id`, `phone`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@test.com', NULL, '$2y$12$gcr.jB.fq6rTqcsFwUJmS.hDatGXbbuxnN8NCSFo2wwaFUUku739O', 'admin', NULL, NULL, NULL, NULL, '2025-06-29 06:16:12', '2025-06-29 06:16:12'),
(2, 'Estudiante 1', 'student1@test.com', NULL, '$2y$12$B3degYY3JQqNwqoVUcy0SulgCgIE.tmnMd2dl3Nv3xu7kSFoZKgRa', 'student', 'EST001', NULL, NULL, NULL, '2025-06-29 06:16:13', '2025-06-29 06:16:13'),
(3, 'Estudiante 2', 'student2@test.com', NULL, '$2y$12$lthflKutw8iRqh6kXYyhm..CQJBtpdiPovdVdl0Aqj5GmsGntGQEe', 'student', 'EST002', NULL, NULL, NULL, '2025-06-29 06:16:13', '2025-06-29 06:16:13'),
(4, 'Estudiante 3', 'student3@test.com', NULL, '$2y$12$u1D82/WA/GZ5Ge3FECc4yeIBO82Oxm0UUjlhjq78cah9LPZ2I8RLq', 'student', 'EST003', NULL, NULL, NULL, '2025-06-29 06:16:13', '2025-06-29 06:16:13'),
(5, 'Estudiante 4', 'student4@test.com', NULL, '$2y$12$6ZURlRGPVQWWyWw67ZjAM.m26jdJFKPVIFDrTst8uRYcqI9MLt4AC', 'student', 'EST004', NULL, NULL, NULL, '2025-06-29 06:16:13', '2025-06-29 06:16:13'),
(6, 'Estudiante 5', 'student5@test.com', NULL, '$2y$12$DxNRWufUuS1YeXaL/Q9bneBleCScxtFSdzLz1R2H5p906ldsk63Wa', 'student', 'EST005', NULL, NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(7, 'Juan Pérez', 'student@test.com', NULL, '$2y$12$WZx1RE3q6Htve/898HtZzeWowBVBOIgheHKjEaItlRpEIDg1p9cg2', 'student', 'EST001', NULL, NULL, NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(8, 'david', 'davidalejandrolerea@gmail.com', NULL, '$2y$12$20QNWSlwVWWvctLiC.p/6OGPxb6vtaytg6X5halb7fuq0HuedcD4O', 'student', NULL, NULL, NULL, NULL, '2025-06-29 19:07:35', '2025-06-29 19:07:35'),
(9, 'David Lerea', 'davidalejandrolerea@outlook.com', NULL, '$2y$12$7Udv.dyfR5xW12ewldN58eTPxJE9bCaH5gsNpm/8Xw06w1NWcRM7a', 'admin', NULL, NULL, NULL, NULL, '2025-06-29 19:35:52', '2025-06-29 19:35:52'),
(10, 'David Alejandro', 'prueba@gmail.com', NULL, '$2y$12$ALORGOY2mlCUwTyHNGTz0OcNo7vSwBCqApxtboegj8nl3dWVv3A6i', 'admin', NULL, NULL, NULL, NULL, '2025-07-04 03:46:47', '2025-07-04 03:46:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `workshops`
--

CREATE TABLE `workshops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `instructor` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 20,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule`)),
  `location` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `workshops`
--

INSERT INTO `workshops` (`id`, `name`, `description`, `instructor`, `capacity`, `start_date`, `end_date`, `schedule`, `location`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Taller de Programación Web', 'Aprende a desarrollar aplicaciones web modernas con HTML, CSS, JavaScript y frameworks actuales.', 'Prof. Ana García', 25, '2025-07-06', '2025-09-04', NULL, 'Laboratorio de Cómputo A', 'active', NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(2, 'Taller de Diseño Gráfico', 'Desarrolla habilidades en diseño visual utilizando herramientas profesionales como Photoshop e Illustrator.', 'Prof. Carlos Mendoza', 20, '2025-07-13', '2025-09-11', NULL, 'Aula de Diseño', 'active', NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(3, 'Taller de Fotografía Digital', 'Domina las técnicas de fotografía digital y edición de imágenes para crear contenido visual impactante.', 'Prof. María López', 15, '2025-07-20', '2025-09-18', NULL, 'Estudio Fotográfico', 'active', NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(4, 'Taller de Robótica', 'Construye y programa robots utilizando Arduino y sensores para automatización.', 'Prof. Roberto Silva', 12, '2025-07-29', '2025-09-27', NULL, 'Laboratorio de Robótica', 'active', NULL, '2025-06-29 06:16:14', '2025-06-29 06:16:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `workshop_enrollments`
--

CREATE TABLE `workshop_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','dropped','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `workshop_enrollments`
--

INSERT INTO `workshop_enrollments` (`id`, `user_id`, `workshop_id`, `enrollment_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, 1, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(2, 2, 1, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(3, 3, 1, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(4, 4, 1, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(5, 6, 1, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(6, 7, 2, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(7, 5, 2, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(8, 6, 2, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(9, 7, 3, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(10, 2, 3, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(11, 4, 3, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(12, 6, 3, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(13, 7, 4, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(14, 4, 4, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14'),
(15, 5, 4, '2025-06-29 06:16:14', 'active', '2025-06-29 06:16:14', '2025-06-29 06:16:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_workshop_id_foreign` (`workshop_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_assigned_by_foreign` (`assigned_by`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `workshop_enrollments`
--
ALTER TABLE `workshop_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workshop_enrollments_user_id_workshop_id_unique` (`user_id`,`workshop_id`),
  ADD KEY `workshop_enrollments_workshop_id_foreign` (`workshop_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `workshop_enrollments`
--
ALTER TABLE `workshop_enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_workshop_id_foreign` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `workshop_enrollments`
--
ALTER TABLE `workshop_enrollments`
  ADD CONSTRAINT `workshop_enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `workshop_enrollments_workshop_id_foreign` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
