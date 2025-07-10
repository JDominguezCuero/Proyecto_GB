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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_mora_cuota_individual` (IN `p_idCuotaCredito` INT)   BEGIN
    DECLARE v_fechaVencimiento DATE;
    DECLARE v_montoTotalCuota DECIMAL(10,2);
    DECLARE v_tasaMoraAnual DECIMAL(5,2); -- Tasa de mora fija
    DECLARE v_diasMora INT;
    DECLARE v_montoRecargoMora DECIMAL(10,2);
    DECLARE v_idEstadoCuota INT; -- Estado actual de la cuota

    -- Establecemos la tasa de mora anual fija
    SET v_tasaMoraAnual = 28.00; -- 28.00% anual

    -- 1. Obtener los datos necesarios de la cuota
    SELECT
        Fecha_Vencimiento,
        Monto_Total_Cuota,
        ID_Estado_Cuota
    INTO
        v_fechaVencimiento,
        v_montoTotalCuota,
        v_idEstadoCuota
    FROM
        CuotaCredito
    WHERE
        ID_CuotaCredito = p_idCuotaCredito;

    -- Solo calcular mora si la cuota NO está pagada (ID_Estado_Cuota = 7 'Pagado')
    -- y si la cuota existe y tiene fecha de vencimiento válida
    IF v_idEstadoCuota IS NOT NULL AND v_idEstadoCuota != 7 AND v_fechaVencimiento IS NOT NULL THEN
        -- Calcular días en mora: desde la fecha de vencimiento hasta la fecha actual
        SET v_diasMora = DATEDIFF(CURDATE(), v_fechaVencimiento);

        -- Asegurarse de que los días en mora no sean negativos (si aún no vence o si venció hoy)
        SET v_diasMora = GREATEST(0, v_diasMora);

        -- Calcular recargo por mora
        IF v_diasMora > 0 THEN
            -- La tasa moratoria diaria se aplica sobre el Monto_Total_Cuota
            SET v_montoRecargoMora = (v_montoTotalCuota * (v_tasaMoraAnual / 100 / 365)) * v_diasMora;
            
            -- Actualizar el estado a "Mora" si tiene días de mora y no está ya en ese estado
            -- Asumiendo 8 es el ID para 'Mora'
            IF v_idEstadoCuota != 8 THEN
                 UPDATE CuotaCredito
                 SET ID_Estado_Cuota = 8 -- Cambiar estado a Mora
                 WHERE ID_CuotaCredito = p_idCuotaCredito;
            END IF;
        ELSE
            SET v_montoRecargoMora = 0;
            -- Opcional: Si el estado era 'Mora' y ahora no tiene días de mora,
            -- podrías cambiarlo a 'Pendiente' o 'Activo' (si es el caso).
            -- Asumiré que no lo cambiamos automáticamente si los días de mora llegan a 0 por un pago,
            -- ya que el estado 'Pagado' se maneja por separado.
        END IF;

        -- Actualizar los campos de mora en la tabla CuotaCredito
        UPDATE CuotaCredito
        SET
            Dias_Mora_Al_Pagar = v_diasMora,
            Monto_Recargo_Mora = ROUND(v_montoRecargoMora, 2) -- Redondear a 2 decimales
        WHERE
            ID_CuotaCredito = p_idCuotaCredito;
    END IF;

END$$

DELIMITER ;

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
(2, 5, 1, NULL, '2025-07-05 18:12:35', 'Activo'),
(3, 6, 37, NULL, '2025-07-05 18:12:35', 'Activo'),
(4, 6, 23, NULL, '2025-07-05 18:12:35', 'Activo'),
(5, 6, 19, NULL, '2025-07-05 18:12:35', 'Activo'),
(6, 6, 17, NULL, '2025-07-05 18:12:35', 'Activo'),
(7, 6, 38, NULL, '2025-07-05 18:12:35', 'Activo'),
(8, 7, 28, NULL, '2025-07-05 18:12:35', 'Activo'),
(9, 7, 40, NULL, '2025-07-05 18:12:35', 'Activo'),
(10, 7, 39, NULL, '2025-07-05 18:12:35', 'Activo'),
(11, 7, 20, NULL, '2025-07-05 18:12:35', 'Activo'),
(12, 7, 1, NULL, '2025-07-05 18:12:35', 'Activo'),
(13, 8, 24, NULL, '2025-07-05 18:12:35', 'Activo'),
(14, 8, 13, NULL, '2025-07-05 18:12:35', 'Activo'),
(15, 8, 21, NULL, '2025-07-05 18:12:35', 'Activo'),
(16, 8, 27, NULL, '2025-07-05 18:12:35', 'Activo'),
(17, 9, 40, NULL, '2025-07-05 18:12:35', 'Activo'),
(18, 9, 37, NULL, '2025-07-05 18:12:35', 'Activo'),
(19, 9, 28, NULL, '2025-07-05 18:12:35', 'Activo'),
(20, 9, 24, NULL, '2025-07-05 18:12:35', 'Activo'),
(21, 10, 39, NULL, '2025-07-05 18:12:35', 'Activo'),
(22, 10, 13, NULL, '2025-07-05 18:12:35', 'Activo'),
(23, 10, 21, NULL, '2025-07-05 18:12:35', 'Activo'),
(24, 10, 27, NULL, '2025-07-05 18:12:35', 'Activo'),
(25, 10, 1, NULL, '2025-07-05 18:12:35', 'Activo'),
(26, 11, 20, NULL, '2025-07-05 18:12:35', 'Activo'),
(27, 11, 38, NULL, '2025-07-05 18:12:35', 'Activo'),
(28, 11, 23, NULL, '2025-07-05 18:12:35', 'Activo'),
(29, 11, 19, NULL, '2025-07-05 18:12:35', 'Activo'),
(30, 11, 17, NULL, '2025-07-05 18:12:35', 'Activo'),
(31, 12, 13, NULL, '2025-07-05 18:12:35', 'Activo'),
(32, 12, 37, NULL, '2025-07-05 18:12:35', 'Activo'),
(33, 12, 28, NULL, '2025-07-05 18:12:35', 'Activo'),
(34, 12, 24, NULL, '2025-07-05 18:12:35', 'Activo'),
(35, 12, 1, NULL, '2025-07-05 18:12:35', 'Activo'),
(36, 13, 21, NULL, '2025-07-05 18:12:35', 'Activo'),
(37, 13, 23, NULL, '2025-07-05 18:12:35', 'Activo'),
(38, 13, 19, NULL, '2025-07-05 18:12:35', 'Activo'),
(39, 13, 17, NULL, '2025-07-05 18:12:35', 'Activo'),
(40, 13, 38, NULL, '2025-07-05 18:12:35', 'Activo'),
(41, 14, 27, NULL, '2025-07-05 18:12:35', 'Activo'),
(42, 14, 40, NULL, '2025-07-05 18:12:35', 'Activo'),
(43, 14, 39, NULL, '2025-07-05 18:12:35', 'Activo'),
(44, 14, 20, NULL, '2025-07-05 18:12:35', 'Activo'),
(45, 15, 23, NULL, '2025-07-05 18:12:35', 'Activo'),
(46, 15, 37, NULL, '2025-07-05 18:12:35', 'Activo'),
(47, 15, 28, NULL, '2025-07-05 18:12:35', 'Activo'),
(48, 15, 24, NULL, '2025-07-05 18:12:35', 'Activo'),
(49, 16, 19, NULL, '2025-07-05 18:12:35', 'Activo'),
(50, 16, 13, NULL, '2025-07-05 18:12:35', 'Activo'),
(51, 16, 21, NULL, '2025-07-05 18:12:35', 'Activo'),
(52, 16, 27, NULL, '2025-07-05 18:12:35', 'Activo'),
(53, 16, 38, NULL, '2025-07-05 18:12:35', 'Activo'),
(54, 17, 17, NULL, '2025-07-05 18:12:35', 'Activo'),
(55, 17, 40, NULL, '2025-07-05 18:12:35', 'Activo'),
(56, 17, 39, NULL, '2025-07-05 18:12:35', 'Activo'),
(57, 17, 20, NULL, '2025-07-05 18:12:35', 'Activo'),
(58, 17, 1, NULL, '2025-07-05 18:12:35', 'Activo'),
(62, 3, 7, NULL, '2025-07-09 12:34:33', 'Activo'),
(63, 8, 41, NULL, '2025-07-10 13:47:46', 'Activo'),
(64, 10, 41, NULL, '2025-07-10 13:49:28', 'Activo'),
(65, 14, 41, NULL, '2025-07-10 13:49:28', 'Activo'),
(66, 16, 41, NULL, '2025-07-10 13:49:54', 'Activo');

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
  `Estado_Cliente` varchar(255) DEFAULT 'Activo',
  `Contraseña` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE `credito` (
  `ID_Credito` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `Monto_Total_Credito` decimal(15,2) NOT NULL,
  `Desembolso` varchar(255) DEFAULT NULL,
  `Monto_Pendiente_Credito` decimal(15,2) NOT NULL,
  `Fecha_Apertura_Credito` datetime NOT NULL DEFAULT current_timestamp(),
  `Fecha_Vencimiento_Credito` date NOT NULL,
  `ID_Producto` int(11) NOT NULL DEFAULT 4,
  `ID_Estado` int(11) NOT NULL,
  `Tasa_Interes_Anual` decimal(5,2) DEFAULT 0.00,
  `Tasa_Interes_Periodica` decimal(7,5) DEFAULT 0.00000,
  `Numero_Cuotas` int(11) DEFAULT 0,
  `Valor_Cuota_Calculado` decimal(10,2) DEFAULT 0.00,
  `Periodicidad` varchar(20) DEFAULT 'Mensual'
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
(13, 'Inactivo', 'Registro inactivo', 'General'),
(14, 'Pendiente ', 'Crédito pendiente', 'Credito');

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
(1, 'Gerente', 'Admin', 1, 1, 1, '0000000000', '3130000001', 'gerente@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:00:44', NULL),
(2, 'SubGerente', 'Admin', 2, 2, 2, '0000000002', '3130000002', 'subGerente@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:02:48', NULL),
(3, 'Asesor', 'Admin', 3, 3, 3, '0000000003', '3130000003', 'asesor@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:02:48', NULL),
(4, 'Cajero', 'Admin', 4, 1, 1, '0000000004', '3130000004', 'cajero@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:02:48', NULL),
(5, 'ADMIN', 'SUPER', 5, 1, 1, '0000000001', '3206339397', 'admin1$#$@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-07-06 13:03:05', 'N/A'),
(6, 'Nubia Lorena', 'Montoya Palomo', 3, 3, 3, '1056785288', '3130000001', 'montoyapalomo1997@gmail.com', '$2y$10$rzP7mlZzKcup2vc/Agq/1epXqyg9O4F9CzFdS.4NG0DFLaaQTKs4a', 1, '2025-06-20 17:02:48', NULL),
(7, 'Dina Mayerli', 'Muñoz Erazo', 3, 3, 3, '1118473918', '3130000001', 'izajhoss@gmail.com', '$2y$10$3F7xsoYl7dGn.5fAyhB4gupHK.wnHyqsZyg/lfbFTm6mRRfpQ2pbC', 1, '2025-06-20 17:02:48', NULL),
(8, 'Luisa Fernanda', 'Cuesta Rivera', 3, 3, 3, '1056770991', '3130000002', 'luisacuesta180@gmail.com', '$2y$10$pQ2b.W38yG5XmbMOAgm7r.CgCijpMqM05DhWdStj2uAiR725Tqel2', 1, '2025-06-20 17:02:48', NULL),
(9, 'Angela Maria', 'Valencia Tabarez', 3, 3, 3, '1036220463', '3130000003', 'Angelavalenciatabarez09@gmail.com', '$2y$10$unKbRqmTnHlkzs/40zCJQeq1fhwdshVCdbUG5fltyVU.S9hzrjr4m', 1, '2025-06-20 17:02:48', NULL),
(10, 'Miguel Ángel', 'Hernández Cárdenas', 3, 3, 3, '1073680139', '3130000004', 'hernandezcardenas288@gmail.com', '$2y$10$lJYqgq/AW9k89gwKnm0l.uT1URD9lwuRpiMnK1SFFaGFw/4sWM9R2', 1, '2025-06-20 17:02:48', NULL),
(11, 'Lilian Yohana', 'Ocampo Henao', 3, 3, 3, '1056777856', '3130000005', 'yoanaocampohenao@gmail.com', '$2y$10$cFtGjXz4S6qT2Osji/pLEOsxTEub0L8Fq/Q00GQEAN2UO.KOKkuoe', 1, '2025-06-20 17:02:48', NULL),
(12, 'Naftali Estefani', 'Moreno Murcia', 3, 3, 3, '1007201990', '3130000006', 'morenonaftali@gmail.com', '$2y$10$1wlVVNE3lkPDdO9ZjWCchuMznR9anCzewkj4gzafy5C9rLaWGDMFS', 1, '2025-06-20 17:02:48', NULL),
(13, 'Siara Vanesa', 'Madero Ramirez', 3, 3, 3, '1056784532', '3130000007', 'saramadero96@gmail.com', '$2y$10$XQh6gnIYO9CyFednl6IP6elWKZvZatQKtLh905/qWtTDOUpz4v2g.', 1, '2025-06-20 17:02:48', NULL),
(14, 'Ana Yulieth', 'Palacio Cuellar', 3, 3, 3, '1099262144', '3130000008', 'Anayuliethpalacio04@gmail.com', '$2y$10$lQJoLLcjkIRrzzyZj1JheOVTXIr0qT3SivMPJdTkZ4k6pt7NjDvCq', 1, '2025-06-20 17:02:48', NULL),
(15, 'Nicoll Daniela', 'Flores Pacheco', 3, 3, 3, '1056770987', '3130000009', 'florezdaniela313@gmail.com', '$2y$10$ZLsV5nAI7Ds64Dy1iADH1.gHcI3iWhyLINMcfSbX.BTqaQCPRZ5L6', 1, '2025-06-20 17:02:48', NULL),
(16, 'Angie Lorena', 'Murcia', 3, 3, 3, '1007474249', '3130000010', 'lorenamurcia199905@gmail.com', '$2y$10$REW8fNhTrhode9GS3GET1eUKQsRIZMp4FBkX.r/ANmCiyk4xZDy96', 1, '2025-06-20 17:02:48', NULL),
(17, 'Heilyn Stefany', 'Hernández Pérez', 3, 3, 3, '1056773867', '3130000011', 'heihernandez111@gmail.com', '$2y$10$kmLlj1cALv8UeW0w0kSr2OBumwG4F7wLSyPwL3kCXhijFrl4y1IbS', 1, '2025-06-20 17:02:48', NULL),
(24, 'Zharick Valentina', 'Barrera García', 4, 2, 3, '1039680619', '3130000012', 'barreragarciavalentina646@gmail.com', '$2y$10$MdQRzThfemjrG64.ipLEueDHn//Gg6xSO3dRwyy1z4XMUPUiL1UKO', 1, '2025-06-20 17:02:48', NULL),
(25, 'Alisson Yulieth', 'Palacios Arrieta', 4, 2, 3, '1056772579', '3130000013', 'alissonpalaciosa@gmail.com', '$2y$10$LUA7ALzqbVz2kWV5d15bdOJUbhRPTroswEYennoW8vLkSa1wsc7GO', 1, '2025-06-20 17:02:48', NULL),
(26, 'Angoli Angelica', 'Martinez Sandoval', 4, 2, 3, '1056774261', '3130000014', 'Sandovalanyoli44@gmail.com', '$2y$10$iF8xeve1h.JDbbDQ062/AeDfU1SSOfbDgqOZBpU5eSC0HKCD32IpK', 1, '2025-06-20 17:02:48', NULL),
(27, 'Geraldin', 'Ulloa Martinez', 4, 2, 3, '1007568476', '3130000015', 'Alexcrissdbz@gmail.com', '$2y$10$Z6RKpH.95cKzFWwwcOfFbuuzvYiTKZS7OgLNiid1/3oOYtiTwN01q', 1, '2025-06-20 17:02:48', NULL),
(28, 'Laura Dayana', 'Hernández Cuellar', 4, 2, 3, '1056771905', '3130000016', 'lauradayanahernandezcuellar@gmail.com', '$2y$10$H4ENoEZlq4CiD4OB50hMLuvONEOxd6r0O8upOKph0nJwOSSFfZNVe', 1, '2025-06-20 17:02:48', NULL),
(29, 'Lesly Nathalia', 'Suárez Rendón', 4, 2, 3, '1193561026', '3130000017', 'suarezrendonnathalia2807@gmail.com', '$2y$10$TMx6UgTeqyYXhUbr38XgAOCPyDzkYXQ9tn0eHN8B9gBVEQOCQ3sMm', 1, '2025-06-20 17:02:48', NULL),
(30, 'Jeimy Zulay ', 'Hincapié ', 2, 1, 1, '1056771429', '3108685491', 'jeimy@gmail.com', '$2y$10$QPjQSWmjr/Ae9KpqavZ/Oe1ljmlQkPlWBktWdUo67LyugNZ/3dJzi', 1, '2025-07-09 22:52:15', NULL),
(31, 'Yeimy Alejandra ', 'Muñoz Montoya', 1, 1, 1, '1056771196', '3115913689', 'yeimy@gmail.com', '$2y$10$bQJ3DMN6Vq.m98Tp6Bdx2ecmSB0nctQrdHrAF98KKUsCWDD1SM.rG', 1, '2025-07-09 22:53:35', NULL),
(32, 'Nataly', 'Caseres', 5, 2, 1, '00000', '00000', 'nataly@gmail.com', '$2y$10$Uivyca.0TxlmcTxly8qE4e48aAATlIsjtOcSYkBbRydp4q3jsYRMG', 1, '2025-06-20 17:00:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre_Producto` varchar(100) NOT NULL,
  `Categoria_Productos` varchar(100) DEFAULT NULL,
  `Descripcion_Producto` text DEFAULT NULL,
  `ID_TI` int(11) DEFAULT NULL,
  `Monto_Minimo` decimal(12,2) DEFAULT NULL,
  `Monto_Maximo` decimal(12,2) DEFAULT NULL,
  `Plazo_Minimo` int(11) DEFAULT NULL COMMENT 'En meses',
  `Plazo_Maximo` int(11) DEFAULT NULL COMMENT 'En meses',
  `Activo_Producto` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_Producto`, `Nombre_Producto`, `Categoria_Productos`, `Descripcion_Producto`, `ID_TI`, `Monto_Minimo`, `Monto_Maximo`, `Plazo_Minimo`, `Plazo_Maximo`, `Activo_Producto`) VALUES
(1, 'Compra de cartera de crédito hipotecario', 'Compra de cartera', 'Permite trasladar tu crédito de vivienda actual desde otra entidad financiera para mejorar condiciones como tasa de interés, plazo o cuota mensual.', 1, 5000000.00, 500000000.00, 12, 84, 1),
(2, 'Compra de cartera de vehículo', 'Compra de cartera', 'Consiste en trasladar tu crédito vehicular actual a otra entidad que te ofrezca mejores condiciones de pago, tasas más bajas o mayor plazo.', 4, 5000000.00, 500000000.00, 12, 84, 1),
(3, 'Compra de cartera de microcrédito', 'Compra de cartera', 'Traslada microcréditos obtenidos para pequeños negocios a otra entidad que ofrezca mejores tasas o plazos, facilitando el pago.', 8, 5000000.00, 500000000.00, 12, 84, 1),
(4, 'Compra de cartera de crédito de educación', 'Compra de cartera', 'Es un producto financiero que te permite trasladar tu crédito educativo actual desde otra entidad hacia un nuevo banco o cooperativa.', 5, 5000000.00, 500000000.00, 12, 84, 1),
(5, 'Compra de cartera de libranza', 'Compra de cartera', 'Permite unificar y trasladar tus créditos por libranza desde otra entidad para acceder a una mejor tasa o una menor cuota mensual.', 17, 5000000.00, 500000000.00, 12, 84, 1),
(6, 'Compra de cartera de crédito agropecuario', 'Compra de cartera', 'Traslada créditos agropecuarios a otra entidad financiera para acceder a mejores condiciones y facilitar la sostenibilidad del negocio rural.', 10, 5000000.00, 500000000.00, 12, 84, 1),
(7, 'Compra de cartera de crédito comercial (grandes empresas)', 'Compra de cartera', 'Dirigido a grandes empresas que desean trasladar sus obligaciones crediticias a otro banco con mejores condiciones, optimizando su flujo de caja.', 9, 5000000.00, 500000000.00, 12, 84, 1),
(8, 'Compra de cartera de crédito de libre inversión', 'Compra de cartera', 'Permite trasladar un crédito de libre inversión a otra entidad para acceder a menores tasas, cuotas más cómodas o mejores plazos.', 16, 5000000.00, 500000000.00, 12, 84, 1),
(9, 'Compra de cartera de crédito de consumo', 'Compra de cartera', 'Permite consolidar créditos de consumo (como préstamos personales) en una sola entidad, mejorando condiciones y reduciendo el valor de la cuota.', 15, 5000000.00, 500000000.00, 12, 84, 1),
(10, 'Compra de cartera de tarjetas de crédito', 'Compra de cartera', 'Traslada el saldo de tus tarjetas de crédito a un nuevo crédito con menor tasa de interés y un plan de pagos fijo.', 14, 5000000.00, 500000000.00, 12, 84, 1),
(11, 'Compra de cartera de crédito rotativo', 'Compra de cartera', 'Permite unificar saldos de créditos rotativos (líneas de crédito que se renuevan) para pagar en cuotas fijas y con menor interés.', 13, 5000000.00, 500000000.00, 12, 84, 1),
(12, 'Compra de cartera (para personas naturales)', 'Compra de cartera', 'Es la opción para personas que desean unificar diferentes tipos de créditos personales (hipotecario, consumo, libranza, etc.) en una sola entidad con mejores condiciones.', 16, 5000000.00, 500000000.00, 12, 84, 1),
(13, 'Crédito de Vehículo', 'Créditos Vehiculares', 'Un crédito de vehículo es un tipo de préstamo otorgado por una entidad financiera (como un banco, cooperativa o compañía de financiamiento) para que una persona pueda comprar un carro o una moto, ya sea nuevo o usado.', 3, 10000000.00, 300000000.00, 12, 72, 1),
(14, 'Compra de Cartera de Vehículo', 'Créditos_Vehiculares', 'Es un crédito que reemplaza tu deuda actual de vehículo con otra entidad, ofreciéndote mejores condiciones como menor interés o cuota más baja.', 4, 10000000.00, 250000000.00, 12, 60, 1),
(15, 'Crédito educativo a corto plazo', 'Créditos educativos', 'Cubre hasta el 100% de la matrícula en Colombia o el exterior. Puedes reutilizar el cupo a medida que pagas, y financiar desde uno hasta todos los periodos académicos. Se paga en 6 a 12 meses y va desde $700.000 hasta 25 SMLMV. Ideal para formación continua en plazos breves.', 5, 700000.00, 25000000.00, 6, 48, 1),
(16, 'Crédito libre educativo', 'Créditos educativos', 'El Crédito Libre Educativo permite financiar estudios en Colombia con un solo desembolso a la institución. Se paga en 24, 36 o 48 meses y ofrece montos desde $700.000 hasta 25 SMLMV. Es flexible y se adapta a tus necesidades', 6, 700000.00, 25000000.00, 6, 48, 1),
(17, 'Crediestudiantil', 'Créditos educativos', 'Este crédito financia hasta el 100% de la matrícula para estudios técnicos, tecnológicos, de pregrado y posgrado en Colombia. El plazo de pago depende del tipo de programa: 6 meses para pregrados semestrales 12 meses para programas anuales 12 a 36 meses para diplomados o posgrados', 7, 700000.00, 25000000.00, 6, 48, 1),
(19, 'Microcrédito', 'Créditos Productivos o Empresariales', 'Préstamo de bajo monto dirigido a personas o microempresas con acceso a la banca tradicional, ideal para iniciar o fortalecer pequeños negocios.', 8, 1000000.00, 50000000.00, 6, 36, 1),
(20, 'Crédito Comercial', 'Créditos Productivos o Empresariales', 'Financiamiento flexible para emprendedores o microempresarios que buscan invertir en actividades productivas como comercio, servicios o industria.', 9, 1000000.00, 50000000.00, 6, 36, 1),
(21, 'Crédito Agrofácil', 'Créditos Productivos o Empresariales', 'Crédito ofrecido por Bancolombia para productores agropecuarios, con condiciones adaptadas al ciclo productivo y apoyo técnico especializado.', 10, 2000000.00, 200000000.00, 12, 120, 1),
(22, 'Crédito Finagro', 'Créditos Productivos o Empresariales', 'Línea de crédito con recursos de fomento para actividades agropecuarias y rurales, con tasas preferenciales y apoyo del Gobierno.', 11, 2000000.00, 200000000.00, 12, 120, 1),
(23, 'CDT', 'Créditos Productivos o Empresariales', 'Préstamo respaldado por un Certificado de Depósito a Término (CDT), que permite acceder a liquidez sin perder la inversión.', 12, 1000000.00, 50000000.00, 6, 36, 1),
(24, 'Crédito Hipotecario', 'Créditos Hipotecarios y de Vivienda', 'Préstamo otorgado para comprar una vivienda, usando el inmueble como garantía.', 1, 50000000.00, 1000000000.00, 60, 240, 1),
(27, 'Compra de Cartera de Vivienda', 'Créditos Hipotecarios y de Vivienda', 'Similar a la anterior, pero incluye la posibilidad de trasladar contratos de leasing habitacional.', 2, 50000000.00, 800000000.00, 60, 180, 1),
(28, 'Crédito de descuento por nómina o mesada pencional', 'Créditos de libranza', 'Con pagos automáticos descontados del salario o pensión mediante un convenio con su empresa, este método elimina la necesidad de contar con fiadores, simplificando el proceso de aprobación; ofreciendo una tasa fija que permite financiar proyectos personales.', 17, 3000000.00, 100000000.00, 12, 60, 1),
(30, 'Cuentas de Ahorro', 'Cuentas de Ahorros', 'Las cuentas de ahorro son herramientas financieras que permiten guardar dinero de forma segura, fomentando el hábito del ahorro y ayudando a alcanzar metas económicas. Existen diversos tipos según las necesidades del usuario', NULL, NULL, NULL, NULL, NULL, 1),
(31, 'Cuenta de Ahorro Básica o Estándar', 'Cuentas de Ahorros', 'Permite guardar dinero con disponibilidad inmediata y genera intereses bajos. Ideal para uso cotidiano y manejo simple del dinero.', 18, NULL, NULL, NULL, NULL, 1),
(32, 'Cuenta de Ahorro Programado', 'Cuentas de Ahorros', 'Permite ahorrar una cantidad fija regularmente durante un plazo definido, ayudando a alcanzar metas específicas.', 19, NULL, NULL, NULL, NULL, 1),
(33, 'Cuenta de Ahorro para Jóvenes o Niños', 'Cuentas de Ahorros', 'Una cuenta de ahorro para jóvenes o niños es un producto financiero diseñado especialmente para menores de edad, con el objetivo de enseñarles a manejar el dinero y fomentar el hábito del ahorro desde temprana edad.', 20, NULL, NULL, NULL, NULL, 1),
(34, 'Cuenta de Ahorro de Nómina', 'Cuentas de Ahorros', 'Recibe directamente el salario del empleador. Suele tener beneficios como cero cuota de manejo y retiros gratuitos.', 21, NULL, NULL, NULL, NULL, 1),
(35, 'Cuenta de Ahorro para Pensionados', 'Cuentas de Ahorros', 'Especial para recibir mesadas pensionales. Ofrece beneficios como exención del 4x1000 y retiros sin costo.', 22, NULL, NULL, NULL, NULL, 1),
(36, 'Cuenta de Ahorro en Moneda Extranjera', 'Cuentas de Ahorros', 'Permite ahorrar en divisas como dólares o euros. Útil para protegerse de la devaluación o hacer transacciones internacionales.', 23, NULL, NULL, NULL, NULL, 1),
(37, 'Crédito Rotativo', 'Créditos de Consumo y Libre Inversión', 'Disponible para tus compras y avances cuando lo necesites. Flexibilidad en pagos y uso.', 13, 1000000.00, 100000000.00, 6, 60, 1),
(38, 'Tarjeta de crédito', 'Créditos de Consumo y Libre Inversión', 'Usa tu tarjeta de crédito como quieras y disfrútala.', 14, 1000000.00, 100000000.00, 6, 60, 1),
(39, 'Crédito de Consumo', 'Créditos de Consumo y Libre Inversión', 'Financia gastos personales como viajes, educación o tecnología con tasas preferenciales.', 15, 1000000.00, 100000000.00, 6, 60, 1),
(40, 'Crédito Libre Inversión', 'Créditos de Consumo y Libre Inversión', 'Usa el crédito como quieras: remodela, paga deudas o realiza tus planes personales.', 16, 1000000.00, 100000000.00, 6, 60, 1),
(41, 'Crédito De Vivienda', 'Créditos Hipotecarios Y De Vivienda', 'Un Crédito de vivienda es un crédito otorgado por bancos, cooperativas u otras entidades financieras para la adquisición, construcción o mejora de una vivienda.', 1, 50000000.00, 1000000000.00, 60, 240, 1),
(50, 'Producto de Interés - No definido.', 'Producto indefinido.', NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroasesoramiento`
--

CREATE TABLE `registroasesoramiento` (
  `ID_RegistroAsesoramiento` int(11) NOT NULL,
  `ID_Personal` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `ID_Turno` int(11) DEFAULT NULL,
  `Fecha_Hora_Inicio` datetime DEFAULT current_timestamp(),
  `Fecha_Hora_Fin` datetime DEFAULT current_timestamp(),
  `Observaciones` text DEFAULT NULL,
  `Resultado` enum('Aprobado','Denegado','Pendiente') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'Cajero', 4, 'Cajero que procesa pagos', 'Procesar pagos, ver información de créditos'),
(5, 'SUPER ADMIN', 5, 'ACCESO COMPLETO AL SISTEMA', 'FUNCIONALIDADES COMPLETAS DEL SISTEMA');

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
(1, 10.50, 6, 1),
(2, 9.75, 6, 1),
(3, 12.25, 6, 1),
(4, 11.50, 6, 1),
(5, 8.50, 6, 1),
(6, 9.25, 6, 1),
(7, 7.75, 6, 1),
(8, 15.00, 6, 1),
(9, 13.50, 6, 1),
(10, 10.00, 6, 1),
(11, 8.50, 6, 1),
(12, 6.50, 6, 1),
(13, 1.75, 2, 1),
(14, 2.25, 2, 1),
(15, 1.50, 2, 1),
(16, 1.85, 2, 1),
(17, 1.20, 2, 1),
(18, 5.00, 6, 1),
(19, 6.50, 6, 1),
(20, 7.00, 6, 1),
(21, 4.50, 6, 1),
(22, 5.50, 6, 1),
(23, 3.00, 6, 1);

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
  MODIFY `ID_Asesor_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `ID_Bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `credito`
--
ALTER TABLE `credito`
  MODIFY `ID_Credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `cuotacredito`
--
ALTER TABLE `cuotacredito`
  MODIFY `ID_CuotaCredito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `ID_Estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `ID_Genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagocuota`
--
ALTER TABLE `pagocuota`
  MODIFY `ID_PagoCuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `ID_Periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `ID_Personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `registroasesoramiento`
--
ALTER TABLE `registroasesoramiento`
  MODIFY `ID_RegistroAsesoramiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_Rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tasa_interes`
--
ALTER TABLE `tasa_interes`
  MODIFY `ID_TI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `ID_TD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `ID_Turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

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

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`ID_Personal`, `Nombre_Personal`, `Apellido_Personal`, `ID_Rol`, `ID_Genero`, `ID_TD`, `N_Documento_Personal`, `Celular_Personal`, `Correo_Personal`, `Contraseña_Personal`, `Activo_Personal`, `Fecha_Creacion_Personal`, `Foto_Perfil_Personal`) VALUES
(1, 'Gerente', 'Primero', 1, 1, 1, '0000000000', '3130000001', 'jsdmngzc@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:00:44', NULL),
(2, 'SubGerente', 'Segundo', 2, 2, 2, '0000000002', '3130000002', 'SubGerente@gmail.com', '12345', 1, '2025-06-20 17:02:48', NULL),
(3, 'Asesor', 'tercero', 3, 3, 3, '0000000003', '3130000003', 'jose@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-06-20 17:02:48', NULL),
(4, 'Cajero', 'Cuarto', 4, 1, 1, '0000000004', '3130000004', 'Cajero@gmail.com', '12345', 1, '2025-06-20 17:02:48', NULL),
(5, 'ADMIN', 'SUPER', 5, 1, 1, '0000000001', '3206339397', 'admin1$#$@gmail.com', '$2y$10$jqggiIAtb4r0rmIy3hCC1us0PDdkoOdYUqyRiWO9A4ZNXyxb1dvl.', 1, '2025-07-06 13:03:05', 'N/A'),
(19, 'Juan', 'Santos', 5, 1, 1, '00545000000', '3130000001', 'jdsp.1011@gmail.com', '$2y$10$9xnt8x9onA7z.ae62o8HQu5Oh944G0AAOkOrPOARY6lCQCFCv2UhK', 1, '2025-06-20 17:00:44', NULL),
(56, 'Zharick Valentina', 'Barrera García', 4, 2, 1, '1039680619', '3143610286', 'barreragarciavalentina646@gmail.com', '$2y$10$rkKHkUEXmUeH6CAyexrT0O9lvMfM.5BaSMD1eGjVmMQvRU3JRIqvy', 1, '2025-06-20 17:02:48', NULL),
(57, 'Naftali Estefani', 'Moreno Murcia', 3, 2, 1, '1007201990', '3207238688', 'morenonaftali@gmail.com', '$2b$12$OT4gxskOUKYf3yMqnVp6m.OpFXvijZk0yvdPnVTWNhYdSt5B/utT2', 1, '2025-06-20 17:02:48', NULL),
(58, 'Alisson Yulieth', 'Palacios Arrieta', 4, 2, 1, '1056772579', '3216842389', 'alissonpalaciosa@gmail.com', '$2y$10$oxYMXGtxsEU4qV2TwG6STu3twIhkN5qPYfbdH3Qtun0wzSKMZzN6y', 1, '2025-06-20 17:02:48', NULL),
(59, 'Siara Vanesa', 'Madero Ramirez', 3, 2, 1, '1056784532', '3223157570', 'saramadero96@gmail.com', '$2b$12$9y0nSvm6qXgVq76h.wm/De7uA8/.BDyyZSxZqtbu03wVHoamyvisK', 1, '2025-06-20 17:02:48', NULL),
(60, 'Luisa Fernanda', 'Cuesta Rivera', 3, 2, 1, '1056770991', '3143702502', 'luisacuesta180@gmail.com', '$2b$12$owvT2WEdWEasBn4yl9W/9uQZ7EVgmLkAocO2.mmYALNaxTqSai1eu', 1, '2025-06-20 17:02:48', NULL),
(61, 'Nubia Lorena', 'Montoya Palomo', 3, 2, 1, '1056785288', '3105945332', 'montoyapalomo1997@gmail.com', '$2b$12$tKjvxULmvVdAwcjt/cDF/.uu2PtVS8LQuztTtCWUtzLXzCxmy7UXu', 1, '2025-06-20 17:02:48', NULL),
(62, 'Heilyn Stefany', 'Hernández Pérez', 3, 2, 1, '1056773867', '3218796494', 'heihernandez111@gmail.com', '$2b$12$g17nRKC3qS4d41BtpgBlduRE1QINn2hw3WeG0tenrgmmhS2xtemdG', 1, '2025-06-20 17:02:48', NULL),
(63, 'Dina Mayerli', 'Muñoz Erazo', 3, 2, 1, '1118473918', '3173792234', 'izajhoss@gmail.com', '$2b$12$qovAPfsjRb6NLc0vTavSveYK9cL6TGZY84qzHrD1ju05jKYeaBR.y', 1, '2025-06-20 17:02:48', NULL),
(64, 'Lilian Yohana', 'Ocampo Henao', 3, 2, 1, '1056777856', '3134729782', 'yoanaocampohenao@gmail.com', '$2b$12$SrigfQ8Rl/Vy7qAHA6c87OdqdmYspxUxP/8QsdOPW0DwO6zLPNq9C', 1, '2025-06-20 17:02:48', NULL),
(65, 'Lesly Nathalia', 'Suárez Rendón', 4, 2, 1, '1193561026', '3146940364', 'suarezrendonnathalia2807@gmail.com', '$2y$10$RzeLnsLgHd6vAO5QoazpiuOfVuPbi4lmMFG6D3t5DWkD8BYfdIgKq', 1, '2025-06-20 17:02:48', NULL),
(66, 'Ana Yulieth', 'Palacio Cuellar', 3, 2, 1, '1099262144', '3207132939', 'Anayuliethpalacio04@gmail.com', '$2b$12$zkEFusdEqpT1ALwOLIXqr.XaZrQW6sAom3a4OpWGeBNdSWpgkfuym', 1, '2025-06-20 17:02:48', NULL),
(67, 'Angie Lorena', 'Murcia', 3, 2, 1, '1007474249', '3127132616', 'lorenamurcia199905@gmail.com', '$2b$12$UZlp83s.6WlBmNm3bd7FXOvmHt6BiKBBGpLOu6t7TIuhAaWiwThSO', 1, '2025-06-20 17:02:48', NULL),
(68, 'Angela Maria', 'Valencia Tabarez', 3, 2, 1, '1036220463', '3112705628', 'Angelavalenciatabarez09@gmail.com', '$2b$12$mEKEOWj.eDJFHC4z2j5V/OBjX/IIlEQ/20b6Q9rJG1AUIJRbqOI2u', 1, '2025-06-20 17:02:48', NULL),
(69, 'Miguel Ángel', 'Hernández Cárdenas', 3, 1, 1, '1073680139', '3017743293', 'hernandezcardenas288@gmail.com', '$2b$12$l1vmEuAWcmzuKW4WJWmL.OZ770kvHsxn.YF5QHVUKhCKupgnqV/Ei', 1, '2025-06-20 17:02:48', NULL),
(70, 'Laura Dayana', 'Hernández Cuellar', 4, 2, 1, '1056771905', '3114895895', 'lauradayanahernandezcuellar@gmail.com', '$2y$10$MhiC2aMQAJIl5tShY23Q/ON1CdZbZLsc74vYfbB7BqBNu8XW5crlq', 1, '2025-06-20 17:02:48', NULL),
(71, 'Nicoll Daniela', 'Flores Pacheco', 3, 2, 1, '1056770987', '3112754273', 'florezdaniela313@gmail.com', '$2b$12$8YVwt2sD4jEGRXnYUcvpAuCPu35Gg9PLV5hWp0gLMuo2SlbTkPfgS', 1, '2025-06-20 17:02:48', NULL),
(72, 'Geraldin', 'Ulloa Martinez', 4, 2, 1, '1007568476', '3127552509', 'Alexcrissdbz@gmail.com', '$2y$10$ASyYo4W1m99M7YbXp34GFOkV7n1ZbJZqGrj65gEfnrQw/7NfjRLje', 1, '2025-06-20 17:02:48', NULL),
(73, 'Angoli Angelica', 'Martinez Sandoval', 4, 2, 1, '1056774261', '3025113277', 'Sandovalanyoli44@gmail.com', '$2y$10$8OhNUmQj6Lra9CefCZ9kXOlb9U6R4.nACWAnbh1IRKU/pdALRTlLi', 1, '2025-06-20 17:02:48', NULL);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `ID_Personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `fk_personal_genero` FOREIGN KEY (`ID_Genero`) REFERENCES `genero` (`ID_Genero`),
  ADD CONSTRAINT `fk_personal_rol` FOREIGN KEY (`ID_Rol`) REFERENCES `rol` (`ID_Rol`),
  ADD CONSTRAINT `fk_personal_tipo_documento` FOREIGN KEY (`ID_TD`) REFERENCES `tipo_documento` (`ID_TD`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
