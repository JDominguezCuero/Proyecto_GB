<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Microcrédito</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Microcrédito</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/X8.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Microcredito</h3>
<p>El Microcrédito de Banco FINAN-CIAS es una opción de financiación destinada a microempresarios y personas independientes para invertir en el mantenimiento y crecimiento de sus negocios, con préstamos que oscilan entre 1 y 120 SMMLV. </p>
    </p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Estudio de crédito sin costo, adjuntando los documentos que soporten tus ingresos, como facturas de venta.</li>
        <li>Acompañamiento personalizado desde la solicitud del producto.</li>
        <li>El Fondo Nacional de Garantías te sirve como garantía del crédito.</li>
      
        <li>Luego de la aprobación puedes desembolsar el dinero en un tiempo de minimo 24 horas.</li>
        <li>Puedes autorizar el débito automático de tu cuenta de ahorros o realiza tus pagos con la Sucursal Virtual o Física.</li>
      </ul>

    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
<li>Documento de identificación</li>
<li>Ser mayor de edad (18 años)</li>
<li>Plan de negocios</li>
<li>Registro cámara de comercio</li>
<li>Facturas de venta</li>
<li>Declaración de impuestos</li>
<li>Residir en el país que hace la solicitud</li>
      </ul>

    </ul>
    </div>
  </section>



</body>
</html>