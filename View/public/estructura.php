<?php
  require_once(__DIR__ . '../../../Config/config.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contáctanos - Banco Finan-CIAS</title>
  <link rel="stylesheet" href="assets/inicio.css">
  <style>
    .imagen-contacto {
      width: 90%;
      height: auto;
      display: block;
      margin: 0 auto;
      margin-top: 20px;
      border-radius: 40px;
    }
   

  </style>
</head>
<body>

  <?php include '../public/layout/barraNavAsesor.php'; ?>

  <div class="container">
    <img src="assets/Img/carrusel/A16.jpg" alt="Imagen Contacto" class="imagen-contacto">
  </div>


  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>
  

</body>
</html>
