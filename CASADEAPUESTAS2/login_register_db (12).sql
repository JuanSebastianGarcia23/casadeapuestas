-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2025 a las 16:01:48
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
-- Base de datos: `login_register_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apuestas`
--

CREATE TABLE `apuestas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `evento` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `marcador` varchar(10) DEFAULT NULL,
  `resultado_partido` varchar(50) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cuota` decimal(10,2) NOT NULL,
  `estado` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `apuestas`
--

INSERT INTO `apuestas` (`id`, `id_usuario`, `nombre_usuario`, `evento`, `monto`, `marcador`, `resultado_partido`, `fecha`, `hora`, `cuota`, `estado`) VALUES
(147, 7, 'juan pita', '⚽ Santa Fe vs Deportivo Cali', 40000.00, '2-2', '2-2', '2025-05-06', '14:10:12', 1.80, 'Ganó'),
(148, 7, 'juan pita', '⚽ Once Caldas vs Envigado', 100000.00, '5-5', '1-2', '2025-05-06', '14:10:28', 2.20, 'Perdió'),
(149, 7, 'juan pita', '⚽ Atlético de Madrid vs Milan', 50000.00, '5-5', '4-5', '2025-05-06', '14:10:48', 2.00, 'Perdió'),
(150, 7, 'juan pita', '⚽ Italia vs Uruguay', 40000.00, '2-5', '2-5', '2025-05-06', '14:11:08', 1.80, 'Ganó'),
(151, 7, 'juan pita', '⚽ Bélgica vs Croacia', 100000.00, '2-4', '5-5', '2025-05-06', '14:11:24', 2.20, 'Perdió'),
(152, 8, 'Juan Diego', '⚽ Alianza FC vs Pereira', 40000.00, '2-1', '0-0', '2025-05-06', '14:33:03', 1.80, 'Perdió'),
(153, 8, 'Juan Diego', '⚽ Bayern Múnich vs PSG', 30000.00, '5-2', '5-2', '2025-05-06', '14:33:20', 1.80, 'Ganó'),
(154, 8, 'Juan Diego', '⚽ Brasil vs Colombia', 40000.00, '2-3', '1-2', '2025-05-06', '14:33:41', 1.80, 'Perdió'),
(155, 11, 'jhonfer', '⚽ Santa Fe vs Deportivo Cali', 40000.00, '2-3', '2-3', '2025-05-06', '16:40:31', 1.80, 'Ganó'),
(156, 11, 'jhonfer', '⚽ Once Caldas vs Envigado', 100000.00, '2-3', '0-0', '2025-05-06', '16:40:48', 2.20, 'Perdió'),
(157, 11, 'jhonfer', '⚽ Atlético de Madrid vs Milan', 30000.00, '2-4', '2-4', '2025-05-06', '16:41:07', 1.80, 'Ganó'),
(158, 9, 'sebastian Garcia', '⚽ Once Caldas vs Envigado', 40000.00, '2-3', '2-3', '2025-05-07', '12:14:02', 1.80, 'Ganó'),
(159, 9, 'sebastian Garcia', '⚽ Barcelona vs Inter de Milán', 40000.00, '5-5', '1-2', '2025-05-07', '12:14:19', 1.80, 'Perdió'),
(160, 9, 'sebastian Garcia', '⚽ Japón vs Corea del Sur', 20000.00, '2-5', '2-5', '2025-05-07', '12:14:37', 1.80, 'Ganó'),
(161, 6, 'deivid rios', '⚽ Santa Fe vs Deportivo Cali', 20000.00, '2-1', '2-1', '2025-05-16', '19:40:15', 1.80, '—'),
(162, 6, 'deivid rios', '⚽ Santa Fe vs Deportivo Cali', 20000.00, '2-45', '2-45', '2025-05-16', '20:07:03', 1.80, '—');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `equipo_local` varchar(100) NOT NULL,
  `equipo_visitante` varchar(100) NOT NULL,
  `premio_1` text DEFAULT NULL,
  `premio_2` text DEFAULT NULL,
  `premio_3` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `id_torneo`, `equipo_local`, `equipo_visitante`, `premio_1`, `premio_2`, `premio_3`) VALUES
(3, 1, 'Santa Fe', 'Deportivo Cali', 'gana visitante', 'gana local', 'empate'),
(4, 1, 'Once Caldas', 'Envigado', 'gana visitante', 'gana local', 'empate'),
(5, 1, 'La Equidad', 'Jaguares', 'gana visitante', 'gana local ', 'empate'),
(6, 1, 'Tolima', 'Bucaramanga', 'gana visitante', 'gana local ', 'empate'),
(7, 1, 'Alianza FC', 'Pereira', 'gana visitante', 'gana local', 'empate'),
(8, 1, 'Patriotas', 'Huila', 'gana visitante', 'gana local', 'empate'),
(9, 2, 'Real Madrid', 'Manchester City', 'gana visitante', 'gana local', 'empate'),
(10, 2, 'Bayern Múnich', 'PSG', 'gana visitante', 'gana local', 'empate'),
(11, 2, 'Barcelona', 'Inter de Milán', 'gana visitante', 'gana local', 'empate'),
(12, 2, 'Arsenal', 'Napoli', 'gana visitante', 'gana local', 'empate'),
(13, 2, 'Atlético de Madrid', 'Milan', 'gana visitante', 'gana local', 'empate'),
(14, 2, 'Benfica', 'Chelsea', 'gana visitante', 'gana local', 'empate'),
(15, 2, 'Porto', 'Borussia Dortmund', 'gana visitante', 'gana local', 'empate'),
(16, 2, 'Manchester United', 'Ajax', 'gana visitante', 'gana local', 'empate'),
(17, 3, 'Argentina', 'Colombia', 'gana visitante', 'gana local', 'empate'),
(18, 3, 'Brasil', 'Colombia', 'gana visitante', 'gana local', 'empate'),
(19, 3, 'España', 'Inglaterra', 'gana visitante', 'gana local', 'empate'),
(20, 3, 'Portugal', 'Países Bajos', 'gana visitante', 'gana local', 'empate'),
(21, 3, 'Italia', 'Uruguay', 'gana visitante', 'gana local', 'empate'),
(22, 3, 'Bélgica', 'Croacia', 'gana visitante', 'gana local', 'empate'),
(23, 3, 'México', 'Estados Unidos', 'gana visitante', 'gana local', 'empate'),
(24, 3, 'Japón', 'Corea del Sur', 'gana visitante', 'gana local', 'empate'),
(34, 1, 'Tolima', 'Medellin', 'gana visitante', 'gana local', 'empate'),
(35, 2, 'Barcelona', 'Real Madrid', 'gana visitante', 'gana local ', 'empate'),
(39, 3, 'España', 'colombia', 'visitante', 'local', 'empate');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ganancias_diarias`
--

CREATE TABLE `ganancias_diarias` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total_apostado` decimal(10,2) NOT NULL,
  `total_ganado` decimal(10,2) NOT NULL,
  `comision` decimal(10,2) DEFAULT NULL,
  `ganancias_netas` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ganancias_diarias`
--

INSERT INTO `ganancias_diarias` (`id`, `fecha`, `total_apostado`, `total_ganado`, `comision`, `ganancias_netas`) VALUES
(65, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(66, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(67, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(68, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(69, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(70, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(71, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(72, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(73, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(74, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(75, '2025-05-08', 710000.00, 493000.00, 10850.00, 217000.00),
(76, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(77, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(78, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(79, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(80, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(81, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(82, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(83, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(84, '2025-05-08', 710000.00, 493000.00, NULL, 217000.00),
(85, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(86, '2025-05-13', 710000.00, 493000.00, 28800.00, 498800.00),
(87, '2025-05-13', 710000.00, 493000.00, 28800.00, 498800.00),
(88, '2025-05-13', 710000.00, 493000.00, 28800.00, 498800.00),
(89, '2025-05-13', 710000.00, 493000.00, 28800.00, 498800.00),
(90, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(91, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(92, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(93, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(94, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(95, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(96, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(97, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(98, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(99, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(100, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(101, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(102, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(103, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(104, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(105, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(106, '2025-05-13', 710000.00, 493000.00, NULL, 217000.00),
(107, '2025-05-16', 730000.00, 357000.00, 28800.00, 498800.00),
(108, '2025-05-16', 730000.00, 357000.00, 28800.00, 498800.00),
(109, '2025-05-16', 750000.00, 425000.00, 28800.00, 498800.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ganancias`
--

CREATE TABLE `historial_ganancias` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total_ganado` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_ganancias`
--

INSERT INTO `historial_ganancias` (`id`, `id_usuario`, `fecha`, `total_ganado`) VALUES
(48, 9, '2025-05-08', 51000.00),
(49, 9, '2025-05-08', 51000.00),
(50, 9, '2025-05-08', 51000.00),
(51, 9, '2025-05-08', 51000.00),
(52, 9, '2025-05-08', 51000.00),
(56, 9, '2025-05-08', 51000.00),
(57, 9, '2025-05-08', 51000.00),
(58, 9, '2025-05-08', 51000.00),
(59, 9, '2025-05-13', 51000.00),
(60, 6, '2025-05-16', 0.00),
(61, 6, '2025-05-16', 0.00),
(62, 6, '2025-05-16', 34000.00),
(63, 6, '2025-05-16', 34000.00),
(64, 6, '2025-05-16', 68000.00),
(65, 6, '2025-05-16', 68000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneos`
--

CREATE TABLE `torneos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneos`
--

INSERT INTO `torneos` (`id`, `nombre`) VALUES
(1, 'Liga Colombiana'),
(2, 'UEFA Champions League'),
(3, 'Copa Mundial de la FIFA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `totales_usuarios`
--

CREATE TABLE `totales_usuarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total_ganado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `totales_usuarios`
--

INSERT INTO `totales_usuarios` (`id`, `id_usuario`, `total_ganado`, `actualizado_en`) VALUES
(8, 9, 51000.00, '2025-05-08 22:50:16'),
(9, 6, 68000.00, '2025-05-17 01:32:26'),
(10, 7, 136000.00, '2025-05-06 19:12:41'),
(11, 8, 51000.00, '2025-05-06 19:35:02'),
(12, 11, 119000.00, '2025-05-06 21:42:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo`, `usuario`, `contraseña`, `rol`) VALUES
(1, 'Juan Sebastian', 'sabastiangarcia11@gmail.com', 'juan', '123654789', 'admin'),
(6, 'deivid rios', 'DeividRios22@gmail.com', 'Deivid', '159357', 'usuario'),
(7, 'juan pita', 'juanpita23@gmail.com', 'juan david', '147369258', 'usuario'),
(8, 'Juan Diego', 'juandiego22@gmail.com', 'Diego', '987123654', 'usuario'),
(9, 'sebastian Garcia', 'sebastian23@gmail.com', 'Sebastian', '987456321', 'usuario'),
(10, 'Juan David Urbano', 'juandavidurbano32@gmail.com', 'juandavidurbano', '159632147', 'usuario'),
(11, 'jhonfer', 'jhonfer34@gmail.com', 'jhonfer', '147369258', 'usuario'),
(12, 'Santiago Linares', 'Santiagolinares@gmail.com', 'santiago', '1596321', 'usuario'),
(13, 'jhonatan', 'jhonatan24@gmail.com', 'jhon', '147258369', 'usuario'),
(16, 'Geronimo', 'Geronimo25@gmail.com', 'Geronimo', '123654789', 'usuario'),
(17, 'santiago', 'santiago@gmail.com', 'santiago', '123987456', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apuestas`
--
ALTER TABLE `apuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_torneo` (`id_torneo`);

--
-- Indices de la tabla `ganancias_diarias`
--
ALTER TABLE `ganancias_diarias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_ganancias`
--
ALTER TABLE `historial_ganancias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `torneos`
--
ALTER TABLE `torneos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `totales_usuarios`
--
ALTER TABLE `totales_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apuestas`
--
ALTER TABLE `apuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `ganancias_diarias`
--
ALTER TABLE `ganancias_diarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de la tabla `historial_ganancias`
--
ALTER TABLE `historial_ganancias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `totales_usuarios`
--
ALTER TABLE `totales_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apuestas`
--
ALTER TABLE `apuestas`
  ADD CONSTRAINT `apuestas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_torneo`) REFERENCES `torneos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_ganancias`
--
ALTER TABLE `historial_ganancias`
  ADD CONSTRAINT `historial_ganancias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `totales_usuarios`
--
ALTER TABLE `totales_usuarios`
  ADD CONSTRAINT `totales_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
