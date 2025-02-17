-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2025 a las 02:26:53
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
-- Base de datos: `gur_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditorias`
--

CREATE TABLE `auditorias` (
  `contador` int(11) NOT NULL,
  `id_movimiento` varchar(16) DEFAULT NULL,
  `accion` varchar(6) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `contador` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `ubicacion` varchar(50) DEFAULT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `emoji` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`contador`, `nombre`, `ubicacion`, `fk_usr_sistema`, `fecha_registro`, `emoji`) VALUES
(1, 'Bebidas frias', 'Cocina principal', '1234567893', '2024-12-25 21:39:47', 40),
(3, 'Carnes', 'Cocina', '1234567893', '2024-12-25 21:39:49', 43),
(5, 'Comida Rapida', 'cocina', '1234567893', '2025-01-01 16:10:12', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedidos`
--

CREATE TABLE `detalles_pedidos` (
  `contador` int(11) NOT NULL,
  `fk_pedido` int(11) NOT NULL,
  `fk_producto` varchar(16) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float NOT NULL,
  `total` float NOT NULL,
  `estado_cocina` varchar(14) NOT NULL DEFAULT 'Sin preparar',
  `estado_mesero` varchar(12) NOT NULL DEFAULT 'Sin entregar',
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedidos`
--

INSERT INTO `detalles_pedidos` (`contador`, `fk_pedido`, `fk_producto`, `cantidad`, `precio_compra`, `precio_venta`, `total`, `estado_cocina`, `estado_mesero`, `fecha_registro`, `estado`) VALUES
(24, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:08:54', 'INACTIVO'),
(25, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:08:54', 'INACTIVO'),
(26, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Entregado', '2025-01-26 20:08:54', 'ACTIVO'),
(27, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:08:54', 'ACTIVO'),
(28, 7, 'P002', 1, 20, 25, 25, 'Sin preparar', 'Sin entregar', '2025-01-26 20:08:54', 'INACTIVO'),
(29, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:16:44', 'ACTIVO'),
(30, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:16:44', 'ACTIVO'),
(31, 7, '12345678912', 1, 1000, 12000, 12000, 'Sin preparar', 'Sin entregar', '2025-01-26 20:16:44', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_ventas`
--

CREATE TABLE `detalles_ventas` (
  `contador` int(11) NOT NULL,
  `fk_venta` varchar(15) NOT NULL,
  `fk_producto` varchar(16) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `codigo_ingrediente` varchar(16) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fk_categoria` int(11) NOT NULL,
  `unidad_medida` varchar(10) NOT NULL,
  `stock_actual` float NOT NULL,
  `stock_minimo` float NOT NULL,
  `precio_compra` float NOT NULL,
  `presentacion` varchar(25) NOT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`codigo_ingrediente`, `nombre`, `fk_categoria`, `unidad_medida`, `stock_actual`, `stock_minimo`, `precio_compra`, `presentacion`, `fk_usr_sistema`, `fecha_registro`, `estado`) VALUES
('09876543214', 'pollo', 3, 'gramo', 1, 1, 28000, 'unidad', '1234567897', '2025-01-21 08:27:36', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes_productos`
--

CREATE TABLE `ingredientes_productos` (
  `contador` int(11) NOT NULL,
  `fk_producto` varchar(16) NOT NULL,
  `fk_ingrediente` varchar(16) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes_productos`
--

INSERT INTO `ingredientes_productos` (`contador`, `fk_producto`, `fk_ingrediente`, `cantidad`, `estado`) VALUES
(20, 'PC20250121082844', '09876543214', 500, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `numero` varchar(3) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'ACTIVO',
  `capacidad` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`numero`, `nombre`, `fecha_registro`, `estado`, `capacidad`) VALUES
('1', 'M1', '2024-12-24 13:31:00', 'ACTIVO', ''),
('2', 'M2', '2025-01-14 22:11:10', 'ACTIVO', ''),
('3', 'M3', '2025-01-16 22:24:42', 'ACTIVO', ''),
('4', 'M4', '2025-01-16 22:25:46', 'ACTIVO', ''),
('5', 'M5', '2025-01-16 22:31:07', 'ACTIVO', ''),
('6', 'M6', '2025-01-16 22:34:03', 'ACTIVO', ''),
('7', 'M7', '2025-01-16 22:34:38', 'ACTIVO', ''),
('8', 'M8', '2025-01-24 23:25:58', 'ACTIVO', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `contador` int(11) NOT NULL,
  `tipo_movimiento` varchar(7) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fk_ingrediente` varchar(16) DEFAULT NULL,
  `fk_producto` varchar(16) DEFAULT NULL,
  `cantidad` float NOT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `contador` int(11) NOT NULL,
  `titulo` varchar(20) NOT NULL,
  `fk_mesa` varchar(3) NOT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL,
  `total` float NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'ACTIVO',
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`contador`, `titulo`, `fk_mesa`, `fk_usr_sistema`, `total`, `estado`, `fecha_registro`) VALUES
(7, 'principal', '1', '1234567893', 60000, 'ACTIVO', '2025-01-26 20:08:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigo_producto` varchar(16) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fk_categoria` int(11) NOT NULL,
  `tipo` varchar(6) NOT NULL,
  `foto` varchar(20) NOT NULL,
  `stock_actual` float DEFAULT NULL,
  `stock_minimo` float DEFAULT NULL,
  `fecha_registro` datetime NOT NULL,
  `presentacion` varchar(25) DEFAULT NULL,
  `unidad_medida` varchar(10) DEFAULT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float NOT NULL,
  `estado` varchar(8) NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`codigo_producto`, `nombre`, `fk_categoria`, `tipo`, `foto`, `stock_actual`, `stock_minimo`, `fecha_registro`, `presentacion`, `unidad_medida`, `fk_usr_sistema`, `precio_compra`, `precio_venta`, `estado`) VALUES
('12345678912', 'Cocacola', 1, 'estand', 'IM20250114224319.jpg', 1, 1, '2025-01-14 22:43:19', 'Plato', 'unidad', '1234567893', 1000, 12000, 'ACTIVO'),
('P001', 'Producto 1', 1, 'estand', 'sin_foto.jpg', 100, 10, '2025-01-19 16:55:54', 'Presentación 1', 'Unidad 1', '1234567890', 10, 15, 'ACTIVO'),
('P002', 'Producto 2', 1, 'estand', 'sin_foto.jpg', 200, 20, '2025-01-19 16:55:54', 'Presentación 2', 'Unidad 2', '1234567890', 20, 25, 'ACTIVO'),
('P003', 'Producto 3', 1, 'estand', 'sin_foto.jpg', 150, 15, '2025-01-19 16:55:54', 'Presentación 3', 'Unidad 3', '1234567890', 30, 35, 'ACTIVO'),
('P004', 'Producto 4', 1, 'estand', 'sin_foto.jpg', 250, 25, '2025-01-19 16:55:54', 'Presentación 4', 'Unidad 4', '1234567890', 40, 45, 'ACTIVO'),
('P005', 'Producto 5', 1, 'estand', 'sin_foto.jpg', 300, 30, '2025-01-19 16:55:54', 'Presentación 5', 'Unidad 5', '1234567890', 50, 55, 'ACTIVO'),
('P006', 'Producto 6', 1, 'estand', 'sin_foto.jpg', 350, 35, '2025-01-19 16:55:54', 'Presentación 6', 'Unidad 6', '1234567890', 60, 65, 'ACTIVO'),
('P007', 'Producto 7', 1, 'estand', 'sin_foto.jpg', 400, 40, '2025-01-19 16:55:54', 'Presentación 7', 'Unidad 7', '1234567890', 70, 75, 'ACTIVO'),
('P008', 'Producto 8', 1, 'estand', 'sin_foto.jpg', 450, 45, '2025-01-19 16:55:54', 'Presentación 8', 'Unidad 8', '1234567890', 80, 85, 'ACTIVO'),
('P009', 'Producto 9', 1, 'estand', 'sin_foto.jpg', 500, 50, '2025-01-19 16:55:54', 'Presentación 9', 'Unidad 9', '1234567890', 90, 95, 'ACTIVO'),
('P010', 'Producto 10', 1, 'estand', 'sin_foto.jpg', 550, 55, '2025-01-19 16:55:54', 'Presentación 10', 'Unidad 10', '1234567890', 100, 105, 'ACTIVO'),
('P011', 'Producto 11', 1, 'estand', 'sin_foto.jpg', 600, 60, '2025-01-19 16:55:54', 'Presentación 11', 'Unidad 11', '1234567890', 110, 115, 'ACTIVO'),
('P012', 'Producto 12', 1, 'estand', 'sin_foto.jpg', 650, 65, '2025-01-19 16:55:54', 'Presentación 12', 'Unidad 12', '1234567890', 120, 125, 'ACTIVO'),
('P013', 'Producto 13', 1, 'estand', 'sin_foto.jpg', 700, 70, '2025-01-19 16:55:54', 'Presentación 13', 'Unidad 13', '1234567890', 130, 135, 'ACTIVO'),
('P014', 'Producto 14', 1, 'estand', 'sin_foto.jpg', 750, 75, '2025-01-19 16:55:54', 'Presentación 14', 'Unidad 14', '1234567890', 140, 145, 'ACTIVO'),
('P015', 'Producto 15', 1, 'estand', 'sin_foto.jpg', 800, 80, '2025-01-19 16:55:54', 'Presentación 15', 'Unidad 15', '1234567890', 150, 155, 'ACTIVO'),
('P016', 'Producto 16', 1, 'estand', 'sin_foto.jpg', 850, 85, '2025-01-19 16:55:54', 'Presentación 16', 'Unidad 16', '1234567890', 160, 165, 'ACTIVO'),
('P017', 'Producto 17', 1, 'estand', 'sin_foto.jpg', 900, 90, '2025-01-19 16:55:54', 'Presentación 17', 'Unidad 17', '1234567890', 170, 175, 'ACTIVO'),
('P018', 'Producto 18', 1, 'estand', 'sin_foto.jpg', 950, 95, '2025-01-19 16:55:54', 'Presentación 18', 'Unidad 18', '1234567890', 180, 185, 'ACTIVO'),
('P019', 'Producto 19', 1, 'estand', 'sin_foto.jpg', 1000, 100, '2025-01-19 16:55:54', 'Presentación 19', 'Unidad 19', '1234567890', 190, 195, 'ACTIVO'),
('P020', 'Producto 20', 1, 'estand', 'sin_foto.jpg', 1050, 105, '2025-01-19 16:55:54', 'Presentación 20', 'Unidad 20', '1234567890', 200, 205, 'ACTIVO'),
('PC20250119150916', 'Alita', 3, 'cocina', 'IM20250119150916.jpg', NULL, NULL, '2025-01-19 15:09:16', 'Plato', NULL, '1234567893', NULL, 10000, 'ACTIVO'),
('PC20250121082844', 'Pizza Hawaina', 5, 'cocina', 'IM20250121082844.jpg', NULL, NULL, '2025-01-21 08:28:44', 'Plato', NULL, '1234567897', NULL, 10000, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_vendidos`
--

CREATE TABLE `productos_vendidos` (
  `contador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fk_producto` varchar(16) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `codigo_venta` varchar(15) NOT NULL,
  `fk_pedido` int(11) DEFAULT NULL,
  `tipo_venta` varchar(6) NOT NULL,
  `total` float NOT NULL,
  `ganancia` float DEFAULT NULL,
  `fecha_registro` datetime NOT NULL,
  `fk_usr_sistema` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditorias`
--
ALTER TABLE `auditorias`
  ADD PRIMARY KEY (`contador`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`contador`);

--
-- Indices de la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_pedido` (`fk_pedido`),
  ADD KEY `fk_producto` (`fk_producto`);

--
-- Indices de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_venta` (`fk_venta`),
  ADD KEY `fk_producto` (`fk_producto`);

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`codigo_ingrediente`),
  ADD KEY `fk_categoria` (`fk_categoria`);

--
-- Indices de la tabla `ingredientes_productos`
--
ALTER TABLE `ingredientes_productos`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_producto` (`fk_producto`),
  ADD KEY `fk_ingrediente` (`fk_ingrediente`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`numero`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_ingrediente` (`fk_ingrediente`),
  ADD KEY `fk_producto` (`fk_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_mesa` (`fk_mesa`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo_producto`),
  ADD KEY `fk_categoria` (`fk_categoria`);

--
-- Indices de la tabla `productos_vendidos`
--
ALTER TABLE `productos_vendidos`
  ADD PRIMARY KEY (`contador`),
  ADD KEY `fk_producto` (`fk_producto`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`codigo_venta`),
  ADD KEY `fk_pedido` (`fk_pedido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditorias`
--
ALTER TABLE `auditorias`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingredientes_productos`
--
ALTER TABLE `ingredientes_productos`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos_vendidos`
--
ALTER TABLE `productos_vendidos`
  MODIFY `contador` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  ADD CONSTRAINT `detalles_pedidos_ibfk_1` FOREIGN KEY (`fk_pedido`) REFERENCES `pedidos` (`contador`),
  ADD CONSTRAINT `detalles_pedidos_ibfk_2` FOREIGN KEY (`fk_producto`) REFERENCES `productos` (`codigo_producto`);

--
-- Filtros para la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  ADD CONSTRAINT `detalles_ventas_ibfk_1` FOREIGN KEY (`fk_venta`) REFERENCES `ventas` (`codigo_venta`),
  ADD CONSTRAINT `detalles_ventas_ibfk_2` FOREIGN KEY (`fk_producto`) REFERENCES `productos` (`codigo_producto`);

--
-- Filtros para la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `ingredientes_ibfk_1` FOREIGN KEY (`fk_categoria`) REFERENCES `categorias` (`contador`);

--
-- Filtros para la tabla `ingredientes_productos`
--
ALTER TABLE `ingredientes_productos`
  ADD CONSTRAINT `ingredentes_productos_ibfk_1` FOREIGN KEY (`fk_producto`) REFERENCES `productos` (`codigo_producto`),
  ADD CONSTRAINT `ingredentes_productos_ibfk_2` FOREIGN KEY (`fk_ingrediente`) REFERENCES `ingredientes` (`codigo_ingrediente`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`fk_ingrediente`) REFERENCES `ingredientes` (`codigo_ingrediente`),
  ADD CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`fk_producto`) REFERENCES `productos` (`codigo_producto`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`fk_mesa`) REFERENCES `mesas` (`numero`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`fk_categoria`) REFERENCES `categorias` (`contador`);

--
-- Filtros para la tabla `productos_vendidos`
--
ALTER TABLE `productos_vendidos`
  ADD CONSTRAINT `productos_vendidos_ibfk_1` FOREIGN KEY (`fk_producto`) REFERENCES `productos` (`codigo_producto`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`fk_pedido`) REFERENCES `pedidos` (`contador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
