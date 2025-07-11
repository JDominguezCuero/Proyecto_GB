<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crediestudiantil</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU CREDIESTUDIANTIL HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y16.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3> crediestudiantil</h3>
      <p>Haz realidad tus sueños profesionales con nuestro Crédito "crediestudiantil". Financia hasta el 100% de tus estudios superiores de forma sencilla, rápida y con plazos flexibles. Porque tu talento no espera… ¡tu futuro empieza hoy!</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Financia hasta el 100% de la matrícula o gastos asociados.</li>
        <li>Cuotas ajustadas a tu capacidad de pago.</li>
        <li>Plazos amplios, incluso después de terminar los estudios.</li>
        <li>Pago solo de intereses durante el periodo académico (según condiciones).</li>
        <li>Tasas preferenciales para estudiantes.</li>
       </ul>
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
       <li>Declaración de renta del último año gravable (si aplica)</li>
       <li>Ser mayor de edad </li>
       <li>Certificación de ingresos y retenciones del último año gravable</li>
       <li>Certificado laboral original</li>
       <li>Si eres una persona asalariada</li>
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>