<?php
require_once(__DIR__ . '../../../Config/config.php');

// Mostrar mensajes con showModal()
if (isset($_GET['login'])) {
    if ($_GET['login'] == 'success') {
        $user = htmlspecialchars($_SESSION['nombreCliente'] ?? 'Cliente');
        echo '
        <div id="modalBienvenidaGB" class="modal-bienvenida-gb">
        <div class="modal-contenido-gb">
            <span class="cerrar-modal-gb" onclick="cerrarModalBienvenidaGB()">&times;</span>
            <h1>¡Bienvenido '. $user .' a la Gestión de Clientes GB!</h1>
            <p>Tu plataforma para gestionar eficientemente los datos de tus clientes.</p>
        </div>
        </div>

        <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Modal personalizado */
        .modal-bienvenida-gb {
            display: flex;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-contenido-gb {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 500px;
            text-align: center;
            animation: fadeInGB 0.3s ease-out;
            position: relative;
        }

        .modal-contenido-gb h1 {
            color: #2e7d32;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .modal-contenido-gb p {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .btn-ingresar-gb {
            background-color: #ef6c00;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-ingresar-gb:hover {
            background-color: #d84315;
        }

        .cerrar-modal-gb {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 20px;
            transition: 0.3s;
        }

        .cerrar-modal-gb:hover,
        .cerrar-modal-gb:focus {
            color: #000;
        }

        @keyframes fadeInGB {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        </style>

        <script>
        function cerrarModalBienvenidaGB() {
            const modal = document.getElementById("modalBienvenidaGB");
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }

        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("modalBienvenidaGB");

            if (window.location.href.includes("login=success")) {
            modal.style.display = "flex";
            document.body.style.overflow = "hidden";
            } else {
            modal.style.display = "none";
            }
        });
        </script>';

    } elseif ($_GET['login'] == 'error') {
        $mensaje = htmlspecialchars($_GET['error'] ?? 'Error inesperado');
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('❌ Error al iniciar sesión', '$mensaje', 'error');
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo-nombre">
            <img src="<?= BASE_URL ?>/View/public/assets/img/logo.jpg" alt="Logo Banco Finan-Cias" class="logo">
            <h1 class="titulo">Gestión de Clientes</h1>
        </div>
    </header>

    <nav class="nav">
        <ul>
            <li><a href="<?= BASE_URL ?>/Controlador/clienteController.php?action=mostrarPerfilCliente">Perfil Cliente</a></li>
            <li><a href="<?= BASE_URL ?>/Controlador/clienteController.php?action=mostrarDatosCliente&id=<?= htmlspecialchars($datos_cliente['ID_Cliente'] ?? '') ?>">Actualizar Datos</a></li>
            <li><a href="<?= BASE_URL ?>/Controlador/clienteController.php?action=mostrarValidacionProductos&id=<?= htmlspecialchars($datos_cliente['ID_Cliente'] ?? '') ?>">Validación de Productos</a></li>
            <li><a href="<?= BASE_URL ?>/Controlador/clienteController.php?action=mostrarCertificaciones">Generar Certificado</a></li>
        </ul>
    </nav>

    <main>
        <?php
        if (isset($mensaje_cliente_no_encontrado)) {
            echo "<div class='message-box error'><p>" . htmlspecialchars($mensaje_cliente_no_encontrado) . "</p></div>";
        }

        if (isset($seccion)) {
            switch ($seccion) {
                case 'perfil_cliente':
                    include '../../View/cliente/Perfil_Cliente.php';
                    break;
                case 'actualizar_datos':
                    include '../../View/cliente/Actualizar_datos.php';
                    break;
                case 'validacion_productos':
                    include '../../View/cliente/Validacion_Productos.php';
                    break;
                case 'certificaciones':
                    include '../../View/cliente/Certificaciones.php';
                    break;
                default:
                    include '../../View/cliente/Perfil_Cliente.php';
            }
        } else {
            include '../../View/cliente/Perfil_Cliente.php';
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> Banco Finan-Cias. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
