<?php
// Iniciar el búfer de salida para capturar cualquier salida inesperada
ob_start();

session_start();
require_once(__DIR__ . '../../Config/config.php');
require_once(__DIR__ . '../../Model/asesorModel.php');
require_once(__DIR__ . '../../Model/gerenteModel.php');


$accion = $_GET['accion'] ?? '';

if ($accion !== 'listarGestionTotal') {
    // Configurar el reporte de errores para no mostrar advertencias/notices en la salida directa
    // Esto es crucial para las respuestas JSON
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    ini_set('display_errors', 'Off'); // Asegúrate de que no se muestren errores en la salida
    header('Content-Type: application/json');
}

switch ($accion) {
    case 'listarGestionTotal':

        ob_end_clean(); 

        header('Location: ' . BASE_URL . '/View/gerente/gestionTotal.php');
        exit;
        break;

    // --- Acciones existentes para las llamadas AJAX desde gestionTotal.php ---
    case 'getDashboardMetrics':
        try {
            $totalClients = getTotalClientes($conexion);
            $totalActiveCredits = getTotalCreditosActivos($conexion);
            $creditsInMora = getTotalCreditosEnMora($conexion);
            $totalTransactionsAmount = getTotalMontoTransacciones($conexion);

            echo json_encode([
                'exito' => true,
                'metrics' => [
                    'totalClients' => $totalClients,
                    'totalActiveCredits' => $totalActiveCredits,
                    'creditsInMora' => $creditsInMora,
                    'totalTransactionsAmount' => $totalTransactionsAmount
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en getDashboardMetrics: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener métricas: ' . $e->getMessage()]);
        }
        break;

    case 'getRecentTransactions':
        try {
            $recentTransactions = getRecentPagosCuota($conexion, 10);

            echo json_encode([
                'exito' => true,
                'transactions' => $recentTransactions
            ]);
        } catch (Exception $e) {
            error_log("Error en getRecentTransactions: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener transacciones: ' . $e->getMessage()]);
        }
        break;

    case 'getClientDetails':
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $documento = $data['documento'] ?? null;

        if (!$documento) {
            echo json_encode(['exito' => false, 'mensaje' => 'Documento no proporcionado.']);
            exit;
        }

        try {
            $cliente = obtenerClientePorDocumento($conexion, $documento);
            $credito = null;
            if ($cliente) {
                $credito = obtenerCreditoActivoPorCliente($conexion, $cliente['ID_Cliente']);
                if ($credito) {
                    $producto = obtenerProductoPorId($conexion, $credito['ID_Producto']);
                    $estadoCredito = obtenerEstadoPorId($conexion, $credito['ID_Estado']);
                    $credito['NombreProducto'] = $producto['NombreProducto'] ?? 'N/A';
                    $credito['Estado_Credito'] = $estadoCredito['Estado'] ?? 'N/A';
                }
            }

            if ($cliente) {
                echo json_encode(['exito' => true, 'cliente' => $cliente, 'credito' => $credito]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Cliente no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en getClientDetails: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al buscar cliente: ' . $e->getMessage()]);
        }
        break;

    case 'getAdvisorDetails':
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $documento = $data['documento'] ?? null;

        if (!$documento) {
            echo json_encode(['exito' => false, 'mensaje' => 'Documento no proporcionado.']);
            exit;
        }

        try {
            $asesor = obtenerPersonalPorDocumento($conexion, $documento);
            $productosAsociados = [];
            if ($asesor) {
                $rol = obtenerRolPorId($conexion, $asesor['ID_Rol']);
                $asesor['Nombre_Rol'] = $rol['Nombre_Rol'] ?? 'N/A';
                $productosAsociados = obtenerProductosAsociadosAsesor($conexion, $asesor['ID_Personal']);
            }

            if ($asesor) {
                echo json_encode(['exito' => true, 'asesor' => $asesor, 'productosAsociados' => $productosAsociados]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Asesor no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en getAdvisorDetails: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al buscar asesor: ' . $e->getMessage()]);
        }
        break;

    case 'getCreditDetails':
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $idCredito = $data['idCredito'] ?? null;

        if (!$idCredito) {
            echo json_encode(['exito' => false, 'mensaje' => 'ID de Crédito no proporcionado.']);
            exit;
        }

        try {
            $credito = obtenerCreditoPorId($conexion, $idCredito);
            $cuotas = [];
            if ($credito) {
                $cliente = obtenerClientePorId($conexion, $credito['ID_Cliente']);
                $producto = obtenerProductoPorId($conexion, $credito['ID_Producto']);
                $estadoCredito = obtenerEstadoPorId($conexion, $credito['ID_Estado']);

                $credito['Nombre_Cliente'] = $cliente['Nombre_Cliente'] ?? 'N/A';
                $credito['Apellido_Cliente'] = $cliente['Apellido_Cliente'] ?? 'N/A';
                $credito['NombreProducto'] = $producto['NombreProducto'] ?? 'N/A';
                $credito['Estado_Credito'] = $estadoCredito['Estado'] ?? 'N/A';

                $cuotas = obtenerCuotasPorCredito($conexion, $idCredito);
            }

            if ($credito) {
                echo json_encode(['exito' => true, 'credito' => $credito, 'cuotas' => $cuotas]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Crédito no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en getCreditDetails: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al buscar crédito: ' . $e->getMessage()]);
        }
        break;

        case 'getCreditStatusData':
            try {
                $creditStatusCounts = getCreditStatusCounts($conexion); // Implementar en creditoModelo.php

                $labels = [];
                $values = [];
                foreach ($creditStatusCounts as $status) {
                    $labels[] = $status['Estado']; // Asume que el array tiene una clave 'Estado'
                    $values[] = $status['Count'];  // Asume que el array tiene una clave 'Count'
                }

                echo json_encode(['exito' => true, 'data' => ['labels' => $labels, 'values' => $values]]);
            } catch (Exception $e) {
                error_log("Error en getCreditStatusData: " . $e->getMessage());
                echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener datos de estado de créditos: ' . $e->getMessage()]);
            }
        break;

    case 'getNewClientsData':
        try {
            $newClientsPerMonth = getNewClientsPerMonth($conexion); // Implementar en clienteModelo.php

            $labels = [];
            $values = [];
            foreach ($newClientsPerMonth as $monthData) {
                $labels[] = $monthData['Mes']; // Asume que el array tiene una clave 'Mes' (ej. 'Ene 2024')
                $values[] = $monthData['Count']; // Asume que el array tiene una clave 'Count'
            }

            echo json_encode(['exito' => true, 'data' => ['labels' => $labels, 'values' => $values]]);
        } catch (Exception $e) {
            error_log("Error en getNewClientsData: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener datos de nuevos clientes: ' . $e->getMessage()]);
        }
        break;

    case 'getTransactionVolumeData':
        try {
            $transactionVolumePerMonth = getTransactionVolumePerMonth($conexion); // Implementar en pagoModelo.php

            $labels = [];
            $values = [];
            foreach ($transactionVolumePerMonth as $monthData) {
                $labels[] = $monthData['Mes']; // Asume que el array tiene una clave 'Mes'
                $values[] = $monthData['TotalMonto']; // Asume que el array tiene una clave 'TotalMonto'
            }

            echo json_encode(['exito' => true, 'data' => ['labels' => $labels, 'values' => $values]]);
        } catch (Exception $e) {
            error_log("Error en getTransactionVolumeData: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener datos de volumen de transacciones: ' . $e->getMessage()]);
        }
    break;

    case 'listarBitacora':
        try {
            $bitacoraRegistros = obtenerRegistrosBitacora($conexion); 

            echo json_encode(['exito' => true, 'registros' => $bitacoraRegistros]);
        } catch (Exception $e) {
            error_log("Error en listarBitacora: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error al obtener registros de bitácora: ' . $e->getMessage()]);
        }
    break;

    default:
        echo json_encode(['exito' => false, 'mensaje' => 'Acción no válida.']);
        break;
}

// Limpiar el búfer de salida al final del script si no se hizo antes (ej. por redirección)
ob_end_flush();
?>
