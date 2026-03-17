-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 02:26:45
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
-- Base de datos: `sidequest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha_solicitud` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `equipado` tinyint(1) DEFAULT 0,
  `fecha_compra` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('skin','badge','marco','consumible') NOT NULL,
  `precio` int(11) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quests`
--

CREATE TABLE `quests` (
  `id` int(11) NOT NULL,
  `creador_id` int(11) NOT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `recompensa_gold` int(11) NOT NULL CHECK (`recompensa_gold` > 0),
  `recompensa_xp` int(11) DEFAULT 50,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `estado` enum('disponible','en_curso','completada','cancelada') DEFAULT 'disponible',
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `quests`
--

INSERT INTO `quests` (`id`, `creador_id`, `worker_id`, `titulo`, `descripcion`, `recompensa_gold`, `recompensa_xp`, `latitud`, `longitud`, `estado`, `fecha_creacion`) VALUES
(1, 2, NULL, 'Rescatar Gato en Árbol', 'Mi gato está subido en un árbol.', 500, 50, 40.41677500, -3.68339000, 'disponible', '2026-01-26 17:42:25'),
(2, 2, NULL, 'Entrega de Paquete Discreto', 'Lleva este maletín a la estación de tren.', 1200, 50, 40.40677500, -3.69379000, 'disponible', '2026-01-26 17:42:25'),
(3, 2, 1, 'Limpiar Jardín Encantado', 'Hay gnomos en mi jardín, échalos.', 300, 50, 40.42677500, -3.71379000, 'en_curso', '2026-01-26 17:42:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL,
  `destino_id` int(11) NOT NULL,
  `estrellas` int(11) DEFAULT NULL CHECK (`estrellas` between 1 and 5),
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo` enum('deposito','retiro','pago_mision','cobro_mision','compra_shop','bono_admin') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `concepto` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('worker','client','admin') DEFAULT 'worker',
  `gold` int(11) DEFAULT 1000,
  `xp` int(11) DEFAULT 0,
  `nivel` int(11) DEFAULT 1,
  `avatar_url` varchar(255) DEFAULT 'default.png',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `gold`, `xp`, `nivel`, `avatar_url`, `fecha_registro`) VALUES
(1, 'Lobo Suelto', 'mercenario@test.com', '1234', 'worker', 0, 150, 2, 'default.png', '2026-01-26 17:42:25'),
(2, 'Currante', 'cliente@test.com', '1234', 'client', 50000, 0, 1, 'default.png', '2026-01-26 17:42:25'),
(3, 'Admin', 'admin@test.com', '1234', 'admin', 1000, 0, 1, 'default.png', '2026-01-26 17:42:25'),
(5, 'Bocadillo', 'holaquetal@gmail.com', '$2y$10$uLuuG58tOgUlcKz2xh.knu/UCQ1Xx7YtaHJZsveW9gYxsEuOT5vcu', 'worker', 1000, 0, 1, 'default.png', '2026-02-03 02:07:50'),
(6, 'hola', 'hola@adios.com', '$2y$10$hsAecwgUO8I7WDBIZ3qjouTsWChb8jIEKxBQz0caCT0B5mho9TQIe', 'worker', 1000, 0, 1, 'default.png', '2026-02-03 02:09:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quest_id` (`quest_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indices de la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creador_id` (`creador_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quest_id` (`quest_id`);

--
-- Indices de la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quests`
--
ALTER TABLE `quests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quests`
--
ALTER TABLE `quests`
  ADD CONSTRAINT `quests_ibfk_1` FOREIGN KEY (`creador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quests_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
