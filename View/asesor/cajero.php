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

    /* Estilos para el mensaje de carga */
    #loadingMessage {
        display: none; /* Oculto por defecto */
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        text-align: center;
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
 <button id="abonarCuotaBtn" onclick="abonarCuota()">Abonar Cuota Seleccionada</button>
    <div id="loadingMessage">Procesando abono... Por favor espere.</div>
 </div>
</div>
</div>

<?php include '../public/layout/frontendBackend.php'; ?>
<?php include '../public/layout/layoutfooter.php'; ?>

<script>
function consultarCliente() {
 const documento = document.getElementById("documento").value;
 if (!documento) {
  alert("Ingrese un documento válido");
  return;
 }

 fetch("<?= BASE_URL ?>/Controlador/asesorController.php?accion=Simulador_Cajero", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ documento: documento })
 })
 .then(res => {
  if (!res.ok) {
   throw new Error(`HTTP error! status: ${res.status}`);
  }
  return res.json();
 })
 .then(data => {
  if (!data.exito) {
   alert(data.mensaje || "Cliente no encontrado");
   document.getElementById("clienteInfo").style.display = "none";
   return;
  }

  const cliente = data.cliente;
  const credito = data.credito;
  const cuotas = data.cuotas;

  document.getElementById("nombreCliente").textContent = cliente.Nombre_Cliente + ' ' + cliente.Apellido_Cliente;
  document.getElementById("productoCliente").textContent = credito.NombreProducto; // Usar NombreProducto
  document.getElementById("montoCredito").textContent = parseInt(credito.Monto_Total_Credito).toLocaleString();
  document.getElementById("numCuotas").textContent = cuotas.length;
  document.getElementById("tasaAnual").textContent = credito.Tasa_Interes_Anual;
  document.getElementById("periodicidad").textContent = credito.Periodicidad;

  const tasaNominal = parseFloat(credito.Tasa_Interes_Anual) / 100;
  let tasaPeriodica = tasaNominal / 12;

  if (credito.Periodicidad === 'Trimestral') tasaPeriodica = tasaNominal / 4;
  else if (credito.Periodicidad === 'Semestral') tasaPeriodica = tasaNominal / 2;
  else if (credito.Periodicidad === 'Anual') tasaPeriodica = tasaNominal;

  let cuotaValor = 0;
  if (cuotas.length > 0) {
   cuotaValor = parseFloat(cuotas[0].Monto_Total_Cuota);
  } else {
   console.warn("No se encontraron cuotas para este crédito.");
   alert("No se encontraron cuotas de amortización para este crédito.");
  }

  document.getElementById("tasaPeriodica").innerHTML = `📈 Tasa periódica (${credito.Periodicidad}): <strong>${(tasaPeriodica * 100).toFixed(4)}%</strong>`;
  document.getElementById("valorCuota").innerHTML = `💰 Valor cuota estimada: <strong>$${cuotaValor.toLocaleString()}</strong>`;

  let saldo = parseFloat(credito.Monto_Total_Credito);
  let tablaHTML = `
   <h4>Tabla de Amortización</h4>
   <table>
    <thead>
     <tr>
      <th>#</th>
      <th>Valor Cuota</th>
      <th>Capital</th>
      <th>Intereses</th>
      <th>Monto Pagado</th> <th>Fecha Pago</th> <th>Días Mora</th> <th>Recargo Mora</th> <th>Estado</th> <th>Saldo</th>
      <th>Seleccionar</th>
     </tr>
    </thead>
    <tbody>
     <tr>
      <td>0</td>
      <td>$0</td>
      <td>$0</td>
      <td>$0</td>
      <td>$0</td> <td>N/A</td> <td>0</td> <td>$0</td> <td>N/A</td> <td>$${saldo.toLocaleString('es-CO')}</td>
      <td></td>
     </tr>`;

  cuotas.forEach((cuota, i) => {
   // Estado de la cuota viene de 'Estado_Cuota'
   const cancelada = cuota.Estado_Cuota === 'Pagado'; // <-- Usa 'Pagado' para la clase
   saldo -= parseFloat(cuota.Monto_Capital); // El saldo se ajusta con el capital de la cuota

   // Formatear la fecha de pago
   const fechaPagoDisplay = cuota.Fecha_Pago ? new Date(cuota.Fecha_Pago).toLocaleDateString('es-CO') : 'Pendiente';
   // Formatear montos para mostrar con comas
   const montoPagadoDisplay = parseFloat(cuota.Monto_Pagado).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
   const montoRecargoMoraDisplay = parseFloat(cuota.Monto_Recargo_Mora).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});


   tablaHTML += `
    <tr class="${cancelada ? 'cancelada' : ''}">
     <td>${cuota.Numero_Cuota}</td>
     <td>$${parseFloat(cuota.Monto_Total_Cuota).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td> <!-- CAMBIO CLAVE AQUÍ -->
     <td>$${parseFloat(cuota.Monto_Capital).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
     <td>$${parseFloat(cuota.Monto_Interes).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
     <td>$${montoPagadoDisplay}</td> <td>${fechaPagoDisplay}</td> <td>${cuota.Dias_Mora_Al_Pagar}</td> <td>$${montoRecargoMoraDisplay}</td> <td>${cuota.Estado_Cuota}</td> <td>$${saldo > 0 ? saldo.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0'}</td>
     <td><input type="radio" name="cuotaSeleccionada" value="${cuota.ID_CuotaCredito}" ${cancelada ? 'disabled' : ''}></td>
    </tr>`;
  });

  tablaHTML += `</tbody></table>`;
  document.getElementById("tabla-amortizacion").innerHTML = `
  <div style="overflow-x: auto;">
    ${tablaHTML}
  </div>
`;
  document.getElementById("clienteInfo").style.display = "block";
 })
 .catch(err => {
  console.error("Error:", err);
  alert("Error al consultar cliente: " + err.message);
  document.getElementById("clienteInfo").style.display = "none";
 });
}

function abonarCuota() {
  const cuotaSeleccionadaRadio = document.querySelector('input[name="cuotaSeleccionada"]:checked');
  if (!cuotaSeleccionadaRadio) {
    alert("Seleccione una cuota a abonar.");
    return;
  }

  const cuotaId = cuotaSeleccionadaRadio.value;

  const filaCuota = cuotaSeleccionadaRadio.closest('tr');
  // CAMBIO CLAVE AQUÍ: Asegúrate de que la lectura del texto no pierda decimales
  const montoCuotaTexto = filaCuota.querySelector('td:nth-child(2)').textContent; // Columna "Valor Cuota"
  const montoCuota = parseFloat(montoCuotaTexto.replace('$', '').replace(/\./g, '').replace(',', '.')); // Limpiar y convertir a número

  // Validar que se pudo obtener el monto
  if (isNaN(montoCuota) || montoCuota <= 0) {
    alert("No se pudo obtener un monto válido para la cuota seleccionada.");
    return;
  }

  const montoPagadoTransaccion = montoCuota;
  const idPersonalElement = document.getElementById("idPersonal");
  const idPersonal = idPersonalElement ? idPersonalElement.value : '1'; // Usar un valor por defecto si no existe, o manejar error

  console.log("ID de Cuota enviado:", cuotaId);

    // Obtener referencias a los elementos de feedback
    const abonarBtn = document.getElementById("abonarCuotaBtn");
    const loadingMsg = document.getElementById("loadingMessage");

    // Mostrar mensaje de carga y deshabilitar botón
    abonarBtn.disabled = true;
    loadingMsg.style.display = "block";


  fetch("<?= BASE_URL ?>/Controlador/asesorController.php?accion=Abonar_Cuota", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      idCuotaCredito: cuotaId,
      idPersonal: idPersonal,
      montoPagadoTransaccion: montoPagadoTransaccion,
      observaciones: "Abono de cuota desde simulador cajero"
    })
  })
  .then(res => {
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    return res.json();
  })
  .then(data => {
    if (data.exito) {
      console.log(data.mensaje); 
            // Aumentar el retraso para dar más tiempo a la DB
      setTimeout(() => {
        consultarCliente(); 
                // Ocultar mensaje y habilitar botón después de la recarga
                abonarBtn.disabled = false;
                loadingMsg.style.display = "none";
      }, 300); // Mantenemos 300 ms por precaución
    } else {
      alert("Error al abonar la cuota: " + (data.mensaje || "Error desconocido."));
            // Ocultar mensaje y habilitar botón en caso de error
            abonarBtn.disabled = false;
            loadingMsg.style.display = "none";
    }
  })
  .catch(err => {
    console.error("Error en el abono:", err);
    alert("Ocurrió un error al intentar abonar la cuota: " + err.message);
        // Ocultar mensaje y habilitar botón en caso de error
        abonarBtn.disabled = false;
        loadingMsg.style.display = "none";
  });
}
</script>
</body>
</html>
