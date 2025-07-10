
<?php


$datos_cliente = $datos_cliente ?? []; 
?>

<h2 style="color: #2e7d32;">Perfil del Cliente</h2>

<?php if (!empty($datos_cliente)): ?>
    <div class="perfil-cliente-container">
        <div class="profile-photo-container">
            <img src="<?= BASE_URL ?>/View/public/assets/img/default_profile.png" alt="Foto de Perfil" class="profile-photo">
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
            <a href="<?= BASE_URL ?>/Controlador/clienteController.php?action=mostrarDatosCliente&id=<?php echo htmlspecialchars($datos_cliente['ID_Cliente'] ?? ''); ?>" class="btn-primary">Actualizar mis Datos</a>
        </div>
    </div>
<?php else: ?>
    <div class="message-box">
        <p>No se encontraron datos para el perfil del cliente.</p>
        <p>Esto puede ocurrir si el ID del cliente es incorrecto o no existe en la base de datos.</p>
    </div>
<?php endif; ?>
