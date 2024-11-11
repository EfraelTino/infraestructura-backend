-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2024 a las 15:57:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cocecha`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre_cat` varchar(120) NOT NULL,
  `foto_cat` varchar(80) NOT NULL,
  `fecha_creacion` varchar(20) NOT NULL,
  `cat_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre_cat`, `foto_cat`, `fecha_creacion`, `cat_estado`) VALUES
(1, 'Frutas', '1708848058128-fontanero.webp', '25/Feb/24', 1),
(2, 'Verduras', '1708840672034-construccion.webp', '25/Feb/24', 1),
(3, 'Efrael', 'data20240902215502.webp', '2024-09-02 21:55:02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprador`
--

CREATE TABLE `comprador` (
  `id_u` int(11) NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `apellido` varchar(120) NOT NULL,
  `tel` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `repeat_password` varchar(120) NOT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `feacha_creacion` datetime NOT NULL,
  `is_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprador`
--

INSERT INTO `comprador` (`id_u`, `nombre`, `apellido`, `tel`, `email`, `password`, `repeat_password`, `id_tipo_usuario`, `feacha_creacion`, `is_admin`) VALUES
(1, 'Usuario', 'Comprador', 9512201, 'comprador@gmail.com', '123456', '', 1, '0000-00-00 00:00:00', 0),
(2, '', '', 0, '', '', '', 1, '2024-02-27 00:19:44', 0),
(3, 'Efrael', 'Villanueva', 2147483647, 'efrael2001@gmail.com', '$2b$10$YcNViQ0FZ8kE9inJw821hOxv1xKfkhKa3Rpa6E96rra6VhCpSwH8u', '$2b$10$36VjeoLhfK1qZXh0Re/9M.5RjnvmjSOTAiOkvx64znWr9XMnZSDR6', 1, '2026-02-24 00:00:00', 0),
(4, 'Efrael', 'Villanueva', 2147483647, 'efrael2001s@gmail.com', '$2b$10$IapI0Tkqhs8WRBBgyyyFV.NX5Qky1ZPwTmAPVcEw6nTAnL0k0vcE6', '$2b$10$.l7IllZXzufBIHLLcMqcce0jT9Ub51bCoaaqLC2vHZJ51tVlJ1URy', 1, '2026-02-24 00:00:00', 0),
(5, 'Usuario', 'Cuatro', 2147483647, 'urusario4@gmail.com', '$2b$10$PSHwfah8ja9Oa3dBQR9hgum.EmMIKr7feXdX7Q/e6EzUgG9/M3dw6', '$2b$10$zyKcN/YR68nK3Kr0x98o0uf1CEn4HnE1u9ZCuDnG0e3mPOsAoVR4q', 1, '2026-02-24 00:00:00', 0),
(6, 'Usuario', 'Cuatro', 2147483647, 'urusario4@gmail.com', '$2b$10$J6m1UB8ofFUh9ZoEq3xN/eoCXRR5oN6g7ldZns4SBef0l1MZVohe.', '$2b$10$8Ov8s9vcLrTpHC.MIwK.UuYFjZo0p8Sm0o7MG4Hm.h8tdPXsiufji', 1, '2026-02-24 00:00:00', 0),
(7, 'Usuario', 'Cuatro', 2147483647, 'urusario4@gmail.com', '$2b$10$R1iX1F0Znz/aGOHfmgl0cuMMMQ1Tb0zoiJ5upim8i.zzzM2U8o70W', '$2b$10$8kGX6w3zQxr5XsvgNyOZ7OZvx7xlM5LhXECWfBMShzWjiJGjAtO2S', 1, '2026-02-24 00:00:00', 0),
(8, 'Efrael', 'Villanueva', 915068001, 'efrael200asdasd1@gmail.com', '$2b$10$k7Vs7K.6Worez2s2s9K/vOW8E72lrazOFs7fKzImktyS1A7VYQAVG', '$2b$10$KF72TiSvNvqjOTY/emBb4Oqqj9vrc0bdqfAB3ZKSLdZ14C0P3iEBi', 1, '2024-02-29 01:05:28', 0),
(9, 'Efrael', 'Villanueva', 915068001, 'efrael200asdaasdasdsd1@gmail.com', '$2b$10$uPuyFiboOzOUZGiWXhf.wOcIzAHecyq.MovWWNRSNBzLWzMokESHe', '$2b$10$Uiz9BrvTWU3rs59ligKHBuPJ2Ldh4CANZ97YBmhJvsYsSfDY2cGd2', 1, '2024-02-29 01:05:28', 0),
(10, 'Efrael', 'Villanueva', 915068001, 'efrael200asdaasdaasdsd1@gmail.com', '$2b$10$5q2BWUZkeQBMHs7/1Tj0i.EmhdodxutPmexiXFaYqOJhMKcv8fJdS', '$2b$10$lZVrHO4emyfYoftfvlDTT.JGr6yX/UzGQksQGLWCqo3NruoBD0Gyq', 1, '2024-02-29 01:05:28', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios`
--

CREATE TABLE `precios` (
  `id` int(11) NOT NULL,
  `precio` varchar(15) NOT NULL,
  `contiene` varchar(120) NOT NULL,
  `desde_cantidad` varchar(10) NOT NULL,
  `hasta_candidad` varchar(10) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios`
--

INSERT INTO `precios` (`id`, `precio`, `contiene`, `desde_cantidad`, `hasta_candidad`, `id_producto`) VALUES
(1, '12', '20Kg por saco', '3', '14', 179),
(2, '11.50', '20Kg por saco', '14', '20', 179),
(3, '10', 'Saco 21', '10', '14', 199),
(4, '12', 'Saco 20', '10', '14', 201),
(5, '14', 'Saco 21', '120', '12', 203),
(6, '14', 'Saco 21', '120', '12', 205),
(7, '14', 'Saco 21', '120', '12', 207),
(8, '14', 'Saco 21', '120', '12', 209),
(9, '123123', '123123', '10', '14', 211);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `imagen` varchar(120) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text NOT NULL,
  `calidad` varchar(100) NOT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_subcategoria` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `precio_base` varchar(20) NOT NULL,
  `contacto` varchar(12) NOT NULL,
  `activo` int(11) NOT NULL,
  `is_premium` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `imagen`, `nombre`, `descripcion`, `calidad`, `unidad_medida`, `id_proveedor`, `id_subcategoria`, `fecha_creacion`, `precio_base`, `contacto`, `activo`, `is_premium`) VALUES
(179, 'data20240831204844.webp', 'Producto de prueba', 'Producto de prueba', 'Estándar', 'Kg', 17, 2, '2024-09-14 20:01:01', '12', '996960292', 0, 1),
(199, 'data20240903000051.webp', 'Primer productos', 'asdasd', 'asdasdasd', 'Kg', 17, 10, '2024-09-14 19:53:05', '123', '996960292', 0, 1),
(201, 'data20240903000452.webp', 'Primer productos', 'hola\n', 'asdasdasd', 'Kg', 17, 8, '2024-09-14 19:53:11', '12', '996960292', 1, 0),
(203, 'data20240903000704.webp', 'Primer productos', 'asdasdasd', 'asdasdasd', 'kg', 17, 5, '2024-09-03 05:07:04', '123', '915068001', 1, 0),
(205, 'data20240903000911.webp', 'Primer productos', 'asdasdasd', 'asdasdasd', 'kg', 17, 5, '2024-09-03 05:09:11', '123', '915068001', 1, 0),
(207, 'data20240903000935.webp', 'Primer productos', 'asdasdasd', 'asdasdasd', 'kg', 17, 5, '2024-09-03 05:09:36', '123', '915068001', 1, 0),
(209, 'data20240903001026.webp', 'Primer productos', 'asdasdasd', 'asdasdasd', 'kg', 17, 5, '2024-09-03 05:10:27', '123', '915068001', 1, 0),
(211, 'data20240903210116.webp', 'Primer productos', 'AAAAAAA', 'Estándar', 'Kg', 17, 7, '2024-09-04 02:01:16', '123', '996960292', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_categoria`
--

CREATE TABLE `sub_categoria` (
  `id` int(11) NOT NULL,
  `nombre_subcat` varchar(120) NOT NULL,
  `foto_subcat` varchar(50) NOT NULL,
  `fecha_creacion` varchar(100) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sub_categoria`
--

INSERT INTO `sub_categoria` (`id`, `nombre_subcat`, `foto_subcat`, `fecha_creacion`, `id_categoria`, `estado`) VALUES
(2, 'Plátano', '1708842694600-construccion.webp', '25/Feb/24', 1, 1),
(5, 'Papa', '1708842789292-construccion.webp', '25/Feb/24', 2, 1),
(7, 'Efrael', 'data20240902223527.webp', '2024-09-02 22:35:27', 2, 1),
(8, 'Efrael', 'data20240902223639.webp', '2024-09-02 22:36:39', 2, 1),
(9, 'Efrael', 'data20240902223751.webp', '2024-09-02 22:37:51', 2, 1),
(10, 'Efrael', 'data20240902223814.webp', '2024-09-02 22:38:14', 2, 1),
(11, 'Efrael', 'data20240902223829.webp', '2024-09-02 22:38:29', 2, 1),
(12, 'Efrael', 'data20240902223928.webp', '2024-09-02 22:39:28', 2, 1),
(13, 'Efrael', 'data20240902223944.webp', '2024-09-02 22:39:44', 2, 1),
(14, 'Efrael', 'data20240902223955.webp', '2024-09-02 22:39:55', 2, 1),
(15, 'Efrael', 'data20240902224020.webp', '2024-09-02 22:40:20', 2, 1),
(16, 'Efrael', 'data20240902224034.webp', '2024-09-02 22:40:34', 2, 1),
(17, 'Efrael', 'data20240902224041.webp', '2024-09-02 22:40:41', 2, 1),
(18, 'Efrael', 'data20240902224052.webp', '2024-09-02 22:40:52', 2, 1),
(19, 'Efrael', 'data20240902224133.webp', '2024-09-02 22:41:33', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `tipo`) VALUES
(1, 0),
(3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `apellido` varchar(120) NOT NULL,
  `tel` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `repeat_password` varchar(120) NOT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `is_verify` tinyint(1) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_premium` int(11) NOT NULL,
  `about` varchar(180) NOT NULL,
  `perfil` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `nombre`, `apellido`, `tel`, `email`, `password`, `repeat_password`, `id_tipo_usuario`, `fecha_creacion`, `is_verify`, `foto`, `fecha_inicio`, `is_premium`, `about`, `perfil`) VALUES
(5, 'Usuario', 'Cuatro', 2147483647, 'efraesl2001@gmail.com', '$2b$10$hr/KNJav/jkH71Z11.mHieT1H51S4DVlyL/3jdiJc41JgC.EdvsEa', '$2b$10$H2mtO8CsOS7jWhp0WpR0lOSfaftQxGSI7hbM2cikatOqLChwcETDK', 0, '2026-02-24 00:00:00', 1, '', '2024-08-25 20:27:34', 0, '', ''),
(8, 'Efrael', 'Villanueva', 915068001, 'proveedor@gmail.com', '$2b$10$P9C6IEm3MD58t8iOFmntS.963tQTti7genHABDDVkkp8yUFAuIJaG', '$2b$10$PB2WceqkoXRfUScgkf5VHOHwdBH7vUEYYf7mZSaV2O.cRApOdE8Z6', 2, '2024-04-11 22:45:42', 1, '', '2024-08-29 05:23:39', 0, '', ''),
(9, 'Efrael', 'Villanueva', 915068001, 'comprador@gmail.com', '$2b$10$P9C6IEm3MD58t8iOFmntS.963tQTti7genHABDDVkkp8yUFAuIJaG', '$2b$10$H71Bl33JGCVJ3qVE3Xy5eugfsF.BtqUaTmkq6owEwbgtUU2WgGNqa', 2, '2024-06-11 23:00:28', 1, '', '2024-08-27 06:26:39', 0, '', ''),
(17, 'Efrael', 'Villanueva', 915068001, 'efrael2001@gmail.com', '$2y$10$Ti/fBZ.BWKJtX.7l6xVxgO2YkA3j7sJ8EgWtajcInUaeAAkX1vlqC', '$2y$10$Ti/fBZ.BWKJtX.7l6xVxgO2YkA3j7sJ8EgWtajcInUaeAAkX1vlqC', 1, '2024-08-16 16:25:30', 0, '', '2024-09-18 16:59:16', 0, 'Somos un negocio registrado en la SUNAT, contamos con 2 años de experiencia produciendo productos agrícolas como; papa, cebolla y maíz.', 'data20240828135425.webp'),
(18, 'Junior', 'Villanueva', 0, 'efrael2000@gmail.com', '$2y$10$99Fp1x4F7RCUYYOrcy7RHuWsZjgLvy7FBWUZZs63vaYJBQ7Boij22', '$2y$10$99Fp1x4F7RCUYYOrcy7RHuWsZjgLvy7FBWUZZs63vaYJBQ7Boij22', 1, '2024-09-13 00:52:19', 0, '', '2024-09-13 05:54:19', 0, '', ''),
(19, 'Efrael', 'Villanueva', 0, 'efraela2001@gmail.com', '$2y$10$K76A7yDg5yKSB0FQ7JPxGeMN8S36T/OloBuCrLMABjL9pCqtGT4a6', '$2y$10$K76A7yDg5yKSB0FQ7JPxGeMN8S36T/OloBuCrLMABjL9pCqtGT4a6', 0, '2024-09-13 00:52:56', 0, '', '2024-09-13 05:53:50', 0, '', ''),
(20, 'Efrael', 'Villanueva', 0, 'efarael2001@gmail.com', '$2y$10$Da/QDy5yKjlsWKcJmByUoe4lTjFfW0in02QyZSzGjH3eeUOtOrwmO', '$2y$10$Da/QDy5yKjlsWKcJmByUoe4lTjFfW0in02QyZSzGjH3eeUOtOrwmO', 1, '2024-09-13 00:54:45', 0, '', '2024-09-13 06:24:39', 0, '', ''),
(21, 'Efrael', 'Villanueva', 0, 'efraasdasdel2001@gmail.com', '$2y$10$UaVOUszCZ0bksAxrIWd5q.oCx/CGZ0XCqs3rJIp5zoBaG.I5/SsHy', '$2y$10$UaVOUszCZ0bksAxrIWd5q.oCx/CGZ0XCqs3rJIp5zoBaG.I5/SsHy', 0, '2024-09-13 01:13:16', 0, '', '2024-09-13 06:24:51', 0, '', ''),
(22, 'Efrael', 'Villanueva', 0, 'efrael24001@gmail.com', '$2y$10$3EGwU3SiBrIrB.AUl8GlVutaKgMucZMhmes8hDvBAqM63pQcFFyXW', '$2y$10$3EGwU3SiBrIrB.AUl8GlVutaKgMucZMhmes8hDvBAqM63pQcFFyXW', 1, '2024-10-07 17:36:57', 0, '', '2024-10-08 15:00:20', 0, '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comprador`
--
ALTER TABLE `comprador`
  ADD PRIMARY KEY (`id_u`),
  ADD KEY `id_tipo_usuario` (`id_tipo_usuario`);

--
-- Indices de la tabla `precios`
--
ALTER TABLE `precios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_precio_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_sucategoria` (`id_subcategoria`),
  ADD KEY `fk_productos_proveedores` (`id_proveedor`);

--
-- Indices de la tabla `sub_categoria`
--
ALTER TABLE `sub_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subcategorias_categoria` (`id_categoria`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo_usuario` (`id_tipo_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comprador`
--
ALTER TABLE `comprador`
  MODIFY `id_u` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `precios`
--
ALTER TABLE `precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT de la tabla `sub_categoria`
--
ALTER TABLE `sub_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `precios`
--
ALTER TABLE `precios`
  ADD CONSTRAINT `fk_precio_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_sucategoria` FOREIGN KEY (`id_subcategoria`) REFERENCES `sub_categoria` (`id`),
  ADD CONSTRAINT `fk_productos_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `sub_categoria`
--
ALTER TABLE `sub_categoria`
  ADD CONSTRAINT `fk_subcategorias_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
