<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Credito CDT</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Credito CDT</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/X12.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es un Credito CDT?</h3>
<P>Un crédito de CDT (Certificado de Depósito a Término) es un producto financiero ofrecido por entidades bancarias en el cual una persona invierte su dinero a un plazo fijo a cambio de un interés. Este interés puede ser fijo o variable y depende del plazo y monto invertido</P>    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Seguridad</li>
<li>Rentabilidad fija</li>
<li>Planificacion financiera</li>
<li>Divercificacion</li>
<li>Proteccion frente la inflacion</li>
      </ul>

    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
        <li>El solicitante debe de ser mayor de edad</li>
<li>Tener un CDT activo</li>
<li>Monto mínimo del CDT</li>
<li>Identificación personal</li>
<li>Comprobantes de ingresos (opcional)</li>
<li>Firma de pagaré o contrato</li>
<li>Solicitud formal del crédito</li>
<li>Plazo del crédito</li>
      </ul>

    </ul>
    </div>
  </section>

  <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>
