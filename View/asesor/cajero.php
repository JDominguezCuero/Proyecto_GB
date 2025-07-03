<?php
session_start();
require_once(__DIR__ . '../../../Config/config.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Simulador de Cajero</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
    }

    h2 {
      color: #2e7d32;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }
    input[type="text"] {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background-color: #2e7d32;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background-color: #1b5e20;
    }
    .info {
      margin-top: 20px;
      background: #e8f5e9;
      padding: 15px;
      border-radius: 8px;
      display: none;
    }
    .info p {
      margin: 4px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    tr.cancelada {
      background-color: #c8e6c9;
      text-decoration: line-through;
    }
    .acciones {
      margin-top: 10px;
      text-align: right;
    }

    .contenedor-banco {
      background-color: white;
      padding: 30px;
      margin: 30px auto 0 auto;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 900px;
    }
  </style>
</head>
<body>
  <?php include '../public/layout/barraNavAsesor.php'; ?>

<div class="container contenedor-banco">
  <h2>Simulador de Cajero</h2>

  <div class="form-group">
    <label for="documento">Buscar Cliente por Documento:</label>
    <input type="text" id="documento" placeholder="Ingrese número de documento" />
  </div>
  <button onclick="consultarCliente()">Consultar</button>

  <div class="info" id="clienteInfo">
    <h3>Información del Cliente</h3>
    <p><strong>Nombre:</strong> <span id="nombreCliente"></span></p>
    <p><strong>Producto:</strong> <span id="productoCliente"></span></p>
    <p><strong>Monto:</strong> $<span id="montoCredito"></span></p>
    <p><strong>Cuotas:</strong> <span id="numCuotas"></span></p>
    <p><strong>Tasa Anual:</strong> <span id="tasaAnual"></span>%</p>
    <p><strong>Periodicidad:</strong> <span id="periodicidad"></span></p>

    <div id="tasaPeriodica"></div>
    <div id="valorCuota"></div>

    <div id="tabla-amortizacion" style="margin-top: 20px;"></div>

    <div class="acciones">
      <button onclick="abonarCuota()">Abonar Cuota Seleccionada</button>
    </div>
  </div>
</div>

  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>

<script>
  const dataSimulada = {
    "123456789": {
      nombre: "Juan Pérez",
      producto: "Crédito Consumo - Moto",
      monto: 600000,
      cuotas: 4,
      tasaAnual: 12, // en porcentaje
      periodicidad: "Mensual",
      canceladas: [] // guarda índices de cuotas canceladas
    }
  };

  function consultarCliente() {
    const doc = document.getElementById("documento").value;
    const cliente = dataSimulada[doc];
    const infoDiv = document.getElementById("clienteInfo");

    if (!cliente) {
      alert("Cliente no encontrado.");
      infoDiv.style.display = "none";
      return;
    }

    // Mostrar datos generales
    document.getElementById("nombreCliente").textContent = cliente.nombre;
    document.getElementById("productoCliente").textContent = cliente.producto;
    document.getElementById("montoCredito").textContent = cliente.monto.toLocaleString();
    document.getElementById("numCuotas").textContent = cliente.cuotas;
    document.getElementById("tasaAnual").textContent = cliente.tasaAnual;
    document.getElementById("periodicidad").textContent = cliente.periodicidad;

    // Calcular tasa periódica
    const tasaNominal = cliente.tasaAnual / 100;
    let tasaPeriodica = 0;

    switch (cliente.periodicidad) {
      case "Diaria": tasaPeriodica = tasaNominal / 365; break;
      case "Mensual": tasaPeriodica = tasaNominal / 12; break;
      case "Bimensual": tasaPeriodica = tasaNominal / 6; break;
      case "Trimestral": tasaPeriodica = tasaNominal / 4; break;
      case "Semestral": tasaPeriodica = tasaNominal / 2; break;
      case "Anual": tasaPeriodica = tasaNominal; break;
    }

    const monto = cliente.monto;
    const cuotas = cliente.cuotas;

    // Calcular cuota
    const cuota = monto * ((tasaPeriodica * Math.pow(1 + tasaPeriodica, cuotas)) / (Math.pow(1 + tasaPeriodica, cuotas) - 1));

    document.getElementById("tasaPeriodica").innerHTML =
      `📈 Tasa periódica (${cliente.periodicidad}): <strong>${(tasaPeriodica * 100).toFixed(4)}%</strong>`;
    document.getElementById("valorCuota").innerHTML =
      `💰 Valor cuota estimada: <strong>$${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</strong>`;

    // Generar tabla
    let saldo = monto;
    let tablaHTML = `
      <h4>Tabla de Amortización</h4>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Valor Cuota</th>
            <th>Capital</th>
            <th>Intereses</th>
            <th>Saldo</th>
            <th>Seleccionar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>0</td>
            <td>$0</td>
            <td>$0</td>
            <td>$0</td>
            <td>$${saldo.toLocaleString('es-CO')}</td>
            <td></td>
          </tr>
    `;

    for (let i = 1; i <= cuotas; i++) {
      const interes = saldo * tasaPeriodica;
      const abonoCapital = cuota - interes;
      saldo -= abonoCapital;
      const cancelada = cliente.canceladas.includes(i);

      tablaHTML += `
        <tr class="${cancelada ? 'cancelada' : ''}">
          <td>${i}</td>
          <td>$${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
          <td>$${abonoCapital.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
          <td>$${interes.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
          <td>$${saldo > 0 ? saldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'}</td>
          <td><input type="radio" name="cuotaSeleccionada" value="${i}" ${cancelada ? 'disabled' : ''}></td>
        </tr>
      `;
    }

    tablaHTML += `</tbody></table>`;
    document.getElementById("tabla-amortizacion").innerHTML = tablaHTML;

    infoDiv.style.display = "block";
  }

  function abonarCuota() {
    const doc = document.getElementById("documento").value;
    const cliente = dataSimulada[doc];
    if (!cliente) return;

    const seleccion = document.querySelector('input[name="cuotaSeleccionada"]:checked');
    if (!seleccion) {
      alert("Seleccione una cuota para abonar.");
      return;
    }

    const index = parseInt(seleccion.value);
    if (!cliente.canceladas.includes(index)) {
      cliente.canceladas.push(index);
    }

    consultarCliente(); // Recarga con cambios
    alert(`Cuota #${index} abonada exitosamente.`);
  }
</script>
</body>
</html>