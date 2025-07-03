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
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario de Registro</title>   
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/crearAsesor.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
 <!-- Header -->
  <?php include '../public/layout/barraNavAsesor.php'; ?>

  <form id="registroForm" action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=agregarAsesor" method="POST" class="formulario-banco">
    <h2>Registro De Asesor</h2>

    <div class="fila">
      <div class="columna">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan">

        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" required placeholder="Ej. Martínez">

        <label for="genero">Género *</label>
        <select id="genero" name="genero" required>
          <option value="" disabled selected>Seleccione una opción</option>
          <option value="1">Femenino</option>
          <option value="2">Masculino</option>
          <option value="3">Otro</option>
        </select>

        <label for="tipo_documento">Tipo de Documento *</label>
        <select id="tipo_documento" name="tipo_documento" required>
          <option value="" disabled selected>Seleccione una opción</option>
          <option value="1">Cédula de Ciudadanía</option>
          <option value="2">Tarjeta de Identidad</option>
          <option value="3">Cédula de Extranjería</option>
        </select>

        <?php 
          if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1){
             echo '<label for="idRol">Rol *</label>
              <select id="idRol" name="idRol" required>
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="1">Gerente</option>
                <option value="2">Sub Gerente</option>
                <option value="3">Asesor</option>
                <option value="3">Cajero</option>
              </select>';
          }
        ?>

      </div>

      <div class="columna">
        <label for="documento">Número de Documento *</label>
        <input type="text" id="documento" name="documento" required pattern="\d+" placeholder="Ej. 1234567890">

        <label for="celular">Celular *</label>
        <input type="tel" id="celular" name="celular" required pattern="[0-9]{10}" maxlength="10" placeholder="Ej. 3001234567">

        <label for="correo">Correo Electrónico *</label>
        <input type="email" id="correo" name="correo" required placeholder="Ej. asesor@correo.com"
          onfocus="this.placeholder=''" onblur="this.placeholder='Ej. asesor@correo.com'">

        <label for="contrasena">Contraseña *</label>
        <div class="password-wrapper">
          <input type="password" id="contrasena" name="contrasena" required placeholder="Crea una contraseña segura">
          <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
      </div>
    </div>

    <button type="submit">Registrarse</button>
  </form>


  <script>
    function togglePassword() {
      const passwordField = document.getElementById("contrasena");
      const toggleBtn = document.querySelector(".toggle-password");

      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleBtn.textContent = "🙈";
      } else {
        passwordField.type = "password";
        toggleBtn.textContent = "👁";
      }
    }

    // Validación simple
    document.getElementById("registroForm").addEventListener("submit", function(event) {
      // Aquí puedes agregar más validaciones si lo necesitas
      alert("Formulario enviado correctamente.");
    });
  </script>


  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>

  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

</body>
</html>