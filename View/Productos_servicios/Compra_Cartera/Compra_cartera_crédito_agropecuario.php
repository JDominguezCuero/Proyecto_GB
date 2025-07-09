<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compra de cartera de crédito agropecuario.</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>SOLICITA TU COMPRA DE CARTERA DE CRÉDITO AGROPECUARIO HOY <br> CON NOSOTROS TU AMIGO Y BANCO DE CONFIANZA</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/Y3.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Compra de cartera de crédito agropecuario?</h3>
      <p>Es un producto financiero que permite a productores del sector agropecuario trasladar sus deudas actuales (créditos agrícolas, pecuarios, rurales, etc.) desde otra entidad a una nueva, con el objetivo de mejorar condiciones como tasa de interés, plazo, cuotas o unificar obligaciones.
</p>
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
      <li>Documento de identidad del titular o representante.</li>
  <li>Registro como productor agropecuario (si aplica).</li>
  <li>RUT actualizado.</li>
  <li>Carta de saldo de la deuda agropecuaria a trasladar.</li>
  <li>Certificado de ingresos o estados financieros.</li>
  <li>Declaración de renta (si aplica).</li>
  <li>Extractos bancarios de los últimos meses.</li>
  <li>Certificación de la actividad productiva (verificada por visita o asesoría en campo).</li>
  <li>En algunos casos, referencias comerciales del sector.</li>
      </ul>
    </div>
  </section>

</body>
</html>