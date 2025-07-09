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
    <h1>Créditos De Libranza </h1>
  </div>
 <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J13.jpg" alt="Imagen 1">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J14.jpg" alt="Imagen 2">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J15.jpg" alt="Imagen 3">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/J16.jpg" alt="Imagen 4">
    </div>
  </div>
  <!-- Paginación (puntos) -->
  <div class="swiper-pagination"></div>
</div>







  <div class="card-container">
    <div class="card empty"></div>
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C6.png" alt="Crédito de libranza">
      <div class="card-content">
        <h3>Crédito de descuento por nómina o mesada pencional</h3>
        <p>Con pagos automáticos descontados del salario o la pensión mediante un convenio con su empresa, este método elimina la necesidad de contar con fiadores, simplificando el proceso de aprobación y ofreciendo una tasa fija que permite financiar proyectos personales.
</p>
      </div>
      <div class="card-button">
        <a href="./Credito_libranza.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/C5.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de libranza</h3>
        <p>Permite unificar y trasladar tus créditos por libranza desde otra entidad para acceder a una mejor tasa o una menor cuota mensual.</p>
      </div>
      <div class="card-button">
        <a href="../creditos_descuento_nomina/compra_cartera_libranza.php">Saber más <span>→</span></a>
      </div>
    </div>
<div class="card empty"></div>
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