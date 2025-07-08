<?php
require_once(__DIR__ . '../../../Config/config.php');

if (isset($_GET['login']) && $_GET['login'] == 'success') {
    $user = htmlspecialchars($_SESSION['nombre'] ?? 'Asesor');
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('✅ Operación Exitosa', 'Bienvenido @$user.', 'success');
            });
          </script>";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Créditos y Clientes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/amortizacion.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Estilos base para los modales */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posición fija en la pantalla */
            z-index: 1000; /* Por encima de todo */
            left: 0;
            top: 0;
            width: 100%; /* Ancho completo */
            height: 100%; /* Alto completo */
            overflow: auto; /* Habilitar scroll si el contenido es muy largo */
            background-color: rgba(0,0,0,0.7); /* Fondo semi-transparente */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border: 1px solid #888;
            width: 90%; /* Ancho responsivo */
            max-width: 800px; /* Ancho máximo */
            border-radius: 8px;
            position: relative;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation-name: animatetop;
            animation-duration: 0.4s
        }
        @keyframes animatetop {
            from {top: -300px; opacity: 0}
            to {top: 0; opacity: 1}
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .main-content-area {
            padding: 20px;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative; /* Para el icono de estado */
        }
        .info-box.missing {
            border-color: #fca5a5; /* Rojo claro para indicar falta */
            background-color: #fee2e2; /* Fondo rojo claro */
        }
        .info-box.completed {
            border-color: #86efac; /* Verde claro para indicar completado */
            background-color: #dcfce7; /* Fondo verde claro */
        }
        .info-box h3 {
            margin-top: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-icon {
            font-size: 20px;
            line-height: 1;
        }
        .status-icon.missing { color: #ef4444; } /* Rojo */
        .status-icon.completed { color: #22c55e; } /* Verde */

        /* Colores naranjas y verdes */
        .bg-orange-500 { background-color: #f97316; }
        .hover\:bg-orange-700:hover { background-color: #c2410c; }
        .bg-lime-500 { background-color: #84cc16; }
        .hover\:bg-lime-700:hover { background-color: #4d7c0f; }
        .bg-purple-600 { background-color: #9333ea; } /* Mantener para finalizar proceso */
        .hover\:bg-purple-800:hover { background-color: #6b21a8; }


    /* Botón abrir modal */
    #abrirModal {
      padding: 12px 25px;
      font-size: 16px;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100vw;
      height: 100vh;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-contenido {
      background-color: white;
      margin: 5% auto;
      padding: 30px;
      border: 2px solid orange;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 0 10px limegreen;
      position: relative;
    }

    .modal h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1.5px solid orange;
      border-radius: 5px;
    }

    input::placeholder {
      color: #999;
    }

    button[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: orange;
      color: white;
      border: none;
      margin-top: 20px;
      font-weight: bold;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .cerrar {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 22px;
      font-weight: bold;
      color: #999;
      cursor: pointer;
    }

    .resultado {
      margin-top: 20px;
      background: #f9f9f9;
      padding: 15px;
      border-radius: 8px;
      font-size: 16px;
      border: 1px solid #ddd;
    }

    .resultado strong {
      color: #007BFF;
    }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../public/layout/barraNavAsesor.php'; ?>

    <div class="main-content-area">
        <h1 class="text-3xl font-bold mb-6 text-center">Gestión de Créditos y Clientes</h1>

        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <button id="btnGenerarSimulacion" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                Generar Simulación
            </button>
            <button id="btnAsociarCliente" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                Crear Cliente
            </button>
            <button id="btnSeleccionarProducto" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                Seleccionar Producto
            </button>
            <button id="abrirModal" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                🧮Capacidad de Endeudamiento</button>
        </div>

        <div id="statusSection" class="mb-8">
            <div id="simulationStatus" class="info-box missing">
                <h3><span class="status-icon missing">❌</span> Simulación de Amortización: <span class="font-normal text-red-600" id="simStatusText">Pendiente</span></h3>
                <div id="displaySimulationData" class="mt-2 hidden">
                    <p><strong>Monto:</strong> <span id="displayMonto"></span></p>
                    <p><strong>Cuotas:</strong> <span id="displayCuotas"></span></p>
                    <p><strong>Tasa Anual:</strong> <span id="displayTasaAnual"></span></p>
                    <p><strong>Periodicidad:</strong> <span id="displayPeriodicidad"></span></p>
                    <p><strong>Valor Cuota:</strong> <span id="displayValorCuota"></span></p>
                    <div id="displayTablaAmortizacion" class="mt-4 overflow-x-auto"></div>
                </div>
            </div>

            <div id="clientStatus" class="info-box missing">
                <h3><span class="status-icon missing">❌</span> Datos del Cliente: <span class="font-normal text-red-600" id="clientStatusText">Pendiente</span></h3>
                <div id="displayClientData" class="mt-2 hidden">
                    <p><strong>Nombre:</strong> <span id="displayClientNombre"></span> <span id="displayClientApellido"></span></p>
                    <p><strong>Documento:</strong> <span id="displayClientDocumentoTipo"></span> <span id="displayClientDocumentoNum"></span></p>
                    <p><strong>Celular:</strong> <span id="displayClientCelular"></span></p>
                    <p><strong>Correo:</strong> <span id="displayClientCorreo"></span></p>
                </div>
            </div>

            <div id="productStatus" class="info-box missing">
                <h3><span class="status-icon missing">❌</span> Producto de Crédito: <span class="font-normal text-red-600" id="productStatusText">Pendiente</span></h3>
                <div id="displayProductData" class="mt-2 hidden">
                    <p><strong>Nombre del Producto:</strong> <span id="displayProductName"></span></p>
                    <p><strong>Descripción:</strong> <span id="displayProductDesc"></span></p>
                    <p><strong>Monto:</strong> <span id="displayProductMontoMin"></span> - <span id="displayProductMontoMax"></span></p>
                    <p><strong>Plazo:</strong> <span id="displayProductPlazoMin"></span> - <span id="displayProductPlazoMax"></span></p>
                </div>
            </div>
        </div>

        <div id="finalProcessSection" class="flex justify-center mt-8">
           <button id="btnFinalizarProceso" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg hidden">
                Finalizar Proceso (Guardar Todo)
            </button>
        </div>
    </div>

    <div id="simulacionModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('simulacionModal')">&times;</span>
            <h2 class="text-2xl font-bold mb-4 text-center">Simulador de Tabla de Amortización</h2>
            <form id="formularioAmortizacion" class="formulario-banco">
                <label for="monto">Valor del Préstamo (COP)</label>
                <input type="number" id="monto" name="monto" placeholder="Ej. 1.000.000" required>

                <label for="cuotas">Número de Cuotas</label>
                <input type="number" id="cuotas" name="cuotas" placeholder="Ej. 12" required>

                <label for="tasaAnual">Tasa de Interés Anual (%)</label>
                <input type="number" id="tasaAnual" name="tasaAnual" step="0.01" placeholder="Ej. 15.5" required>

                <label for="periodicidad">Periodicidad</label>
                <select id="periodicidad" name="periodicidad" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="Diaria">Diaria</option>
                    <option value="Mensual">Mensual</option>
                    <option value="Bimensual">Bimestral</option>
                    <option value="Trimestral">Trimestral</option>
                    <option value="Semestral">Semestral</option>
                    <option value="Anual">Anual</option>
                </select>

                <button type="submit" class="mt-4 bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Calcular Simulación</button>
            </form>

            <div id="resultadoSimulacionModal" class="mt-6">
                <div class="output" id="tasaPeriodicaModal"></div>
                <div class="output" id="valorCuotaModal"></div>
                <div id="tabla-amortizacion-modal" class="mt-4 overflow-x-auto"></div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button id="btnUsarSimulacion" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded hidden">
                    Utilizar esta Simulación
                </button>
                <button onclick="closeModal('simulacionModal')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <div id="clienteModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('clienteModal')">&times;</span>
            <h2 class="text-2xl font-bold mb-4 text-center">Registro de Cliente</h2>
            <form id="formularioCliente" action="#" method="POST" class="formulario-banco">
                <input type="hidden" name="idTurno" id="idTurnoHidden" value="<?= htmlspecialchars($turnoAtender['ID_Turno'] ?? '') ?>">

                <div class="fila">
                    <div class="columna">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" id="clientNombre" placeholder="Ej. Carlos" required
                                value="<?= htmlspecialchars(explode(' ', $turnoAtender['Nombre_Completo_Solicitante'] ?? '')[0] ?? '') ?>">

                        <label>Género:</label>
                        <select name="genero" id="clientGenero" required>
                            <option value="" disabled selected>Seleccione el género</option>
                            <option value="1">Hombre</option>
                            <option value="2">Mujer</option>
                            <option value="3">Otro</option>
                        </select>

                        <label>Número de Documento:</label>
                        <input type="text" name="documento" id="clientDocumento" placeholder="Ej. 1234567890" required
                                value="<?= htmlspecialchars($turnoAtender['N_Documento_Solicitante'] ?? '') ?>">

                        <label>Correo:</label>
                        <input type="email" name="correo" id="clientCorreo" placeholder="Ej. usuario@correo.com">

                        <label>Ciudad:</label>
                        <input type="text" name="ciudad" id="clientCiudad" placeholder="Ej. Medellín">

                        <label>Contraseña:</label>
                        <input type="password" name="contrasena" id="clientContrasena" placeholder="Crea una contraseña segura" required>
                    </div>

                    <div class="columna">
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="clientApellido" placeholder="Ej. Ramírez" required
                                value="<?= htmlspecialchars(implode(' ', array_slice(explode(' ', $turnoAtender['Nombre_Completo_Solicitante'] ?? ''), 1))) ?>">

                        <label>Tipo Documento:</label>
                        <select name="tipo_documento" id="clientTipoDocumento" required>
                            <option value="" disabled selected>Seleccionar el tipo de documento</option>
                            <option value="1">Cédula de ciudadanía</option>
                            <option value="2">Tarjeta de identidad</option>
                            <option value="3">Cédula extranjera</option>
                        </select>

                        <label>Celular:</label>
                        <input type="text" name="celular" id="clientCelular" placeholder="Ej. 3001234567">

                        <label>Dirección:</label>
                        <input type="text" name="direccion" id="clientDireccion" placeholder="Ej. Cra 45 #10-15">

                        <label>Fecha de Nacimiento:</label>
                        <input type="date" name="fecha_nacimiento" id="clientFechaNacimiento">
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                        Registrar Cliente
                    </button>
                    <button type="button" onclick="closeModal('clienteModal')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="productosModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('productosModal')">&times;</span>
            <h2 class="text-xl font-semibold mb-4">Seleccionar Producto</h2>
            <input type="text" id="filtroProducto" placeholder="Buscar por nombre..." class="mb-4 p-2 border w-full rounded">
            <div class="overflow-y-auto" style="max-height: 400px;">
                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-green-200">
                            <th class="px-4 py-2 bg-orange-500">ID</th>
                            <th class="px-4 py-2 bg-orange-500">Nombre</th>
                            <th class="px-4 py-2 bg-orange-500">Descripción</th>
                            <th class="px-4 py-2 bg-orange-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="border px-4 py-2"><?= htmlspecialchars($producto['ID_Producto']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($producto['Nombre_Producto']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($producto['Descripcion_Producto']) ?></td>
                                    <td class="border px-4 py-2">
                                        <button
                                            type="button"
                                            onclick="seleccionarProducto(<?= htmlspecialchars(json_encode($producto), ENT_QUOTES, 'UTF-8') ?>)"
                                            class="bg-orange-500 hover:bg-orange-700 text-white px-3 py-1 rounded"
                                        >Seleccionar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">No hay productos disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal('productosModal')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
   
<!-- Modal -->
<div id="miModal" class="modal">
  <div class="modal-contenido">
    <span class="cerrar" id="cerrarModal">&times;</span>

    <form id="formulario">
      <h2> Capacidad de Endeudamiento</h2>

      <label for="nombre">Nombre del Cliente</label>
      <input type="text" id="nombre" required>

      <label for="id">Identificación</label>
      <input type="text" id="id" placeholder="ingrese CC" required>

      <label for="fecha">Fecha</label>
      <input type="date" id="fecha" required>

      <label for="ingresos">Ingresos Mensuales (COP)</label>
      <input type="number" id="ingresos"  required>

      <label for="gastos">Gastos Mensuales (COP)</label>
      <input type="number" id="gastos" required>

      <label for="deudas">Deudas Actuales (COP)</label>
      <input type="number" id="deudas" required>

      <button type="submit">Calcular</button>
    </form>

    <div id="resultado" class="resultado" style="display: none;"></div>
  </div>
</div>
    <script>
        window.initialProductFromTurn = <?= isset($productoInteres) && !empty($productoInteres) ? json_encode($productoInteres) : 'null' ?>;

        window.initialClientDocumentFromTurn = '<?= isset($turnoAtender["numero_documento"]) ? htmlspecialchars($turnoAtender["numero_documento"]) : "" ?>';
        window.initialClientNameFromTurn = '<?= isset($turnoAtender["nombre_cliente"]) ? htmlspecialchars($turnoAtender["nombre_cliente"]) : "" ?>';
        window.initialClientLastNameFromTurn = '<?= isset($turnoAtender["apellido_cliente"]) ? htmlspecialchars($turnoAtender["apellido_cliente"]) : "" ?>';

    </script>


    <script src="<?= BASE_URL ?>/View/public/assets/js/scripts.js"></script>

    <?php include __DIR__ . '/../public/layout/frontendBackend.php'; ?>
    <?php include __DIR__ . '/../public/layout/layoutfooter.php'; ?>    
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>




 <script>
  // Mostrar modal
  document.getElementById("abrirModal").addEventListener("click", function () {
    document.getElementById("miModal").style.display = "block";
  });

  // Cerrar modal
  document.getElementById("cerrarModal").addEventListener("click", function () {
    document.getElementById("miModal").style.display = "none";
    document.getElementById("resultado").style.display = "none";
    document.getElementById("formulario").reset();
  });

  // Calcular
  document.getElementById("formulario").addEventListener("submit", function (e) {
    e.preventDefault();

    const ingresos = parseFloat(document.getElementById("ingresos").value);
    const gastos = parseFloat(document.getElementById("gastos").value);
    const deudas = parseFloat(document.getElementById("deudas").value);

    const capacidadPago = ingresos - gastos;
    const capacidadEndeudamiento = (capacidadPago * 0.30) - deudas;

    const resultadoDiv = document.getElementById("resultado");
    resultadoDiv.style.display = "block";
    resultadoDiv.innerHTML = `
      ✅ <strong>Capacidad de Pago:</strong> $${capacidadPago.toLocaleString('es-CO')}<br>
      💰 <strong>Capacidad de Endeudamiento:</strong> $${(capacidadEndeudamiento > 0 ? capacidadEndeudamiento : 0).toLocaleString('es-CO')}
    `;
  });
</script>

</body>
</html>