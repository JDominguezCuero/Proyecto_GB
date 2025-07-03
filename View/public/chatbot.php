<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot de Asistencia Bancaria</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        /*
        =========================================================
        CSS Variables (Colores y Sombras) - Replicadas para independencia
        =========================================================
        Es buena práctica replicar variables si el iframe necesita ser standalone,
        aunque el padre podría pasar variables CSS con JS si fuera necesario.
        */
        :root {
            --primary-color: #007bff;          /* Azul principal para encabezados, botones importantes */
            --primary-dark-color: #0056b3;     /* Azul más oscuro para hover */
            --secondary-color: #f8f9fa;        /* Light background for chat window */
            --background-color: #e9ecef;       /* Overall body background - menos relevante aquí */
            --text-color: #343a40;             /* Color de texto oscuro general */
            --text-light-color: #6c757d;       /* Color de texto más claro para prompts */
            --border-color: #dee2e6;           /* Color general de bordes */
            --user-message-bg: #dcf8c6;        /* Light green for user messages */
            --user-message-text: #28a745;
            --bot-message-bg: #e2e3e5;         /* Light grey for bot messages */
            --bot-message-text: #343a40;
            --option-button-bg: #f0f2f5;       /* Off-white for options */
            --option-button-hover: #e0e2e5;
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0; /* Sin padding en el body, el padding va en chat-window */
            background-color: var(--secondary-color); /* El color del chat-window */
            display: flex;
            flex-direction: column;
            height: 100vh; /* Ocupa toda la altura del iframe */
            box-sizing: border-box;
            color: var(--text-color);
        }

        /*
        =========================================================
        Chat Window (Área de Mensajes)
        =========================================================
        */
        .chat-window {
            flex-grow: 1; /* Permite que el área de chat crezca y ocupe el espacio */
            padding: 15px; /* Padding interno para los mensajes */
            overflow-y: auto; /* Permite el scroll cuando hay muchos mensajes */
            background-color: var(--secondary-color); /* Fondo del área de mensajes */
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre mensajes */
            scroll-behavior: smooth; /* Desplazamiento suave para nuevos mensajes */
        }

        .message {
            padding: 10px 15px;
            border-radius: 18px; /* Burbujas de chat más redondeadas */
            max-width: 85%;
            line-height: 1.4;
            word-wrap: break-word; /* Asegura que palabras largas se envuelvan */
            animation: fadeInMessage 0.3s ease-out; /* Animación de aparición */
        }
        @keyframes fadeInMessage {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user-message {
            align-self: flex-end; /* Alinea los mensajes del usuario a la derecha */
            background-color: var(--user-message-bg);
            color: var(--user-message-text);
            border-bottom-right-radius: 5px; /* Ligeramente menos redondeado para un efecto "cola" */
        }

        .bot-message {
            align-self: flex-start; /* Alinea los mensajes del bot a la izquierda */
            background-color: var(--bot-message-bg);
            color: var(--bot-message-text);
            border-bottom-left-radius: 5px; /* Ligeramente menos redondeado para un efecto "cola" */
        }

        /*
        =========================================================
        Dynamic Interaction Area (Opciones y Campo de Entrada)
        =========================================================
        */
        .dynamic-interaction-message {
            background-color: #fff; /* Fondo blanco para esta sección inferior */
            padding: 15px;
            border-top: 1px solid var(--border-color);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px; /* Espacio entre el prompt, opciones y input */
            flex-shrink: 0; /* Evita que esta sección se encoja */
        }

        .dynamic-interaction-message p {
            margin: 0;
            color: var(--text-light-color);
            font-size: 0.95em;
            font-weight: 500;
        }

        /* Contenedor para los botones de opción */
        .options-container {
            display: flex;
            flex-wrap: wrap; /* Permite que los botones se envuelvan a la siguiente línea */
            gap: 8px; /* Espacio entre botones */
            justify-content: center;
            width: 100%;
            max-width: 380px; /* Limita el ancho para que no se extiendan demasiado */
            margin: 0 auto;
        }

        .option-button {
            background-color: var(--option-button-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 10px 18px;
            border-radius: 25px; /* Botones en forma de píldora */
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap; /* Evita que el texto del botón se rompa */
            flex-shrink: 0; /* Previene que los botones se encojan */
        }

        .option-button:hover {
            background-color: var(--option-button-hover);
            border-color: var(--primary-color); /* Borde resaltado al pasar el ratón */
            transform: translateY(-2px); /* Efecto de "levantar" sutil */
        }

        .option-button.primary {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
        }

        .option-button.primary:hover {
            background-color: var(--primary-dark-color);
            border-color: var(--primary-dark-color);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Contenedor del campo de entrada de texto */
        .input-field-container {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 400px; /* Ajusta el ancho máximo para el input */
            align-items: center; /* Alinea verticalmente el input y el botón */
            margin-top: 10px; /* Espacio desde las opciones/texto superior */
        }

        .input-field-container input[type="text"] {
            flex-grow: 1; /* Permite que el input crezca y ocupe el espacio */
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 25px; /* Campo de entrada redondeado */
            font-size: 1em;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-field-container input[type="text"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .input-field-container button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px; /* Botón de enviar redondeado */
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: background-color 0.2s ease, transform 0.2s ease;
            flex-shrink: 0; /* Previene que el botón de enviar se encoja */
        }

        .input-field-container button:hover {
            background-color: var(--primary-dark-color);
            transform: translateY(-1px);
        }

        .hidden {
            display: none !important;
        }

        /*
        =========================================================
        Responsive Adjustments (Media Queries)
        =========================================================
        */
        @media (max-width: 500px) {
            .chat-window {
                padding: 10px;
            }
            .message {
                max-width: 90%;
                padding: 8px 12px;
                font-size: 0.95em;
            }
            .dynamic-interaction-message {
                padding: 10px;
            }
            .option-button {
                padding: 8px 15px;
                font-size: 0.85em;
            }
            .input-field-container input[type="text"],
            .input-field-container button {
                padding: 10px 15px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="chat-window" id="chatWindow">
        <div class="bot-message message">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?</div>
    </div>

    <div class="dynamic-interaction-message" id="dynamicInteractionMessage">
        <p id="interactionPrompt">Puedes seleccionar una opción o escribir tu pregunta:</p>
        <div id="interactionArea">
            </div>
    </div>

    <script>
        // =========================================================
        // Lógica del Chatbot (Dentro del Iframe)
        // =========================================================

        // --- Referencias a Elementos del DOM ---
        const chatWindow = document.getElementById('chatWindow');
        const interactionArea = document.getElementById('interactionArea');
        const dynamicInteractionMessage = document.getElementById('dynamicInteractionMessage');
        const interactionPrompt = document.getElementById('interactionPrompt');

        // Variable para controlar el modo de entrada (opciones o texto)
        let currentInputMode = 'options';

        // --- Funciones de Mensajes y Renderizado ---

        /**
         * Añade un mensaje al historial del chat.
         * @param {string} text Contenido del mensaje.
         * @param {'user'|'bot'} sender Quién envía el mensaje ('user' o 'bot').
         * @param {boolean} [isHtml=false] Si el texto contiene HTML.
         */
        function addMessage(text, sender, isHtml = false) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', `${sender}-message`);
            if (isHtml) {
                messageDiv.innerHTML = text;
            } else {
                messageDiv.textContent = text;
            }
            chatWindow.appendChild(messageDiv);
            // Auto-scroll al final del chat
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        /**
         * Renderiza un conjunto de botones de opciones en el área de interacción.
         * @param {Array<Object>} options Array de objetos con { text: string, value: string, isPrimary?: boolean }.
         */
        function renderOptions(options) {
            interactionArea.innerHTML = ''; // Limpia el contenido anterior
            currentInputMode = 'options';
            dynamicInteractionMessage.classList.remove('hidden'); // Asegura que el contenedor sea visible
            interactionPrompt.textContent = "Puedes seleccionar una opción o escribir tu pregunta:"; // Restablece el prompt

            const optionsContainer = document.createElement('div');
            optionsContainer.classList.add('options-container');

            options.forEach(option => {
                const button = document.createElement('button');
                button.classList.add('option-button');
                if (option.isPrimary) {
                    button.classList.add('primary');
                }
                button.textContent = option.text;
                button.dataset.value = option.value || option.text; // Usa data-value o el texto
                optionsContainer.appendChild(button);

                button.addEventListener('click', () => handleOptionClick(button.dataset.value));
            });
            interactionArea.appendChild(optionsContainer);

            // Siempre renderizar el campo de texto debajo de las opciones
            renderTextInput();
        }

        /**
         * Renderiza el campo de entrada de texto con el botón de enviar.
         */
        function renderTextInput() {
            // Elimina cualquier campo de texto existente para evitar duplicados
            const existingTextInput = interactionArea.querySelector('.input-field-container');
            if (existingTextInput) {
                existingTextInput.remove();
            }

            const inputFieldContainer = document.createElement('div');
            inputFieldContainer.classList.add('input-field-container');

            const input = document.createElement('input');
            input.type = 'text';
            input.id = 'chatInput'; // ID para fácil acceso
            input.placeholder = 'Escribe tu mensaje...';
            inputFieldContainer.appendChild(input);

            const sendButton = document.createElement('button');
            sendButton.id = 'sendMessage'; // ID para fácil acceso
            sendButton.textContent = 'Enviar';
            inputFieldContainer.appendChild(sendButton);

            interactionArea.appendChild(inputFieldContainer);

            // Añadir event listeners a los elementos recién creados
            sendButton.addEventListener('click', sendMessage);
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Enfocar el input si estamos en modo de texto
            if (currentInputMode === 'text') {
                input.focus();
            }
        }

        /**
         * Cambia el modo de interacción a solo entrada de texto.
         */
        function switchToTextInputMode() {
            interactionArea.innerHTML = ''; // Limpia todo el contenido anterior
            dynamicInteractionMessage.classList.remove('hidden'); // Asegura la visibilidad
            interactionPrompt.textContent = "Por favor, escribe tu mensaje:"; // Cambia el prompt
            currentInputMode = 'text';
            renderTextInput(); // Solo renderiza el input de texto
            document.getElementById('chatInput').focus(); // Enfoca el input
        }

        // --- Base de Conocimiento del Chatbot ---
        const responses = {
            "initial": [
                { text: "Consultar Saldo", value: "Consultar Saldo" },
                { text: "Créditos e Inversiones", value: "Créditos e Inversiones" },
                { text: "Horarios de Atención", value: "Horarios de Atención" },
                { text: "Sacar Turno", value: "Sacar Turno", isPrimary: true }
            ],
            "Sacar Turno": {
                response: "Entendido. Abriendo el sistema de turnos...",
                action: "openTurnoGenerator" // Acción para comunicar al padre
            },
            "Consultar Saldo": {
                response: "Para consultar tu saldo, por favor, inicia sesión en nuestra banca en línea o visita una sucursal. También puedes llamar a nuestro centro de atención.",
                nextAction: "showInitialOptions"
            },
            "Créditos e Inversiones": {
                response: "Tenemos diversas opciones de créditos personales, hipotecarios e inversiones con atractivas tasas. ¿Te gustaría agendar una asesoría gratuita con uno de nuestros expertos?",
                nextAction: "showInitialOptions"
            },
            "Horarios de Atención": {
                response: "Nuestras sucursales operan de lunes a viernes de 8:00 AM a 4:00 PM. Nuestro servicio de banca en línea está disponible 24/7. Te recordamos que la ubicación actual es Puerto Boyacá, Boyaca, Colombia.",
                nextAction: "showInitialOptions"
            },
            "default": "Lo siento, no tengo una respuesta directa para eso en este momento. Mi función principal es asistirte con consultas bancarias comunes. ¿Hay algo más en lo que pueda ayudarte con las opciones que te presento?"
        };

        // --- Manejadores de Eventos de Interacción ---

        /**
         * Maneja el clic en un botón de opción.
         * @param {string} optionValue El valor de la opción seleccionada.
         */
        function handleOptionClick(optionValue) {
            addMessage(optionValue, 'user'); // Muestra la opción seleccionada como mensaje del usuario

            // Limpia el input de texto después de una selección de opción
            const currentChatInput = document.getElementById('chatInput');
            if (currentChatInput) {
                currentChatInput.value = '';
            }

            const botResponseData = responses[optionValue] || responses.default;
            const botResponseText = botResponseData.response || botResponseData; // Usa la propiedad 'response' si existe

            addMessage(botResponseText, 'bot');

            if (botResponseData.action === "openTurnoGenerator") {
                setTimeout(() => {
                    // Envía un mensaje al padre para que abra el generador de turnos
                    window.parent.postMessage({ type: 'openTurnoGenerator' }, '*');
                    // Después de la acción, volvemos a modo de texto para preguntas de seguimiento
                    switchToTextInputMode();
                    addMessage("¿En qué más puedo ayudarte después de gestionar tu turno? Por favor, escribe tu consulta.", 'bot');
                }, 500);
            } else if (botResponseData.nextAction === "showInitialOptions") {
                // Si la respuesta indica mostrar opciones iniciales
                setTimeout(() => {
                    addMessage("¿Hay algo más en lo que pueda ayudarte?", 'bot');
                    renderOptions(responses.initial); // Muestra las opciones iniciales de nuevo
                }, 1000);
            } else {
                // Comportamiento por defecto: muestra las opciones iniciales después de un retraso
                setTimeout(() => {
                    addMessage("¿Hay algo más en lo que pueda ayudarte?", 'bot');
                    renderOptions(responses.initial);
                }, 1000);
            }
        }

        /**
         * Maneja el envío de un mensaje de texto por el usuario.
         */
        function sendMessage() {
            const currentChatInput = document.getElementById('chatInput');
            if (!currentChatInput) return; // Si no hay input, sale

            const messageText = currentChatInput.value.trim();
            if (messageText === '') return; // No envía mensajes vacíos

            addMessage(messageText, 'user'); // Muestra el mensaje del usuario
            currentChatInput.value = ''; // Limpia el input

            setTimeout(() => {
                let botResponseText = responses.default;

                // Lógica de respuesta básica basada en palabras clave
                if (messageText.toLowerCase().includes("saldo")) {
                    botResponseText = responses["Consultar Saldo"].response;
                } else if (messageText.toLowerCase().includes("crédito") || messageText.toLowerCase().includes("inversión")) {
                    botResponseText = responses["Créditos e Inversiones"].response;
                } else if (messageText.toLowerCase().includes("horario")) {
                    botResponseText = responses["Horarios de Atención"].response;
                } else if (messageText.toLowerCase().includes("turno")) {
                    botResponseText = "Claro, para sacar un turno, por favor, selecciona la opción 'Sacar Turno' de las opciones iniciales.";
                } else if (messageText.toLowerCase().includes("gracias") || messageText.toLowerCase().includes("adios") || messageText.toLowerCase().includes("gracías")) {
                    botResponseText = "¡De nada! Es un placer ayudarte. Que tengas un excelente día.";
                }

                addMessage(botResponseText, 'bot');
                // Después de un mensaje de texto libre, siempre volver al modo de opciones
                setTimeout(() => {
                    addMessage("¿Hay algo más en lo que pueda ayudarte?", 'bot');
                    renderOptions(responses.initial);
                }, 1000);

            }, 500); // Pequeño retraso para la respuesta del bot
        }

        // --- Inicialización ---

        // Configuración inicial cuando el chatbot carga
        document.addEventListener('DOMContentLoaded', () => {
            renderOptions(responses.initial); // Muestra las opciones iniciales al cargar
        });
    </script>
</body>
</html>