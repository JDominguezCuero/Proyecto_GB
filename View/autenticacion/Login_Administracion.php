<?php
  require_once(__DIR__ . '../../../Config/config.php');

  // inicio
  if (isset($_GET['login']) && $_GET['login'] == 'error') {
    $usuario = isset($_GET['usuario']) ? addslashes($_GET['usuario']) : 'Desconocido';

    $mensaje = "Error desconocido, contactese con el administrador";

    if (isset($_GET['error']) && $_GET['error']) {
        $mensaje = htmlspecialchars($_GET['error']);
    }    
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('❌ Acceso Denegado', '$mensaje', 'error');
        });
    </script>";      
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

  <style>
    body {
      background: url('../../View/public/assets/Img/Redes/fondoLoginAdministrativo.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
    }


    .login-container {
      display: flex;
      background: rgba(255, 255, 255, 0.93);
      border-radius: 40px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      overflow: hidden;
      backdrop-filter: blur(10px);
      max-width: 760px; 
      width: 90%;
    }

    form {
      padding: 60px 40px;
      width: 60%;
      border-top: 10px solid #b8860b;
      box-sizing: border-box;
    }

    .info-box {
      background-color: #28a745;
      width: 40%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      padding: 20px;
      box-sizing: border-box;
      border-top: 10px solid #228B22;
    }

    .info-box h2 {
      color: #fff;
      margin-bottom: 15px;
      font-size: 24px;
    }

    .info-box p {
      margin-bottom: 25px;
      font-size: 16px;
    }

    .info-box button {
      background-color: transparent;
      color: #fff;
      border: 2px solid #fff;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .info-box button:hover {
      background-color: #fff;
      color: #28a745;
    }

    h2 {
      text-align: center;
      color: #b8860b;
      margin-bottom: 25px;
      font-size: 24px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #222;
    }

    input, select {
      width: 90%;
      padding: 10px 18px;
      margin-bottom: 18px;
      border: 1px solid #444;
      border-radius: 8px;
      font-size: 15px;
      background-color: #f8f8f8;
      color: #000;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus, select:focus {
      border-color: #28a745;
      box-shadow: 0 0 6px rgba(40, 167, 69, 0.5);
      outline: none;
    }

    button {
      background-color: #b8860b;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: bold;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #a1740a;
    }

    .forgot-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #007bff;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .forgot-link:hover {
      color: #0056b3;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <form method="post" action="<?= BASE_URL ?>/Controlador/autenticacionController.php?accion=loginAsesor">
      <h2>Inicio de Sesión como admistrativo</h2>

      <label for="usuario">Usuario</label>
      <input type="text" id="usuario" name="usuario" required autocomplete="username">

      <label for="clave">Contraseña</label>
      <input type="password" id="clave" name="clave" required autocomplete="current-password">

      <button type="submit">Ingresar</button>

      <a href="recuperar.html" class="forgot-link">¿Olvidó su contraseña?</a>
      <a href="../public/inicio.php" class="forgot-link">Volver</a>
    </form>
    <div class="info-box">
      <h2>Panel Administrativo Central</h2>
      <p>Supervisa, gestiona y controla toda la operación del sistema con eficiencia y seguridad.</p>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

</body>
</html>
  
  