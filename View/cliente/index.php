

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Confi/Conexion.php'; 
require_once 'Model/ClienteModel.php';
require_once 'Controlador/ClienteController.php';


$db_conexion = new Conexion();
$mysqli_conn = $db_conexion->getConexion();

if (!$mysqli_conn || $mysqli_conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. " . $mysqli_conn->connect_error);
}

$controller_name = $_GET['controller'] ?? 'cliente';
$action_name = $_GET['action'] ?? 'index';

$controller_class = ucfirst($controller_name) . 'Controller';

if ($controller_name === 'cliente' && class_exists($controller_class)) {

    $clienteModel = new ClienteModel($mysqli_conn);
    $controller = new $controller_class($clienteModel);

    if (method_exists($controller, $action_name)) {

        if (isset($_GET['id'])) {
            $controller->$action_name((int)$_GET['id']);
        } elseif (isset($_GET['N_Documento_Cliente'])) {
 
            $controller->$action_name(); 
        } else {
            $controller->$action_name();
        }
    } else {
        http_response_code(404);
        echo "Error 404: La acción '" . htmlspecialchars($action_name) . "' no se encontró en el controlador '" . htmlspecialchars($controller_class) . "'.";
    }
} else {
    http_response_code(404);
    echo "Error 404: El controlador '" . htmlspecialchars($controller_name) . "' no se encontró o no está configurado.";
}
    

if (isset($db_conexion)) {
    $db_conexion->closeConexion();
}
?>