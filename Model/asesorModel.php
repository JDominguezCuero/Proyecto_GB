<?php
// usuarios/model.php
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';

/**
 * Registra nuevo personal.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosPersonal Array asociativo con los datos del personal.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarPersonal(PDO $conexion, array $datosPersonal): bool {
    $sql = "INSERT INTO personal (Nombre_Personal, Apellido_Personal, ID_Rol, ID_Genero, ID_TD, N_Documento_Personal, Celular_Personal, Correo_Personal, Contraseña_Personal, Activo_Personal, Foto_Perfil_Personal)
            VALUES (:nombre, :apellido, :idRol, :idGenero, :idTd, :nDocumento, :celular, :correo, :contrasena, :activo, :fotoPerfil)";
    $stmt = $conexion->prepare($sql);

    // Hashear la contraseña antes de guardar
    $datosPersonal['Contraseña_Personal'] = password_hash($datosPersonal['Contraseña_Personal'], PASSWORD_DEFAULT);

    return $stmt->execute([
        ':nombre' => $datosPersonal['Nombre_Personal'],
        ':apellido' => $datosPersonal['Apellido_Personal'],
        ':idRol' => $datosPersonal['ID_Rol'],
        ':idGenero' => $datosPersonal['ID_Genero'],
        ':idTd' => $datosPersonal['ID_TD'],
        ':nDocumento' => $datosPersonal['N_Documento_Personal'],
        ':celular' => $datosPersonal['Celular_Personal'],
        ':correo' => $datosPersonal['Correo_Personal'],
        ':contrasena' => $datosPersonal['Contraseña_Personal'],
        ':activo' => $datosPersonal['Activo_Personal'] ?? 1, // Valor por defecto si no se proporciona
        ':fotoPerfil' => $datosPersonal['Foto_Perfil_Personal'] ?? null
    ]);
}

/**
* Registra un nuevo turno con datos básicos.
* Genera el Numero_Turno y establece el estado inicial 'En Espera'.
* @param PDO $conexion Objeto de conexión PDO.
* @param string $nombreCompleto Nombre completo del solicitante.
* @param string $nDocumento Número de documento del solicitante.
* @return bool True si se registra correctamente, false en caso contrario.
*/
function registrarTurno(PDO $conexion, string $nombreCompleto, string $nDocumento, ?int $productoInteresId = null, int $idEstadoTurno = 1): ?array {
    $fechaSolicitud = date('Y-m-d H:i:s');

    $sql = "INSERT INTO turno (Nombre_Completo_Solicitante, N_Documento_Solicitante, Fecha_Hora_Solicitud, ID_Estado_Turno, ID_Producto_Interes)
            VALUES (:nombreCompleto, :nDocumento, :fechaSolicitud, :idEstadoTurno, :productoInteresId)";
    
    $stmt = $conexion->prepare($sql);
    
    try {
        $stmt->execute([
            ':nombreCompleto' => $nombreCompleto,
            ':nDocumento' => $nDocumento,
            ':fechaSolicitud' => $fechaSolicitud,
            ':idEstadoTurno' => $idEstadoTurno,
            ':productoInteresId' => $productoInteresId // Nuevo campo
        ]);

        // Recuperar el ID del turno recién insertado
        $lastInsertId = $conexion->lastInsertId();

        // Ahora, recuperamos todos los datos del registro recién insertado
        // Esto es crucial para obtener el ID real y cualquier campo con valor por defecto o autogenerado.
        $sqlSelect = "SELECT ID_Turno, Nombre_Completo_Solicitante, N_Documento_Solicitante, Numero_Turno, Fecha_Hora_Solicitud, ID_Estado_Turno, ID_Producto_Interes 
                      FROM turno 
                      WHERE ID_Turno = :id";
        $stmtSelect = $conexion->prepare($sqlSelect);
        $stmtSelect->execute([':id' => $lastInsertId]);
        
        $registroTurno = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($registroTurno && empty($registroTurno['Numero_Turno'])) {
            $numeroTurnoGenerado = "T" . str_pad($registroTurno['ID_Turno'], 3, '0', STR_PAD_LEFT);
            $sqlUpdateNumeroTurno = "UPDATE turno SET Numero_Turno = :numeroTurno WHERE ID_Turno = :id";
            $stmtUpdate = $conexion->prepare($sqlUpdateNumeroTurno);
            $stmtUpdate->execute([
                ':numeroTurno' => $numeroTurnoGenerado,
                ':id' => $registroTurno['ID_Turno']
            ]);
            $registroTurno['Numero_Turno'] = $numeroTurnoGenerado;
        }

        return $registroTurno; // Retorna el array con los datos del turno completo
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error al registrar turno: " . $e->getMessage());
        return null; // Devuelve null si hay un error
    }
}

/**
 * Obtiene un producto por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idProducto ID del producto.
 * @return array|false Array asociativo del producto o false si no se encuentra.
 */
function obtenerProductoPorId(PDO $conexion, int $idProducto) {
    $sql = "SELECT p.*, ti.Tasa_Interes, per.Periodo
            FROM producto p
            JOIN tasa_interes ti ON p.ID_TI = ti.ID_TI
            JOIN periodo per ON ti.ID_Periodo = per.ID_Periodo
            WHERE p.ID_Producto = :idProducto";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los turnos, con filtros opcionales.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $filtros Array asociativo de filtros (ej. 'ID_Cliente', 'ID_Estado_Turno', 'ID_Turno').
 * @return array Array asociativo de turnos.
 */
function obtenerTurnos(PDO $conexion, array $filtros = []): array {
    $sql = "SELECT t.*, c.Nombre_Cliente, c.Apellido_Cliente, p.Nombre_Producto, p.Descripcion_Producto, t.ID_Estado_Turno AS Estado_Turno
            FROM turno t
            LEFT JOIN cliente c ON t.ID_Cliente = c.ID_Cliente
            LEFT JOIN producto p ON t.ID_Producto_Interes = p.ID_Producto
            JOIN estado e ON t.ID_Estado_Turno = e.ID_Estado
            WHERE e.Tipo_Estado = 'Turno'"; // Asegura que solo se traigan turnos y no otros tipos de estado.

    $condiciones = [];
    $valores = [];

    // Filtro por ID de Cliente
    if (isset($filtros['ID_Cliente'])) {
        $condiciones[] = "t.ID_Cliente = :idCliente";
        $valores[':idCliente'] = $filtros['ID_Cliente'];
    }
    // Filtro por ID de Estado de Turno
    if (isset($filtros['ID_Estado_Turno'])) {
        $condiciones[] = "t.ID_Estado_Turno = :idEstadoTurno";
        $valores[':idEstadoTurno'] = $filtros['ID_Estado_Turno'];
    }
    // Filtro por Fecha de Inicio de Solicitud
    if (isset($filtros['Fecha_Inicio_Solicitud'])) {
        $condiciones[] = "t.Fecha_Hora_Solicitud >= :fechaInicio";
        $valores[':fechaInicio'] = $filtros['Fecha_Inicio_Solicitud'];
    }
    // Filtro por Fecha de Fin de Solicitud
    if (isset($filtros['Fecha_Fin_Solicitud'])) {
        $condiciones[] = "t.Fecha_Hora_Solicitud <= :fechaFin";
        $valores[':fechaFin'] = $filtros['Fecha_Fin_Solicitud'];
    }
    // Nuevo filtro por ID de Turno
    if (isset($filtros['ID_Turno'])) {
        $condiciones[] = "t.ID_Turno = :idTurno";
        $valores[':idTurno'] = $filtros['ID_Turno'];
    }

    if (!empty($condiciones)) {
        $sql .= " AND " . implode(' AND ', $condiciones);
    }
    $sql .= " ORDER BY t.Fecha_Hora_Solicitud ASC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($valores);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Actualiza un registro de turno existente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idTurno ID del turno a actualizar.
 * @param array $datosTurno Array asociativo con los datos a actualizar.
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarTurno(PDO $conexion, int $idTurno, array $datosTurno): bool {
    $campos = [];
    $valores = [':idTurno' => $idTurno];

    if (isset($datosTurno['ID_Cliente'])) { $campos[] = 'ID_Cliente = :idCliente'; $valores[':idCliente'] = $datosTurno['ID_Cliente']; }
    if (isset($datosTurno['Nombre_Completo_Solicitante'])) { $campos[] = 'Nombre_Completo_Solicitante = :nombreCompleto'; $valores[':nombreCompleto'] = $datosTurno['Nombre_Completo_Solicitante']; }
    if (isset($datosTurno['N_Documento_Solicitante'])) { $campos[] = 'N_Documento_Solicitante = :nDocumento'; $valores[':nDocumento'] = $datosTurno['N_Documento_Solicitante']; }
    if (isset($datosTurno['Numero_Turno'])) { $campos[] = 'Numero_Turno = :numeroTurno'; $valores[':numeroTurno'] = $datosTurno['Numero_Turno']; }
    if (isset($datosTurno['ID_Producto_Interes'])) { $campos[] = 'ID_Producto_Interes = :idProductoInteres'; $valores[':idProductoInteres'] = $datosTurno['ID_Producto_Interes']; }
    if (isset($datosTurno['Fecha_Hora_Finalizacion'])) { $campos[] = 'Fecha_Hora_Finalizacion = :fechaFinalizacion'; $valores[':fechaFinalizacion'] = $datosTurno['Fecha_Hora_Finalizacion']; }
    if (isset($datosTurno['ID_Estado_Turno'])) { $campos[] = 'ID_Estado_Turno = :idEstadoTurno'; $valores[':idEstadoTurno'] = $datosTurno['ID_Estado_Turno']; }
    if (isset($datosTurno['Tiempo_Espera_Minutos'])) { $campos[] = 'Tiempo_Espera_Minutos = :tiempoEspera'; $valores[':tiempoEspera'] = $datosTurno['Tiempo_Espera_Minutos']; }
    if (isset($datosTurno['Motivo_Turno'])) { $campos[] = 'Motivo_Turno = :motivoTurno'; $valores[':motivoTurno'] = $datosTurno['Motivo_Turno']; }

    if (empty($campos)) {
        return false;
    }

    $sql = "UPDATE turno SET " . implode(', ', $campos) . " WHERE ID_Turno = :idTurno";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($valores);
}

/**
 * Registra un evento en la bitácora.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosBitacora Array asociativo con los datos del evento.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarEventoBitacora(PDO $conexion, array $datosBitacora): bool {
    $sql = "INSERT INTO bitacora (ID_Cliente, ID_Personal, ID_RegistroAsesoramiento, Tipo_Evento, Descripcion_Evento)
            VALUES (:idCliente, :idPersonal, :idRegistroAsesoramiento, :tipoEvento, :descripcionEvento)";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        ':idCliente' => $datosBitacora['ID_Cliente'] ?? null,
        ':idPersonal' => $datosBitacora['ID_Personal'] ?? null,
        ':idRegistroAsesoramiento' => $datosBitacora['ID_RegistroAsesoramiento'] ?? null,
        ':tipoEvento' => $datosBitacora['Tipo_Evento'],
        ':descripcionEvento' => $datosBitacora['Descripcion_Evento'] ?? null
    ]);
}

/**
 * Registra un nuevo asesoramiento.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosAsesoramiento Array asociativo con los datos del asesoramiento.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarAsesoramiento(PDO $conexion, array $datosAsesoramiento): ?int {
    $sql = "INSERT INTO RegistroAsesoramiento (ID_Personal, ID_Cliente, ID_Turno, Fecha_Hora_Inicio, Fecha_Hora_Fin, Observaciones, Resultado)
            VALUES (:idPersonal, :idCliente, :idTurno, :fechaInicio, :fechaFin, :observaciones, :resultado)";
    
    $stmt = $conexion->prepare($sql);
    $resultado = $stmt->execute([
        ':idPersonal' => $datosAsesoramiento['ID_Personal'],
        ':idCliente' => $datosAsesoramiento['ID_Cliente'],
        ':idTurno' => $datosAsesoramiento['ID_Turno'] ?? null,
        ':fechaInicio' => $datosAsesoramiento['Fecha_Hora_Inicio'],
        ':fechaFin' => $datosAsesoramiento['Fecha_Hora_Fin'],
        ':observaciones' => $datosAsesoramiento['Observaciones'] ?? null,
        ':resultado' => $datosAsesoramiento['Resultado'] ?? 'Pendiente'
    ]);

    if ($resultado) {
        return (int)$conexion->lastInsertId();
    }

    return null;
}

/**
 * Obtiene un registro de cliente por su número de documento.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param string $nDocumento Número de documento del cliente.
 * @return array|false Array asociativo del cliente o false si no se encuentra.
 */
function obtenerClientePorDocumento(PDO $conexion, string $nDocumento) {
    $sql = "SELECT c.*, g.Genero AS Nombre_Genero, td.Tipo_Documento AS Nombre_Tipo_Documento
            FROM cliente c
            LEFT JOIN genero g ON c.ID_Genero = g.ID_Genero
            LEFT JOIN tipo_documento td ON c.ID_TD = td.ID_TD
            WHERE c.N_Documento_Cliente = :nDocumento";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nDocumento', $nDocumento, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerCreditoActivoPorCliente(PDO $conexion, int $idCliente) {
    $sql = "SELECT c.*, p.Nombre_Producto AS NombreProducto, e.Estado AS Estado_Credito
            FROM Credito c
            JOIN Producto p ON c.ID_Producto = p.ID_Producto
            JOIN estado e ON c.ID_Estado = e.ID_Estado
            WHERE c.ID_Cliente = :idCliente AND c.ID_Estado = 4 LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/**
 * Obtiene todas las cuotas de un crédito específico.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCredito ID del crédito.
 * @return array Array asociativo de cuotas.
 */
function obtenerCuotasPorCredito(PDO $conexion, int $idCredito): array {
    $sql = "SELECT cc.*, e.Estado AS Estado_Cuota
            FROM CuotaCredito cc
            JOIN estado e ON cc.ID_Estado_Cuota = e.ID_Estado
            WHERE cc.ID_Credito = :idCredito AND e.Tipo_Estado = 'Credito'
            ORDER BY cc.Numero_Cuota ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCredito', $idCredito, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene una cuota específica por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCuotaCredito ID de la cuota.
 * @return array|false Array asociativo de la cuota o false si no se encuentra.
 */
function obtenerCuotaPorId(PDO $conexion, int $idCuotaCredito) {
    $sql = "SELECT cc.*, e.Estado AS Estado_Cuota
            FROM CuotaCredito cc
            JOIN estado e ON cc.ID_Estado_Cuota = e.ID_Estado
            WHERE cc.ID_CuotaCredito = :idCuotaCredito AND e.Tipo_Estado = 'Credito'";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCuotaCredito', $idCuotaCredito, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Actualiza el estado de una cuota (ej. cuando es pagada).
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCuotaCredito ID de la cuota a actualizar.
 * @param array $datosCuota Array asociativo con los datos a actualizar (ej. Fecha_Pago, Monto_Pagado, Dias_Mora_Al_Pagar, Monto_Recargo_Mora, ID_Estado_Cuota).
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarCuotaCredito(PDO $conexion, int $idCuotaCredito, array $datosCuota): bool {
    $campos = [];
    
    $valores = [':idCuotaCredito' => $idCuotaCredito];

    if (isset($datosCuota['Fecha_Pago'])) { $campos[] = 'Fecha_Pago = :fechaPago'; $valores[':fechaPago'] = $datosCuota['Fecha_Pago']; }
    if (isset($datosCuota['Monto_Pagado'])) { $campos[] = 'Monto_Pagado = :montoPagado'; $valores[':montoPagado'] = $datosCuota['Monto_Pagado']; }
    if (isset($datosCuota['Dias_Mora_Al_Pagar'])) { $campos[] = 'Dias_Mora_Al_Pagar = :diasMora'; $valores[':diasMora'] = $datosCuota['Dias_Mora_Al_Pagar']; }
    if (isset($datosCuota['Monto_Recargo_Mora'])) { $campos[] = 'Monto_Recargo_Mora = :montoRecargo'; $valores[':montoRecargo'] = $datosCuota['Monto_Recargo_Mora']; }
    if (isset($datosCuota['ID_Estado_Cuota'])) { $campos[] = 'ID_Estado_Cuota = :idEstadoCuota'; $valores[':idEstadoCuota'] = $datosCuota['ID_Estado_Cuota']; }

    if (empty($campos)) {
        return false;
    }

    $sql = "UPDATE CuotaCredito SET " . implode(', ', $campos) . " WHERE ID_CuotaCredito = :idCuotaCredito";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($valores);
}

/**
 * Registra un nuevo pago de cuota.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosPago Array asociativo con los datos del pago.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarPagoCuota(PDO $conexion, array $datosPago): bool {
    $sql = "INSERT INTO PagoCuota (ID_CuotaCredito, ID_Personal, Fecha_Hora_Pago, Monto_Pagado_Transaccion, ID_Estado_Pago, Observaciones_Pago)
            VALUES (:idCuotaCredito, :idPersonal, NOW(), :montoPagadoTransaccion, :idEstadoPago, :observacionesPago)";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        ':idCuotaCredito' => $datosPago['ID_CuotaCredito'],
        ':idPersonal' => $datosPago['ID_Personal'] ?? null,
        ':montoPagadoTransaccion' => $datosPago['Monto_Pagado_Transaccion'],
        ':idEstadoPago' => $datosPago['ID_Estado_Pago'],
        ':observacionesPago' => $datosPago['Observaciones_Pago'] ?? null
    ]);
}

/**
 * Asocia una lista de productos a un asesor específico.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del asesor.
 * @param array $idProductos Array de IDs de productos a asociar.
 * @param string $descripcion Opcional, descripción para la asociación.
 * @param int $estado Opcional, estado de la asociación (1 activo, 0 inactivo).
 * @return bool True si todas las asociaciones se registran correctamente, false en caso contrario.
 */
function asociarAsesorProducto(PDO $conexion, int $idPersonal, array $idProductos, string $descripcion = '', int $estado = 1): bool {
    // Si no hay productos seleccionados, se considera exitoso (no hay nada que asociar)
    if (empty($idProductos)) {
        return true;
    }

    $sql = "INSERT INTO Asesor_Producto (ID_Personal, ID_Producto, Descripcion_AP, Fecha_Asignacion, Estado_AsesorProducto)
            VALUES (:idPersonal, :idProducto, :descripcionAP, NOW(), :estadoAP)";
    $stmt = $conexion->prepare($sql);

    foreach ($idProductos as $idProducto) {
        // Asegurarse de que el ID del producto sea un entero válido
        $idProducto = (int)$idProducto;
        if ($idProducto <= 0) {
            error_log("Intento de asociar un ID_Producto inválido: " . $idProducto);
            // Podrías lanzar una excepción o simplemente saltar este producto
            continue;
        }

        $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':descripcionAP', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':estadoAP', $estado, PDO::PARAM_INT);
        
        // Ejecutar la inserción. Si falla, la función devuelve false
        if (!$stmt->execute()) {
            error_log("Error al asociar producto ID {$idProducto} al personal ID {$idPersonal}.");
            return false; // Si una inserción falla, se reporta el error
        }
    }
    return true; // Todas las asociaciones fueron exitosas
}

/**
 * Obtiene un registro de personal y sus estadísticas asociadas.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del personal.
 * @return array|false Array asociativo del personal con sus estadísticas o false si no se encuentra.
 */
function obtenerPerfilPersonalCompleto(PDO $conexion, int $idPersonal) {
    // 1. Consulta principal para los datos del personal
    // Usamos LEFT JOIN para que siempre traiga el personal, incluso si no tiene registros de asesoramiento.
    $sqlPersonal = "SELECT 
                        p.ID_Personal, 
                        p.Nombre_Personal, 
                        p.Apellido_Personal, 
                        p.N_Documento_Personal,
                        p.Correo_Personal,
                        p.Celular_Personal,
                        p.Fecha_Creacion_Personal,
                        p.Activo_Personal,
                        p.ID_Rol,
                        r.Rol AS Nombre_Rol, 
                        td.Tipo_Documento AS Nombre_Tipo_Documento, 
                        g.Genero AS Nombre_Genero
                    FROM personal p
                    LEFT JOIN rol r ON p.ID_Rol = r.ID_Rol
                    LEFT JOIN tipo_documento td ON p.ID_TD = td.ID_TD
                    LEFT JOIN genero g ON p.ID_Genero = g.ID_Genero
                    WHERE p.ID_Personal = :idPersonal";
    
    $stmtPersonal = $conexion->prepare($sqlPersonal);
    $stmtPersonal->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    $stmtPersonal->execute();
    $personalData = $stmtPersonal->fetch(PDO::FETCH_ASSOC);

    if (!$personalData) {
        return false; // El personal no fue encontrado
    }

    // 2. Consulta para estadísticas de registro de asesoramiento
    // Hacemos GROUP BY para sumar/contar por el ID_Personal.
    $sqlEstadisticas = "SELECT
                COUNT(ra.ID_RegistroAsesoramiento) AS total_asesoramientos,
                COUNT(DISTINCT ra.ID_Cliente) AS clientes_asesorados_unicos,
                COUNT(DISTINCT ra.ID_Turno) AS turnos_atendidos,
                SUM(CASE WHEN c.Monto_Total_Credito IS NOT NULL THEN c.Monto_Total_Credito ELSE 0 END) AS monto_total_aprobado_clientes_asesorados
            FROM registroasesoramiento ra
            LEFT JOIN credito c ON ra.ID_Cliente = c.ID_Cliente -- ¡Aquí está el cambio clave!
            WHERE ra.ID_Personal = :idPersonal";

    $stmtEstadisticas = $conexion->prepare($sqlEstadisticas);
    $stmtEstadisticas->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    $stmtEstadisticas->execute();
    $estadisticas = $stmtEstadisticas->fetch(PDO::FETCH_ASSOC);

    // Si no hay estadísticas, se inicializan a cero para evitar errores
    if (!$estadisticas) {
        $estadisticas = [
            'total_asesoramientos' => 0,
            'clientes_asesorados_unicos' => 0,
            'turnos_atendidos' => 0,
            'monto_total_aprobado_clientes_asesorados' => 0.00
        ];
    } else {
         // Formatear el monto para la vista si es necesario, aquí o en el frontend
         $estadisticas['monto_total_aprobado_clientes_asesorados'] = number_format((float)$estadisticas['monto_total_aprobado_clientes_asesorados'], 2, '.', ',');
    }


    // 3. Combinar los datos del personal con las estadísticas
    $personalData['estadisticas'] = $estadisticas;

    return $personalData;
}

/**
     * Actualiza un registro de asesoramiento existente.
     * @param PDO $conexion Objeto de conexión PDO.
     * @param int $idRegistroAsesoramiento ID del registro a actualizar.
     * @param array $datosAsesoramiento Array asociativo con los datos a actualizar.
     * @return bool True si se actualiza correctamente, false en caso contrario.
     */
    function actualizarRegistroAsesoramiento(PDO $conexion, int $idRegistroAsesoramiento, array $datosAsesoramiento): bool {
        $campos = [];
        $valores = [':idRegistroAsesoramiento' => $idRegistroAsesoramiento];
    
        if (isset($datosAsesoramiento['ID_Personal'])) { $campos[] = 'ID_Personal = :idPersonal'; $valores[':idPersonal'] = $datosAsesoramiento['ID_Personal']; }
        if (isset($datosAsesoramiento['ID_Cliente'])) { $campos[] = 'ID_Cliente = :idCliente'; $valores[':idCliente'] = $datosAsesoramiento['ID_Cliente']; }
        if (isset($datosAsesoramiento['ID_Turno'])) { $campos[] = 'ID_Turno = :idTurno'; $valores[':idTurno'] = $datosAsesoramiento['ID_Turno']; }
        if (isset($datosAsesoramiento['Fecha_Hora_Inicio'])) { $campos[] = 'Fecha_Hora_Inicio = :fechaInicio'; $valores[':fechaInicio'] = $datosAsesoramiento['Fecha_Hora_Inicio']; }
        if (isset($datosAsesoramiento['Fecha_Hora_Fin'])) { $campos[] = 'Fecha_Hora_Fin = :fechaFin'; $valores[':fechaFin'] = $datosAsesoramiento['Fecha_Hora_Fin']; }
        if (isset($datosAsesoramiento['Observaciones'])) { $campos[] = 'Observaciones = :observaciones'; $valores[':observaciones'] = $datosAsesoramiento['Observaciones']; }
        if (isset($datosAsesoramiento['Resultado'])) { $campos[] = 'Resultado = :resultado'; $valores[':resultado'] = $datosAsesoramiento['Resultado']; }
    
        if (empty($campos)) {
            return false;
        }
    
        $sql = "UPDATE RegistroAsesoramiento SET " . implode(', ', $campos) . " WHERE ID_RegistroAsesoramiento = :idRegistroAsesoramiento";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute($valores);
    }

function obtenerDatosComprobantePago($conexion, $idCuota) {
    try {
        $stmt = $conexion->prepare("
            SELECT
                cc.ID_Credito,
                cc.ID_CuotaCredito,
                cc.Numero_Cuota,
                cc.Monto_Total_Cuota,
                cc.Monto_Capital,
                cc.Monto_Interes,
                cc.Monto_Recargo_Mora,
                cc.Fecha_Pago,
                cc.Fecha_Vencimiento,
                pc.Monto_Pagado_Transaccion,
                pc.Fecha_Hora_Pago,
                pc.Observaciones_Pago,
                cr.Monto_Total_Credito,
                cl.Nombre_Cliente,
                cl.Apellido_Cliente,
                cl.N_Documento_Cliente,
                p.Nombre_Personal,
                p.Apellido_Personal,
                e.Estado AS Estado_Cuota_Nombre
            FROM
                cuotacredito cc
            JOIN
                pagocuota pc ON cc.ID_CuotaCredito = pc.ID_CuotaCredito
            JOIN
                credito cr ON cc.ID_Credito = cr.ID_Credito
            JOIN
                cliente cl ON cr.ID_Cliente = cl.ID_Cliente
            LEFT JOIN
                personal p ON pc.ID_Personal = p.ID_Personal
            LEFT JOIN
                estado e ON cc.ID_Estado_Cuota = e.ID_Estado
            WHERE
                cc.ID_CuotaCredito = :idCuota
            LIMIT 1
        ");
        $stmt->bindParam(':idCuota', $idCuota, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al obtener datos del comprobante de pago: " . $e->getMessage());
        return false;
    }
}

function obtenerProximaCuota($conexion, $idCredito, $numeroCuotaActual) {
    try {
        $stmt = $conexion->prepare("
            SELECT Numero_Cuota, Fecha_Vencimiento, Monto_Total_Cuota
            FROM cuotacredito
            WHERE ID_Credito = :idCredito AND Numero_Cuota = :numProx
            LIMIT 1
        ");
        $stmt->execute([
            ':idCredito' => $idCredito,
            ':numProx' => $numeroCuotaActual + 1
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener próxima cuota: " . $e->getMessage());
        return null;
    }
}

// Función 2: Actualizar el estado de un crédito
// Necesaria para el case 'Aprobar_Desembolso' en asesorController.php
function actualizarEstadoCredito($conexion, $idCredito, $nuevoEstadoId) {
    try {
        // Asume que tienes una tabla 'Credito' con una columna 'ID_Estado_Credito'
        $stmt = $conexion->prepare("
            UPDATE Credito
            SET Desembolso = :nuevoEstadoId
            WHERE ID_Credito = :idCredito
        ");
        $stmt->bindParam(':nuevoEstadoId', $nuevoEstadoId, PDO::PARAM_STR);
        $stmt->bindParam(':idCredito', $idCredito, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Devuelve true si se actualizó al menos una fila

    } catch (PDOException $e) {
        error_log("Error al actualizar estado del crédito: " . $e->getMessage());
        return false;
    }
}

// Función 3: Registrar un desembolso de crédito
// Necesaria para el case 'Aprobar_Desembolso' en asesorController.php
function registrarDesembolso($conexion, $idCredito, $montoDesembolsar, $idPersonal, $observaciones = '') {
    try {
        // Asume que tienes una tabla llamada 'Desembolsos' o 'Movimientos_Credito'
        // que registra los desembolsos. Si no la tienes, necesitarías crearla.
        $stmt = $conexion->prepare("
            INSERT INTO Desembolsos (ID_Credito, Monto_Desembolsado, Fecha_Desembolso, ID_Personal, Observaciones)
            VALUES (:idCredito, :montoDesembolsar, NOW(), :idPersonal, :observaciones)
        ");
        $stmt->bindParam(':idCredito', $idCredito, PDO::PARAM_INT);
        $stmt->bindParam(':montoDesembolsar', $montoDesembolsar, PDO::PARAM_STR); // Usar STR para montos, PDO maneja la conversión
        $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
        $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
        $stmt->execute();
        return $conexion->lastInsertId(); // Devuelve el ID del nuevo registro de desembolso

    } catch (PDOException $e) {
        error_log("Error al registrar desembolso: " . $e->getMessage());
        return false;
    }
}








































































// METODOS FUNCIONALES 

function obtenerProductos($conexion) {
    $sql = "SELECT * FROM producto";
    $stmt = $conexion->query($sql);
    if (!$stmt) {
        throw new Exception("Error al obtener inventario de alimentos: " . implode(":", $conexion->errorInfo()));
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un registro de personal por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del personal.
 * @return array|false Array asociativo del personal o false si no se encuentra.
 */
function obtenerPersonalPorId(PDO $conexion, int $idPersonal) {
    $sql = "SELECT p.*, r.Rol AS Nombre_Rol, td.Tipo_Documento AS Nombre_Tipo_Documento, g.Genero AS Nombre_Genero
            FROM personal p
            LEFT JOIN rol r ON p.ID_Rol = r.ID_Rol
            LEFT JOIN tipo_documento td ON p.ID_TD = td.ID_TD
            LEFT JOIN genero g ON p.ID_Genero = g.ID_Genero
            WHERE p.ID_Personal = :idPersonal";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



/**
 * Actualiza un registro de personal existente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del personal a actualizar.
 * @param array $datosPersonal Array asociativo con los datos a actualizar.
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarPersonal(PDO $conexion, int $idPersonal, array $datosPersonal): bool {
    $campos = [];
    $valores = [':idPersonal' => $idPersonal];

    if (isset($datosPersonal['Nombre_Personal'])) { $campos[] = 'Nombre_Personal = :nombre'; $valores[':nombre'] = $datosPersonal['Nombre_Personal']; }
    if (isset($datosPersonal['Apellido_Personal'])) { $campos[] = 'Apellido_Personal = :apellido'; $valores[':apellido'] = $datosPersonal['Apellido_Personal']; }
    if (isset($datosPersonal['ID_Rol'])) { $campos[] = 'ID_Rol = :idRol'; $valores[':idRol'] = $datosPersonal['ID_Rol']; }
    if (isset($datosPersonal['ID_Genero'])) { $campos[] = 'ID_Genero = :idGenero'; $valores[':idGenero'] = $datosPersonal['ID_Genero']; }
    if (isset($datosPersonal['ID_TD'])) { $campos[] = 'ID_TD = :idTd'; $valores[':idTd'] = $datosPersonal['ID_TD']; }
    if (isset($datosPersonal['N_Documento_Personal'])) { $campos[] = 'N_Documento_Personal = :nDocumento'; $valores[':nDocumento'] = $datosPersonal['N_Documento_Personal']; }
    if (isset($datosPersonal['Celular_Personal'])) { $campos[] = 'Celular_Personal = :celular'; $valores[':celular'] = $datosPersonal['Celular_Personal']; }
    if (isset($datosPersonal['Correo_Personal'])) { $campos[] = 'Correo_Personal = :correo'; $valores[':correo'] = $datosPersonal['Correo_Personal']; }
    if (isset($datosPersonal['Contraseña_Personal']) && !empty($datosPersonal['Contraseña_Personal'])) {
        $campos[] = 'Contraseña_Personal = :contrasena';
        $valores[':contrasena'] = password_hash($datosPersonal['Contraseña_Personal'], PASSWORD_DEFAULT);
    }
    if (isset($datosPersonal['Activo_Personal'])) { $campos[] = 'Activo_Personal = :activo'; $valores[':activo'] = $datosPersonal['Activo_Personal']; }
    if (isset($datosPersonal['Foto_Perfil_Personal'])) { $campos[] = 'Foto_Perfil_Personal = :fotoPerfil'; $valores[':fotoPerfil'] = $datosPersonal['Foto_Perfil_Personal']; }

    if (empty($campos)) {
        return false; // No hay campos para actualizar
    }

    $sql = "UPDATE personal SET " . implode(', ', $campos) . " WHERE ID_Personal = :idPersonal";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($valores);
}

/**
 * Desactiva un registro de personal (soft delete).
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del personal a desactivar.
 * @return bool True si se desactiva correctamente, false en caso contrario.
 */
function desactivarPersonal(PDO $conexion, int $idPersonal): bool {
    $sql = "UPDATE personal SET Activo_Personal = 0 WHERE ID_Personal = :idPersonal";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    return $stmt->execute();
}

// --- Funciones para la tabla 'cliente' ---

/**
 * Obtiene todos los registros de clientes con su género y tipo de documento.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param bool $incluirInactivos Si es true, incluye clientes inactivos.
 * @return array Array asociativo de clientes.
 */
function obtenerTodosLosClientes(PDO $conexion, bool $incluirInactivos = false): array {
    $sql = "SELECT c.*, g.Genero AS Nombre_Genero, td.Tipo_Documento AS Nombre_Tipo_Documento
            FROM cliente c
            LEFT JOIN genero g ON c.ID_Genero = g.ID_Genero
            LEFT JOIN tipo_documento td ON c.ID_TD = td.ID_TD";
    if (!$incluirInactivos) {
        $sql .= " WHERE c.Estado_Cliente = 1";
    }
    $sql .= " ORDER BY c.Nombre_Cliente ASC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un registro de cliente por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCliente ID del cliente.
 * @return array|false Array asociativo del cliente o false si no se encuentra.
 */
function obtenerClientePorId(PDO $conexion, int $idCliente) {
    $sql = "SELECT c.*, g.Genero AS Nombre_Genero, td.Tipo_Documento AS Nombre_Tipo_Documento
            FROM cliente c
            LEFT JOIN genero g ON c.ID_Genero = g.ID_Genero
            LEFT JOIN tipo_documento td ON c.ID_TD = td.ID_TD
            WHERE c.ID_Cliente = :idCliente";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/**
 * Registra un nuevo cliente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosCliente Array asociativo con los datos del cliente.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarCliente(PDO $conexion, array $datosCliente): bool {
    $sql = "INSERT INTO cliente (Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, N_Documento_Cliente, Celular_Cliente, Correo_Cliente, Direccion_Cliente, Ciudad_Cliente, Fecha_Nacimiento_Cliente, ID_Personal_Creador, Contraseña, Estado_Cliente)
            VALUES (:nombre, :apellido, :idGenero, :idTd, :nDocumento, :celular, :correo, :direccion, :ciudad, :fechaNacimiento, :idPersonalCreador, :contrasena, :estado)";
    $stmt = $conexion->prepare($sql);

    // Hashear la contraseña antes de guardar
    // Asegúrate de que $datosCliente['Contraseña'] siempre exista aquí.
    $hashedPassword = password_hash($datosCliente['Contraseña'], PASSWORD_DEFAULT);
    // $datosCliente['Contraseña'] = password_hash($datosPersonal['Contraseña'], PASSWORD_DEFAULT);

    return $stmt->execute([
        ':nombre' => $datosCliente['Nombre_Cliente'],
        ':apellido' => $datosCliente['Apellido_Cliente'],
        ':idGenero' => $datosCliente['ID_Genero'],
        ':idTd' => $datosCliente['ID_TD'],
        ':nDocumento' => $datosCliente['N_Documento_Cliente'],
        ':celular' => $datosCliente['Celular_Cliente'],
        ':correo' => $datosCliente['Correo_Cliente'],
        ':direccion' => $datosCliente['Direccion_Cliente'],
        ':ciudad' => $datosCliente['Ciudad_Cliente'],
        ':fechaNacimiento' => $datosCliente['Fecha_Nacimiento_Cliente'],
        ':idPersonalCreador' => $datosCliente['ID_Personal_Creador'] ?? null,
        ':contrasena' => $hashedPassword, // Usar la contraseña hasheada
        ':estado' => $datosCliente['Estado_Cliente'] ?? 1
    ]);
}

/**
 * Registra un nuevo crédito para un cliente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosCredito Array asociativo con los datos del crédito.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarCredito(PDO $conexion, array $datosCredito): bool {
    $sql = "INSERT INTO credito (
                ID_Cliente,
                Monto_Total_Credito,
                Desembolso,
                Monto_Pendiente_Credito,
                Fecha_Apertura_Credito,
                Fecha_Vencimiento_Credito,
                ID_Producto,
                ID_Estado,
                Tasa_Interes_Anual,
                Tasa_Interes_Periodica,
                Numero_Cuotas,
                Valor_Cuota_Calculado,
                Periodicidad
            ) VALUES (
                :idCliente,
                :montoTotal,
                :desembolso,
                :montoPendiente,
                :fechaCreacion,
                :fechaVencimiento,
                :idProducto,
                :idEstado,
                :tasaAnual,
                :tasaPeriodica,
                :numeroCuotas,
                :valorCuotaCalculado,
                :periodicidad
            )";
    
    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        ':idCliente' => $datosCredito['ID_Cliente'],
        ':montoTotal' => $datosCredito['Monto_Total_Credito'],
        ':desembolso'=> $datosCredito['Desembolso'],
        ':montoPendiente' => $datosCredito['Monto_Pendiente_Credito'],
        ':fechaCreacion'=> $datosCredito['Fecha_Apertura_Credito'],
        ':fechaVencimiento' => $datosCredito['Fecha_Vencimiento_Credito'],
        ':idProducto' => $datosCredito['ID_Producto'],
        ':idEstado' => $datosCredito['ID_Estado'],
        ':tasaAnual' => $datosCredito['Tasa_Interes_Anual'],
        ':tasaPeriodica' => $datosCredito['Tasa_Interes_Periodica'],
        ':numeroCuotas' => $datosCredito['Numero_Cuotas'],
        ':valorCuotaCalculado' => $datosCredito['Valor_Cuota_Calculado'],
        ':periodicidad' => $datosCredito['Periodicidad']
    ]);
}

/**
 * Registra una cuota específica de un crédito.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosCuota Array asociativo con los datos de la cuota.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function registrarCuotaCredito(PDO $conexion, array $datosCuota): bool {
    $sql = "INSERT INTO cuotacredito (
                ID_Credito,
                Numero_Cuota,
                Monto_Capital,
                Monto_Interes,
                Monto_Total_Cuota,
                Fecha_Vencimiento,
                ID_Estado_Cuota,
                Fecha_Pago, -- Puede ser null si aún no se ha pagado
                Monto_Pagado -- Puede ser null si aún no se ha pagado
            ) VALUES (
                :idCredito,
                :numeroCuota,
                :montoCapital,
                :montoInteres,
                :montoTotalCuota,
                :fechaVencimiento,
                :idEstadoCuota,
                :fechaPago,
                :montoPagado
            )";
    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        ':idCredito' => $datosCuota['ID_Credito'],
        ':numeroCuota' => $datosCuota['Numero_Cuota'],
        ':montoCapital' => $datosCuota['Monto_Capital'],
        ':montoInteres' => $datosCuota['Monto_Interes'],
        ':montoTotalCuota' => $datosCuota['Monto_Total_Cuota'],
        ':fechaVencimiento' => $datosCuota['Fecha_Vencimiento'],
        ':idEstadoCuota' => $datosCuota['ID_Estado_Cuota'],
        ':fechaPago' => $datosCuota['Fecha_Pago'] ?? null,   // Permitir NULL si no hay fecha de pago
        ':montoPagado' => $datosCuota['Monto_Pagado'] ?? null // Permitir NULL si no hay monto pagado
    ]);
}

/**
 * Actualiza un registro de cliente existente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCliente ID del cliente a actualizar.
 * @param array $datosCliente Array asociativo con los datos a actualizar.
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarCliente(PDO $conexion, int $idCliente, array $datosCliente): bool {
    $campos = [];
    $valores = [':idCliente' => $idCliente];

    if (isset($datosCliente['Nombre_Cliente'])) { $campos[] = 'Nombre_Cliente = :nombre'; $valores[':nombre'] = $datosCliente['Nombre_Cliente']; }
    if (isset($datosCliente['Apellido_Cliente'])) { $campos[] = 'Apellido_Cliente = :apellido'; $valores[':apellido'] = $datosCliente['Apellido_Cliente']; }
    if (isset($datosCliente['ID_Genero'])) { $campos[] = 'ID_Genero = :idGenero'; $valores[':idGenero'] = $datosCliente['ID_Genero']; }
    if (isset($datosCliente['ID_TD'])) { $campos[] = 'ID_TD = :idTd'; $valores[':idTd'] = $datosCliente['ID_TD']; }
    if (isset($datosCliente['N_Documento_Cliente'])) { $campos[] = 'N_Documento_Cliente = :nDocumento'; $valores[':nDocumento'] = $datosCliente['N_Documento_Cliente']; }
    if (isset($datosCliente['Celular_Cliente'])) { $campos[] = 'Celular_Cliente = :celular'; $valores[':celular'] = $datosCliente['Celular_Cliente']; }
    if (isset($datosCliente['Correo_Cliente'])) { $campos[] = 'Correo_Cliente = :correo'; $valores[':correo'] = $datosCliente['Correo_Cliente']; }
    if (isset($datosCliente['Direccion_Cliente'])) { $campos[] = 'Direccion_Cliente = :direccion'; $valores[':direccion'] = $datosCliente['Direccion_Cliente']; }
    if (isset($datosCliente['Ciudad_Cliente'])) { $campos[] = 'Ciudad_Cliente = :ciudad'; $valores[':ciudad'] = $datosCliente['Ciudad_Cliente']; }
    if (isset($datosCliente['Fecha_Nacimiento_Cliente'])) { $campos[] = 'Fecha_Nacimiento_Cliente = :fechaNacimiento'; $valores[':fechaNacimiento'] = $datosCliente['Fecha_Nacimiento_Cliente']; }
    if (isset($datosCliente['ID_Personal_Creador'])) { $campos[] = 'ID_Personal_Creador = :idPersonalCreador'; $valores[':idPersonalCreador'] = $datosCliente['ID_Personal_Creador']; }
    if (isset($datosCliente['Contraseña']) && !empty($datosCliente['Contraseña'])) {
        $campos[] = 'Contraseña = :contrasena';
        $valores[':contrasena'] = password_hash($datosCliente['Contraseña'], PASSWORD_DEFAULT);
    }
    if (isset($datosCliente['Estado_Cliente'])) { $campos[] = 'Estado_Cliente = :estado'; $valores[':estado'] = $datosCliente['Estado_Cliente']; }

    if (empty($campos)) {
        return false; // No hay campos para actualizar
    }

    $sql = "UPDATE cliente SET " . implode(', ', $campos) . " WHERE ID_Cliente = :idCliente";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($valores);
}

/**
 * Desactiva un registro de cliente (soft delete).
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCliente ID del cliente a desactivar.
 * @return bool True si se desactiva correctamente, false en caso contrario.
 */
function desactivarCliente(PDO $conexion, int $idCliente): bool {
    $sql = "UPDATE cliente SET Estado_Cliente = 0 WHERE ID_Cliente = :idCliente";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
    return $stmt->execute();
}

// --- Funciones para la tabla 'credito' ---

/**
 * Obtiene todos los créditos, opcionalmente filtrados por cliente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int|null $idCliente ID del cliente para filtrar, o null para todos.
 * @return array Array asociativo de créditos.
 */
function obtenerCreditos(PDO $conexion, ?int $idCliente = null): array {
    $sql = "SELECT cr.*, cl.Nombre_Cliente, cl.Apellido_Cliente, p.Nombre_Producto, e.Estado AS Estado_Credito
            FROM credito cr
            JOIN cliente cl ON cr.ID_Cliente = cl.ID_Cliente
            JOIN producto p ON cr.ID_Producto = p.ID_Producto
            JOIN estado e ON cr.ID_Estado = e.ID_Estado
            WHERE e.Tipo_Estado = 'Credito'"; // Filtrar estados específicos de crédito
    $valores = [];
    if ($idCliente !== null) {
        $sql .= " AND cr.ID_Cliente = :idCliente";
        $valores[':idCliente'] = $idCliente;
    }
    $sql .= " ORDER BY cr.Fecha_Apertura_Credito DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute($valores);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un crédito por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCredito ID del crédito.
 * @return array|false Array asociativo del crédito o false si no se encuentra.
 */
function obtenerCreditoPorId(PDO $conexion, int $idCredito) {
    $sql = "SELECT cr.*, cl.Nombre_Cliente, cl.Apellido_Cliente, p.Nombre_Producto, e.Estado AS Estado_Credito
            FROM credito cr
            JOIN cliente cl ON cr.ID_Cliente = cl.ID_Cliente
            JOIN producto p ON cr.ID_Producto = p.ID_Producto
            JOIN estado e ON cr.ID_Estado = e.ID_Estado
            WHERE cr.ID_Credito = :idCredito AND e.Tipo_Estado = 'Credito'";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCredito', $idCredito, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Actualiza un registro de crédito existente.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCredito ID del crédito a actualizar.
 * @param array $datosCredito Array asociativo con los datos a actualizar.
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarCredito(PDO $conexion, int $idCredito, array $datosCredito): bool {
    $campos = [];
    $valores = [':idCredito' => $idCredito];

    if (isset($datosCredito['Monto_Pendiente_Credito'])) { $campos[] = 'Monto_Pendiente_Credito = :montoPendiente'; $valores[':montoPendiente'] = $datosCredito['Monto_Pendiente_Credito']; }
    if (isset($datosCredito['Fecha_Vencimiento_Credito'])) { $campos[] = 'Fecha_Vencimiento_Credito = :fechaVencimiento'; $valores[':fechaVencimiento'] = $datosCredito['Fecha_Vencimiento_Credito']; }
    if (isset($datosCredito['ID_Estado'])) { $campos[] = 'ID_Estado = :idEstado'; $valores[':idEstado'] = $datosCredito['ID_Estado']; }

    if (empty($campos)) {
        return false;
    }

    $sql = "UPDATE credito SET " . implode(', ', $campos) . " WHERE ID_Credito = :idCredito";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($valores);
}

// --- Funciones para la tabla 'CuotaCredito' ---







// --- Funciones para la tabla 'PagoCuota' ---



/**
 * Obtiene todos los pagos de una cuota específica.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idCuotaCredito ID de la cuota.
 * @return array Array asociativo de pagos.
 */
function obtenerPagosPorCuota(PDO $conexion, int $idCuotaCredito): array {
    $sql = "SELECT pc.*, p.Nombre_Personal, p.Apellido_Personal, e.Estado AS Estado_Pago
            FROM PagoCuota pc
            LEFT JOIN personal p ON pc.ID_Personal = p.ID_Personal
            JOIN estado e ON pc.ID_Estado_Pago = e.ID_Estado
            WHERE pc.ID_CuotaCredito = :idCuotaCredito AND e.Tipo_Estado = 'Pago'
            ORDER BY pc.Fecha_Hora_Pago ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idCuotaCredito', $idCuotaCredito, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



/**
 * Obtiene todos los registros de asesoramiento, opcionalmente filtrados por cliente o personal.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int|null $idCliente ID del cliente para filtrar.
 * @param int|null $idPersonal ID del personal para filtrar.
 * @return array Array asociativo de registros de asesoramiento.
 */
function obtenerRegistrosAsesoramiento(PDO $conexion, ?int $idCliente = null, ?int $idPersonal = null): array {
    $sql = "SELECT ra.*, cl.Nombre_Cliente, cl.Apellido_Cliente, p.Nombre_Personal, p.Apellido_Personal, t.Numero_Turno
            FROM RegistroAsesoramiento ra
            JOIN cliente cl ON ra.ID_Cliente = cl.ID_Cliente
            JOIN personal p ON ra.ID_Personal = p.ID_Personal
            LEFT JOIN turno t ON ra.ID_Turno = t.ID_Turno";
    $condiciones = [];
    $valores = [];

    if ($idCliente !== null) {
        $condiciones[] = "ra.ID_Cliente = :idCliente";
        $valores[':idCliente'] = $idCliente;
    }
    if ($idPersonal !== null) {
        $condiciones[] = "ra.ID_Personal = :idPersonal";
        $valores[':idPersonal'] = $idPersonal;
    }

    if (!empty($condiciones)) {
        $sql .= " WHERE " . implode(' AND ', $condiciones);
    }
    $sql .= " ORDER BY ra.Fecha_Hora_Inicio DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($valores);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un registro de asesoramiento por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idRegistroAsesoramiento ID del registro de asesoramiento.
 * @return array|false Array asociativo del registro o false si no se encuentra.
 */
function obtenerRegistroAsesoramientoPorId(PDO $conexion, int $idRegistroAsesoramiento) {
    $sql = "SELECT ra.*, cl.Nombre_Cliente, cl.Apellido_Cliente, p.Nombre_Personal, p.Apellido_Personal, t.Numero_Turno
            FROM RegistroAsesoramiento ra
            JOIN cliente cl ON ra.ID_Cliente = cl.ID_Cliente
            JOIN personal p ON ra.ID_Personal = p.ID_Personal
            LEFT JOIN turno t ON ra.ID_Turno = t.ID_Turno
            WHERE ra.ID_RegistroAsesoramiento = :idRegistroAsesoramiento";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idRegistroAsesoramiento', $idRegistroAsesoramiento, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// --- Funciones para la tabla 'asesor_producto' ---

/**
 * Asigna un producto a un asesor.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $datosAsesorProducto Array asociativo con ID_Personal, ID_Producto, Descripcion_AP, Estado_AsesorProducto.
 * @return bool True si se registra correctamente, false en caso contrario.
 */
function asignarAsesorProducto(PDO $conexion, array $datosAsesorProducto): bool {
    $sql = "INSERT INTO asesor_producto (ID_Personal, ID_Producto, Descripcion_AP, Estado_AsesorProducto)
            VALUES (:idPersonal, :idProducto, :descripcionAp, :estadoAp)";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        ':idPersonal' => $datosAsesorProducto['ID_Personal'],
        ':idProducto' => $datosAsesorProducto['ID_Producto'],
        ':descripcionAp' => $datosAsesorProducto['Descripcion_AP'] ?? null,
        ':estadoAp' => $datosAsesorProducto['Estado_AsesorProducto'] ?? 'Activo'
    ]);
}

/**
 * Obtiene los productos asignados a un asesor.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idPersonal ID del personal.
 * @param bool $incluirInactivos Si es true, incluye asignaciones inactivas.
 * @return array Array asociativo de productos asignados.
 */
function obtenerProductosPorAsesor(PDO $conexion, int $idPersonal, bool $incluirInactivos = false): array {
    $sql = "SELECT ap.*, p.Nombre_Producto, p.Descripcion_Producto, p.Monto_Minimo, p.Monto_Maximo, p.Plazo_Minimo, p.Plazo_Maximo 
            FROM asesor_producto ap
            JOIN producto p ON ap.ID_Producto = p.ID_Producto
            WHERE ap.ID_Personal = :idPersonal";
    if (!$incluirInactivos) {
        $sql .= " AND ap.Estado_AsesorProducto = 'Activo'";
    }
    $sql .= " ORDER BY p.Nombre_Producto ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Actualiza el estado de una asignación asesor-producto.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idAsesorProducto ID de la asignación.
 * @param string $estado Nuevo estado ('Activo' o 'Inactivo').
 * @return bool True si se actualiza correctamente, false en caso contrario.
 */
function actualizarEstadoAsesorProducto(PDO $conexion, int $idAsesorProducto, string $estado): bool {
    $sql = "UPDATE asesor_producto SET Estado_AsesorProducto = :estado WHERE ID_Asesor_Producto = :idAsesorProducto";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':idAsesorProducto', $idAsesorProducto, PDO::PARAM_INT);
    return $stmt->execute();
}

// --- Funciones para la tabla 'bitacora' ---



/**
 * Obtiene registros de la bitácora, con filtros opcionales.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param array $filtros Array asociativo de filtros (ej. 'ID_Cliente', 'Tipo_Evento').
 * @return array Array asociativo de registros de bitácora.
 */
function obtenerEventosBitacora(PDO $conexion, array $filtros = []): array {
    $sql = "SELECT b.*, c.Nombre_Cliente, c.Apellido_Cliente, p.Nombre_Personal, p.Apellido_Personal
            FROM bitacora b
            LEFT JOIN cliente c ON b.ID_Cliente = c.ID_Cliente
            LEFT JOIN personal p ON b.ID_Personal = p.ID_Personal";
    $condiciones = [];
    $valores = [];

    if (isset($filtros['ID_Cliente'])) {
        $condiciones[] = "b.ID_Cliente = :idCliente";
        $valores[':idCliente'] = $filtros['ID_Cliente'];
    }
    if (isset($filtros['ID_Personal'])) {
        $condiciones[] = "b.ID_Personal = :idPersonal";
        $valores[':idPersonal'] = $filtros['ID_Personal'];
    }
    if (isset($filtros['Tipo_Evento'])) {
        $condiciones[] = "b.Tipo_Evento = :tipoEvento";
        $valores[':tipoEvento'] = $filtros['Tipo_Evento'];
    }
    if (isset($filtros['Fecha_Inicio'])) {
        $condiciones[] = "b.Fecha_Hora >= :fechaInicio";
        $valores[':fechaInicio'] = $filtros['Fecha_Inicio'];
    }
    if (isset($filtros['Fecha_Fin'])) {
        $condiciones[] = "b.Fecha_Hora <= :fechaFin";
        $valores[':fechaFin'] = $filtros['Fecha_Fin'];
    }

    if (!empty($condiciones)) {
        $sql .= " WHERE " . implode(' AND ', $condiciones);
    }
    $sql .= " ORDER BY b.Fecha_Hora DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($valores);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Funciones para la tabla 'turno' ---




/**
 * Obtiene un turno por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idTurno ID del turno.
 * @return array|false Array asociativo del turno o false si no se encuentra.
 */
function obtenerTurnoPorId(PDO $conexion, int $idTurno) {
    $sql = "SELECT t.*, c.Nombre_Cliente, c.Apellido_Cliente, p.Nombre_Producto, e.Estado AS Estado_Turno
            FROM turno t
            LEFT JOIN cliente c ON t.ID_Cliente = c.ID_Cliente
            LEFT JOIN producto p ON t.ID_Producto_Interes = p.ID_Producto
            JOIN estado e ON t.ID_Estado_Turno = e.ID_Estado
            WHERE t.ID_Turno = :idTurno AND e.Tipo_Estado = 'Turno'";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idTurno', $idTurno, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



// --- Funciones para tablas auxiliares (ejemplos) ---

/**
 * Obtiene todos los estados de un tipo específico.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param string $tipoEstado Tipo de estado (ej. 'Turno', 'Credito', 'Pago', 'Cuota', 'General', 'Personal', 'Producto').
 * @return array Array asociativo de estados.
 */
function obtenerEstadosPorTipo(PDO $conexion, string $tipoEstado): array {
    $sql = "SELECT * FROM estado WHERE Tipo_Estado = :tipoEstado ORDER BY Estado ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':tipoEstado', $tipoEstado, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un estado por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idEstado ID del estado.
 * @return array|false Array asociativo del estado o false si no se encuentra.
 */
function obtenerEstadoPorId(PDO $conexion, int $idEstado) {
    $sql = "SELECT * FROM estado WHERE ID_Estado = :idEstado";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idEstado', $idEstado, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los roles.
 * @param PDO $conexion Objeto de conexión PDO.
 * @return array Array asociativo de roles.
 */
function obtenerTodosLosRoles(PDO $conexion): array {
    $sql = "SELECT * FROM rol ORDER BY Nivel_Acceso ASC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un rol por su ID.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param int $idRol ID del rol.
 * @return array|false Array asociativo del rol o false si no se encuentra.
 */
function obtenerRolPorId(PDO $conexion, int $idRol) {
    $sql = "SELECT * FROM rol WHERE ID_Rol = :idRol";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los productos.
 * @param PDO $conexion Objeto de conexión PDO.
 * @param bool $incluirInactivos Si es true, incluye productos inactivos.
 * @return array Array asociativo de productos.
 */
function obtenerTodosLosProductos(PDO $conexion, bool $incluirInactivos = false): array {
    $sql = "SELECT p.*, ti.Tasa_Interes, per.Periodo
            FROM producto p
            JOIN tasa_interes ti ON p.ID_TI = ti.ID_TI
            JOIN periodo per ON ti.ID_Periodo = per.ID_Periodo";
    if (!$incluirInactivos) {
        $sql .= " WHERE p.Activo_Producto = 1";
    }
    $sql .= " ORDER BY p.Nombre_Producto ASC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>