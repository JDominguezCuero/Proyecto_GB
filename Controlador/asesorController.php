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

        case 'listarCliente':
            $productos = obtenerProductos($conexion);
            include __DIR__ . '/../View/asesor/CrearCliente.php';
        break;

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

        case 'agregarCliente':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Captura de datos desde el formulario
                    $nombre = $_POST['nombre'] ?? '';
                    $apellido = $_POST['apellido'] ?? '';
                    $idGenero = $_POST['genero'] ?? '';
                    $idTipoDocumento = $_POST['tipo_documento'] ?? '';
                    $documento = $_POST['documento'] ?? '';
                    $celular = $_POST['celular'] ?? '';
                    $correo = $_POST['correo'] ?? '';
                    $direccion = $_POST['direccion'] ?? '';
                    $ciudad = $_POST['ciudad'] ?? '';
                    $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
                    $contrasena = $_POST['contrasena'] ?? '';

                    // IDs quemados (modifica según tu lógica de negocio)
                    $idAsesorAsignado = $_SESSION['usuario'] ?? 1; // Asignado por defecto
                    $idPersonalModificador = 1; // Personal que realiza el registro

                    if ($nombre && $apellido && $idGenero && $idTipoDocumento && $documento && $correo && $contrasena) {
                        $resultado = registrarCliente(
                            $conexion,
                            $nombre,
                            $apellido,
                            $idGenero,
                            $idTipoDocumento,
                            $documento,
                            $celular,
                            $correo,
                            $direccion,
                            $ciudad,
                            $fechaNacimiento,
                            $idAsesorAsignado,
                            $idPersonalModificador,
                            $contrasena
                        );

                        if ($resultado) {
                            $mensaje = "Cliente registrado correctamente.";
                            header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?success=success&msg=" . urlencode($mensaje));
                            exit;
                        } else {
                            $mensajeError = "Error al registrar el cliente.";
                            header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
                        }
                    } else {
                        $mensajeError = "Faltan campos obligatorios.";
                        header("Location: /Proyecto_GB/View/asesor/CrearCliente.php?error=error&msg=" . urlencode($mensajeError));
                    }
                }
            } catch (Exception $e) {
                $mensajeError = "Error inesperado: " . urlencode($e->getMessage());
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
