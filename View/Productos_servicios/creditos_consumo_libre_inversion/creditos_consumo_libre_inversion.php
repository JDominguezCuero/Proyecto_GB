<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tipos de Créditos</title>
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
    <h1>Créditos de Consumo  y Libre Inversión</h1>
  </div>
 <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P2.png" alt="Imagen 1">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P3.png" alt="Imagen 2">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P4.png" alt="Imagen 3">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P1.png" alt="Imagen 4">
    </div>
  </div>
  <!-- Paginación (puntos) -->
  <div class="swiper-pagination"></div>
</div>







  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C1.png" alt="Crédito Rotativo">
      <div class="card-content">
        <h3>Crédito Rotativo</h3>
        <p>Disponible para tus compras y avances cuando lo necesites. Flexibilidad en pagos y uso.</p>
      </div>
      <div class="card-button">
        <a href="./Credito_Rotativo.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C4.png" alt="Tarjeta de crédito">
      <div class="card-content">
        <h3>Tarjeta de crédito</h3>
        <p>Usa tu tarjeta de crédito como quieras y disfrútala.</p>
      </div>
      <div class="card-button">
        <a href="../creditos_consumo_libre_inversion/Tarjeta_de_Credito.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C2.png" alt="Crédito de Consumo">
      <div class="card-content">
        <h3>Crédito de Consumo</h3>
        <p>Financia gastos personales como viajes, educación o tecnología con tasas preferenciales.</p>
      </div>
      <div class="card-button">
        <a href="../creditos_consumo_libre_inversion/Credito_de_Consumo.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C3.png" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Crédito Libre Inversión</h3>
        <p>Usa el crédito como quieras: remodela, paga deudas o realiza tus planes personales.</p>
      </div>
      <div class="card-button">
        <a href="credito_libre.php">Saber más <span>→</span></a>
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


</body>
</html>
