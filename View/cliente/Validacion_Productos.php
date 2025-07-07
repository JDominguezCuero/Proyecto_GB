<?php

$datos_cliente = $datos_cliente ?? [];
?>

<h2>Validación de Productos del Cliente</h2>

<?php if (!empty($datos_cliente)): ?>
    <div class="product-validation-info">
        <p>Aquí se mostrarían los productos asociados al cliente:</p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($datos_cliente['Nombre'] ?? 'N/A') . ' ' . htmlspecialchars($datos_cliente['Apellido'] ?? 'N/A'); ?></p>
        <p><strong>N° Documento:</strong> <?php echo htmlspecialchars($datos_cliente['N_Documento'] ?? 'N/A'); ?></p>
        
        <p>*(Esta sección aún no tiene lógica para listar productos de la base de datos. Requiere una nueva función en ClienteModel para obtener los productos de un cliente.)*</p>
        
        </div>
<?php else: ?>
    <div class="message-box" style="text-align: center; margin-top: 50px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
        <p style="font-size: 1.1em; color: #555;">No se han encontrado datos del cliente para mostrar productos.</p>
        <p style="font-size: 0.9em; color: #777;">Por favor, asegúrese de que el ID del cliente sea válido.</p>
    </div>
<?php endif; ?>