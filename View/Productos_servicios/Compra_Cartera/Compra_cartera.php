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







  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O1.png" alt="Crédito de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito hipotecario</h3>
        <p>Permite trasladar tu crédito de vivienda actual desde otra entidad financiera para mejorar condiciones como tasa de interés, plazo o cuota mensual.</p>
      </div>
      <div class="card-button">
        <a href="../Creditos_Hipotecarios_y_de_Vivienda/Compra_de_Cartera_de_Credito_Hipotecario.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O2.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de vehículo</h3>
        <p>Consiste en trasladar tu crédito vehicular actual a otra entidad que te ofrezca mejores condiciones de pago, tasas más bajas o mayor plazo.</p>
      </div>
      <div class="card-button">
        <a href="../Créditos_Vehiculares/Compra_cartera_Vehiculo.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O3.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de microcrédito</h3>
        <p>Traslada microcréditos obtenidos para pequeños negocios a otra entidad que ofrezca mejores tasas o plazos, facilitando el pago.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_de_cartera_microcrédito.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O4.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito de educación</h3>
        <p>Es un producto financiero que te permite trasladar tu crédito educativo actual desde otra entidad hacia un nuevo banco o cooperativa.</p>
      </div>
      <div class="card-button">
        <a href="../Créditos_educativos/Compra_cartera_crédito_educación.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O5.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de libranza</h3>
        <p>Permite unificar y trasladar tus créditos por libranza desde otra entidad para acceder a una mejor tasa o una menor cuota mensual.</p>
      </div>
      <div class="card-button">
        <a href="../Creditos_descuento_nomina/Compra_cartera_libranza.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O6.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito agropecuario</h3>
        <p>Traslada créditos agropecuarios a otra entidad financiera para acceder a mejores condiciones y facilitar la sostenibilidad del negocio rural.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_crédito_agropecuario.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O7.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito comercial (grandes empresas)</h3>
        <p>Dirigido a grandes empresas que desean trasladar sus obligaciones crediticias a otro banco con mejores condiciones, optimizando su flujo de caja.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_crédito_comercial .php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O8.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito de libre inversión</h3>
        <p>Permite trasladar un crédito de libre inversión a otra entidad para acceder a menores tasas, cuotas más cómodas o mejores plazos.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_crédito_libre_inversión.php">Saber más <span>→</span></a>
      </div>
    </div>
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O9.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito de consumo</h3>
        <p>Permite consolidar créditos de consumo (como préstamos personales) en una sola entidad, mejorando condiciones y reduciendo el valor de la cuota.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_crédito_consumo.php">Saber más <span>→</span></a>
      </div>
    </div>
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O10.jpg" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de tarjetas de crédito</h3>
        <p>Traslada el saldo de tus tarjetas de crédito a un nuevo crédito con menor tasa de interés y un plan de pagos fijo.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_tarjetas_crédito.php">Saber más <span>→</span></a>
      </div>
    </div>
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O11L.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera de crédito rotativo</h3>
        <p>Permite unificar saldos de créditos rotativos (líneas de crédito que se renuevan) para pagar en cuotas fijas y con menor interés.</p>
      </div>
      <div class="card-button">
        <a href="./Compra_cartera_crédito_rotativo.php">Saber más <span>→</span></a>
      </div>
    </div>
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/O12.png" alt="Compra de cartera de libranza">
      <div class="card-content">
        <h3>Compra de cartera (para personas naturales)</h3>
        <p>Es la opción para personas que desean unificar diferentes tipos de créditos personales (hipotecario, consumo, libranza, etc.) en una sola entidad con mejores condiciones.
</p>
      </div>
      <div class="card-button">
        <a href="./Compra_de_cartera.php">Saber más <span>→</span></a>
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