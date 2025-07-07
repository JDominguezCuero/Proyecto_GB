<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crédito Rotativo</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU CRÉDITO DE LIBRANZA HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/j2.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un crédito de libranza?</h3>
      <p>Es un crédito dirigido a empleados, pensionados, docentes y miembros de las fuerzas militares  que se descuenta mensualmente de la nómina o mesada pensional según corresponda.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Bajas tazas de interés </li>
        <li>Descuentos directos por nómina o mesada.</li>
        <li>Elegir plazos de pagos ajustados a la necesidad.</li>
        <li>Libre destinación del dinero.</li>
        <li>Mayor facilidad de aprobación.</li>
        <li>Seguro de vida incluido.</li>
       </ul>
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
        <li>Tener contrato laboral vigente con empresas que tengan convenio con el banco.</li>
        <li>Tener contrato laboral a término fijo o  indefinido.</li>
        <li>Certificado laboral vigente.</li>
        <li>Tener entre 18 y 74 años.</li>
        <li>Documento de identidad.</li>
        <li>Contar con un ingreso mínimo de 1 SMMLV.</li>
        <li>Certificado de ingresos.</li>
        <li>Ultimos tres comprobantes de pago de salario o pensión.</li>
      </ul>
    </div>
  </section>

</body>
</html>