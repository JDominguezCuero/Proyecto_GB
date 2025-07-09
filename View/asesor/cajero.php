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

    <div class="info" id="clienteInfo">
      <input type="hidden" id="id_registroHidden" value="">
      <input type="hidden" id="idCreditoHidden" value="">

      <h3>Información del Cliente</h3>
      <p><strong>Nombre:</strong> <span id="nombreCliente"></span></p>
      <p><strong>Producto:</strong> <span id="productoCliente"></span></p>
      <p><strong>Monto:</strong> $<span id="montoCredito"></span></p>
      <p><strong>Cuotas:</strong> <span id="numCuotas"></span></p>
      <p><strong>Tasa Anual:</strong> <span id="tasaAnual"></span>%</p>
      <p><strong>Periodicidad:</strong> <span id="periodicidad"></span></p>
      <p><strong>Estado Crédito:</strong> <span id="estadoCredito"></span></p>
      <p><strong>Estado Desembolso:</strong> <span id="estadoDesembolso"></span></p> <div id="tasaPeriodica"></div>
      <div id="valorCuota"></div>
      <div id="tabla-amortizacion" style="margin-top: 20px;"></div>

      <div class="acciones">
          <button id="aprobarDesembolsoBtn" class="btn-desembolso" style="display:none;" onclick="aprobarDesembolso()">Aprobar Desembolso</button>
          <button id="abonarCuotaBtn" onclick="abonarCuota()">Abonar Cuota Seleccionada</button>
          <div id="loadingMessage">Procesando abono... Por favor espere.</div>
      </div>
  </div>


</div>

<?php include '../public/layout/frontendBackend.php'; ?>
<?php include '../public/layout/layoutfooter.php'; ?>

<script>
// NUEVO: Pasa el ID del personal de PHP a JavaScript
const ID_PERSONAL = <?php echo json_encode($idPersonal); ?>;
let idRegistroAsesor = null; // Para guardar el ID de la sesión de asesoramiento

function consultarCliente(esRefresco = false) { // Modificado para aceptar parámetro esRefresco
    const documento = document.getElementById("documento").value;
    
    if (!documento) {
        alert("Ingrese un documento válido");
        return;
    }

    // NUEVO: Mostrar mensaje de carga general
    document.getElementById("loadingMessage").textContent = "Cargando información del cliente...";
    document.getElementById("loadingMessage").style.display = "block";
    document.getElementById("abonarCuotaBtn").disabled = true;
    document.getElementById("aprobarDesembolsoBtn").disabled = true; // Deshabilitar el botón de desembolso

    // CORREGIDO: Pasar la acción por URL
    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Simulador_Cajero`;

    fetch(url, { // Usar la URL con la acción
        method: "POST", // Sigue siendo POST porque envías más datos en el body
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            documento: documento,
            esRefresco: esRefresco, // Pasa el flag de refresco
            idRegistroAsesor: idRegistroAsesor, // Pasa el ID existente si es un refresco
            idPersonal: ID_PERSONAL // Pasa el ID_PERSONAL
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        // Ocultar mensaje de carga y habilitar botones al finalizar
        document.getElementById("loadingMessage").style.display = "none";
        document.getElementById("abonarCuotaBtn").disabled = false;
        // El botón de desembolso se habilitará/deshabilitará lógicamente más abajo

        if (!data.exito) {
            alert(data.mensaje || "Cliente no encontrado");
            document.getElementById("clienteInfo").style.display = "none";
            return;
        }

        const cliente = data.cliente;
        const credito = data.credito;
        const cuotas = data.cuotas;

        // NUEVO: Almacena el idRegistroAsesoramiento solo si no es un refresco
        if (!esRefresco) {
            idRegistroAsesor = data.idRegistroAsesor;
            document.getElementById("id_registroHidden").value = idRegistroAsesor;
        }

        // NUEVO: Guarda el ID del crédito
        document.getElementById("idCreditoHidden").value = credito.ID_Credito;

        document.getElementById("nombreCliente").textContent = cliente.Nombre_Cliente + ' ' + cliente.Apellido_Cliente;
        document.getElementById("productoCliente").textContent = credito.NombreProducto;
        document.getElementById("montoCredito").textContent = parseInt(credito.Monto_Total_Credito).toLocaleString('es-CO');
        document.getElementById("numCuotas").textContent = cuotas.length;
        document.getElementById("tasaAnual").textContent = credito.Tasa_Interes_Anual;
        document.getElementById("periodicidad").textContent = credito.Periodicidad;
        // NUEVO: Mostrar el estado del crédito
        document.getElementById("estadoCredito").textContent = cuotas.Estado_Cuota;
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
            alert("No se encontraron cuotas de amortización para este crédito.");
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
                        <th>Acciones</th> </tr>
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
            
            // Lógica para botones de acción en la tabla de cuotas
            if (esPagada) {
                // NUEVO: Botón para generar comprobante si la cuota está pagada
                tablaHTML += `<button class="btn-comprobante" onclick="generarComprobante(${cuota.ID_CuotaCredito})">Comprobante</button>`;
            } else {
                // Radio button para seleccionar cuotas a abonar
                tablaHTML += `<input type="radio" name="cuotaSeleccionada" value="${cuota.ID_CuotaCredito}">`;
            }
            tablaHTML += `</td>
                </tr>`;
        });

        tablaHTML += `</tbody></table>`;
        document.getElementById("tabla-amortizacion").innerHTML = `
        <div style="overflow-x: auto;">
            ${tablaHTML}
        </div>
        `;
        document.getElementById("clienteInfo").style.display = "block";

        // NUEVO: Lógica para mostrar/ocultar el botón de Aprobar Desembolso
        const aprobarDesembolsoBtn = document.getElementById('aprobarDesembolsoBtn');
          if (credito.Desembolso === 'No desembolsado') {
            aprobarDesembolsoBtn.style.display = "block";
            aprobarDesembolsoBtn.disabled = false;
        } else {
            aprobarDesembolsoBtn.style.display = "none";
        }


    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error al consultar cliente: " + err.message);
        document.getElementById("clienteInfo").style.display = "none";
        // Ocultar mensaje de carga y habilitar botones en caso de error
        document.getElementById("loadingMessage").style.display = "none";
        document.getElementById("abonarCuotaBtn").disabled = false;
        document.getElementById("aprobarDesembolsoBtn").style.display = "none"; // Asegurarse de que esté oculto en error
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

    console.log("ID de Cuota enviado:", cuotaId);
    console.log("ID de Asesoramiento enviado:", idAsesoramientoActual);
    console.log("ID Personal enviado:", ID_PERSONAL);

    const abonarBtn = document.getElementById("abonarCuotaBtn");
    const loadingMsg = document.getElementById("loadingMessage");

    abonarBtn.disabled = true;
    loadingMsg.textContent = "Procesando abono... Por favor espere.";
    loadingMsg.style.display = "block";

    // CORREGIDO: Pasar la acción por URL
    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Abonar_Cuota&idReAsesoramiento=${idAsesoramientoActual}`;

    fetch(url, { // Usar la URL con la acción
        method: "POST", // Sigue siendo POST
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            idCuotaCredito: cuotaId,
            idPersonal: ID_PERSONAL, // Usar la constante global
            montoPagadoTransaccion: montoPagadoTransaccion,
            observaciones: "Abono de cuota desde el sistema de Gestión de Cajero"
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
            // Recargar la información del cliente para ver los cambios en las cuotas y saldo
            consultarCliente(true); // Pasar true para indicar que es un refresco
        } else {
            alert("Error al abonar la cuota: " + (data.mensaje || "Error desconocido."));
        }
        abonarBtn.disabled = false;
        loadingMsg.style.display = "none";
    })
    .catch(err => {
        console.error("Error en el abono:", err);
        alert("Ocurrió un error al intentar abonar la cuota: " + err.message);
        abonarBtn.disabled = false;
        loadingMsg.style.display = "none";
    });
}

// NUEVA FUNCIÓN: Generar Comprobante
function generarComprobante(idCuota) {
    if (!ID_PERSONAL) {
        alert("Error: ID de Personal no disponible. Inicie sesión.");
        return;
    }
    // Ya estaba correcto, la acción y los parámetros van por URL para GET
    window.open(`<?= BASE_URL ?>/Controlador/asesorController.php?accion=Generar_Comprobante_Pago&idCuota=${idCuota}&idPersonal=${ID_PERSONAL}`, '_blank');
}

// NUEVA FUNCIÓN: Aprobar Desembolso
async function aprobarDesembolso() {
    const idCredito = document.getElementById('idCreditoHidden').value;
    const montoTotalCredito = parseFloat(document.getElementById('montoCredito').textContent.replace(/[^0-9,-]+/g,"").replace(",", ".")); // Limpia y convierte

    if (!idCredito || isNaN(montoTotalCredito) || montoTotalCredito <= 0) {
        alert("No se pudo obtener el ID del crédito o el monto para el desembolso.");
        return;
    }

    if (!ID_PERSONAL) {
        alert("Error: ID de Personal no disponible. Inicie sesión.");
        return;
    }

    if (!confirm(`¿Está seguro de que desea aprobar el desembolso del crédito ${idCredito} por $${montoTotalCredito.toLocaleString('es-CO')}?`)) {
        return;
    }

    const aprobarDesembolsoBtn = document.getElementById("aprobarDesembolsoBtn");
    const loadingMsg = document.getElementById("loadingMessage");

    aprobarDesembolsoBtn.disabled = true;
    loadingMsg.textContent = "Aprobando desembolso... Por favor espere.";
    loadingMsg.style.display = "block";

    // CORREGIDO: Pasar la acción por URL
    const url = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Aprobar_Desembolso`;

    try {
        const response = await fetch(url, { // Usar la URL con la acción
            method: "POST", // Sigue siendo POST
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                idCredito: idCredito,
                montoDesembolsar: montoTotalCredito,
                idPersonal: ID_PERSONAL,
                observaciones: "Desembolso aprobado por cajero."
            })
        });

        const data = await response.json();

        if (data.exito) {
            alert(data.mensaje || "Desembolso aprobado con éxito!");
            // Recargar la información del cliente para ver el cambio de estado del crédito
            consultarCliente(true); // Pasar true para indicar que es un refresco
        } else {
            alert("Error al aprobar desembolso: " + (data.mensaje || "Error desconocido."));
        }
    } catch (error) {
        console.error("Error en la aprobación de desembolso:", error);
        alert("Ocurrió un error al intentar aprobar el desembolso: " + error.message);
    } finally {
        aprobarDesembolsoBtn.disabled = false;
        loadingMsg.style.display = "none";
    }
}
</script>


</body>
</html>