<?php
session_start();
require_once(__DIR__ . '../../../Config/config.php');

if (isset($_GET['login']) && $_GET['login'] == 'success') {
    $user = htmlspecialchars($_SESSION['nombre'] ?? 'Asesor');
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('✅ Operación Exitosa', 'Bienvenido @$user.', 'success');
        });
        </script>";
}
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
    <title>Bitácora de Actividad</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .bitacora-container {
            margin: 30px auto;
            width: 95%;
            max-width: 1000px;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 25px;
            text-align: center;
            font-size: 2em;
        }
        .filter-section {
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap; /* Permite que los elementos se envuelvan */
            gap: 15px;
            justify-content: flex-end;
            align-items: center;
        }
        .filter-section label {
            font-weight: bold;
            color: #555;
            white-space: nowrap; /* Evita que la etiqueta se rompa */
        }
        .filter-section input[type="text"],
        .filter-section select {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            flex-grow: 1; /* Permite que los inputs crezcan */
            min-width: 150px; /* Ancho mínimo para inputs */
        }
        .filter-section button {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            flex-shrink: 0; /* Evita que el botón se encoja */
        }
        .filter-section button:hover {
            background-color: #1b5e20;
        }

        /* Estilos para las tarjetas de eventos de la bitácora */
        #bitacoraEventsContainer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* 2 o 3 columnas */
            gap: 20px;
        }
        .event-card {
            background-color: #f8f8f8;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer; /* Indica que es clickeable */
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }
        .event-header .event-type {
            font-weight: bold;
            color: #2e7d32;
            font-size: 1.1em;
        }
        .event-header .event-id {
            font-size: 0.9em;
            color: #888;
        }
        .event-description {
            font-size: 0.95em;
            color: #444;
            margin-bottom: 15px;
            line-height: 1.5;
            /* Limitar la altura para la vista previa */
            max-height: 4.5em; /* Aproximadamente 3 líneas */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Número de líneas a mostrar */
            -webkit-box-orient: vertical;
        }
        .event-details {
            font-size: 0.85em;
            color: #666;
            line-height: 1.4;
        }
        .event-details p {
            margin: 3px 0;
        }
        .event-details strong {
            color: #333;
        }
        .no-records {
            text-align: center;
            color: #888;
            padding: 30px;
            font-size: 1.1em;
            grid-column: 1 / -1; /* Ocupa todas las columnas en grid */
        }

        @media (max-width: 768px) {
            .bitacora-container {
                padding: 15px;
            }
            .filter-section {
                justify-content: center;
            }
            .filter-section input[type="text"],
            .filter-section select,
            .filter-section button {
                width: 100%;
                flex-grow: unset; /* Deshacer flex-grow en móvil */
            }
            #bitacoraEventsContainer {
                grid-template-columns: 1fr; /* Una sola columna en móviles */
            }
        }

        /* --- Estilos para la Modal de Detalles del Evento --- */
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
            max-width: 700px; /* Ancho ajustado para más espacio */
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
            font-size: 1.5em;
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
        /* Estilos para el nuevo layout de la modal */
        .modal-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnas */
            gap: 15px 25px; /* Espacio entre filas y columnas */
        }
        .modal-detail-item {
            /* margin-bottom: 10px; -- Removido, el gap del grid lo maneja */
        }
        .modal-detail-item strong {
            display: block;
            margin-bottom: 3px;
            color: #2e7d32;
            font-size: 0.95em; /* Un poco más pequeño para el título */
        }
        .modal-detail-item p {
            margin: 0;
            color: #444;
            font-size: 1em;
            line-height: 1.4;
        }
        .modal-full-width-item {
            grid-column: 1 / -1; /* Ocupa todo el ancho */
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #eee;
        }
        .modal-full-width-item strong {
            font-size: 1.1em;
            color: #1b5e20;
            margin-bottom: 8px;
        }
        
        @media (max-width: 550px) {
            .modal-details-grid {
                grid-template-columns: 1fr; /* Una columna en pantallas muy pequeñas */
            }
        }

        /* Estilos para el modal de Exportar (similar al de detalles pero separado) */
        #modalExportar {
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
        #modalExportar.show {
            opacity: 1;
            visibility: visible;
        }
        #modalExportar .modal-content-export { /* Usar una clase diferente para evitar conflictos de estilos */
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 400px;
            position: relative;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            display: flex; /* Añadido para flexbox en el contenido */
            flex-direction: column; /* Apila elementos verticalmente */
            align-items: center; /* Centra elementos horizontalmente */
        }
        #modalExportar.show .modal-content-export {
            transform: translateY(0);
        }
        #modalExportar .modal-header {
            width: 100%; /* El header ocupa todo el ancho */
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            text-align: center; /* Centrar el título */
        }
        #modalExportar .modal-header h3 {
            flex-grow: 1; /* Permite que el título ocupe el espacio disponible */
            text-align: center; /* Centra el texto del título */
            color: #8B5CF6; /* Un color distinto para el modal de exportar */
            margin: 0; /* Elimina márgenes por defecto del h3 */
        }
        /* Ajuste para el botón de cierre en el header */
        #modalExportar .modal-header #cerrarModalExportarTop {
            position: absolute; /* Posiciona el botón de cierre en la esquina */
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #555;
            padding: 0; /* Elimina padding para que sea más pequeño */
            width: auto; /* Elimina el 100% de width */
            margin: 0; /* Elimina el margin-bottom */
        }
        #modalExportar .modal-header #cerrarModalExportarTop:hover {
            color: #333;
        }
        /* Ajustes para el contenido del body del modal de exportación */
        #modalExportar .mt-2.px-4.py-3 {
            width: 100%;
            padding: 15px 25px; /* Ajuste de padding */
            margin-top: 0; /* Eliminar margen superior si Tailwind lo pone */
        }
        #modalExportar .text-sm.text-gray-500.mb-4.text-center {
            margin-bottom: 20px; /* Más espacio debajo de la descripción */
            line-height: 1.5;
        }
        #modalExportar .mb-4.text-left {
            width: 100%; /* Asegura que el contenedor del select ocupe todo el ancho */
            margin-bottom: 20px; /* Más espacio debajo del select */
        }
        #modalExportar .mb-4.text-left label {
            text-align: left; /* Alinea la etiqueta a la izquierda */
            width: 100%; /* La etiqueta ocupa todo el ancho */
        }
        #modalExportar .mb-4.text-left select {
            width: 100%;
            padding: 12px 15px; /* Más padding para el select */
            border: 1px solid #d1d5db; /* Color de borde más suave */
            border-radius: 8px;
            font-size: 1em;
        }
        #modalExportar .items-center.px-4.py-3.flex.flex-col {
            width: 100%;
            padding-top: 0; /* Eliminar padding superior si Tailwind lo pone */
            align-items: stretch; /* Estira los botones para que ocupen todo el ancho */
        }
        #modalExportar button {
            background-color: #8B5CF6; /* Botones de exportar de color púrpura */
            color: white;
            padding: 12px 20px; /* Más padding para los botones */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em; /* Ajuste de tamaño de fuente */
            transition: background-color 0.3s ease;
            width: 100%; /* Asegura que los botones ocupen todo el ancho disponible */
            margin-bottom: 15px; /* Más espacio entre botones */
        }
        #modalExportar button:last-child {
            margin-bottom: 0; /* Eliminar margen inferior del último botón */
        }
        #modalExportar button:hover {
            background-color: #7C3AED;
        }
        #modalExportar #exportarExcel {
            background-color: #22C55E; /* Verde para Excel */
        }
        #modalExportar #exportarExcel:hover {
            background-color: #16A34A;
        }
        #modalExportar #exportarPdf {
            background-color: #EF4444; /* Rojo para PDF */
        }
        #modalExportar #exportarPdf:hover {
            background-color: #DC2626;
        }
        #modalExportar #cerrarModalExportar {
            background-color: #6B7280; /* Gris para Cancelar */
        }
        #modalExportar #cerrarModalExportar:hover {
            background-color: #4B5563;
        }

    </style>
</head>
<body>
    <?php include '../public/layout/barraNavAsesor.php'; ?>

    <div class="bitacora-container">
        <h2>Bitácora de Actividad del Sistema</h2>

        <div class="filter-section">
            <label for="filterDocumento">Filtrar por Documento (Cliente/Personal):</label>
            <input type="text" id="filterDocumento" placeholder="Ingrese documento...">
            <label for="filterRol">Filtrar por Tipo de Personal:</label>
            <select id="filterRol">
                <option value="">Ambos</option>
                <option value="3">Asesor</option>
                <option value="4">Cajero</option>
            </select>
            <button onclick="applyFilters()">Aplicar Filtros</button>
            <button id="btnExportar" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Exportar Bitácora</button>
        </div>

        <div id="bitacoraEventsContainer">
            <p class="no-records">Cargando registros de bitácora...</p>
        </div>
    </div>

    <?php include '../public/layout/frontendBackend.php'; ?>
    <?php include '../public/layout/layoutfooter.php'; ?>
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

    <div id="eventDetailsModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalEventTitle">Detalles del Evento</h3>
                <button type="button" class="modal-close-btn" onclick="closeEventDetailsModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalEventBody">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEventDetailsModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="modalExportar" class="modal-overlay">
        <div class="modal-content-export">
            <div class="modal-header">
                <h3>Opciones de Exportación</h3>
                <button type="button" class="modal-close-btn" id="cerrarModalExportarTop">&times;</button>
            </div>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500 mb-4 text-center">Selecciona los filtros y el formato de exportación.</p>

                <div class="mb-4 text-left">
                    <label for="filtroPersonal" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Personal que Ejecutó el Evento:</label>
                    <select id="filtroPersonal" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="ambos">Asesor y Cajero</option>
                        <option value="asesor">Solo Asesores</option>
                        <option value="cajero">Solo Cajeros</option>
                    </select>
                </div>
            </div>
            <div class="items-center px-4 py-3 flex flex-col">
                <button id="exportarExcel" class="bg-green-500 hover:bg-green-600">
                    Exportar a Excel
                </button>
                <button id="exportarPdf" class="bg-red-500 hover:bg-red-600">
                    Generar PDF
                </button>
                <button id="cerrarModalExportar" class="bg-gray-500 hover:bg-gray-600">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <script>
        let allBitacoraRecords = []; // Almacenará todos los registros para el filtrado local
        let currentFilteredRecords = []; // Almacenará los registros actualmente visibles en la tabla

        async function loadBitacoraRecords() {
            const container = document.getElementById('bitacoraEventsContainer');
            container.innerHTML = '<p class="no-records">Cargando registros de bitácora...</p>';

            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=listarBitacora");
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.exito && data.registros.length > 0) {
                    allBitacoraRecords = data.registros; // Guardar todos los registros
                    applyFilters(); // Aplicar filtros iniciales y renderizar
                } else {
                    container.innerHTML = '<p class="no-records">No se encontraron registros de bitácora.</p>';
                    allBitacoraRecords = [];
                    currentFilteredRecords = []; // También vaciar esta
                }
            } catch (error) {
                console.error("Error al cargar la bitácora:", error);
                container.innerHTML = '<p class="no-records">Error al cargar la bitácora. ' + error.message + '</p>';
                showModal('Error de Carga', 'No se pudieron cargar los registros de la bitácora. ' + error.message, 'error');
            }
        }

        function renderBitacora(recordsToRender) {
            const container = document.getElementById('bitacoraEventsContainer');
            container.innerHTML = ''; // Limpiar el contenedor

            if (recordsToRender.length === 0) {
                container.innerHTML = '<p class="no-records">No se encontraron registros que coincidan con los filtros.</p>';
                return;
            }

            recordsToRender.forEach(record => {
                const card = document.createElement('div');
                card.className = 'event-card';
                card.addEventListener('click', () => openEventDetailsModal(record));

                const clientName = record.Nombre_Cliente ? `${record.Nombre_Cliente} ${record.Apellido_Cliente}` : 'N/A';
                const clientDoc = record.N_Documento_Cliente ? ` (Doc: ${record.N_Documento_Cliente})` : '';

                const personalName = record.Nombre_Personal ? `${record.Nombre_Personal} ${record.Apellido_Personal}` : 'N/A';
                const personalDoc = record.N_Documento_Personal ? ` (Doc: ${record.N_Documento_Personal})` : '';

                const fechaHora = record.Fecha_Hora ? new Date(record.Fecha_Hora).toLocaleString('es-CO') : 'No definido';

                let nombreIdentificado = '';
                if (record.ID_Cliente) {
                    nombreIdentificado = `<strong>Cliente:</strong> ${clientName} ${clientDoc}`;
                } else {
                    nombreIdentificado = `<strong>Usuario:</strong> ${record.Nombre_Completo_Solicitante ?? 'Sin Nombre'} ${record.N_Documento_Solicitante ?? ''}`;
                }

                card.innerHTML = `
                    <div class="event-header">
                        <span class="event-type">${record.Tipo_Evento ?? 'No Definido'}</span>
                        <span class="event-id">ID: ${record.ID_Bitacora ?? 'No Definido'}</span>
                    </div>
                    <p class="event-description">${record.Descripcion_Evento ?? 'No Definido'}</p>
                    <div class="event-details">
                        <p><strong>Fecha y Hora:</strong> ${fechaHora}</p>
                        <p>${nombreIdentificado}</p>
                        <p><strong>Personal:</strong> ${personalName}${personalDoc}</p>
                        ${record.ID_RegistroAsesoramiento ? `<p><strong>ID Asesoramiento:</strong> ${record.ID_RegistroAsesoramiento}</p>` : ''}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function applyFilters() {
            const documentoFilter = document.getElementById('filterDocumento').value.toLowerCase();
            const rolFilter = document.getElementById('filterRol').value;

            currentFilteredRecords = allBitacoraRecords.filter(record => {
                const clientDocMatch = record.N_Documento_Cliente ? record.N_Documento_Cliente.toString().toLowerCase().includes(documentoFilter) : false;
                const personalDocMatch = record.N_Documento_Personal ? record.N_Documento_Personal.toString().toLowerCase().includes(documentoFilter) : false;

                const matchesDocumento = (clientDocMatch || personalDocMatch || documentoFilter === '');

                let matchesRol = true;
                if (rolFilter !== '') {
                    matchesRol = record.ID_Rol == parseInt(rolFilter);
                }

                return matchesDocumento && matchesRol;
            });

            renderBitacora(currentFilteredRecords);
        }

        // --- Funciones para la Modal de Detalles del Evento ---
        function openEventDetailsModal(record) {
            const modal = document.getElementById('eventDetailsModal');
            const modalTitle = document.getElementById('modalEventTitle');
            const modalBody = document.getElementById('modalEventBody');

            modalTitle.textContent = `Detalles de Evento: ${record.Tipo_Evento ?? 'No Definido'} (ID: ${record.ID_Bitacora ?? 'No Definido'})`;

            const clientName = record.Nombre_Cliente ? `${record.Nombre_Cliente} ${record.Apellido_Cliente}` : 'No Definido';
            const clientDoc = record.N_Documento_Cliente ? `${record.N_Documento_Cliente}` : 'No Definido';
            const personalName = record.Nombre_Personal ? `${record.Nombre_Personal} ${record.Apellido_Personal}` : 'No Definido';
            const personalDoc = record.N_Documento_Personal ? `${record.N_Documento_Personal}` : 'No Definido';
            const fechaHora = record.Fecha_Hora ? new Date(record.Fecha_Hora).toLocaleString('es-CO') : 'No Definido';

            modalBody.innerHTML = `
                <div class="modal-details-grid">
                    <div class="modal-detail-item">
                        <strong>ID del Evento:</strong>
                        <p>${record.ID_Bitacora ?? 'No Definido'}</p>
                    </div>
                    <div class="modal-detail-item">
                        <strong>Tipo de Evento:</strong>
                        <p>${record.Tipo_Evento ?? 'No Definido'}</p>
                    </div>
                    <div class="modal-full-width-item">
                        <strong>Descripción Detallada:</strong>
                        <p>${record.Descripcion_Evento ?? 'No Definido'}</p>
                    </div>
                    <div class="modal-detail-item">
                        <strong>Fecha y Hora:</strong>
                        <p>${fechaHora}</p>
                    </div>
                    ${record.ID_RegistroAsesoramiento ? `
                    <div class="modal-detail-item">
                        <strong>ID Asesoramiento:</strong>
                        <p>${record.ID_RegistroAsesoramiento}</p>
                    </div>` : ''}
                </div>

                ${record.ID_Cliente ? `
                <div class="modal-full-width-item mt-6">
                    <strong>Información del Cliente:</strong>
                    <div class="modal-details-grid">
                        <div class="modal-detail-item">
                            <strong>Nombre Cliente:</strong>
                            <p>${clientName}</p>
                        </div>
                        <div class="modal-detail-item">
                            <strong>Documento Cliente:</strong>
                            <p>${clientDoc}</p>
                        </div>
                        <div class="modal-detail-item">
                            <strong>ID Cliente:</strong>
                            <p>${record.ID_Cliente}</p>
                        </div>
                    </div>
                </div>
                ` : `
                <div class="modal-full-width-item mt-6">
                    <strong>Información del Usuario:</strong>
                    <div class="modal-details-grid">
                        <div class="modal-detail-item">
                            <strong>Número de Turno:</strong>
                            <p>${record.Numero_Turno}</p>
                        </div>
                        <div class="modal-detail-item">
                            <strong>Nombre Completo:</strong>
                            <p>${record.Nombre_Completo_Solicitante}</p>
                        </div>
                        <div class="modal-detail-item">
                            <strong>Cédula:</strong>
                            <p>${record.N_Documento_Solicitante}</p>
                        </div>
                    </div>
                </div>
                `}

                <div class="modal-full-width-item mt-6">
                    <strong>Información del Personal: ${record.Rol}</strong>
                    <div class="modal-details-grid">
                        <div class="modal-detail-item">
                            <strong>Nombre Personal:</strong>
                            <p>${personalName}</p>
                        </div>
                        <div class="modal-detail-item">
                            <strong>Documento Personal:</strong>
                            <p>${personalDoc}</p>
                        </div>
                        ${record.ID_Personal ? `
                        <div class="modal-detail-item">
                            <strong>ID Personal:</strong>
                            <p>${record.ID_Personal}</p>
                        </div>` : ''}
                    </div>
                </div>
            `;

            modal.classList.add('show');
        }

        function closeEventDetailsModal() {
            document.getElementById('eventDetailsModal').classList.remove('show');
        }

        // Cargar los registros de la bitácora al cargar la página
        document.addEventListener('DOMContentLoaded', loadBitacoraRecords);

        // --- Lógica para el Nuevo Modal de Exportación ---
        const btnExportar = document.getElementById('btnExportar');
        const modalExportar = document.getElementById('modalExportar');
        const cerrarModalExportar = document.getElementById('cerrarModalExportar');
        const cerrarModalExportarTop = document.getElementById('cerrarModalExportarTop'); // Botón de cerrar en el header
        const filtroPersonal = document.getElementById('filtroPersonal');
        const exportarExcelBtn = document.getElementById('exportarExcel');
        const exportarPdfBtn = document.getElementById('exportarPdf');

        // Función para abrir el modal de exportación
        btnExportar.addEventListener('click', () => {
            modalExportar.classList.add('show');
        });

        // Funciones para cerrar el modal de exportación
        cerrarModalExportar.addEventListener('click', () => {
            modalExportar.classList.remove('show');
        });
        cerrarModalExportarTop.addEventListener('click', () => { // Listener para el botón de cerrar en el header
            modalExportar.classList.remove('show');
        });

        // Cerrar modal al hacer clic fuera de él
        modalExportar.addEventListener('click', (e) => {
            if (e.target === modalExportar) {
                modalExportar.classList.remove('show');
            }
        });

        exportarExcelBtn.addEventListener('click', () => {
            const dataToExport = getFilteredExportData();
            if (dataToExport.length === 0) {
                showModal('Advertencia', 'No hay registros para exportar a Excel con los filtros actuales.', 'warning');
                return;
            }

            // Crear una hoja de cálculo a partir de los datos JSON
            const ws = XLSX.utils.json_to_sheet(dataToExport);

            // **Aplicar estilos al encabezado**
            // Obtener el rango de la hoja para determinar las columnas presentes
            const range = XLSX.utils.decode_range(ws['!ref']); // Ej. A1:M4

            // Iterar sobre cada celda en la primera fila (fila del encabezado)
            for (let C = range.s.c; C <= range.e.c; ++C) { // C es el índice de la columna
                const cellAddress = XLSX.utils.encode_cell({ r: 0, c: C }); // r:0 es la primera fila (encabezado)
                const cell = ws[cellAddress];

                if (cell) { // Asegurarse de que la celda exista
                    if (!cell.s) {
                        cell.s = {}; // Inicializar el objeto de estilo si no existe
                    }

                    // Aplicar negrita
                    if (!cell.s.font) {
                        cell.s.font = {};
                    }
                    cell.s.font.bold = true;

                    // Aplicar color de fondo (verde oscuro)
                    if (!cell.s.fill) {
                        cell.s.fill = {};
                    }
                    cell.s.fill.fgColor = { rgb: "FF2E7D32" }; // FF es para transparencia (opaco), 2E7D32 es el color

                    // Aplicar color de texto (blanco)
                    if (!cell.s.font) {
                        cell.s.font = {};
                    }
                    cell.s.font.color = { rgb: "FFFFFFFF" }; // FF es para transparencia (opaco), FFFFFF es blanco
                }
            }

            // Crear un libro de trabajo
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Bitácora de Actividad");

            // Guardar el archivo
            const date = new Date();
            const dateString = date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0');
            const timeString = date.getHours().toString().padStart(2, '0') + date.getMinutes().toString().padStart(2, '0') + date.getSeconds().toString().padStart(2, '0');
            const fileName = `Bitacora_Actividad_${dateString}_${timeString}.xlsx`;
            XLSX.writeFile(wb, fileName);

            modalExportar.classList.remove('show');
            showModal('✅ Éxito', 'Bitácora exportada a Excel correctamente.', 'success');
        });

        // Lógica para el botón de Generar PDF
        exportarPdfBtn.addEventListener('click', () => {
            const dataToExport = getFilteredExportData(); // Obtener los datos ya filtrados

            if (dataToExport.length === 0) {
                showModal('Advertencia', 'No hay registros para generar PDF con los filtros actuales.', 'warning');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape'); // 'landscape' para orientación horizontal

            // **Agregar el Logo del Banco**
            // Usando la ruta que proporcionaste. Asegúrate que `BASE_URL` se resuelva correctamente.
            const logoUrl = '<?= BASE_URL ?>/View/public/assets/Img/logos/logo2.png';
            const logoWidth = 40; // Ancho del logo en mm
            const logoHeight = 40; // Alto del logo en mm
            const logoX = 14;     // Posición X del logo (margen izquierdo)
            const logoY = 10;     // Posición Y del logo (parte superior)

            doc.addImage(logoUrl, 'PNG', logoX, logoY, logoWidth, logoHeight);

            // **Parte informativa al inicio (aparece solo una vez al principio del PDF)**
            // Ajusta las coordenadas Y y X para que no se solapen con el logo y el texto quede mejor distribuido.
            const textStartX = logoX + logoWidth + 10; // Inicio del texto a la derecha del logo
            const initialY = logoY + 5; // Posición Y inicial para el texto, alineado con la parte superior del logo

            doc.setFontSize(18);
            doc.setTextColor(46, 125, 50); // Color verde oscuro
            doc.text("Reporte de Bitácora de Actividad del Sistema", textStartX, initialY);

            doc.setFontSize(10);
            doc.setTextColor(68, 68, 68); // Color gris oscuro
            doc.text("Este documento presenta un registro detallado de los eventos y acciones ocurridas en el sistema.", textStartX, initialY + 10);
            doc.text(`Fecha de Generación: ${new Date().toLocaleString('es-CO')}`, textStartX, initialY + 15);
            doc.text(`Total de Registros: ${dataToExport.length}`, textStartX, initialY + 20);
            doc.text("A continuación, se detalla cada registro con su información relevante.", textStartX, initialY + 25);


            // Preparar encabezados y cuerpo de la tabla
            const headers = Object.keys(dataToExport[0]); // Obtener encabezados de la primera fila
            const body = dataToExport.map(row => Object.values(row)); // Obtener solo los valores

            doc.autoTable({
                head: [headers],
                body: body,
                startY: Math.max(initialY + 35, logoY + logoHeight + 5), // Asegura que la tabla empiece después del logo y el texto descriptivo
                theme: 'grid', // 'striped', 'grid', 'plain'
                styles: { fontSize: 8, cellPadding: 2, overflow: 'linebreak' }, // Ajusta tamaño de fuente y padding
                headStyles: { fillColor: [46, 125, 50], textColor: 255, fontStyle: 'bold' }, // Color verde oscuro para encabezados
                columnStyles: {
                    // Si tienes columnas específicas y quieres controlar su ancho:
                    'Descripción': { cellWidth: 70 },
                    'Fecha y Hora': { cellWidth: 35 },
                    // Asegúrate de que los nombres de las columnas aquí coincidan exactamente con las claves de tu objeto en dataToExport
                    // Puedes ajustar o eliminar estas líneas si quieres que jsPDF maneje los anchos automáticamente
                },
                didDrawPage: function (data) {
                    // Pie de página (aparece en cada página)
                    let str = "Página " + doc.internal.getNumberOfPages();
                    doc.setFontSize(9);
                    doc.setTextColor(100);
                    doc.text(str, doc.internal.pageSize.width - data.settings.margin.right, doc.internal.pageSize.height - 10);
                }
            });

            // Pequeño texto al final del PDF (aparece solo una vez, después de toda la tabla)
            const finalY = doc.autoTable.previous.finalY;
            doc.setFontSize(9);
            doc.setTextColor(68, 68, 68);
            doc.text("Este reporte es generado automáticamente y refleja la actividad del sistema hasta la fecha y hora de emisión.", 14, finalY + 15);
            doc.text("Para cualquier consulta, por favor contacte al administrador del sistema. Gracias por su atención.", 14, finalY + 20);

            // Generar un nombre de archivo con fecha y hora
            const date = new Date();
            const dateString = date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0');
            const timeString = date.getHours().toString().padStart(2, '0') + date.getMinutes().toString().padStart(2, '0') + date.getSeconds().toString().padStart(2, '0');
            const fileName = `Bitacora_Actividad_${dateString}_${timeString}.pdf`;

            doc.save(fileName);
            modalExportar.classList.remove('show');
            showModal('✅ Éxito', 'Bitácora generada en PDF correctamente.', 'success');
        });

        // --- Modificación clave aquí ---
        // Función para preparar los datos filtrados para la exportación
        function getFilteredExportData() {
            const tipoPersonalFiltro = document.getElementById('filtroPersonal').value;

            // Filtra los 'currentFilteredRecords' (los que ya están en la vista)
            const filteredForExport = currentFilteredRecords.filter(record => {
                let matchesPersonalType = true;
                if (tipoPersonalFiltro !== 'ambos') {
                    if (tipoPersonalFiltro === 'asesor' && record.ID_Rol != 3) {
                        matchesPersonalType = false;
                    } else if (tipoPersonalFiltro === 'cajero' && record.ID_Rol != 4) {
                        matchesPersonalType = false;
                    }
                }
                return matchesPersonalType;
            });

            return filteredForExport.map(record => {
                const personalFullName = (record.Nombre_Personal && record.Apellido_Personal) ? `${record.Nombre_Personal} ${record.Apellido_Personal}` : 'No Definido';

                let tipoPersonalEjecuta = 'No Definido';
                if (record.ID_Rol == 3) {
                    tipoPersonalEjecuta = 'Asesor';
                } else if (record.ID_Rol == 4) {
                    tipoPersonalEjecuta = 'Cajero';
                } else if (record.ID_Personal) {
                    tipoPersonalEjecuta = 'Personal General';
                }

                let nombreRelacionado = 'N/A';
                let documentoRelacionado = 'N/A';
                let tipoEntidad = 'N/A';
                let idEntidad = 'N/A';
                let numeroTurno = 'N/A';

                if (record.ID_Cliente) {
                    nombreRelacionado = (record.Nombre_Cliente && record.Apellido_Cliente) ? `${record.Nombre_Cliente} ${record.Apellido_Cliente}` : 'No Definido';
                    documentoRelacionado = record.N_Documento_Cliente ?? 'No Definido';
                    tipoEntidad = 'Cliente';
                    idEntidad = record.ID_Cliente ?? 'No Definido';
                } else if (record.Nombre_Completo_Solicitante || record.N_Documento_Solicitante) {
                    nombreRelacionado = record.Nombre_Completo_Solicitante ?? 'No Definido';
                    documentoRelacionado = record.N_Documento_Solicitante ?? 'No Definido';
                    tipoEntidad = 'Usuario (No Cliente)';
                    idEntidad = record.ID_Turno ?? 'N/A';
                    numeroTurno = record.Numero_Turno ?? 'No Definido';
                }

                return {
                    'ID Bitácora': record.ID_Bitacora ?? 'No definido',
                    'Tipo de Evento': record.Tipo_Evento ?? 'No definido',
                    'Descripción': record.Descripcion_Evento ?? 'No definido',
                    'Fecha y Hora': record.Fecha_Hora ? new Date(record.Fecha_Hora).toLocaleString('es-CO') : 'No definido',
                    'Tipo Entidad Relacionada': tipoEntidad,
                    'Nombre Entidad Relacionada': nombreRelacionado,
                    'Documento Entidad Relacionada': documentoRelacionado,
                    'ID Entidad': idEntidad,
                    'Número de Turno': numeroTurno,
                    'ID Personal': record.ID_Personal ?? 'No definido',
                    'Nombre Personal': personalFullName,
                    'Documento Personal': record.N_Documento_Personal ?? 'No definido',
                    'Tipo de Personal Ejecuta': tipoPersonalEjecuta,
                    'ID Asesoramiento': record.ID_RegistroAsesoramiento ?? 'No definido'
                };
            });
        }
    </script>
</body>
</html>