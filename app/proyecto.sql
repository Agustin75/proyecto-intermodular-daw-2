-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-01-2026 a las 15:19:06
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_adivinanza`
--

CREATE TABLE `j_adivinanza` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `pista1` text NOT NULL,
  `pista2` text NOT NULL,
  `pista3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_clasificar`
--

CREATE TABLE `j_clasificar` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `num_pokemon` int(3) NOT NULL,
  `num_opciones` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_tipo_adivinanza`
--

CREATE TABLE `j_tipo_adivinanza` (
  `id` int(11) NOT NULL,
  `tipo_adivinanza` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_tipo_clasificar`
--

CREATE TABLE `j_tipo_clasificar` (
  `id` int(11) NOT NULL,
  `tipo_clasificar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_enunciado`
--

CREATE TABLE `j_trivia_enunciado` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `segundos` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_opcion`
--

CREATE TABLE `j_trivia_opcion` (
  `id` int(11) NOT NULL,
  `opcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_respuesta`
--

CREATE TABLE `j_trivia_respuesta` (
  `id_pregunta` int(11) NOT NULL,
  `id_opción` int(11) NOT NULL,
  `esCorrecta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon_usuario`
--

CREATE TABLE `pokemon_usuario` (
  `id_pokemon` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_juego`
--

CREATE TABLE `tipo_juego` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `contrasenya` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  `nivel` int(1) NOT NULL,
  `puntuacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `j_clasificar`
--
ALTER TABLE `j_clasificar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `j_tipo_adivinanza`
--
ALTER TABLE `j_tipo_adivinanza`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `j_trivia_enunciado`
--
ALTER TABLE `j_trivia_enunciado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `j_trivia_opcion`
--
ALTER TABLE `j_trivia_opcion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `j_trivia_respuesta`
--
ALTER TABLE `j_trivia_respuesta`
  ADD PRIMARY KEY (`id_pregunta`,`id_opción`),
  ADD KEY `id_opción` (`id_opción`);

--
-- Indices de la tabla `pokemon_usuario`
--
ALTER TABLE `pokemon_usuario`
  ADD PRIMARY KEY (`id_pokemon`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tipo_juego`
--
ALTER TABLE `tipo_juego`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `j_clasificar`
--
ALTER TABLE `j_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `j_trivia_enunciado`
--
ALTER TABLE `j_trivia_enunciado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `j_trivia_opcion`
--
ALTER TABLE `j_trivia_opcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  ADD CONSTRAINT `j_adivinanza_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `j_tipo_adivinanza` (`id`);

--
-- Filtros para la tabla `j_clasificar`
--
ALTER TABLE `j_clasificar`
  ADD CONSTRAINT `j_clasificar_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `j_tipo_clasificar` (`id`);

--
-- Filtros para la tabla `j_trivia_respuesta`
--
ALTER TABLE `j_trivia_respuesta`
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_1` FOREIGN KEY (`id_opción`) REFERENCES `j_trivia_opcion` (`id`),
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `j_trivia_enunciado` (`id`);

--
-- Filtros para la tabla `pokemon_usuario`
--
ALTER TABLE `pokemon_usuario`
  ADD CONSTRAINT `pokemon_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
