

CREATE TABLE `RegistroAsesoramiento` (
    `ID_RegistroAsesoramiento` INT(11) NOT NULL AUTO_INCREMENT, -- Clave primaria para cada registro de asesoramiento
    `ID_Personal` INT(11) NOT NULL, -- Clave foránea que referencia al asesor (empleado) que realizó el asesoramiento
    `ID_Cliente` INT(11) NOT NULL, -- Clave foránea que referencia al cliente atendido
    `ID_Turno` INT(11) DEFAULT NULL, -- Clave foránea que referencia el turno (si aplica, puede ser NULL)
    `Fecha_Hora_Inicio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), -- Fecha y hora de inicio del asesoramiento
    `Fecha_Hora_Fin` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), -- Fecha y hora de fin del asesoramiento
    `Observaciones` TEXT DEFAULT NULL, -- Observaciones o descripción del asesoramiento
    `Resultado` ENUM('Aprobado','Denegado','Pendiente') NOT NULL DEFAULT 'Pendiente', -- Resultado del asesoramiento
    PRIMARY KEY (`ID_RegistroAsesoramiento`),
    -- AÑADIR CLAVES FORÁNEAS (dependiendo de tus otras tablas)
    -- CONSTRAINT `fk_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `Personal` (`ID_Personal`),
    -- CONSTRAINT `fk_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `Cliente` (`ID_Cliente`),
    -- CONSTRAINT `fk_turno` FOREIGN KEY (`ID_Turno`) REFERENCES `Turno` (`ID_Turno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `asesor_producto` (
    `ID_Asesor_Producto` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_Personal` INT(11) NOT NULL, -- Ahora referencia a `personal`.ID_Personal
    `ID_Producto` INT(11) NOT NULL,
    `Descripcion_AP` TEXT DEFAULT NULL,
    `Fecha_Asignacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `Estado_AsesorProducto` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo', -- Cambiado a ENUM para mayor claridad
    PRIMARY KEY (`ID_Asesor_Producto`),
    CONSTRAINT `fk_ap_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
    CONSTRAINT `fk_ap_producto` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bitacora` (
    `ID_Bitacora` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_Cliente` INT(11) DEFAULT NULL, -- Agregado para conocer el cliente atendido (puede ser NULL si el evento no está ligado a un cliente específico)
    `ID_Personal` INT(11) DEFAULT NULL, -- Agregado para saber qué personal realizó la acción (puede ser NULL)
    `ID_RegistroAsesoramiento` INT(11) DEFAULT NULL, -- Referencia al registro de asesoramiento (puede ser NULL)
    `Tipo_Evento` VARCHAR(50) NOT NULL, -- Por ejemplo: 'Asesoramiento', 'Pago', 'Creacion Cliente', 'Login', etc.
    `Descripcion_Evento` TEXT DEFAULT NULL, -- Detalles del evento (renombrado de Detalles_Bitacora)
    `Fecha_Hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`ID_Bitacora`),
    CONSTRAINT `fk_bitacora_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
    CONSTRAINT `fk_bitacora_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
    CONSTRAINT `fk_bitacora_registro_asesoramiento` FOREIGN KEY (`ID_RegistroAsesoramiento`) REFERENCES `RegistroAsesoramiento` (`ID_RegistroAsesoramiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cliente` (
    `ID_Cliente` INT(11) NOT NULL AUTO_INCREMENT,
    `Nombre_Cliente` VARCHAR(50) NOT NULL,
    `Apellido_Cliente` VARCHAR(50) NOT NULL,
    `ID_Genero` INT(11) NOT NULL,
    `ID_TD` INT(11) NOT NULL, -- ID Tipo Documento
    `N_Documento_Cliente` VARCHAR(45) NOT NULL UNIQUE, -- Se añade UNIQUE para asegurar documentos únicos
    `Celular_Cliente` VARCHAR(45) NOT NULL,
    `Correo_Cliente` VARCHAR(45) NOT NULL,
    `Direccion_Cliente` VARCHAR(100) NOT NULL,
    `Ciudad_Cliente` VARCHAR(50) NOT NULL,
    `Fecha_Nacimiento_Cliente` DATE NOT NULL,
    `ID_Personal_Creador` INT(11) DEFAULT NULL, -- Renombrado de ID_Asesor_Creador a ID_Personal_Creador
    `Fecha_Creacion_Cliente` DATETIME DEFAULT CURRENT_TIMESTAMP(),
    `Estado_Cliente` TINYINT(1) NOT NULL DEFAULT 1, -- 1 para activo, 0 para inactivo
    `Contraseña` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`ID_Cliente`),
    CONSTRAINT `fk_cliente_genero` FOREIGN KEY (`ID_Genero`) REFERENCES `genero` (`ID_Genero`),
    CONSTRAINT `fk_cliente_tipo_documento` FOREIGN KEY (`ID_TD`) REFERENCES `tipo_documento` (`ID_TD`),
    CONSTRAINT `fk_cliente_personal_creador` FOREIGN KEY (`ID_Personal_Creador`) REFERENCES `personal` (`ID_Personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `credito` (
    `ID_Credito` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_Cliente` INT(11) NOT NULL, -- Clave foránea que relaciona el crédito con el cliente
    `Monto_Total_Credito` DECIMAL(15,2) NOT NULL,
    `Monto_Pendiente_Credito` DECIMAL(15,2) NOT NULL,
    `Fecha_Apertura_Credito` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `Fecha_Vencimiento_Credito` DATE NOT NULL,
    `ID_Producto` INT(11) NOT NULL DEFAULT 4, -- Tipo de producto de crédito (ej. préstamo personal, hipoteca)
    `ID_Estado` INT(11) NOT NULL, -- Estado del crédito (ej. Activo, Pagado, En Mora)
    PRIMARY KEY (`ID_Credito`),
    CONSTRAINT `fk_credito_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
    CONSTRAINT `fk_credito_producto` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`),
    CONSTRAINT `fk_credito_estado` FOREIGN KEY (`ID_Estado`) REFERENCES `estado` (`ID_Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `CuotaCredito` (
    `ID_CuotaCredito` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_Credito` INT(11) NOT NULL, -- Clave foránea al crédito al que pertenece esta cuota
    `Numero_Cuota` INT(11) NOT NULL, -- Número de la cuota (ej. 1, 2, 3...)
    `Monto_Capital` DECIMAL(15,2) NOT NULL, -- Monto de capital de la cuota
    `Monto_Interes` DECIMAL(15,2) NOT NULL, -- Monto de interés de la cuota
    `Monto_Total_Cuota` DECIMAL(15,2) NOT NULL, -- Suma de capital e interés
    `Fecha_Vencimiento` DATE NOT NULL,
    `Fecha_Pago` DATETIME DEFAULT NULL, -- Fecha en que la cuota fue pagada (puede ser NULL si no pagada)
    `Monto_Pagado` DECIMAL(15,2) DEFAULT 0.00, -- Monto real pagado por esta cuota
    `Dias_Mora_Al_Pagar` INT(11) DEFAULT 0, -- Días de mora al momento del pago (calculado)
    `Monto_Recargo_Mora` DECIMAL(15,2) DEFAULT 0.00, -- Recargo aplicado por mora (si aplica)
    `ID_Estado_Cuota` INT(11) NOT NULL, -- Estado de la cuota (ej. Pendiente, Pagada, Vencida)
    PRIMARY KEY (`ID_CuotaCredito`),
    CONSTRAINT `fk_cuota_credito` FOREIGN KEY (`ID_Credito`) REFERENCES `credito` (`ID_Credito`),
    CONSTRAINT `fk_cuota_estado` FOREIGN KEY (`ID_Estado_Cuota`) REFERENCES `estado` (`ID_Estado`),
    UNIQUE (`ID_Credito`, `Numero_Cuota`) -- Asegura que cada cuota de un crédito tenga un número único
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `PagoCuota` (
    `ID_PagoCuota` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_CuotaCredito` INT(11) NOT NULL, -- Referencia a la cuota específica que se está pagando
    `ID_Personal` INT(11) DEFAULT NULL, -- Referencia al personal (cajero) que procesó el pago
    `Fecha_Hora_Pago` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `Monto_Pagado_Transaccion` DECIMAL(15,2) NOT NULL, -- Monto pagado en esta transacción específica
    `ID_Estado_Pago` INT(11) NOT NULL, -- Estado del pago (ej. Completado, Revertido)
    `Observaciones_Pago` TEXT DEFAULT NULL,
    PRIMARY KEY (`ID_PagoCuota`),
    CONSTRAINT `fk_pago_cuota_credito` FOREIGN KEY (`ID_CuotaCredito`) REFERENCES `CuotaCredito` (`ID_CuotaCredito`),
    CONSTRAINT `fk_pago_personal` FOREIGN KEY (`ID_Personal`) REFERENCES `personal` (`ID_Personal`),
    CONSTRAINT `fk_pago_estado` FOREIGN KEY (`ID_Estado_Pago`) REFERENCES `estado` (`ID_Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `turno` (
    `ID_Turno` INT(11) NOT NULL AUTO_INCREMENT,
    `ID_Cliente` INT(11) DEFAULT NULL, -- Puede ser NULL si el cliente aún no existe
    `Nombre_Completo_Solicitante` VARCHAR(100) DEFAULT NULL, -- Nombre completo del solicitante (temporal si no es cliente)
    `N_Documento_Solicitante` VARCHAR(45) DEFAULT NULL, -- Número de documento del solicitante (temporal)
    `Numero_Turno` VARCHAR(20) NOT NULL,
    `ID_Producto_Interes` INT(11) DEFAULT NULL, -- Producto o tipo de servicio de interés
    `Fecha_Hora_Solicitud` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `Fecha_Hora_Finalizacion` DATETIME DEFAULT NULL,
    `ID_Estado_Turno` INT(11) NOT NULL DEFAULT 1, -- Estado del turno (ej. En Espera, Atendido, Cancelado)
    `Tiempo_Espera_Minutos` INT(11) DEFAULT NULL, -- Tiempo de espera en minutos
    `Motivo_Turno` TEXT DEFAULT NULL, -- Motivo de la solicitud del turno
    PRIMARY KEY (`ID_Turno`),
    CONSTRAINT `fk_turno_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
    CONSTRAINT `fk_turno_producto` FOREIGN KEY (`ID_Producto_Interes`) REFERENCES `producto` (`ID_Producto`),
    CONSTRAINT `fk_turno_estado` FOREIGN KEY (`ID_Estado_Turno`) REFERENCES `estado` (`ID_Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tablas Auxiliares

CREATE TABLE `estado` (
    `ID_Estado` INT(11) NOT NULL AUTO_INCREMENT,
    `Estado` VARCHAR(45) NOT NULL,
    `Descripcion` TEXT DEFAULT NULL,
    `Tipo_Estado` ENUM('Turno','Credito','Pago','Cuota','General','Personal','Producto') NOT NULL, -- Se amplía el ENUM
    PRIMARY KEY (`ID_Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `genero` (
    `ID_Genero` INT(11) NOT NULL AUTO_INCREMENT,
    `Genero` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`ID_Genero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `periodo` (
    `ID_Periodo` INT(11) NOT NULL AUTO_INCREMENT,
    `Periodo` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`ID_Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `personal` (
    `ID_Personal` INT(11) NOT NULL AUTO_INCREMENT,
    `Nombre_Personal` VARCHAR(50) NOT NULL,
    `Apellido_Personal` VARCHAR(50) NOT NULL,
    `ID_Rol` INT(11) NOT NULL, -- Rol del personal (ej. Asesor, Cajero, Administrador)
    `ID_Genero` INT(11) NOT NULL,
    `ID_TD` INT(11) NOT NULL, -- ID Tipo Documento
    `N_Documento_Personal` VARCHAR(45) NOT NULL UNIQUE, -- Se añade UNIQUE
    `Celular_Personal` VARCHAR(45) NOT NULL,
    `Correo_Personal` VARCHAR(45) NOT NULL UNIQUE, -- Se añade UNIQUE
    `Contraseña_Personal` VARCHAR(255) NOT NULL,
    `Activo_Personal` TINYINT(1) NOT NULL DEFAULT 1,
    `Fecha_Creacion_Personal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `Foto_Perfil_Personal` VARCHAR(300) DEFAULT NULL,
    PRIMARY KEY (`ID_Personal`),
    CONSTRAINT `fk_personal_rol` FOREIGN KEY (`ID_Rol`) REFERENCES `rol` (`ID_Rol`),
    CONSTRAINT `fk_personal_genero` FOREIGN KEY (`ID_Genero`) REFERENCES `genero` (`ID_Genero`),
    CONSTRAINT `fk_personal_tipo_documento` FOREIGN KEY (`ID_TD`) REFERENCES `tipo_documento` (`ID_TD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `producto` (
    `ID_Producto` INT(11) NOT NULL AUTO_INCREMENT,
    `Nombre_Producto` VARCHAR(100) NOT NULL,
    `Descripcion_Producto` TEXT DEFAULT NULL,
    `ID_TI` INT(11) NOT NULL, -- ID Tasa de Interés
    `Monto_Minimo` DECIMAL(12,2) NOT NULL,
    `Monto_Maximo` DECIMAL(12,2) NOT NULL,
    `Plazo_Minimo` INT(11) NOT NULL COMMENT 'En meses',
    `Plazo_Maximo` INT(11) NOT NULL COMMENT 'En meses',
    `Activo_Producto` TINYINT(1) NOT NULL DEFAULT 1, -- Se añade DEFAULT 1
    PRIMARY KEY (`ID_Producto`),
    CONSTRAINT `fk_producto_tasa_interes` FOREIGN KEY (`ID_TI`) REFERENCES `tasa_interes` (`ID_TI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rol` (
    `ID_Rol` INT(11) NOT NULL AUTO_INCREMENT,
    `Rol` VARCHAR(50) NOT NULL,
    `Nivel_Acceso` INT(11) NOT NULL, -- Ejmp. 1. Administrador, 2. Cajero, 3. Asesor, 4. Cliente
    `Descripcion_Rol` TEXT NOT NULL,
    `Permisos` TEXT DEFAULT NULL,
    PRIMARY KEY (`ID_Rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tasa_interes` (
    `ID_TI` INT(11) NOT NULL AUTO_INCREMENT,
    `Tasa_Interes` DECIMAL(5,2) NOT NULL,
    `ID_Periodo` INT(11) NOT NULL,
    `Activo_TI` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`ID_TI`),
    CONSTRAINT `fk_tasa_interes_periodo` FOREIGN KEY (`ID_Periodo`) REFERENCES `periodo` (`ID_Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipo_documento` (
    `ID_TD` INT(11) NOT NULL AUTO_INCREMENT,
    `Tipo_Documento` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`ID_TD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;