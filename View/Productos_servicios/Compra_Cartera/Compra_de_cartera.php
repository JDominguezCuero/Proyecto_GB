<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de cartera (para personas naturales)</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y10.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Compra de cartera (para personas naturales)?</h3>
      <p>El crédito de compra de cartera es un tipo de préstamo que permite trasladar tus deudas de otros bancos a uno nuevo para mejorar condiciones: pagar menos interés, tener cuotas mas bajas, plazos mas largos y unificar todo en una sola deuda.</p>
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
</div>






    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requerimientos</h3>
      <ul>
       <li>Cédula de ciudadanía o extranjería (original y copia).</li>
    <li>Formulario proporcionado por la entidad financiera.</li>
    <li>Certificación laboral.</li>
    <li>Carta laboral con fecha de expedición no mayor a 30 días, indicando salario, cargo y antigüedad.</li>
    <li>Declaración de renta o estados financieros certificados por contador.</li>
    <li>Extractos bancarios (últimos 3 meses).</li>
    <li>Comprobantes de ingresos.</li>
    <li>Desprendibles de nómina (empleados).</li>
    <li>Contratos, certificados de ingresos o estados financieros (independientes).</li>
    <li>Carta de saldo de la entidad actual: Documento que certifica el valor exacto de la deuda a trasladar.</li>
    </div>
  </section>

  <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>