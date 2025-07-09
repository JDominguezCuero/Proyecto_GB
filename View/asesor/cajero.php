<?php
session_start();
require_once(__DIR__ . '../../../Config/config.php'); // Asegúrate de que esta ruta sea correcta

// Obtener el ID del personal de la sesión para pasarlo al JS
$idPersonal = $_SESSION['idPersonal'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Gestión de Cajero</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Tus estilos existentes */
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
/* Estilo para filas canceladas (cuotas pagadas) */
tr.cancelada {
    background-color: #c8e6c9; /* Verde claro para indicar pagado */
    text-decoration: line-through; /* Tachado para indicar pagado */
    color: #555; /* Texto un poco más tenue */
}
/* Estilo para cuotas con mora */
tr.con-mora {
    background-color: #ffe0b2; /* Naranja claro para indicar mora */
}
.acciones {
    margin-top: 10px;
    text-align: right;
    display: flex; /* Para alinear botones */
    justify-content: flex-end; /* Para mover botones a la derecha */
    gap: 10px; /* Espacio entre botones */
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

/* Estilo para los nuevos botones de acción */
.btn-comprobante {
    background-color: #007bff; /* Azul */
    padding: 8px 12px;
    font-size: 14px;
}
.btn-comprobante:hover {
    background-color: #0056b3;
}
.btn-desembolso {
    background-color: #28a745; /* Verde */
    padding: 10px 18px;
    font-size: 16px;
}
.btn-desembolso:hover {
    background-color: #218838;
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

    <div class="info" id="clienteInfo" style="display: none;">
      <input type="hidden" id="id_registroHidden" value="">
      <input type="hidden" id="idCreditoHidden" value="">
      <input type="hidden" id="idClienteHidden"> <input type="hidden" id="id_registroHidden" value="">


      <h3>Información del Cliente</h3>
      <p><strong>Nombre:</strong> <span id="nombreCliente"></span></p>
      <p><strong>Producto:</strong> <span id="productoCliente"></span></p>
      <p><strong>Monto:</strong> $<span id="montoCredito"></span></p>
      <p><strong>Cuotas:</strong> <span id="numCuotas"></span></p>
      <p><strong>Tasa Anual:</strong> <span id="tasaAnual"></span>%</p>
      <p><strong>Periodicidad:</strong> <span id="periodicidad"></span></p>
      <p><strong>Estado Crédito:</strong> <span id="estadoCredito"></span></p>
      <p><strong>Estado Desembolso:</strong> <span id="estadoDesembolso"></span></p>
      <div id="tasaPeriodica"></div>
      <div id="valorCuota"></div>

      <div id="tabla-amortizacion" style="margin-top: 20px;">
          </div>

      <div class="acciones">
          <button id="aprobarDesembolsoBtn" class="btn-desembolso" style="display:none;">Aprobar Desembolso</button>
          <button id="abonarCuotaBtn" onclick="abonarCuota()">Abonar Cuota Seleccionada</button>
          <div id="loadingMessage" style="display:none;">Procesando abono... Por favor espere.</div>
      </div>
    </div>
</div>

<div id="desembolsoModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Detalle del Crédito para Desembolso</h2>
        <p><strong>Cliente:</strong> <span id="modalNombreCliente"></span></p>
        <p><strong>Producto:</strong> <span id="modalProductoCredito"></span></p>
        <p><strong>Monto Total del Crédito:</strong> $<span id="modalMontoCredito"></span></p>
        <p><strong>Número de Cuotas:</strong> <span id="modalNumCuotas"></span></p>
        <p><strong>Tasa Anual:</strong> <span id="modalTasaAnual"></span>%</p>
        <p><strong>Periodicidad:</strong> <span id="modalPeriodicidad"></span></p>
        <p><strong>Estado del Crédito:</strong> <span id="modalEstadoCredito"></span></p>
        <p><strong>Estado del Desembolso Actual:</strong> <span id="modalEstadoDesembolso"></span></p>
        <p>¿Confirma que desea aprobar este desembolso?</p>
        <button id="confirmarDesembolsoBtn">Confirmar Desembolso</button>
    </div>
</div>

<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
        max-width: 500px;
        border-radius: 8px;
        position: relative;
    }

    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #confirmarDesembolsoBtn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }

    #confirmarDesembolsoBtn:hover {
        background-color: #45a049;
    }

    /* Estilos para la tabla */
    #tabla-amortizacion table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    #tabla-amortizacion th, #tabla-amortizacion td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    #tabla-amortizacion th {
        background-color: #f2f2f2;
    }

    .cancelada {
        background-color: #e6ffe6; /* Fondo verde claro para cuotas pagadas */
    }

    .con-mora {
        background-color: #ffe6e6; /* Fondo rojo claro para cuotas con mora */
    }
</style>

<?php include '../public/layout/frontendBackend.php'; ?>
<?php include '../public/layout/layoutfooter.php'; ?>

<script>
// NUEVO: Pasa el ID del personal de PHP a JavaScript
const ID_PERSONAL = <?php echo json_encode($idPersonal); ?>;
let idRegistroAsesor = null; // Para guardar el ID de la sesión de asesoramiento

// NUEVO: Referencias a elementos del DOM para el modal
const aprobarDesembolsoBtn = document.getElementById("aprobarDesembolsoBtn");
const desembolsoModal = document.getElementById("desembolsoModal");
const closeModalBtn = document.querySelector(".close-button");
const confirmarDesembolsoBtn = document.getElementById("confirmarDesembolsoBtn");

// NUEVO: Elementos dentro del modal para mostrar los detalles del crédito
const modalNombreCliente = document.getElementById("modalNombreCliente");
const modalProductoCredito = document.getElementById("modalProductoCredito");
const modalMontoCredito = document.getElementById("modalMontoCredito");
const modalNumCuotas = document.getElementById("modalNumCuotas");
const modalTasaAnual = document.getElementById("modalTasaAnual");
const modalPeriodicidad = document.getElementById("modalPeriodicidad");
const modalEstadoCredito = document.getElementById("modalEstadoCredito");
const modalEstadoDesembolso = document.getElementById("modalEstadoDesembolso");

// NUEVO: La tabla de amortización y el botón de abonar estarán deshabilitados inicialmente
const tablaAmortizacionContainer = document.getElementById("tabla-amortizacion");
const abonarCuotaBtn = document.getElementById("abonarCuotaBtn");


function consultarCliente(esRefresco = false) {
    const documento = document.getElementById("documento").value;

    if (!documento) {
        alert("Ingrese un documento válido");
        return;
    }

    document.getElementById("loadingMessage").textContent = "Cargando información del cliente...";
    document.getElementById("loadingMessage").style.display = "block";
    
    // Deshabilitar botones y ocultar tabla al iniciar la consulta
    abonarCuotaBtn.disabled = true;
    aprobarDesembolsoBtn.disabled = true;
    tablaAmortizacionContainer.style.display = "none"; 


    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Simulador_Cajero`;

    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            documento: documento,
            esRefresco: esRefresco,
            idRegistroAsesor: idRegistroAsesor,
            idPersonal: ID_PERSONAL
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        document.getElementById("loadingMessage").style.display = "none";
        document.getElementById("clienteInfo").style.display = "block"; // Asegura que la info del cliente siempre se muestre si hay data

        if (!data.exito) {
            alert(data.mensaje || "Cliente no encontrado");
            document.getElementById("clienteInfo").style.display = "none";            
            tablaAmortizacionContainer.style.display = "none"; 
            abonarCuotaBtn.disabled = true;
            aprobarDesembolsoBtn.style.display = "none";
            return;
        }

        const cliente = data.cliente;
        const credito = data.credito;
        const cuotas = data.cuotas;

        if (!esRefresco) {
            idRegistroAsesor = data.idRegistroAsesor;
            document.getElementById("id_registroHidden").value = idRegistroAsesor;
        }

        document.getElementById("idCreditoHidden").value = credito.ID_Credito;
        document.getElementById("idClienteHidden").value = cliente.ID_Cliente;
        document.getElementById("nombreCliente").textContent = cliente.Nombre_Cliente + ' ' + cliente.Apellido_Cliente;
        document.getElementById("productoCliente").textContent = credito.NombreProducto;
        document.getElementById("montoCredito").textContent = parseInt(credito.Monto_Total_Credito).toLocaleString('es-CO');
        document.getElementById("numCuotas").textContent = cuotas.length;
        document.getElementById("tasaAnual").textContent = credito.Tasa_Interes_Anual;
        document.getElementById("periodicidad").textContent = credito.Periodicidad;
        document.getElementById("estadoCredito").textContent = credito.Estado_Credito;
        document.getElementById("estadoDesembolso").textContent = credito.Desembolso || 'N/A';

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
            // No alertar aquí, ya que el estado de desembolso se encargará de la lógica.
        }

        document.getElementById("tasaPeriodica").innerHTML = `📈 Tasa periódica (${credito.Periodicidad}): <strong>${(tasaPeriodica * 100).toFixed(4)}%</strong>`;
        document.getElementById("valorCuota").innerHTML = `💰 Valor cuota estimada: <strong>$${cuotaValor.toLocaleString('es-CO')}</strong>`;

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
                        <th>Acciones</th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>0</td>
                        <td>$0</td>
                        <td>$0</td>
                        <td>$0</td>
                        <td>$0</td> <td>N/A</td> <td>0</td> <td>$0</td> <td>N/A</td> <td>$${saldo.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td></td>
                    </tr>`;

        cuotas.forEach((cuota, i) => {
            const esPagada = cuota.ID_Estado_Cuota == 7;
            const tieneMora = parseFloat(cuota.Monto_Recargo_Mora) > 0;
            saldo -= parseFloat(cuota.Monto_Capital);

            const fechaPagoDisplay = cuota.Fecha_Pago ? new Date(cuota.Fecha_Pago).toLocaleDateString('es-CO') : 'Pendiente';
            const montoPagadoDisplay = parseFloat(cuota.Monto_Pagado).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            const montoRecargoMoraDisplay = parseFloat(cuota.Monto_Recargo_Mora).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            let rowClass = '';
            if (esPagada) {
                rowClass = 'cancelada';
            } else if (tieneMora) {
                rowClass = 'con-mora';
            }

            tablaHTML += `
                <tr class="${rowClass}">
                    <td>${cuota.Numero_Cuota}</td>
                    <td>$${parseFloat(cuota.Monto_Total_Cuota).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td>$${parseFloat(cuota.Monto_Capital).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td>$${parseFloat(cuota.Monto_Interes).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td>$${montoPagadoDisplay}</td> <td>${fechaPagoDisplay}</td> <td>${cuota.Dias_Mora_Al_Pagar}</td> <td>$${montoRecargoMoraDisplay}</td> <td>${cuota.Estado_Cuota}</td> <td>$${saldo > 0 ? saldo.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0'}</td>
                    <td>`;
            
            if (esPagada) {
                tablaHTML += `<i class="fas fa-file-invoice-dollar" title="Generar Comprobante" style="cursor: pointer; color: #2a6ebd;" onclick="generarComprobante(${cuota.ID_CuotaCredito})"></i>`;
            } else {
                tablaHTML += `<input type="radio" name="cuotaSeleccionada" value="${cuota.ID_CuotaCredito}">`;
            }
            tablaHTML += `</td>
                </tr>`;
        });

        tablaHTML += `</tbody></table>`;
        tablaAmortizacionContainer.innerHTML = `<div style="overflow-x: auto;">${tablaHTML}</div>`;
        

        // Lógica para mostrar/ocultar el botón de Aprobar Desembolso y habilitar/deshabilitar la tabla
        if (credito.Desembolso === 'No desembolsado') {
            aprobarDesembolsoBtn.style.display = "block";
            aprobarDesembolsoBtn.disabled = false;
            tablaAmortizacionContainer.style.display = "none"; // Ocultar tabla
            abonarCuotaBtn.disabled = true; 
        } else {
            aprobarDesembolsoBtn.style.display = "none";
            aprobarDesembolsoBtn.disabled = true;
            tablaAmortizacionContainer.style.display = "block"; // Mostrar tabla
            abonarCuotaBtn.disabled = false; // Habilitar botón de abonar
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error al consultar cliente: " + err.message);
        document.getElementById("clienteInfo").style.display = "none";
        document.getElementById("loadingMessage").style.display = "none";
        abonarCuotaBtn.disabled = true;
        aprobarDesembolsoBtn.style.display = "none";
        tablaAmortizacionContainer.style.display = "none";
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
    const montoCuotaTexto = filaCuota.querySelector('td:nth-child(2)').textContent;
    const montoCuota = parseFloat(montoCuotaTexto.replace('$', '').replace(/\./g, '').replace(',', '.'));

    if (isNaN(montoCuota) || montoCuota <= 0) {
        alert("No se pudo obtener un monto válido para la cuota seleccionada.");
        return;
    }

    const montoPagadoTransaccion = montoCuota;
    const idAsesoramientoActual = document.getElementById('id_registroHidden').value;
    const ID_Cliente = document.getElementById('idClienteHidden').value;

    console.log("ID de Cuota enviado:", cuotaId);
    console.log("ID de Asesoramiento enviado:", idAsesoramientoActual);
    console.log("ID Personal enviado:", ID_PERSONAL);

    abonarCuotaBtn.disabled = true;
    document.getElementById("loadingMessage").textContent = "Procesando abono... Por favor espere.";
    document.getElementById("loadingMessage").style.display = "block";

    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Abonar_Cuota&idReAsesoramiento=${idAsesoramientoActual}&ID_Cliente=${ID_Cliente}`;

    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            idCuotaCredito: cuotaId,
            idPersonal: ID_PERSONAL,
            montoPagadoTransaccion: montoPagadoTransaccion,
            observaciones: "Abono de cuota desde módulo de gestión de cajero"
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
            alert(data.mensaje || "Abono realizado con éxito.");
            consultarCliente(true);
        } else {
            alert("Error al abonar la cuota: " + (data.mensaje || "Error desconocido."));
        }
        abonarCuotaBtn.disabled = false;
        document.getElementById("loadingMessage").style.display = "none";
    })
    .catch(err => {
        console.error("Error en el abono:", err);
        alert("Ocurrió un error al intentar abonar la cuota: " + err.message);
        abonarCuotaBtn.disabled = false;
        document.getElementById("loadingMessage").style.display = "none";
    });
}

function generarComprobante(idCuota) {
    if (!ID_PERSONAL) {
        alert("Error: ID de Personal no disponible. Inicie sesión.");
        return;
    }
    window.open(`<?= BASE_URL ?>/Controlador/asesorController.php?accion=Generar_Comprobante_Pago&idCuota=${idCuota}&idPersonal=${ID_PERSONAL}`, '_blank');
}

// NUEVA FUNCIÓN: Mostrar el modal con los detalles del crédito
function mostrarModalDesembolso() {
    const idCredito = document.getElementById('idCreditoHidden').value;
    const nombreCliente = document.getElementById("nombreCliente").textContent;
    const productoCliente = document.getElementById("productoCliente").textContent;
    const montoCredito = document.getElementById("montoCredito").textContent;
    const numCuotas = document.getElementById("numCuotas").textContent;
    const tasaAnual = document.getElementById("tasaAnual").textContent;
    const periodicidad = document.getElementById("periodicidad").textContent;
    const estadoCredito = document.getElementById("estadoCredito").textContent;
    const estadoDesembolso = document.getElementById("estadoDesembolso").textContent;

    if (!idCredito) {
        alert("No hay un crédito cargado para desembolsar.");
        return;
    }

    modalNombreCliente.textContent = nombreCliente;
    modalProductoCredito.textContent = productoCliente;
    modalMontoCredito.textContent = montoCredito;
    modalNumCuotas.textContent = numCuotas;
    modalTasaAnual.textContent = tasaAnual;
    modalPeriodicidad.textContent = periodicidad;
    modalEstadoCredito.textContent = estadoCredito;
    modalEstadoDesembolso.textContent = estadoDesembolso;

    desembolsoModal.style.display = "block";
}

// NUEVA FUNCIÓN: Cerrar el modal
closeModalBtn.onclick = function() {
    desembolsoModal.style.display = "none";
}

// Cerrar el modal haciendo clic fuera de él
window.onclick = function(event) {
    if (event.target == desembolsoModal) {
        desembolsoModal.style.display = "none";
    }
}

// Event listener para el botón "Aprobar Desembolso" para mostrar el modal
aprobarDesembolsoBtn.addEventListener('click', mostrarModalDesembolso);


// MODIFICADA: Función para aprobar el desembolso (ahora llamada desde el modal)
async function confirmarAprobarDesembolso() {
    const idCredito = document.getElementById('idCreditoHidden').value;
    const idCliente = document.getElementById('idClienteHidden').value;
    const montoTotalCredito = parseFloat(document.getElementById('montoCredito').textContent.replace(/[^0-9,-]+/g,"").replace(",", ".")); // Limpia y convierte

    if (!idCredito || !idCliente || isNaN(montoTotalCredito) || montoTotalCredito <= 0) {
        alert("No se pudo obtener el ID del crédito o el monto para el desembolso.");
        return;
    }

    if (!ID_PERSONAL) {
        alert("Error: ID de Personal no disponible. Inicie sesión.");
        return;
    }

    // Ya no se necesita el confirm porque el modal actúa como confirmación visual.
    // if (!confirm(`¿Está seguro de que desea aprobar el desembolso del crédito ${idCredito} por $${montoTotalCredito.toLocaleString('es-CO')}?`)) {
    //     return;
    // }

    confirmarDesembolsoBtn.disabled = true; // Deshabilitar el botón de confirmar en el modal
    document.getElementById("loadingMessage").textContent = "Aprobando desembolso... Por favor espere.";
    document.getElementById("loadingMessage").style.display = "block";
    desembolsoModal.style.display = "none"; // Ocultar el modal mientras se procesa

    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Aprobar_Desembolso`;

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                idCliente: idCliente,
                idCredito: idCredito,
                montoDesembolsar: montoTotalCredito,
                idPersonal: ID_PERSONAL,
                observaciones: "Desembolso aprobado por cajero."
            })
        });

        const data = await response.json();

        if (data.exito) {
            alert(data.mensaje || "Desembolso aprobado con éxito!");
            consultarCliente(true);
        } else {
            alert("Error al aprobar desembolso: " + (data.mensaje || "Error desconocido."));
        }
    } catch (error) {
        console.error("Error en la aprobación de desembolso:", error);
        alert("Ocurrió un error al intentar aprobar el desembolso: " + error.message);
    } finally {
        confirmarDesembolsoBtn.disabled = false; // Habilitar el botón de confirmar
        document.getElementById("loadingMessage").style.display = "none";
    }
}

// Event listener para el botón de confirmar dentro del modal
confirmarDesembolsoBtn.addEventListener('click', confirmarAprobarDesembolso);

// Al cargar la página, la tabla de amortización estará oculta y el botón de abonar deshabilitado
document.addEventListener('DOMContentLoaded', () => {
    tablaAmortizacionContainer.style.display = "none";
    abonarCuotaBtn.disabled = true;
});
</script>


</body>
</html>