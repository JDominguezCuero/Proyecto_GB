<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Créditos Hipotecarios
  </title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Créditos Hipotecarios </h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A7.avif" alt="Imagen crédito" floatr="right">
  </div>
</div>






  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/X6.png" alt="Crédito Rotativo">
      <div class="card-content">
        <h3>Crédito Hipotecario para Remodelación de Vivienda</h3>
    <p>Un crédito hipotecario para remodelación de vivienda es un préstamo usando tu casa como garantía para financiar mejoras, ampliaciones o renovaciones. Te permite acceder a fondos para proyectos grandes o pequeños en tu propiedad actual, con tasas y plazos usualmente favorables.</p>    
    </div>
    </div>

    <div >
    </div>

    <div>
   
      <div >
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/X7.PNG" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Crédito Hipotecario para Construcción de Vivienda</h3>
       <p>Un crédito hipotecario para construcción de vivienda financia la edificación de una casa desde cero en un terreno propio. Los fondos se desembolsan por etapas, a medida que la obra avanza, y el préstamo se garantiza con la vivienda una vez terminada.</p>
      </div>
    </div>
  </div>


  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Crédito Hipotecario?</h3>
      <p>Es un medio de pago que te permite hacer compras,
        pagos o avances sin necesidad de tener dinero en efectivo, usando un cupo de crédito aprobado por el Banco.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Pagos mensuales <br>Permite dividir el costo de la vivienda en cuotas mensuales, lo que reduce la presión financiera.</li>
        <li>Inversión a largo plazo <br>Al adquirir una propiedad, se invierte en un activo que puede aumentar su valor con el tiempo. </li>
        <li>Seguridad financiera <br>La propiedad adquirida con un crédito hipotecario puede proporcionar seguridad financiera a largo plazo. </li>
        <li>Control y privacidad <br>Al ser dueño de la vivienda, se tiene control y privacidad sobre el propio espacio.  </li>
        <li>Facilidades de pago <br> Se puede elegir la forma de amortización que mejor se adapte a las necesidades. </li>
     
      </ul>
    </div>

    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
     <strong><li>Crédito Hipotecario para remodelación de vivienda</li></strong>
        <li>Documento de Identificación</li>
        <li>Prueba de Ingresos</li>
        <li>Información Financiera</li>
        <li>Documentación de la Propiedad</li>
        <li>Documentación Adicional</li>
        <li>Cotización de la remodelación</li>
        <li>Formulario de solicitud</li>
   <strong><p>Crédito hipotecario para construcción de vivienda</p></strong>
        <li>Documento de Identificación</li>
        <li>Prueba de Ingresos</li>
        <li>·Extractos Bancarios</li>
        <li>·Certificado de Libertad y Tradición</li>
        <li>·Escritura de la Propiedad</li>
        <li>·Planos y Permisos de Construcción</li>
        <li>·Cotizaciones de Contratistas</li>
        <li>·Formulario de Solicitud</li>

      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>