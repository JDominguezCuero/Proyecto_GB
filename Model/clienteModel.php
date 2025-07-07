<?php
// PROYECTO_GB/Model/ClienteModel.php

class ClienteModel {
    private $conexion;

    public function __construct(mysqli $conexion) {
        $this->conexion = $conexion;
    }

    // Método para obtener los datos de un cliente por su ID
    public function getClienteById($id_cliente) {
        $stmt = $this->conexion->prepare("SELECT 
            ID_Cliente, Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, 
            N_Documento_Cliente, Celular_Cliente, Correo_Cliente, Direccion_Cliente, 
            Ciudad_Cliente, Fecha_Nacimiento_Cliente, ID_Asesor_Asignado, Fecha_Creacion_Cliente, 
            Activo_Cliente, Fecha_Modificacion_Cliente, ID_Personal_Modificador, Contraseña 
            FROM cliente WHERE ID_Cliente = ?"); // Asegúrate de que los nombres de las columnas coincidan con tu DB
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta getClienteById: " . $this->conexion->error);
            return null;
        }

        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }

    // Método para obtener los datos de un cliente por su número de documento
    public function getClienteByNdocumento($n_documento_cliente) { // Nombre de parámetro corregido
        $stmt = $this->conexion->prepare("SELECT 
            ID_Cliente, Nombre_Cliente, Apellido_Cliente, ID_Genero, ID_TD, 
            N_Documento_Cliente, Celular_Cliente, Correo_Cliente, Direccion_Cliente, 
            Ciudad_Cliente, Fecha_Nacimiento_Cliente, ID_Asesor_Asignado, Fecha_Creacion_Cliente, 
            Activo_Cliente, Fecha_Modificacion_Cliente, ID_Personal_Modificador, Contraseña 
            FROM cliente WHERE N_Documento_Cliente = ?"); // Nombre de columna corregido
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta getClienteByNdocumento: " . $this->conexion->error);
            return null;
        }

        $stmt->bind_param("s", $n_documento_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }

    // Método para obtener los datos de un producto por su ID
    public function getProductoById($id_producto) {
        $stmt = $this->conexion->prepare("SELECT ID_Producto, Nombre_Producto, Descripcion_Producto FROM producto WHERE ID_Producto = ?"); // Asegúrate de los nombres de columnas
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta getProductoById: " . $this->conexion->error);
            return null;
        }

        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }

    // Método para actualizar los datos del cliente
    public function updateCliente($id_cliente, $datos_a_actualizar) {
        $set_parts = [];
        $params = [];
        $types = '';

        $field_map = [
            'Celular_Cliente' => 's',
            'Correo_Cliente' => 's',
            'Direccion_Cliente' => 's',
            'Ciudad_Cliente' => 's',
            'Contraseña' => 's' // Asegúrate de que si actualizas la contraseña, la hagas hash
        ];

        foreach ($datos_a_actualizar as $campo => $valor) {
            if (isset($field_map[$campo])) {
                $set_parts[] = "`" . $campo . "` = ?";
                $params[] = $valor;
                $types .= $field_map[$campo];
            }
        }

        if (empty($set_parts)) {
            return false; // No hay campos para actualizar
        }

        $query = "UPDATE cliente SET " . implode(", ", $set_parts) . " WHERE ID_Cliente = ?";
        $params[] = $id_cliente;
        $types .= 'i';

        $stmt = $this->conexion->prepare($query);
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta updateCliente: " . $this->conexion->error);
            return false;
        }
        
        // El operador '...' desempaqueta el array $params en una lista de argumentos
        $stmt->bind_param($types, ...$params); 
        
        $ejecutado = $stmt->execute();
        $stmt->close();
        
        return $ejecutado;
    }
}
?>