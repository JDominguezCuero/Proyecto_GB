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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banco XYZ - Tu Compañero Financiero</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/productosyservicios.css">
</head>
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
      <form id="turnoForm" action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=registrarTurno" method="POST">
        <div class="form-group">
          <label for="nombre">Nombre Completo:</label>
          <input type="text" id="nombre" name="nombre_completo_solicitante" required placeholder="Ej: Juan Pérez">
          <p class="error-message" id="error-nombre">Por favor, ingresa tu nombre completo.</p>
        </div>
        <div class="form-group">
          <label for="cedula">Número de Cédula:</label>
          <input type="number" id="cedula" name="n_documento_solicitante" required placeholder="Ej: 123456789">
          <p class="error-message" id="error-cedula">Por favor, ingresa tu número de cédula.</p>
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

    optionGenerarTurno.addEventListener('click', () => showOverlay(turnoOverlay));
    closeTurnoButton.addEventListener('click', () => {
      hideOverlay(turnoOverlay);
      turnoForm.reset();
      turnoDisplay.style.display = 'none';
      errorNombre.style.display = 'none';
      errorCedula.style.display = 'none';
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
        contadorTurnos++;
        const numeroTurnoGenerado = `T${contadorTurnos.toString().padStart(3, '0')}`;
        const fechaHoraActual = new Date().toISOString().slice(0, 19).replace('T', ' ');

        document.getElementById('n_turno_hidden').value = numeroTurnoGenerado;
        document.getElementById('fecha_solicitud_hidden').value = fechaHoraActual;
              
      }else {
            event.preventDefault();
        }
    });

    window.addEventListener('message', function(event) {
      if (event.data && event.data.type === 'openTurnoGenerator') {
        hideOverlay(chatbotOverlay);
        showOverlay(turnoOverlay);
      } else if (event.data && event.data.type === 'closeChatbot') {
        hideOverlay(chatbotOverlay);
      }
    });

    turnoOverlay.addEventListener('click', (event) => {
      if (event.target === turnoOverlay) {
        hideOverlay(turnoOverlay);
        turnoForm.reset();
        turnoDisplay.style.display = 'none';
        errorNombre.style.display = 'none';
        errorCedula.style.display = 'none';
      }
    });

    chatbotOverlay.addEventListener('click', (event) => {
      if (event.target === chatbotOverlay) {
        hideOverlay(chatbotOverlay);
      }
    });
  </script>

  
   <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>

</body>
</html>
