<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_finan_cias');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '/Proyecto_GB');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $conexion = new PDO($dsn, DB_USER, DB_PASS, $opciones);
    
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>