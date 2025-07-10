<?php
// PROYECTO_GB/Vista/Cliente/Actualizar_datos.php
require_once(__DIR__ . '../../../Config/config.php');

// Asegúrate de que $datos_cliente esté definido y no sea nulo.
$datos_cliente = $datos_cliente ?? [];
// Puedes tener una variable para el mensaje de estado si viene del controlador
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $status_message = '<div class="message-box success"><p>¡Datos actualizados correctamente!</p></div>';
    } elseif ($_GET['status'] == 'error') {
        $status_message = '<div class="message-box error"><p>Error al actualizar los datos. Intente de nuevo.</p></div>';
    } elseif ($_GET['status'] == 'error_id_invalido') {
        $status_message = '<div class="message-box error"><p>Error: ID de cliente inválido.</p></div>';
    }
}
?>

<h2 style="color: #2e7d32;">Actualizar Datos del Cliente</h2>

<?php echo $status_message; ?>

<?php if (!empty($datos_cliente)): ?>
    <div class="form-container">
        <form action="index.php?controller=cliente&action=actualizarCliente" method="POST">
            <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($datos_cliente['ID_Cliente'] ?? ''); ?>">
            
            <div class="form-group">
                <label for="celular_cliente">Celular:</label>
                <input type="text" id="celular_cliente" name="celular_cliente" value="<?php echo htmlspecialchars($datos_cliente['Celular_Cliente'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="correo_cliente">Correo:</label>
                <input type="email" id="correo_cliente" name="correo_cliente" value="<?php echo htmlspecialchars($datos_cliente['Correo_Cliente'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="direccion_cliente">Direccion:</label>
                <input type="text" id="direccion_cliente" name="direccion_cliente" value="<?php echo htmlspecialchars($datos_cliente['Direccion_Cliente'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="ciudad_cliente">Ciudad:</label>
                <input type="text" id="ciudad_cliente" name="ciudad_cliente" value="<?php echo htmlspecialchars($datos_cliente['Ciudad_Cliente'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="Dejar en blanco para no cambiar">
            </div>

            <center><button type="submit" class="btn-primary">Guardar Cambios</button></center>
        </form>
    </div>
<?php else: ?>
    <div class="message-box info">
        <p>No se encontraron datos para actualizar el perfil del cliente.</p>
        <p>Asegúrese de que el ID del cliente sea válido.</p>
    </div>
<?php endif; ?>
