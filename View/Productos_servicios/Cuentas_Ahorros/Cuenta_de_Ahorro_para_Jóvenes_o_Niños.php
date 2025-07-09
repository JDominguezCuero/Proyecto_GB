<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuenta de Ahorro Para Jovenes o Niños</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>CUANTA DE AHORROS PARA JOVENES O NIÑOS</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N7.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorros para Jovenes o Niños?</h3>
<p>Es una cuenta bancaria creada a nombre del niño, niña o adolescente, con el acompañamiento de un representante legal (padre, madre o tutor), que permite ahorrar dinero de forma segura y en algunos casos realizar operaciones limitadas como retiros o consultas.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
       <h3>Beneficios</h3>
      <ul>
        <li>Sin cuota de manejo.</li>
        <li>Intereses sobre el saldo.</li>
        <li>Acceso a consultas y retiros limitados (con control adulto).</li>
        <li>Facilita ahorros para estudios, regalos, metas personales, etc.</li>
      </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
        <li>Documento de identidad del menor (registro civil o tarjeta de identidad).</li>
        <li>Documento de identidad del representante legal.</li>
        <li>Diligenciar el formulario de apertura.</li>
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>