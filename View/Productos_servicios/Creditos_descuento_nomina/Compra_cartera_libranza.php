<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>COMPRA DE CARTERA DE LIBRANZA</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA DE LIBRANZA HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/j1.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una compra de cartera de libranza?</h3>
      <p>Es un producto que permite trasladar un crédito con descuento por nomina o pensión a otra entidad, obteniendo mejores condiciones como menor tasa de interés plazos más amplios o cuotas mas bajas.
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
       <li>Unificación de deudas en una sola cuota mensual.</li>
       <li>Tasa de interés más baja que la original.</li>
       <li>Reducción de la carga mensual por mayor plazo o mejores condiciones.</li>
       <li>Pago automático desde la nómina o pensión.</li>
       <li>Facilidad para mejorar el historial crediticio.</li>
       <li>No se requiere codeudor (en la mayoría de los casos).</li>
       <li>Posibilidad de obtener dinero adicional si tu capacidad de pago lo permite.</li>
       <li>Mayor control de tus finanzas personales.</li>
       <li>Seguro de vida incluido en el nuevo crédito.</li>
      </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
        <li>Cédula de ciudadanía.</li>
        <li>Solicitud de crédito diligenciada.</li>
        <li>Carta de saldo expedida por la entidad actual, que incluya: saldo total, tasa de interés y plazo restante.</li>
        <li>Carta de autorización de la empresa donde trabajas para realizar el descuento por nómina.</li>
        <li>Certificación laboral con fecha reciente (no mayor a 30 días).</li>
        <li>Últimos 2 o 3 desprendibles de nómina.</li>
        <li>Extractos bancarios recientes (si la entidad financiera lo solicita).</li>
      </ul>
    </div>
  </section>

</body>
</html>