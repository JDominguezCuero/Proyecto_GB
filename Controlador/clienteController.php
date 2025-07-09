<?php
// PROYECTO_GB/Controlador/ClienteController.php

class ClienteController {
    private $clienteModel;

    public function __construct(ClienteModel $clienteModel) {
        $this->clienteModel = $clienteModel;
    }

    // Acción por defecto: Redirige al perfil del cliente
    public function index() {
        $id_cliente_default = $_SESSION['ID_Cliente_Logueado'] ?? 1;
        header("Location: index.php?controller=cliente&action=mostrarPerfilCliente&id=" . $id_cliente_default);
        exit();
    }

    // Método para mostrar los datos que el cliente puede actualizar
    public function mostrarDatosCliente() {
        $id_cliente = $_GET['id'] ?? 1;
        $datos_cliente = $this->clienteModel->getClienteById($id_cliente);

        if (!$datos_cliente) {
            $mensaje_cliente_no_encontrado = "Cliente con ID " . htmlspecialchars($id_cliente) . " no encontrado.";
        }

        $campos_actualizables = ['Celular_Cliente', 'Correo_Cliente', 'Direccion_Cliente', 'Ciudad_Cliente', 'Contraseña'];

        $seccion = 'actualizar_datos'; 
        include 'Vista/Cliente.php';
    }

    // Método para mostrar el perfil básico del cliente
    public function mostrarPerfilCliente() {
        $id_cliente = $_GET['id'] ?? 1;
        
        $datos_cliente = $this->clienteModel->getClienteById($id_cliente); 

        if (!$datos_cliente) {
            $mensaje_cliente_no_encontrado = "No se encontraron datos para el perfil del cliente con ID: " . htmlspecialchars($id_cliente);
        }

        $seccion = 'perfil_cliente'; 
        include 'Vista/Cliente.php';
    }

    // Método para mostrar la sección de validación de productos
    public function mostrarValidacionProductos() {
        $id_cliente = $_GET['id'] ?? 1;
        $datos_cliente = $this->clienteModel->getClienteById($id_cliente);

        if (!$datos_cliente) {
            $mensaje_cliente_no_encontrado = "Cliente con ID " . htmlspecialchars($id_cliente) . " no encontrado para validación de productos.";
        }
        
        $seccion = 'validacion_productos';
        include 'Vista/Cliente.php';
    }

    // Método para mostrar la sección de generación de certificaciones
    public function mostrarCertificaciones() {
        $datos_cliente = [];
        $producto_para_certificado = null;
        $error_message = '';

        // *** CORRECCIÓN CLAVE AQUÍ: Usar 'N_Documento_Cliente' en lugar de 'n_documento' ***
        if (isset($_GET['N_Documento_Cliente']) && !empty($_GET['N_Documento_Cliente'])) { 
            $n_documento_cliente = $_GET['N_Documento_Cliente']; // Cambiado a $n_documento_cliente

            // Usar el nombre de la columna real de la base de datos para la búsqueda
            $datos_cliente_temp = $this->clienteModel->getClienteByNdocumento($n_documento_cliente); 

            if ($datos_cliente_temp) { 
                $datos_cliente = $datos_cliente_temp; 

                // Obtener un producto. Aquí asumo que siempre buscarás el producto con ID 1
                // o necesitarás una lógica para determinar qué producto certificar.
                $producto_para_certificado = $this->clienteModel->getProductoById(1); 
                if (!$producto_para_certificado) { 
                    $error_message = "No se encontró el producto para certificar (ID: 1)."; 
                }
            } else { 
                $error_message = "Cliente con N° Documento '" . htmlspecialchars($n_documento_cliente) . "' no encontrado."; 
            }
        } else { 
            // Si no se envió 'N_Documento_Cliente' o se envió vacío, muestra un mensaje para el usuario.
            if (!isset($_GET['N_Documento_Cliente'])) { 
                 $error_message = "Por favor, ingrese un número de documento para generar el certificado."; 
            }
        }

        $seccion = 'certificaciones';
        include 'Vista/Cliente.php';
    }

    public function actualizarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cliente = $_POST['id_cliente'] ?? null;

            if ($id_cliente === null) {
                header("Location: index.php?controller=cliente&action=mostrarDatosCliente&status=error_id_invalido");
                exit();
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
            
            $actualizado = $this->clienteModel->updateCliente($id_cliente, $datos_a_actualizar);

            if ($actualizado) {
                header("Location: index.php?controller=cliente&action=mostrarDatosCliente&status=success&id=" . $id_cliente);
            } else {
                header("Location: index.php?controller=cliente&action=mostrarDatosCliente&status=error&id=" . $id_cliente);
            }
            exit();
        } else {
            header("Location: index.php?controller=cliente&action=mostrarDatosCliente");
            exit();
        }
    }
}
?>