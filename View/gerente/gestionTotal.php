<?php
session_start();
require_once(__DIR__ . '../../../Config/config.php');

// Mensajes de éxito/error (reutilizando tu lógica existente)
if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('❌ Error', '$mensaje', 'error');
        });
        </script>";
} else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Operación Exitosa.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('✅ Éxito', '$mensaje', 'success');
        });
        </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión General</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .contenedor-banco {
            background-color: white;
            padding: 30px;
            margin: 30px auto 0 auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 1200px; /* Ampliado para el dashboard */
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            text-align: center;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-align: center;
        }
        .dashboard-card h3 {
            color: #1b5e20;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .dashboard-card p {
            font-size: 2em;
            font-weight: bold;
            color: #2e7d32;
        }
        .transactions-section {
            margin-top: 30px;
        }
        .transactions-section h3 {
            color: #2e7d32;
            margin-bottom: 15px;
        }
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            overflow-x: auto; /* Permite scroll horizontal para tablas anchas */
            display: block; /* Necesario para overflow-x en tablas */
        }
        .transactions-table thead, .transactions-table tbody {
            display: table;
            width: 100%;
            table-layout: fixed; /* Asegura que las columnas tengan el mismo ancho */
        }
        .transactions-table th, .transactions-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            white-space: nowrap; /* Evita que el texto se rompa */
        }
        .transactions-table th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap; /* Permite que los botones se envuelvan en pantallas pequeñas */
        }
        .action-buttons button {
            padding: 12px 25px;
            font-size: 17px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .action-buttons button:hover {
            transform: translateY(-2px);
        }

        /* Modal Styles (reutilizados del archivo crearAsesor.php) */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 800px; /* Ajustado para las modales de consulta */
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.show .modal-content {
            transform: translateY(0);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .modal-header h3 {
            margin: 0;
            color: #2e7d32;
        }
        .modal-close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #555;
        }
        .modal-close-btn:hover {
            color: #333;
        }
        .modal-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .modal-body th, .modal-body td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .modal-body th {
            background-color: #f2f2f2;
        }
        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }
        .modal-footer button {
            margin-left: 10px;
        }
        .modal-search-input {
            width: calc(100% - 120px); /* Ajusta el ancho */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .modal-search-btn {
            padding: 8px 15px;
            background-color: #2e7d32;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-search-btn:hover {
            background-color: #1b5e20;
        }
    </style>
</head>
<body>
    <?php include '../public/layout/barraNavAsesor.php'; ?>

    <div class="container contenedor-banco">
        <h2>Panel de Gestión General</h2>

        <!-- Dashboard de Métricas Clave -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Total Clientes</h3>
                <p id="totalClients">Cargando...</p>
            </div>
            <div class="dashboard-card">
                <h3>Total Créditos Activos</h3>
                <p id="totalActiveCredits">Cargando...</p>
            </div>
            <div class="dashboard-card">
                <h3>Créditos en Mora</h3>
                <p id="creditsInMora">Cargando...</p>
            </div>
            <div class="dashboard-card">
                <h3>Monto Total Transacciones</h3>
                <p id="totalTransactionsAmount">Cargando...</p>
            </div>
        </div>

        <!-- Transacciones Recientes -->
        <div class="transactions-section">
            <h3>Transacciones Recientes</h3>
            <div style="overflow-x: auto;">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>ID Transacción</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Asesor</th>
                        </tr>
                    </thead>
                    <tbody id="recentTransactionsBody">
                        <tr><td colspan="6">Cargando transacciones...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botones de Consulta -->
        <div class="action-buttons">
            <button onclick="openConsultModal('client')">Consultar Cliente</button>
            <button onclick="openConsultModal('advisor')">Consultar Asesor</button>
            <button onclick="openConsultModal('credit')">Consultar Crédito</button>
        </div>
    </div>

    <?php include '../public/layout/frontendBackend.php'; ?>
    <?php include '../public/layout/layoutfooter.php'; ?>
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

    <!-- Modales de Consulta (una para cada tipo) -->
    <div id="clientModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Consultar Cliente</h3>
                <button type="button" class="modal-close-btn" onclick="closeConsultModal('client')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="text" id="clientSearchInput" class="modal-search-input" placeholder="Documento del Cliente">
                <button class="modal-search-btn" onclick="searchClientDetails()">Buscar</button>
                <div id="clientDetailsContent" class="mt-4">
                    <!-- Detalles del cliente se cargarán aquí -->
                    <p>Ingrese un documento y presione buscar.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeConsultModal('client')">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="advisorModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Consultar Asesor</h3>
                <button type="button" class="modal-close-btn" onclick="closeConsultModal('advisor')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="text" id="advisorSearchInput" class="modal-search-input" placeholder="Documento del Asesor">
                <button class="modal-search-btn" onclick="searchAdvisorDetails()">Buscar</button>
                <div id="advisorDetailsContent" class="mt-4">
                    <!-- Detalles del asesor se cargarán aquí -->
                    <p>Ingrese un documento y presione buscar.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeConsultModal('advisor')">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="creditModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Consultar Crédito</h3>
                <button type="button" class="modal-close-btn" onclick="closeConsultModal('credit')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="text" id="creditSearchInput" class="modal-search-input" placeholder="ID del Crédito">
                <button class="modal-search-btn" onclick="searchCreditDetails()">Buscar</button>
                <div id="creditDetailsContent" class="mt-4">
                    <!-- Detalles del crédito se cargarán aquí -->
                    <p>Ingrese un ID de crédito y presione buscar.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeConsultModal('credit')">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Función genérica para abrir una modal de consulta
        function openConsultModal(type) {
            document.getElementById(`${type}Modal`).classList.add('show');
            // Limpiar contenido previo al abrir
            document.getElementById(`${type}DetailsContent`).innerHTML = `<p>Ingrese un ${type === 'client' ? 'documento' : (type === 'advisor' ? 'documento' : 'ID de crédito')} y presione buscar.</p>`;
            document.getElementById(`${type}SearchInput`).value = ''; // Limpiar input de búsqueda
        }

        // Función genérica para cerrar una modal de consulta
        function closeConsultModal(type) {
            document.getElementById(`${type}Modal`).classList.remove('show');
        }

        // --- Funciones para cargar datos del Dashboard ---
        async function loadDashboardMetrics() {
            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=getDashboardMetrics");
                if (!response.ok) throw new Error('Error al cargar métricas del dashboard.');
                const data = await response.json();

                if (data.exito) {
                    document.getElementById('totalClients').textContent = data.metrics.totalClients.toLocaleString();
                    document.getElementById('totalActiveCredits').textContent = data.metrics.totalActiveCredits.toLocaleString();
                    document.getElementById('creditsInMora').textContent = data.metrics.creditsInMora.toLocaleString();
                    document.getElementById('totalTransactionsAmount').textContent = `$${parseFloat(data.metrics.totalTransactionsAmount).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                } else {
                    showModal('Error', data.mensaje || 'No se pudieron cargar las métricas.', 'error');
                }
            } catch (error) {
                console.error("Error al cargar métricas del dashboard:", error);
                showModal('Error de Conexión', 'No se pudieron cargar las métricas del dashboard. ' + error.message, 'error');
            }
        }

        // --- Funciones para cargar Transacciones Recientes ---
        async function loadRecentTransactions() {
            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=getRecentTransactions");
                if (!response.ok) throw new Error('Error al cargar transacciones recientes.');
                const data = await response.json();

                const tableBody = document.getElementById('recentTransactionsBody');
                tableBody.innerHTML = ''; // Limpiar tabla

                if (data.exito && data.transactions.length > 0) {
                    data.transactions.forEach(tx => {
                        const row = tableBody.insertRow();
                        row.insertCell().textContent = tx.ID_PagoCuota || tx.ID_Transaccion; // Ajusta según tu DB
                        row.insertCell().textContent = tx.Tipo_Transaccion || 'Pago Cuota'; // Ajusta según tu DB
                        row.insertCell().textContent = `$${parseFloat(tx.Monto_Pagado_Transaccion || tx.Monto).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                        row.insertCell().textContent = new Date(tx.Fecha_Hora_Pago || tx.Fecha_Transaccion).toLocaleString('es-CO');
                        row.insertCell().textContent = tx.Nombre_Cliente ? `${tx.Nombre_Cliente} ${tx.Apellido_Cliente}` : 'N/A';
                        row.insertCell().textContent = tx.Nombre_Personal ? `${tx.Nombre_Personal} ${tx.Apellido_Personal}` : 'N/A';
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6">No hay transacciones recientes.</td></tr>';
                }
            } catch (error) {
                console.error("Error al cargar transacciones recientes:", error);
                tableBody.innerHTML = '<tr><td colspan="6">Error al cargar transacciones.</td></tr>';
                showModal('Error de Conexión', 'No se pudieron cargar las transacciones recientes. ' + error.message, 'error');
            }
        }

        // --- Funciones para buscar detalles en las modales ---

        // Buscar Cliente
        async function searchClientDetails() {
            const documento = document.getElementById('clientSearchInput').value;
            const contentDiv = document.getElementById('clientDetailsContent');
            contentDiv.innerHTML = '<p>Buscando cliente...</p>';

            if (!documento) {
                contentDiv.innerHTML = '<p>Por favor, ingrese un número de documento.</p>';
                return;
            }

            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=getClientDetails", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ documento: documento })
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.exito && data.cliente) {
                    let detailsHtml = `
                        <h4 class="font-bold text-lg text-green-700">Información del Cliente</h4>
                        <p><strong>Nombre:</strong> ${data.cliente.Nombre_Cliente} ${data.cliente.Apellido_Cliente}</p>
                        <p><strong>Documento:</strong> ${data.cliente.N_Documento_Cliente}</p>
                        <p><strong>Correo:</strong> ${data.cliente.Correo_Cliente}</p>
                        <p><strong>Celular:</strong> ${data.cliente.Celular_Cliente}</p>
                        <p><strong>Dirección:</strong> ${data.cliente.Direccion_Cliente}</p>
                        <p><strong>Ciudad:</strong> ${data.cliente.Ciudad_Cliente}</p>
                        <p><strong>Fecha Nacimiento:</strong> ${new Date(data.cliente.Fecha_Nacimiento_Cliente).toLocaleDateString('es-CO')}</p>
                    `;
                    if (data.credito) {
                        detailsHtml += `
                            <h4 class="font-bold text-lg text-green-700 mt-4">Información del Crédito</h4>
                            <p><strong>Monto Total:</strong> $${parseFloat(data.credito.Monto_Total_Credito).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                            <p><strong>Monto Pendiente:</strong> $${parseFloat(data.credito.Monto_Pendiente_Credito).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                            <p><strong>Fecha Apertura:</strong> ${new Date(data.credito.Fecha_Apertura_Credito).toLocaleDateString('es-CO')}</p>
                            <p><strong>Estado Crédito:</strong> ${data.credito.Estado_Credito}</p>
                            <p><strong>Número de Cuotas:</strong> ${data.credito.Numero_Cuotas}</p>
                            <p><strong>Valor Cuota:</strong> $${parseFloat(data.credito.Valor_Cuota_Calculado).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        `;
                    } else {
                        detailsHtml += `<p class="mt-4 text-gray-600">Este cliente no tiene un crédito activo.</p>`;
                    }
                    contentDiv.innerHTML = detailsHtml;
                } else {
                    contentDiv.innerHTML = `<p class="text-red-500">${data.mensaje || 'Cliente no encontrado.'}</p>`;
                }
            } catch (error) {
                console.error("Error al buscar cliente:", error);
                contentDiv.innerHTML = `<p class="text-red-500">Error al buscar cliente: ${error.message}</p>`;
            }
        }

        // Buscar Asesor
        async function searchAdvisorDetails() {
            const documento = document.getElementById('advisorSearchInput').value;
            const contentDiv = document.getElementById('advisorDetailsContent');
            contentDiv.innerHTML = '<p>Buscando asesor...</p>';

            if (!documento) {
                contentDiv.innerHTML = '<p>Por favor, ingrese un número de documento.</p>';
                return;
            }

            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=getAdvisorDetails", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ documento: documento })
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.exito && data.asesor) {
                    let detailsHtml = `
                        <h4 class="font-bold text-lg text-green-700">Información del Asesor</h4>
                        <p><strong>Nombre:</strong> ${data.asesor.Nombre_Personal} ${data.asesor.Apellido_Personal}</p>
                        <p><strong>Documento:</strong> ${data.asesor.N_Documento_Personal}</p>
                        <p><strong>Correo:</strong> ${data.asesor.Correo_Personal}</p>
                        <p><strong>Celular:</strong> ${data.asesor.Celular_Personal}</p>
                        <p><strong>Rol:</strong> ${data.asesor.Nombre_Rol}</p>
                        <p><strong>Fecha Creación:</strong> ${new Date(data.asesor.Fecha_Creacion_Personal).toLocaleDateString('es-CO')}</p>
                        <p><strong>Estado:</strong> ${data.asesor.Estado_Personal == 1 ? 'Activo' : 'Inactivo'}</p>
                    `;
                    if (data.productosAsociados && data.productosAsociados.length > 0) {
                        detailsHtml += `<h4 class="font-bold text-lg text-green-700 mt-4">Productos Asociados</h4><ul>`;
                        data.productosAsociados.forEach(prod => {
                            detailsHtml += `<li>${prod.NombreProducto}</li>`;
                        });
                        detailsHtml += `</ul>`;
                    } else {
                        detailsHtml += `<p class="mt-4 text-gray-600">Este asesor no tiene productos asociados.</p>`;
                    }
                    contentDiv.innerHTML = detailsHtml;
                } else {
                    contentDiv.innerHTML = `<p class="text-red-500">${data.mensaje || 'Asesor no encontrado.'}</p>`;
                }
            } catch (error) {
                console.error("Error al buscar asesor:", error);
                contentDiv.innerHTML = `<p class="text-red-500">Error al buscar asesor: ${error.message}</p>`;
            }
        }

        // Buscar Crédito
        async function searchCreditDetails() {
            const creditId = document.getElementById('creditSearchInput').value;
            const contentDiv = document.getElementById('creditDetailsContent');
            contentDiv.innerHTML = '<p>Buscando crédito...</p>';

            if (!creditId || isNaN(creditId)) {
                contentDiv.innerHTML = '<p>Por favor, ingrese un ID de crédito válido.</p>';
                return;
            }

            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=getCreditDetails", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ idCredito: parseInt(creditId) })
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.exito && data.credito) {
                    let detailsHtml = `
                        <h4 class="font-bold text-lg text-green-700">Información del Crédito (ID: ${data.credito.ID_Credito})</h4>
                        <p><strong>Cliente:</strong> ${data.credito.Nombre_Cliente} ${data.credito.Apellido_Cliente}</p>
                        <p><strong>Producto:</strong> ${data.credito.NombreProducto}</p>
                        <p><strong>Monto Total:</strong> $${parseFloat(data.credito.Monto_Total_Credito).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        <p><strong>Monto Pendiente:</strong> $${parseFloat(data.credito.Monto_Pendiente_Credito).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        <p><strong>Fecha Apertura:</strong> ${new Date(data.credito.Fecha_Apertura_Credito).toLocaleDateString('es-CO')}</p>
                        <p><strong>Fecha Vencimiento:</strong> ${new Date(data.credito.Fecha_Vencimiento_Credito).toLocaleDateString('es-CO')}</p>
                        <p><strong>Estado Crédito:</strong> ${data.credito.Estado_Credito}</p>
                        <p><strong>Número de Cuotas:</strong> ${data.credito.Numero_Cuotas}</p>
                        <p><strong>Valor Cuota:</strong> $${parseFloat(data.credito.Valor_Cuota_Calculado).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        <p><strong>Periodicidad:</strong> ${data.credito.Periodicidad}</p>
                        <p><strong>Tasa Interés Anual:</strong> ${data.credito.Tasa_Interes_Anual}%</p>
                        <p><strong>Tasa Interés Periódica:</strong> ${data.credito.Tasa_Interes_Periodica}%</p>
                    `;
                    if (data.cuotas && data.cuotas.length > 0) {
                        detailsHtml += `
                            <h4 class="font-bold text-lg text-green-700 mt-4">Cuotas del Crédito</h4>
                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #eee; border-radius: 5px;">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="p-2 border-b">#</th>
                                            <th class="p-2 border-b">Vencimiento</th>
                                            <th class="p-2 border-b">Monto Cuota</th>
                                            <th class="p-2 border-b">Monto Pagado</th>
                                            <th class="p-2 border-b">Días Mora</th>
                                            <th class="p-2 border-b">Recargo Mora</th>
                                            <th class="p-2 border-b">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        data.cuotas.forEach(cuota => {
                            const isPaid = cuota.Estado_Cuota === 'Pagado';
                            detailsHtml += `
                                <tr class="${isPaid ? 'bg-green-50' : ''}">
                                    <td class="p-2 border-b">${cuota.Numero_Cuota}</td>
                                    <td class="p-2 border-b">${new Date(cuota.Fecha_Vencimiento).toLocaleDateString('es-CO')}</td>
                                    <td class="p-2 border-b">$${parseFloat(cuota.Monto_Total_Cuota).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td class="p-2 border-b">$${parseFloat(cuota.Monto_Pagado).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td class="p-2 border-b">${cuota.Dias_Mora_Al_Pagar}</td>
                                    <td class="p-2 border-b">$${parseFloat(cuota.Monto_Recargo_Mora).toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td class="p-2 border-b">${cuota.Estado_Cuota}</td>
                                </tr>
                            `;
                        });
                        detailsHtml += `</tbody></table></div>`;
                    } else {
                        detailsHtml += `<p class="mt-4 text-gray-600">No se encontraron cuotas para este crédito.</p>`;
                    }
                    contentDiv.innerHTML = detailsHtml;
                } else {
                    contentDiv.innerHTML = `<p class="text-red-500">${data.mensaje || 'Crédito no encontrado.'}</p>`;
                }
            } catch (error) {
                console.error("Error al buscar crédito:", error);
                contentDiv.innerHTML = `<p class="text-red-500">Error al buscar crédito: ${error.message}</p>`;
            }
        }

        // Cargar datos al iniciar la página
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardMetrics();
            loadRecentTransactions();
        });
    </script>
</body>
</html>
