<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crédito libre educativo</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU CRÉDITO LIBRE EDUCATIVO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J23.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>crédito libre educativo</h3>
      <p>Impulsa tu futuro hoy con el Crédito Libre Educativo. Financia tu carrera, posgrado o programa técnico en Colombia de forma rápida y flexible. ¡Un solo desembolso directo a tu institución y hasta 48 meses para pagar!</p>
    </div>
    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>No está limitado a una institución educativa específica.</li>
        <li>Flexibilidad en el monto y destino de los recursos.</li>
        <li>Desembolso directo al solicitante o a quien se autorice.</li>
        <li>Plazos ajustables según el perfil del cliente.</li>
        <li>Posibilidad de aplicar sin codeudor (según política).</li>
        <li>Tasas preferenciales para estudiantes o empleados del sector educativo.</li>
       </ul>
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
       <li>Cédula de ciudadanía original y fotocopia legible.</li>
       <li>Comprobante de pago de matrícula (CPM) de la institución educativa con fecha vigente</li>
       <li>Certificación laboral (si aplica)</li>
       <li>Extracto bancario</li>
       <li>Declaración de renta (si aplica)</li>
       <li>Formulario de solicitud de crédito diligenciado</li>
       <li>Información del aspirante o estudiante</li>
      </ul>
    </div>
  </section>

</body>
</html>