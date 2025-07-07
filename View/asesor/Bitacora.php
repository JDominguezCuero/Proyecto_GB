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
    </style>
</head>
<body>
    <?php include '../public/layout/barraNavAsesor.php'; ?>

    <div class="bitacora-container">
        <h2>Bitácora de Actividad del Sistema</h2>

        <div class="filter-section">
            <label for="filterDocumento">Filtrar por Documento (Cliente/Personal):</label>
            <input type="text" id="filterDocumento" placeholder="Ingrese documento...">
            <label for="filterEventType">Tipo de Evento:</label>
            <select id="filterEventType">
                <option value="">Todos</option>
                <!-- Opciones de tipo de evento se pueden cargar dinámicamente o ser estáticas -->
                <option value="Login">Login</option>
                <option value="Registro Cliente">Registro Cliente</option>
                <option value="Registro Asesor">Registro Asesor</option>
                <option value="Abono Cuota">Abono Cuota</option>
                <option value="Creacion Credito">Creación Crédito</option>
                <!-- Agrega más tipos de evento según tu DB -->
            </select>
            <button onclick="applyFilters()">Aplicar Filtros</button>
        </div>

        <div id="bitacoraEventsContainer">
            <!-- Los registros de la bitácora se cargarán aquí -->
            <p class="no-records">Cargando registros de bitácora...</p>
        </div>
    </div>

    <?php include '../public/layout/frontendBackend.php'; ?>
    <?php include '../public/layout/layoutfooter.php'; ?>
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

    <!-- Modal para Detalles del Evento -->
    <div id="eventDetailsModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalEventTitle">Detalles del Evento</h3>
                <button type="button" class="modal-close-btn" onclick="closeEventDetailsModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalEventBody">
                <!-- Los detalles del evento se cargarán aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEventDetailsModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        let allBitacoraRecords = []; // Almacenará todos los registros para el filtrado local

        async function loadBitacoraRecords() {
            const container = document.getElementById('bitacoraEventsContainer');
            container.innerHTML = '<p class="no-records">Cargando registros de bitácora...</p>';

            try {
                const response = await fetch("<?= BASE_URL ?>/Controlador/gerenteController.php?accion=listarBitacora");
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.exito && data.registros.length > 0) {
                    allBitacoraRecords = data.registros; // Guardar todos los registros
                    renderBitacora(allBitacoraRecords); // Renderizar inicialmente todos
                } else {
                    container.innerHTML = '<p class="no-records">No se encontraron registros de bitácora.</p>';
                    allBitacoraRecords = [];
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
                // Añadir un listener de clic a cada tarjeta
                card.addEventListener('click', () => openEventDetailsModal(record));

                const clientName = record.Nombre_Cliente ? `${record.Nombre_Cliente} ${record.Apellido_Cliente}` : 'N/A';
                // Usar N_Documento_Cliente para el documento del cliente si está disponible
                const clientDoc = record.N_Documento_Cliente ? ` (Doc: ${record.N_Documento_Cliente})` : '';

                const personalName = record.Nombre_Personal ? `${record.Nombre_Personal} ${record.Apellido_Personal}` : 'N/A';
                // Usar N_Documento_Personal para el documento del personal si está disponible
                const personalDoc = record.N_Documento_Personal ? ` (Doc: ${record.N_Documento_Personal})` : '';

                const fechaHora = new Date(record.Fecha_Hora).toLocaleString('es-CO');

                card.innerHTML = `
                    <div class="event-header">
                        <span class="event-type">${record.Tipo_Evento}</span>
                        <span class="event-id">ID: ${record.ID_Bitacora}</span>
                    </div>
                    <p class="event-description">${record.Descripcion_Evento}</p>
                    <div class="event-details">
                        <p><strong>Fecha y Hora:</strong> ${fechaHora}</p>
                        <p><strong>Cliente:</strong> ${clientName}${clientDoc}</p>
                        <p><strong>Personal:</strong> ${personalName}${personalDoc}</p>
                        ${record.ID_RegistroAsesoramiento ? `<p><strong>ID Asesoramiento:</strong> ${record.ID_RegistroAsesoramiento}</p>` : ''}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function applyFilters() {
            const documentoFilter = document.getElementById('filterDocumento').value.toLowerCase();
            const eventTypeFilter = document.getElementById('filterEventType').value;

            const filteredRecords = allBitacoraRecords.filter(record => {
                // Asegurarse de que los campos de documento existan antes de llamar a toLowerCase()
                const clientDocMatch = record.N_Documento_Cliente ? record.N_Documento_Cliente.toLowerCase().includes(documentoFilter) : false;
                const personalDocMatch = record.N_Documento_Personal ? record.N_Documento_Personal.toLowerCase().includes(documentoFilter) : false;
                
                const matchesDocumento = (clientDocMatch || personalDocMatch || documentoFilter === '');

                const matchesEventType = (eventTypeFilter === '' || record.Tipo_Evento === eventTypeFilter);

                return matchesDocumento && matchesEventType;
            });

            renderBitacora(filteredRecords);
        }

        // --- Funciones para la Modal de Detalles del Evento ---
        function openEventDetailsModal(record) {
            const modal = document.getElementById('eventDetailsModal');
            const modalTitle = document.getElementById('modalEventTitle');
            const modalBody = document.getElementById('modalEventBody');

            modalTitle.textContent = `Detalles de Evento: ${record.Tipo_Evento} (ID: ${record.ID_Bitacora})`;
            
            const clientName = record.Nombre_Cliente ? `${record.Nombre_Cliente} ${record.Apellido_Cliente}` : 'N/A';
            const clientDoc = record.N_Documento_Cliente ? `${record.N_Documento_Cliente}` : 'N/A';
            const personalName = record.Nombre_Personal ? `${record.Nombre_Personal} ${record.Apellido_Personal}` : 'N/A';
            const personalDoc = record.N_Documento_Personal ? `${record.N_Documento_Personal}` : 'N/A';
            const fechaHora = new Date(record.Fecha_Hora).toLocaleString('es-CO');

            modalBody.innerHTML = `
                <div class="modal-details-grid">
                    <div class="modal-detail-item">
                        <strong>ID del Evento:</strong>
                        <p>${record.ID_Bitacora}</p>
                    </div>
                    <div class="modal-detail-item">
                        <strong>Tipo de Evento:</strong>
                        <p>${record.Tipo_Evento}</p>
                    </div>
                    <div class="modal-full-width-item">
                        <strong>Descripción Detallada:</strong>
                        <p>${record.Descripcion_Evento}</p>
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
                        ${record.ID_Cliente ? `
                        <div class="modal-detail-item">
                            <strong>ID Cliente:</strong>
                            <p>${record.ID_Cliente}</p>
                        </div>` : ''}
                    </div>
                </div>

                <div class="modal-full-width-item mt-6">
                    <strong>Información del Personal (Asesor):</strong>
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
    </script>

</body>
</html>
