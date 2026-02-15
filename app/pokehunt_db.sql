-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 06:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokehunt_db`
--
CREATE DATABASE IF NOT EXISTS pokehunt_db;
USE pokehunt_db;

-- --------------------------------------------------------

--
-- Table structure for table `j_adivinanza`
--

CREATE TABLE `j_adivinanza` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `pista1` text NOT NULL,
  `pista2` text NOT NULL,
  `pista3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_adivinanza`
--

INSERT INTO `j_adivinanza` (`id`, `id_pokemon`, `id_tipo`, `pista1`, `pista2`, `pista3`) VALUES
(1, 25, 2, 'Es un roedor.', 'Puedes encontrarlo en el Bosque Verde.', 'Es la mascota de Pokémon.'),
(2, 282, 1, 'Fue introducido en la tercera generación.', 'No se puede encontrar salvaje en Hoenn.', 'Uno de sus tipos es el Psíquico.'),
(3, 773, 3, 'Es de tipo normal.', 'Fue introducido en la generación 7.', 'Fue creado en base a un Pokémon legendario.');

-- --------------------------------------------------------

--
-- Table structure for table `j_clasificar`
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
-- Dumping data for table `j_clasificar`
--

INSERT INTO `j_clasificar` (`id`, `id_pokemon`, `id_tipo`, `num_pokemon`, `num_opciones`, `num_requerido`) VALUES
(22, 493, 1, 4, 4, 3),
(23, 1, 2, 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `j_tipo_adivinanza`
--

CREATE TABLE `j_tipo_adivinanza` (
  `id` int(11) NOT NULL,
  `tipo_adivinanza` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_tipo_adivinanza`
--

INSERT INTO `j_tipo_adivinanza` (`id`, `tipo_adivinanza`) VALUES
(1, 'Grito'),
(2, 'Silueta'),
(3, 'Descripción');

-- --------------------------------------------------------

--
-- Table structure for table `j_tipo_clasificar`
--

CREATE TABLE `j_tipo_clasificar` (
  `id` int(11) NOT NULL,
  `tipo_clasificar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_tipo_clasificar`
--

INSERT INTO `j_tipo_clasificar` (`id`, `tipo_clasificar`) VALUES
(1, 'Tipo'),
(2, 'Generación');

-- --------------------------------------------------------

--
-- Table structure for table `j_trivia_enunciado`
--

CREATE TABLE `j_trivia_enunciado` (
  `id` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `tiempo` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_trivia_enunciado`
--

INSERT INTO `j_trivia_enunciado` (`id`, `id_pokemon`, `pregunta`, `tiempo`) VALUES
(18, 6, '¿Cuántos Pokémon existen en la primera generación de Pokémon?', 20);

-- --------------------------------------------------------

--
-- Table structure for table `j_trivia_opcion`
--

CREATE TABLE `j_trivia_opcion` (
  `id` int(11) NOT NULL,
  `opcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_trivia_opcion`
--

INSERT INTO `j_trivia_opcion` (`id`, `opcion`) VALUES
(25, '150'),
(26, '151');

-- --------------------------------------------------------

--
-- Table structure for table `j_trivia_respuesta`
--

CREATE TABLE `j_trivia_respuesta` (
  `id_pregunta` int(11) NOT NULL,
  `id_opcion` int(11) NOT NULL,
  `esCorrecta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_trivia_respuesta`
--

INSERT INTO `j_trivia_respuesta` (`id_pregunta`, `id_opcion`, `esCorrecta`) VALUES
(18, 25, 0),
(18, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pokemon_usuario`
--

CREATE TABLE `pokemon_usuario` (
  `id_pokemon` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pokemon_usuario`
--

INSERT INTO `pokemon_usuario` (`id_pokemon`, `id_usuario`, `favorito`) VALUES
(25, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
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
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `contrasenya`, `email`, `activo`, `imagen`, `puntuacion`, `nivel`) VALUES
(1, 'root', '$2y$10$tQRGLlG2ZPj8oECa5UJxr.ZOJ/ETIT7a972P4AnKzc111E4OCMPV6', 'r@email.com', 1, 'avatar00', 0, 3),
(2, 'Ash', '$2y$10$xtZuuYs3Vcp2JBpvdk3qb.RdgmPkSV7YWo4JzJR6HhJXBFdxOk4za', 'ash@paleta.com', 1, 'avatar00', 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indexes for table `j_clasificar`
--
ALTER TABLE `j_clasificar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indexes for table `j_tipo_adivinanza`
--
ALTER TABLE `j_tipo_adivinanza`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j_trivia_enunciado`
--
ALTER TABLE `j_trivia_enunciado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j_trivia_opcion`
--
ALTER TABLE `j_trivia_opcion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j_trivia_respuesta`
--
ALTER TABLE `j_trivia_respuesta`
  ADD PRIMARY KEY (`id_pregunta`,`id_opcion`),
  ADD KEY `id_opcion` (`id_opcion`);

--
-- Indexes for table `pokemon_usuario`
--
ALTER TABLE `pokemon_usuario`
  ADD PRIMARY KEY (`id_pokemon`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `j_clasificar`
--
ALTER TABLE `j_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `j_tipo_clasificar`
--
ALTER TABLE `j_tipo_clasificar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `j_trivia_enunciado`
--
ALTER TABLE `j_trivia_enunciado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `j_trivia_opcion`
--
ALTER TABLE `j_trivia_opcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `j_adivinanza`
--
ALTER TABLE `j_adivinanza`
  ADD CONSTRAINT `j_adivinanza_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `j_tipo_adivinanza` (`id`);

--
-- Constraints for table `j_clasificar`
--
ALTER TABLE `j_clasificar`
  ADD CONSTRAINT `j_clasificar_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `j_tipo_clasificar` (`id`);

--
-- Constraints for table `j_trivia_respuesta`
--
ALTER TABLE `j_trivia_respuesta`
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `j_trivia_opcion` (`id`),
  ADD CONSTRAINT `j_trivia_respuesta_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `j_trivia_enunciado` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
