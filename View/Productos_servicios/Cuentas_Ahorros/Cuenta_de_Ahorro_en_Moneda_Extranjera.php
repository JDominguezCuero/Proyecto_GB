<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuenta de Ahorro en Moneda Extranjera</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Cuenta de Ahorro en Moneda Extranjera</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N10.webp" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorro en Moneda Extranjera?</h3>
<p>Es una cuenta bancaria que funciona igual que una cuenta de ahorro común, pero el dinero se mantiene en moneda extranjera. Se usa principalmente para protegerse de la devaluación del peso, recibir pagos del exterior o realizar operaciones internacionales.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
   <li>Protección ante la devaluación del peso colombiano.</li>
   <li>Posibilidad de recibir pagos internacionales sin conversión automática a pesos.</li>
   <li>Facilidad para transferencias internacionales.</li>
   <li>Atractivo para quienes viajan, estudian o hacen negocios fuera de Colombia.</li>
   <li>Algunas entidades permiten acceder a tasas de interés competitivas.</li>
    </ul>
    </div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
      <li>Ser mayor de edad.</li>
    <li>Documento de identidad válido.</li>
    <li>Justificación del origen de los fondos (por normativas de control cambiario).</li>
    <li>Monto mínimo de apertura (depende del banco, por ejemplo: USD 500 o más).</li>
    <li>En algunos casos, demostrar experiencia o necesidad de manejo en divisas.</li>
      
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>