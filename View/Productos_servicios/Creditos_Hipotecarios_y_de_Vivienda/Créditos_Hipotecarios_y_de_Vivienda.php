<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créditos Hipotecarios y de Vivienda</title>
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
    <h1>Créditos Hipotecarios y de Vivienda</h1>
  </div>
 <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P5.png" alt="Imagen 1">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P6.png" alt="Imagen 2">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P7.png" alt="Imagen 3">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P8.png" alt="Imagen 4">
    </div>
  </div>
  <!-- Paginación (puntos) -->
  <div class="swiper-pagination"></div>
</div>







  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/c5.png" alt="Crédito Rotativo">
      <div class="card-content">
        <h3>Crédito Hipotecario</h3>
<p> Préstamo otorgado para comprar una vivienda, usando el inmueble como garantía.</p>      </div>
      <div class="card-button">
        <a href="./Credito_Hipotecario.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C6.png" alt="Tarjeta de crédito">
      <div class="card-content">
        <h3>Crédito de Vivienda</h3>
<p>Un Credito de VIvienda es un credito otorgado por bancos, copertivas u otras entidades financieras para la adquisicion, construccion o mejora de una vivienda. </p>      </div>
      <div class="card-button">
        <a href="./Crédito_de_Vivienda.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/c7.png" alt="Crédito de Consumo">
      <div class="card-content">
        <h3>Compra de Cartera de Crédito Hipotecario</h3>
<p>Permite trasladar un crédito hipotecario existente a otra entidad financiera que ofrezca mejores condiciones (tasa de interés, plazo, etc.).</p>      </div>
      <div class="card-button">
        <a href="./Compra_de_Cartera_de_Credito_Hipotecario.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos//A8.jpg" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Compra de Cartera de Vivienda</h3>
<p>Similar a la anterior, pero incluye la posibilidad de trasladar contratos de leasing habitacional.</p>      </div>
      <div class="card-button">
        <a href="./Compra_de_Cartera_de_Vivienda.php">Saber más <span>→</span></a>
      </div>
    </div>
  </div>


</div>

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
