<?php
// personal/controller.php
session_start();

require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';
require_once __DIR__ . '/../../Proyecto_GB/Model/asesorModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accionesPublicas = ['crearTurnoPublico', 'verTurnos', 'listarTurnosPublicos']; 

// Si la acción actual está en la lista pública, no validamos sesión
$accion = $_GET['accion'] ?? '';

if (!in_array($accion, $accionesPublicas)) {
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
        case 'listar':
            // $personal = obtenerTodoElPersonal($conexion);
            // header("Location: /Proyecto_GB/View/public/inicio.php?login=success");
        break;

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
            }

            include __DIR__ . '/../View/asesor/turnos.php';
        break;

        //Modificar
        case 'agregarAsesor':
            try{
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nombre = $_POST['nombre'] ?? '';
                    $apellido = $_POST['apellido'] ?? '';
                    $idRol = $_POST['id_rol'] ?? 3;
                    $genero = $_POST['genero'] ?? '';
                    $tipoDocumento = $_POST['tipo_documento'] ?? '';
                    $documento = $_POST['documento'] ?? '';
                    $celular = $_POST['celular'] ?? '';
                    $correo = $_POST['correo'] ?? '';
                    $contrasena = $_POST['contrasena'] ?? '';

                    if ($nombre && $apellido && $tipoDocumento && $documento && $correo && $contrasena) {
                        $resultado = registrarPersonal($conexion, $nombre, $apellido, $idRol, $genero, $tipoDocumento, $documento, $celular, $correo, $contrasena);
                        if ($resultado) {
                            $mensajeError = "Usuario agregado correctamente.";
                            header("Location: /Proyecto_GB/View/asesor/RegistroAsesor.php?success=success&msg=" . urlencode($mensajeError));
                            exit;
                        } else {
                            $mensajeError = "Error al registrar el usuario.";
                            header("Location: /Proyecto_GB/View/asesor/RegistroAsesor.php?error=error&msg=" . urlencode($mensajeError));
                        }
                    } else {
                        $mensajeError = "Faltan campos obligatorios.";
                        header("Location: /Proyecto_GB/View/asesor/RegistroAsesor.php?error=error&msg=" . urlencode($mensajeError));
                    }
                }
            } catch (Exception $e) {
                $mensajeError = "Error inesperado, ". urlencode($e);
                header("Location: /Proyecto_GB/View/asesor/RegistroAsesor.php?error=error&msg=" . urlencode($mensajeError));
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
                    $idEstadoCreditoActivo = 7; // ID para 'Activo' (confirma tu valor)

                    $datosCreditoParaDB = [
                        'ID_Cliente' => $idCliente,
                        'Monto_Total_Credito' => $montoPrestamo,
                        'Monto_Pendiente_Credito' => $montoPrestamo, // Al inicio, el monto total
                        'Fecha_Apertura_Credito' => date('Y-m-d H:i:s'), // Fecha actual de apertura
                        'Fecha_Vencimiento_Credito' => $fechaVencimientoCredito,
                        'ID_Producto' => $idProducto,
                        'ID_Estado' => $idEstadoCreditoActivo,
                        // Estos campos no estaban en tu JSON de 'Credito', pero son parte de la simulación.
                        // Si tu tabla Credito los guarda, asegúrate de que los tipos de datos coincidan (ej. % vs decimal).
                        'Tasa_Interes_Anual' => $tasaInteresAnual * 100, // Multiplicar por 100 si tu DB guarda el %
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
                    $idEstadoCuotaPendiente = 8; // ID para 'Pendiente' (confirma tu valor)
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

                    // --- 4. Actualizar el estado del turno a 10 ---
                    $idTurnoActual = $_GET['idTurno'] ?? null;

                    if ($idTurnoActual !== null && $idTurnoActual !== '') {
                        $nuevoEstadoTurno = 10; 

                        $datosParaActualizarTurno = [
                            'ID_Estado_Turno' => $nuevoEstadoTurno,
                            'Fecha_Hora_Finalizacion' => date('Y-m-d H:i:s')
                        ];

                        $resultadoUpdateTurno = actualizarTurno($conexion, (int)$idTurnoActual, $datosParaActualizarTurno);

                        if (!$resultadoUpdateTurno) {
                            error_log("Advertencia: No se pudo actualizar el estado del turno ID: " . $idTurnoActual . ". El proceso de crédito continuó.");
                        }
                    } else {
                        error_log("Advertencia: ID de turno no disponible para actualizar su estado.");
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

        default:
            header("Location: controller.php?accion=listar");
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