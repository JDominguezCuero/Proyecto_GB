<?php
// personal/controller.php
session_start();

require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';
require_once __DIR__ . '/../../Proyecto_GB/Model/asesorModel.php';


// Verifica si hay sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: /Proyecto_GB/View/public/inicio.php?login=error&reason=nologin");
    exit;
}

$accion = $_GET['accion'] ?? 'listar';
$error = "";

try {
    switch ($accion) {
        case 'listar':
            // $personal = obtenerTodoElPersonal($conexion);
            header("Location: /Proyecto_GB/View/asesor/asesorGerente.php?login=success");
        break;

        //Eliminar
        case 'listarCliente':
            $idPersonal = $_SESSION['idPersonal'];
            $idTurno = $_GET['idTurno'] ?? '';

            $turnoAtender = null; // Inicializa la variable

            if ($idTurno) {
                // Consulta la base de datos para obtener el turno específico por su ID
                $turnosEncontrados = obtenerTurnos($conexion, ['ID_Turno' => $idTurno]);
                
                // Si se encontró un turno, tómalo (asumiendo que el ID es único)
                if (!empty($turnosEncontrados)) {
                    $turnoAtender = $turnosEncontrados[0]; // Se toma el primer y único resultado
                }
            }

            $productos = obtenerProductosPorAsesor($conexion, $idPersonal);
            include __DIR__ . '/../View/asesor/CrearCliente.php';
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

        // case 'listarProductos_Servicios':

        //     $listProductos = obtenerProductos($conexion);
        //     include __DIR__ . '/../View/asesor/productosyservicios.php';
        // break;

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

        // case 'agregarCliente':
        //     try {
        //         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //             // Captura de datos desde el formulario
        //             $nombre = $_POST['nombre'] ?? '';
        //             $apellido = $_POST['apellido'] ?? '';
        //             $idGenero = $_POST['genero'] ?? '';
        //             $idTipoDocumento = $_POST['tipo_documento'] ?? '';
        //             $documento = $_POST['documento'] ?? '';
        //             $celular = $_POST['celular'] ?? '';
        //             $correo = $_POST['correo'] ?? '';
        //             $direccion = $_POST['direccion'] ?? '';
        //             $ciudad = $_POST['ciudad'] ?? '';
        //             $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
        //             $contrasena = $_POST['contrasena'] ?? '';

        //             // IDs quemados (modifica según tu lógica de negocio)
        //             $idAsesorAsignado = $_SESSION['usuario'] ?? 1; // Asignado por defecto
        //             $idPersonalModificador = 1; // Personal que realiza el registro

        //             if ($nombre && $apellido && $idGenero && $idTipoDocumento && $documento && $correo && $contrasena) {
        //                 $resultado = registrarCliente(
        //                     $conexion,
        //                     $nombre,
        //                     $apellido,
        //                     $idGenero,
        //                     $idTipoDocumento,
        //                     $documento,
        //                     $celular,
        //                     $correo,
        //                     $direccion,
        //                     $ciudad,
        //                     $fechaNacimiento,
        //                     $idAsesorAsignado,
        //                     $idPersonalModificador,
        //                     $contrasena
        //                 );

        //                 if ($resultado) {
        //                     $mensaje = "Cliente registrado correctamente.";
        //                     header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?success=success&msg=" . urlencode($mensaje));
        //                     exit;
        //                 } else {
        //                     $mensajeError = "Error al registrar el cliente.";
        //                     header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
        //                 }
        //             } else {
        //                 $mensajeError = "Faltan campos obligatorios.";
        //                 header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
        //             }
        //         }
        //     } catch (Exception $e) {
        //         $mensajeError = "Error inesperado: " . urlencode($e->getMessage());
        //         header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
        //         exit;
        //     }
        //     break;

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

        case 'agregarCliente':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // 1. Captura de datos del Cliente
                    $datosCliente = [
                        'Nombre_Cliente' => $_POST['nombre'] ?? '',
                        'Apellido_Cliente' => $_POST['apellido'] ?? '',
                        'ID_Genero' => $_POST['genero'] ?? '',
                        'ID_TD' => $_POST['tipo_documento'] ?? '',
                        'N_Documento_Cliente' => $_POST['documento'] ?? '',
                        'Celular_Cliente' => $_POST['celular'] ?? '',
                        'Correo_Cliente' => $_POST['correo'] ?? '',
                        'Direccion_Cliente' => $_POST['direccion'] ?? '',
                        'Ciudad_Cliente' => $_POST['ciudad'] ?? '',
                        'Fecha_Nacimiento_Cliente' => $_POST['fecha_nacimiento'] ?? '',
                        'Contraseña' => $_POST['contrasena'] ?? '',
                        'ID_Personal_Creador' => $_SESSION['idPersonal'] ?? null,
                    ];

                    // Validación básica de campos obligatorios del cliente
                    if (empty($datosCliente['Nombre_Cliente']) || empty($datosCliente['Apellido_Cliente']) ||
                        empty($datosCliente['ID_Genero']) || empty($datosCliente['ID_TD']) ||
                        empty($datosCliente['N_Documento_Cliente']) || empty($datosCliente['Correo_Cliente']) ||
                        empty($datosCliente['Contraseña'])) {
                        $mensajeError = "Faltan campos obligatorios para registrar el cliente.";
                        header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
                        exit;
                    }

                    // Iniciar una transacción para asegurar la atomicidad de las operaciones
                    $conexion->beginTransaction();

                    // 1. Registrar Cliente
                    $resultadoCliente = registrarCliente($conexion, $datosCliente);
                    if (!$resultadoCliente) {
                        throw new Exception("Error al registrar el cliente.");
                    }
                    $idCliente = $conexion->lastInsertId(); // Obtener el ID del cliente recién insertado

                    // 2. Captura de datos del Crédito desde el formulario
                    $idProducto = $_POST['id_producto'] ?? null; // ID del producto de crédito seleccionado
                    $montoPrestamo = floatval($_POST['monto'] ?? 0);
                    $numeroCuotas = intval($_POST['num_cuotas'] ?? 0);
                    $tasaInteresAnual = floatval(str_replace(',', '.', $_POST['tasa_interes_anual'] ?? '0')); // Tasa anual en %
                    $periodicidadTexto = $_POST['periodicidad'] ?? ''; // 'Mensual', 'Diaria', etc.
                    $tasaInteresPeriodica = floatval(str_replace(',', '.', $_POST['tasa_interes_periodica'] ?? '0')) / 100; // Convertir a decimal
                    $valorCuota = floatval($_POST['valor_cuota'] ?? 0);

                    // Validación básica de campos obligatorios del crédito
                    if (empty($idProducto) || $montoPrestamo <= 0 || $numeroCuotas <= 0 || $tasaInteresAnual <= 0 || empty($periodicidadTexto) || $tasaInteresPeriodica <= 0 || $valorCuota <= 0) {
                        throw new Exception("Faltan campos obligatorios o valores inválidos para registrar el crédito.");
                    }

                    // Calcular fecha de vencimiento del crédito general (aproximado)
                    $periodoMesesParaVencimiento = 0;
                    switch ($periodicidadTexto) {
                        case "Diaria":
                            $periodoMesesParaVencimiento = ceil($numeroCuotas / 30);
                            break;
                        case "Mensual":
                            $periodoMesesParaVencimiento = $numeroCuotas;
                            break;
                        case "Bimensual":
                            $periodoMesesParaVencimiento = $numeroCuotas * 2;
                            break;
                        case "Trimestral":
                            $periodoMesesParaVencimiento = $numeroCuotas * 3;
                            break;
                        case "Semestral":
                            $periodoMesesParaVencimiento = $numeroCuotas * 6;
                            break;
                        case "Anual":
                            $periodoMesesParaVencimiento = $numeroCuotas * 12;
                            break;
                        default:
                            $periodoMesesParaVencimiento = $numeroCuotas; // Por si acaso
                            break;
                    }
                    $fechaVencimientoCredito = date('Y-m-d', strtotime("+$periodoMesesParaVencimiento months"));
                    $idEstadoCreditoActivo = 7; // EJEMPLO: Reemplaza con el ID real de tu estado 'Activo' para créditos

                    $datosCredito = [
                        'ID_Cliente' => $idCliente,
                        'Monto_Total_Credito' => $montoPrestamo,
                        'Monto_Pendiente_Credito' => $montoPrestamo, // Inicialmente el monto total
                        'Fecha_Vencimiento_Credito' => $fechaVencimientoCredito,
                        'ID_Producto' => $idProducto,
                        'ID_Estado' => $idEstadoCreditoActivo,
                        'Tasa_Interes_Anual' => $tasaInteresAnual, // Ya viene en %
                        'Tasa_Interes_Periodica' => $tasaInteresPeriodica * 100, // Guardar en %
                        'Numero_Cuotas' => $numeroCuotas,
                        'Valor_Cuota_Calculado' => $valorCuota, // Ya viene calculado
                        'Periodicidad' => $periodicidadTexto,
                    ];

                    // 2. Registrar Crédito
                    $resultadoCredito = registrarCredito($conexion, $datosCredito);
                    if (!$resultadoCredito) {
                        throw new Exception("Error al registrar el crédito.");
                    }
                    $idCredito = $conexion->lastInsertId(); // Obtener el ID del crédito recién insertado

                    // 3. Generar y Registrar Cuotas de Amortización (tabla cuotacredito)
                    $idEstadoCuotaPendiente = 8; // EJEMPLO: Reemplaza con el ID real de tu estado 'Pendiente' para cuotas
                    $saldoActual = $montoPrestamo;
                    $fechaInicioCredito = date('Y-m-d'); // Fecha de inicio del crédito, usualmente la fecha de hoy

                    for ($i = 1; $i <= $numeroCuotas; $i++) {
                        $interesCuota = $saldoActual * $tasaInteresPeriodica;
                        $abonoCapitalCuota = $valorCuota - $interesCuota;
                        $saldoActual -= $abonoCapitalCuota;

                        // Ajuste para la última cuota para evitar problemas de redondeo y asegurar que el saldo llegue a cero
                        if ($i === $numeroCuotas) {
                            $abonoCapitalCuota = $valorCuota - $interesCuota + $saldoActual; // Sumar el saldo restante
                            $saldoActual = 0;
                        }

                        // Calcular la fecha de vencimiento para esta cuota específica
                        $fechaVencimientoCuota = '';
                        switch ($periodicidadTexto) {
                            case "Diaria":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i days", strtotime($fechaInicioCredito)));
                                break;
                            case "Mensual":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i months", strtotime($fechaInicioCredito)));
                                break;
                            case "Bimensual":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 2) . " months", strtotime($fechaInicioCredito)));
                                break;
                            case "Trimestral":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 3) . " months", strtotime($fechaInicioCredito)));
                                break;
                            case "Semestral":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 6) . " months", strtotime($fechaInicioCredito)));
                                break;
                            case "Anual":
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+" . ($i * 12) . " months", strtotime($fechaInicioCredito)));
                                break;
                            default:
                                $fechaVencimientoCuota = date('Y-m-d', strtotime("+$i months", strtotime($fechaInicioCredito))); // Default a mensual si no se reconoce
                                break;
                        }

                        $datosCuota = [
                            'ID_Credito' => $idCredito,
                            'Numero_Cuota' => $i,
                            'Monto_Capital' => round($abonoCapitalCuota, 2),
                            'Monto_Interes' => round($interesCuota, 2),
                            'Monto_Total_Cuota' => round($valorCuota, 2), // El valor de la cuota es fijo
                            'Fecha_Vencimiento' => $fechaVencimientoCuota,
                            'ID_Estado_Cuota' => $idEstadoCuotaPendiente,
                        ];

                        $resultadoCuota = registrarCuotaCredito($conexion, $datosCuota);
                        if (!$resultadoCuota) {
                            throw new Exception("Error al registrar la cuota " . $i . " del crédito.");
                        }
                    }

                    // Si todo fue exitoso, confirmar la transacción
                    $conexion->commit();
                    $mensaje = "Cliente y crédito registrados correctamente.";
                    header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?success=success&msg=" . urlencode($mensaje));
                    exit;

                }
            } catch (PDOException $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error de DB
                if ($e->getCode() == '23000') {
                    $mensajeError = "Error: El número de documento o correo electrónico del cliente ya está registrado.";
                } else {
                    $mensajeError = "Error de base de datos al registrar cliente/crédito: " . $e->getMessage();
                }
                error_log("Error en agregarCliente (DB): " . $e->getMessage());
                header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
                exit;
            } catch (Exception $e) {
                $conexion->rollBack(); // Revertir la transacción en caso de error general
                $mensajeError = "Error inesperado al registrar cliente/crédito: " . $e->getMessage();
                error_log("Error en agregarCliente (general): " . $e->getMessage());
                header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
                exit;
            }
            break;

        default:
            header("Location: controller.php?accion=listar");
            exit;
    }
} catch (Exception $e) {
    error_log("Error en Controller de Cliente: " . $e->getMessage());

    $mensajeUsuario = "Ocurrió un error inesperado. Intente más tarde.";
    header("Location: /Proyecto_GB/View/asesor/asesorGerente.php?login=error&error=" . urlencode($mensajeUsuario));
    exit;
}
?>