<?php
session_start();
  require_once(__DIR__ . '../../../Config/config.php');
  
  if (isset($_GET['login']) && $_GET['login'] == 'success') {
    $user = htmlspecialchars($_SESSION['nombre'] ?? 'Asesor');
    echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modal de Bienvenida</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 0; 
                    overflow: hidden; /* Oculta el scroll de la página principal */
                }

                /* Estilos para el modal */
                .modal {
                    display: flex; /* Muestra el modal por defecto y usa flex para centrar */
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.6); /* Fondo semi-transparente más oscuro */
                    justify-content: center;
                    align-items: center;
                }

                .modal-content {
                    background-color: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                    width: 90%;
                    max-width: 500px;
                    text-align: center;
                    animation: fadeIn 0.3s ease-out;
                    position: relative; /* Necesario para el botón de cerrar */
                }

                h1 { 
                    color: #2e7d32; 
                    font-size: 1.8rem;
                    margin-bottom: 15px;
                }

                p { 
                    color: #555; 
                    font-size: 1.1rem;
                    margin-bottom: 10px;
                }

                .btn-start {
                    background-color: #ef6c00;
                    color: white;
                    padding: 12px 25px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 1.1rem;
                    text-decoration: none;
                    transition: background-color 0.3s ease;
                    display: inline-block; /* Para que el padding y margin funcionen correctamente */
                    margin-top: 20px;
                }

                .btn-start:hover {
                    background-color: #d84315;
                }

                .close-button {
                    color: #aaa;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                    position: absolute;
                    top: 10px;
                    right: 20px;
                    transition: 0.3s;
                }

                .close-button:hover,
                .close-button:focus {
                    color: #000;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body>

            <div id="welcomeModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeModal()">&times;</span>
                    <h1>¡Bienvenido a la Gestión de Clientes GB!</h1>
                    <p>Tu plataforma para gestionar eficientemente los datos de tus clientes.</p>
                    <p>Haz clic para acceder a la sección de clientes:</p>
                    <a href="index.php" class="btn-start">Ir a la Gestión de Clientes</a>
                </div>
            </div>

            <script>
                function closeModal() {
                    const modal = document.getElementById("welcomeModal");
                    modal.style.display = "none";
                    document.body.style.overflow = "auto"; /* Restaura el scroll del body */
                }

                // Mostrar el modal automáticamente al cargar la página
                document.addEventListener(\'DOMContentLoaded\', function() {
                    const modal = document.getElementById("welcomeModal");
                    modal.style.display = "flex"; // Asegura que el modal esté visible
                    document.body.style.overflow = "hidden"; // Oculta el scroll del body
                });
            </script>
        </body>
        </html>';

  } 

?>

<?php


$datos_cliente = $datos_cliente ?? []; 
?>

<h2 style="color: #2e7d32;">Perfil del Cliente</h2>

<?php if (!empty($datos_cliente)): ?>
    <div class="perfil-cliente-container">
        <div class="profile-photo-container">
            <img src="Assets/img/default_profile.png" alt="Foto de Perfil" class="profile-photo">
        </div>

        <h3>Información General del Cliente</h3>
        
        <div class="info-group">
            <label>Nombre Completo:</label>
            <span><?php echo htmlspecialchars($datos_cliente['Nombre_Cliente'] ?? 'N/A') . ' ' . htmlspecialchars($datos_cliente['Apellido_Cliente'] ?? 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Número de Documento:</label>
            <span><?php echo htmlspecialchars($datos_cliente['N_Documento_Cliente'] ?? 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Fecha de Nacimiento:</label>
            <span><?php echo htmlspecialchars(isset($datos_cliente['Fecha_Nacimiento_Cliente']) && !empty($datos_cliente['Fecha_Nacimiento_Cliente']) ? date('d/m/Y', strtotime($datos_cliente['Fecha_Nacimiento_Cliente'])) : 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Celular:</label>
            <span><?php echo htmlspecialchars($datos_cliente['Celular_Cliente'] ?? 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Correo Electrónico:</label>
            <span><?php echo htmlspecialchars($datos_cliente['Correo_Cliente'] ?? 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Dirección:</label>
            <span><?php echo htmlspecialchars($datos_cliente['Direccion_Cliente'] ?? 'N/A'); ?></span>
        </div>

        <div class="info-group">
            <label>Ciudad:</label>
            <span><?php echo htmlspecialchars($datos_cliente['Ciudad_Cliente'] ?? 'N/A'); ?></span>
        </div>
        
        <div class="button-container" style="text-align: center; margin-top: 30px;">
            <a href="index.php?controller=cliente&action=mostrarDatosCliente&id=<?php echo htmlspecialchars($datos_cliente['ID_Cliente'] ?? ''); ?>" class="btn-primary">Actualizar mis Datos</a>
        </div>
    </div>
<?php else: ?>
    <div class="message-box">
        <p>No se encontraron datos para el perfil del cliente.</p>
        <p>Esto puede ocurrir si el ID del cliente es incorrecto o no existe en la base de datos.</p>
    </div>
<?php endif; ?>
