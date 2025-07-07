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
    <h1>SOLICITA TU CRÉDITO ROTATIVO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A4.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Crédito Rotativo (Crediágil)?</h3>
      <p>Es un crédito diseñado para brindarte flexibilidad y disponibilidad inmediata de recursos, que se renueva a medida que se hacen abonos a la deuda, permitiendo disponer de él de manera parcial o total.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Abonos y pagos sin costo adicional.</li>
        <li>Tasa y cuota fija por desembolso.</li>
        <li>Exoneración de comisión por 3 meses.</li>
        <li>Montos de desembolso.</li>
        <li>Comisión mensua.</li>
        <li>Seguro de vida.</li>
        <li>Plazos flexibles.</li> 
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
        <li>Tener entre 18 y 84 años.</li>
        <li>Documento de identidad.</li>
        <li>Certificación laboral.</li>
        <li>Ingreso mínimo de 2 SMMLV.</li>
        <li>Certificado de ingresos.</li>
        <li>Retenciones o constancia de no declarante.</li>
        <li>Constancia de residencia.</li>
        <li>Formato de verificación diligenciado.</li>
      </ul>
    </div>
  </section>

</body>
</html>