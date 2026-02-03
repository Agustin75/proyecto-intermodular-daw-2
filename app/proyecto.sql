-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 18:56:42
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
(7, 'root', '$2y$10$tQRGLlG2ZPj8oECa5UJxr.ZOJ/ETIT7a972P4AnKzc111E4OCMPV6', 'r@email.com', 0, 'default', 0, 3);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
