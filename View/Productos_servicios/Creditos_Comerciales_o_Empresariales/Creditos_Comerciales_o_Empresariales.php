<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créditos Productivos o Empresariales</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

  <!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="panel">
  <div class="panel-texto">
    <h1>Créditos Productivos o Empresariales</h1>
  </div>
 <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P9.jpg" alt="Imagen 1">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P11.jpeg" alt="Imagen 2">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P10.jpeg" alt="Imagen 3">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P12.webp" alt="Imagen 4">
    </div>
  </div>
  <!-- Paginación (puntos) -->
  <div class="swiper-pagination"></div>
</div>







  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/AA1.png">
      <div class="card-content">
        <h3>Microcrédito</h3>
<p>Préstamo de bajo monto dirigido a personas o microempresas con acceso a la banca tradicional, ideal para iniciar o fortalecer pequeños negocios.</p>      </div>
      <div class="card-button">
        <a href="./Microcredito.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A12.png" alt="Tarjeta de crédito">
      <div class="card-content">
        <h3>Crédito Comercial</h3>
        <p>Financiamiento flexible para emprendedores o microempresarios que buscan invertir en actividades productivas como comercio, servicios o industria.</p>
      </div>
      <div class="card-button">
        <a href="./Credito_Productivo.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A13.png" alt="Crédito de Consumo">
      <div class="card-content">
        <h3>Crédito Agrofácil</h3>
    <p>Crédito ofrecido por Bancolombia para productores agropecuarios, con condiciones adaptadas al ciclo productivo y apoyo técnico especializado.</p>
      </div>
      <div class="card-button">
        <a href="./Credito_Agrofacil.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A14.jpeg" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Crédito Finagro</h3>
    <p>Línea de crédito con recursos de fomento para actividades agropecuarias y rurales, con tasas preferenciales y apoyo del Gobierno.</p>
      </div>
      <div class="card-button">
        <a href="./Credito_Finagro.php">Saber más <span>→</span></a>
</div>
</div>

  <div class="card" padding="9
  ">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a02.jpg" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>CDT</h3>
        <p>Préstamo respaldado por un Certificado de Depósito a Término (CDT), que permite acceder a liquidez sin perder la inversión.</p>
      </div>
      <div class="card-button">
        <a href="./Credito_de_CDT.php">Saber más <span>→</span></a>
      </div>

      </div>
    </div>
 
</div>
 
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
</script>
  <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>
