<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuenta de Ahorro para Pensionados</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Cuenta de Ahorro para Pensionados</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N9.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorro para Pensionados?</h3>
<p>Es una cuenta bancaria en la que una entidad financiera recibe y administra los pagos de pensión de una persona, permitiéndole realizar retiros, pagos y transferencias, entre otras operaciones.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Sin cuota de manejo.</li>
<li>Retiros ilimitados en cajeros de la misma entidad.</li>
<li>Exención del 4x1000 (en una cuenta única registrada).</li>
<li>Facilidad para recibir la pensión directamente.</li>
<li>Acceso a líneas de crédito especiales para pensionados.</li>
<li>Tarjeta débito gratuita.</li>
<li>Posibilidad de descuentos en servicios o productos.</li>
      </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos </h3>
      <ul>
        <li>Ser pensionado reconocido por el sistema (Colpensiones, fondos privados, fuerzas militares, etc.).</li>
        <li>Presentar el documento de identidad original.</li>
        <li>Constancia de pensión activa o resolución de pensión.</li>
        <li>Diligenciar el formulario de apertura.</li>
      </ul>
    </div>
  </section>

</body>
</html>