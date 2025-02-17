-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-02-2025 a las 05:48:02
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
-- Base de datos: `senasoft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `pk_administrador` int(11) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `correo` varchar(64) NOT NULL,
  `contrasena` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`pk_administrador`, `nombres`, `apellidos`, `correo`, `contrasena`) VALUES
(1, 'Juan', 'Hoyos', 'juan@gmail.com', '7c7a2587f9ea662148439ed16205f546cd9a253144aab2af198bec25f3240628b19292afbcc8e9a168ac3daaa3ba1be4b1888c7ed236fed1e522b4bc6ad969c4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquileres`
--

CREATE TABLE `alquileres` (
  `pk_alquiler` int(11) NOT NULL,
  `fk_bicicleta` int(11) DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL,
  `fecha_alquiler` datetime DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alquileres`
--

INSERT INTO `alquileres` (`pk_alquiler`, `fk_bicicleta`, `fk_usuario`, `fecha_alquiler`, `fecha_devolucion`, `total`) VALUES
(14, 3, 8, '2024-10-05 21:04:27', '2024-10-05 21:08:19', 307),
(15, 4, 8, '2024-10-05 21:23:39', '2024-10-05 21:24:01', 30),
(16, 10, 6, '2025-02-13 23:27:36', '2025-02-13 23:28:05', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bicicletas`
--

CREATE TABLE `bicicletas` (
  `pk_bicicleta` int(11) NOT NULL,
  `marca` varchar(30) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `precio_alquiler` int(11) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `fk_region` int(11) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bicicletas`
--

INSERT INTO `bicicletas` (`pk_bicicleta`, `marca`, `color`, `precio_alquiler`, `foto`, `fk_region`, `estado`) VALUES
(1, 'GW', 'Naranja', 5000, 'U12345678.jpg', 1, 'inactiva'),
(2, 'GW', 'Naranja', 5000, 'U12345678.jpg', 1, 'inactiva'),
(3, 'GW', 'Naranja', 5000, 'U12345678.jpg', 1, 'inactiva'),
(4, 'GW', 'Naranja', 5000, 'U12345678.jpg', 1, 'inactiva'),
(5, 'GW', 'Naranja', 5000, 'U12345678.jpg', 1, 'inactiva'),
(6, 'gnk', 'Rojo', 5000, 'U20241001145624.png', 12, 'inactiva'),
(7, 'gnk', 'Rojo', 5000, 'U20241001151259.png', 12, 'inactiva'),
(8, 'gnk', 'Rojo', 5000, 'U20241006203322.png', 30, 'inactiva'),
(9, 'gnk', 'Azul', 5000, 'U20241006210311.png', 2, 'inactiva'),
(10, 'GW', 'Rojo', 2500, 'U20250213225951.png', 12, 'disponible'),
(11, 'GW', 'Rojo', 2500, 'U20250213232221.png', 4, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `pk_evento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `lugar` varchar(50) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`pk_evento`, `nombre`, `descripcion`, `fecha`, `lugar`, `foto`, `estado`) VALUES
(1, 'Tour Sena', 'Recorrido por las calles de cali y sus alrededores', '2024-09-28 00:00:00', 'Cali', 'U12345678.jpg', 'vencido'),
(2, 'Tour Sena', 'Recorrido por las calles de cali y sus alrededores', '2024-10-30 00:00:00', 'Cali', 'U12345678.jpg', 'vencido'),
(3, 'Tour Sena', 'Recorrido por las calles de cali y sus alrededores', '2024-09-28 00:00:00', 'Cali', 'U12345678.jpg', 'vencido'),
(4, 'Tour Sena', 'Recorrido por las calles de cali y sus alrededores', '2024-09-28 00:00:00', 'Cali', 'U12345678.jpg', 'vencido'),
(5, 'Tour', 'Gran evento del sena', '2024-10-20 08:00:00', 'Buga', 'U20241002004651.png', 'inactivo'),
(6, 'Tour', 'Gran evento del sena', '2024-10-20 08:00:00', 'Buga', 'U20241002102319.png', 'vencido'),
(7, 'Tour', 'Gran evento del sena', '2024-10-19 00:00:00', 'Buga', 'U20241002103115.png', 'inactivo'),
(8, 'Tour', 'Gran evento del sena', '2024-10-20 08:00:00', 'Buga', 'U20241002104548.png', 'inactivo'),
(9, 'Tour', 'Gran evento del sena', '2024-10-13 21:46:00', 'Buga', 'U20241002214621.png', 'vencido'),
(10, 'Tour', 'Gran evento del sena', '2024-10-28 09:53:00', 'Buga', 'U20241002215409.png', 'vencido'),
(11, 'Tour', 'Gran evento del sena', '2024-10-11 21:58:00', 'Buga', 'U20241002215830.png', 'inactivo'),
(12, 'Tour', 'Gran evento del sena', '2024-10-03 09:47:00', 'Buga', 'U20241003094529.png', 'vencido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `pk_participante` int(11) NOT NULL,
  `fk_evento` int(11) DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participantes`
--

INSERT INTO `participantes` (`pk_participante`, `fk_evento`, `fk_usuario`) VALUES
(3, 11, 6),
(4, 11, 8),
(5, 2, 8),
(6, 9, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regiones`
--

CREATE TABLE `regiones` (
  `pk_region` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `regiones`
--

INSERT INTO `regiones` (`pk_region`, `nombre`) VALUES
(1, 'Amazonas'),
(2, 'Antioquia'),
(3, 'Arauca'),
(4, 'Atlántico'),
(5, 'Bolívar'),
(6, 'Boyacá'),
(7, 'Caldas'),
(8, 'Caquetá'),
(9, 'Casanare'),
(10, 'Cauca'),
(11, 'Cesar'),
(12, 'Chocó'),
(13, 'Córdoba '),
(14, 'Cundinamarca '),
(15, 'Guainía'),
(16, 'Guaviare'),
(17, 'Huila '),
(18, 'La Guajira'),
(19, 'Magdalena '),
(20, 'Meta'),
(21, 'Nariño'),
(22, 'Norte de Santander '),
(23, 'Putumayo'),
(24, 'Quindío'),
(25, 'Risaralda '),
(26, 'San Andrés y Providencia'),
(27, 'Santander'),
(28, 'Sucre'),
(29, 'Tolima'),
(30, 'Valle del Cauca'),
(31, 'Vaupés'),
(32, 'Vichada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `pk_usuario` int(11) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `correo` varchar(64) NOT NULL,
  `contrasena` varchar(128) NOT NULL,
  `estrato` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`pk_usuario`, `nombres`, `apellidos`, `correo`, `contrasena`, `estrato`) VALUES
(6, 'Juan David', 'Tilmans Restrepo', 'davidrestrepo0733@gmail.com', '7c7a2587f9ea662148439ed16205f546cd9a253144aab2af198bec25f3240628b19292afbcc8e9a168ac3daaa3ba1be4b1888c7ed236fed1e522b4bc6ad969c4', '3'),
(7, 'juan estea', 'sfdfdfdfd', 'kevin_jdiazb@soy.sena.edu.co', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', '3'),
(8, 'Carlos', 'Restrepo', 'carlos@gmail.com', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', '3');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`pk_administrador`);

--
-- Indices de la tabla `alquileres`
--
ALTER TABLE `alquileres`
  ADD PRIMARY KEY (`pk_alquiler`),
  ADD KEY `fk_usuario` (`fk_usuario`),
  ADD KEY `fk_bicicleta` (`fk_bicicleta`);

--
-- Indices de la tabla `bicicletas`
--
ALTER TABLE `bicicletas`
  ADD PRIMARY KEY (`pk_bicicleta`),
  ADD KEY `fk_region` (`fk_region`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`pk_evento`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`pk_participante`),
  ADD KEY `fk_usuario` (`fk_usuario`),
  ADD KEY `fk_evento` (`fk_evento`);

--
-- Indices de la tabla `regiones`
--
ALTER TABLE `regiones`
  ADD PRIMARY KEY (`pk_region`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`pk_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `pk_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alquileres`
--
ALTER TABLE `alquileres`
  MODIFY `pk_alquiler` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bicicletas`
--
ALTER TABLE `bicicletas`
  MODIFY `pk_bicicleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `pk_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `pk_participante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `regiones`
--
ALTER TABLE `regiones`
  MODIFY `pk_region` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `pk_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alquileres`
--
ALTER TABLE `alquileres`
  ADD CONSTRAINT `alquileres_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`pk_usuario`),
  ADD CONSTRAINT `alquileres_ibfk_2` FOREIGN KEY (`fk_bicicleta`) REFERENCES `bicicletas` (`pk_bicicleta`);

--
-- Filtros para la tabla `bicicletas`
--
ALTER TABLE `bicicletas`
  ADD CONSTRAINT `bicicletas_ibfk_1` FOREIGN KEY (`fk_region`) REFERENCES `regiones` (`pk_region`);

--
-- Filtros para la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`pk_usuario`),
  ADD CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`fk_evento`) REFERENCES `eventos` (`pk_evento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
