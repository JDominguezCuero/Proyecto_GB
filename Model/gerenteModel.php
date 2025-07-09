<?php
// cliente/model.php
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';

// --- Modelo/clienteModelo.php ---
function getTotalClientes(PDO $conexion): int {
    $sql = "SELECT COUNT(ID_Cliente) FROM Cliente";
    $stmt = $conexion->query($sql);
    return $stmt->fetchColumn();
}

// function obtenerClientesPorDocumento(PDO $conexion, string $documento) {
//     $sql = "SELECT * FROM Cliente WHERE N_Documento_Cliente = :documento";
//     $stmt = $conexion->prepare($sql);
//     $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// function obtenerClientePorId(PDO $conexion, int $idCliente) {
//     $sql = "SELECT * FROM Cliente WHERE ID_Cliente = :idCliente";
//     $stmt = $conexion->prepare($sql);
//     $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// --- Modelo/creditoModelo.php ---
function getTotalCreditosActivos(PDO $conexion): int {
    // Asumiendo que ID_Estado = 4 es 'Activo' para créditos
    $sql = "SELECT COUNT(ID_Credito) FROM Credito WHERE ID_Estado = 4";
    $stmt = $conexion->query($sql);
    return $stmt->fetchColumn();
}

function getTotalCreditosEnMora(PDO $conexion): int {
    // Asumiendo que ID_Estado = 8 es 'Mora' para cuotas
    // Esto podría ser más complejo si un crédito se considera en mora si AL MENOS una cuota está en mora
    // Por simplicidad, aquí contamos créditos donde al menos una cuota está en mora.
    $sql = "SELECT COUNT(DISTINCT c.ID_Credito)
            FROM Credito c
            JOIN CuotaCredito cc ON c.ID_Credito = cc.ID_Credito
            WHERE cc.ID_Estado_Cuota = 8"; // Asumiendo 8 es el ID para 'Mora' en CuotaCredito
    $stmt = $conexion->query($sql);
    return $stmt->fetchColumn();
}

// function obtenerCreditoActivoPorCliente(PDO $conexion, int $idCliente) {
//     $sql = "SELECT cr.*, p.NombreProducto, e.Estado AS Estado_Credito
//             FROM Credito cr
//             JOIN Producto p ON cr.ID_Producto = p.ID_Producto
//             JOIN estado e ON cr.ID_Estado = e.ID_Estado
//             WHERE cr.ID_Cliente = :idCliente AND e.Tipo_Estado = 'Credito' AND e.Estado = 'Activo'"; // Asumiendo 'Activo'
//     $stmt = $conexion->prepare($sql);
//     $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// function obtenerCreditoPorId(PDO $conexion, int $idCredito) {
//     $sql = "SELECT cr.*, p.NombreProducto, e.Estado AS Estado_Credito
//             FROM Credito cr
//             JOIN Producto p ON cr.ID_Producto = p.ID_Producto
//             JOIN estado e ON cr.ID_Estado = e.ID_Estado
//             WHERE cr.ID_Credito = :idCredito";
//     $stmt = $conexion->prepare($sql);
//     $stmt->bindParam(':idCredito', $idCredito, PDO::PARAM_INT);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }


// --- Modelo/personalModelo.php ---
function obtenerPersonalPorDocumento(PDO $conexion, string $documento) {
    $sql = "SELECT p.*, r.Rol
            FROM Personal p
            JOIN Rol r ON p.ID_Rol = r.ID_Rol
            WHERE p.N_Documento_Personal = :documento";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// function obtenerRolPorId(PDO $conexion, int $idRol) {
//     $sql = "SELECT Nombre_Rol FROM Rol WHERE ID_Rol = :idRol";
//     $stmt = $conexion->prepare($sql);
//     $stmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// --- Modelo/pagoModelo.php (o un nuevo modelo de transacciones) ---
function getTotalMontoTransacciones(PDO $conexion): float {
    // Suma de todos los montos pagados en PagoCuota
    $sql = "SELECT SUM(Monto_Pagado_Transaccion) FROM PagoCuota";
    $stmt = $conexion->query($sql);
    return (float)$stmt->fetchColumn();
}

function getRecentPagosCuota(PDO $conexion, int $limit = 10): array {
    // Ajusta esta consulta para que traiga los datos que necesitas en la tabla
    // Incluye joins a Cliente y Personal para obtener nombres
    $sql = "SELECT pc.ID_PagoCuota, pc.Monto_Pagado_Transaccion, pc.Fecha_Hora_Pago,
                   c.Nombre_Cliente, c.Apellido_Cliente,
                   p.Nombre_Personal, p.Apellido_Personal
            FROM PagoCuota pc
            JOIN CuotaCredito cc ON pc.ID_CuotaCredito = cc.ID_CuotaCredito
            JOIN Credito cr ON cc.ID_Credito = cr.ID_Credito
            JOIN Cliente c ON cr.ID_Cliente = c.ID_Cliente
            LEFT JOIN Personal p ON pc.ID_Personal = p.ID_Personal -- LEFT JOIN si ID_Personal puede ser NULL
            ORDER BY pc.Fecha_Hora_Pago DESC
            LIMIT :limit";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Modelo/asesorProductoModelo.php ---
function obtenerProductosAsociadosAsesor(PDO $conexion, int $idPersonal): array {
    $sql = "SELECT ap.ID_Producto, p.Nombre_Producto
            FROM Asesor_Producto ap
            JOIN Producto p ON ap.ID_Producto = p.ID_Producto
            WHERE ap.ID_Personal = :idPersonal AND ap.Estado_AsesorProducto = 1"; // Asumiendo 1 es activo
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idPersonal', $idPersonal, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// --- Modelo/creditoModelo.php ---
function getCreditStatusCounts(PDO $conexion): array {
    $sql = "SELECT e.Estado, COUNT(c.ID_Credito) AS Count
            FROM Credito c
            JOIN Estado e ON c.ID_Estado = e.ID_Estado
            WHERE e.Tipo_Estado = 'Credito' -- Asegúrate de filtrar por tipo si es necesario
            GROUP BY e.Estado
            ORDER BY e.Estado";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Modelo/clienteModelo.php ---
function getNewClientsPerMonth(PDO $conexion): array {
    // Asume que tienes una columna Fecha_Creacion_Cliente en tu tabla Cliente
    $sql = "SELECT DATE_FORMAT(Fecha_Creacion_Cliente, '%Y-%m') AS Mes,
                   COUNT(ID_Cliente) AS Count
            FROM Cliente
            GROUP BY Mes
            ORDER BY Mes"; // Ordenar por mes para la línea de tiempo
    $stmt = $conexion->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear el mes para que sea más legible en el gráfico (ej. 'Ene 2024')
    $formattedResults = [];
    foreach ($results as $row) {
        $dateObj = DateTime::createFromFormat('Y-m', $row['Mes']);
        $row['Mes'] = $dateObj->format('M Y'); // Ej: 'Jan 2024'
        $formattedResults[] = $row;
    }
    return $formattedResults;
}

// --- Modelo/pagoModelo.php (o tu modelo de transacciones) ---
function getTransactionVolumePerMonth(PDO $conexion): array {
    // Asume que tienes una columna Fecha_Hora_Pago en tu tabla PagoCuota
    $sql = "SELECT DATE_FORMAT(Fecha_Hora_Pago, '%Y-%m') AS Mes,
                   SUM(Monto_Pagado_Transaccion) AS TotalMonto
            FROM PagoCuota
            GROUP BY Mes
            ORDER BY Mes"; // Ordenar por mes para la línea de tiempo
    $stmt = $conexion->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear el mes para que sea más legible en el gráfico (ej. 'Ene 2024')
    $formattedResults = [];
    foreach ($results as $row) {
        $dateObj = DateTime::createFromFormat('Y-m', $row['Mes']);
        $row['Mes'] = $dateObj->format('M Y'); // Ej: 'Jan 2024'
        $formattedResults[] = $row;
    }
    return $formattedResults;
}

function obtenerRegistrosBitacora(PDO $conexion): array {
    $sql = "SELECT 
        b.ID_Bitacora,
        b.ID_Cliente,
        c.Nombre_Cliente, 
        t.Nombre_Completo_Solicitante,
        c.N_Documento_Cliente, 
        t.N_Documento_Solicitante,
        t.ID_Turno,
        c.Apellido_Cliente,
        b.ID_Personal,
        p.Nombre_Personal,
        p.Apellido_Personal,
        p.N_Documento_Personal,
        rl.Rol,
        b.ID_RegistroAsesoramiento,
        a.ID_Turno,
        t.Numero_Turno,
        b.Tipo_Evento,
        b.Descripcion_Evento,
        b.Fecha_Hora
    FROM Bitacora b
    LEFT JOIN Cliente c ON b.ID_Cliente = c.ID_Cliente
    LEFT JOIN registroasesoramiento a ON b.ID_RegistroAsesoramiento = a.ID_RegistroAsesoramiento
    LEFT JOIN Turno t ON a.ID_Turno = t.ID_Turno
    LEFT JOIN Personal p ON b.ID_Personal = p.ID_Personal
    LEFT JOIN Rol rl ON p.ID_Rol = rl.ID_Rol
    ORDER BY b.Fecha_Hora DESC"; // Ordenar por fecha más reciente
    
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>