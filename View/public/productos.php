<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ban</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="assets/productosyservicios.css" rel="stylesheet">
  <link href="assets/inicio.css" rel="stylesheet">
</head>
<body>
  <!-- Header -->
   <?php include 'layout/layoutNav.php'; ?>
  
  <main class="options-container">
    <a href="creditos-consumo.html" class="option-card"><span class="icon">🏦</span><h2>Créditos de Consumo y Libre Inversión</h2></a>
    <a href="creditos-nomina.html" class="option-card"><span class="icon">💼</span><h2>Créditos con Descuento por Nómina</h2></a>
    <a href="creditos-vivienda.html" class="option-card"><span class="icon">🏡</span><h2>Créditos de Vivienda</h2></a>
    <a href="creditos-comerciales.html" class="option-card"><span class="icon">🚀</span><h2>Créditos Comerciales</h2></a>
    <a href="inversiones.html" class="option-card"><span class="icon">📈</span><h2>Inversiones y Productos Financieros</h2></a>
    <a href="ahorro.html" class="option-card"><span class="icon">💰</span><h2>Ahorro y Cuentas Bancarias</h2></a>
    <a href="seguros.html" class="option-card"><span class="icon">🔒</span><h2>Seguros</h2></a>
    <a href="servicios-complementarios.html" class="option-card"><span class="icon">🛠️</span><h2>Servicios Complementarios</h2></a>
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
      <form id="turnoForm">
        <div class="form-group">
          <label for="nombre">Nombre Completo:</label>
          <input type="text" id="nombre" name="nombre" required placeholder="Ej: Juan Pérez">
          <p class="error-message" id="error-nombre">Por favor, ingresa tu nombre completo.</p>
        </div>
        <div class="form-group">
          <label for="cedula">Número de Cédula:</label>
          <input type="number" id="cedula" name="cedula" required placeholder="Ej: 123456789">
          <p class="error-message" id="error-cedula">Por favor, ingresa tu número de cédula.</p>
        </div>
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
      <iframe id="chatbotIframe" class="chatbot-iframe" src="chatbot.php" frameborder="0" title="Chatbot de Asistencia"></iframe>
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
      event.preventDefault();

      errorNombre.style.display = 'none';
      errorCedula.style.display = 'none';

      let valid = true;
      valid = validateInput(nombreInput, errorNombre, 'Por favor, ingresa tu nombre completo.') && valid;
      valid = validateInput(cedulaInput, errorCedula, 'Por favor, ingresa tu número de cédula.') && valid;

      if (valid) {
        contadorTurnos++;
        const horaActual = new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        numeroTurnoSpan.textContent = `T${contadorTurnos.toString().padStart(3, '0')}`;
        nombreClienteSpan.textContent = nombreInput.value;
        cedulaClienteSpan.textContent = cedulaInput.value;
        horaGeneracionSpan.textContent = horaActual;

        turnoDisplay.style.display = 'block';
        turnoForm.style.display = 'none';

        setTimeout(() => {
          hideOverlay(turnoOverlay);
          turnoForm.style.display = 'block';
        }, 5000);
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

  
     <?php include 'layout/frontendBackend.php'; ?>
    <?php include 'layout/layoutfooter.php'; ?>

</body>
</html>
