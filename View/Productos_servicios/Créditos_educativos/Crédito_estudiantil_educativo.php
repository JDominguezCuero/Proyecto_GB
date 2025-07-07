<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crédito estudiantil o educativo</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU CRÉDITO ESTUDIANTIL O EDUCATIVO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J23.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un crédito estudiantil o educativo?</h3>
      <p>Este crédito cubre hasta el 100% de los estudios superiores (técnicos, tecnológicos, pregrados, posgrados) y está dirigido a estudiantes con méritos académicos pero pocos recursos, facilitando su acceso a la educación. </p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Tasas de interés preferenciales para estudiantes.</li>
        <li>Financiación parcial o total de matrículas y otros gastos educativos.</li>
        <li>Plazos flexibles durante y después de los estudios.</li>
        <li>Opción de pago solo de intereses mientras estudias.</li>
        <li>No siempre se requiere codeudor.</li>
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
       <li>Formulario de solicitud de crédito diligenciado.</li>
       <li>Información del aspirante o estudiante</li>
          

      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>