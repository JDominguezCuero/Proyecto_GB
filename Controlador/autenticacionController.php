<?php
// cliente/controller.php
session_start();
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';
require_once __DIR__ . '/../../Proyecto_GB/Model/autenticacionModel.php';

$accion = $_GET['accion'] ?? 'listar';
$error = "";

try {
    switch ($accion) {   

        case 'loginCliente':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario = $_POST['usuario'] ?? '';
                $clave = $_POST['clave'] ?? '';

                $cliente = loginCliente($conexion, $usuario, $clave);

                if ($cliente) {
                    if ($cliente['Estado_Cliente'] == 'Activo') {

                        $_SESSION['usuarioCliente'] = $usuario;
                        $_SESSION['nombreCliente'] = $cliente['Nombre_Cliente'];
                        $_SESSION['rolCliente'] = 'cliente';
                        $_SESSION['cliente_id'] = $cliente['ID_Cliente'];
                        $_SESSION['ID_Cliente_Logueado'] = $cliente['ID_Cliente'];
                        $id_cliente_default = $cliente['ID_Cliente'];

                        header("Location: /Proyecto_GB/Controlador/clienteController.php?accion=mostrarPerfilCliente&id=" . $id_cliente_default);
                        exit;
                    } else {
                        $mensjError = "El usuario no se encuentra activo, por favor contactese con el administrador";
                        header("Location: /Proyecto_GB/View/autenticacion/Login_Cliente.php?login=error&error=" . urlencode($mensjError));
                        exit;
                    }
                } else {
                    $mensjError = "Usuario o contraseña incorrecta";
                    header("Location: /Proyecto_GB/View/autenticacion/Login_Cliente.php?login=error&error=" . urlencode($mensjError));
                    exit;
                }
            } else {
                $mensjError = "Error con el servidor, contactate con el administrador.";
                header("Location: /Proyecto_GB/View/autenticacion/Login_Cliente.php?login=error&error=" . urlencode($mensjError));
                exit;
            }
        break;
        
        case 'loginAsesor':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario = $_POST['usuario'] ?? '';
                $clave = $_POST['clave'] ?? '';

                $resultado = loginPersonal($conexion, $usuario, $clave);

                if ($resultado) {
                    if ($resultado['Activo_Personal'] == 1) {
                    
                        $_SESSION['idPersonal'] = $resultado['ID_Personal'];
                        $_SESSION['nombre'] = $resultado['Nombre_Personal'];
                        $_SESSION['apellido'] = $resultado['Apellido_Personal'];
                        $_SESSION['usuario'] = $usuario;
                        $_SESSION['rol'] = $resultado['ID_Rol'];

                        header("Location: /Proyecto_GB/View/public/inicio.php?login=success");
                        exit;

                    } else {
                        $mensjError = "El usuario no se encuentra activo, por favor contactese con el administrador";
                        header("Location: /Proyecto_GB/View/autenticacion/Login_Administracion.php?login=error&error=" . urlencode($mensjError));
                        exit;
                    }
                } else {
                    $mensjError = "Usuario o contraseña incorrecta";
                    header("Location: /Proyecto_GB/View/autenticacion/Login_Administracion.php?login=error&error=" . urlencode($mensjError));
                    exit;
                }
            } else {
                $mensjError = "Error con el servidor, contactate con el administrador.";
                header("Location: /Proyecto_GB/View/autenticacion/Login_Administracion.php?login=error&error=" . urlencode($mensjError));
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
    header("Location: /Proyecto_GB/View/cliente/Login_Cliente.php?login=error&error=" . urlencode($mensajeUsuario));
    exit;
}
