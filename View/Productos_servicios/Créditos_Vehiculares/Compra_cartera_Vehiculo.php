<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de cartera de vehículo </title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA DE VEHÍCULO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J24.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una compra de cartera de vehículo?</h3>
      <p>Es un proceso en el que una entidad financiera asume tu crédito actual de vehículo con otro banco, ofreciéndote mejores condiciones como menor tasa de interés, cuota más baja o plazos más cómodos. Ideal para ahorrar y organizar tus finanzas.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Menor tasa de interés frente al crédito original.</li>
        <li>Reducción de la cuota mensual.</li>
        <li>Plazos más amplios y cómodos.</li>
        <li>Mejores condiciones de pago.</li>
        <li>Unificación de deudas si tienes otros créditos relacionados.</li>
      </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
        <li>Documento de identidad (original y copia).</li>
       <li>Solicitud de crédito diligenciada.</li>
       <li>Carta de saldo del crédito vehicular actual (valor a trasladar, tasa, plazo, y condiciones).</li>
       <li>Tarjeta de propiedad del vehículo.</li>
       <li>Certificación de ingresos</li>
       <li>Carta laboral reciente y desprendibles de nómina.</li>
       <li>Declaración de renta o estados financieros certificados.</li>
       <li>Extractos bancarios (últimos 3 meses).</li>
       <li>Póliza de seguro vigente del vehículo (si aplica).</li>
      </ul>
    </div>
  </section>

</body>
</html>