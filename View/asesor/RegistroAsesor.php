<?php
session_start();
require_once(__DIR__ . '../../../Config/config.php');

// Los productos deberían cargarse desde la base de datos para estar siempre actualizados.
// Por ahora, usamos la lista estática que proporcionaste.
$productos = [
    1 => 'Compra de cartera de crédito hipotecario',
    2 => 'Compra de cartera de vehículo',
    3 => 'Compra de cartera de microcrédito',
    4 => 'Compra de cartera de crédito de educación',
    5 => 'Compra de cartera de libranza',
    6 => 'Compra de cartera de crédito agropecuario',
    7 => 'Compra de cartera de crédito comercial (grandes empresas)',
    8 => 'Compra de cartera de crédito de libre inversión',
    9 => 'Compra de cartera de crédito de consumo',
    10 => 'Compra de cartera de tarjetas de crédito',
    11 => 'Compra de cartera de crédito rotativo',
    12 => 'Compra de cartera (para personas naturales)',
    13 => 'Crédito de Vehículo',
    14 => 'Compra de Cartera de Vehículo',
    15 => 'Crédito educativo a corto plazo',
    16 => 'Crédito libre educativo',
    17 => 'Crediestudiantil',
    19 => 'Microcrédito',
    20 => 'Crédito Comercial',
    21 => 'Crédito Agrofácil',
    22 => 'Crédito Finagro',
    23 => 'CDT',
    24 => 'Crédito Hipotecario',
    27 => 'Compra de Cartera de Vivienda',
    28 => 'Crédito de descuento por nómina o mesada pencional',
    30 => 'Cuentas de Ahorro',
    31 => 'Cuenta de Ahorro Básica o Estándar',
    32 => 'Cuenta de Ahorro Programado',
    33 => 'Cuenta de Ahorro para Jóvenes o Niños',
    34 => 'Cuenta de Ahorro de Nómina',
    35 => 'Cuenta de Ahorro para Pensionados',
    36 => 'Cuenta de Ahorro en Moneda Extranjera',
    37 => 'Crédito Rotativo',
    38 => 'Tarjeta de crédito',
    39 => 'Crédito de Consumo',
    40 => 'Crédito Libre Inversión'
];


if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('❌ Error al registrar', '$mensaje', 'error');
        });
        </script>";
} else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Proceso Exitoso.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('✅ Operación Exitosa', '$mensaje', 'success');
        });
        </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>    
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/crearAsesor.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Asegura que el box-sizing sea consistente */
        * {
            box-sizing: border-box;
        }

        /* Estilos para el contenedor de checkboxes */
        .checkbox-group {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px;
            max-height: 150px; /* Altura máxima para el scroll */
            overflow-y: auto; /* Scroll si hay muchas opciones */
            background-color: white;
            margin-top: 6px; /* Espacio con la etiqueta */
            width: 100%; /* Asegura que ocupe todo el ancho disponible */

            /* --- CAMBIOS CLAVE PARA EL LAYOUT DE DOS COLUMNAS --- */
            display: grid; /* Usar CSS Grid */
            grid-template-columns: 1fr 1fr; /* Dos columnas de igual ancho */
            gap: 8px 15px; /* Espacio entre filas y columnas (vertical y horizontal) */
            /* ---------------------------------------------------- */
        }
        
        /* Estilos para cada item de checkbox (la etiqueta que contiene el input y el texto) */
        .checkbox-group .checkbox-item {
            display: flex; /* Contenedor flex para el checkbox y el texto */
            align-items: flex-start; /* Alineación vertical al inicio para el texto largo */
            font-weight: normal; /* El texto de la opción no es negrita */
            cursor: pointer;
            padding: 2px 0; /* Pequeño padding para cada item */
        }
        
        /* Estilos para el input checkbox dentro del item */
        .checkbox-group .checkbox-item input[type="checkbox"] {
            margin-right: 8px; /* Espacio entre checkbox y texto */
            flex-shrink: 0; /* Evita que el checkbox se encoja */
            margin-top: 4px; /* Ajusta la posición vertical del checkbox si es necesario */
        }
        
        /* Estilos para el texto de la etiqueta dentro del item */
        .checkbox-group .checkbox-item span {
            flex-grow: 1; /* Permite que el texto ocupe el espacio restante */
            word-wrap: break-word; /* Permite que el texto largo se envuelva */
            white-space: normal; /* Asegura que el texto no se fuerce a una sola línea */
            line-height: 1.2; /* Ajusta la altura de línea para mejor legibilidad */
        }

        /* --- NUEVOS ESTILOS PARA LA MODAL --- */
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
            max-width: 700px;
            max-height: 80vh; /* Altura máxima para la modal */
            overflow-y: auto; /* Scroll dentro de la modal si el contenido es largo */
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

        /* Estilos para la lista de productos seleccionados en la vista principal */
        #selectedProductsContainer { /* Nuevo contenedor para la etiqueta y el botón/display */
            margin-top: 10px;
        }

        #selectedProductsDisplay {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            min-height: 40px; /* Para que siempre tenga un tamaño visible */
            background-color: #f9f9f9;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .selected-product-tag {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .selected-product-tag .remove-tag {
            cursor: pointer;
            font-weight: bold;
            color: #1b5e20;
        }

        /* Estilos para deshabilitar visualmente */
        .disabled-section {
            opacity: 0.6;
            pointer-events: none; /* Deshabilita clics en los elementos dentro */
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include '../public/layout/barraNavAsesor.php'; ?>

    <form id="registroForm" action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=agregarAsesor" method="POST" class="formulario-banco">
        <h2>Registro De Asesor</h2>

        <div class="fila">
            <div class="columna">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan">

                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" name="apellido" required placeholder="Ej. Martínez">

                <label for="genero">Género *</label>
                <select id="genero" name="genero" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="1">Femenino</option>
                    <option value="2">Masculino</option>
                    <option value="3">Otro</option>
                </select>

                <label for="tipo_documento">Tipo de Documento *</label>
                <select id="tipo_documento" name="tipo_documento" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="1">Cédula de Ciudadanía</option>
                    <option value="2">Tarjeta de Identidad</option>
                    <option value="3">Cédula de Extranjería</option>
                </select>

                <?php 
                if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1){
                    echo '<label for="idRol">Rol *</label>
                          <select id="idRol" name="idRol" required onchange="toggleProductAssociation()">
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="1">Gerente</option>
                            <option value="2">Sub Gerente</option>
                            <option value="3">Asesor</option>
                            <option value="4">Cajero</option>
                          </select>';
                }
                ?>
            </div>

            <div class="columna">
                <label for="documento">Número de Documento *</label>
                <input type="text" id="documento" name="documento" required pattern="\d+" placeholder="Ej. 1234567890">

                <label for="celular">Celular *</label>
                <input type="tel" id="celular" name="celular" required pattern="[0-9]{10}" maxlength="10" placeholder="Ej. 3001234567">

                <label for="correo">Correo Electrónico *</label>
                <input type="email" id="correo" name="correo" required placeholder="Ej. asesor@correo.com"
                    onfocus="this.placeholder=''" onblur="this.placeholder='Ej. asesor@correo.com'">

                <label for="contrasena">Contraseña *</label>
                <div class="password-wrapper">
                    <input type="password" id="contrasena" name="contrasena" required placeholder="Crea una contraseña segura">
                    <span class="toggle-password" onclick="togglePassword()">👁</span>
                </div>
            </div>
        </div>

        <!-- Sección para Asociar Productos con Modal -->
        <div id="selectedProductsContainer">
            <label>Productos Asociados *</label>
            <button type="button" id="associateProductsBtn" onclick="openProductModal()" class="mt-2 mb-2" disabled>Asociar Productos</button>
            <div id="selectedProductsDisplay">
                <!-- Aquí se mostrarán los productos seleccionados -->
                Ningún producto seleccionado.
            </div>
            <!-- Campo oculto para enviar los IDs de productos al servidor -->
          <input type="hidden" name="productos_asociados" id="hiddenProductsInput" value="">
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <!-- Modal para Selección de Productos -->
    <div id="productModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Seleccionar Productos</h3>
                <button type="button" class="modal-close-btn" onclick="closeProductModal()">&times;</button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th></th> <!-- Columna para el checkbox -->
                            <th>ID</th>
                            <th>Nombre del Producto</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <!-- Los productos se cargarán aquí con JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="confirmProductSelection()">Agregar Productos</button>
                <button type="button" onclick="closeProductModal()">Cerrar</button>
            </div>
        </div>
    </div>


    <script>
        // Hacer que la lista de productos de PHP esté disponible en JavaScript
        const allProducts = <?= json_encode($productos) ?>;
        // Array para almacenar los IDs de los productos seleccionados
        let selectedProductIds = [];

        // Referencias a elementos clave
        const idRolSelect = document.getElementById('idRol');
        const associateProductsBtn = document.getElementById('associateProductsBtn');
        const selectedProductsContainer = document.getElementById('selectedProductsContainer'); // Contenedor de la sección de productos

        // Función para alternar la visibilidad y habilitación de la sección de productos
        function toggleProductAssociation() {
            // Asumiendo que el ID del rol 'Asesor' es 3
            const IS_ASESOR_ROLE_ID = '3'; 
            
            if (idRolSelect) { // Asegurarse de que el elemento exista
                const selectedRoleId = idRolSelect.value;
                const isAsesor = (selectedRoleId === IS_ASESOR_ROLE_ID);

                if (isAsesor) {
                    associateProductsBtn.disabled = false;
                    selectedProductsContainer.classList.remove('disabled-section');
                } else {
                    associateProductsBtn.disabled = true;
                    selectedProductsContainer.classList.add('disabled-section');
                    // Limpiar selecciones si el rol no es asesor
                    selectedProductIds = [];
                    renderSelectedProducts();
                }
            } else {
                // Si idRolSelect no existe (ej. si el rol no es 1), deshabilitar por defecto
                associateProductsBtn.disabled = true;
                selectedProductsContainer.classList.add('disabled-section');
                selectedProductIds = [];
                renderSelectedProducts();
            }
        }

        function togglePassword() {
            const passwordField = document.getElementById("contrasena");
            const toggleBtn = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleBtn.textContent = "🙈";
            } else {
                passwordField.type = "password";
                toggleBtn.textContent = "👁";
            }
        }

        function openProductModal() {
            const modal = document.getElementById('productModal');
            const tableBody = document.getElementById('productsTableBody');
            tableBody.innerHTML = ''; // Limpiar la tabla antes de rellenarla

            // Rellenar la tabla con todos los productos
            for (const id in allProducts) {
                const name = allProducts[id];
                const row = tableBody.insertRow();
                
                const cellCheckbox = row.insertCell();
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = id;
                checkbox.id = 'product-' + id; // Dar un ID único al checkbox
                // Pre-seleccionar si ya estaba en selectedProductIds
                if (selectedProductIds.includes(parseInt(id))) {
                    checkbox.checked = true;
                }
                cellCheckbox.appendChild(checkbox);

                const cellId = row.insertCell();
                cellId.textContent = id;

                const cellName = row.insertCell();
                cellName.textContent = name;
            }

            modal.classList.add('show'); // Mostrar la modal
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('show'); // Ocultar la modal
        }

        function confirmProductSelection() {
            selectedProductIds = []; // Limpiar selecciones anteriores

            const checkboxes = document.querySelectorAll('#productsTableBody input[type="checkbox"]:checked');
            checkboxes.forEach(checkbox => {
                selectedProductIds.push(parseInt(checkbox.value));
            });

            renderSelectedProducts(); // Actualizar la visualización en la ventana principal
            closeProductModal(); // Cerrar la modal
        }

        function removeProductTag(idToRemove) {
            selectedProductIds = selectedProductIds.filter(id => id !== idToRemove);
            renderSelectedProducts();
        }

        function renderSelectedProducts() {
            const displayDiv = document.getElementById('selectedProductsDisplay');
            const hiddenInput = document.getElementById('hiddenProductsInput');
            displayDiv.innerHTML = ''; // Limpiar la visualización actual

            if (selectedProductIds.length === 0) {
                displayDiv.textContent = 'Ningún producto seleccionado.';
                // Si no hay productos seleccionados, el campo oculto debe estar vacío
                hiddenInput.value = ''; 
            } else {
                selectedProductIds.forEach(id => {
                    const productName = allProducts[id];
                    if (productName) {
                        const tag = document.createElement('span');
                        tag.className = 'selected-product-tag';
                        tag.innerHTML = `
                            ${productName} 
                            <span class="remove-tag" data-id="${id}">&times;</span>
                        `;
                        displayDiv.appendChild(tag);
                    }
                });
                // Añadir event listeners para los botones de eliminar tag
                displayDiv.querySelectorAll('.remove-tag').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const id = parseInt(event.target.dataset.id);
                        removeProductTag(id);
                    });
                });

                // Actualizar el campo oculto con los IDs seleccionados (como un string JSON)
                hiddenInput.value = JSON.stringify(selectedProductIds);
            }
        }

        // Validación del formulario principal al enviar
        document.getElementById("registroForm").addEventListener("submit", function(event) {
            // Solo validar si la sección de productos está habilitada (es decir, si el rol es Asesor)
            if (!associateProductsBtn.disabled && selectedProductIds.length === 0) {
                alert("Debe seleccionar al menos un producto asociado para el rol de Asesor.");
                event.preventDefault(); // Evita que el formulario se envíe
            }
            // Si todo está bien, el hiddenInput.value ya contendrá los IDs en formato JSON
        });

        // Inicializar la visualización de productos seleccionados y el estado del botón al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            renderSelectedProducts();
            toggleProductAssociation(); // Llamar al inicio para establecer el estado inicial
        });
    </script>


    <?php include '../public/layout/frontendBackend.php'; ?>
    <?php include '../public/layout/layoutfooter.php'; ?>

    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

</body>
</html>
