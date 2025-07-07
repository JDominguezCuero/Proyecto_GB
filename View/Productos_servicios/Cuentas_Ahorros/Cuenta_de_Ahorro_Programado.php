<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuenta de Ahorro Programado</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Cuenta de Ahorro Programado</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N5.avif" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorro Programado?</h3>
<p>Es una cuenta en la que el cliente consigna periódicamente (diaria, semanal o mensualmente) una suma acordada, con el objetivo de acumular un capital para una meta específica, como estudios, un viaje, una vivienda, etc.</p>

    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>

<li>Tasa de interés más alta que la de una cuenta de ahorro tradicional.</li>
<li>Fomenta el ahorro constante y planificado.</li>
<i>Puede servir como respaldo para créditos o subsidios de vivienda (como el programa Mi Casa Ya).</i>
<li>No cobran cuota de manejo.</li>
<li>Algunas cuentas permiten bloquear los retiros hasta cumplir la meta, ayudando a evitar el uso impulsivo del dinero.</li>
</ul>
    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
<li>Ser mayor de edad (o menor con tutor legal).</li>
<li>Documento de identidad válido.</li>
<li>Establecer una meta de ahorro (monto y plazo).</li>
<li>Definir el valor y frecuencia del aporte.</li>
<li>Se requiere tener una cuenta de ahorro activa en el mismo banco.</li>
      </ul>
    </div>
  </section>

</body>
</html>