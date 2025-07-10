lucide.createIcons();

// Variables globales para almacenar los datos
let currentSimulationData = null;
let currentClientData = null;
let currentProductData = null;

// --- Funciones para Modales ---
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// --- Función para mostrar alertas personalizadas (si no la tienes ya) ---
// Si ya la tienes en tu HTML o en otro script, no la incluyas aquí.
// Pero si tu showModal solo está en el fragmento de JS que me enviaste antes,
// y está en este archivo .js, entonces sí debe ir aquí.
function showModal(title, message, type) {
    const modal = document.getElementById('modal'); // Asume un modal con id="modal"
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');

    if (modal && modalTitle && modalMessage) {
        modalTitle.innerText = title;
        modalMessage.innerText = message;

        modalTitle.style.color = ''; // Reset color
        if (type === 'success') {
            modalTitle.style.color = 'green';
        } else if (type === 'error') {
            modalTitle.style.color = 'red';
        } else if (type === 'warning' || type === 'info') {
            modalTitle.style.color = 'orange'; // O el color que prefieras para advertencias/información
        }

        modal.style.display = 'flex'; // Para modales ocultos con display:none
        modal.setAttribute('aria-hidden', 'false');
        modal.focus();
    } else {
        console.warn('Modal elements not found for showModal. Title:', title, 'Message:', message);
        alert(`${title}: ${message}`); // Fallback alert
    }
}

// Asumiendo que tienes un botón o un elemento con clase 'close-modal' para cerrar.
document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function() {
        const parentModal = button.closest('.modal'); // Busca el modal padre
        if (parentModal) {
            parentModal.style.display = 'none';
            parentModal.setAttribute('aria-hidden', 'true');
        }
    });
});


// --- Lógica de Inicialización con Datos del Turno (ACTUALIZADO para JS puro) ---
function initializeWithTurnData() {
    let preloadedClientData = false;

    // Pre-cargar datos del CLIENTE si vienen del turno
    // Se asume que window.initialClientDocumentFromTurn, etc., existen desde el PHP
    if (window.initialClientDocumentFromTurn || window.initialClientNameFromTurn) {
        currentClientData = {
            nombre: window.initialClientNameFromTurn || '',
            apellido: window.initialClientLastNameFromTurn || '',
            documento: window.initialClientDocumentFromTurn || '',
            // Los demás campos quedan vacíos, el asesor los completará
            tipoDocumento: '', 
            genero: '',
            celular: '',
            correo: '',
            direccion: '',
            ciudad: '',
            fechaNacimiento: '',
            contrasena: '' 
        };
        
        // Pre-llenar los campos del formulario del cliente
        document.getElementById('clientNombre').value = currentClientData.nombre;
        document.getElementById('clientApellido').value = currentClientData.apellido;
        document.getElementById('clientDocumento').value = currentClientData.documento;

        preloadedClientData = true; // Se pre-cargaron datos del cliente
    }

    // Pre-cargar datos del PRODUCTO si vienen del turno
    // Se asume que window.initialProductFromTurn existe y es el objeto producto o null
    if (window.initialProductFromTurn) {
        // Directamente llama a seleccionarProducto con el objeto.
        // Esto establecerá currentProductData y actualizará la UI.
        seleccionarProducto(window.initialProductFromTurn);
        // La función seleccionarProducto ya llama a updateStatusDisplay y showModal.
    }

    // Mostrar alerta combinada si solo se cargaron datos de cliente o ambos
    if (preloadedClientData && !window.initialProductFromTurn) {
        showModal('ℹ️ Cliente Pre-cargado', 'El nombre y documento del cliente se han pre-cargado. Por favor, completa el resto de la información del cliente y selecciona un producto.', 'info');
    } else if (preloadedClientData && window.initialProductFromTurn) {
        showModal('ℹ️ Datos Pre-cargados', 'El cliente y el producto del turno se han pre-cargado. Por favor, completa la información restante del cliente (si aplica) y la simulación.', 'info');
    }
}


// --- Actualizar Estado en la Página Principal (No hay cambios aquí) ---
function updateStatusDisplay() {
    // Simulacion
    const simStatusBox = document.getElementById('simulationStatus');
    const simStatusText = document.getElementById('simStatusText');
    const displaySimData = document.getElementById('displaySimulationData');
    if (currentSimulationData) {
        simStatusBox.classList.remove('missing');
        simStatusBox.classList.add('completed');
        simStatusBox.querySelector('.status-icon').innerHTML = '✅';
        simStatusBox.querySelector('.status-icon').classList.remove('missing');
        simStatusBox.querySelector('.status-icon').classList.add('completed');
        simStatusText.innerText = 'Registrada';
        simStatusText.classList.remove('text-red-600');
        simStatusText.classList.add('text-green-600');

        document.getElementById('displayMonto').innerText = currentSimulationData.monto.toLocaleString('es-CO');
        document.getElementById('displayCuotas').innerText = currentSimulationData.cuotas;
        document.getElementById('displayTasaAnual').innerText = (currentSimulationData.tasaAnual * 100).toFixed(2) + '%';
        document.getElementById('displayPeriodicidad').innerText = currentSimulationData.periodicidad;
        document.getElementById('displayValorCuota').innerText = `$${currentSimulationData.valorCuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`;
        document.getElementById('displayTablaAmortizacion').innerHTML = currentSimulationData.tablaHTML;
        displaySimData.classList.remove('hidden');
    } else {
        simStatusBox.classList.remove('completed');
        simStatusBox.classList.add('missing');
        simStatusBox.querySelector('.status-icon').innerHTML = '❌';
        simStatusBox.querySelector('.status-icon').classList.remove('completed');
        simStatusBox.querySelector('.status-icon').classList.add('missing');
        simStatusText.innerText = 'Pendiente';
        simStatusText.classList.remove('text-green-600');
        simStatusText.classList.add('text-red-600');
        displaySimData.classList.add('hidden');
    }

    // Cliente
    const clientStatusBox = document.getElementById('clientStatus');
    const clientStatusText = document.getElementById('clientStatusText');
    const displayClientData = document.getElementById('displayClientData');
    if (currentClientData) {
        clientStatusBox.classList.remove('missing');
        clientStatusBox.classList.add('completed');
        clientStatusBox.querySelector('.status-icon').innerHTML = '✅';
        clientStatusBox.querySelector('.status-icon').classList.remove('missing');
        clientStatusBox.querySelector('.status-icon').classList.add('completed');
        clientStatusText.innerText = 'Registrado';
        clientStatusText.classList.remove('text-red-600');
        clientStatusText.classList.add('text-green-600');

        document.getElementById('displayClientNombre').innerText = currentClientData.nombre;
        document.getElementById('displayClientApellido').innerText = currentClientData.apellido;
        document.getElementById('displayClientDocumentoTipo').innerText = currentClientData.tipoDocumento;
        document.getElementById('displayClientDocumentoNum').innerText = currentClientData.documento;
        document.getElementById('displayClientCelular').innerText = currentClientData.celular;
        document.getElementById('displayClientCorreo').innerText = currentClientData.correo;
        displayClientData.classList.remove('hidden');
    } else {
        clientStatusBox.classList.remove('completed');
        clientStatusBox.classList.add('missing');
        clientStatusBox.querySelector('.status-icon').innerHTML = '❌';
        clientStatusBox.querySelector('.status-icon').classList.remove('completed');
        clientStatusBox.querySelector('.status-icon').classList.add('missing');
        clientStatusText.innerText = 'Pendiente';
        clientStatusText.classList.remove('text-green-600');
        clientStatusText.classList.add('text-red-600');
        displayClientData.classList.add('hidden');
    }

    // Producto
    const productStatusBox = document.getElementById('productStatus');
    const productStatusText = document.getElementById('productStatusText');
    const displayProductData = document.getElementById('displayProductData');
    if (currentProductData) {
        productStatusBox.classList.remove('missing');
        productStatusBox.classList.add('completed');
        productStatusBox.querySelector('.status-icon').innerHTML = '✅';
        productStatusBox.querySelector('.status-icon').classList.remove('missing');
        productStatusBox.querySelector('.status-icon').classList.add('completed');
        productStatusText.innerText = 'Seleccionado';
        productStatusText.classList.remove('text-red-600');
        productStatusText.classList.add('text-green-600');

        document.getElementById('displayProductName').innerText = currentProductData.Nombre_Producto;
        document.getElementById('displayProductDesc').innerText = currentProductData.Descripcion_Producto;
        document.getElementById('displayProductMontoMin').innerText = parseFloat(currentProductData.Monto_Minimo).toLocaleString('es-CO');
        document.getElementById('displayProductMontoMax').innerText = parseFloat(currentProductData.Monto_Maximo).toLocaleString('es-CO');
        document.getElementById('displayProductPlazoMin').innerText = currentProductData.Plazo_Minimo + ' meses';
        document.getElementById('displayProductPlazoMax').innerText = currentProductData.Plazo_Maximo + ' meses';
        displayProductData.classList.remove('hidden');
    } else {
        productStatusBox.classList.remove('completed');
        productStatusBox.classList.add('missing');
        productStatusBox.querySelector('.status-icon').innerHTML = '❌';
        productStatusBox.querySelector('.status-icon').classList.remove('completed');
        productStatusBox.querySelector('.status-icon').classList.add('missing');
        productStatusText.innerText = 'Pendiente';
        productStatusText.classList.remove('text-green-600');
        productStatusText.classList.add('text-red-600');
        displayProductData.classList.add('hidden');
    }

    // --- Mostrar/Ocultar el contenedor del botón Finalizar Proceso ---
    const finalProcessSection = document.getElementById('finalProcessSection');
    const btnFinalizarProceso = document.getElementById('btnFinalizarProceso');

    if (currentSimulationData && currentClientData && currentProductData) {
        finalProcessSection.classList.remove('hidden'); // Hace visible el div contenedor
        btnFinalizarProceso.classList.remove('hidden'); // Hace visible el botón
    } else {
        finalProcessSection.classList.add('hidden'); // Oculta el div contenedor
        btnFinalizarProceso.classList.add('hidden'); // Oculta el botón
    }
}

// --- Event Listeners para botones principales ---
document.getElementById('btnGenerarSimulacion').addEventListener('click', function() {
    openModal('simulacionModal');
    // Limpiar formulario y resultados al abrir
    document.getElementById('formularioAmortizacion').reset();

    document.getElementById('tasaPeriodicaModal').innerHTML = '';
    document.getElementById('valorCuotaModal').innerHTML = '';
    document.getElementById('tabla-amortizacion-modal').innerHTML = '';

    document.getElementById('btnUsarSimulacion').classList.add('hidden');
});

document.getElementById('btnAsociarCliente').addEventListener('click', function () {
    openModal('clienteModal');
    // Si ya pre-cargamos datos, no reseteamos el formulario al abrirlo.
    // document.getElementById('formularioCliente').reset(); 
});

document.getElementById('btnSeleccionarProducto').addEventListener('click', function () {
    openModal('productosModal');
    document.getElementById('filtroProducto').value = ''; // Limpiar filtro al abrir
    const filas = document.querySelectorAll('#tablaProductos tr');
    filas.forEach(fila => fila.style.display = ''); // Mostrar todas las filas
});


// --- Lógica de Cálculo de Amortización ---
document.getElementById("formularioAmortizacion").addEventListener("submit", function (e) {
    e.preventDefault();

    const monto = parseFloat(document.getElementById("monto").value);
    const cuotas = parseInt(document.getElementById("cuotas").value);
    const tasaAnual = parseFloat(document.getElementById("tasaAnual").value.replace(",", ".")) / 100;
    const periodicidad = document.getElementById("periodicidad").value;

    let tasaPeriodica = 0;

    // CALCULO CON TASA NOMINAL (simple división)
    switch (periodicidad) {
        case "Diaria":
            tasaPeriodica = Math.pow(1 + tasaAnual, 1 / 365) - 1;
            break;
        case "Mensual":
            tasaPeriodica = Math.pow(1 + tasaAnual, 1 / 12) - 1;
            break;
        case "Bimensual":
            tasaPeriodica = Math.pow(1 + tasaAnual, 1 / 6) - 1;
            break;
        case "Trimestral":
            tasaPeriodica = Math.pow(1 + tasaAnual, 1 / 4) - 1;
            break;
        case "Semestral":
            tasaPeriodica = Math.pow(1 + tasaAnual, 1 / 2) - 1;
            break;
        case "Anual":
            tasaPeriodica = tasaAnual;
            break;
    }

    // Calcular cuota
    const cuota = monto * ((tasaPeriodica * Math.pow(1 + tasaPeriodica, cuotas)) / (Math.pow(1 + tasaPeriodica, cuotas) - 1));

    document.getElementById("tasaPeriodicaModal").innerHTML = `📈 Tasa de interés periódica (${periodicidad}): <strong>${(tasaPeriodica * 100).toFixed(4)}%</strong>`;
    document.getElementById("valorCuotaModal").innerHTML = `💰 Valor de la cuota: <strong>$${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</strong>`;

    // Generar tabla al ingresar los valores
    let saldo = monto;
    let tablaHTML = `
                            <h2>Tabla de Amortización</h2>
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr class="bg-orange-500">
                                        <th class="bg-orange-500 py-2 px-4 border-b">Nº Cuota</th>
                                        <th class="bg-orange-500 py-2 px-4 border-b">Valor Cuota</th>
                                        <th class="bg-orange-500 py-2 px-4 border-b">Abono a Capital</th>
                                        <th class="bg-orange-500 py-2 px-4 border-b">Abono a Intereses</th>
                                        <th class="bg-orange-500 py-2 px-4 border-b">Saldo después del Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b">0</td>
                                        <td class="py-2 px-4 border-b">$ 0</td>
                                        <td class="py-2 px-4 border-b">$ 0</td>
                                        <td class="py-2 px-4 border-b">$ 0</td>
                                        <td class="py-2 px-4 border-b">$ ${saldo.toLocaleString('es-CO')}</td>
                                    </tr>
                `;

    for (let i = 1; i <= cuotas; i++) {
        const interes = saldo * tasaPeriodica;
        const abonoCapital = cuota - interes;
        saldo -= abonoCapital;

        tablaHTML += `
                                <tr>
                                    <td class="py-2 px-4 border-b">${i}</td>
                                    <td class="py-2 px-4 border-b">$ ${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                                    <td class="py-2 px-4 border-b">$ ${abonoCapital.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                                    <td class="py-2 px-4 border-b">$ ${interes.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                                    <td class="py-2 px-4 border-b">$ ${saldo > 0 ? saldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'}</td>
                                </tr>
                            `;
    }

    tablaHTML += `</tbody></table>`;
    document.getElementById("tabla-amortizacion-modal").innerHTML = tablaHTML;

    // Almacenar los datos de la simulación
    currentSimulationData = {
        monto: monto,
        cuotas: cuotas,
        tasaAnual: tasaAnual,
        periodicidad: periodicidad,
        tasaPeriodica: tasaPeriodica,
        valorCuota: cuota,
        tablaHTML: tablaHTML
    };

    // Mostrar el botón "Utilizar esta Simulación"
    document.getElementById('btnUsarSimulacion').classList.remove('hidden');
});

// Botón "Utilizar esta Simulación" (dentro del modal de simulación)
document.getElementById('btnUsarSimulacion').addEventListener('click', function () {
    closeModal('simulacionModal');
    updateStatusDisplay(); // Actualiza la página principal
});

// --- Lógica de Formulario de Cliente ---
document.getElementById('formularioCliente').addEventListener('submit', function (event) {
    event.preventDefault(); // Previene el envío tradicional del formulario

    // Recopilar los datos del cliente
    currentClientData = {
        nombre: document.getElementById('clientNombre').value,
        apellido: document.getElementById('clientApellido').value,
        tipoDocumento: document.getElementById('clientTipoDocumento').value,
        documento: document.getElementById('clientDocumento').value,
        genero: document.getElementById('clientGenero').value,
        celular: document.getElementById('clientCelular').value,
        correo: document.getElementById('clientCorreo').value,
        direccion: document.getElementById('clientDireccion').value,
        ciudad: document.getElementById('clientCiudad').value,
        fechaNacimiento: document.getElementById('clientFechaNacimiento').value,
        contrasena: document.getElementById('clientContrasena').value
    };

    closeModal('clienteModal');
    updateStatusDisplay(); // Actualiza la página principal
    showModal('✅ Cliente Registrado', 'Datos del cliente listos en la página principal.', 'success');
});


// --- Lógica para el Modal de Productos ---
function seleccionarProducto(producto) {
    currentProductData = producto; // Almacena el objeto completo del producto
    closeModal('productosModal'); // Cierra el modal de productos si está abierto
    updateStatusDisplay(); // Actualiza la página principal
    showModal('✅ Producto Seleccionado', `Producto "${producto.Nombre_Producto}" añadido.`, 'success');
}

// Filtro de productos
document.getElementById('filtroProducto').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaProductos tr');
    filas.forEach(fila => {
        const nombreCell = fila.cells[1]; // Columna del nombre del producto
        if (nombreCell) {
            const nombre = nombreCell.textContent.toLowerCase();
            fila.style.display = nombre.includes(filtro) ? '' : 'none';
        }
    });
});

// --- Botón "Finalizar Proceso" (Guardar Todo) ---
document.getElementById('btnFinalizarProceso').addEventListener('click', function () {
    if (!currentSimulationData || !currentClientData || !currentProductData) {
        showModal('⚠️ Advertencia', 'Por favor, completa todos los datos (Simulación, Cliente y Producto) antes de finalizar.', 'warning');
        return;
    }

    // Recopilar todos los datos finales para enviar al backend
    const finalDataToSave = {
        simulacion: {
            monto: currentSimulationData.monto,
            cuotas: currentSimulationData.cuotas,
            tasaAnual: currentSimulationData.tasaAnual,
            periodicidad: currentSimulationData.periodicidad,
            tasaPeriodica: currentSimulationData.tasaPeriodica,
            valorCuota: currentSimulationData.valorCuota,
        },
        cliente: {
            nombre: currentClientData.nombre,
            apellido: currentClientData.apellido,
            tipo_documento: currentClientData.tipoDocumento, 
            documento: currentClientData.documento,
            genero: currentClientData.genero,
            celular: currentClientData.celular,
            correo: currentClientData.correo,
            direccion: currentClientData.direccion,
            ciudad: currentClientData.ciudad,
            fecha_nacimiento: currentClientData.fechaNacimiento,
            contrasena: currentClientData.contrasena
        },
        producto: {
            id_producto: currentProductData.ID_Producto,
            nombre_producto: currentProductData.Nombre_Producto,
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const idTurno = urlParams.get('idTurno'); 
    const idAsesoramientoActual = document.getElementById('id_registroHidden').value;

    console.error('Id Turno:', idTurno);
    console.error('Id Asesoramiento Actual:', idAsesoramientoActual);

    const fetchUrl = `/Proyecto_GB/Controlador/asesorController.php?accion=consolidadoCliente&idTurno=${idTurno}&idReAsesoramiento=${idAsesoramientoActual}`;

    // Aquí es donde enviarías finalDataToSave a tu backend vía AJAX (fetch).
    fetch(fetchUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(finalDataToSave),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModal('✅ Proceso Completado', 'Todos los datos han sido guardados exitosamente.', 'success');
            // Reiniciar la interfaz si el guardado fue exitoso
            currentSimulationData = null;
            currentClientData = null;
            currentProductData = null;
            updateStatusDisplay();
            document.getElementById('formularioAmortizacion').reset();
            document.getElementById('formularioCliente').reset();
        } else {
            showModal('❌ Error', 'Error al guardar los datos: ' + (data.message || 'Desconocido'), 'error');
        }
    })
    .catch((error) => {
        console.error('Error al enviar datos:', error);
        showModal('❌ Error', 'Error de conexión o en el servidor al intentar guardar los datos.', 'error');
    });
});

// Inicializar el estado y pre-cargar datos del turno al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Llama a la función que usa las variables globales definidas en el PHP de la vista
    initializeWithTurnData(); 
    updateStatusDisplay(); 
});