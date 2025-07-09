<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de Cartera de Crédito Hipotecario
  </title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>COMPRA DE CARTERA DE CRÉDITO HIPOTECARIO</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A9.png" alt="Imagen crédito" floatr="right">
  </div>
</div>





  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Compra de Cartera de Crédito Hipotecario?</h3>
<p>El crédito de compra de cartera es un tipo de préstamo que permite trasladar tus deudas de otros bancos a uno nuevo para mejorar condiciones: pagar menos intereses, tener cuotas más bajas, plazos más largos y unificar todo en una sola deuda. </p>       </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li> Tasas de interés más bajas: Muchas entidades ofrecen tasas más competitivas que las del crédito original.</li>
<li> Mejores plazos: Puedes extender o reducir el plazo del crédito según tu capacidad de pago.</li>
<li> Ahorro en costos: Algunos bancos asumen gastos como el avalúo o no exigen nueva hipoteca, lo que evita gastos notariales.</li>    
<li> Beneficios tributarios: Los intereses pagados pueden disminuir tu base gravable en la declaración de renta.</li>
<li> Seguros incluidos: Generalmente se incluyen seguros de vida, incendio y terremoto.</li>
<li> Asesoría personalizada: Las entidades ofrecen acompañamiento durante todo el proceso, desde el estudio hasta el desembolso.</li>
</ul>
    </div>

    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
   <li>Cédula de ciudadanía o extranjería.</li>
   <li>Solicitud de crédito diligenciada.</li>
   <li>Carta de saldo de la entidad financiera donde está el crédito hipotecario.</li>
    <li>Certificado de tradición y libertad del inmueble (vigencia no mayor a 30 días).</li>
<li>Copia de la escritura pública del inmueble.</li>
<li>Avalúo del bien (si el banco lo solicita).</li>
<li>Certificación laboral o ingresos.</li>
<li>Extractos bancarios (últimos 3 meses).</li>
<li>Paz y salvo del crédito, si ya ha sido cancelado por otra entidad.</li>

</ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>
