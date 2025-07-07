<?php
session_start();
  require_once(__DIR__ . '../../../../Config/config.php');
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tarjeta de Credito.
  </title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/stilo.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">

</head>
<body>

<!-- Header -->
  <?php include '../../public/layout/barraNavAsesor.php'; ?>

<div class="porque">
  <div class="texto-encabezado">
    <h1>Solicita la tuya hoy <br>mismo con inteligencia </h1>
  </div>
  <div class="imagen-encabezado">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/N4.jpg" alt="Imagen crédito" floatr="right">
  </div>
</div>

  <section class="info-grid">
   
  <div class="info-box">
  
<center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/a1.png" alt=""></center>
      <h3>¿Qué es una Cuenta de Ahorro?</h3>
      <p>Es un producto financiero que permite a los usuarios depositar dinero y obtener intereses sobre el saldo ahorrado. Las cuentas de ahorro son ideales para guardar dinero de forma segura y acceder a él fácilmente cuando se necesita.</p>
    </div>

    <div class="info-box">
      <center>  <img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A3.png" alt="" ></center>
      <h3>Beneficios</h3>
      <ul>
<li>Seguridad: Tus fondos están protegidos de robos o pérdidas, a diferencia del efectivo. Las entidades bancarias suelen contar con seguros de depósito que salvaguardan tu dinero hasta cierto límite.</li>
<li>Facilidad de Acceso a Fondos: Permite acceder a tu dinero en cualquier momento a través de cajeros automáticos, transferencias electrónicas o pagos con tarjeta débito, haciendo las transacciones diarias más convenientes.</li>
<li>Organización Financiera: Ayuda a separar los gastos personales o del negocio, facilita el seguimiento de ingresos y egresos, y permite establecer metas de ahorro.</li>
<li>Acceso a Otros Productos Bancarios: Tener una cuenta de ahorro es el primer paso para acceder a otros servicios financieros como créditos, tarjetas de crédito, inversiones y más, construyendo un historial bancario.</li>



</ul>
<strong><p></p></strong>
    </div>

    <div class="info-box">
        <center><img src="<?= BASE_URL ?>/View/public/assets/Img/Creditos/A2.png" alt=""></center>
      <h3>Requisitos</h3>
      <ul>
     <li>Ser mayor de edad</li>
        <li>Cédula de ciudadanía, extranjería o tarjeta de identidad (autorización firmada por los padres)</li>
        <li>Formulario de vinculación</li>
        <li>RUT (si aplica)</li>
      </ul>
    </div>
  </section>

</body>
</html>