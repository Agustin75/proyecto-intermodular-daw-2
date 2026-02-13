-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 15:36:46
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
  `num_opciones` int(2) NOT NULL,
  `num_requerido` int(11) NOT NULL COMMENT 'El número de Pokémon que el usuario debe clasificar correctamente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_clasificar`
--

INSERT INTO `j_clasificar` (`id`, `id_pokemon`, `id_tipo`, `num_pokemon`, `num_opciones`, `num_requerido`) VALUES
(20, 1, 2, 11, 11, 1),
(21, 5, 1, 2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_tipo_adivinanza`
--

CREATE TABLE `j_tipo_adivinanza` (
  `id` int(11) NOT NULL,
  `tipo_adivinanza` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_tipo_adivinanza`
--

INSERT INTO `j_tipo_adivinanza` (`id`, `tipo_adivinanza`) VALUES
(1, 'Grito'),
(2, 'Silueta'),
(3, 'Descripción');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_tipo_clasificar`
--

CREATE TABLE `j_tipo_clasificar` (
  `id` int(11) NOT NULL,
  `tipo_clasificar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_tipo_clasificar`
--

INSERT INTO `j_tipo_clasificar` (`id`, `tipo_clasificar`) VALUES
(1, 'Tipo'),
(2, 'Generación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_enunciado`
--

CREATE TABLE `j_trivia_enunciado` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `tiempo` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_trivia_enunciado`
--

INSERT INTO `j_trivia_enunciado` (`id`, `id_pokemon`, `pregunta`, `tiempo`) VALUES
(15, 4, 'Pregunta 1', 3),
(16, 24, 'asd', 2),
(17, 282, 'ajsdakjsd', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_opcion`
--

CREATE TABLE `j_trivia_opcion` (
  `id` int(11) NOT NULL,
  `opcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_trivia_opcion`
--

INSERT INTO `j_trivia_opcion` (`id`, `opcion`) VALUES
(20, '1'),
(21, '2'),
(22, '22'),
(23, 'aa'),
(24, 'ss');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `j_trivia_respuesta`
--

CREATE TABLE `j_trivia_respuesta` (
  `id_pregunta` int(11) NOT NULL,
  `id_opcion` int(11) NOT NULL,
  `esCorrecta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `j_trivia_respuesta`
--

INSERT INTO `j_trivia_respuesta` (`id_pregunta`, `id_opcion`, `esCorrecta`) VALUES
(15, 20, 1),
(15, 21, 0),
(16, 21, 0),
(16, 22, 1),
(17, 23, 1),
(17, 24, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon_usuario`
--

CREATE TABLE `pokemon_usuario` (
  `id_pokemon` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pokemon_usuario`
--

INSERT INTO `pokemon_usuario` (`id_pokemon`, `id_usuario`, `favorito`) VALUES
(1, 7, 0),
(1, 8, 1),
(4, 7, 0),
(4, 8, 0),
(4, 11, 0),
(5, 7, 1),
(5, 8, 1),
(24, 7, 0),
(24, 8, 1),
(24, 11, 0),
(282, 7, 0),
(282, 8, 0),
(282, 11, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `contrasenya` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `nivel` int(1) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `contrasenya`, `email`, `activo`, `imagen`, `puntuacion`, `nivel`) VALUES
(1, 'admin', 'admin', 'cooladmin@pokehunt.net', 0, 'default', 0, 3),
(2, 'ffffff', '$2y$10$WxsKF4qMD47MpurUztDxOuf1fvmrNy3h1', 'f@gmail.com', 0, 'default', 0, 0),
(3, 'wedfs', '$2y$10$feri9CimuPPFPlsP8j.SzuRtSuvDTNjhP', 'addfs@gmail.com', 0, 'default', 0, 2),
(4, 'usuario', '$2y$10$RyKIwVkr62c3saPB7zhtiu6q9i7eIXt2A', 'a@gmail.com', 0, 'default', 0, 3),
(5, 'z', '$2y$10$pg9LiO/X1wNSz6uZ/.TK2e8yyGLvSyTHw', 'z@gmail.com', 0, 'default', 0, 2),
(6, 'a', '$2y$10$0mYf6P.0ExHv6R.pxcfK6.g31bKyT9Y4/qp7fzyz9yoVDfBDTDoFq', 'a@gmail.com', 0, 'default', 0, 2),
(7, 'root', '$2y$10$tQRGLlG2ZPj8oECa5UJxr.ZOJ/ETIT7a972P4AnKzc111E4OCMPV6', 'r@email.com', 1, 'default', 0, 3),
(8, 'Ash', '$2y$10$xtZuuYs3Vcp2JBpvdk3qb.RdgmPkSV7YWo4JzJR6HhJXBFdxOk4za', 'ash@paleta.com', 0, 'imagen2', 0, 2),
(9, 'p', '$2y$10$AtpBNhS2j.jDDW6vlRYxeOxpFRZTGfAoh/MFOFQ/bH0.cYU1kNQZ2', 'p@email.com', 0, 'default', 0, 2),
(11, 'x', '$2y$10$qZslcad6O4GxTf1znJHesecQ3vvhb.QslwVKnOHIdG8BImGGFXANG', 'x@email.com', 0, 'default', 0, 2);

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
  ADD PRIMARY KEY (`id_pregunta`,`id_opcion`),
  ADD KEY `id_opcion` (`id_opcion`);

--
-- Indices de la tabla `pokemon_usuario`
--
ALTER TABLE `pokemon_usuario`
  ADD PRIMARY KEY (`id_pokemon`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `j_trivia_enunciado`
--
ALTER TABLE `j_trivia_enunciado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `j_trivia_opcion`
--
ALTER TABLE `j_trivia_opcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `j_trivia_opcion` (`id`),
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `j_trivia_enunciado` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
