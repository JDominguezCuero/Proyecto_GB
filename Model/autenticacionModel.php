<?php
// cliente/model.php
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';

function loginCliente($conexion, $usuario, $clave) {
    $sql = "SELECT * FROM cliente WHERE Correo_Cliente = :correo";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':correo', $usuario);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica que exista y que la contraseña sea correcta
    if ($cliente && password_verify($clave, $cliente['Contraseña'])) {
        return $cliente;
    }

    return false; // No coincide
}

/**
 * Autenticación de personal por correo y contraseña.
 */
function loginPersonal($conexion, $correo, $clave) {
    $sql = "SELECT * FROM personal WHERE Correo_Personal = :correo AND Activo = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($clave, $usuario['Contraseña_Personal'])) {
        return $usuario;
    }

    return false;
}

?>