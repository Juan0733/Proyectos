-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-12-2024 a las 17:47:16
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
-- Base de datos: `gur_app_clientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_clientes`
--

CREATE TABLE `usuarios_clientes` (
  `num_identificacion_empresa` varchar(15) NOT NULL,
  `tipo_documento` varchar(3) DEFAULT NULL,
  `nombre_empresa` varchar(64) DEFAULT NULL,
  `tipo_empresa` varchar(2) DEFAULT NULL,
  `logo_empresa` varchar(19) DEFAULT NULL,
  `direccion_empresa` varchar(20) DEFAULT NULL,
  `correo_empresa` varchar(64) DEFAULT NULL,
  `telefono_empresa` varchar(10) DEFAULT NULL,
  `num_identificacion_representante` varchar(15) DEFAULT NULL,
  `tipo_documento_representante` varchar(3) DEFAULT NULL,
  `nombre_representante` varchar(64) DEFAULT NULL,
  `apellidos_representante` varchar(64) DEFAULT NULL,
  `telefono_representante` varchar(10) DEFAULT NULL,
  `bdd_empresa` varchar(18) DEFAULT NULL,
  `pw_bdd_empresa` varchar(32) DEFAULT NULL,
  `fecha_registro_empresa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_empresa`
--

CREATE TABLE `usuarios_empresa` (
  `num_identificacion_persona` varchar(15) NOT NULL,
  `tipo_documento_persona` varchar(3) DEFAULT NULL,
  `nombre_persona` varchar(64) DEFAULT NULL,
  `apellidos_persona` varchar(64) DEFAULT NULL,
  `telefono_persona` varchar(10) DEFAULT NULL,
  `correo_persona` varchar(64) DEFAULT NULL,
  `cargo_persona` varchar(2) DEFAULT NULL,
  `nombre_usuario` varchar(32) NOT NULL,
  `fecha_hora_ultima_sesion` datetime DEFAULT NULL,
  `num_identificacion_empresa` varchar(15) DEFAULT NULL,
  `pw_persona` varchar(32) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios_clientes`
--
ALTER TABLE `usuarios_clientes`
  ADD PRIMARY KEY (`num_identificacion_empresa`);

--
-- Indices de la tabla `usuarios_empresa`
--
ALTER TABLE `usuarios_empresa`
  ADD PRIMARY KEY (`num_identificacion_persona`),
  ADD KEY `num_identificacion_empresa` (`num_identificacion_empresa`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios_empresa`
--
ALTER TABLE `usuarios_empresa`
  ADD CONSTRAINT `usuarios_empresa_ibfk_1` FOREIGN KEY (`num_identificacion_empresa`) REFERENCES `usuarios_clientes` (`num_identificacion_empresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
