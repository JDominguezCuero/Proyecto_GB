<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuenta de Ahorro de Nómina</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Cuenta de Ahorro de Nómina</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N8.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorro de Nómina?</h3>
    <p>Es una cuenta de ahorro usada principalmente para recibir el pago del sueldo, primas, cesantías y otros ingresos laborales. Se vincula directamente con el empleador, quien autoriza el abono automático de la nómina.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Sin cuota de manejo.</li>
<li>Exenta del 4x1000, si el titular la registra como su cuenta única para ese beneficio.</li>
<li>Retiros ilimitados en cajeros y oficinas del banco.</li>
<li>Tarjeta débito gratis.</li>
<li>Acceso a productos financieros preferenciales (créditos, libranzas, seguros).</li>
<li>Posibilidad de avances o anticipos de nómina.</li>
<li>Servicio de domiciliación de pagos (servicios públicos, tarjetas, etc.).
</li>
      </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
    <li>Ser empleado de una empresa que tenga convenio con el banco.</li>
    <li>Presentar documento de identidad original.</li>
    <li>Carta laboral o constancia del empleador para apertura.</li>
    <li>Formulario de apertura diligenciado.</li>
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>