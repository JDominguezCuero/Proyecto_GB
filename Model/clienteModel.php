<?php
// Model/clienteModel.php
require_once __DIR__ . '/../../Proyecto_GB/Config/config.php';

function obtenerClientePorId($conexion, $id_cliente) {
    try {
        $stmt = $conexion->prepare("
            SELECT ID_Cliente, Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, 
                   N_Documento_Cliente, Celular_Cliente, Correo_Cliente, Direccion_Cliente, 
                   Ciudad_Cliente, Fecha_Nacimiento_Cliente, ID_Personal_Creador, Fecha_Creacion_Cliente, 
                    Estado_Cliente, Fecha_Creacion_Cliente, Contraseña
            FROM cliente
            WHERE ID_Cliente = :id
        ");
        $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al obtener cliente por ID: " . $e->getMessage());
        return null;
    }
}

function obtenerClientePorDocumento($conexion, $documento) {
    try {
        $stmt = $conexion->prepare("
            SELECT ID_Cliente, Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, 
                   N_Documento_Cliente, Celular_Cliente, Correo_Cliente, Direccion_Cliente, 
                   Ciudad_Cliente, Fecha_Nacimiento_Cliente, ID_Personal_Creador, Fecha_Creacion_Cliente, 
                    Estado_Cliente, Fecha_Creacion_Cliente, Contraseña
            FROM cliente
            WHERE N_Documento_Cliente = :documento
        ");
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al obtener cliente por documento: " . $e->getMessage());
        return null;
    }
}

function obtenerProductoPorId($conexion, $id_producto) {
    try {
        $stmt = $conexion->prepare("
            SELECT ID_Producto, Nombre_Producto, Descripcion_Producto
            FROM producto
            WHERE ID_Producto = :id
        ");
        $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al obtener producto por ID: " . $e->getMessage());
        return null;
    }
}

function actualizarCliente($conexion, $id_cliente, $datos) {
    try {
        $campos = [];
        $parametros = [':id' => $id_cliente];

        foreach ($datos as $campo => $valor) {
            $campos[] = "`$campo` = :$campo";
            $parametros[":$campo"] = $valor;
        }

        if (empty($campos)) return false;

        $sql = "UPDATE cliente SET " . implode(', ', $campos) . " WHERE ID_Cliente = :id";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute($parametros);

    } catch (PDOException $e) {
        error_log("Error al actualizar cliente: " . $e->getMessage());
        return false;
    }
}
