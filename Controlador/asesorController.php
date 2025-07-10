<?php
// personal/controller.php
session_start();

require_once(__DIR__ . '/../vendor/autoload.php');
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';
require_once __DIR__ . '/../../Proyecto_GB/Model/asesorModel.php';

use Dompdf\Options;
use Dompdf\Dompdf;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accionesPublicas = $_GET['crearTurnoPublico'] ?? ''; 

// Si la acción actual está en la lista pública, no validamos sesión

if ($accionesPublicas) {
    if (!isset($_SESSION['usuario'])) {
        if (
            isset($_SERVER['HTTP_ACCEPT']) &&
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
        ) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Sesión expirada o no iniciada.']);
        } else {
            $mensajeError = "Usuario no logueado.";
            header("Location: /Proyecto_GB/View/public/inicio.php?login=error&error=". urlencode($mensajeError)."&reason=nologin");
        }
        exit;
    }
}
   
$accion = $_GET['accion'] ?? 'listar';
$error = "";

try {
    switch ($accion) {
        // case 'listar':
        //     $personal = obtenerTodoElPersonal($conexion);
        //     header("Location: /Proyecto_GB/View/public/inicio.php?login=success");
        // break;

        case 'Credito_Cliente':
            $idPersonal = $_SESSION['idPersonal'];
            $idTurno = $_GET['idTurno'] ?? '';

            $turnoAtender = null;
            $productoInteres = null;

            if ($idTurno) {
                $turnosEncontrados = obtenerTurnos($conexion, ['ID_Turno' => $idTurno]);

                // Si se encontró un turno, tómalo (asumiendo que el ID es único)
                if (!empty($turnosEncontrados)) {
                    $turnoAtender = $turnosEncontrados[0];
                }

                if ($turnoAtender['ID_Producto_Interes']) {

                    $nuevoEstadoTurno = 2; 

                    $fechaSolicitud = new DateTime($turnoAtender['Fecha_Hora_Solicitud']);
                    $ahora = new DateTime();
                    $intervalo = $fechaSolicitud->diff($ahora);
                    $minutosEspera = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

                    // Actualizar estado del turno
                    $datosParaActualizarTurno = [
                        'ID_Estado_Turno' => $nuevoEstadoTurno,
                        'Tiempo_Espera_Minutos' => $minutosEspera
                    ];

                    $resultadoUpdateTurno = actualizarTurno($conexion, (int)$idTurno, $datosParaActualizarTurno);

                    // Registrar Asesoramiento
                    $datosAsesoramiento = [
                        'ID_Personal' => $idPersonal,
                        'ID_Cliente' => $turnoAtender['ID_Cliente'],
                        'ID_Turno' => $idTurno,
                        'Fecha_Hora_Inicio' => date('Y-m-d H:i:s'),
                        'Fecha_Hora_Fin' => null, // o la misma fecha si es inmediato
                        'Observaciones' => 'El asesor inició la atención del usuario mediante el turno asignado desde el sistema.',
                        'Resultado' => 'Usuario en proceso de vinculación'
                    ];

                    $idRegistroAsesoramiento = registrarAsesoramiento($conexion, $datosAsesoramiento);

                    // Registrar bitacora
                    if ($idRegistroAsesoramiento) {
                        $datosBitacora = [
                            'ID_Cliente' => $turnoAtender['ID_Cliente'],
                            'ID_Personal' => $idPersonal,
                            'ID_RegistroAsesoramiento' => $idRegistroAsesoramiento,
                            'Tipo_Evento' => 'Solicitud de crédito',
                            'Descripcion_Evento' => 'Se registró asesoramiento para solicitud de crédito'
                        ];

                        registrarEventoBitacora($conexion, $datosBitacora);
                    }

                    $productoInteres = obtenerProductoPorId($conexion, $turnoAtender['ID_Producto_Interes']);
                }
            }
            
            $productos = obtenerProductosPorAsesor($conexion, $idPersonal);
            include __DIR__ . '/../View/asesor/Tab_Amortizacion.php';
        break;

        case 'listarTurnos':
            $idPersonal = $_SESSION['idPersonal'];
            $productos = obtenerProductosPorAsesor($conexion, $idPersonal);
            $turnos = obtenerTurnos($conexion);
            
            foreach ($turnos as &$turno) {
                if ($turno['ID_Estado_Turno'] == 1) {
                    $fechaSolicitud = new DateTime($turno['Fecha_Hora_Solicitud']);
                    $ahora = new DateTime();
                    $intervalo = $fechaSolicitud->diff($ahora);
                    // Calcular el tiempo en minutos
                    $minutosEspera = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;
                    $turno['Tiempo_Espera_Minutos'] = $minutosEspera;
                }

                $cliente = obtenerClientePorDocumento($conexion, $turno['N_Documento_Solicitante']);
                $turno['EsCliente'] = $cliente ? 'Cliente' : 'Nuevo usuario';
            }

            include __DIR__ . '/../View/asesor/turnos.php';
        break;

        case 'Simulador_Cajero':
            $idPersonal = $_SESSION['idPersonal'] ?? null;

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
                exit;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $nDocumento = $data['documento'] ?? null;
            $esRefresco = $data['esRefresco'] ?? false;
            $idRegistroAsesoramientoExistente = $data['idRegistroAsesor'] ?? null;

            if (isset($data['idPersonal'])) {
                $idPersonal = $data['idPersonal'];
            }

            if (!$nDocumento) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Documento no proporcionado.']);
                exit;
            }
            if (!$idPersonal) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'ID de Personal no disponible. Por favor, inicie sesión o contacte a soporte.']);
                exit;
            }

            // 1. Obtener información del cliente
            $cliente = obtenerClientePorDocumento($conexion, $nDocumento);

            if (!$cliente) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Cliente no encontrado.']);
                exit;
            }

            // 2. Obtener el crédito activo del cliente
            $credito = obtenerCreditoActivoPorCliente($conexion, $cliente['ID_Cliente']);

            if (!$credito) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Este cliente no tiene crédito activo.']);
                exit;
            }

            // 3. Obtener las cuotas inicialmente (pueden tener mora desactualizada)
            $cuotas = obtenerCuotasPorCredito($conexion, $credito['ID_Credito']);

            // 4. Recorrer las cuotas para actualizar la mora de las que estén vencidas y no pagadas
            foreach ($cuotas as $cuota) {
                // ID_Estado_Cuota 7 = 'Pagado' (o el ID que uses para cuotas pagadas)
                if ($cuota['ID_Estado_Cuota'] != 7 && strtotime($cuota['Fecha_Vencimiento']) < strtotime(date('Y-m-d'))) {
                    try {
                        // Llamar al Stored Procedure para actualizar la mora de esta cuota
                        $stmt = $conexion->prepare("CALL sp_actualizar_mora_cuota_individual(:idCuota)");
                        $stmt->bindParam(':idCuota', $cuota['ID_CuotaCredito'], PDO::PARAM_INT);
                        $stmt->execute();
                        // Es CRUCIAL cerrar el cursor después de cada CALL para poder hacer más operaciones de DB
                        $stmt->closeCursor();
                    } catch (PDOException $e) {
                        // Si hay un error al actualizar la mora de una cuota, lo registramos
                        error_log("Error al actualizar mora para cuota " . $cuota['ID_CuotaCredito'] . ": " . $e->getMessage());
                    }
                }
            }

            // 5. Volver a obtener las cuotas para reflejar los cambios realizados por el SP.
            // Esto asegura que los datos enviados al frontend estén completamente actualizados.
            $cuotas = obtenerCuotasPorCredito($conexion, $credito['ID_Credito']);

            // Inicializa el ID de asesoramiento con el valor existente si es un refresco.
            $idRegistroAsesoramiento = $idRegistroAsesoramientoExistente;

            // 6. Registrar Asesoramiento para el cajero (SOLO SI NO ES UN REFRESH)
            // La bitácora también se registra aquí, solo en la consulta inicial.
            if (!$esRefresco) {
                $datosAsesoramiento = [
                    'ID_Personal' => $idPersonal,
                    'ID_Cliente' => $cliente['ID_Cliente'],
                    'ID_Turno' => $idTurno ?? NULL, // Asegúrate de que $idTurno esté definido en tu contexto si es necesario
                    'Fecha_Hora_Inicio' => date('Y-m-d H:i:s'),
                    'Fecha_Hora_Fin' => NULL,
                    'Observaciones' => 'El cajero inició la atención del usuario a través del módulo de de Gestión de Cajero.',
                    'Resultado' => 'Usuario en proceso de pago mediante el sistema de caja.'
                ];

                $idRegistroAsesoramiento = registrarAsesoramiento($conexion, $datosAsesoramiento);

                // 7. Registrar bitácora (SOLO SI ES UNA CONSULTA INICIAL Y SE REGISTRA ASESORAMIENTO)
                if ($idRegistroAsesoramiento) {
                    $datosBitacora = [
                        'ID_Cliente' => $cliente['ID_Cliente'],
                        'ID_Personal' => $idPersonal,
                        'ID_RegistroAsesoramiento' => $idRegistroAsesoramiento,
                        'Tipo_Evento' => 'Inicio de atención en módulo de cajero',
                        'Descripcion_Evento' => 'El usuario fue atendido por el cajero a través del módulo de Gestión de Cajero para gestionar un pago.'
                    ];
                    registrarEventoBitacora($conexion, $datosBitacora);
                }
            }

            // 8. Si todo es exitoso, envía los datos en formato JSON
            header('Content-Type: application/json');
            echo json_encode([
                'exito' => true,
                'cliente' => $cliente,
                'credito' => $credito,
                'cuotas' => $cuotas,
                'idRegistroAsesor' => $idRegistroAsesoramiento
            ]);
            exit;
        break;

        case 'Abonar_Cuota':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
                exit;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $idCuotaCredito = $data['idCuotaCredito'] ?? null;
            $idPersonal = $data['idPersonal'] ?? null;
            $montoPagadoTransaccion = $data['montoPagadoTransaccion'] ?? null;
            $observaciones = $data['observaciones'] ?? '';

            if (!$idCuotaCredito || !$idPersonal || !is_numeric($montoPagadoTransaccion) || $montoPagadoTransaccion <= 0) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Datos de abono incompletos o inválidos.']);
                exit;
            }

            // Iniciar una transacción de base de datos para asegurar la atomicidad
            $conexion->beginTransaction();

            try {
                // 1. Obtener la información actual de la cuota (antes de cualquier actualización de mora)
                $cuotaOriginal = obtenerCuotaPorId($conexion, (int)$idCuotaCredito);

                error_log("DEBUG: Valor de \$cuotaOriginal para ID {$idCuotaCredito}: " . var_export($cuotaOriginal, true));

                if (!$cuotaOriginal) {
                    throw new Exception("Cuota no encontrada.");
                }

                // Validar que la cuota no esté ya pagada
                if ($cuotaOriginal['Estado_Cuota'] === 'Pagado') { // Asumiendo 'Pagado' es el texto del estado
                    throw new Exception("Esta cuota ya ha sido pagada.");
                }

                // --- DEBUG: Mostrar cuota original ---
                error_log("DEBUG ABONO: Cuota Original (ID: {$idCuotaCredito}): " . print_r($cuotaOriginal, true));

                // 2. Llamar al Stored Procedure para ACTUALIZAR LA MORA de esta cuota en la DB.
                // Esto recalcula y guarda Dias_Mora_Al_Pagar y Monto_Recargo_Mora en la tabla CuotaCredito.
                $stmt = $conexion->prepare("CALL sp_actualizar_mora_cuota_individual(:idCuota)");
                $stmt->bindParam(':idCuota', $idCuotaCredito, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor(); // ¡CRUCIAL! Cierra el cursor para liberar el recurso y permitir la siguiente consulta.

                // 3. RE-OBTENER la información de la cuota para tener los valores de mora ACTUALIZADOS por el SP.
                $cuotaActualizadaConMora = obtenerCuotaPorId($conexion, $idCuotaCredito);

                if (!$cuotaActualizadaConMora) {
                    throw new Exception("Error al re-obtener la cuota después de actualizar la mora.");
                }

                // --- DEBUG: Mostrar cuota después de SP y re-obtener ---
                error_log("DEBUG ABONO: Cuota Actualizada con Mora (ID: {$idCuotaCredito}): " . print_r($cuotaActualizadaConMora, true));


                // Calcular el nuevo Monto_Pagado acumulado y determinar el nuevo estado de la cuota
                $nuevoMontoPagadoAcumulado = (float)$cuotaActualizadaConMora['Monto_Pagado'] + (float)$montoPagadoTransaccion;
                $montoTotalCuota = (float)$cuotaActualizadaConMora['Monto_Total_Cuota'];

                $datosCuotaParaActualizar = [
                    'Monto_Pagado' => $nuevoMontoPagadoAcumulado,
                    // Ahora usamos los valores de mora que ya fueron actualizados por el SP y re-obtenidos
                    'Dias_Mora_Al_Pagar' => $cuotaActualizadaConMora['Dias_Mora_Al_Pagar'],
                    'Monto_Recargo_Mora' => $cuotaActualizadaConMora['Monto_Recargo_Mora'],
                ];

                // Determinar el nuevo estado de la cuota y la fecha de pago
                if ($nuevoMontoPagadoAcumulado >= $montoTotalCuota) {
                    $datosCuotaParaActualizar['ID_Estado_Cuota'] = 7; // Asumiendo 7 es el ID para 'Pagado'
                    $datosCuotaParaActualizar['Fecha_Pago'] = date('Y-m-d H:i:s'); // Fecha de pago completa
                } else {
                    // Abono parcial: el estado de la cuota se mantiene (ej. 'Pendiente', 'Mora')
                    $datosCuotaParaActualizar['ID_Estado_Cuota'] = $cuotaActualizadaConMora['ID_Estado_Cuota']; // Mantener estado actual
                    $datosCuotaParaActualizar['Fecha_Pago'] = null; // No se ha pagado completamente
                }

                // --- DEBUG: Mostrar datos que se enviarán a actualizarCuotaCredito ---
                error_log("DEBUG ABONO: Datos para actualizarCuotaCredito (ID: {$idCuotaCredito}): " . print_r($datosCuotaParaActualizar, true));

                // 4. Registrar el pago en la tabla pagoCuota
                $datosPago = [
                    'ID_CuotaCredito' => $idCuotaCredito,
                    'ID_Personal' => $idPersonal,
                    'Monto_Pagado_Transaccion' => $montoPagadoTransaccion,
                    'ID_Estado_Pago' => 7, // Asumiendo 7 es el ID_Estado para 'Pagado' de la transacción de pago
                    'Observaciones_Pago' => $observaciones
                ];
                $exitoRegistroPago = registrarPagoCuota($conexion, $datosPago);

                if (!$exitoRegistroPago) {
                    throw new Exception("Error al registrar el pago.");
                }

                // 5. Actualizar la tabla CuotaCredito con los datos completos
                $exitoActualizacionCuota = actualizarCuotaCredito($conexion, $idCuotaCredito, $datosCuotaParaActualizar);

                if (!$exitoActualizacionCuota) {
                    throw new Exception("Error al actualizar la cuota.");
                }

                $idReAsesoramiento = $_GET['idReAsesoramiento'] ?? null;
                $idCliente = $_GET['ID_Cliente'] ?? null;

                $datosBitacora = [
                    'ID_Cliente' => $idCliente,
                    'ID_Personal' => $idPersonal,
                    'ID_RegistroAsesoramiento' => $idReAsesoramiento,
                    'Tipo_Evento' => 'Pago finalizado por el cliente',
                    'Descripcion_Evento' => 'El cliente ha completado satisfactoriamente el proceso de pago y formalización en el sistema.'
                ];

                registrarEventoBitacora($conexion, $datosBitacora);


                $datosAsesoramiento = [
                    'Fecha_Hora_Fin' => date('Y-m-d H:i:s'),
                    'Observaciones' => 'El cajero finalizó el proceso de atención tras la confirmación del pago.',
                    'Resultado' => 'Cliente validado y pago efectuado exitosamente'
                ];

                $idRegistroAsesoramiento = actualizarRegistroAsesoramiento($conexion, (int)$idReAsesoramiento, $datosAsesoramiento);

                $conexion->commit();
                header('Content-Type: application/json');
                echo json_encode(['exito' => true, 'mensaje' => 'Abono registrado y cuota actualizada con éxito.']);
                exit;

            } catch (Exception $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de cualquier error
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
                exit;
            }
        break;

        case 'Generar_Comprobante_Pago':
            $idCuota = $_GET['idCuota'] ?? null;

            if (!$idCuota) {
                exit('ID de cuota no proporcionado.');
            }

            $datosComprobante = obtenerDatosComprobantePago($conexion, (int)$idCuota);

            if (!$datosComprobante) {
                exit('Datos del comprobante no encontrados.');
            }
           
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $qrTexto = "Cuota: {$datosComprobante['Numero_Cuota']} - Cliente: {$datosComprobante['Nombre_Cliente']} {$datosComprobante['Apellido_Cliente']} - Documento: {$datosComprobante['N_Documento_Cliente']} - Monto: $" . number_format($datosComprobante['Monto_Pagado_Transaccion'], 2, ',', '.') . " - Fecha: {$datosComprobante['Fecha_Hora_Pago']}";

            $logoPath = __DIR__ . '../../View/public/assets/Img/logos/logo2.png';
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoBase64;

            $proxCuota = obtenerProximaCuota($conexion, $datosComprobante['ID_Credito'], $datosComprobante['Numero_Cuota']);

           $html = '
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
                    h1 { text-align: center; color: #2a2a2a; margin-bottom: 20px; }
                    h3 { margin-top: 25px; color: #1a1a1a; }
                    .info, .resumen, .footer { margin-bottom: 15px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                    th, td { border: 1px solid #999; padding: 8px; text-align: left; }
                    th { background-color: #f0f0f0; }
                    .highlight { font-weight: bold; color: #1a73e8; }
                    .footer { text-align: center; font-size: 11px; margin-top: 30px; }
                    .logo-footer, .qr { margin-top: 30px; text-align: center; }
                    .logo-footer img { width: 150px; opacity: 0.9; }
                    .qr img { width: 100px; }
                    .firma { margin-top: 40px; text-align: right; font-style: italic; font-size: 12px; }
                    .referencia { text-align: right; font-size: 11px; color: #666; }
                </style>

                <h1>Comprobante de Pago</h1>

                <div class="referencia">
                    <strong>Referencia:</strong> CP-' . str_pad($datosComprobante['ID_CuotaCredito'], 6, '0', STR_PAD_LEFT) . '
                </div>

                <div class="info">
                    <table>
                        <tr><th>Cliente</th><td>' . $datosComprobante['Nombre_Cliente'] . ' ' . $datosComprobante['Apellido_Cliente'] . '</td></tr>
                        <tr><th>Documento</th><td>' . $datosComprobante['N_Documento_Cliente'] . '</td></tr>
                        <tr><th>Atendido por</th><td>' . $datosComprobante['Nombre_Personal'] . ' ' . $datosComprobante['Apellido_Personal'] . '</td></tr>
                        <tr><th>Fecha y hora del pago</th><td>' . $datosComprobante['Fecha_Hora_Pago'] . '</td></tr>
                    </table>
                </div>

                <div class="resumen">
                    <h3>Detalles del Pago</h3>
                    <table>
                        <tr>
                            <th>Número de Cuota</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Valor Cuota</th>
                            <th>Capital</th>
                            <th>Intereses</th>
                            <th>Mora</th>
                            <th>Monto Pagado</th>
                            <th>Estado</th>
                        </tr>
                        <tr>
                            <td>' . $datosComprobante['Numero_Cuota'] . '</td>
                            <td>' . $datosComprobante['Fecha_Vencimiento'] . '</td>
                            <td>$' . number_format($datosComprobante['Monto_Total_Cuota'], 2, ',', '.') . '</td>
                            <td>$' . number_format($datosComprobante['Monto_Capital'], 2, ',', '.') . '</td>
                            <td>$' . number_format($datosComprobante['Monto_Interes'], 2, ',', '.') . '</td>
                            <td>$' . number_format($datosComprobante['Monto_Recargo_Mora'], 2, ',', '.') . '</td>
                            <td class="highlight">$' . number_format($datosComprobante['Monto_Pagado_Transaccion'], 2, ',', '.') . '</td>
                            <td>' . $datosComprobante['Estado_Cuota_Nombre'] . '</td>
                        </tr>
                    </table>
                </div>

                <div class="info">
                    <h3>Información del Crédito</h3>
                    <table>
                        <tr><th>Monto Total del Crédito</th><td>$' . number_format($datosComprobante['Monto_Total_Credito'], 2, ',', '.') . '</td></tr>
                    </table>
                </div>

                <div class="info">
                    <h3>Próxima Cuota (Estimación)</h3>
                    <table>
                        <tr>
                            <th>Número</th>
                            <th>Fecha Proximo Pago</th>
                            <th>Valor Cuota</th>
                        </tr>
                        <tr>
                            <td>' . ($datosComprobante['Numero_Cuota'] + 1) . '</td>
                            <td>' . ($proxCuota['Fecha_Vencimiento'] ?? 'N/A') . '</td>
                            <td>' . number_format($proxCuota['Monto_Total_Cuota'] ?? 0, 2, ',', '.') . '</td>
                        </tr>
                    </table>
                    <small>* Esta información es estimada y puede variar según el plan de amortización.</small>
                </div>

                <div class="info">
                    <h3>Observaciones</h3>
                    <p>' . ($datosComprobante['Observaciones_Pago'] ?: 'Ninguna') . '</p>
                </div>

                <div class="firma">
                    ___________________________________________<br>
                    ' . $datosComprobante['Nombre_Personal'] . ' ' . $datosComprobante['Apellido_Personal'] . '<br>
                    Cajero - Banco Finan-CIAS
                </div>

                <div class="qr">
                    <p><strong>Escanea para validar:</strong></p>
                </div>

                <div class="footer">
                    Gracias por su pago. Este documento ha sido generado automáticamente por el sistema Finan-CIAS.
                </div>

                <div class="logo-footer">
                </div>
                ';


            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Comprobante_Pago_Cuota_{$idCuota}.pdf", ["Attachment" => false]);
            exit;
        break; 

        case 'Aprobar_Desembolso':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
                exit;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $idCliente = $data['idCliente'] ?? null;
            $idCredito = $data['idCredito'] ?? null;
            $idPersonal = $data['idPersonal'] ?? null;
            $montoDesembolsar = $data['montoDesembolsar'] ?? null;
            $estadoDesembolso = 'Desembolsado';

            if (!$idCliente || !$idCredito || !$idPersonal || !is_numeric($montoDesembolsar) || $montoDesembolsar <= 0) {
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => 'Datos de desembolso incompletos o inválidos.']);
                exit;
            }

            $conexion->beginTransaction();

            try {
                $exitoActualizacionCredito = actualizarEstadoCredito($conexion, (int)$idCredito, $estadoDesembolso);

                if (!$exitoActualizacionCredito) {
                    throw new Exception("Error al actualizar el estado del crédito.");
                }
                
                $datosBitacora = [
                    'ID_Cliente' => $idCliente,
                    'ID_Personal' => $idPersonal,
                    'Tipo_Evento' => 'Crédito Desembolsado',
                    'Descripcion_Evento' => "Se ha aprobado y desembolsado el crédito por $" . number_format($montoDesembolsar, 2, ',', '.') . " al cliente."
                ];
                registrarEventoBitacora($conexion, $datosBitacora);

                $conexion->commit();
                header('Content-Type: application/json');
                echo json_encode(['exito' => true, 'mensaje' => 'Desembolso aprobado y registrado con éxito.']);
                exit;

            } catch (Exception $e) {
                $conexion->rollBack();
                header('Content-Type: application/json');
                echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
                exit;
            }
        break;

         case 'agregarAsesor':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Iniciar una transacción para asegurar la atomicidad
                    $conexion->beginTransaction();

                    // --- 1. Preparar datos del Asesor ---
                    $datosAsesorParaDB = [
                        'Nombre_Personal' => $_POST['nombre'] ?? '',
                        'Apellido_Personal' => $_POST['apellido'] ?? '',
                        'ID_Genero' => $_POST['genero'] ?? '',
                        'ID_TD' => $_POST['tipo_documento'] ?? '',
                        'N_Documento_Personal' => $_POST['documento'] ?? '',
                        'Celular_Personal' => $_POST['celular'] ?? '',
                        'Correo_Personal' => $_POST['correo'] ?? '',
                        'Contraseña_Personal' => $_POST['contrasena'] ?? '', // Se hashea dentro de registrarPersonal
                        'ID_Rol' => $_POST['idRol'] ?? 3, // Valor por defecto si no se selecciona o no es gerente
                        'Activo_Personal' => 1, // Asume que el asesor se crea activo
                        'Fecha_Creacion_Personal' => date('Y-m-d H:i:s'),
                        'Foto_Perfil_Personal' => null // Puedes manejar esto si tienes carga de fotos
                    ];

                    // Validación básica de campos obligatorios para Personal
                    if (empty($datosAsesorParaDB['Nombre_Personal']) || empty($datosAsesorParaDB['Apellido_Personal']) ||
                        empty($datosAsesorParaDB['ID_TD']) || empty($datosAsesorParaDB['N_Documento_Personal']) ||
                        empty($datosAsesorParaDB['Correo_Personal']) || empty($datosAsesorParaDB['Contraseña_Personal'])) {
                        throw new Exception("Faltan campos obligatorios para registrar el asesor.");
                    }

                    // Llamar a tu función para registrar el personal (asesor)
                    $resultadoPersonal = registrarPersonal($conexion, $datosAsesorParaDB); 
                    if (!$resultadoPersonal) {
                        throw new Exception("Error al registrar el asesor. El documento o correo ya podría estar en uso.");
                    }
                    $idPersonal = $conexion->lastInsertId(); // Obtener el ID del asesor recién insertado

                    // --- 2. Procesar Productos Asociados ---
                    // $_POST['productos_asociados'] será un array de IDs si se seleccionaron, o null si no
                    $productosAsociadosJSON = $_POST['productos_asociados'] ?? '[]';
                    $productosAsociados = json_decode($productosAsociadosJSON, true); // Decodificar a un array de PHP

                    if (!is_array($productosAsociados)) {
                        $productosAsociados = []; // Asegurarse de que sea un array válido
                    }

                    // Llamar a la nueva función del modelo para asociar productos
                    // Puedes pasar una descripción o estado si lo necesitas, aquí se usan los valores por defecto
                    $exitoAsociacion = asociarAsesorProducto($conexion, $idPersonal, $productosAsociados);

                    if (!$exitoAsociacion) {
                        throw new Exception("Error al asociar productos al asesor.");
                    }

                    // Confirmar la transacción si todo fue exitoso
                    $conexion->commit();

                    // Redirigir con mensaje de éxito
                    header('Location: ' . BASE_URL . '/View/asesor/RegistroAsesor.php?success=success&msg=' . urlencode('Asesor registrado y productos asociados exitosamente.'));
                    exit;

                } else {
                    throw new Exception("Método de solicitud no permitido.");
                }
            } catch (PDOException $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error de DB
                $errorMessage = "Error de base de datos: " . $e->getMessage();
                if ($e->getCode() == '23000') { // Violación de restricción de unicidad (ej. documento o correo duplicado)
                    $errorMessage = "Error: El número de documento o correo electrónico ya está registrado.";
                }
                error_log("Error en 'agregarAsesor' (DB): " . $e->getMessage());
                header('Location: ' . BASE_URL . '/View/asesor/RegistroAsesor.php?error=error&msg=' . urlencode($errorMessage));
                exit;
            } catch (Exception $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error general
                $errorMessage = "Error inesperado: " . $e->getMessage();
                error_log("Error en 'agregarAsesor' (general): " . $e->getMessage());
                header('Location: ' . BASE_URL . '/View/asesor/RegistroAsesor.php?error=error&msg=' . urlencode($errorMessage));
                exit;
            }
        break;

        case 'registrarTurno':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nombreCompleto = $_POST['nombre_completo_solicitante'] ?? '';
                    $nDocumento = $_POST['n_documento_solicitante'] ?? '';
                    $numeroTurno = $_POST['n_Turno'] ?? '';
                    $fecha_Solicitud = $_POST['fechaSolicitud'] ?? '';
                    $productoInteresId = $_POST['producto_interes'] ?? '';

                    if (empty($nombreCompleto) || empty($nDocumento)) {
                        $mensajeError = "El nombre completo y el número de documento son obligatorios para registrar un turno.";
                        header("Location: /Proyecto_GB/View/asesor/productosyservicios.php?error=error&msg=" . urlencode($mensajeError));
                        exit;
                    }

                    $turnoData = registrarTurno($conexion, $nombreCompleto, $nDocumento, $productoInteresId);

                    if ($turnoData) {
                        // Si el registro fue exitoso y obtuvimos los datos del turno
                        $mensajeExito = "¡Tu turno ha sido generado exitosamente!";

                        // Preparamos los parámetros para la URL usando los datos REALES del turno insertado
                        $params = [
                            'success' => 'success',
                            'msg' => $mensajeExito,
                            'n_documento' => $turnoData['N_Documento_Solicitante'],
                            'n_turno' => $turnoData['Numero_Turno'],
                            'nombre_completo' => $turnoData['Nombre_Completo_Solicitante'],
                            'fecha_solicitud' => $turnoData['Fecha_Hora_Solicitud'],
                            'producto_interes' => $turnoData['ID_Producto_Interes'] 
                        ];

                        $queryString = http_build_query($params);
                        header("Location: /Proyecto_GB/View/asesor/productosyservicios.php?" . $queryString);
                        exit;
                    } else {
                        $mensajeError = "Error al registrar el turno. Verifique los datos o la configuración del estado 'En Espera'.";
                        header("Location: /Proyecto_GB/View/asesor/productosyservicios.php?error=error&msg=" . urlencode($mensajeError));
                        exit;
                    }
                }
            } catch (PDOException $e) {
                $mensajeError = "Error de base de datos al registrar turno: " . $e->getMessage();
                error_log("Error en registrarTurno: " . $e->getMessage());
                header("Location: /Proyecto_GB/View/asesor/productosyservicios.php?error=error&msg=" . urlencode($mensajeError));
                exit;
            } catch (Exception $e) {
                $mensajeError = "Error inesperado al registrar turno: " . $e->getMessage();
                error_log("Error en registrarTurno (general): " . $e->getMessage());
                header("Location: /Proyecto_GB/View/asesor/productosyservicios.php?error=error&msg=" . urlencode($mensajeError));
                exit;
            }
        break;

        case 'consolidadoCliente':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
                    // Leer el cuerpo de la solicitud JSON
                    $input = file_get_contents('php://input');
                    file_put_contents("debug_json.txt", $input);
                    $data = json_decode($input, true); // Decodificar a un array asociativo

                    // Validar que el JSON se decodificó correctamente y que los datos esperados existen
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
                    }
                    if (!isset($data['cliente']) || !isset($data['simulacion']) || !isset($data['producto'])) {
                        throw new Exception("Datos de cliente, simulación o producto incompletos en la solicitud.");
                    }

                    // Extraer los datos de cada sección del JSON
                    $datosClienteJSON = $data['cliente'];
                    $datosSimulacionJSON = $data['simulacion'];
                    $datosProductoJSON = $data['producto'];

                    // Iniciar una transacción para asegurar la atomicidad
                    $conexion->beginTransaction();

                    // --- 1. Registrar Cliente ---
                    // Mapear los datos del JSON a los campos de la tabla 'Cliente'
                    $datosClienteParaDB = [
                        'Nombre_Cliente' => $datosClienteJSON['nombre'] ?? '',
                        'Apellido_Cliente' => $datosClienteJSON['apellido'] ?? '',
                        // Aquí debes asegurarte de que 'genero' y 'tipo_documento' sean los IDs correctos para tu DB.
                        // Si el JS envía el texto (ej. "Cédula de Ciudadanía"), necesitarás funciones como getGeneroId/getTipoDocumentoId.
                        // Si el JS ya envía el ID numérico, usa $datosClienteJSON['genero'] y $datosClienteJSON['tipoDocumento'].
                        'ID_Genero' => $datosClienteJSON['genero'] ?? '', // <--- Implementa esta función si 'genero' es texto
                        'ID_TD' => $datosClienteJSON['tipo_documento'] ?? '', // <--- Implementa esta función si 'tipo_documento' es texto
                        'N_Documento_Cliente' => $datosClienteJSON['documento'] ?? '',
                        'Celular_Cliente' => $datosClienteJSON['celular'] ?? '',
                        'Correo_Cliente' => $datosClienteJSON['correo'] ?? '',
                        'Direccion_Cliente' => $datosClienteJSON['direccion'] ?? '',
                        'Ciudad_Cliente' => $datosClienteJSON['ciudad'] ?? '',
                        'Fecha_Nacimiento_Cliente' => $datosClienteJSON['fecha_nacimiento'] ?? '', // Coincide con 'fechaNacimiento' del JS
                        'ID_Personal_Creador' => $_SESSION['idPersonal'] ?? null,
                        'Fecha_Creacion_Cliente' => date('Y-m-d H:i:s'), // Fecha actual de creación
                        'Estado_Cliente' => 1, // Asume un ID de estado inicial para el cliente (ej. Activo)
                        'Contraseña' => password_hash($datosClienteJSON['contrasena'] ?? 'default_pass_hash', PASSWORD_DEFAULT), // Siempre hashear
                    ];

                    // Validación básica de campos obligatorios para Cliente
                    if (empty($datosClienteParaDB['Nombre_Cliente']) || empty($datosClienteParaDB['Apellido_Cliente']) ||
                        empty($datosClienteParaDB['ID_TD']) || empty($datosClienteParaDB['N_Documento_Cliente']) ||
                        empty($datosClienteParaDB['Correo_Cliente']) || empty($datosClienteJSON['contrasena'])) {
                        throw new Exception("Faltan campos obligatorios para registrar el cliente.");
                    }

                    // Llamar a tu función para registrar el cliente
                    $resultadoCliente = registrarCliente($conexion, $datosClienteParaDB);
                    if (!$resultadoCliente) {
                        throw new Exception("Error al registrar el cliente.");
                    }
                    $idCliente = $conexion->lastInsertId(); // Obtener el ID del cliente recién insertado

                    // --- 2. Registrar Crédito ---
                    // Mapear los datos del JSON a los campos de la tabla 'Credito'
                    $idProducto = $datosProductoJSON['id_producto'] ?? null;
                    $montoPrestamo = floatval($datosSimulacionJSON['monto'] ?? 0);
                    $numeroCuotas = intval($datosSimulacionJSON['cuotas'] ?? 0);
                    $periodicidadTexto = $datosSimulacionJSON['periodicidad'] ?? '';
                    $tasaInteresAnual = floatval($datosSimulacionJSON['tasaAnual'] ?? 0); // Viene en decimal del JS
                    $tasaInteresPeriodica = floatval($datosSimulacionJSON['tasaPeriodica'] ?? 0); // Viene en decimal del JS
                    $valorCuota = floatval($datosSimulacionJSON['valorCuota'] ?? 0); // Viene ya calculado del JS

                    // Calcular la fecha de vencimiento del crédito general (tu lógica existente)
                    $periodoMesesParaVencimiento = 0;
                    switch ($periodicidadTexto) {
                        case "Diaria": $periodoMesesParaVencimiento = ceil($numeroCuotas / 30); break;
                        case "Mensual": $periodoMesesParaVencimiento = $numeroCuotas; break;
                        case "Bimensual": $periodoMesesParaVencimiento = $numeroCuotas * 2; break;
                        case "Trimestral": $periodoMesesParaVencimiento = $numeroCuotas * 3; break;
                        case "Semestral": $periodoMesesParaVencimiento = $numeroCuotas * 6; break;
                        case "Anual": $periodoMesesParaVencimiento = $numeroCuotas * 12; break;
                        default: $periodoMesesParaVencimiento = $numeroCuotas; break;
                    }
                    $fechaVencimientoCredito = date('Y-m-d', strtotime("+$periodoMesesParaVencimiento months"));
                    $idEstadoCreditoActivo = 4; // ID para 'Activo' (confirma tu valor)

                    $datosCreditoParaDB = [
                        'ID_Cliente' => $idCliente,
                        'Monto_Total_Credito' => $montoPrestamo,
                        'Desembolso' => 'No desembolsado',
                        'Monto_Pendiente_Credito' => $montoPrestamo, // Al inicio, el monto total
                        'Fecha_Apertura_Credito' => date('Y-m-d H:i:s'), // Fecha actual de apertura
                        'Fecha_Vencimiento_Credito' => $fechaVencimientoCredito,
                        'ID_Producto' => $idProducto,
                        'ID_Estado' => $idEstadoCreditoActivo,
                        'Tasa_Interes_Anual' => $tasaInteresAnual, // Multiplicar por 100 si tu DB guarda el %
                        'Tasa_Interes_Periodica' => $tasaInteresPeriodica * 100, // Multiplicar por 100 si tu DB guarda el %
                        'Numero_Cuotas' => $numeroCuotas,
                        'Valor_Cuota_Calculado' => $valorCuota,
                        'Periodicidad' => $periodicidadTexto,
                    ];

                    // Validación básica de campos obligatorios para Crédito
                    if (empty($idProducto) || $montoPrestamo <= 0 || $numeroCuotas <= 0 || $valorCuota <= 0) {
                        throw new Exception("Faltan campos obligatorios o valores inválidos para registrar el crédito.");
                    }

                    // Llamar a tu función para registrar el crédito
                    $resultadoCredito = registrarCredito($conexion, $datosCreditoParaDB);
                    if (!$resultadoCredito) {
                        throw new Exception("Error al registrar el crédito.");
                    }
                    $idCredito = $conexion->lastInsertId(); // Obtener el ID del crédito

                    // --- 3. Generar y Registrar Cuotas de Amortización (tabla CuotaCliente) ---
                    $idEstadoCuotaPendiente = 14; // ID para 'Pendiente' (confirma tu valor)
                    $saldoActual = $montoPrestamo;
                    $fechaInicioCredito = date('Y-m-d'); // Fecha de inicio para calcular vencimientos de cuotas

                    for ($i = 1; $i <= $numeroCuotas; $i++) {
                        $interesCuota = $saldoActual * $tasaInteresPeriodica;
                        $abonoCapitalCuota = $valorCuota - $interesCuota;
                        $saldoActual -= $abonoCapitalCuota;

                        // Ajuste para la última cuota para evitar errores de redondeo y asegurar saldo cero
                        if ($i === $numeroCuotas && abs($saldoActual) < 0.01) { // Umbral para considerar "casi cero"
                            $abonoCapitalCuota += $saldoActual;
                            $saldoActual = 0;
                        }

                        // Calcular la fecha de vencimiento para esta cuota específica (tu lógica existente)
                        $fechaVencimientoCuota = '';
                        switch ($periodicidadTexto) {
                            case "Diaria": $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i days", strtotime($fechaInicioCredito))); break;
                            case "Mensual": $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i months", strtotime($fechaInicioCredito))); break;
                            case "Bimensual": $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 2) . " months", strtotime($fechaInicioCredito))); break;
                            case "Trimestral": $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 3) . " months", strtotime($fechaInicioCredito))); break;
                            case "Semestral": $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 6) . " months", strtotime($fechaInicioCredito))); break;
                            case "Anual": $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 12) . " months", strtotime($fechaInicioCredito))); break;
                            default: $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i months", strtotime($fechaInicioCredito))); break;
                        }

                        // Mapear los datos calculados/recibidos a los campos de la tabla 'CuotaCliente'
                        $datosCuotaParaDB = [
                            'ID_Credito' => $idCredito,
                            'Numero_Cuota' => $i,
                            'Monto_Capital' => round($abonoCapitalCuota, 2),
                            'Monto_Interes' => round($interesCuota, 2),
                            'Monto_Total_Cuota' => round($valorCuota, 2),
                            'Fecha_Vencimiento' => $fechaVencimientoCuota,
                            'Fecha_Pago' => null, // Nulo inicialmente, se llena al pagar
                            'Monto_Pagado' => 0.00, // Cero inicialmente
                            'Dias_Mora_Al_Pagar' => 0, // Cero inicialmente
                            'Monto_Recargo_Mora' => 0.00, // Cero inicialmente
                            'ID_Estado_Cuota' => $idEstadoCuotaPendiente,
                        ];

                        // Llamar a tu función para registrar cada cuota
                        $resultadoCuota = registrarCuotaCredito($conexion, $datosCuotaParaDB);
                        if (!$resultadoCuota) {
                            throw new Exception("Error al registrar la cuota " . $i . " del crédito.");
                        }
                    }

                    // --- 4. Actualizar el estado del turno a 3 ---
                    $idTurnoActual = $_GET['idTurno'] ?? null;
                    $idReAsesoramiento = $_GET['idReAsesoramiento'] ?? null;

                    if ($idTurnoActual !== null && $idTurnoActual !== '') {
                        $nuevoEstadoTurno = 3; 

                        $datosParaActualizarTurno = [
                            'ID_Cliente' => $idCliente,
                            'ID_Estado_Turno' => $nuevoEstadoTurno,
                            'Fecha_Hora_Finalizacion' => date('Y-m-d H:i:s')
                        ];

                        $resultadoUpdateTurno = actualizarTurno($conexion, (int)$idTurnoActual, $datosParaActualizarTurno);

                        if ($idReAsesoramiento !== null && $idReAsesoramiento !== '') {

                            $datosAsesoramiento = [
                                'ID_Cliente' => $idCliente,
                                'Fecha_Hora_Fin' => date('Y-m-d H:i:s'),
                                'Observaciones' => 'Registro del usuario finalizado por el asesor desde el módulo de turnos.',
                                'Resultado' => 'Proceso de vinculación al sistema finalizado correctamente'
                            ];

                            $resultadoActualizacionAsesoramiento = actualizarRegistroAsesoramiento($conexion, (int)$idReAsesoramiento, $datosAsesoramiento);

                            if (!$resultadoActualizacionAsesoramiento) {
                                error_log("Error al actualizar el registro de asesoramiento ID: " . $idReAsesoramiento);
                            }

                            if ($resultadoUpdateTurno && $resultadoActualizacionAsesoramiento) {
                                $datosBitacora = [
                                    'ID_Cliente' => $idCliente,
                                    'ID_Personal' => $_SESSION['idPersonal'],
                                    'ID_RegistroAsesoramiento' => (int)$idReAsesoramiento,
                                    'Tipo_Evento' => 'Formalización de cliente',
                                    'Descripcion_Evento' => 'El usuario ha finalizado el proceso de aprobación y se vinculó oficialmente como cliente.'
                                ];
                                registrarEventoBitacora($conexion, $datosBitacora);
                            }
                        } else {
                            error_log("Advertencia: Se intentó actualizar un turno (ID: " . $idTurnoActual . "), pero el ID_RegistroAsesoramiento no fue proporcionado para su actualización.");
                        }

                        if (!$resultadoUpdateTurno) {
                            error_log("Advertencia: No se pudo actualizar el estado del turno ID: " . $idTurnoActual . ". El proceso de crédito continuó.");
                        }

                    } else {
                        // Registrar Asesoramiento Cuando sea por registro directo y no por turno
                        $datosAsesoramiento = [
                            'ID_Personal' => $idPersonal,
                            'ID_Cliente' => $idCliente,
                            'Fecha_Hora_Inicio' => date('Y-m-d H:i:s'),
                            'Fecha_Hora_Fin' => date('Y-m-d H:i:s'),
                            'Observaciones' => 'Ingreso del usuario gestionado directamente por el asesor, sin turno asignado desde el sistema.',
                            'Resultado' => 'Proceso de vinculación al sistema finalizado correctamente'
                        ];

                        $idRegistroAsesoramiento = registrarAsesoramiento($conexion, $datosAsesoramiento);

                        error_log("Información: Nuevo registro de asesoramiento creado directamente, sin turno asociado.");
                    }

                    // Confirmar la transacción si todo fue exitoso
                    $conexion->commit();

                    // Enviar respuesta JSON de éxito al frontend
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Proceso de consolidación completado exitosamente.']);
                    exit;

                } else {
                    throw new Exception("Método de solicitud no permitido.");
                }
            } catch (PDOException $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error de DB
                $errorMessage = "Error de base de datos: " . $e->getMessage();
                if ($e->getCode() == '23000') { // Violación de restricción de unicidad
                    $errorMessage = "Error: El número de documento o correo electrónico del cliente ya está registrado.";
                }
                error_log("Error en 'consolidadoCliente' (DB): " . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errorMessage]);
                exit;
            } catch (Exception $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error general
                $errorMessage = "Error inesperado: " . $e->getMessage();
                error_log("Error en 'consolidadoCliente' (general): " . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errorMessage]);
                exit;
            }
        break;

       case 'verPerfilPersonal':
            $idPersonal = $_SESSION['idPersonal'];

            try {
                $personalData = obtenerPerfilPersonalCompleto($conexion, $idPersonal);

                if ($personalData) {
                    include __DIR__ . '/../View/asesor/perfilAsesor.php'; 
                } else {
                    header('Location: ' . BASE_URL . '/View/public/inicio.php?login=error&error==Perfil de personal no encontrado o inaccesible.');
                    exit();
                }
            } catch (Exception $e) {
                error_log("Error al cargar perfil personal: " . $e->getMessage());
                header('Location: ' . BASE_URL . '/View/public/inicio.php?login=error&error=Error interno al cargar su perfil.');
                exit();
            }
        break;

        default:
            header("Location: /Proyecto_GB/View/public/inicio.php?login=success");
            exit;
        break;
    }
} catch (Exception $e) {
    error_log("Error en Controller de Cliente: " . $e->getMessage());

    $mensajeUsuario = "Ocurrió un error inesperado. Intente más tarde.";
    header("Location: /Proyecto_GB/View/public/inicio.php?login=error&error=" . urlencode($mensajeUsuario));
    exit;
}
?>