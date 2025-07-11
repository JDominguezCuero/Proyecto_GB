<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crédito de libre inversion</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>TENDRAS APROBACIÓN EN TIEMPO RECORD, TASAS FLEXIBLES, MINIMOS REQUISITOS, PLAZOS COMODOS.</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A8.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
    <div class="info-box">
      <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Crédito de Libre Inversion?</h3>
      <p>Es un credito personal sin destino específico, que puedes usar para lo que necesites: un viaje, estudios, remodelar tu casa, comprar electrodomésticos, invertir en un negocio, etc.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Flexibilidad total.</li>
        <li>Cuotas fijas flexibles.</li>
        <li>Sin necesidad de justificar el uso del dinero.</li>     
        <li> No necesitas codeudor.</li>
        <li>Con finan-CIAS tienes confianza,innovacion y atención cercana.</li>  
    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
      <li>Ser mayor de edad: tener mínimo 18 años.</li>
      <li>Tener ingresos fijos y demostrables: ya sea como empleado, independiente o pensionado.</li>
      <li>Buen historial crediticio: no estar reportado negativamente en Datacrédito u otras centrales.</li>
      <li>Presentar cédula original y vigente.</li>
      <li>Tener una cuenta bancaria activa para recibir el desembolso del dinero.</li>
      <li>Contar con una capacidad de pago adecuada.</li>
      <li>Que tus deudas no superen tu capacidad mensual (máximo entre el 30 % y el 40 % de tus ingresos).</li>
      <li>Aceptar el seguro de vida del crédito.</li>
      </ul>
    </div>
  </section>

  <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>