<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de cartera de crédito de consumo</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA DE CREDITO DE CONSUMO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y6.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Compra de cartera de crédito de consumo?</h3>
      <p>Es un producto financiero que te permite trasladar uno o varios créditos de consumo que tienes en otras entidades hacia una nueva institución financiera, con el objetivo de mejorar las condiciones de pago. Esto puede significar una tasa de interés más baja, cuotas mensuales más bajas, o plazos más amplios.
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
      <li>Documento de identidad.</li>
  <li>Formulario de solicitud de crédito.</li>
  <li>Carta de saldo con las condiciones del crédito (saldo total, tasa, plazo restante).</li>
  <li>Certificación laboral o comprobantes de ingresos.</li>
  <li>Extractos bancarios recientes.</li>
  <li>Información detallada del crédito a trasladar (entidad, número del crédito, condiciones actuales).</li>
    </ul>
    </div>
  
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>