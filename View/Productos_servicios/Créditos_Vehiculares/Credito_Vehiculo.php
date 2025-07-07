<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crédito de vehículo</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU CRÉDITO DE VEHÍCULO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J23.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un crédito de vehículo?</h3>
      <p>Es un préstamo otorgado por una entidad financiera para financiar la compra de un carro nuevo o usado. El vehículo queda como garantía mientras se paga el crédito en cuotas mensuales.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Seguros opcionales (todo riesgo, vida, desempleo)</li>
        <li>Asistencia en carretera y jurídica</li>
        <li>Pagos por canales digitales 24/7</li>
        <li>Acceso a promociones con concesionarios aliados</li>
       </ul>
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
       
          <li>Ser mayor de edad (18 años)</li>
          <li>El ingreso mínimo requerido es de 2 SMMLV</li>
          <li>Para personas independientes se exige experiencia de 1 año en la actividad desempeñada.</li>
          <li>Para independientes no formales, el monto de crédito a otorgar no podrá exceder 66 SMMLV de requerir un valor a financiar superior, deberá aportar codeudor que cumpla políticas para acceder al monto o garantía adicional.</li>

      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>