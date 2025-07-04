-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2025 a las 09:44:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12


CREATE DATABASE db_finan_cias2;

USE db_finan_cias2;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_finan_cias2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesor_producto`
--

CREATE TABLE `asesor_producto` (
  `ID_Asesor_Producto` int(11) NOT NULL,
  `ID_Personal` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Descripcion_AP` text DEFAULT NULL,
  `Fecha_Asignacion` datetime NOT NULL DEFAULT current_timestamp(),
  `Estado_AsesorProducto` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asesor_producto`
--

INSERT INTO `asesor_producto` (`ID_Asesor_Producto`, `ID_Personal`, `ID_Producto`, `Descripcion_AP`, `Fecha_Asignacion`, `Estado_AsesorProducto`) VALUES
(1, 1, 4, 'Asignado como asesor para productos de inversión tipo CDT a corto plazo.', '2025-07-01 00:00:00', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `ID_Bitacora` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `ID_Personal` int(11) DEFAULT NULL,
  `ID_RegistroAsesoramiento` int(11) DEFAULT NULL,
  `Tipo_Evento` varchar(50) NOT NULL,
  `Descripcion_Evento` text DEFAULT NULL,
  `Fecha_Hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`ID_Bitacora`, `ID_Cliente`, `ID_Personal`, `ID_RegistroAsesoramiento`, `Tipo_Evento`, `Descripcion_Evento`, `Fecha_Hora`) VALUES
(1, 1, 1, 1, 'Asesoramiento Inicial', 'Se brindó asesoramiento financiero sobre manejo de presupuesto mensual.', '2025-07-04 15:32:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID_Cliente` int(11) NOT NULL,
  `Nombre_Cliente` varchar(50) NOT NULL,
  `Apellido_Cliente` varchar(50) NOT NULL,
  `ID_Genero` int(11) NOT NULL,
  `ID_TD` int(11) NOT NULL,
  `N_Documento_Cliente` varchar(45) NOT NULL,
  `Celular_Cliente` varchar(45) NOT NULL,
  `Correo_Cliente` varchar(45) NOT NULL,
  `Direccion_Cliente` varchar(100) NOT NULL,
  `Ciudad_Cliente` varchar(50) NOT NULL,
  `Fecha_Nacimiento_Cliente` date NOT NULL,
  `ID_Personal_Creador` int(11) DEFAULT NULL,
  `Fecha_Creacion_Cliente` datetime DEFAULT current_timestamp(),
  `Estado_Cliente` tinyint(1) NOT NULL DEFAULT 1,
  `Contraseña` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID_Cliente`, `Nombre_Cliente`, `Apellido_Cliente`, `ID_Genero`, `ID_TD`, `N_Documento_Cliente`, `Celular_Cliente`, `Correo_Cliente`, `Direccion_Cliente`, `Ciudad_Cliente`, `Fecha_Nacimiento_Cliente`, `ID_Personal_Creador`, `Fecha_Creacion_Cliente`, `Estado_Cliente`, `Contraseña`) VALUES
(1, 'SIN', 'CLIENTE', 3, 1, '0000000000', '000000000', 'SIN@gmail.com', 'DESCONOCIDAD', 'DESCONOCIDAD', '2000-01-01', NULL, '2025-06-29 15:27:36', 0, '741852963QAZWSXEDCRFVTGBYHNUJMIKOLPÑ'),
(2, 'Cliente', 'prueba', 1, 3, '0000000005', '313000008', 'Cliente@gmail.com', 'el SENA', 'Puerto Boyaca', '2000-01-01', NULL, '2025-06-20 17:04:54', 1, '12345'),
(3, 'Cliente2', 'Aprobado', 1, 1, '0000000006', '313000009', 'ClienteAprobado@gmail.com', 'SENA', 'PUERTO BOYACA', '2000-01-01', 1, '2025-06-29 16:25:07', 1, '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE `credito` (
  `ID_Credito` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `Monto_Total_Credito` decimal(15,2) NOT NULL,
  `Monto_Pendiente_Credito` decimal(15,2) NOT NULL,
  `Fecha_Apertura_Credito` datetime NOT NULL DEFAULT current_timestamp(),
  `Fecha_Vencimiento_Credito` date NOT NULL,
  `ID_Producto` int(11) NOT NULL DEFAULT 4,
  `ID_Estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotacredito`
--

CREATE TABLE `cuotacredito` (
  `ID_CuotaCredito` int(11) NOT NULL,
  `ID_Credito` int(11) NOT NULL,
  `Numero_Cuota` int(11) NOT NULL,
  `Monto_Capital` decimal(15,2) NOT NULL,
  `Monto_Interes` decimal(15,2) NOT NULL,
  `Monto_Total_Cuota` decimal(15,2) NOT NULL,
  `Fecha_Vencimiento` date NOT NULL,
  `Fecha_Pago` datetime DEFAULT NULL,
  `Monto_Pagado` decimal(15,2) DEFAULT 0.00,
  `Dias_Mora_Al_Pagar` int(11) DEFAULT 0,
  `Monto_Recargo_Mora` decimal(15,2) DEFAULT 0.00,
  `ID_Estado_Cuota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `ID_Estado` int(11) NOT NULL,
  `Estado` varchar(45) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Tipo_Estado` enum('Turno','Credito','Pago','Cuota','General','Personal','Producto') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`ID_Estado`, `Estado`, `Descripcion`, `Tipo_Estado`) VALUES
(1, 'Espera', 'Turno en espera de atencion', 'Turno'),
(2, 'Atendido', 'Turno atendido por asesor', 'Turno'),
(3, 'Terminado', 'Turno finalizado', 'Turno'),
(4, 'Activo', 'Crédito activo y vigente', 'Credito'),
(5, 'Vencido', 'Crédito vencido', 'Credito'),
(6, 'Cancelado', 'Crédito cancelado', 'Credito'),
(7, 'Pagado', 'Crédito pagado completamente', 'Credito'),
(8, 'Mora', 'Crédito atrasado', 'Credito'),
(9, 'Pendiente', 'Pago pendiente', 'Pago'),
(10, 'Completado', 'Pago completado', 'Pago'),
(11, 'Rechazado', 'Pago rechazado', 'Pago'),
(12, 'Activo', 'Registro activo', 'General'),
(13, 'Inactivo', 'Registro inactivo', 'General');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `ID_Genero` int(11) NOT NULL,
  `Genero` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`ID_Genero`, `Genero`) VALUES
(1, 'Hombre'),
(2, 'Mujer'),
(3, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagocuota`
--

CREATE TABLE `pagocuota` (
  `ID_PagoCuota` int(11) NOT NULL,
  `ID_CuotaCredito` int(11) NOT NULL,
  `ID_Personal` int(11) DEFAULT NULL,
  `Fecha_Hora_Pago` datetime NOT NULL DEFAULT current_timestamp(),
  `Monto_Pagado_Transaccion` decimal(15,2) NOT NULL,
  `ID_Estado_Pago` int(11) NOT NULL,
  `Observaciones_Pago` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE `periodo` (
  `ID_Periodo` int(11) NOT NULL,
  `Periodo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodo`
--

INSERT INTO `periodo` (`ID_Periodo`, `Periodo`) VALUES
(1, 'Diaria'),
(2, 'Mensual'),
(3, 'Bimestral'),
(4, 'Trimestral'),
(5, 'Semestral'),
(6, 'Anual');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `ID_Personal` int(11) NOT NULL,
  `Nombre_Personal` varchar(50) NOT NULL,
  `Apellido_Personal` varchar(50) NOT NULL,
  `ID_Rol` int(11) NOT NULL,
  `ID_Genero` int(11) NOT NULL,
  `ID_TD` int(11) NOT NULL,
  `N_Documento_Personal` varchar(45) NOT NULL,
  `Celular_Personal` varchar(45) NOT NULL,
  `Correo_Personal` varchar(45) NOT NULL,
  `Contraseña_Personal` varchar(255) NOT NULL,
  `Activo_Personal` tinyint(1) NOT NULL DEFAULT 1,
  `Fecha_Creacion_Personal` datetime NOT NULL DEFAULT current_timestamp(),
  `Foto_Perfil_Personal` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`ID_Personal`, `Nombre_Personal`, `Apellido_Personal`, `ID_Rol`, `ID_Genero`, `ID_TD`, `N_Documento_Personal`, `Celular_Personal`, `Correo_Personal`, `Contraseña_Personal`, `Activo_Personal`, `Fecha_Creacion_Personal`, `Foto_Perfil_Personal`) VALUES
(1, 'Gerente', 'Primero', 1, 1, 1, '0000000000', '3130000001', 'jsdmngzc@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:00:44', NULL),
(2, 'SubGerente', 'Segundo', 2, 2, 2, '0000000002', '3130000002', 'SubGerente@gmail.com', '12345', 1, '2025-06-20 17:02:48', NULL),
(3, 'Asesor', 'tercero', 3, 3, 3, '0000000003', '3130000003', 'Asesor@gmail.com', '12345', 1, '2025-06-20 17:02:48', NULL),
(4, 'Cajero', 'Cuarto', 4, 1, 1, '0000000004', '3130000004', 'Cajero@gmail.com', '12345', 1, '2025-06-20 17:02:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre_Producto` varchar(100) NOT NULL,
  `Descripcion_Producto` text DEFAULT NULL,
  `ID_TI` int(11) NOT NULL,
  `Monto_Minimo` decimal(12,2) NOT NULL,
  `Monto_Maximo` decimal(12,2) NOT NULL,
  `Plazo_Minimo` int(11) NOT NULL COMMENT 'En meses',
  `Plazo_Maximo` int(11) NOT NULL COMMENT 'En meses',
  `Activo_Producto` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_Producto`, `Nombre_Producto`, `Descripcion_Producto`, `ID_TI`, `Monto_Minimo`, `Monto_Maximo`, `Plazo_Minimo`, `Plazo_Maximo`, `Activo_Producto`) VALUES
(1, 'Préstamo Personal', 'Préstamo para necesidades personales', 1, 1000000.00, 50000000.00, 6, 60, 1),
(2, 'Préstamo Hipotecario', 'Préstamo para compra de vivienda', 2, 50000000.00, 500000000.00, 60, 240, 1),
(3, 'Préstamo Vehicular', 'Préstamo para compra de vehículo', 3, 10000000.00, 200000000.00, 12, 84, 1),
(4, 'Luis Fernando', 'No sé cómo, pero funciono a la primera jajajaja', 2, 20.00, 60.00, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroasesoramiento`
--

CREATE TABLE `registroasesoramiento` (
  `ID_RegistroAsesoramiento` int(11) NOT NULL,
  `ID_Personal` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `ID_Turno` int(11) DEFAULT NULL,
  `Fecha_Hora_Inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `Fecha_Hora_Fin` datetime NOT NULL DEFAULT current_timestamp(),
  `Observaciones` text DEFAULT NULL,
  `Resultado` enum('Aprobado','Denegado','Pendiente') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroasesoramiento`
--

INSERT INTO `registroasesoramiento` (`ID_RegistroAsesoramiento`, `ID_Personal`, `ID_Cliente`, `ID_Turno`, `Fecha_Hora_Inicio`, `Fecha_Hora_Fin`, `Observaciones`, `Resultado`) VALUES
(1, 1, 1, 1, '2025-07-04 14:00:00', '2025-07-04 14:45:00', 'Cliente interesado en consolidar sus deudas. Se identificaron gastos innecesarios.', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `ID_Rol` int(11) NOT NULL,
  `Rol` varchar(50) NOT NULL,
  `Nivel_Acceso` int(11) NOT NULL,
  `Descripcion_Rol` text NOT NULL,
  `Permisos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`ID_Rol`, `Rol`, `Nivel_Acceso`, `Descripcion_Rol`, `Permisos`) VALUES
(1, 'Gerente', 1, 'Gerente general con acceso completo al sistema', 'Todos los permisos'),
(2, 'SubGerente', 2, 'Subgerente con acceso casi completo al sistema', 'Gestionar personal, productos, tasas, ver reportes'),
(3, 'Asesor', 3, 'Asesor financiero que atiende clientes', 'Gestionar clientes, créditos, asesoramientos, turnos'),
(4, 'Cajero', 4, 'Cajero que procesa pagos', 'Procesar pagos, ver información de créditos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa_interes`
--

CREATE TABLE `tasa_interes` (
  `ID_TI` int(11) NOT NULL,
  `Tasa_Interes` decimal(5,2) NOT NULL,
  `ID_Periodo` int(11) NOT NULL,
  `Activo_TI` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasa_interes`
--

INSERT INTO `tasa_interes` (`ID_TI`, `Tasa_Interes`, `ID_Periodo`, `Activo_TI`) VALUES
(1, 45.78, 4, 1),
(2, 8.75, 1, 1),
(3, 10.25, 1, 1),
(4, 50.50, 4, 1),
(11, 75.00, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `ID_TD` int(11) NOT NULL,
  `Tipo_Documento` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`ID_TD`, `Tipo_Documento`) VALUES
(1, 'Cedula'),
(2, 'Tarjeta'),
(3, 'Extranjera'),
(4, 'Pasaporte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `ID_Turno` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `Nombre_Completo_Solicitante` varchar(100) DEFAULT NULL,
  `N_Documento_Solicitante` varchar(45) DEFAULT NULL,
  `Numero_Turno` varchar(20) NOT NULL,
  `ID_Producto_Interes` int(11) DEFAULT NULL,
  `Fecha_Hora_Solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `Fecha_Hora_Finalizacion` datetime DEFAULT NULL,
  `ID_Estado_Turno` int(11) NOT NULL DEFAULT 1,
  `Tiempo_Espera_Minutos` int(11) DEFAULT NULL,
  `Motivo_Turno` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`ID_Turno`, `ID_Cliente`, `Nombre_Completo_Solicitante`, `N_Documento_Solicitante`, `Numero_Turno`, `ID_Producto_Interes`, `Fecha_Hora_Solicitud`, `Fecha_Hora_Finalizacion`, `ID_Estado_Turno`, `Tiempo_Espera_Minutos`, `Motivo_Turno`) VALUES
(1, 1, 'Laura Camila Ríos Martínez', '1032456789', 'A-102', 4, '2025-07-04 13:30:00', '2025-07-04 14:00:00', 1, 15, 'Solicitud de asesoría para inversión en CDT'),
(2, NULL, 'Jose Dominguez Cuero', '1032456789', 'A-102', 4, '2025-07-04 13:30:00', '2025-07-04 14:00:00', 2, 15, 'Solicitud de asesoría para inversión en CDT'),
(3, NULL, 'Jose Dominguez Cuero', '1037665857', 'T001', NULL, '2025-07-04 05:25:59', NULL, 1, NULL, NULL),
(4, NULL, 'Prueb Turno', '102626', 'T001', NULL, '2025-07-04 06:39:57', NULL, 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asesor_producto`
--
ALTER TABLE `asesor_producto`
  ADD PRIMARY KEY (`ID_Asesor_Producto`),
  ADD KEY `fk_ap_personal` (`ID_Personal`),
  ADD KEY `fk_ap_producto` (`ID_Producto`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`ID_Bitacora`),
  ADD KEY `fk_bitacora_cliente` (`ID_Cliente`),
  ADD KEY `fk_bitacora_personal` (`ID_Personal`),
  ADD KEY `fk_bitacora_registro_asesoramiento` (`ID_RegistroAsesoramiento`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ID_Cliente`),
  ADD UNIQUE KEY `N_Documento_Cliente` (`N_Documento_Cliente`),
  ADD KEY `fk_cliente_genero` (`ID_Genero`),
  ADD KEY `fk_cliente_tipo_documento` (`ID_TD`),
  ADD KEY `fk_cliente_personal_creador` (`ID_Personal_Creador`);

--
-- Indices de la tabla `credito`
--
ALTER TABLE `credito`
  ADD PRIMARY KEY (`ID_Credito`),
  ADD KEY `fk_credito_cliente` (`ID_Cliente`),
  ADD KEY `fk_credito_producto` (`ID_Producto`),
  ADD KEY `fk_credito_estado` (`ID_Estado`);

--
-- Indices de la tabla `cuotacredito`
--
ALTER TABLE `cuotacredito`
  ADD PRIMARY KEY (`ID_CuotaCredito`),
  ADD UNIQUE KEY `ID_Credito` (`ID_Credito`,`Numero_Cuota`),
  ADD KEY `fk_cuota_estado` (`ID_Estado_Cuota`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`ID_Estado`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`ID_Genero`);

--
-- Indices de la tabla `pagocuota`
--
ALTER TABLE `pagocuota`
  ADD PRIMARY KEY (`ID_PagoCuota`),
  ADD KEY `fk_pago_cuota_credito` (`ID_CuotaCredito`),
  ADD KEY `fk_pago_personal` (`ID_Personal`),
  ADD KEY `fk_pago_estado` (`ID_Estado_Pago`);

--
-- Indices de la tabla `periodo`
--
ALTER TABLE `periodo`
  ADD PRIMARY KEY (`ID_Periodo`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`ID_Personal`),
  ADD UNIQUE KEY `N_Documento_Personal` (`N_Documento_Personal`),
  ADD UNIQUE KEY `Correo_Personal` (`Correo_Personal`),
  ADD KEY `fk_personal_rol` (`ID_Rol`),
  ADD KEY `fk_personal_genero` (`ID_Genero`),
  ADD KEY `fk_personal_tipo_documento` (`ID_TD`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_Producto`),
  ADD KEY `fk_producto_tasa_interes` (`ID_TI`);

--
-- Indices de la tabla `registroasesoramiento`
--
ALTER TABLE `registroasesoramiento`
  ADD PRIMARY KEY (`ID_RegistroAsesoramiento`),
  ADD KEY `fk_personal` (`ID_Personal`),
  ADD KEY `fk_cliente` (`ID_Cliente`),
  ADD KEY `fk_turno` (`ID_Turno`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID_Rol`);

--
-- Indices de la tabla `tasa_interes`
--
ALTER TABLE `tasa_interes`
  ADD PRIMARY KEY (`ID_TI`),
  ADD KEY `fk_tasa_interes_periodo` (`ID_Periodo`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`ID_TD`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`ID_Turno`),
  ADD KEY `fk_turno_cliente` (`ID_Cliente`),
  ADD KEY `fk_turno_producto` (`ID_Producto_Interes`),
  ADD KEY `fk_turno_estado` (`ID_Estado_Turno`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asesor_producto`
--
ALTER TABLE `asesor_producto`
  MODIFY `ID_Asesor_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `ID_Bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `credito`
--
ALTER TABLE `credito`
  MODIFY `ID_Credito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuotacredito`
--
ALTER TABLE `cuotacredito`
  MODIFY `ID_CuotaCredito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `ID_Estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `ID_Genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagocuota`
--
ALTER TABLE `pagocuota`
  MODIFY `ID_PagoCuota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `ID_Periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `ID_Personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `registroasesoramiento`
--
ALTER TABLE `registroasesoramiento`
  MODIFY `ID_RegistroAsesoramiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_Rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tasa_interes`
--
ALTER TABLE `tasa_interes`
  MODIFY `ID_TI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `ID_TD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `ID_Turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asesor_producto`
--
ALTER TABLE `asesor_producto`
  ADD CONSTRAINT `fk_ap_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
  ADD CONSTRAINT `fk_ap_producto` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`);

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `fk_bitacora_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_bitacora_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
  ADD CONSTRAINT `fk_bitacora_registro_asesoramiento` FOREIGN KEY (`ID_RegistroAsesoramiento`) REFERENCES `registroasesoramiento` (`ID_RegistroAsesoramiento`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_genero` FOREIGN KEY (`ID_Genero`) REFERENCES `genero` (`ID_Genero`),
  ADD CONSTRAINT `fk_cliente_personal_creador` FOREIGN KEY (`ID_Personal_Creador`) REFERENCES `personal` (`ID_Personal`),
  ADD CONSTRAINT `fk_cliente_tipo_documento` FOREIGN KEY (`ID_TD`) REFERENCES `tipo_documento` (`ID_TD`);

--
-- Filtros para la tabla `credito`
--
ALTER TABLE `credito`
  ADD CONSTRAINT `fk_credito_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_credito_estado` FOREIGN KEY (`ID_Estado`) REFERENCES `estado` (`ID_Estado`),
  ADD CONSTRAINT `fk_credito_producto` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`);

--
-- Filtros para la tabla `cuotacredito`
--
ALTER TABLE `cuotacredito`
  ADD CONSTRAINT `fk_cuota_credito` FOREIGN KEY (`ID_Credito`) REFERENCES `credito` (`ID_Credito`),
  ADD CONSTRAINT `fk_cuota_estado` FOREIGN KEY (`ID_Estado_Cuota`) REFERENCES `estado` (`ID_Estado`);

--
-- Filtros para la tabla `pagocuota`
--
ALTER TABLE `pagocuota`
  ADD CONSTRAINT `fk_pago_cuota_credito` FOREIGN KEY (`ID_CuotaCredito`) REFERENCES `cuotacredito` (`ID_CuotaCredito`),
  ADD CONSTRAINT `fk_pago_estado` FOREIGN KEY (`ID_Estado_Pago`) REFERENCES `estado` (`ID_Estado`),
  ADD CONSTRAINT `fk_pago_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`);

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `fk_personal_genero` FOREIGN KEY (`ID_Genero`) REFERENCES `genero` (`ID_Genero`),
  ADD CONSTRAINT `fk_personal_rol` FOREIGN KEY (`ID_Rol`) REFERENCES `rol` (`ID_Rol`),
  ADD CONSTRAINT `fk_personal_tipo_documento` FOREIGN KEY (`ID_TD`) REFERENCES `tipo_documento` (`ID_TD`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_tasa_interes` FOREIGN KEY (`ID_TI`) REFERENCES `tasa_interes` (`ID_TI`);

--
-- Filtros para la tabla `registroasesoramiento`
--
ALTER TABLE `registroasesoramiento`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
  ADD CONSTRAINT `fk_turno` FOREIGN KEY (`ID_Turno`) REFERENCES `turno` (`ID_Turno`);

--
-- Filtros para la tabla `tasa_interes`
--
ALTER TABLE `tasa_interes`
  ADD CONSTRAINT `fk_tasa_interes_periodo` FOREIGN KEY (`ID_Periodo`) REFERENCES `periodo` (`ID_Periodo`);

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `fk_turno_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_turno_estado` FOREIGN KEY (`ID_Estado_Turno`) REFERENCES `estado` (`ID_Estado`),
  ADD CONSTRAINT `fk_turno_producto` FOREIGN KEY (`ID_Producto_Interes`) REFERENCES `producto` (`ID_Producto`);
COMMIT;
