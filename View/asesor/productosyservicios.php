<?php
  session_start();
  require_once(__DIR__ . '../../../Config/config.php');
  
  if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('❌ Error al registrar', '$mensaje', 'error');
      });
      </script>";

  }else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Proceso Exitoso.';

    // Captura las variables si existen en la URL
    $nDocumento_js = isset($_GET['n_documento']) ? htmlspecialchars($_GET['n_documento']) : '';
    $numeroTurno_js = isset($_GET['n_turno']) ? htmlspecialchars($_GET['n_turno']) : '';
    $nombreCompleto_js = isset($_GET['nombre_completo']) ? htmlspecialchars($_GET['nombre_completo']) : '';
    $fecha_Solicitud_js = isset($_GET['fecha_solicitud']) ? htmlspecialchars($_GET['fecha_solicitud']) : '';
    $productoInteres_js = isset($_GET['producto_interes']) ? htmlspecialchars($_GET['producto_interes']) : 'No especificado';

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica si los datos del turno están disponibles (solo procedemos si sí)
            const nDocumento = '$nDocumento_js';
            const numeroTurno = '$numeroTurno_js';
            const nombreCompleto = '$nombreCompleto_js';
            const fechaSolicitud = '$fecha_Solicitud_js';
            const productoInteres = '$productoInteres_js';

            if (numeroTurno && nombreCompleto && nDocumento && fechaSolicitud) {
                const turnoOverlay = document.getElementById('turnoOverlay');
                const turnoForm = document.getElementById('turnoForm');
                const turnoDisplay = document.getElementById('turno-display');
                const modalContainer = turnoOverlay ? turnoOverlay.querySelector('.modal-container') : null;

                if (turnoOverlay && turnoForm && turnoDisplay && modalContainer) {
                    // Asegúrate de que el modal de turno esté abierto
                    turnoOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';

                    // Oculta el formulario y el display del turno al inicio de la carga
                    turnoForm.style.display = 'none';
                    turnoDisplay.style.display = 'none'; 

                    // Crea y muestra el mensaje de carga
                    let loadingDiv = document.createElement('div');
                    loadingDiv.id = 'loading-turn-generator';
                    loadingDiv.style.textAlign = 'center';
                    loadingDiv.style.padding = '20px';
                    loadingDiv.innerHTML = '<div class=\"spinner\"></div><p style=\"margin-top:15px; font-size:1.1em; color:#333;\">Generando su turno, por favor espere...</p>';
                    modalContainer.appendChild(loadingDiv);

                    // Asegúrate de cerrar el chatbot si estuviera abierto para evitar conflictos
                    const chatbotOverlay = document.getElementById('chatbotOverlay');
                    if (chatbotOverlay && chatbotOverlay.classList.contains('active')) {
                        chatbotOverlay.classList.remove('active');
                        // No resetear overflow aquí, ya que turnoOverlay estará activo
                    }

                    // Establece un temporizador para simular la carga y luego mostrar el turno
                    setTimeout(() => {
                        // Oculta el mensaje de carga
                        if (loadingDiv) {
                            loadingDiv.remove();
                        }

                        // Llenar los datos del turno en el turno-display
                        document.getElementById('numeroTurno').textContent = numeroTurno;
                        document.getElementById('nombreCliente').textContent = nombreCompleto;
                        document.getElementById('cedulaCliente').textContent = nDocumento;
                        document.getElementById('horaGeneracion').textContent = fechaSolicitud;
                        
                        const productoInteresClienteSpan = document.getElementById('productoInteresCliente');
                        if(productoInteresClienteSpan) { // Verifica si el elemento existe
                            productoInteresClienteSpan.textContent = productoInteres;
                        }
                        
                        // Muestra el div con la información del turno
                        turnoDisplay.style.display = 'block';

                    }, 1500); // Simula 1.5 segundos de carga
                } else {
                    // Fallback: Si no se encuentran los elementos del modal de turno
                    // Esto indica un problema en el HTML, pero al menos da una pista.
                    showModal('❌ Error de Configuración', 'No se pudieron encontrar los elementos del modal de turno. Contacta a soporte.', 'error');
                }
            } else {
                // Si la página cargó con 'success' pero sin datos de turno (esto no debería pasar si el controlador está bien)
                showModal('⚠️ Proceso Incompleto', 'Operación exitosa, pero faltan datos del turno. Intenta de nuevo.', 'warning');
            }
        });
        </script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banco finan-cias</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/productosyservicios.css">
</head>
<style>
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #3B82F6; /* Un color de tu elección, como Tailwind blue-500 */
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto; /* Centra el spinner */
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Oculta el turno-display y muestra el formulario por defecto */
  #turno-display {
      display: none; /* Asegura que esté oculto por defecto */
  }
  #turnoForm {
      display: block; /* Asegura que el formulario esté visible por defecto si abres el modal sin recargar */
  }

  /* Asegura que al abrir el modal para GENERAR, se oculte cualquier display anterior */
  /* Puedes añadir esta regla si el modal se abre siempre con el formulario visible */
  .turno-container #loading-turn-generator {
      display: none; /* Asegura que la carga no se vea si no se activó por success */
  }

  .form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db; /* border-gray-300 de Tailwind */
    border-radius: 0.375rem; /* rounded-md de Tailwind */
    background-color: #ffffff; /* bg-white */
    font-size: 1rem; /* text-base */
    line-height: 1.5;
    color: #374151; /* text-gray-700 */
    appearance: none; /* Elimina estilos por defecto del navegador */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%207L10%2010L13%207%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); /* Flecha personalizada */
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.5em 1.5em; /* Ajusta el tamaño de la flecha */
    cursor: pointer;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-group select:focus {
    border-color: #6366f1; /* indigo-500 de Tailwind */
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); /* ring-indigo-200 de Tailwind */
}

/* Estilos para el contenedor del grupo de formulario (si no lo tienes ya) */
.form-group {
    margin-bottom: 1.25rem; /* mb-5 de Tailwind */
}

/* Estilos para las etiquetas (labels) */
.form-group label {
    display: block;
    margin-bottom: 0.5rem; /* mb-2 de Tailwind */
    font-weight: 500; /* font-medium de Tailwind */
    color: #1f2937; /* text-gray-900 */
}

/* Estilo para los mensajes de error (si ya no los tienes definidos) */
.error-message {
    color: #ef4444; /* red-500 de Tailwind */
    font-size: 0.875rem; /* text-sm de Tailwind */
    margin-top: 0.25rem; /* mt-1 de Tailwind */
    display: none; /* Se oculta por defecto y se muestra con JS */
}

/* Ajuste específico para el select, similar a tu estilo en el código PHP */
#filtroProducto { /* Usando el ID que ya tenías definido, aunque el `select` genérico también aplica */
    max-height: 160px; /* Limita la altura si la lista es muy larga */
    overflow-y: auto; /* Agrega scroll si excede la altura máxima */
}
  </style>
<body>
  <!-- Header -->
   <?php include '../public/layout/barraNavAsesor.php'; ?>
  
  <main class="options-container">
    <a href="<?= BASE_URL ?>/View/Productos_servicios/creditos_consumo_libre_inversion/creditos_consumo_libre_inversion.php" class="option-card"><span class="icon">🏦</span><h2>Créditos De Consumo Y Libre Inversión</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Creditos_descuento_nomina/Creditos_descuento_nomina.php" class="option-card"><span class="icon">💼</span><h2>Créditos Con Descuento Por Nómina</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Creditos_Hipotecarios_y_de_Vivienda/Créditos_Hipotecarios_y_de_Vivienda.php" class="option-card"><span class="icon">🏡</span><h2>Créditos Hipotecarios Y De Vivienda</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Créditos_Vehiculares/Creditos_vehiculares.php" class="option-card"><span class="icon">🚗 </span><h2>Creditos Vehiculares</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Compra_Cartera/Compra_cartera.php" class="option-card"><span class="icon">💳</span><h2>Compra De Cartera</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Cuentas_Ahorros/Cuenta_de_Ahorros.php" class="option-card"><span class="icon">💰</span><h2>Cuentas De Ahorro</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Créditos_educativos/Créditos_educativos.php" class="option-card"><span class="icon">🎓 </span><h2>Creditos Educativos</h2></a>
    <a href="<?= BASE_URL ?>/View/Productos_servicios/Creditos_Comerciales_o_Empresariales/Creditos_Comerciales_o_Empresariales.php" class="option-card"><span class="icon">🏢</span><h2>Creditos Comerciales O Empresariales</h2></a>
  </main>

  <!-- FAB flotante con opciones -->
  <div class="fab-container" id="fabContainer">
    <button class="fab-button" id="fabButton">≡</button>
    <ul class="fab-options">
      <li id="optionChatbot">Asistente Virtual</li>
      <li id="optionGenerarTurno">Generar Turno</li>
    </ul>
  </div>

  <!-- Modal Turno -->
  <div class="modal-overlay" id="turnoOverlay">
    <div class="modal-container turno-container">
      <button class="modal-close-button" id="closeTurno">&times;</button>

      <h1>Generador de Turnos</h1>

      <form id="turnoForm" action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=registrarTurno&accionesPublicas=crearTurnoPublico" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo: *</label>
            <input type="text" id="nombre" name="nombre_completo_solicitante" required placeholder="Ej: Juan Pérez">
            <p class="error-message" id="error-nombre">Por favor, ingresa tu nombre completo.</p>
        </div>

        <div class="form-group">
            <label for="cedula">Número de Cédula: *</label>
            <input type="number" id="cedula" name="n_documento_solicitante" required placeholder="Ej: 123456789">
            <p class="error-message" id="error-cedula">Por favor, ingresa tu número de cédula.</p>
        </div>

        <div class="form-group">
            <label for="producto_interes">Producto de Interés:</label>
            <select id="producto_interes" name="producto_interes">
                <option value="">Ninguno - Pedir asesoría.</option>
                <option value="1">Compra de cartera de crédito hipotecario</option>
                <option value="2">Compra de cartera de vehículo</option>
                <option value="3">Compra de cartera de microcrédito</option>
                <option value="4">Compra de cartera de crédito de educación</option>
                <option value="5">Compra de cartera de libranza</option>
                <option value="6">Compra de cartera de crédito agropecuario</option>
                <option value="7">Compra de cartera de crédito comercial (grandes empresas)</option>
                <option value="8">Compra de cartera de crédito de libre inversión</option>
                <option value="9">Compra de cartera de crédito de consumo</option>
                <option value="10">Compra de cartera de tarjetas de crédito</option>
                <option value="11">Compra de cartera de crédito rotativo</option>
                <option value="12">Compra de cartera (para personas naturales)</option>
                <option value="13">Crédito de Vehículo</option>
                <option value="14">Compra de Cartera de Vehículo</option>
                <option value="15">Crédito educativo a corto plazo</option>
                <option value="16">Crédito libre educativo</option>
                <option value="17">Crediestudiantil</option>
                <option value="19">Microcrédito</option>
                <option value="20">Crédito Comercial</option>
                <option value="21">Crédito Agrofácil</option>
                <option value="22">Crédito Finagro</option>
                <option value="23">CDT</option>
                <option value="24">Crédito Hipotecario</option>
                <option value="27">Compra de Cartera de Vivienda</option>
                <option value="28">Crédito de descuento por nómina o mesada pencional</option>
                <option value="30">Cuentas de Ahorro</option>
                <option value="31">Cuenta de Ahorro Básica o Estándar</option>
                <option value="32">Cuenta de Ahorro Programado</option>
                <option value="33">Cuenta de Ahorro para Jóvenes o Niños</option>
                <option value="34">Cuenta de Ahorro de Nómina</option>
                <option value="35">Cuenta de Ahorro para Pensionados</option>
                <option value="36">Cuenta de Ahorro en Moneda Extranjera</option>
                <option value="37">Crédito Rotativo</option>
                <option value="38">Tarjeta de crédito</option>
                <option value="39">Crédito de Consumo</option>
                <option value="40">Crédito Libre Inversión</option>
            </select>
        </div>

        <input type="hidden" id="n_turno_hidden" name="n_Turno">
        <input type="hidden" id="fecha_solicitud_hidden" name="fechaSolicitud">

        <button type="submit">Generar Turno</button>
      </form>

      <div id="turno-display">
        <p>Su turno ha sido generado:</p>
        <p><strong>Turno No.:</strong> <span id="numeroTurno"></span></p>
        <p><strong>Nombre:</strong> <span id="nombreCliente"></span></p>
        <p><strong>Cédula:</strong> <span id="cedulaCliente"></span></p>
        <p><strong>Hora de Generación:</strong> <span id="horaGeneracion"></span></p>
        <p>¡Gracias por su espera!</p>
      </div>
    </div>
  </div>

  <!-- Modal Chatbot -->
  <div class="modal-overlay" id="chatbotOverlay">
    <div class="modal-container chatbot-container">
      <div class="chatbot-header">
        Nuestro Asistente Virtual
        <button class="modal-close-button" id="closeChatbot">&times;</button>
      </div>
      <iframe id="chatbotIframe" class="chatbot-iframe" src="../public/chatbot.php" frameborder="0" title="Chatbot de Asistencia"></iframe>
    </div>
  </div>

  <!-- JavaScript -->
 <script>
    const fabButton = document.getElementById('fabButton');
    const fabContainer = document.getElementById('fabContainer');
    const optionChatbot = document.getElementById('optionChatbot');
    const optionGenerarTurno = document.getElementById('optionGenerarTurno');

    const turnoOverlay = document.getElementById('turnoOverlay');
    const closeTurnoButton = document.getElementById('closeTurno');
    const turnoForm = document.getElementById('turnoForm');
    const nombreInput = document.getElementById('nombre');
    const cedulaInput = document.getElementById('cedula');
    const errorNombre = document.getElementById('error-nombre');
    const errorCedula = document.getElementById('error-cedula');
    const turnoDisplay = document.getElementById('turno-display');
    const numeroTurnoSpan = document.getElementById('numeroTurno');
    const nombreClienteSpan = document.getElementById('nombreCliente');
    const cedulaClienteSpan = document.getElementById('cedulaCliente');
    const horaGeneracionSpan = document.getElementById('horaGeneracion');
    // Asegúrate de que esta variable existe si tienes un span para el producto de interés
    const productoInteresClienteSpan = document.getElementById('productoInteresCliente'); 

    const chatbotOverlay = document.getElementById('chatbotOverlay');
    const closeChatbotButton = document.getElementById('closeChatbot');

    let contadorTurnos = 0;

    function showOverlay(overlayElement) {
        overlayElement.classList.add('active');
        document.body.style.overflow = 'hidden';
        fabContainer.classList.remove('active');
    }

    function hideOverlay(overlayElement) {
        overlayElement.classList.remove('active');
        if (!chatbotOverlay.classList.contains('active') && !turnoOverlay.classList.contains('active')) {
            document.body.style.overflow = '';
        }
    }

    function validateInput(inputElement, errorElement, errorMessage) {
        if (inputElement.value.trim() === '') {
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.style.display = 'none';
            return true;
        }
    }

    fabButton.addEventListener('click', () => {
        fabContainer.classList.toggle('active');
    });

    document.addEventListener('click', (event) => {
        if (!fabContainer.contains(event.target) && fabContainer.classList.contains('active')) {
            fabContainer.classList.remove('active');
        }
    });

    // --- MODIFICACIÓN CLAVE AQUÍ: Lógica al hacer clic en 'Generar Turno' ---
    optionGenerarTurno.addEventListener('click', () => {
        // Restablece el estado del modal de turno a su estado inicial de formulario
        if (turnoForm) turnoForm.style.display = 'block';
        if (turnoDisplay) turnoDisplay.style.display = 'none';
        if (turnoForm) turnoForm.reset();
        if (errorNombre) errorNombre.style.display = 'none';
        if (errorCedula) errorCedula.style.display = 'none';

        // Elimina cualquier spinner de carga o mensaje anterior si existe
        const existingLoadingDiv = document.getElementById('loading-turn-generator');
        if (existingLoadingDiv) {
            existingLoadingDiv.remove();
        }

        showOverlay(turnoOverlay);
    });

    // --- MODIFICACIÓN CLAVE AQUÍ: Lógica al cerrar el modal de turno ---
    closeTurnoButton.addEventListener('click', () => {
        hideOverlay(turnoOverlay);
        // Asegúrate de resetear el estado al cerrar
        if (turnoForm) turnoForm.reset();
        if (turnoDisplay) turnoDisplay.style.display = 'none';
        if (errorNombre) errorNombre.style.display = 'none';
        if (errorCedula) errorCedula.style.display = 'none';

        // Elimina cualquier spinner de carga al cerrar
        const existingLoadingDiv = document.getElementById('loading-turn-generator');
        if (existingLoadingDiv) {
            existingLoadingDiv.remove();
        }
    });

    optionChatbot.addEventListener('click', () => showOverlay(chatbotOverlay));
    closeChatbotButton.addEventListener('click', () => hideOverlay(chatbotOverlay));

    turnoForm.addEventListener('submit', function(event) {
        errorNombre.style.display = 'none';
        errorCedula.style.display = 'none';

        let valid = true;
        valid = validateInput(nombreInput, errorNombre, 'Por favor, ingresa tu nombre completo.') && valid;
        valid = validateInput(cedulaInput, errorCedula, 'Por favor, ingresa tu número de cédula.') && valid;

        if (valid) {
            // Permite que el formulario se envíe si es válido.
            // La lógica de simulación de carga y muestra del turno se manejará
            // cuando la página se recargue con los parámetros `success` del controlador.
            
            // Si tu contadorTurnos y fechaHoraActual son solo para el envío al PHP,
            // y el PHP es quien realmente genera el turno y los devuelve, entonces esta parte está bien.
            // Si quisieras una *simulación instantánea* sin recargar, necesitarías AJAX.
            // Para la solución actual (recargando con parámetros GET), no se necesita aquí.

        } else {
            event.preventDefault(); // Evita el envío si la validación falla
        }
    });

    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'openTurnoGenerator') {
            hideOverlay(chatbotOverlay);
            // Al abrir desde el chatbot, también reseteamos el estado del modal de turno
            if (turnoForm) turnoForm.style.display = 'block';
            if (turnoDisplay) turnoDisplay.style.display = 'none';
            if (turnoForm) turnoForm.reset();
            if (errorNombre) errorNombre.style.display = 'none';
            if (errorCedula) errorCedula.style.display = 'none';
            const existingLoadingDiv = document.getElementById('loading-turn-generator');
            if (existingLoadingDiv) {
                existingLoadingDiv.remove();
            }
            showOverlay(turnoOverlay);
        } else if (event.data && event.data.type === 'closeChatbot') {
            hideOverlay(chatbotOverlay);
        }
    });

    turnoOverlay.addEventListener('click', (event) => {
        if (event.target === turnoOverlay) {
            hideOverlay(turnoOverlay);
            // Asegúrate de resetear el estado al hacer clic fuera del modal
            if (turnoForm) turnoForm.reset();
            if (turnoDisplay) turnoDisplay.style.display = 'none';
            if (errorNombre) errorNombre.style.display = 'none';
            if (errorCedula) errorCedula.style.display = 'none';
            const existingLoadingDiv = document.getElementById('loading-turn-generator');
            if (existingLoadingDiv) {
                existingLoadingDiv.remove();
            }
        }
    });

    chatbotOverlay.addEventListener('click', (event) => {
        if (event.target === chatbotOverlay) {
            hideOverlay(chatbotOverlay);
        }
    });

    // --- LÓGICA PHP INYECTADA AL CARGAR LA PÁGINA CON ÉXITO ---
    // Este bloque de script PHP DEBE ir en tu archivo .php,
    // NO directamente dentro de este bloque de script HTML/JS.
    // Solo lo muestro aquí para referencia.

    <?php if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])): ?>
        <?php
        // Captura las variables PHP que vienen de la URL
        $nDocumento_js = isset($_GET['n_documento']) ? htmlspecialchars($_GET['n_documento']) : '';
        $numeroTurno_js = isset($_GET['n_turno']) ? htmlspecialchars($_GET['n_turno']) : '';
        $nombreCompleto_js = isset($_GET['nombre_completo']) ? htmlspecialchars($_GET['nombre_completo']) : '';
        $fecha_Solicitud_js = isset($_GET['fecha_solicitud']) ? htmlspecialchars($_GET['fecha_solicitud']) : '';
        $productoInteres_js = isset($_GET['producto_interes']) ? htmlspecialchars($_GET['producto_interes']) : 'No especificado';
        $mensaje_exito = htmlspecialchars($_GET['msg']) ?? 'Proceso Exitoso.';
        ?>

        document.addEventListener('DOMContentLoaded', function() {
            const turnoOverlay = document.getElementById('turnoOverlay');
            const turnoForm = document.getElementById('turnoForm');
            const turnoDisplay = document.getElementById('turno-display');
            const modalContainer = turnoOverlay ? turnoOverlay.querySelector('.modal-container') : null;

            if (turnoOverlay && turnoForm && turnoDisplay && modalContainer) {
                // Asegúrate de que el modal de turno esté abierto y el formulario oculto
                turnoOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                turnoForm.style.display = 'none';
                turnoDisplay.style.display = 'none'; // Aseguramos que el display de turno esté oculto al inicio de la carga

                // Crea y muestra el mensaje de carga
                let loadingDiv = document.createElement('div');
                loadingDiv.id = 'loading-turn-generator';
                loadingDiv.style.textAlign = 'center';
                loadingDiv.style.padding = '20px';
                loadingDiv.innerHTML = '<div class=\"spinner\"></div><p style=\"margin-top:15px; font-size:1.1em; color:#333;\">Generando su turno, por favor espere...</p>';
                modalContainer.appendChild(loadingDiv);

                // Cierra el chatbot si está abierto para evitar conflictos
                const chatbotOverlay = document.getElementById('chatbotOverlay');
                if (chatbotOverlay && chatbotOverlay.classList.contains('active')) {
                    chatbotOverlay.classList.remove('active');
                }

                // Simula la carga y luego muestra los detalles del turno
                setTimeout(() => {
                    // Elimina el mensaje de carga
                    if (loadingDiv) {
                        loadingDiv.remove();
                    }

                    // Rellena los datos del turno en el turno-display
                    document.getElementById('numeroTurno').textContent = '<?= $numeroTurno_js ?>';
                    document.getElementById('nombreCliente').textContent = '<?= $nombreCompleto_js ?>';
                    document.getElementById('cedulaCliente').textContent = '<?= $nDocumento_js ?>';
                    document.getElementById('horaGeneracion').textContent = '<?= $fecha_Solicitud_js ?>';
                    if (productoInteresClienteSpan) { // Verifica si el elemento existe
                        productoInteresClienteSpan.textContent = '<?= $productoInteres_js ?>';
                    }
                    
                    // Muestra el div con la información del turno
                    turnoDisplay.style.display = 'block';

                    // Opcional: Si aún quieres una notificación pequeña, usa showModal.
                    // Pero la info principal ya está en turnoOverlay.

                }, 1500); // 1.5 segundos de simulación de carga
            } else {
                // Fallback si no se encuentran los elementos principales del modal de turno.
                // Muestra el showModal general si no se puede usar el modal de turno.
            }
        });
    <?php endif; ?>
    // --- FIN DE LA LÓGICA PHP INYECTADA ---

</script>

  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>
   <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>

</body>
</html>