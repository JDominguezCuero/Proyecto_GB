<?php
// usuarios/model.php
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';

/**
 * Obtener personal por ID.
 */
function obtenerPersonalPorId($conexion, $id) {
    $sql = "SELECT * FROM personal WHERE id_personal = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtener todos los usuarios internos.
 */
function obtenerTodoElPersonal($conexion) {
    $sql = "SELECT p.*, r.nombre_rol 
            FROM personal p
            LEFT JOIN roles r ON p.ID_Rol = r.id_rol
            ORDER BY p.Nombre ASC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Registrar nuevo personal.
 */
function registrarPersonal($conexion, $nombre, $apellido, $idRol, $genero, $tipoDocumento, $documento, $celular, $correo, $contrasena, $activo = 1) {
    $sql = "INSERT INTO personal (Nombre_Personal, Apellido_Personal, ID_Rol, ID_Genero, ID_TD, N_Documento_Personal, Celular_Personal, Correo_Personal, Contraseña_Personal, Activo)
            VALUES (:nombre, :apellido, :idRol, :genero, :tipoDocumento, :documento, :celular, :correo, :contrasena, :activo)";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':idRol' => $idRol,
        ':genero' => $genero,
        ':tipoDocumento' => $tipoDocumento,
        ':documento' => $documento,
        ':celular' => $celular,
        ':correo' => $correo,
        ':contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
        ':activo' => $activo
    ]);
}

function registrarCliente($conexion, $nombre, $apellido, $idGenero, $idTipoDocumento, $documento, $celular, $correo, $direccion, $ciudad, $fechaNacimiento, $idAsesorAsignado, $idPersonalModificador, $contrasena, $activo = 1) {
    $sql = "INSERT INTO cliente (
                Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, 
                N_Documento_Cliente, Celular_Cliente, Correo_Cliente, 
                Direccion_Cliente, Ciudad_Cliente, Fecha_Nacimiento_Cliente, 
                ID_Asesor_Asignado, Fecha_Creacion_Cliente, Activo_Cliente, 
                Fecha_Modificacion_Cliente, ID_Personal_Modificador, Contraseña
            ) VALUES (
                :nombre, :apellido, :idGenero, :idTipoDocumento, 
                :documento, :celular, :correo, 
                :direccion, :ciudad, :fechaNacimiento, 
                :idAsesorAsignado, NOW(), :activo, 
                NULL, :idPersonalModificador, :contrasena
            )";

    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':idGenero' => $idGenero,
        ':idTipoDocumento' => $idTipoDocumento,
        ':documento' => $documento,
        ':celular' => $celular,
        ':correo' => $correo,
        ':direccion' => $direccion,
        ':ciudad' => $ciudad,
        ':fechaNacimiento' => $fechaNacimiento,
        ':idAsesorAsignado' => $idAsesorAsignado,
        ':activo' => $activo,
        ':idPersonalModificador' => $idPersonalModificador,
        ':contrasena' => password_hash($contrasena, PASSWORD_DEFAULT)
    ]);
}


/**
 * Actualizar usuario personal.
 */
function actualizarPersonal($conexion, $id, $nombre, $apellido, $correo, $contrasena, $idRol, $direccion, $celular, $activo) {
    $sql = "UPDATE personal SET
                Nombre = :nombre,
                Apellido = :apellido,
                Correo = :correo,
                Contraseña = :contrasena,
                ID_Rol = :idRol,
                Direccion = :direccion,
                Celular = :celular,
                Activo = :activo
            WHERE id_personal = :id";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':correo' => $correo,
        ':contrasena' => $contrasena, // usar password_hash si implementas seguridad
        ':idRol' => $idRol,
        ':direccion' => $direccion,
        ':celular' => $celular,
        ':activo' => $activo
    ]);
}

/**
 * Eliminar usuario personal.
 */
function eliminarPersonal($conexion, $id) {
    $stmt = $conexion->prepare("DELETE FROM personal WHERE id_personal = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

/**
 * Obtener roles desde tabla roles.
 */
function obtenerRoles($conexion) {
    $stmt = $conexion->query("SELECT id_rol, nombre_rol FROM roles ORDER BY nombre_rol ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
