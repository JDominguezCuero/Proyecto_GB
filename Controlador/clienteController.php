<?php
// cliente/controller.php

session_start();

require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';
require_once __DIR__ . '/../../Proyecto_GB/Model/clienteModel.php'; // modelo funcional

if (!isset($_SESSION['usuarioCliente'])) {
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

$accion = $_GET['accion'] ?? 'mostrarPerfilCliente';
$idCliente = $_SESSION['ID_Cliente_Logueado'] ?? null;

try {
    switch ($accion) {

        case 'mostrarPerfilCliente':
            try {
                
                if (!$idCliente) {
                    throw new Exception("No se pudo identificar al cliente en sesión.");
                }

                
                $datos_cliente = obtenerClientePorId($conexion, $idCliente);
                $seccion = 'perfil_cliente';
                $loginStatus = 'success'; // Puedes usar esto como bandera

                include '../../Proyecto_GB/View/cliente/Cliente.php';
                exit;

            } catch (PDOException $e) {
                error_log("Error al obtener el id " . $idCliente . ": " . $e->getMessage());
            }

        break;

        case 'mostrarDatosCliente':
            if (!$idCliente) {
                throw new Exception("No se pudo identificar al cliente.");
            }

            $datos_cliente = obtenerClientePorId($conexion, $idCliente);
            $campos_actualizables = ['Celular_Cliente', 'Correo_Cliente', 'Direccion_Cliente', 'Ciudad_Cliente', 'Contraseña'];
            $seccion = 'actualizar_datos';
            include '../../Proyecto_GB/View/cliente/Cliente.php';
            break;

        case 'mostrarValidacionProductos':
            if (!$idCliente) {
                throw new Exception("No se pudo identificar al cliente.");
            }

            $datos_cliente = obtenerClientePorId($conexion, $idCliente);
            $seccion = 'validacion_productos';
            include '../../Proyecto_GB/View/cliente/Cliente.php';
            break;

        case 'mostrarCertificaciones':
            $n_documento_cliente = $_GET['N_Documento_Cliente'] ?? null;

            if (!$n_documento_cliente) {
                $error_message = "Por favor, ingrese un número de documento para generar el certificado.";
            } else {
                $datos_cliente = obtenerClientePorDocumento($conexion, $n_documento_cliente);

                if ($datos_cliente) {
                    $producto_para_certificado = obtenerProductoPorId($conexion, 1);

                    if (!$producto_para_certificado) {
                        $error_message = "No se encontró el producto para certificar (ID: 1).";
                    }
                } else {
                    $error_message = "Cliente con N° Documento '" . htmlspecialchars($n_documento_cliente) . "' no encontrado.";
                }
            }

            $seccion = 'certificaciones';
            include '../../Proyecto_GB/View/cliente/Cliente.php';
            break;

        case 'actualizarCliente':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_cliente = $_POST['id_cliente'] ?? null;

                if (!$id_cliente) {
                    header("Location: controller.php?accion=mostrarDatosCliente&status=error_id_invalido");
                    exit;
                }

                $datos_a_actualizar = [
                    'Celular_Cliente' => $_POST['celular_cliente'] ?? null,
                    'Correo_Cliente' => $_POST['correo_cliente'] ?? null,
                    'Direccion_Cliente' => $_POST['direccion_cliente'] ?? null,
                    'Ciudad_Cliente' => $_POST['ciudad_cliente'] ?? null,
                ];

                if (!empty($_POST['contrasena'])) {
                    $datos_a_actualizar['Contraseña'] = $_POST['contrasena'];
                }

                $actualizado = actualizarCliente($conexion, $id_cliente, $datos_a_actualizar);

                if ($actualizado) {
                    header("Location: controller.php?accion=mostrarDatosCliente&status=success");
                } else {
                    header("Location: controller.php?accion=mostrarDatosCliente&status=error");
                }
                exit;
            } else {
                header("Location: controller.php?accion=mostrarDatosCliente");
                exit;
            }

        default:
            header("Location: controller.php?accion=mostrarPerfilCliente");
            exit;
    }

} catch (Exception $e) {
    error_log("Error en controller.php (cliente): " . $e->getMessage());
    $mensajeUsuario = "Ocurrió un error inesperado. Intente más tarde.";
    header("Location: /Proyecto_GB/View/autenticacion/Login_Cliente.php?login=error&error=" . urlencode($mensajeUsuario));
    exit;
}
