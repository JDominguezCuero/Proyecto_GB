<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Credito de Vivienda.
  </title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>CREDITO DE VIVIENDA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A7.avif" alt="Imagen crédito" floatr="right">
  </div>
</div>





  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Credito de VIvienda?</h3>
    <p>Un crédito de vivienda es un crédito otorgado por bancos, cooperativas u otras entidades financieras para la adquisición, construcción o mejora de una vivienda.</p>
       </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Permite comprar una vivienda sin necesidad de tener todo el dinero de inmediato </li>
<li>Se puede usar para remodelar, ampliar o construir </li>
<li>Es una inversión a largo plazo que genera patrimonio </li>
<li>En general, tiene tasas de interés más bajas que otros productos bancarios </li>
<li>Se puede elegir entre pagar en pesos o en UVR </li>
      </ul>
    </div>

    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
    <li>Identificación vigente</li>
    <li>Ser mayor de edad (18 años)</li>
    <li>Comprobante de ingresos</li>
    <li>Certificación laboral</li>
    <li>Certificado de ingresos y retenciones</li>
    <li>Declaración de renta</li>
    <li>No tener reporte en centrales de riesgo.</li>
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>