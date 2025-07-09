<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de cartera de crédito comercial (grandes empresas)</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA DE CREDITO COMERCIAL HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y4.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Compra de cartera de crédito comercial?</h3>
      <p>Es un producto financiero que permite a las empresas trasladar sus deudas actuales (préstamos, líneas de crédito, leasing, etc.) de diferentes entidades a una sola entidad financiera.</p>
    </div>
    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
        <li>Menos intereses: Accede a tasas más bajas y paga menos en total.</li>
    <li>Cuotas más bajas: Mejora tus condiciones y alarga el plazo para pagar menos cada mes.</li>
    <li>Una sola deuda: Unifica tus créditos y paga en una sola fecha.</li>
    <li>Mejor historial crediticio: Organiza tus pagos y mejora tu perfil financiero.</li>
    <li>Más dinero disponible: Libera tu flujo de caja para otros gastos o proyectos.</li>
    <li>Trámite fácil y rápido: Proceso ágil, con pocos requisitos.</li>
    <li>Evita moras y cobros extra: Reduce el riesgo de retrasos y penalizaciones.</li>
    <li>Condiciones ajustadas a ti: Posibilidad de plazos más largos o periodos de gracia.</li>
       </ul>
       </ul>
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
      <li>RUT actualizado.</li>
  <li>Cámara de Comercio vigente (no mayor a 30 días).</li>
  <li>Documento de identidad del representante legal.</li>
  <li>Estados financieros de los últimos 2 años, firmados por contador público.</li>
  <li>Extractos bancarios de los últimos 3 o 6 meses.</li>
  <li>Declaración de renta del último año.</li>
  <li>Relación de créditos a trasladar con cartas de saldo de cada entidad.</li>
  <li>Flujo de caja proyectado (en algunos casos).</li>
  <li>Certificado de paz y salvo (si el crédito ya fue cancelado).</li>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>