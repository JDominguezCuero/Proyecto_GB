<?php
session_start();
  require_once(__DIR__ . '../../../Config/config.php');
  
  if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('❌ Error al registrar', '$mensaje', 'error');
      });
      </script>";

  }else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('✅ Operación Exitosa', '$mensaje', 'success');
      });
      </script>";
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/crearAsesor.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
    <!-- barra de navegacion en todas las paginas :) -->
<?php include '../public/layout/barraNavAsesor.php'; ?>
<body>

  <form action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=agregarCliente" method="POST" class="formulario-banco">
    <h2>Registro de Cliente</h2>

    <div class="fila">
      <div class="columna">
        <label>Nombre:</label>
        <input type="text" name="nombre" placeholder="Ej. Carlos" required>

        <label>Género:</label>
        <select name="genero" required>
          <option value="" disabled selected>Seleccione el género</option>
          <option value="1">Hombre</option>
          <option value="2">Mujer</option>
          <option value="3">Otro</option>
        </select>

        <label>Número de Documento:</label>
        <input type="text" name="documento" placeholder="Ej. 1234567890" required>

        <label>Correo:</label>
        <input type="email" name="correo" placeholder="Ej. usuario@correo.com">

        <label>Ciudad:</label>
        <input type="text" name="ciudad" placeholder="Ej. Medellín">

        <label>Contraseña:</label>
        <input type="password" name="contrasena" placeholder="Crea una contraseña segura" required>
      </div>

      <div class="columna">
        <label>Apellido:</label>
        <input type="text" name="apellido" placeholder="Ej. Ramírez" required>

        <label>Tipo Documento:</label>
        <select name="tipo_documento" required>
          <option value="" disabled selected>Seleccionar el tipo de documento</option>
          <option value="1">Cédula de ciudadanía</option>
          <option value="2">Tarjeta de identidad</option>
          <option value="3">Cédula extranjera</option>
        </select>

        <label>Celular:</label>
        <input type="text" name="celular" placeholder="Ej. 3001234567">

        <label>Dirección:</label>
        <input type="text" name="direccion" placeholder="Ej. Cra 45 #10-15">

        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento">
        
      </div>
    </div>

    <button type="submit">Guardar Cliente</button>
  </form>


  <!-- footer -->

  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>
   
  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

</body>
</html>



  