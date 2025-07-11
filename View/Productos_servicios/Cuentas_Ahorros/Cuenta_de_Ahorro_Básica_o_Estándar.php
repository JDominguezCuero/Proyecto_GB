<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuneta de Ahorro Basica o Estandar</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>CUENTA DE AHORRO BASICA O ESTANDAR</h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N6.png" alt="Imagen crédito" floatr="right">
  </div>
</div>




  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuneta de Ahorro Basica o Estandar?</h3>
<p>Es una cuenta bancaria que permite guardar dinero de forma segura, ganar intereses (aunque bajos) y realizar operaciones como transferencias, retiros en cajeros, pagos de servicios y compras con tarjeta débito.</p>

    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
   <li>Acceso fácil y rápido a tu dinero.</li>
   <li>Intereses sobre el saldo, aunque suelen ser bajos.</li>
   <li>Tarjeta débito para compras y retiros.</li>
   <li>Permite pagar servicios, hacer transferencias y recibir depósitos.</li>
   <li>Puedes abrirla con bajos montos iniciales.</li>
    </ul>
    </div>


    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets//Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
       <li>Ser mayor de edad o menor con autorización.</li>
       <li>Documento de identidad válido (cédula o tarjeta de identidad).</li>
       <li>Formulario de apertura.</li>
       <li>Aportar un monto mínimo de apertura (puede ser desde $10.000 COP).</li>
       <li>Certificado de ingresos o actividad económica (aunque no siempre se exige).</li>
      </ul>
    </div>
  </section>

    <?php include '../../public/layout/mensajesModal.php'; ?>
  <?php include '../../public/layout/frontendBackend.php'; ?>
  <?php include '../../public/layout/layoutfooter.php'; ?>

</body>
</html>