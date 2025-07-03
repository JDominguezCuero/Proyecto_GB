<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cuentas de Ahorros</title>
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
    <h1>Cuentas de Ahorros
</h1>
  </div>
 <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P13.avif" alt="Imagen 1">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P14.jpg" alt="Imagen 2">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P15.jpg" alt="Imagen 3">
    </div>
    <div class="swiper-slide">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/P16.webp" alt="Imagen 4">
    </div>
  </div>
  <!-- Paginación (puntos) -->
  <div class="swiper-pagination"></div>
</div>







  <div class="card-container">
    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/M1.png">
      <div class="card-content">
        <h3>Cuentas de Ahorro</h3>
<P>Las cuentas de ahorro son herramientas financieras que permiten guardar dinero de forma segura, fomentando el hábito del ahorro y ayudando a alcanzar metas económicas. Existen diversos tipos según las necesidades del usuario.</P>
      </div>
      <div class="card-button">
        <a href="./Ahorro.php">Saber más <span>→</span></a>
      </div>
    </div>


    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/m2.jpg" alt="Tarjeta de crédito">
      <div class="card-content">
        <h3>Cuenta de Ahorro Básica o Estándar</h3>
<P>Permite guardar dinero con disponibilidad inmediata y genera intereses bajos. Ideal para uso cotidiano y manejo simple del dinero.</P>
    </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_Básica_o_Estándar.php">Saber más <span>→</span></a>
      </div>
    </div>


    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/m3.jpg" alt="Crédito de Consumo">
      <div class="card-content">
        <h3>Cuenta de Ahorro Programado</h3>
    <p>Permite ahorrar una cantidad fija regularmente durante un plazo definido, ayudando a alcanzar metas específicas.</p>
      </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_Programado.php">Saber más <span>→</span></a>
      </div>
    </div>

    <div class="card">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/m4.png" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Cuenta de Ahorro para Jóvenes o Niños</h3>
<P>Una cuenta de ahorro para jóvenes o niños es un producto financiero diseñado especialmente para menores de edad, con el objetivo de enseñarles a manejar el dinero y fomentar el hábito del ahorro desde temprana edad.</P>    
  </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_para_Jóvenes_o_Niños.php">Saber más <span>→</span></a>
</div>
</div>

  <div class="card" padding="9
  ">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/M5.png" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Cuenta de Ahorro de Nómina</h3>
<P>Recibe directamente el salario del empleador. Suele tener beneficios como cero cuota de manejo y retiros gratuitos.</P> 
     </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_de_Nómina.php">Saber más <span>→</span></a>
      </div>

      </div>



<div class="card" padding="9
  ">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/M6.webp" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Cuenta de Ahorro para Pensionados</h3>
<P>Especial para recibir mesadas pensionales. Ofrece beneficios como exención del 4x1000 y retiros sin costo.
</P>
    </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_para_Pensionados.php">Saber más <span>→</span></a>
      </div>

      </div>




      <div class="card" padding="9
  ">
      <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/M7.jpg" alt="Crédito Libre Inversión">
      <div class="card-content">
        <h3>Cuenta de Ahorro en Moneda Extranjera</h3>
<P>Permite ahorrar en divisas como dólares o euros. Útil para protegerse de la devaluación o hacer transacciones internacionales.</P>
    </div>
      <div class="card-button">
        <a href="./Cuenta_de_Ahorro_en_Moneda_Extranjera.php">Saber más <span>→</span></a>
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


</body>
</html>



