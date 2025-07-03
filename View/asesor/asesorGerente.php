<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Simulador de Cajero Financiero</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f2f4f8;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #1d3557;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      max-width: 900px;
      margin: 20px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }
    input[type="text"] {
      padding: 10px;
      width: 100%;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #457b9d;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #1d3557;
    }
    .info-box {
      background-color: #e0f0ff;
      border-left: 6px solid #2196F3;
      padding: 15px;
      margin: 20px 0;
      border-radius: 8px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ccc;
    }
    th {
      background-color: #1d3557;
      color: white;
    }
    tr.cancelada {
      background-color: #d4edda !important;
    }
    .select-cuota {
      padding: 5px 10px;
      background-color: #2a9d8f;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .select-cuota:hover {
      background-color: #21867a;
    }
  </style>
</head>
<body>
  <header>
    <h1>Panel del Cajero</h1>
  </header>

  <div class="container">
    <div class="form-group">
      <label for="documento">Buscar cliente por Documento</label>
      <input type="text" id="documento" placeholder="Ingrese número de documento" />
      <button onclick="consultarCliente()">Consultar</button>
    </div>

    <div id="cliente-info" style="display:none;">
      <div class="info-box">
        <strong>Cliente:</strong> <span id="nombre-cliente"></span><br>
        <strong>Producto:</strong> <span id="producto-cliente"></span><br>
        <strong>Monto:</strong> <span id="monto-cliente"></span>
      </div>

      <h3>Tabla de Amortización</h3>
      <table id="tabla-cuotas">
        <thead>
          <tr>
            <th># Cuota</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <!-- JS rellenará filas aquí -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const cuotas = [
      { numero: 1, fecha: '2025-07-15', monto: 200000, estado: 'Pendiente' },
      { numero: 2, fecha: '2025-08-15', monto: 200000, estado: 'Pendiente' },
      { numero: 3, fecha: '2025-09-15', monto: 200000, estado: 'Pendiente' }
    ];

    function consultarCliente() {
      const documento = document.getElementById('documento').value.trim();
      if (documento === '') {
        alert('Por favor ingrese un documento.');
        return;
      }

      // Simulación de datos del cliente
      document.getElementById('nombre-cliente').textContent = 'Juan Pérez';
      document.getElementById('producto-cliente').textContent = 'Crédito de Libre Inversión';
      document.getElementById('monto-cliente').textContent = '$600,000';
      document.getElementById('cliente-info').style.display = 'block';

      const tbody = document.querySelector('#tabla-cuotas tbody');
      tbody.innerHTML = '';
      cuotas.forEach(cuota => {
        const fila = document.createElement('tr');
        fila.id = 'cuota-' + cuota.numero;
        fila.innerHTML = `
          <td>${cuota.numero}</td>
          <td>${cuota.fecha}</td>
          <td>$${cuota.monto.toLocaleString()}</td>
          <td id="estado-${cuota.numero}">${cuota.estado}</td>
          <td><button class="select-cuota" onclick="abonarCuota(${cuota.numero})">Abonar</button></td>
        `;
        tbody.appendChild(fila);
      });
    }

    function abonarCuota(numero) {
      const confirmacion = confirm(`¿Deseas marcar la cuota ${numero} como cancelada?`);
      if (!confirmacion) return;

      document.getElementById('estado-' + numero).textContent = 'Cancelada';
      document.getElementById('cuota-' + numero).classList.add('cancelada');
    }
  </script>
</body>
</html>
