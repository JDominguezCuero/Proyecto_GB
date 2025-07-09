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
    <h1>Créditos Educativos</h1>
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
</div>

<div class="card-container">
  <div class="card">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y11.png" alt="Crédito de libranza">
    <div class="card-content">
      <h3>Crédito educativo a corto plazo</h3>
      <p>Cubre hasta el 100% de la matrícula en Colombia o el exterior. Puedes reutilizar el cupo a medida que pagas, y financiar desde uno hasta todos los periodos académicos. Se paga en 6 a 12 meses y va desde $700.000 hasta 25 SMLMV. Ideal para formación continua en plazos breves.</p>
    </div>
    <div class="card-button">
      <a href="./Crédito_estudiantil_educativo.php">Saber más <span>→</span></a>
    </div>
  </div>

  <div class="card">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y13.png" alt="Compra de cartera de libranza">
    <div class="card-content">
      <h3>Crédito libre educativo</h3>
      <p>El Crédito Libre Educativo permite financiar estudios en Colombia con un solo desembolso a la institución. Se paga en 24, 36 o 48 meses y ofrece montos desde $700.000 hasta 25 SMLMV. Es flexible y se adapta a tus necesidades.</p>
    </div>
    <div class="card-button">
      <a href="./Crédito_libre_educativo.php">Saber más <span>→</span></a>
    </div>
  </div>

  <div class="card">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y15.png" alt="Compra de cartera de libranza">
    <div class="card-content">
      <h3>Crediestudiantil</h3>
      <p>Este crédito financia hasta el 100 % de la matrícula para estudios técnicos, tecnológicos, de pregrado y posgrado en Colombia. El plazo de pago depende del tipo de programa: 6 meses para pregrados semestrales, 12 meses para programas anuales, y de 12 a 36 meses para diplomados o posgrados.</p>
    </div>
    <div class="card-button">
      <a href="./Crediestudiantil.php">Saber más <span>→</span></a>
    </div>
  </div>

  <div class="card">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y17.png" alt="Compra de cartera de libranza">
    <div class="card-content">
      <h3>Compra de cartera de crédito de educación</h3>
      <p>Es un producto financiero que te permite trasladar tu crédito educativo actual desde otra entidad hacia un nuevo banco o cooperativa.</p>
    </div>
    <div class="card-button">
      <a href="./Compra_cartera_crédito_educación.php">Saber más <span>→</span></a>
    </div>
  </div>
</div> <!-- ← ESTE DIV FALTABA -->

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