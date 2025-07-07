<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Credito Agrofácil</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Credito Agrofácil</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/X11.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Credito Agrofácil?</h3>
<p>Un crédito Agrofácil es un préstamo diseñado para financiar proyectos agrícolas, a diferencia de un crédito de consumo que cubre gastos personales. Su objetivo principal es impulsar la capacidad de una empresa agrícola para generar ingresos y crecer.</p>    
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Pagos Flexibles: Se adaptan a tus ciclos de cosecha o producción, pagas cuando tienes ingresos.</li>
<li>Tasas Competitivas: Generalmente ofrece tasas de interés más bajas gracias a su vinculación con Finagro.</li>
<li>Ahorro de Costos: Puede estar exento del 4x1000 (GMF) en los desembolsos.</li>
<li>Asesoría: Algunas entidades ofrecen acompañamiento y orientación técnica.</li>
<li>Facilita Inversión: Permite financiar desde insumos hasta maquinaria y mejoras en tu finca.</li>
      </ul>

    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
<li>Cédula de ciudadanía (persona natural)</li>
<li>Para persona jurídica (copia del RUT, certificado de existencia y representación legal)</li>
<li>Ser mayor de edad (18 años)</li>
<li>Extractos bancarios</li>
<li>Declaración renta (si aplica)</li>
<li>Si cuenta con un proyecto (plan de inversión y estados financieros)</li>
<li>Certificado cámara de comercio</li>

      </ul>

    </ul>
    </div>
  </section>



</body>
</html>



